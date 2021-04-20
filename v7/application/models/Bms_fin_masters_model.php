<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_masters_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getSalesItemsForBill ($property_id) {                
        $sql = "SELECT coa_id, coa_name,period                
                FROM bms_fin_coa b
                WHERE b.property_id = ".$property_id." AND bill_enabled=1 ORDER BY coa_name ASC";
                //CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getSalesItemsForReceipt ($property_id) {                
        $sql = "SELECT coa_id, coa_name,period                
                FROM bms_fin_coa b
                WHERE b.property_id = ".$property_id." AND receipt_enabled=1 ORDER BY coa_name ASC";
                //CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getSalesItemsForReceiptSummary ($property_id) {                
        $sql = "SELECT coa_id, coa_name,period                
                FROM bms_fin_coa b
                WHERE b.property_id = ".$property_id." AND (receipt_enabled=1 OR deposit_enabled=1) ORDER BY coa_name ASC";
                //CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getDpItems ($property_id) {                
        $sql = "SELECT coa_id, coa_name,period                
                FROM bms_fin_coa b
                WHERE b.property_id = ".$property_id." AND deposit_enabled=1 ORDER BY coa_name ASC";
                //CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getBanksForReceipt ($property_id,$bank_id = '') {
        $add_condi = '';
        if($bank_id != '')
            $add_condi =  " AND coa_id=".$bank_id;
        $sql = "SELECT coa_id as bank_id, coa_name as bank_name
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." $add_condi AND payment_source=1 AND coa_code NOT IN ('3100/000')  ORDER BY bank_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getBanks ($property_id) {                
        $sql = "SELECT coa_id as bank_id, coa_name as bank_name
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." AND payment_source=1 ORDER BY bank_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getExpenseItems ($probid) {
        $sql = "SELECT cate.coa_id,
                CONCAT(cate.coa_name, ' (',cate.coa_code,')') AS coa_name,
                grp.coa_type_name
                FROM bms_fin_coa as cate
                INNER join bms_fin_coa_type as grp
                WHERE cate.payment_enabled = 1 and cate.property_id=$probid 
                AND cate.coa_code NOT LIKE '4100/%' AND grp.coa_type_id=cate.coa_type_id ORDER BY cate.coa_code ASC";
        $query = $this->db->query($sql);
        //  echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }
    
}