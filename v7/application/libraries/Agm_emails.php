<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agm_emails extends Mailersend {
      
    function __construct() { $this->CI = & get_instance();    }
    
    function getPropertyStaffs ($property_id) {
        $sql = "SELECT staff_id,first_name,last_name,email_addr
                FROM bms_staff                
                WHERE emp_type IN (1,2,3) 
                AND designation_id NOT IN (1,8,10,14,13,15,22)
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY first_name ASC,last_name ASC";    
        
        $query = $this->CI->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
    
    function getPropertyJmb ($property_id) {
        $sql = "SELECT member_id,a.property_id,c.unit_id,c.unit_no,c.owner_name as first_name,jmb_desi_name,elect_date,jmb_role,email_addr
                FROM bms_jmb_mc a   
                LEFT JOIN bms_jmb_designation b ON b.jmb_desi_id = a.jmb_desi_id               
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id
                WHERE a.property_id=".$property_id;          
        
        $query = $this->CI->db->query($sql);  
        //echo "<pre>".$this->db->last_query();      
        return $query->result_array();
    }
   
      
}