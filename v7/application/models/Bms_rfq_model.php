<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_rfq_model extends CI_Model {
    function __construct () { parent::__construct(); }    
    
    
    
    function getVendorCategory () {
        $sql = "SELECT vendor_cat_id,vendor_cat_name FROM home_butler_vendor_cat ORDER BY vendor_cat_name ASC ";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getKeywords () {
        $sql = "SELECT DISTINCT trim(vendor_keywords) AS vendor_keywords FROM home_butler_vendor ORDER BY vendor_keywords ASC ";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getState () {
        $sql = "SELECT state_id,state_name FROM home_butler_vendor_state ORDER BY state_name ASC ";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getCity ($state_id) {
        $cond = '';
        if($state_id != 10) {
            $cond = "AND state_id IN (".$state_id.") ";
        }
        $sql = "SELECT city_id,city_name FROM home_butler_vendor_city WHERE 1=1 ".$cond."  ORDER BY city_name ASC ";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getVendor ($s_type,$val,$state,$city) {
        $cond = '';
        if($s_type == 'cat') {
            $cond = " AND vendor_catgory  =".$val. " ";
        } else if($s_type == 'kw') {
            $val_arr = explode(',',$val);
            if(count($val_arr) > 1) {
                $cond .= " AND (";
                foreach ($val_arr as $key=>$val) {
                    $cond .= "vendor_keywords LIKE '%".$val."%' OR ";
                }    
                $cond = rtrim($cond, 'OR ').")";                
            } else {
                $cond = " AND vendor_keywords LIKE '%".$val."%'";
            }            
        }
        
        $cond2 = '';
        if($state != '' && $state != '10') {
            $cond2 = " AND vendor_state IN(".$state.") ";
        }
        if($city != '') {
            $cond2 .= " AND vendor_city IN(".$city.") ";
        }
        
        $sql = "SELECT vendor_name FROM home_butler_vendor WHERE 1=1 ".$cond2 . $cond."  ORDER BY vendor_name ASC ";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    
    function insertDpReceive ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_deposit_receive', $data);
        return $this->db->insert_id();   
    }
    
    function updateDpReceive ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_deposit_receive', $data, array('depo_receive_id' => $id));   
    }
    
    function getDpReceiveList ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='',$from= '',$to='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        
        if($unit_id != '') {            
            $cond .= " AND a.unit_id = ".$unit_id;
        } 
        
        if($from != '' && $to != '') {            
            $cond .= " AND a.deposit_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND a.deposit_date >= '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND a.deposit_date <= '$to'";
        }
        
        
        $sql = "SELECT depo_receive_id,property_name,doc_ref_no,unit_no,owner_name,deposit_date,amount,payment_mode,refund_status
                FROM bms_fin_deposit_receive a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY deposit_date DESC, depo_receive_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function getDpReceive ($depo_receive_id) {       
        
        $sql = "SELECT depo_receive_id,property_id,block_id,unit_id,doc_ref_no,deposit_date,payment_mode,bank_id,bank,
                cheq_card_txn_no,cheq_txn_online_date,online_r_card_type,remarks,coa_id,description,amount
                FROM bms_fin_deposit_receive a                        
                WHERE depo_receive_id =".$depo_receive_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getDpReceiveDetails ($depo_receive_id) {       
        
        $sql = "SELECT depo_receive_id,a.property_id,a.block_id,a.unit_id,b.property_name,b.jmb_mc_name,
                b.address_1,b.address_2,b.pin_code,b.city,b.phone_no,b.phone_no2,b.email_addr,e.state_name,f.country_name,
                c.unit_no,c.owner_name,
                doc_ref_no,deposit_date,payment_mode,remarks,amount,
                bank,a.cheq_card_txn_no,online_r_card_type,cheq_txn_online_date,
                a.created_date,d.first_name,d.last_name,g.coa_name,a.description
                FROM bms_fin_deposit_receive a  
                LEFT JOIN bms_fin_coa g ON g.coa_id = a.coa_id
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id                                
                WHERE depo_receive_id =".$depo_receive_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }    
    
    
    function deleteDpReceive ($depo_receive_id) {
        return $this->db->delete('bms_fin_deposit_receive',array('depo_receive_id'=>$depo_receive_id));
    }
}