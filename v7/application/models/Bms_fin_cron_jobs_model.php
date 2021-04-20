<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_cron_jobs_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function getPropertiesForAutoBilling () {
        $sql = "SELECT billing_cycle, calcul_base, sinking_fund, property_abbrev, email_addr, property_id, sc_charge, 
                bill_due_days, jmb_mc_name, property_name, address_1, address_2, pin_code, sc_charged_date, bill_generate_date   
                FROM bms_property a
                WHERE property_status = '1' AND account_status = '1' 
                AND ( (sc_charged_date IS NULL AND bill_generate_date = '" . date('Y-m-d') . "') 
                OR (sc_charged_date IS NOT NULL AND sc_charged_date < '" . date('Y-m-d') . "' ) ) ";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getPropertiesForAutoBillingByProperty ($property_id) {
        $sql = "SELECT billing_cycle, calcul_base, sinking_fund, property_abbrev, bill_due_days, email_addr, 
                property_id, sc_charge, sinking_fund, jmb_mc_name, property_name, address_1, address_2, pin_code  
                FROM bms_property a
                WHERE property_status = '1' AND property_id = ". $property_id;

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getPropertyUnitsForAutoBilling ( $property_id ) {
        $sql = "SELECT a.state, a.country, a.unit_id, a.unit_no, a.block_id, b.tier_id, b.tier_value, a.unit_no, a.owner_name, c.country_name, d.state_name,
              a.square_feet, a.share_unit, a.email_addr
                FROM  bms_property_units a
                LEFT JOIN bms_property_tier b ON a.tier_id = b.tier_id
                LEFT JOIN bms_countries c ON a.country = c.country_id
                LEFT JOIN bms_state d ON a.state = d.state_id        
                WHERE a.property_id = '$property_id'";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getPropertyUnitsForAutoBilling_kiaraeast ( $property_id ) {
        $sql = "SELECT a.state, a.country, a.unit_id, a.unit_no, a.block_id, b.tier_id, b.tier_value, a.unit_no, a.owner_name, c.country_name, d.state_name,
              a.square_feet, a.share_unit, a.email_addr
                FROM  bms_property_units a
                LEFT JOIN bms_property_tier b ON a.tier_id = b.tier_id
                LEFT JOIN bms_countries c ON a.country = c.country_id
                LEFT JOIN bms_state d ON a.state = d.state_id        
                WHERE a.property_id = '$property_id' 
                AND a.square_feet > 0;";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getServiceChargeId ( $property_id, $item ) {
        $sql = "SELECT coa_id, coa_name
                FROM  bms_fin_coa
                WHERE property_id = '$property_id' AND $item = 1";

        $query = $this->db->query( $sql );
        return $query->row();
    }

    function insertBill ($data) {
        $this->db->insert('bms_fin_bills', $data);
        return $this->db->insert_id();
    }

    function update_property ($data,$property_id) {
        $this->db->update('bms_property', $data, array('property_id' => $property_id));
    }

    function get_properties_for_outstanding_reminder () {
        $sql = "SELECT property_id, property_name,property_abbrev,total_units,jmb_mc_name, property_type,email_addr, a.acb_block_card,
                calcul_base,sinking_fund,billing_cycle,insur_prem_date,quit_rent_paid_on,e_billing_start_date,
                address_1,address_2,phone_no,phone_no2,fax,pin_code,city,state_id,country_id,
                late_payment,late_pay_percent,late_pay_effect_from,late_pay_grace_type,late_pay_grace_value,monthly_billing,
                water,water_min_charg, water_charge_per_unit_rate_1, water_charge_per_unit_rate_2, water_charge_range, 
                acb_grace_type, acb_grace_value, acb_unblock_charges_type, acb_unblock_charges_value, 
                payment_cc_card, bill_due_days, billing_cycle
                vms_status, vms_access, sc_charge, account_status, sc_charged_date, bill_generate_date, sinking_fund
                FROM bms_property a
                WHERE a.property_status = '1' AND a.account_status = '1' 
                AND a.acb_grace_type in (1,2);";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_units_with_outstanding_bills ($property_id) {
        $sql = "SELECT sum(c.bal_amount) as outstanding, b.bill_id, b.unit_id, b.bill_due_date FROM `bms_property_units` a
        LEFT JOIN bms_fin_bills b ON a.`unit_id` = b.`unit_id`
        LEFT JOIN bms_fin_bill_items c ON b.`bill_id` = c.`bill_id`
        WHERE c.`paid_status` = 0 AND a.`property_id` = $property_id
        GROUP BY b.`bill_id`
        ORDER BY a.`unit_id`";
        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_outstanding_bills_for_first_reminder ( $bill_date, $unit_id ) {
        $sql = "SELECT * from bms_fin_bills
        where bill_due_date < '$bill_date'
        AND unit_id = $unit_id
        AND bill_paid_status = '0'";
        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_last_reminder_date ( $unit_id ) {
        $sql = "SELECT max(reminder1_date) as reminder1_date from bms_fin_reminder
        where unit_id = '$unit_id' limit 1;";
        $query = $this->db->query( $sql );
        return $query->row_array();
    }

    function get_current_first_reminder_date ( $last_reminder_date, $unit_id ) {
        $sql = "SELECT * from bms_fin_bills where bill_due_date > '$last_reminder_date' and unit_id = '$unit_id';";
        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_properties_for_fire_insurance () {
        $sql = "SELECT property_id, property_name,property_abbrev,total_units,jmb_mc_name, property_type,email_addr,
                calcul_base,sinking_fund,insur_prem_date,quit_rent_paid_on,e_billing_start_date,
                address_1,address_2,phone_no,phone_no2,fax,pin_code,city,state_id,country_id,
                late_payment,late_pay_percent,late_pay_effect_from,late_pay_grace_type,late_pay_grace_value,monthly_billing,
                water,water_min_charg, water_charge_per_unit_rate_1, water_charge_per_unit_rate_2, water_charge_range, 
                acb_grace_type, acb_grace_value, acb_unblock_charges_type, acb_unblock_charges_value, 
                payment_cc_card, bill_due_days,
                vms_status, vms_access, sc_charge, account_status, insurance_prem  
                FROM bms_property a
                WHERE property_status = '1' AND account_status = '1' 
                AND insur_prem_date = '" . date('Y-m-d') ."' 
                AND insurance_prem > 0 ";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_properties_for_quit_rent () {
        $sql = "SELECT property_id, property_name,property_abbrev,total_units,jmb_mc_name, property_type,email_addr,
                calcul_base,sinking_fund,insur_prem_date,quit_rent_paid_on,e_billing_start_date,
                address_1,address_2,phone_no,phone_no2,fax,pin_code,city,state_id,country_id,
                late_payment,late_pay_percent,late_pay_effect_from,late_pay_grace_type,late_pay_grace_value,monthly_billing,
                water,water_min_charg, water_charge_per_unit_rate_1, water_charge_per_unit_rate_2, water_charge_range, 
                acb_grace_type, acb_grace_value, acb_unblock_charges_type, acb_unblock_charges_value, 
                payment_cc_card, bill_due_days,
                vms_status, vms_access, sc_charge, account_status, quit_rent  
                FROM bms_property a
                WHERE property_status = '1' AND account_status = '1' 
                AND quit_rent_paid_on = '" . date('Y-m-d') ."' 
                AND quit_rent > 0 ";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getBillsList ($unit_id, $email_status = '') {
        $cond = '';
        if ( $unit_id != '' ) {
            $cond .= " AND a.unit_id = ".$unit_id;
        }

        if ( $email_status === 0 ) {
            $cond .= " AND a.email_status = 0";
        }

        $sql = "SELECT a.bill_id,a.bill_no, DATE_FORMAT(a.bill_date,'%d-%m-%Y') as bill_date, DATE_FORMAT(a.bill_due_date,'%d-%m-%Y') as bill_due_date,a.total_amount,a.bill_paid_status,
                b.property_name,c.unit_no,c.owner_name,d.total_amount AS cn_amt
                FROM bms_fin_bills a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_fin_credit_note d ON d.invoice_id = a.bill_id                 
                WHERE 1=1 ". $cond ;

        $query = $this->db->query($sql);
        return $data = $query->result_array();

    }

    function getPropertiesForEmailInvoicing () {
        $sql = "SELECT a.billing_cycle, a.calcul_base, a.sinking_fund, a.property_abbrev, a.email_addr, a.property_id, a.sc_charge, 
                a.bill_due_days, a.jmb_mc_name, a.property_name, a.address_1, a.address_2, a.pin_code, a.sc_charged_date, a.bill_generate_date   
                FROM bms_property a
                LEFT JOIN bms_fin_bills b on a.property_id = b.property_id
                WHERE a.property_status = '1' AND a.account_status = '1' 
                AND b.email_status = 0 ;";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function getPropertyUnitsForEmailInvoicing ($property_id) {

        $sql = "SELECT a.unit_id, a.owner_name, CONCAT(a.address_1, ' ', a.address_2) as owner_address, a.email_addr as owner_email_addr, a.unit_no, a.unit_id, a.valid_email
        FROM bms_property_units a
        LEFT JOIN bms_fin_bills b on a.unit_id = b.unit_id  
        WHERE 1 = 1
        AND a.property_id = '$property_id'
        AND b.email_status = 0" ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_bms_property_unit ($data, $unit_id) {
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id));
    }

    function setValidEmailByEmailAddress ($invalid_email_list) {
        if ( !empty ( $email_addresses ) ) {
            $sql = "UPDATE bms_property_units SET valid_email = 0 WHERE email_addr IN ($invalid_email_list)" ;
            $query = $this->db->query($sql);
            return $query->result_array();
        }
    }
}