<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_dp_receive_model extends CI_Model {
    function __construct () { parent::__construct(); }    
    
    
    
    function getLastDpReceiveNo ($no_format) {
        $sql = "SELECT doc_ref_no FROM bms_fin_deposit_receive WHERE doc_ref_no LIKE '".$no_format."%' ORDER BY depo_receive_id DESC LIMIT 1";
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

    function getDpReceiveForSummary ($property_id, $from, $to) {

        $sql = "SELECT a.depo_receive_id, doc_ref_no, DATE_FORMAT(deposit_date, '%d-%m-%Y') as deposit_date, c.unit_no, c.owner_name, amount, pmode_name, CONCAT(d.first_name,' ',d.last_name),
                DATE_FORMAT(a.created_date, '%d-%m-%Y') as created_date 
                FROM bms_fin_deposit_receive a  
                LEFT JOIN bms_fin_coa g ON g.coa_id = a.coa_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by
                LEFT JOIN bms_fin_payment_mode h on h.pmode_id = a.payment_mode
                WHERE a.property_id = '$property_id' 
                AND deposit_date BETWEEN '$from' AND '$to';";

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function getDpRefundForSummary ( $property_id, $from, $to ) {

        $sql = "SELECT a.doc_ref_no, DATE_FORMAT(a.depo_refund_date, '%d-%m-%Y') as depo_refund_date, c.unit_no, c.owner_name, a.amount, h.doc_ref_no as deposite_number, h.amount as deposite_amount, (h.amount - a.amount) as balance, g.coa_name, CONCAT(d.first_name,' ',d.last_name),
                DATE_FORMAT(a.created_date, '%d-%m-%Y' ) as created_date  
                FROM bms_fin_deposit_refund a  
                LEFT JOIN bms_fin_coa g ON g.coa_id = a.coa_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by
                LEFT JOIN bms_fin_deposit_refund h ON h.depo_receive_id = a.depo_receive_id
                WHERE a.property_id = '$property_id' 
                AND a.depo_refund_date BETWEEN '$from' AND '$to';";

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function getDepositeReceiveItemDetail ( $depo_receive_id ) {
        $sql = "SELECT coa_id as item_cat_id, sum(b.amount) as paid_amount
        FROM bms_fin_deposit_receive b 
        WHERE b.depo_receive_id = $depo_receive_id";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
}