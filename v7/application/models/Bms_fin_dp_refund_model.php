<?php defined('BASEPATH') or exit('No direct script access allowed');

Class bms_fin_dp_refund_model extends CI_Model {
    function __construct () { parent::__construct(); } 
    
    function getLastDpRefundNo ($no_format) {
        $sql = "SELECT doc_ref_no FROM bms_fin_deposit_refund WHERE doc_ref_no LIKE '".$no_format."%' ORDER BY depo_refund_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_unit_deposits ($unit_id,$depo_receive_id='') {
        $sql = "SELECT depo_receive_id,doc_ref_no,amount,coa_id,description               
                FROM bms_fin_deposit_receive a                                
                WHERE unit_id =".$unit_id." 
                AND refund_status=0";
        if(!empty($depo_receive_id)) 
            $sql .= " OR depo_receive_id=".$depo_receive_id;
        $sql .= " ORDER BY doc_ref_no";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function insertDpRefund ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_deposit_refund', $data);
        return $this->db->insert_id();   
    }
    
    function updateDpRefund ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_deposit_refund', $data, array('depo_refund_id' => $id));   
    }
    
    function setDepositRefundStatus ($id) {
        $data['refund_status'] = 1;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->where('depo_receive_id',$id);
        $this->db->update('bms_fin_deposit_receive', $data);
        //echo "<br />".$this->db->last_query();        
    }   
    
    function getDpRefundList ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='',$from= '',$to='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        
        if($unit_id != '') {            
            $cond .= " AND a.unit_id = ".$unit_id;
        } 
        
        if($from != '' && $to != '') {            
            $cond .= " AND a.depo_refund_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND a.depo_refund_date >= '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND a.depo_refund_date <= '$to'";
        }
        
        
        $sql = "SELECT depo_refund_id,depo_receive_id,property_name,doc_ref_no,unit_no,owner_name,depo_refund_date,amount,payment_mode
                FROM bms_fin_deposit_refund a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY depo_refund_date DESC, depo_refund_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function getDpRefund ($depo_refund_id) {       
        
        $sql = "SELECT depo_refund_id,depo_receive_id,property_id,block_id,unit_id,doc_ref_no,depo_refund_date,payment_mode,bank_id,bank,
                cheq_card_txn_no,cheq_txn_online_date,online_r_card_type,remarks,coa_id,description,amount
                FROM bms_fin_deposit_refund a                        
                WHERE depo_refund_id =".$depo_refund_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
   
    function getDpRefundDetails ($depo_refund_id) {       
        
        $sql = "SELECT depo_refund_id,a.property_id,a.block_id,a.unit_id,b.property_name,b.jmb_mc_name,
                b.address_1,b.address_2,b.pin_code,b.city,b.phone_no,b.phone_no2,b.email_addr,e.state_name,f.country_name,
                c.unit_no,c.owner_name,
                doc_ref_no,depo_refund_date,payment_mode,remarks,amount,
                bank,a.cheq_card_txn_no,online_r_card_type,cheq_txn_online_date,
                a.created_date,d.first_name,d.last_name,g.coa_name,a.description
                FROM bms_fin_deposit_refund a  
                LEFT JOIN bms_fin_coa g ON g.coa_id = a.coa_id
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id                                
                WHERE depo_refund_id =".$depo_refund_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }    
    
    function unsetDpReceiveStatus ($id) {
        $data['refund_status'] = 0;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->where('depo_receive_id',$id);
        $this->db->update('bms_fin_deposit_receive', $data);
        //echo "<br />".$this->db->last_query();        
    }            
    
    function deleteDpRefund ($depo_refund_id) {
        return $this->db->delete('bms_fin_deposit_refund',array('depo_refund_id'=>$depo_refund_id));
    }
    
    
    /*function getDpRefundForSummary ($property_id,$from,$to) {
        $sql = "SELECT doc_ref_no,deposit_date,unit_no,c.owner_name,amount,'0.00',CONCAT(d.first_name,' ',d.last_name),
                pmode_name,bank,ctype_name,
                cheq_card_txn_no,cheq_txn_online_date,remarks,bank_name        
                FROM bms_fin_deposit_refund a
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_fin_payment_mode e ON e.pmode_id=a.payment_mode
                LEFT JOIN bms_fin_banks f ON f.bank_id=a.bank_id
                LEFT JOIN bms_fin_card_type g ON g.ctype_id=a.online_r_card_type                
                WHERE a.property_id=".$property_id." AND deposit_date BETWEEN '$from' AND '$to'";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }*/
    
    
}