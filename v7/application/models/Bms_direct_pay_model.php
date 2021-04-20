<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_direct_pay_model extends CI_Model {
    function __construct () { parent::__construct(); }

    function getPayPropertyDetails ($property_id) {

        $sql = "SELECT property_id, pymt_gateway_url, payment_bear_by, payment_fpx, payment_cc_card,  
                payment_merchant_id,payment_merchant_key_index,payment_merchant_key,
                property_name,email_addr,phone_no,phone_no2
                FROM bms_property WHERE property_status=1 AND property_id = '".$property_id."' ";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    function getPayUnit ($property_id) {
       
        $sql = "SELECT unit_id,unit_no,email_addr FROM bms_property_units 
                WHERE property_id = '".$property_id."' ORDER BY unit_no";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result_array();
    }
    
    function direct_pay_web_insert ($data) {
        $this->db->insert('bms_direct_pymt_web', $data);
    }
    
    function getPaymentsList ($offset = '0', $per_page = '25', $property_id, $from ='', $to ='', $search_txt='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        $cond = '';
        if($from != '' && $to != '') {            
            $cond .= " AND DATE_FORMAT(Response_Datetime, '%Y-%m-%d') BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND DATE_FORMAT(Response_Datetime, '%Y-%m-%d') = '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND DATE_FORMAT(Response_Datetime, '%Y-%m-%d') = '$to'";
        }
        $sql = "SELECT Transaction_ID,Reference_Number,Payment_ID,pymt_for, Amount, DATE_FORMAT(Response_Datetime, '%d-%m-%Y') AS trans_date
                FROM bms_direct_pymt_web 
                WHERE property_id = '".$property_id."' ". $cond ."
                UNION 
                SELECT Transaction_ID,Reference_Number,Payment_ID,'Mobile' AS pymt_for, Amount, DATE_FORMAT(Response_Datetime, '%d-%m-%Y')  AS trans_date
                FROM bms_direct_pymt
                WHERE property_id = '".$property_id."' ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();       
        
        $sql .= " ORDER BY trans_date DESC ".$limit;
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }
   
}