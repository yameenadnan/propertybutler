<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_home_butler_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function get_vendor_cat_list ($offset = '0', $per_page = '25', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.vendor_cat_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT vendor_cat_id,vendor_cat_name                
                FROM home_butler_vendor_cat a                 
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `vendor_cat_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    } 
    
    function get_all_vendor_cat_list () {
        
        $sql = "SELECT vendor_cat_id,vendor_cat_name                
                FROM home_butler_vendor_cat a                 
                ORDER BY `vendor_cat_name` ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function get_vendor_cat_details ($desi_id) {
        $sql = "SELECT vendor_cat_id,vendor_cat_name                 
                FROM home_butler_vendor_cat a                
                WHERE vendor_cat_id= ". $desi_id ;
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insert_vendor_cat ($data) {        
        $this->db->insert('home_butler_vendor_cat', $data);                   
    } 
    
    function update_vendor_cat ($data,$desi_id) {        
        $this->db->update('home_butler_vendor_cat', $data, array('vendor_cat_id' => $desi_id));       
    }
    
    
    function getStates () {
        $query = $this->db->select('state_id,state_name')->order_by('state_name')->get('home_butler_vendor_state');
        return $query->result_array();
    }
    
    
    function get_vendor_list ($offset = '0', $per_page = '25', $state_id = '', $search_txt ='') {
         
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        
        if($state_id != '') {
            $cond .= " AND a.vendor_state = ".$state_id;
        }
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.vendor_name) LIKE '%".$search_txt."%')";
        } 
        
        $sql = "SELECT vendor_id,vendor_name,b.vendor_cat_name,vendor_incharge,vendor_mobile_no,vendor_inc_email                
                FROM home_butler_vendor a 
                LEFT JOIN home_butler_vendor_cat b ON b.vendor_cat_id=a.vendor_catgory                
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY `vendor_name` ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
        
    } 
    
    function getCities ($state_id) {
        $query = $this->db->select('city_id,state_id,city_name')->order_by('city_name','ASC')->get_where('home_butler_vendor_city',array('state_id'=>$state_id));
        return $query->result_array();
    }
    
     function getTowns ($state_id,$city_id) {
        $query = $this->db->select('town_id,town_name')->order_by('town_name','ASC')->get_where('home_butler_vendor_town',array('state_id'=>$state_id,'city_id'=>$city_id));
        return $query->result_array();
    }
    
    function get_all_vendor_list () {
        
        $sql = "SELECT vendor_id,vendor_name                
                FROM home_butler_vendor a                 
                ORDER BY `vendor_name` ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    } 
    
    function get_vendor_details ($desi_id) {
        $sql = "SELECT vendor_id,vendor_name,vendor_address1,vendor_address2,vendor_postcode,vendor_state,vendor_town,
                vendor_city,vendor_office_no,vendor_fax,vendor_incharge,vendor_mobile_no,vendor_inc_email,
                vendor_catgory,vendor_keywords,vendor_status,email_addr,password
                FROM home_butler_vendor a                
                WHERE vendor_id= ". $desi_id ;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();       
    }
    
    function checkCity ($city_txt,$state_id) {
        $sql = "SELECT city_id                
                FROM home_butler_vendor_city a                
                WHERE state_id= ". $state_id. " AND LOWER(city_name)='".strtolower($city_txt)."'";
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insertCity ($city_txt,$state_id) {
        $data['city_name'] = $city_txt;
        $data['state_id'] = $state_id;
        $this->db->insert('home_butler_vendor_city', $data);
        return $insert_id = $this->db->insert_id();  
    }
    
    function checkTown ($town_txt,$city_id,$state_id) {
        $sql = "SELECT town_id                
                FROM home_butler_vendor_town a                
                WHERE state_id= ". $state_id. " AND city_id= ". $city_id. " AND LOWER(town_name)='".strtolower($town_txt)."'";
        $query = $this->db->query($sql);
        return $query->row_array();       
    }
    
    function insertTown ($town_txt,$city_id,$state_id) {
        $data['town_name'] = $town_txt;
        $data['city_id'] = $city_id;
        $data['state_id'] = $state_id;
        $this->db->insert('home_butler_vendor_town', $data);
        return $insert_id = $this->db->insert_id();  
    }
    
    function insert_vendor ($data) { 
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('home_butler_vendor', $data);                   
    } 
    
    function update_vendor ($data,$desi_id) { 
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('home_butler_vendor', $data, array('vendor_id' => $desi_id));       
    }
    
}