<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_dp_refund_model extends CI_Model {
    function __construct () { parent::__construct(); }    
    
    function checkOpenCredit($unit_id) {
        $sql = "SELECT depo_receive_id FROM bms_fin_deposit_receive WHERE unit_id =".$unit_id." AND open_credit > 0";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getOutstandingBillIds ($unit_id) {                
        $sql = "SELECT GROUP_CONCAT(bill_id) AS bill_ids
                FROM bms_fin_bills a                        
                WHERE unit_id =".$unit_id." AND bill_paid_status=0";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getOutstandingBillItems ($unit_id) {
        $sql = "SELECT bill_item_id,bill_id,item_cat_id,item_period,item_descrip,item_amount,bal_amount
                FROM bms_fin_bill_items a                        
                WHERE bill_id IN (
                    SELECT bill_id FROM bms_fin_bills a WHERE unit_id =".$unit_id." AND bill_paid_status=0) 
                    AND paid_status=0";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    
    function getLastDpReceiveNo ($bill_no_format) {
        $sql = "SELECT doc_ref_no FROM bms_fin_deposit_receive WHERE doc_ref_no LIKE '".$bill_no_format."%' ORDER BY depo_receive_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
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
    
    function insertDpReceiveItem ($data) {        
        $this->db->insert('bms_fin_deposit_receive_items', $data);        
    }
    
    function getBillItemPaidStatusForBill ($ids) {
        /*$data['paid_status'] = 1;
        $this->db->where_in('bill_item_id',$ids);
        $this->db->update('bms_fin_bill_items', $data);*/
        $sql = "SELECT bill_id,  SUM(paid_status) AS paid_cnt, COUNT(bill_id) AS bill_cnt FROM bms_fin_bill_items 
                WHERE bill_id IN (SELECT bill_id FROM bms_fin_bill_items WHERE bill_item_id IN (".$ids."))
                GROUP BY bill_id";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function setBillAsPaid ($id) {
        $data['bill_paid_status'] = 1;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->where('bill_id',$id);
        $this->db->update('bms_fin_bills', $data);
        //echo "<br />".$this->db->last_query();        
    }
    function setBillAsUnPaid ($id) {
        $data['bill_paid_status'] = 0;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->where('bill_id',$id);
        $this->db->update('bms_fin_bills', $data);
        //echo "<br />".$this->db->last_query();        
    }
    
    function setBillItem ($id,$data) {        
        $this->db->where('bill_item_id',$id);
        $this->db->update('bms_fin_bill_items', $data);
        //echo "<br />".$this->db->last_query();
    }
    
    function updateDpReceiveItem ($data,$id) {        
        $this->db->update('bms_fin_deposit_receive_items', $data,  array('dp_refund_item_id' => $id));
        //echo "<br />".$this->db->last_query(); exit;        
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
    
    
    function getDpReceiveForSummary ($property_id,$from,$to) {
        $sql = "SELECT doc_ref_no,deposit_date,unit_no,c.owner_name,amount,'0.00',CONCAT(d.first_name,' ',d.last_name),
                pmode_name,bank,ctype_name,
                cheq_card_txn_no,cheq_txn_online_date,remarks,bank_name        
                FROM bms_fin_deposit_receive a
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_fin_payment_mode e ON e.pmode_id=a.payment_mode
                LEFT JOIN bms_fin_banks f ON f.bank_id=a.bank_id
                LEFT JOIN bms_fin_card_type g ON g.ctype_id=a.online_r_card_type                
                WHERE a.property_id=".$property_id." AND deposit_date BETWEEN '$from' AND '$to'";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function getDpReceiveItemsForSummary ($property_id,$from,$to) {
        $sql = "SELECT doc_ref_no,deposit_date,unit_no,c.owner_name,amount,'0.00',CONCAT(d.first_name,' ',d.last_name),
                pmode_name,bank,ctype_name,
                cheq_card_txn_no,cheq_txn_online_date,remarks,bank_name        
                FROM bms_fin_deposit_receive a
                LEFT JOIN bms_fin_deposit_receive_items b ON b.depo_receive_id = a.depo_receive_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_fin_payment_mode e ON e.pmode_id=a.payment_mode
                LEFT JOIN bms_fin_banks f ON f.bank_id=a.bank_id
                LEFT JOIN bms_fin_card_type g ON g.ctype_id=a.online_r_card_type                
                WHERE a.property_id=".$property_id." AND deposit_date BETWEEN '$from' AND '$to'";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";exit;
        return $query->result_array();
    }
}