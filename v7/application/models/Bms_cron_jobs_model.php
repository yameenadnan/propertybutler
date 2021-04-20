<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_cron_jobs_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function getAgms () {
        $sql = "SELECT agm_id,a.property_id,b.property_name,agm_type,agm_last_date,agm_date,b.email_addr 
                FROM bms_agm a
                LEFT JOIN bms_property b ON b.property_id = a.property_id 
                WHERE  b.property_status = 1 AND  
                ((agm_date = '0000-00-00' AND TIMESTAMPDIFF(MONTH, '".date('Y-m-d')."', (DATE_ADD(agm_last_date, INTERVAL 12 MONTH) - INTERVAL 1 DAY)) between 0 and 3)
                OR (agm_date <> '0000-00-00' AND TIMESTAMPDIFF(MONTH, '".date('Y-m-d')."', agm_date) between 0 and 3))";
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";exit;
        return $query->result_array();
    }

    function getAgmChecklist ($agm_id) {
        $sql = "SELECT agm_checklist_id,agm_id,agm_responsibility FROM bms_agm_checklist 
                WHERE agm_id=".$agm_id." AND agm_checklist_status IS NULL ";

        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function getAgmreminders ($agm_checklist_id) {
        $sql = "SELECT remind_before,email_content,email_staff,email_jmb FROM bms_agm_checklist_reminder 
                WHERE agm_checklist_id=".$agm_checklist_id;
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_unit_email () {
        $sql = "SELECT a.email_addr,a.unit_id,a.property_id 
                FROM bms_property_units a LEFT JOIN bms_property b ON b.property_id = a.property_id 
                WHERE b.property_status=1 AND b.`property_id`in (161,163,164,165,166)
                GROUP BY a.email_addr,b.property_name HAVING COUNT(a.email_addr) =1
                ORDER BY a.unit_id ASC";
                
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function set_email_addr_invalid ($data) {
        $this->db->insert('bms_email_addr_invalid', $data);
    }
    
    function set_email_not_send ($data) {
        $this->db->insert('bms_email_not_send', $data);
    }

    function getPropertyList () {
        $sql = "SELECT property_id, property_name, email_addr FROM `bms_property`
        WHERE property_id IN ( SELECT property_id FROM `bms_service_provider` 
        WHERE contractual = '1' 
        AND contract_end_date BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))) . "' ) AND property_status = '1'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getServiceProviderList ( $property_id ) {
        $sql = "SELECT remind_before, contract_end_date, service_provider_id, person_inc_email, provider_name FROM `bms_service_provider`
        WHERE property_id = '$property_id'
        AND contract_end_date != '0000-00-00'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getAssetList () {

        $sql = "SELECT a.asset_id, a.service_date, a.service_reminder, b.asset_name, c.property_name, c.property_id, e.person_inc_email, e.person_incharge  
        FROM `bms_asset_service_schedule` a
        LEFT JOIN `bms_property_assets` b ON a.`asset_id` = b.`asset_id`
        LEFT JOIN `bms_property` c ON b.`property_id` = c.`property_id`
        LEFT JOIN `bms_prop_asset_maint_comp` d ON a.`asset_id` = d.`asset_id`
        LEFT JOIN `bms_service_provider` e ON d.`service_provider_id` = e.`service_provider_id`
        WHERE 1=1 AND a.service_date BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))) . "'
        AND c.`property_status` = 1";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPropertyListForAnnualRenewal () {
        $sql = "SELECT a.license_expiry_date, a.item_descrip, a.serial_no, a.location, a.license_no, license_expiry_date,
        b.email_addr, b.property_id
        FROM `bms_annual_renewal` a
        LEFT JOIN bms_property b 
        ON b.property_id = a.property_id 
        WHERE b.property_status = 1 
        AND (a.license_expiry_date <> '0000-00-00' 
        AND a.license_expiry_date > '" . date('Y-m-d') . "' 
        AND  TIMESTAMPDIFF(MONTH, '" . date('Y-m-d') . "', a.license_expiry_date) < 3)  
        ORDER BY `a`.`license_expiry_date`  ASC";
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function getENoticeQueue () {
        $sql = "SELECT a.notice_id,a.queue_id,a.to,a.to_name,b.start_date,b.subject,b.message,b.attachment_name,
                c.property_name, c.email_addr, c.jmb_mc_name,d.unit_no
                FROM bms_e_notice_queue a
                LEFT JOIN bms_e_notice b ON b.notice_id= a.notice_id
                LEFT JOIN bms_property c ON c.property_id= b.property_id
                LEFT JOIN bms_property_units d ON d.unit_id= a.unit_id
                WHERE a.is_sent <> 1 ORDER BY a.queue_id ASC LIMIT 0,100";
        //LEFT JOIN bms_property_units c ON c.unit_id= c.unit_id
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function updateENoticeQueue ($data,$queue_id) {
        $this->db->update('bms_e_notice_queue', $data, array('queue_id' => $queue_id));
    }

    function getTransactionCntForTheDay ($date) {
        $sql = "SELECT property_id,SUM(cnt) AS cnt FROM 
                (SELECT property_id, COUNT(Transaction_ID) AS cnt
                FROM bms_direct_pymt_web
                WHERE DATE_FORMAT(Response_Datetime, '%Y-%m-%d') = '$date'
                GROUP BY property_id                
                UNION ALL
                SELECT property_id, COUNT(Transaction_ID) AS cnt
                FROM bms_direct_pymt
                WHERE DATE_FORMAT(Response_Datetime, '%Y-%m-%d') = '$date'
                GROUP BY property_id                
                ) tbl GROUP BY property_id";

        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getTransactionOfTheProperty ($property_id,$date) {
        $cond = '';
        if($date != '') {
            $cond .= " AND DATE_FORMAT(Response_Datetime, '%Y-%m-%d') ='".$date."'";
        }
        $sql = "SELECT a.property_id,b.email_addr, Transaction_ID,Reference_Number,Payment_ID,pymt_for, Amount, DATE_FORMAT(Response_Datetime, '%d-%m-%Y %h:%i %p') AS trans_date
                FROM bms_direct_pymt_web a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                WHERE a.property_id = '".$property_id."' ". $cond ."
                UNION ALL
                SELECT x.property_id,y.email_addr,Transaction_ID,Reference_Number,Payment_ID,'Mobile' AS pymt_for, Amount, DATE_FORMAT(Response_Datetime, '%d-%m-%Y %h:%i %p')  AS trans_date
                FROM bms_direct_pymt x
                LEFT JOIN bms_property y ON y.property_id = x.property_id
                WHERE x.property_id = '".$property_id."' " . $cond ;
        $query = $this->db->query($sql);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getServiceReqQueue () {
        $sql = "SELECT a.service_req_email_id, a.queue_id, a.to, a.to_name, b.start_date, b.subject, b.message
                FROM sfs_service_req_email_queue a
                LEFT JOIN sfs_service_req_email b ON b.service_req_email_id = a.service_req_email_id
                WHERE a.is_sent <> 1 ORDER BY a.queue_id ASC LIMIT 0,100";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updateServiceReqQueue ($data,$queue_id) {
        $this->db->update('sfs_service_req_email', $data, array('queue_id' => $queue_id));
    }

}