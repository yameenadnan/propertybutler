<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_coa_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getCoaType () {
        $query = $this->db->select("coa_type_id,coa_type_name",false)->order_by('coa_type_id','ASC')->get('bms_fin_coa_type');
        return $query->result_array();
    }
    
    function insert_coa ($data) {

        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_coa', $data);
        return $this->db->insert_id();           
    } 
    
    function update_coa ($data,$coa_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_coa', $data, array('coa_id' => $coa_id));
        //echo "<br />".$this->db->last_query();   exit;    
    }
    
    function get_coa_list ($offset = '0', $per_page = '25', $property_id = '', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=".$property_id." AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(a.coa_name) LIKE '%".$search_txt."%' OR LOWER(a.coa_code) LIKE '%".$search_txt."%')";
        }       
        
        $sql = "SELECT a.coa_id, a.coa_code, a.coa_name, b.coa_type_name               
                FROM bms_fin_coa a 
                LEFT JOIN bms_fin_coa_type b ON b.coa_type_id = a.coa_type_id                
                WHERE  1=1  ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY a.coa_code ASC, a.coa_name ASC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
    
    
    function get_coa_details($coa_id) { 
        $sql = "SELECT coa_id,property_id,coa_code,coa_name,coa_type_id,period,opening_debit,opening_credit,opening_cr_date,
                payment_source,payment_enabled,bill_enabled,receipt_enabled,deposit_enabled, default_acc, fi, qr, sc, sf, water, lpi
                FROM  bms_fin_coa a
                WHERE coa_id=". $coa_id;        
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function check_coa_code($property_id,$coa_code,$coa_id) {
        $cond = !empty($coa_id) ? ' AND coa_id <>'.$coa_id : '';
        $sql = "SELECT COUNT(coa_id) AS cnt 
                FROM bms_fin_coa WHERE property_id=? AND coa_code=?".$cond;
        $query = $this->db->query($sql,array($property_id,$coa_code));
        //echo $this->db->last_query();exit;
        return $query->row_array();   
    }
    
    function insert_coa_sub_acc ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_coa_sub_acc_name', $data);
        //return $this->db->insert_id();           
    } 
    
    function update_coa_sub_acc ($data,$coa_acc_sub_name_id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_coa_sub_acc_name', $data, array('coa_acc_sub_name_id' => $coa_acc_sub_name_id));       
    }
    
    function insert_charge_code_sub_cat ($data) {
        
        $this->db->insert('bms_charge_code_sub_category', $data);
        //return $this->db->insert_id();           
    } 
    
    function update_charge_code_sub_cat ($data,$charge_code_sub_category_id) {        
        $this->db->update('bms_charge_code_sub_category', $data, array('charge_code_sub_category_id' => $charge_code_sub_category_id));       
    }   
    
    
    function get_service_provider ($property_id) {
        $sql = "SELECT service_provider_id,provider_name FROM bms_service_provider 
                WHERE property_id=".$property_id." AND (coa_id IS NULL OR coa_id = '')
                ORDER BY provider_name";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();        
    }
    
    function set_service_provider ($insert_id,$service_provider_id) {
        $data['coa_id'] = $insert_id;
        $this->db->update('bms_service_provider', $data, array('service_provider_id' => $service_provider_id));
    }
    
    function get_last_sp_code($property_id) {
        $sql = "SELECT coa_code FROM bms_fin_coa 
                WHERE property_id=".$property_id." AND coa_code LIKE '4100/%'
                ORDER BY coa_code DESC LIMIT 0,1";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getUnit ($property_id) {
        
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter 
                FROM bms_property_units a 
                LEFT JOIN bms_property_block b ON b.block_id=a.block_id 
                WHERE a.property_id = '".$property_id."' AND (coa_id IS NULL OR coa_id = '')
                ORDER BY b.block_name ASC, a.unit_no ASC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function get_last_unit_code($property_id) {
        $sql = "SELECT coa_code FROM bms_fin_coa 
                WHERE property_id=".$property_id." AND coa_code LIKE '3000/%'
                ORDER BY coa_code DESC LIMIT 0,1";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    
    function set_unit_coa ($insert_id,$unit_id) {
        $data['coa_id'] = $insert_id;
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id));
    }
    
      
}