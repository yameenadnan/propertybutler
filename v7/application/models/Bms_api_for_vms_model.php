<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_api_for_vms_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function get_prebooks_of_property ($property_id) {
        $sql = "SELECT prebook_visitor_id, property_id, unit_id, mobile_no, vehicle_no, visit_type, booking_date, booking_time
              FROM bms_vms_prebook_visitors
              WHERE property_id = '$property_id' AND flag = 1 " ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_prebooks_to_cancel_of_property ($property_id) {
        $sql = "SELECT prebook_visitor_id
              FROM bms_vms_prebook_visitors
              WHERE property_id = '$property_id' AND flag = 3 " ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getPrebookedMobileNo ($prebook_visitor_id) {
        $sql = "SELECT mobile_no
              FROM bms_vms_prebook_visitors
              WHERE prebook_visitor_id = '$prebook_visitor_id'" ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_prebooks_of_property ($prebook_visitor_id, $data) {
        $this->db->where_in('prebook_visitor_id', $prebook_visitor_id);
        return $this->db->update('bms_vms_prebook_visitors', $data);
    }
    
    function getFrequentVisitor ($property_id) {
        $sql = "SELECT frequent_visitor_id,property_id,unit_id,vehicle_no,mobile_no,verify_req,reg_date
              FROM bms_vms_frequent_visitor
              WHERE property_id = '$property_id' AND flag = 1;" ;
        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }

    function getFrequentVisitorToUpdate ($property_id) {
        $sql = "SELECT frequent_visitor_id,property_id,unit_id,vehicle_no,mobile_no,verify_req,reg_date
              FROM bms_vms_frequent_visitor
              WHERE property_id = '$property_id' AND flag = 3;" ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getFrequentRegMobileNo ($property_id,$unit_id,$vehicle_no) {
        $sql = "SELECT mobile_no
              FROM bms_vms_frequent_visitor
              WHERE property_id = '$property_id' AND unit_id='$unit_id' AND vehicle_no = '$vehicle_no' AND verify_req = 1" ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updateFreqVisitor ($frequent_visitor_id, $data) {
        $this->db->where_in('frequent_visitor_id', $frequent_visitor_id);
        return $this->db->update('bms_vms_frequent_visitor', $data);
    }

    function getNoteToGuard ($property_id) {
        $sql = "SELECT note_id,property_id,unit_id,event_type,start,end,mobile_no,notes
              FROM bms_vms_note_to_guard
              WHERE property_id = '$property_id' AND flag = 1 " ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function update_note_to_guard_of_property ($note_id, $data) {
        $this->db->where_in('note_id', $note_id);
        return $this->db->update('bms_vms_note_to_guard', $data);
    }

    function getPanicAlert ($property_id) {
        $sql = "SELECT panic_alert_id,unit_id,mobile_no,received,response,alert_type,notes
              FROM bms_vms_panic_alert
              WHERE property_id = '$property_id' AND flag = 1 " ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function updatePanicAlert ($panic_alert_id, $data) {
        $this->db->where_in('panic_alert_id', $panic_alert_id);
        return $this->db->update('bms_vms_panic_alert', $data);
    }
    
    function updateNoteToGuard ($note_id, $data) {
        $this->db->where_in('note_id', $note_id);
        return $this->db->update('bms_vms_note_to_guard', $data);
    }
    
    // visit master
    function check_visit_master ($where) {
        $this->db->where($where);
        return $this->db->count_all_results('bms_vms_visitor_master');
    }

    function insert_visitor_master ($data) {
        return $this->db->insert('bms_vms_visitor_master', $data);
    }
    
    function update_visitor_master ($data,$where) {
        return $this->db->update('bms_vms_visitor_master', $data, $where);
    }
    
    // Visit details
    function check_visit_details ($where) {
        $this->db->where($where);
        return $this->db->count_all_results('bms_vms_visit_details');
    }

    function insert_visit_details ($data) {
        return $this->db->insert('bms_vms_visit_details', $data);
    }
    
    function update_visit_details ($data,$where) {
        return $this->db->update('bms_vms_visit_details', $data, $where);
    }
    
    function getVisitor ($visitor_id,$property_id) {
        $query = $this->db->select('visitor_name')
                          ->where(array('visitor_id'=>$visitor_id, 'property_id'=>$property_id))
                          ->get('bms_vms_visitor_master');
        return $query->row_array();
    }
    
    function getOwnerPushToken ($unit_id,$mobile_no) {
        $mobile_cond = '';
        if(!empty($mobile_no)) {
            $mobile_cond = " AND contact_1 IN ('".implode("','",$mobile_no)."') ";
        } 
        $sql = "SELECT push_token FROM bms_property_units 
                WHERE unit_id = ".$unit_id ." ".$mobile_cond." 
                AND push_token IS NOT NULL AND push_token <>''";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    function getTenantPushToken ($unit_id,$mobile_no) {
        $mobile_cond = '';
        if(!empty($mobile_no)) {
            $mobile_cond = " AND contact_1 IN ('".implode("','",$mobile_no)."') ";
        }
        $sql = "SELECT push_token FROM bms_property_unit_tenants 
                WHERE unit_id = ".$unit_id ." ".$mobile_cond."
                AND push_token IS NOT NULL AND push_token <>''";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    function getMAUsersPushToken ($unit_id,$mobile_no) {
        $mobile_cond = '';
        if(!empty($mobile_no)) {
            $mobile_cond = " AND ma_user_contact IN ('".implode("','",$mobile_no)."') ";
        }
        $sql = "SELECT push_token FROM bms_property_unit_ma_users 
                WHERE unit_id = ".$unit_id ." ".$mobile_cond."
                AND push_token IS NOT NULL AND push_token <>''";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    function get_unit_unsync_data ($property_id) {
        $sql = "SELECT a.unit_id, b.block_name,a.unit_no,a.unit_status,a.is_defaulter,
        		CASE 
        			WHEN a.unit_status IN (1,3,4) THEN   (SELECT CONCAT(c.owner_name, ':::',c.contact_1) FROM bms_property_units c WHERE c.unit_id = a.unit_id AND c.owner_name IS NOT NULL AND c.owner_name <> '' AND c.contact_1 IS NOT NULL AND c.contact_1 <> '')
        			ELSE (SELECT CONCAT(t.tenant_name, ':::',t.contact_1) FROM bms_property_unit_tenants t WHERE t.unit_id = a.unit_id  AND (t.end_date ='0000-00-00' OR t.end_date >= '".date('Y-m-d')."') AND t.tenant_name IS NOT NULL AND t.tenant_name <> '' AND t.contact_1 IS NOT NULL AND t.contact_1 <> '' ORDER BY t.unit_tenant_id DESC LIMIT 0,1)
        		END AS namecont1,
        	    (SELECT CONCAT(c.ma_user_name, ':::',c.ma_user_contact) FROM bms_property_unit_ma_users c WHERE c.unit_id = a.unit_id AND c.ma_seq_no = '1') AS namecont2,
        		(SELECT CONCAT(d.ma_user_name, ':::',d.ma_user_contact) FROM bms_property_unit_ma_users d WHERE d.unit_id = a.unit_id AND d.ma_seq_no = '2') AS namecont3,
        		(SELECT CONCAT(e.ma_user_name, ':::',e.ma_user_contact) FROM bms_property_unit_ma_users e WHERE e.unit_id = a.unit_id AND e.ma_seq_no = '3') AS namecont4,
        		(SELECT CONCAT(f.ma_user_name, ':::',f.ma_user_contact) FROM bms_property_unit_ma_users f WHERE f.unit_id = a.unit_id AND f.ma_seq_no = '4') AS namecont5,
        		(SELECT CONCAT(g.ma_user_name, ':::',g.ma_user_contact) FROM bms_property_unit_ma_users g WHERE g.unit_id = a.unit_id AND g.ma_seq_no = '5') AS namecont6
                FROM bms_property_units a 
                LEFT JOIN bms_property_block AS b ON b.block_id = a.block_id
                WHERE a.property_id = $property_id AND vms_sync = 0 AND
                (CASE 
        			WHEN a.unit_status IN (1,3,4) THEN   (SELECT CONCAT(c.owner_name, ':::',c.contact_1) FROM bms_property_units c WHERE c.unit_id = a.unit_id AND c.owner_name IS NOT NULL AND c.owner_name <> '' AND c.contact_1 IS NOT NULL AND c.contact_1 <> '')
        			ELSE (SELECT CONCAT(t.tenant_name, ':::',t.contact_1) FROM bms_property_unit_tenants t WHERE t.unit_id = a.unit_id  AND (t.end_date ='0000-00-00' OR t.end_date >= '".date('Y-m-d')."') AND t.tenant_name IS NOT NULL AND t.tenant_name <> '' AND t.contact_1 IS NOT NULL AND t.contact_1 <> '' ORDER BY t.unit_tenant_id DESC LIMIT 0,1)
        		END) IS NOT NULL
                ORDER BY a.unit_id LIMIT 0,100";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    function update_unit_vms_sync_flag ($data,$ids) {
        $this->db->where_in('unit_id', $ids);
        return $this->db->update('bms_property_units', $data);   
        //echo $this->db->last_query();exit;    
    }

}