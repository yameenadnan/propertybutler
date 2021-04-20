<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_debit_note_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getLastDebitNoteNo ($debit_note_no_format) {
        $sql = "SELECT debit_note_no FROM bms_fin_debit_note WHERE debit_note_no LIKE '".$debit_note_no_format."%' ORDER BY debit_note_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insertDebitNote ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_debit_note', $data);
        return $this->db->insert_id();   
    }
    
    function updateDebitNote ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_debit_note', $data, array('debit_note_id' => $id));   
    }   
    
    function getDebitNotesList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND d.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(owner_name) LIKE '%".$search_txt."%' OR LOWER(unit_no) LIKE '%".$search_txt."%' OR LOWER(debit_note_date) LIKE '%".$search_txt."%' OR LOWER(total_amount) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT debit_note_id,property_name,debit_note_no,d.receipt_id,d.receipt_no,unit_no,owner_name,debit_note_date,total_amount
                FROM bms_fin_debit_note a  
                LEFT JOIN bms_fin_receipt d ON d.receipt_id= a.receipt_id
                LEFT JOIN bms_property b ON b.property_id = d.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = d.unit_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY debit_note_date DESC, debit_note_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function get_unit_receipt ($unit_id,$receipt_id='') {
        $sql = "SELECT a.receipt_id,receipt_no,a.paid_amount,bank_id               
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items d ON d.receipt_id= a.receipt_id                                
                WHERE unit_id =".$unit_id." AND d.bill_item_id <> 0
                AND a.receipt_id NOT IN (SELECT receipt_id FROM bms_fin_debit_note WHERE unit_id=".$unit_id.")";
        if(!empty($receipt_id)) 
            $sql .= " OR receipt_id=".$receipt_id;
        $sql .= " ORDER BY receipt_no";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getDebitNote ($debit_note_id) {       
        
        $sql = "SELECT debit_note_id,a.receipt_id,d.property_id,d.block_id,a.unit_id,debit_note_no,debit_note_date,a.remarks                
                FROM bms_fin_debit_note a  
                LEFT JOIN bms_fin_receipt d ON d.receipt_id= a.receipt_id           
                WHERE debit_note_id =".$debit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getDebitNoteDetails ($debit_note_id) {       
        
        $sql = "SELECT debit_note_id,a.property_id,b.property_name,a.block_id,
                b.property_name,b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,b.city,e.state_name,f.country_name,
                a.unit_id,c.unit_no,c.owner_name,debit_note_no,
                debit_note_date,remarks,total_amount,remarks,a.created_date,d.first_name,d.last_name
                FROM bms_fin_debit_note a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id               
                WHERE debit_note_id =".$debit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    
    function getDebitNoteItems ($debit_note_id) {
        $sql = "SELECT debit_note_item_id,debit_note_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount,paid_amount
                FROM bms_fin_debit_note_items a 
                WHERE debit_note_id =".$debit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getDebitNoteItemsDetail ($debit_note_id) {
        $sql = "SELECT debit_note_item_id,debit_note_id,item_cat_id,item_sub_cat_id,
                b.coa_name,item_period,item_descrip,item_amount,paid_amount
                FROM bms_fin_debit_note_items a
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.item_cat_id
                LEFT JOIN bms_charge_code_sub_category c ON c.charge_code_sub_category_id = a.item_sub_cat_id   
                WHERE debit_note_id =".$debit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }    
    
    function deleteDebitNote ($debit_note_id) {
        $this->db->delete('bms_fin_debit_note_items',array('debit_note_id'=>$debit_note_id)); // Items delete
        return $this->db->delete('bms_fin_debit_note',array('debit_note_id'=>$debit_note_id)); // Main entry delete
    }
    
    function setDebitNoteItems ($receipt_id,$debit_note_id) {
        $sql = "INSERT INTO bms_fin_debit_note_items(debit_note_id,receipt_item_id,receipt_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount,paid_amount,bal_amount,bill_item_id)
                SELECT ".$debit_note_id.",receipt_item_id,receipt_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount,paid_amount,bal_amount,bill_item_id 
                FROM bms_fin_receipt_items WHERE  receipt_id=".$receipt_id;
        $this->db->query($sql);
    }
    
}