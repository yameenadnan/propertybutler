<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_attendance_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getMyAttendanceLastCapture () { 
        
        $sql = "SELECT attn_date,atten_time,in_out_type,remarks
                FROM bms_staff_attendance 
                WHERE staff_id=".$_SESSION['bms']['staff_id']." ORDER BY attn_date DESC,atten_time DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function attendance_insert ($data) {
        $this->db->insert('bms_staff_attendance', $data);
        //echo $this->db->last_query();        
    }
    
    function attendance_update ($data,$id) {        
        $this->db->update('bms_staff_attendance', $data, array('atten_id' => $id));   
    }
    
    function get_staff_attendance($start_date,$end_date,$staff_id) {
        $sql = "SELECT first_name,last_name,c.desi_name,a.attn_date, a.atten_time, 
                a.in_out_type, a.remarks, a.img_name FROM bms_staff_attendance a 
                LEFT JOIN bms_staff b ON b.`staff_id` = a.staff_id
                LEFT JOIN bms_designation c ON c.desi_id = b.`designation_id`
                WHERE a.staff_id=$staff_id AND (a.attn_date BETWEEN '$start_date' AND '$end_date' )                
                ORDER BY attn_date ASC, atten_time,in_out_type DESC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();                
    }
    
    function get_staff_attendance_date($date,$staff_id) {
        $sql = "SELECT first_name,last_name,c.desi_name,a.attn_date, a.atten_time, 
                a.in_out_type, a.remarks, a.img_name FROM bms_staff_attendance a 
                LEFT JOIN bms_staff b ON b.`staff_id` = a.staff_id
                LEFT JOIN bms_designation c ON c.desi_id = b.`designation_id`
                WHERE a.staff_id=$staff_id AND (a.attn_date = '$date' )                
                ORDER BY attn_date ASC,atten_time,in_out_type DESC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
                
    }
    
    function get_staff_ids ($property_id) {
        $sql = "SELECT staff_id,first_name,last_name,c.desi_name  FROM `bms_staff` a
                LEFT JOIN bms_designation c ON c.desi_id = a.`designation_id`
                WHERE emp_type IN (1,2,3) AND staff_id NOT IN (1038,1218,1039,1229,1312,1335,1336,1337,1341,1342,1443,1060,1038,1273,1521,1522,1580,1582,1587) 
                AND staff_id IN (SELECT staff_id FROM bms_staff_property WHERE property_id=$property_id)
                ORDER BY first_name ASC,last_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    
    }
    
    function get_all_staff_ids () {
        $sql = "SELECT staff_id,first_name,last_name  FROM `bms_staff` 
                WHERE emp_type IN (1,2,3) AND staff_id NOT IN (1312,1335,1336,1337,1341,1342,1443,1060,1039,1038,1218,1229,1273,1521,1522,1486,1074,1288,1418,1363,1219,1213,1580,1582,1587) 
                AND staff_id IN (
                    SELECT staff_id from bms_staff_property WHERE property_id IN(
                    SELECT property_id from bms_staff_property WHERE staff_id =".$_SESSION['bms']['staff_id']."))
                ORDER BY first_name ASC,last_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    
    }
    
    function get_all_staff_ids_for_SA () {
        $sql = "SELECT staff_id,first_name,last_name,c.desi_name  FROM `bms_staff` a
                LEFT JOIN bms_designation c ON c.desi_id = a.`designation_id`
                WHERE emp_type IN (1,2,3) AND staff_id NOT IN (1038,1218,1039,1229,1312,1335,1336,1337,1341,1342,1443,1582,1587) 
                ORDER BY first_name ASC,last_name ASC";
        $query = $this->db->query($sql);        
        return $query->result_array();
    
    }
    
    function get_staff_name ($staff_id) {
        $sql = "SELECT staff_id,first_name,last_name  FROM `bms_staff` 
                WHERE staff_id =".$staff_id; 
                
        $query = $this->db->query($sql);        
        return $query->row_array();
    }
    
    function getStaffAttendance ($staff_id,$attten_date) {
        $sql = "SELECT atten_id,a.attn_date, a.atten_time,a.in_out_type, a.remarks
                FROM bms_staff_attendance a 
                WHERE a.staff_id=$staff_id AND (a.attn_date = '$attten_date' )                
                ORDER BY atten_id";
        $query = $this->db->query($sql);        
        return $query->result_array();
    }
    
    /*function get_one_prop_all_staff_attendance($start_date,$end_date,$property_id) {
        $sql = "SELECT first_name,last_name,c.desi_name,a.attn_date, a.atten_time, 
                a.in_out_type, a.remarks, a.img_name FROM bms_staff_attendance a 
                LEFT JOIN bms_staff b ON b.`staff_id` = a.staff_id
                LEFT JOIN bms_designation c ON c.desi_id = b.`designation_id`
                WHERE in_out_type IN (1,2) AND (a.attn_date BETWEEN '$start_date' AND '$end_date' )
                AND a.staff_id IN (SELECT staff_id FROM bms_staff_property WHERE property_id=$property_id)
                ORDER BY first_name,last_name,attn_date,in_out_type";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
                
    }*/
    
      
}