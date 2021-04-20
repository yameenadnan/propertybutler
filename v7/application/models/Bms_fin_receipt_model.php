<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_receipt_model extends CI_Model {
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
    
    function getUnitForReceipt ($property_id,$block_id = 0,$unit_id = '') {
        $condi = '';
        if($block_id != 0)
            $condi = " AND block_id = '".$block_id."'";
        if($unit_id != 0 && $unit_id != '' )
            $condi .= " AND unit_id = '".$unit_id."'";
        $sql = "SELECT unit_id,unit_no,unit_status,floor_no,owner_name,gender,email_addr,contact_1,is_defaulter,block_id,
                b.opening_credit,b.open_credit_used  
                FROM bms_property_units a
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.coa_id 
                WHERE a.property_id = '".$property_id."' $condi
                ORDER BY unit_no";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function checkOpenCredit($unit_id) {
        $sql = "SELECT receipt_id FROM bms_fin_receipt WHERE unit_id =".$unit_id." AND open_credit > 0";
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
    
    
    function getLastReceiptNo ($no_format) {
        $sql = "SELECT receipt_no FROM bms_fin_receipt WHERE receipt_no LIKE '".$no_format."%' ORDER BY receipt_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insertReceipt ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_receipt', $data);
        return $this->db->insert_id();   
    }
    
    function updateReceipt ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_receipt', $data, array('receipt_id' => $id));   
    }
    
    function insertReceiptItem ($data) {        
        $this->db->insert('bms_fin_receipt_items', $data);        
    }
    
    function updateOpeningCreditUsed ($unit_id) {
        $sql = "UPDATE bms_property_units a,bms_fin_coa b 
                SET b.open_credit_used=1 
                WHERE b.coa_id=a.coa_id AND a.unit_id=".$unit_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        //return $query->result_array();        
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
    
    function updateReceiptItem ($data,$id) {        
        $this->db->update('bms_fin_receipt_items', $data,  array('receipt_item_id' => $id));
        //echo "<br />".$this->db->last_query(); exit;        
    }
    
    /*function insertOpenCredit ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_open_credit', $data);
        //return $this->db->insert_id();   
    }*/
    
    function getReceiptList ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='',$from= '',$to='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        
        if($unit_id != '') {            
            $cond .= " AND a.unit_id = ".$unit_id;
        } 
        
        if($from != '' && $to != '') {            
            $cond .= " AND a.receipt_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND a.receipt_date >= '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND a.receipt_date <= '$to'";
        }
        
        
        $sql = "SELECT receipt_id,property_name,receipt_no,unit_no,owner_name,receipt_date,
                paid_amount,payment_mode,open_credit,depo_receive_id,opening_credit
                FROM bms_fin_receipt a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();          
        
        $order_by = " ORDER BY receipt_date DESC, receipt_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }
    
    function getReceipt ($receipt_id) {       
        
        $sql = "SELECT receipt_id,property_id,block_id,unit_id,receipt_no,receipt_date,payment_mode,bank_id,bank,
                cheq_card_txn_no,cheq_txn_online_date,online_r_card_type,depo_receive_id,remarks,
                paid_amount,direct_receipt,opening_credit
                FROM bms_fin_receipt a                        
                WHERE receipt_id =".$receipt_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getReceiptItems ($receipt_id) {
        $sql = "SELECT receipt_item_id,receipt_id,item_cat_id,item_sub_cat_id,item_period,
                item_descrip,item_amount,paid_amount,bal_amount,bill_item_id
                FROM bms_fin_receipt_items a                        
                WHERE receipt_id =".$receipt_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getReceiptDetails ($receipt_id) {       
        
        $sql = "SELECT receipt_id,a.property_id,a.block_id,a.unit_id,a.payment_mode,g.doc_ref_no,
                b.property_name,b.jmb_mc_name,
                b.address_1,b.address_2,b.pin_code,b.city,b.phone_no,b.phone_no2,b.email_addr,e.state_name,f.country_name,
                c.unit_no,c.owner_name,
                receipt_no,receipt_date,a.remarks,paid_amount,open_credit,direct_receipt,opening_credit,
                a.bank,a.cheq_card_txn_no,a.online_r_card_type,a.cheq_txn_online_date,
                a.created_date,d.first_name,d.last_name
                FROM bms_fin_receipt a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id 
                LEFT JOIN bms_fin_deposit_receive g ON g.depo_receive_id=a.depo_receive_id                               
                WHERE receipt_id =".$receipt_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getReceiptItemsDetail ($receipt_id) {
        $sql = "SELECT receipt_item_id,receipt_id,a.item_cat_id,a.item_period,a.item_descrip,a.item_amount,
                a.paid_amount,a.bal_amount,b.coa_name,d.bill_no
                FROM bms_fin_receipt_items a 
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.item_cat_id
                LEFT JOIN bms_fin_bill_items c ON c.bill_item_id = a.bill_item_id
                LEFT JOIN bms_fin_bills d ON d.bill_id = c.bill_id                          
                WHERE receipt_id =".$receipt_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getBill ($bill_id) {       
        
        $sql = "SELECT bill_id,property_id,block_id,unit_id,bill_no,bill_date,bill_due_date,remarks,total_amount
                FROM bms_fin_bills a                        
                WHERE bill_id =".$bill_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getBillItems ($bill_id) {
        $sql = "SELECT bill_item_id,bill_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount
                FROM bms_fin_bill_items a                        
                WHERE bill_id =".$bill_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getBillItem ($bill_item_id) {
        $sql = "SELECT bill_item_id,bill_id,item_cat_id,item_sub_cat_id,item_period,item_descrip,item_amount,paid_amount,bal_amount
                FROM bms_fin_bill_items a                        
                WHERE bill_item_id =".$bill_item_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getReceiptItem ($receipt_item_id) {
        $sql = "SELECT receipt_item_id,receipt_id,paid_amount,bill_item_id
                FROM bms_fin_receipt_items a                        
                WHERE receipt_item_id =".$receipt_item_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function deleteReceiptItem ($receipt_item_id) {
        return $this->db->delete('bms_fin_receipt_items',array('receipt_item_id'=>$receipt_item_id));
    }
    
    function deleteReceiptItemByReceiptId ($receipt_id) {
        return $this->db->delete('bms_fin_receipt_items',array('receipt_id'=>$receipt_id));
    }
    
    function deleteReceipt ($receipt_id) {
        return $this->db->delete('bms_fin_receipt',array('receipt_id'=>$receipt_id));
    }
    
    
    function getReceiptForSummary ( $property_id,$from,$to ) {

        $sql = "SELECT a.receipt_id,receipt_no,DATE_FORMAT(receipt_date, '%d-%m-%Y') as receipt_date,unit_no,c.owner_name, a.paid_amount, a.opening_credit, CONCAT(d.first_name,' ',d.last_name) AS name,

                pmode_name, CONCAT_WS(' - ',bank,ctype_name,cheq_card_txn_no,cheq_txn_online_date) AS txn_details,

                remarks, f.coa_name, NULLIF(a.open_credit, 0) as open_credit 
                , 'receipt' AS type

                FROM bms_fin_receipt a

                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 

                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 

                LEFT JOIN bms_fin_payment_mode e ON e.pmode_id=a.payment_mode

                LEFT JOIN bms_fin_coa f ON f.coa_id=a.bank_id

                LEFT JOIN bms_fin_card_type g ON g.ctype_id=a.online_r_card_type                

                WHERE a.property_id=".$property_id." AND receipt_date BETWEEN '$from' AND '$to'

                AND a.receipt_id NOT IN ( SELECT receipt_id FROM bms_fin_debit_note )

                UNION 

                (SELECT aa.depo_receive_id as receipt_id,doc_ref_no as receipt_no,deposit_date as receipt_date,unit_no,cc.owner_name, aa.amount as paid_amount, '' as opening_credit, CONCAT(dd.first_name,' ',dd.last_name) as name,

                pmode_name, CONCAT_WS(' - ',bank,ctype_name,cheq_card_txn_no,cheq_txn_online_date) AS txn_details,

                remarks, ff.coa_name, '' as open_credit , 'deposit' as type

                FROM bms_fin_deposit_receive aa

                LEFT JOIN bms_property_units cc ON cc.unit_id = aa.unit_id 

                LEFT JOIN bms_staff dd ON dd.staff_id=aa.created_by 

                LEFT JOIN bms_fin_payment_mode ee ON ee.pmode_id=aa.payment_mode

                LEFT JOIN bms_fin_coa ff ON ff.coa_id=aa.bank_id

                LEFT JOIN bms_fin_card_type gg ON gg.ctype_id=aa.online_r_card_type                

                WHERE aa.property_id=".$property_id." AND deposit_date BETWEEN '$from' AND '$to');";




        $query = $this->db->query($sql);        

        //echo "<pre>".$this->db->last_query()."</pre>";

        return $query->result_array();

    }	
    
    function getReceiptItemsForSummary ($property_id,$from,$to) {
        $sql = "SELECT receipt_no,receipt_date,unit_no,c.owner_name,paid_amount,'0.00',CONCAT(d.first_name,' ',d.last_name),
                pmode_name,bank,ctype_name,
                cheq_card_txn_no,cheq_txn_online_date,remarks,bank_name        
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items b ON b.receipt_id = a.receipt_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_fin_payment_mode e ON e.pmode_id=a.payment_mode
                LEFT JOIN bms_fin_banks f ON f.bank_id=a.bank_id
                LEFT JOIN bms_fin_card_type g ON g.ctype_id=a.online_r_card_type                
                WHERE a.property_id=".$property_id." AND receipt_date BETWEEN '$from' AND '$to'";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";exit;
        return $query->result_array();
    }

    function getReceiptItemDetail ( $receipt_id ) {
        $sql = "SELECT item_cat_id, sum(a.paid_amount) as paid_amount
        FROM bms_fin_receipt_items a
        LEFT JOIN bms_fin_receipt c ON c.receipt_id = a.receipt_id 
        WHERE a.receipt_id = $receipt_id group by item_cat_id;";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getDepositeItemDetail ( $receipt_id ) {
        $sql = "SELECT coa_id as item_cat_id, sum(b.amount) as paid_amount
        FROM bms_fin_deposit_receive b 
        WHERE b.depo_receive_id = $receipt_id group by item_cat_id";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getUnappliedAmountList ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if($unit_id != '') {
            $cond .= " AND b.unit_id = ".$unit_id;
        }

        $sql = "SELECT a.receipt_id, b.unit_no, owner_name, receipt_no, receipt_date, open_credit FROM `bms_fin_receipt` a
        LEFT JOIN bms_property_units b ON a.unit_id = b.unit_id
        WHERE 1 = 1 
        AND open_credit > 0 ". $cond .
        " UNION
        SELECT a.coa_id AS receipt_id, b.unit_no, b.owner_name, 'COA OPENING CREDIT' AS receipt_no, a.opening_cr_date AS receipt_date, a.opening_credit AS open_credit
        FROM `bms_fin_coa` a
        LEFT JOIN bms_property_units b on b.coa_id = a.coa_id
        WHERE a.property_id = $property_id AND a.opening_credit > 0 AND a.coa_code LIKE '3000/%' AND a.open_credit_used = 0 " . $cond;
        ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();

        $order_by = " ORDER BY receipt_date DESC, receipt_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    }

}