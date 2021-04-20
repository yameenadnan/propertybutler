<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_banks_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    
    function insertBank ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_banks', $data);
        return $this->db->insert_id();   
    }
    
    function updateBank ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_banks', $data, array('bank_id' => $id));
        //echo "<br />".$this->db->last_query();   
    }  
    
    
    function getBanksList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(bank_name) LIKE '%".$search_txt."%')";
        }       
        
        $sql = "SELECT bank_id,property_name,bank_name,acc_type,acc_no,opening_bal,ob_date,acc_status
                FROM bms_fin_banks a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id                        
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY bank_name".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function getBank ($bank_id) {       
        
        $sql = "SELECT bank_id,property_id,bank_name,acc_type,acc_no,opening_bal,ob_date,acc_status
                FROM bms_fin_banks a                        
                WHERE bank_id =".$bank_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }   
       
    function deleteBank ($bank_id) {
        return $this->db->delete('bms_fin_banks',array('bank_id'=>$bank_id));
    }
    
}