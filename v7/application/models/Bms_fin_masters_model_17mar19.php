<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_masters_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getSalesItems () {                
        $sql = "SELECT charge_code_category_id, charge_code_category_name AS charge_code_category_name,period
                
                FROM bms_charge_code_category b
                WHERE b.charge_code_group_id = 5 ORDER BY charge_code ASC";
                //CONCAT(charge_code_category_name, ' (',b.charge_code,')') AS charge_code_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getBanks ($property_id) {                
        $sql = "SELECT bank_id, bank_name
                FROM bms_fin_banks 
                WHERE property_id = ".$property_id ." ORDER BY bank_name ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getSubCategory ($cat_id) {                
        $sql = "SELECT charge_code_sub_category_id, charge_code_sub_category_name AS charge_code_sub_category_name,period 
                FROM bms_charge_code_sub_category 
                WHERE charge_code_category_id  = ".$cat_id ." ORDER BY charge_code ASC";
                //CONCAT(charge_code_sub_category_name, ' (',charge_code,')') AS charge_code_sub_category_name,period
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getExpenseItems () {
        $sql = "SELECT cate.charge_code_category_id,
                CONCAT(cate.charge_code_category_name, ' (',cate.charge_code,')') AS charge_code_category_name,
                grp.charge_code_group_name
                FROM bms_charge_code_category as cate
                INNER join bms_charge_code_cat_group as grp
                WHERE cate.charge_code_group_id != 5 and grp.charge_code_group_id=cate.charge_code_group_id ORDER BY cate.charge_code ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
}