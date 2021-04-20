<?php
defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_ap_cn_dn_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function getServiceProvider ($property_id) {
        $sql = "SELECT service_provider_id,property_id,provider_name,a.service_provider_cat_id,
                address,postcode,city,state,country,contractual,office_ph_no,
                person_incharge,person_inc_mobile,person_inc_email,job_scope, b.country_name
                FROM bms_service_provider a 
                LEFT JOIN bms_countries b ON b.country_id = a.country
                WHERE property_id=".$property_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPropertyAssets ($property_id) {
        $sql = "SELECT asset_id,asset_name,asset_location 
                FROM bms_property_assets a                
                WHERE property_id=".$property_id ." ORDER BY asset_name";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getCnList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';

        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(c.provider_name) LIKE '%".$search_txt."%' OR LOWER(c.person_incharge) LIKE '%".$search_txt."%' OR LOWER(a.credit_note_date) LIKE '%".$search_txt."%' OR LOWER(a.total_amount) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT a.ap_cr_id, a.ap_cr_no, a.credit_note_date, a.total_amount,c.service_provider_id,c.provider_name,c.person_incharge,d.exp_inv_no, d.exp_doc_no, d.paid_amount, d.balance_amount
                FROM bms_fin_ap_credit_note a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_service_provider c ON c.service_provider_id = a.service_provider_id
                LEFT JOIN bms_fin_expense_invoice d ON d.exp_inv_id = a.invoice_id
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        $order_by = " ORDER BY invoice_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);

        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);

    }

    function getInvoiceOrderitems ($exp_inv_id) {
        $sql = "SELECT exp_item_id,exp_id,description,unit_price,net_amount as amount,c.coa_id,CONCAT( c.coa_name, ' ' , c.coa_code ) as coa_name,
                b.paid_amount, b.balance_amount, a.paid_amount as item_paid_amount, a.balance_amount as item_balance_amount 
                FROM bms_fin_exp_inv_items a INNER JOIN bms_fin_expense_invoice b
                LEFT JOIN bms_fin_coa c ON c.coa_id = a.coa_id
                WHERE b.exp_inv_id=a.exp_id and exp_id =".$exp_inv_id ;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getSalesItemsForBill ($property_id) {
        $sql = "SELECT coa_id, coa_name,period
                FROM bms_fin_coa b
                WHERE b.property_id = ".$property_id." AND payment_enabled=1 ORDER BY coa_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_ap_credit_note ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_ap_credit_note', $data, array('ap_cr_id' => $id));
    }

    function get_ap_cr_no ($ap_cr_no_format) {
        $sql = "SELECT ap_cr_no FROM bms_fin_ap_credit_note WHERE ap_cr_no LIKE '".$ap_cr_no_format."%' ORDER BY ap_cr_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }

    function insert_ap_credit_note ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_ap_credit_note', $data);
        return $this->db->insert_id();
    }

    function update_ap_credit_note_items ($data,$id) {
        $this->db->update('bms_fin_ap_credit_note_items', $data,  array('ap_cr_item_id' => $id));
    }

    function insert_ap_credit_note_items ($data) {
        $this->db->insert('bms_fin_ap_credit_note_items', $data);
    }

    function get_expense_invoice_detail ($exp_inv_id) {
        $sql = "SELECT total, paid_amount, balance_amount, exp_inv_id FROM bms_fin_expense_invoice WHERE exp_inv_id = '$exp_inv_id';";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row();
    }

    function get_ap_cn_details ($ap_cr_id) {

        $sql = "SELECT ap_cr_id,a.property_id,b.property_name,
                b.property_name,b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,b.city,e.state_name,f.country_name,
                ap_cr_no,
                credit_note_date,remarks,total_amount,remarks,a.created_date,d.first_name,d.last_name
                FROM bms_fin_ap_credit_note a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id             
                WHERE ap_cr_id =".$ap_cr_id;

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function get_cn_items_detail ($ap_cr_id) {
        $sql = "SELECT ap_cr_item_id,ap_cr_id,a.coa_id,
                b.coa_name,item_descrip,item_amount,adj_amount,bal_amount
                FROM bms_fin_ap_credit_note_items a
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.coa_id
                WHERE ap_cr_id =".$ap_cr_id;

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function getDnList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';

        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.ap_dn_no) LIKE '%".$search_txt."%' OR LOWER(d.pay_no) LIKE '%".$search_txt."%' OR LOWER(a.debit_note_date) LIKE '%".$search_txt."%' OR LOWER(b.property_name) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT a.ap_dn_id, a.ap_dn_no, a.debit_note_date,c.service_provider_id,c.provider_name,c.person_incharge,d.pay_no, d.pay_total, d.pay_id, b.property_name
                FROM bms_fin_ap_debit_note a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_service_provider c ON c.service_provider_id = a.service_provider_id
                LEFT JOIN bms_fin_payment d ON d.pay_id = a.pay_id
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        $order_by = " ORDER BY a.debit_note_date DESC".$limit;
        $query = $this->db->query($sql.$order_by);

        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);

    }

    function get_pay_id ($pay_service_provider_id) {
        $sql = "SELECT pay_inv_id, pay_no, pay_total, pay_id  
                FROM bms_fin_payment
                WHERE 1=1 
                AND pay_id NOT IN 
                (SELECT pay_id FROM bms_fin_ap_debit_note WHERE service_provider_id =" . $pay_service_provider_id . ") AND pay_service_provider_id =".$pay_service_provider_id;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_ap_debit_note ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_ap_debit_note', $data, array('ap_cr_id' => $id));
    }

    function get_ap_dn_no ($ap_dn_no_format) {
        $sql = "SELECT ap_dn_no FROM bms_fin_ap_debit_note WHERE ap_dn_no LIKE '".$ap_dn_no_format."%' ORDER BY ap_dn_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function insert_ap_debit_note ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_ap_debit_note', $data);
        return $this->db->insert_id();
    }

    function get_payment_detail ($pay_id) {
        $sql = "SELECT pay_inv_id, pay_total, bank_id FROM bms_fin_payment WHERE pay_id = '".$pay_id."' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_invoice_detail ($exp_inv_id) {
        $sql = "SELECT paid_amount, balance_amount FROM bms_fin_expense_invoice WHERE exp_inv_id = '".$exp_inv_id."' LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_ap_dn_details ($ap_dn_id) {

        $sql = "SELECT ap_dn_id,a.property_id,b.property_name, b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,b.city,e.state_name,f.country_name, ap_dn_no,
                debit_note_date,remarks,total_amount,remarks,a.created_date,d.first_name,d.last_name
                FROM bms_fin_ap_debit_note a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id             
                WHERE ap_dn_id =".$ap_dn_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function get_dn_items_detail ($ap_dn_id) {
        $sql = "SELECT b.coa_id, b.coa_name, description,net_amount
                FROM bms_fin_ap_debit_note_items a
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.coa_id
                WHERE ap_dn_id =".$ap_dn_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function insert_ap_debit_note_items ($data) {
        $insQuery = "INSERT INTO bms_fin_ap_debit_note_items(ap_dn_id, description,qty,uom,unit_price,amount,discount_amt,net_amount,asset_id,tax_percent,tax_amt,coa_id,exp_inv_item_id, pay_item_id) 
                     select " . $data['ap_dn_id'] . ", pay_description, pay_qty, pay_uom, pay_unit_price, pay_amount, pay_discount_amt, pay_net_amount, pay_asset_id, pay_tax_percent, pay_tax_amt, pay_coa_id, exp_inv_item_id, pay_item_id from bms_fin_payment_items where pay_item_id= " . $data['pay_item_id'];
        $this->db->query ( $insQuery );
    }

    function get_payment_items_detail ($pay_id) {
        $sql = "SELECT a.pay_item_id, a.pay_net_amount, a.exp_inv_item_id, b.exp_item_id, b.paid_status, b.paid_amount, b.balance_amount
                FROM bms_fin_payment_items a
                LEFT JOIN bms_fin_exp_inv_items b ON a.exp_inv_item_id = b.exp_item_id
                WHERE a.pay_id =".$pay_id;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function get_exp_inv_no ($service_provider_id) {
        $sql = "SELECT exp_inv_id, exp_inv_no 
                FROM bms_fin_expense_invoice
                WHERE inv_paid_status = 0 and service_provider_id =".$service_provider_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_exp_inv_items ($exp_item_id) {
        $sql = "SELECT paid_amount, balance_amount, exp_item_id 
                FROM bms_fin_exp_inv_items
                WHERE exp_item_id =" . $exp_item_id . ' Limit 1';
        $query = $this->db->query($sql);
        return $query->row();
    }
}