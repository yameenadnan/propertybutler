<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_charge_code_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getChargeCodeCatGroup () {
        $query = $this->db->select('charge_code_group_id,charge_code_group_name')->order_by('charge_code_group_name','ASC')->get('bms_charge_code_cat_group'); //
        return $query->result_array();
    }
    
    function get_charge_code_list ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        //$cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.charge_code_group_name) LIKE '%".$search_txt."%' OR LOWER(b.charge_code_category_name) LIKE '%".$search_txt."%' OR LOWER(c.charge_code_sub_category_name) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT b.charge_code_category_id,CONCAT(charge_code_group_name, ' (',a.charge_code,')') AS charge_code_group_name,
                CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name , 
                CONCAT(charge_code_sub_category_name, ' (',c.charge_code,')') AS charge_code_sub_category_name
                FROM bms_charge_code_category b 
                LEFT JOIN bms_charge_code_cat_group a ON b.charge_code_group_id = a.charge_code_group_id
                LEFT JOIN bms_charge_code_sub_category c ON c.charge_code_category_id = b.charge_code_category_id
                WHERE  1=1  ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY charge_code_group_name ASC, charge_code_category_name ASC, charge_code_sub_category_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    } 
    
    function get_charge_code_details ($charge_code_id) {
        //$cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT charge_code_category_id,charge_code_group_id,charge_code_category_name,charge_code,payment,expenses,purchase_order,receipt,bills 
                FROM  bms_charge_code_category a               
                WHERE charge_code_category_id=". $charge_code_id ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_charge_code_sub_cat_details ($charge_code_id) {
        //$cond = " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = a.property_id AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        $sql = "SELECT charge_code_sub_category_id,charge_code_sub_category_name,charge_code 
                FROM  bms_charge_code_sub_category a               
                WHERE charge_code_category_id=". $charge_code_id ;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function insert_charge_code ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_charge_code_category', $data);
        return $this->db->insert_id();           
    } 
    
    function update_charge_code ($data,$charge_code_category_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_charge_code_category', $data, array('charge_code_category_id' => $charge_code_category_id));       
    }
    
    function insert_charge_code_sub_cat ($data) {
        
        $this->db->insert('bms_charge_code_sub_category', $data);
        //return $this->db->insert_id();           
    } 
    
    function update_charge_code_sub_cat ($data,$charge_code_sub_category_id) {        
        $this->db->update('bms_charge_code_sub_category', $data, array('charge_code_sub_category_id' => $charge_code_sub_category_id));       
    }   
      
}