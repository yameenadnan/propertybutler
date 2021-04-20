<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_jmb_mc_model extends CI_Model {
    function __construct () { parent::__construct(); }
        
    function get_jmb_mc_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(c.owner_name) LIKE '%".$search_txt."%' OR LOWER(jmb_desi_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT member_id,a.property_id,c.unit_id,c.unit_no,c.owner_name,jmb_desi_name,elect_date,jmb_role,email_addr
                FROM bms_jmb_mc a   
                LEFT JOIN bms_jmb_designation b ON b.jmb_desi_id = a.jmb_desi_id               
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id
                WHERE jmb_status = 1  ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY owner_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }   
    
    function get_jmb_mc_details ($jmb_mc_id) {
        $cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT member_id,a.property_id,c.unit_id,c.owner_name,contact_1,
                a.jmb_desi_id,jmb_desi_name,elect_date,jmb_role,email_addr,jmb_status
                FROM  bms_jmb_mc a  
                LEFT JOIN bms_jmb_designation b ON b.jmb_desi_id = a.jmb_desi_id               
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id             
                WHERE member_id=". $jmb_mc_id . $cond;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getUnit ($property_id) {
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,email_addr,contact_1,is_defaulter 
                FROM bms_property_units WHERE property_id = '".$property_id."'
                ORDER BY unit_no";

        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function getPositions () {
        $sql = "SELECT jmb_desi_id,jmb_desi_name FROM bms_jmb_designation ORDER BY jmb_desi_name";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    
    function check_email($email_addr, $jmb_mc_id) {
        $cond = $jmb_mc_id ? ' AND jmb_mc_id <>'.$jmb_mc_id : '';
        $sql = "SELECT jmb_mc_id,email_addr 
                FROM bms_jmb_mc WHERE status = 1 AND email_addr=? ".$cond;
        $query = $this->db->query($sql,array($email_addr));
        //echo $this->db->last_query();exit;
        return $query->result_array();   
    }
    
    function insert_jmb_mc ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d H:i:s");
        $this->db->insert('bms_jmb_mc', $data);
        return $this->db->insert_id();           
    } 
    
    function update_jmb_mc ($data,$jmb_mc_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d H:i:s");
        $this->db->update('bms_jmb_mc', $data, array('member_id' => $jmb_mc_id));       
    } 
    
}