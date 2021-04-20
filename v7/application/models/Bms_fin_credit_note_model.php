<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_credit_note_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    /*function getSalesItems () {                
        $sql = "SELECT charge_code_category_id,
                CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name 
                FROM bms_charge_code_category b
                WHERE b.charge_code_group_id = 5 ORDER BY charge_code ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getSubCategory ($cat_id) {                
        $sql = "SELECT charge_code_sub_category_id, CONCAT(charge_code_sub_category_name, ' (',charge_code,')') AS charge_code_sub_category_name 
                FROM bms_charge_code_sub_category 
                WHERE charge_code_category_id  = ".$cat_id ." ORDER BY charge_code ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }*/
    
    function getLastCreditNoteNo ($credit_note_no_format) {
        $sql = "SELECT credit_note_no FROM bms_fin_credit_note WHERE credit_note_no LIKE '".$credit_note_no_format."%' ORDER BY credit_note_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insertCreditNote ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_credit_note', $data);
        return $this->db->insert_id();   
    }
    
    function updateCreditNote ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_credit_note', $data, array('credit_note_id' => $id));   
    }
    
    function insertCreditNoteItem ($data) {        
        $this->db->insert('bms_fin_credit_note_items', $data);        
    }
    
    function updateCreditNoteItem ($data,$id) {        
        $this->db->update('bms_fin_credit_note_items', $data,  array('credit_note_item_id' => $id));        
    }
    
    function getCreditNotesList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(owner_name) LIKE '%".$search_txt."%' OR LOWER(unit_no) LIKE '%".$search_txt."%' OR LOWER(credit_note_date) LIKE '%".$search_txt."%' OR LOWER(total_amount) LIKE '%".$search_txt."%')";
        } 
        
        
        $sql = "SELECT credit_note_id,property_name,credit_note_no,unit_no,owner_name,credit_note_date,total_amount
                FROM bms_fin_credit_note a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY credit_note_date DESC, credit_note_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function getCreditNote ($credit_note_id) {       
        
        $sql = "SELECT credit_note_id,property_id,block_id,unit_id,credit_note_no,credit_note_date,remarks,total_amount
                
                FROM bms_fin_credit_note a  
                                
                WHERE credit_note_id =".$credit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getCreditNoteDetails ($credit_note_id) {       
        
        $sql = "SELECT credit_note_id,a.property_id,b.property_name,a.block_id,a.unit_id,
                b.property_name,b.jmb_mc_name,b.address_1,b.address_2,b.pin_code,b.city,e.state_name,f.country_name,
                c.unit_no,c.owner_name,credit_note_no,
                credit_note_date,remarks,total_amount,remarks,a.created_date,d.first_name,d.last_name
                FROM bms_fin_credit_note a 
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id             
                WHERE credit_note_id =".$credit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    
    function getCreditNoteItems ($credit_note_id) {
        $sql = "SELECT credit_note_item_id,credit_note_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount
                FROM bms_fin_credit_note_items a 
                WHERE credit_note_id =".$credit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getCreditNoteItemsDetail ($credit_note_id) {
        $sql = "SELECT credit_note_item_id,credit_note_id,item_cat_id,item_sub_cat_id,
                b.coa_name,item_period,item_descrip,item_amount,adj_amount,bal_amount
                FROM bms_fin_credit_note_items a
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.item_cat_id
                   
                WHERE credit_note_id =".$credit_note_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function deleteCreditNoteItem ($credit_note_item_id) {
        return $this->db->delete('bms_fin_credit_note_items',array('credit_note_item_id'=>$credit_note_item_id));
    }
    
    function deleteCreditNoteItemByCreditNoteId ($credit_note_id) {
        return $this->db->delete('bms_fin_credit_note_items',array('credit_note_id'=>$credit_note_id));
    }
    
    function deleteCreditNote ($credit_note_id) {
        return $this->db->delete('bms_fin_credit_note',array('credit_note_id'=>$credit_note_id));
    }
    
    function getOutstandingBillNos ($unit_id) {                
        $sql = "SELECT bill_id,bill_no
                FROM bms_fin_bills a                        
                WHERE unit_id =".$unit_id." AND bill_paid_status=0";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getBillItems ($bill_id) {
        $sql = "SELECT bill_item_id,bill_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount,bal_amount
                FROM bms_fin_bill_items a                        
                WHERE bill_id =".$bill_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    
}