<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_accounting_model extends CI_Model {
    function __construct () { parent::__construct(); }
    
    function getAllCoa ($property_id) {
        $sql = "SELECT coa_id,CONCAT(coa_name,' (',coa_code,')') AS coa_name,coa_type_id
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." 
                ORDER BY coa_code ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getLastJvNo ($jv_no_format) {
        $sql = "SELECT jv_no FROM bms_fin_journal_entry WHERE jv_no LIKE '".$jv_no_format."%' ORDER BY jv_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insertJv ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_journal_entry', $data);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $this->db->insert_id();   
    }
    
    function updateJv ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_journal_entry', $data, array('jv_id' => $id));   
    }
    
    function insertJvItem ($data) {        
        $this->db->insert('bms_fin_journal_entry_item', $data);
        //echo "<br /><br /><pre>".$this->db->last_query()."</pre>";        
    }
    
    function updateJvItem ($data,$id) {        
        $this->db->update('bms_fin_journal_entry_item', $data,  array('jv_item_id' => $id));        
    }
    
    function getJvsList ($offset = '0', $per_page = '25', $property_id ='', $from= '',$to='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        
        if($from != '' && $to != '') {            
            $cond .= " AND a.jv_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND a.jv_date >= '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND a.jv_date <= '$to'";
        }
        
        $sql_cnt = "SELECT COUNT(a.jv_id) AS num_rows 
                FROM bms_fin_journal_entry a  
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id
                WHERE 1=1 ". $cond;
        
        $sql = "SELECT a.jv_id,a.jv_no,a.jv_date,c.coa_name,b.description,b.debit,b.credit                
                FROM bms_fin_journal_entry a  
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id                               
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id
        $query = $this->db->query($sql_cnt);
        $num_rows = $query->row_array();          
        
        $order_by = " ORDER BY jv_date DESC, jv_time DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);    
    }
    
    function getJv ($jv_id) {       
        
        $sql = "SELECT jv_id,property_id,jv_no,jv_date,remarks
                FROM bms_fin_journal_entry a                        
                WHERE jv_id =".$jv_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getJvItems ($jv_id) {
        $sql = "SELECT jv_item_id,jv_id,jv_coa_id,description,debit,credit
                FROM bms_fin_journal_entry_item a                        
                WHERE jv_id =".$jv_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function deleteJvItem ($jv_item_id) {
        return $this->db->delete('bms_fin_journal_entry_item',array('jv_item_id'=>$jv_item_id));
    }
    
    function deleteJvItemByJvId ($jv_id) {
        return $this->db->delete('bms_fin_journal_entry_item',array('jv_id'=>$jv_id));
    }
    
    function deleteJv ($jv_id) {
        return $this->db->delete('bms_fin_journal_entry',array('jv_id'=>$jv_id));
    }
    
    function get_coa ($property_id) {
        $sql = "SELECT coa_id,coa_code,coa_name,coa_type_id,payment_source,payment_enabled,deposit_enabled,
                bill_enabled,receipt_enabled,opening_debit,opening_credit 
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." AND coa_code NOT LIKE '4100/%' AND coa_code NOT LIKE '3000/%'
                UNION 
                SELECT coa_id,coa_code,coa_name,coa_type_id,payment_source,payment_enabled,deposit_enabled,
                bill_enabled,receipt_enabled,opening_debit,opening_credit 
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." AND coa_code IN('3000/000','4100/000')
                ORDER BY coa_code ASC";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    /** Pay & Receipt Enabled Start */
    function get_pay_n_receipt_bf_debit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND receipt_date < '$from'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE e.property_id = ".$property_id." AND  f.item_cat_id=".$item_cat_id ." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_n_receipt_bf_credit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.pay_net_amount) AS amount
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                WHERE c.pay_property_id = ".$property_id." AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date < '$from' 
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit > 0
                ) bf_credit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_n_receipt_ena ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                b.paid_amount AS amount,'debit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt a
                JOIN bms_fin_receipt_items b ON  b.receipt_id=a.receipt_id
                JOIN bms_property_units x ON  x.unit_id=a.unit_id
                WHERE a.property_id = ".$property_id." AND b.item_cat_id=".$item_cat_id ." AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT debit_note_date AS doc_date,debit_note_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                h.paid_amount AS amount,'credit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g
                JOIN bms_fin_debit_note_items h ON  g.debit_note_id=h.debit_note_id
                JOIN bms_property_units x ON  x.unit_id=g.unit_id
                WHERE  g.property_id = ".$property_id." AND h.item_cat_id=".$item_cat_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                UNION 
                SELECT  pay_date AS doc_date,pay_no AS doc_no,provider_name AS descrip,
                d.pay_net_amount AS amount,'credit' as item_type, pay_time AS doc_time
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id     
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.pay_service_provider_id            
                WHERE c.pay_property_id = ".$property_id." AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date BETWEEN '$from' AND '$to'
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0                          
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    /** Pay & Receipt Enabled End */
    
    
    /** Bill & Receipt Enabled Start */
    function get_bill_receipt_ena_bf_debit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(f.adj_amount) AS amount
                FROM bms_fin_credit_note e
                JOIN bms_fin_credit_note_items f ON  f.credit_note_id=e.credit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND credit_note_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }    
    
    function get_bill_receipt_ena_bf_credit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(item_amount) AS amount
                FROM bms_fin_bills a
                JOIN bms_fin_bill_items b ON  b.bill_id=a.bill_id
                WHERE  a.property_id = ".$property_id." AND b.item_cat_id=".$item_cat_id ." AND bill_date < '$from' 
                UNION
                SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE  c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date < '$from'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit > 0) bf_debit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }  
    
    
    function get_bill_receipt_ena ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT bill_date AS doc_date,bill_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                item_amount AS amount, 'credit' as item_type, bill_time AS doc_time
                FROM bms_fin_bills a
                JOIN bms_fin_bill_items b ON  b.bill_id=a.bill_id
                JOIN bms_property_units x ON  x.unit_id=a.unit_id
                WHERE a.property_id = ".$property_id." AND b.item_cat_id=".$item_cat_id ." AND bill_date BETWEEN '$from' AND '$to'
                UNION                
                SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                c.paid_amount AS amount,'credit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                JOIN bms_property_units x ON  x.unit_id=c.unit_id
                WHERE c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT credit_note_date AS doc_date,credit_note_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                f.adj_amount AS amount,'debit' as item_type, credit_note_time AS doc_time
                FROM bms_fin_credit_note e
                JOIN bms_fin_credit_note_items f ON  e.credit_note_id=f.credit_note_id
                JOIN bms_property_units x ON  x.unit_id=e.unit_id
                WHERE e.property_id=".$property_id ." AND  f.item_cat_id=".$item_cat_id ." AND credit_note_date BETWEEN '$from' AND '$to'
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0                               
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    /** Bill & Receipt Enabled End */
    
    /** Receipt Enabled start */
    function get_receipt_ena_bf_debit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_receipt_ena_bf_credit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE  c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date < '$from'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit > 0
                ) bf_credit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_receipt_ena ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip) AS descrip,
                c.paid_amount AS amount,'credit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                JOIN bms_property_units x ON  x.unit_id=c.unit_id
                WHERE c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date BETWEEN '$from' AND '$to'
                UNION                   
                SELECT debit_note_date AS doc_date,debit_note_no AS doc_no,CONCAT(x.unit_no,' ',item_descrip)  AS descrip,
                h.paid_amount AS amount,'credit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g
                JOIN bms_fin_debit_note_items h ON  g.debit_note_id=h.debit_note_id
                JOIN bms_property_units x ON  x.unit_id=g.unit_id
                WHERE g.property_id = ".$property_id." AND h.item_cat_id=".$item_cat_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0                                
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    /** Receipt Enabled End */
    
    /** Deposit Enabled start */
    function get_deposit_ena_bf_debit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.amount) AS amount
                FROM bms_fin_deposit_refund c
                WHERE  c.property_id = ".$property_id." AND c.coa_id=".$item_cat_id ." AND depo_refund_date < '$from'
                UNION                 
                SELECT SUM(f.amount) AS amount
                FROM bms_fin_receipt e
                JOIN bms_fin_deposit_receive f ON  f.depo_receive_id=e.depo_receive_id
                WHERE  e.property_id = ".$property_id." AND f.coa_id=".$item_cat_id ." AND receipt_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0
                ) bf_debit               
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_deposit_ena_bf_credit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit  > 0
                UNION                 
                SELECT SUM(e.amount) AS amount
                FROM bms_fin_deposit_receive e                
                WHERE  e.property_id = ".$property_id." AND e.coa_id=".$item_cat_id ." AND deposit_date < '$from'
                ) bf_credit 
                ";
                
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_deposit_ena ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',description) AS descrip,
                d.amount AS amount,'debit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                JOIN bms_fin_deposit_receive d ON  c.depo_receive_id = d.depo_receive_id
                JOIN bms_property_units x ON  x.unit_id=c.unit_id
                WHERE c.property_id = ".$property_id." AND d.coa_id=".$item_cat_id ." AND receipt_date BETWEEN '$from' AND '$to'
                UNION                   
                SELECT depo_refund_date AS doc_date,doc_ref_no AS doc_no,CONCAT(x.unit_no,' ',description)  AS descrip,
                g.amount AS amount,'debit' as item_type, depo_refund_time AS doc_time
                FROM bms_fin_deposit_refund g
                JOIN bms_property_units x ON  x.unit_id=g.unit_id
                WHERE g.property_id = ".$property_id." AND g.coa_id=".$item_cat_id ." AND depo_refund_date BETWEEN '$from' AND '$to'
                UNION    
                SELECT deposit_date AS doc_date,doc_ref_no AS doc_no,CONCAT(x.unit_no,' ',description)  AS descrip,
                h.amount AS amount,'credit' as item_type, deposit_time AS doc_time
                FROM bms_fin_deposit_receive h
                JOIN bms_property_units x ON  x.unit_id=h.unit_id
                WHERE h.property_id = ".$property_id." AND h.coa_id=".$item_cat_id ." AND deposit_date BETWEEN '$from' AND '$to'
                UNION             
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit  AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0                                
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<br /><pre>".$this->db->last_query()."</pre>"; 
        return $query->result_array();
    }
    /** Deposit Enabled End */
    
    /** Payment Source Enabled Start */
    function get_pay_sour_ena_bf_debit ($property_id,$bank_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND receipt_date < '$from'
                UNION                 
                SELECT SUM(e.amount) AS amount
                FROM bms_fin_deposit_receive e                
                WHERE  e.property_id = ".$property_id." AND e.bank_id=".$bank_id ." AND deposit_date < '$from'               
                UNION
                SELECT SUM(g.total_amount) AS amount
                FROM bms_fin_ap_debit_note g                          
                WHERE  g.property_id = ".$property_id." AND g.bank_id=".$bank_id ." AND debit_note_date < '$from'    
                UNION
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date < '$from' AND q.debit > 0
                ) bf_debit ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_sour_ena_bf_credit ($property_id,$bank_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.pay_total) AS amount
                FROM bms_fin_payment c                
                WHERE c.pay_property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND c.pay_date < '$from'
                UNION   
                SELECT SUM(c.amount) AS amount
                FROM bms_fin_deposit_refund c
                WHERE  c.property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND depo_refund_date < '$from'
                UNION               
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_debit_note e                
                WHERE e.property_id = ".$property_id." AND  e.bank_id=".$bank_id ." AND debit_note_date < '$from'
                UNION
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date < '$from' AND q.credit > 0
                ) bf_credit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_sour_ena ($property_id,$bank_id,$from,$to) {
        $sql = "SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',a.remarks) AS descrip,
                a.paid_amount AS amount,'debit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt a 
                JOIN bms_property_units x ON  x.unit_id=a.unit_id                
                WHERE a.property_id = ".$property_id." AND a.bank_id=".$bank_id ." AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT debit_note_date AS doc_date,debit_note_no AS doc_no,CONCAT(x.unit_no,' ',g.remarks) AS descrip,
                g.total_amount AS amount,'credit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g  
                JOIN bms_property_units x ON  x.unit_id=g.unit_id              
                WHERE  g.property_id = ".$property_id." AND g.bank_id=".$bank_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                UNION 
                SELECT  pay_date AS doc_date,pay_no AS doc_no,IFNULL(provider_name,pay_service_provider_name) AS descrip,
                c.pay_total AS amount,'credit' as item_type, pay_time AS doc_time
                FROM bms_fin_payment c 
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.pay_service_provider_id
                WHERE c.pay_property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND c.pay_date BETWEEN '$from' AND '$to'
                UNION
                SELECT debit_note_date AS doc_date,ap_dn_no AS doc_no,provider_name AS descrip,
                g.total_amount AS amount,'debit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_ap_debit_note g
                LEFT JOIN bms_service_provider y ON y.service_provider_id = g.service_provider_id          
                WHERE  g.property_id = ".$property_id." AND g.bank_id=".$bank_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                UNION                   
                SELECT depo_refund_date AS doc_date,doc_ref_no AS doc_no,CONCAT(x.unit_no,' ',description)  AS descrip,
                g.amount AS amount,'credit' as item_type, depo_refund_time AS doc_time
                FROM bms_fin_deposit_refund g
                JOIN bms_property_units x ON  x.unit_id=g.unit_id
                WHERE g.property_id = ".$property_id." AND g.bank_id=".$bank_id ." AND depo_refund_date BETWEEN '$from' AND '$to'
                UNION
                SELECT deposit_date AS doc_date,doc_ref_no AS doc_no,CONCAT(x.unit_no,' ',description)  AS descrip,
                h.amount AS amount,'debit' as item_type, deposit_time AS doc_time
                FROM bms_fin_deposit_receive h
                JOIN bms_property_units x ON  x.unit_id=h.unit_id
                WHERE h.property_id = ".$property_id." AND h.bank_id=".$bank_id ." AND deposit_date BETWEEN '$from' AND '$to'
                UNION                  
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0                             
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    /** Payment Source Enabled End */
    
    /** Payment Enabled Start */ 
    function get_pay_ena_bf_debit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.net_amount) AS amount
                FROM bms_fin_expense_invoice c
                JOIN bms_fin_exp_inv_items d ON  d.exp_id=c.exp_inv_id
                WHERE c.property_id = ".$property_id." AND d.coa_id=".$item_cat_id ." AND exp_date < '$from'
                UNION
                SELECT SUM(d.pay_net_amount) AS amount
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                WHERE c.pay_property_id = ".$property_id." AND pay_einv_no = 0 AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date < '$from'
                UNION                
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_ena_bf_credit ($property_id,$item_cat_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit > 0
                UNION                
                SELECT SUM(y.adj_amount) AS amount
                FROM bms_fin_ap_credit_note x
                JOIN bms_fin_ap_credit_note_items y ON  y.ap_cr_id=x.ap_cr_id
                WHERE x.property_id = ".$property_id." AND  y.coa_id=".$item_cat_id ." AND credit_note_date < '$from' AND y.adj_amount > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_pay_ena ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT  exp_date AS doc_date,exp_doc_no AS doc_no,provider_name AS descrip,
                b.net_amount AS amount,'debit' as item_type, exp_time AS doc_time
                FROM bms_fin_expense_invoice a                 
                JOIN bms_fin_exp_inv_items b ON  b.exp_id=a.exp_inv_id
                LEFT JOIN bms_service_provider y ON y.service_provider_id = a.service_provider_id
                WHERE a.property_id = ".$property_id." AND b.coa_id=".$item_cat_id ." AND a.exp_date BETWEEN '$from' AND '$to'
                UNION                
                SELECT credit_note_date AS doc_date,ap_cr_no AS doc_no,provider_name AS descrip,
                y.adj_amount AS amount,'credit' as item_type, credit_note_time AS doc_time
                FROM bms_fin_ap_credit_note x
                JOIN bms_fin_ap_credit_note_items y ON  y.ap_cr_id=x.ap_cr_id
                LEFT JOIN bms_service_provider z ON z.service_provider_id = x.service_provider_id
                WHERE x.property_id = ".$property_id." AND  y.coa_id=".$item_cat_id ." AND credit_note_date BETWEEN '$from' AND '$to' AND y.adj_amount > 0
                UNION 
                SELECT  pay_date AS doc_date,pay_no AS doc_no,IFNULL(provider_name,pay_service_provider_name) AS descrip,
                d.pay_net_amount AS amount,'debit' as item_type, pay_time AS doc_time
                FROM bms_fin_payment c                 
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.pay_service_provider_id
                WHERE c.pay_property_id = ".$property_id." AND pay_einv_no=0 AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date BETWEEN '$from' AND '$to'
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0                              
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>"; exit;
        return $query->result_array();
    }    
    /** Payment Enabled End */ 
    
    /** Debtors Start */
    function get_debtors_bf_debit ($property_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(total_amount) AS amount
                FROM bms_fin_bills a                
                WHERE a.property_id=".$property_id ." AND bill_date < '$from' 
                UNION
                SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.property_id=".$property_id ." AND direct_receipt=1 AND receipt_date < '$from'
                UNION                 
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_debit_note e                
                WHERE e.property_id=".$property_id ." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date < '$from' AND q.debit > 0
                AND  q.jv_coa_id IN (
                    SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."
                    UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '3000/000')
                ) bf_debit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }   
    
    function get_debtors_bf_credit ($property_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.property_id=".$property_id ." AND receipt_date < '$from' 
                UNION
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_credit_note e                
                WHERE e.property_id=".$property_id ." AND credit_note_date < '$from'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date < '$from' AND q.credit > 0
                AND  q.jv_coa_id IN (
                    SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."
                    UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '3000/000')
                ) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";EXIT;
        return $query->row_array();
    }   
    
    function get_debtors_ledger ($property_id,$from,$to,$item_cat_id) {
        $sql = "SELECT bill_date AS doc_date,bill_no AS doc_no,CONCAT(x.unit_no,' ',remarks) AS descrip,
                total_amount AS amount, 'debit' as item_type, bill_time AS doc_time
                FROM bms_fin_bills a                
                JOIN bms_property_units x ON  x.unit_id=a.unit_id
                WHERE a.property_id=".$property_id ." AND bill_date BETWEEN '$from' AND '$to'
                UNION
                SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',remarks) AS descrip,
                c.paid_amount AS amount,'credit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c                
                JOIN bms_property_units x ON  x.unit_id=c.unit_id
                WHERE c.property_id=".$property_id ." AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT receipt_date AS doc_date,receipt_no AS doc_no,CONCAT(x.unit_no,' ',remarks) AS descrip,
                SUM(c.paid_amount) AS amount,'debit' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c                
                JOIN bms_property_units x ON  x.unit_id=c.unit_id
                WHERE c.property_id=".$property_id ." AND direct_receipt=1 AND receipt_date BETWEEN '$from' AND '$to'                
                UNION
                SELECT credit_note_date AS doc_date,credit_note_no AS doc_no,CONCAT(x.unit_no,' ',remarks) AS descrip,
                e.total_amount AS amount,'credit' as item_type, credit_note_time AS doc_time
                FROM bms_fin_credit_note e                
                JOIN bms_property_units x ON  x.unit_id=e.unit_id
                WHERE e.property_id=".$property_id ." AND credit_note_date BETWEEN '$from' AND '$to'
                UNION
                SELECT debit_note_date AS doc_date,debit_note_no AS doc_no,CONCAT(x.unit_no,' ',remarks) AS descrip,
                g.total_amount AS amount,'debit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g                
                JOIN bms_property_units x ON  x.unit_id=g.unit_id
                WHERE g.property_id=".$property_id ." AND debit_note_date BETWEEN '$from' AND '$to' 
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                AND  q.jv_coa_id IN (
                    SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."
                    UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '3000/000')
                    
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0
                AND  q.jv_coa_id IN (SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."
                UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '3000/000')                              
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>"; exit;
        return $query->result_array();
    }  
    /** Debtors End */ 
    
    /** creditors Start */ 
    function get_creditors_bf_debit ($property_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.pay_total) AS amount
                FROM bms_fin_payment c                
                WHERE c.pay_property_id = ".$property_id." AND pay_inv_id <> '0' AND pay_date < '$from'
                UNION 
                SELECT SUM(c.total_amount) AS amount
                FROM bms_fin_ap_credit_note c                
                WHERE c.property_id = ".$property_id." AND credit_note_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date  < '$from' AND q.debit > 0
                AND  q.jv_coa_id IN (SELECT coa_id from bms_service_provider WHERE property_id =".$property_id ."
                UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '4100/000')   
                ) bf_debit ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }   
    
    function get_creditors_bf_credit ($property_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(total) AS amount
                FROM bms_fin_expense_invoice a
                WHERE a.property_id=".$property_id ." AND exp_date < '$from'
                
                UNION
                SELECT SUM(c.total_amount) AS amount
                FROM bms_fin_ap_debit_note c                
                WHERE c.property_id = ".$property_id." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date  < '$from' AND q.credit > 0
                AND  q.jv_coa_id IN (SELECT coa_id from bms_service_provider WHERE property_id =".$property_id ."
                UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '4100/000')
                ) bf_credit ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();        
    }   
    
    function get_creditors ($property_id,$from,$to) {
        $sql = "SELECT exp_date AS doc_date,exp_doc_no AS doc_no, CONCAT(z.provider_name, ' - ', a.remarks) AS descrip,
                total AS amount, 'credit' as item_type, exp_time AS doc_time
                FROM bms_fin_expense_invoice a  
                LEFT JOIN bms_service_provider z ON z.service_provider_id = a.service_provider_id                          
                WHERE a.property_id=".$property_id ." AND exp_date BETWEEN '$from' AND '$to'
                UNION
                SELECT pay_date AS doc_date,pay_no AS doc_no, CONCAT(y.provider_name, ' - ', c.remarks) AS descrip,
                c.pay_total AS amount,'debit' as item_type, pay_time AS doc_time
                FROM bms_fin_payment c  
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.pay_service_provider_id               
                WHERE c.pay_property_id=".$property_id ." AND pay_inv_id <> '0'  AND pay_date BETWEEN '$from' AND '$to'                                             
                UNION
                
                SELECT credit_note_date AS doc_date,ap_cr_no AS doc_no, CONCAT(y.provider_name, ' - ', c.remarks) AS descrip,
                c.total_amount AS amount,'debit' as item_type, credit_note_time AS doc_time
                FROM bms_fin_ap_credit_note c  
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.service_provider_id               
                WHERE c.property_id=".$property_id ." AND credit_note_date BETWEEN '$from' AND '$to'
                UNION
                SELECT debit_note_date AS doc_date,ap_dn_no AS doc_no, CONCAT(y.provider_name, ' - ', c.remarks) AS descrip,
                c.total_amount AS amount,'credit' as item_type, debit_note_time AS doc_time
                FROM bms_fin_ap_debit_note c  
                LEFT JOIN bms_service_provider y ON y.service_provider_id = c.service_provider_id               
                WHERE c.property_id=".$property_id ." AND debit_note_date BETWEEN '$from' AND '$to'
                
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,CONCAT(y.provider_name, ' - ',q.description) AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                LEFT JOIN bms_service_provider y ON y.coa_id = q.jv_coa_id
                WHERE p.property_id = ".$property_id." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0
                AND  q.jv_coa_id IN (SELECT coa_id from bms_service_provider WHERE property_id =".$property_id ."
                UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '4100/000')                              
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,CONCAT(y.provider_name, ' - ',q.description) AS descrip,
                q.credit AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                LEFT JOIN bms_service_provider y ON y.coa_id = q.jv_coa_id
                WHERE p.property_id = ".$property_id." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0
                AND  q.jv_coa_id IN (SELECT coa_id from bms_service_provider WHERE property_id =".$property_id ."
                UNION 
                    SELECT coa_id from bms_fin_coa WHERE property_id =".$property_id ." AND coa_code = '4100/000')
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    /** Creditors End */ 
    
    /** Non Enabled start */
    function get_non_enabled_bf_debit ($property_id,$from,$item_cat_id) {
        $sql = "SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.debit > 0
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_non_enabled_bf_credit ($property_id,$from,$item_cat_id) {
        $sql = "SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date < '$from' AND q.credit  > 0
                ";
                
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_non_enabled_ledger ($property_id,$item_cat_id,$from,$to) {
        $sql = "SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.debit AS amount,'debit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.debit > 0
                UNION                 
                SELECT jv_date AS doc_date,jv_no AS doc_no,description AS descrip,
                q.credit  AS amount,'credit' as item_type, jv_time AS doc_time
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  BETWEEN '$from' AND '$to' AND q.credit > 0                                
                ORDER BY doc_date ASC, doc_time ASC";
        $query = $this->db->query($sql);        
        //echo "<br /><pre>".$this->db->last_query()."</pre>"; 
        return $query->result_array();
    }
    /** Non Enabled End */
    
    function get_debtors_list ($property_id) {
        $sql = "SELECT unit_id,unit_no,owner_name,a.coa_id,coa_code
                FROM bms_property_units a
                JOIN bms_fin_coa b ON b.coa_id=a.coa_id
                WHERE a.property_id=".$property_id." ORDER BY coa_code ASC"; 
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();       
    } 
    
    function get_debtors_aging ($property_id,$from,$to='') {
        
        $cond = $to == '' ? " AND bill_date < '$from'" : " AND bill_date BETWEEN '$from' AND '$to'"; 
        
        $sql = "SELECT a.unit_id, SUM(bal_amount) AS amt, 0 AS ocu               
                FROM bms_fin_bills a
                JOIN bms_fin_bill_items b ON  b.bill_id=a.bill_id                
                WHERE a.property_id=".$property_id ." $cond GROUP BY a.unit_id "; // HAVING SUM(bal_amount) > 0
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();       
    } 
    
    function get_debtors_aging_credit ($property_id,$from,$to='') {
        
        $cond = $to == '' ? " AND receipt_date < '$from'" : " AND receipt_date BETWEEN '$from' AND '$to'"; 
        $cond2 = $to == '' ? " AND a.opening_cr_date < '$from'" : " AND a.opening_cr_date BETWEEN '$from' AND '$to'";
        $sql = "SELECT a.unit_id AS unit_id, SUM(a.open_credit) AS amt,0 AS ocu          
                FROM bms_fin_receipt a                           
                WHERE a.property_id=".$property_id ." AND a.open_credit > 0 $cond GROUP BY a.unit_id 
                UNION 
                SELECT b.unit_id AS unit_id, SUM(opening_credit) AS amt, a.open_credit_used AS ocu               
                FROM bms_fin_coa a  
                LEFT JOIN bms_property_units b ON b.coa_id = a.coa_id                          
                WHERE a.property_id=".$property_id ." AND opening_credit > 0 AND a.open_credit_used = 0 $cond2 GROUP BY a.coa_id  
                "; // HAVING SUM(bal_amount) > 0
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();       
    }
    
    function get_creditors_list ($property_id) {
        $sql = "SELECT service_provider_id,provider_name,a.coa_id,coa_code
                FROM bms_service_provider a
                JOIN bms_fin_coa b ON b.coa_id=a.coa_id
                WHERE a.property_id=".$property_id." ORDER BY coa_code ASC"; 
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();       
    } 
    
    function get_creditors_aging ($property_id,$from,$to='') {
        
        $cond = $to == '' ? " AND exp_date < '$from'" : " AND exp_date BETWEEN '$from' AND '$to'"; 
        
        $sql = "SELECT a.service_provider_id, SUM(balance_amount) AS amt                
                FROM bms_fin_expense_invoice a                               
                WHERE a.property_id=".$property_id ." $cond GROUP BY a.service_provider_id "; // HAVING SUM(balance_amount) > 0
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();       
    } 
    
    function getBankRecon ($property_id,$recon_date,$bank_id) {
        $sql = "SELECT bank_recon_id,smt_amt           
                FROM bms_fin_bank_recon a                               
                WHERE a.property_id=".$property_id ." AND coa_id = '".$bank_id."' AND bank_recon_date = '".$recon_date."'"; 
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array(); 
    }
    function getLastBankRecon ($property_id,$recon_date,$bank_id) {
        $sql = "SELECT smt_amt,bank_recon_date         
                FROM bms_fin_bank_recon a                               
                WHERE a.property_id=".$property_id ." AND coa_id = '".$bank_id."' AND bank_recon_date < '".$recon_date."'
                ORDER BY bank_recon_date DESC LIMIT 0,1"; 
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array(); 
    }
    
    function setBankReconInsert ($property_id,$recon_date,$bank_id,$amt) {
        $data['property_id'] = $property_id;
        $data['coa_id'] = $bank_id;
        $data['bank_recon_date'] = $recon_date;
        $data['smt_amt'] = $amt;
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_bank_recon', $data);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $this->db->insert_id();        
    }
    
    function setBankReconUpdate ($amt,$id) {
        $data['smt_amt'] = $amt;
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_bank_recon', $data, array('bank_recon_id' => $id));   
    }
    
    function getBankReconTrans ($property_id, $from,$bank_id) {
        
        $bank_recon_status = " AND (bank_recon <> 1 OR (bank_recon=1 AND bank_recon_date='".date('Y-m-d',strtotime($from))."'))";
        
        $sql = "SELECT receipt_id AS id, receipt_no AS ref_no_1,receipt_date AS doc_date,receipt_time AS doc_time,
                payment_mode AS pay_mod,cheq_card_txn_no AS ref_no_2,paid_amount AS debit, 
                '0.00' AS credit,bank_recon, 'receipt' AS acc_type 
                FROM bms_fin_receipt 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."' 
                AND receipt_date <= '".$from."' ".$bank_recon_status." 
                UNION 
                SELECT pay_id AS id,pay_no AS ref_no_1,pay_date AS doc_date,pay_time AS doc_time,pay_mod,
                cheq_card_txn_no AS ref_no_2,'0.00' AS debit, pay_total AS credit,bank_recon, 'payment' AS acc_type 
                FROM bms_fin_payment 
                WHERE pay_property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND pay_date <= '".$from."' ".$bank_recon_status." 
                UNION 
                SELECT depo_receive_id AS id,doc_ref_no AS ref_no_1,deposit_date AS doc_date,deposit_time AS doc_time,payment_mode AS pay_mod,
                cheq_card_txn_no AS ref_no_2,amount AS debit, '0.00' AS credit,bank_recon, 'dep_receive' AS acc_type 
                FROM bms_fin_deposit_receive 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND deposit_date <= '".$from."' ".$bank_recon_status." 
                UNION 
                SELECT depo_refund_id AS id,doc_ref_no AS ref_no_1,depo_refund_date AS doc_date,depo_refund_time AS doc_time,payment_mode AS pay_mod,
                cheq_card_txn_no AS ref_no_2,'0.00' AS debit, amount AS credit,bank_recon, 'dep_refund' AS acc_type 
                FROM bms_fin_deposit_refund 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND depo_refund_date <= '".$from."' ".$bank_recon_status." 
                UNION 
                SELECT jv_item_id AS id,jv_no AS ref_no_1,jv_date AS doc_date,jv_time AS doc_time,'' AS pay_mod,
                '' AS ref_no_2,debit AS debit, credit AS credit,bank_recon, 'journal' AS acc_type 
                FROM bms_fin_journal_entry_item x
                LEFT JOIN bms_fin_journal_entry y ON y.jv_id=x.jv_id
                LEFT JOIN bms_fin_coa z ON z.coa_id=x.jv_coa_id  
                WHERE y.property_id=".$property_id. " AND jv_coa_id='".$bank_id."'  
                AND jv_date <= '".$from."' ".$bank_recon_status." 
                ";
        
        $order_by = " ORDER BY doc_date DESC, doc_time DESC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br /><pre>".$this->db->last_query(); exit;
        return $query->result_array();
        //return array('numFound'=>$num_rows,'records'=>$data);    
    }
    
    function getUnpresentBankRecon ($property_id, $from,$bank_id) {
        
        $bank_recon_status = " AND bank_recon <> 1 ";
        
        $sql = "SELECT receipt_id AS id, receipt_no AS ref_no_1,receipt_date AS doc_date,receipt_time AS doc_time,
                payment_mode AS pay_mod,cheq_card_txn_no AS ref_no_2,paid_amount AS debit, 
                '0.00' AS credit,bank_recon, 'receipt' AS acc_type 
                FROM bms_fin_receipt 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."' 
                AND receipt_date <= '".$from."' ".$bank_recon_status."                 
                UNION 
                SELECT depo_receive_id AS id,doc_ref_no AS ref_no_1,deposit_date AS doc_date,deposit_time AS doc_time,payment_mode AS pay_mod,
                cheq_card_txn_no AS ref_no_2,amount AS debit, '0.00' AS credit,bank_recon, 'dep_receive' AS acc_type 
                FROM bms_fin_deposit_receive 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND deposit_date <= '".$from."' ".$bank_recon_status."                
                UNION 
                SELECT jv_item_id AS id,jv_no AS ref_no_1,jv_date AS doc_date,jv_time AS doc_time,'' AS pay_mod,
                '' AS ref_no_2,debit AS debit, credit AS credit,bank_recon, 'journal' AS acc_type 
                FROM bms_fin_journal_entry_item x
                LEFT JOIN bms_fin_journal_entry y ON y.jv_id=x.jv_id
                LEFT JOIN bms_fin_coa z ON z.coa_id=x.jv_coa_id  
                WHERE y.property_id=".$property_id. " AND jv_coa_id='".$bank_id."' AND debit > 0  
                AND jv_date <= '".$from."' ".$bank_recon_status." 
                ";
        
        $order_by = " ORDER BY doc_date DESC, doc_time DESC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br /><pre>".$this->db->last_query(); exit;
        return $query->result_array();
        //return array('numFound'=>$num_rows,'records'=>$data);    
    }
    
    function getUnclaimBankRecon ($property_id, $from,$bank_id) {
        
        $bank_recon_status = " AND bank_recon <> 1 ";
        
        $sql = "SELECT pay_id AS id,pay_no AS ref_no_1,pay_date AS doc_date,pay_time AS doc_time,pay_mod,
                cheq_card_txn_no AS ref_no_2,'0.00' AS debit, pay_total AS credit,bank_recon, 'payment' AS acc_type 
                FROM bms_fin_payment 
                WHERE pay_property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND pay_date <= '".$from."' ".$bank_recon_status."                
                UNION 
                SELECT depo_refund_id AS id,doc_ref_no AS ref_no_1,depo_refund_date AS doc_date,depo_refund_time AS doc_time,payment_mode AS pay_mod,
                cheq_card_txn_no AS ref_no_2,'0.00' AS debit, amount AS credit,bank_recon, 'dep_refund' AS acc_type 
                FROM bms_fin_deposit_refund 
                WHERE property_id=".$property_id. " AND bank_id='".$bank_id."'  
                AND depo_refund_date <= '".$from."' ".$bank_recon_status." 
                UNION 
                SELECT jv_item_id AS id,jv_no AS ref_no_1,jv_date AS doc_date,jv_time AS doc_time,'' AS pay_mod,
                '' AS ref_no_2,debit AS debit, credit AS credit,bank_recon, 'journal' AS acc_type 
                FROM bms_fin_journal_entry_item x
                LEFT JOIN bms_fin_journal_entry y ON y.jv_id=x.jv_id
                LEFT JOIN bms_fin_coa z ON z.coa_id=x.jv_coa_id  
                WHERE y.property_id=".$property_id. " AND jv_coa_id='".$bank_id."'  AND credit > 0  
                AND jv_date <= '".$from."' ".$bank_recon_status." 
                ";
        
        $order_by = " ORDER BY doc_date DESC, doc_time DESC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br /><pre>".$this->db->last_query(); exit;
        return $query->result_array();
        //return array('numFound'=>$num_rows,'records'=>$data);    
    }
    
    function setBankRecon ($id,$type,$val,$recon_date) {
        if($type == 'receipt'){
            $table = 'bms_fin_receipt';
            $id_col = 'receipt_id';
        } else {
            $table = 'bms_fin_payment';
            $id_col = 'pay_id';
        }
        $data['bank_recon'] = $val; 
        $data['bank_recon_date'] = $data['bank_recon'] == 1 ? $recon_date : '';      
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        return $this->db->update($table, $data, array($id_col => $id));           
    }
    
    function get_income_item ($property_id,$from,$to) {
        $sql = "SELECT item_cat_id, coa_name
                FROM bms_fin_bills a
                LEFT JOIN bms_fin_bill_items b ON b.bill_id = a.bill_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ."  AND coa_code <> '5001/000' AND bill_date BETWEEN '".$from."' AND '".$to."' GROUP BY  item_cat_id
                UNION 
                SELECT item_cat_id, coa_name
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items b ON b.receipt_id = a.receipt_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ."  AND coa_code <> '5001/000'  AND bill_item_id=0 AND receipt_date BETWEEN '".$from."' AND '".$to."' GROUP BY  item_cat_id 
                UNION 
                SELECT jv_coa_id AS item_cat_id, coa_name
                FROM bms_fin_journal_entry a
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id
                WHERE a.property_id = ".$property_id ." AND c.coa_code LIKE '5%' AND coa_code <> '5001/000' AND jv_date BETWEEN '".$from."' AND '".$to."' GROUP BY  jv_coa_id 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_income_item_cedit_amt ($property_id,$date,$item_id) {
        $sql = "SELECT SUM(amount) AS amount FROM (
                SELECT SUM(b.item_amount) AS amount
                FROM bms_fin_bills a
                LEFT JOIN bms_fin_bill_items b ON b.bill_id = a.bill_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ." AND b.item_cat_id = '".$item_id."' AND bill_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."'
                UNION 
                SELECT SUM(b.item_amount) AS amount
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items b ON b.receipt_id = a.receipt_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ." AND b.bill_item_id=0 AND b.item_cat_id = '".$item_id."'
                AND receipt_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."'
                UNION 
                SELECT SUM(b.credit) AS amount
                FROM bms_fin_journal_entry a
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id
                WHERE a.property_id = ".$property_id ."  AND b.jv_coa_id = '".$item_id."'
                AND jv_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."' AND b.credit > 0
                
                ) tbl"; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br /><br />".$this->db->last_query(); exit;
        return $query->row_array();
    }
    
    function get_income_item_debit_amt ($property_id,$date,$item_id) {
        $sql = "SELECT SUM(amount) AS amount FROM (
                SELECT SUM(b.debit) AS amount
                FROM bms_fin_journal_entry a
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id
                WHERE a.property_id = ".$property_id ."  AND b.jv_coa_id = '".$item_id."'
                AND jv_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."'  AND b.debit > 0
                ) tbl"; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br /><br />".$this->db->last_query(); exit;
        return $query->row_array();
    }
    
    function get_expense_item_amt ($property_id,$date,$item_id) {
        $sql = "SELECT SUM(amount) AS amount FROM (
                SELECT SUM(net_amount) AS amount
                FROM bms_fin_expense_invoice a
                LEFT JOIN bms_fin_exp_inv_items b ON b.exp_id = a.exp_inv_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.coa_id
                WHERE a.property_id = ".$property_id ." AND b.coa_id ='".$item_id."' AND exp_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."'
                UNION 
                SELECT SUM(pay_amount) AS amount
                FROM bms_fin_payment a
                LEFT JOIN bms_fin_payment_items b ON b.pay_id = a.pay_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.pay_coa_id
               WHERE a.pay_property_id = ".$property_id ." AND pay_einv_no=0 AND b.pay_coa_id ='".$item_id."' AND pay_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."' 
                UNION 
                SELECT SUM(b.debit) AS amount
                FROM bms_fin_journal_entry a
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id
                WHERE a.property_id = ".$property_id ."  AND b.jv_coa_id = '".$item_id."'
                AND jv_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."' 
                ) tbl"; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br /><br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_expense_item ($property_id,$from,$to) {
        $sql = "SELECT c.coa_id AS item_cat_id, coa_name
                FROM bms_fin_expense_invoice a
                LEFT JOIN bms_fin_exp_inv_items b ON b.exp_id = a.exp_inv_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.coa_id
                WHERE a.property_id = ".$property_id ." AND exp_date BETWEEN '".$from."' AND '".$to."' GROUP BY  b.coa_id
                UNION 
                SELECT pay_coa_id AS item_cat_id, coa_name
                FROM bms_fin_payment a
                LEFT JOIN bms_fin_payment_items b ON b.pay_id = a.pay_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.pay_coa_id
                WHERE a.pay_property_id = ".$property_id ." AND pay_einv_no=0 AND pay_date BETWEEN '".$from."' AND '".$to."' GROUP BY  pay_coa_id 
                UNION 
                SELECT jv_coa_id AS item_cat_id, coa_name
                FROM bms_fin_journal_entry a
                LEFT JOIN bms_fin_journal_entry_item b ON b.jv_id = a.jv_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.jv_coa_id
                WHERE a.property_id = ".$property_id ." AND c.coa_code LIKE '9%' AND jv_date BETWEEN '".$from."' AND '".$to."' GROUP BY  jv_coa_id 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_cf_income_item ($property_id,$from,$to) {
        $sql = "SELECT item_cat_id, coa_name
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items b ON b.receipt_id = a.receipt_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ."  AND coa_code <> '5001/000' AND receipt_date BETWEEN '".$from."' AND '".$to."' GROUP BY  item_cat_id 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_cf_income_item_amt ($property_id,$date,$item_id) {
        $sql = " 
                SELECT SUM(b.item_amount) AS amount
                FROM bms_fin_receipt a
                LEFT JOIN bms_fin_receipt_items b ON b.receipt_id = a.receipt_id                
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.item_cat_id
                WHERE a.property_id = ".$property_id ." AND b.item_cat_id = '".$item_id."'
                AND receipt_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."' 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br /><br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_cf_expense_item_amt ($property_id,$date,$item_id) {
        $sql = "
                SELECT SUM(pay_amount) AS amount
                FROM bms_fin_payment a
                LEFT JOIN bms_fin_payment_items b ON b.pay_id = a.pay_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.pay_coa_id
               WHERE a.pay_property_id = ".$property_id ." AND b.pay_coa_id ='".$item_id."' AND pay_date BETWEEN '".$date."' AND '".date('Y-m-t',strtotime($date))."' 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br /><br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function get_cf_expense_item ($property_id,$from,$to) {
        $sql = "SELECT pay_coa_id AS item_cat_id, coa_name
                FROM bms_fin_payment a
                LEFT JOIN bms_fin_payment_items b ON b.pay_id = a.pay_id
                LEFT JOIN bms_fin_coa c ON c.coa_id = b.pay_coa_id
                WHERE a.pay_property_id = ".$property_id ." AND pay_date BETWEEN '".$from."' AND '".$to."' AND pay_coa_id IS NOT NULL GROUP BY  pay_coa_id 
                "; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function get_coa_for_tb ($property_id) {
        $sql = "SELECT coa_id,coa_code,coa_name,b.coa_type_name,a.coa_type_id,payment_source,payment_enabled,deposit_enabled,
                bill_enabled,receipt_enabled,opening_debit,opening_credit 
                FROM bms_fin_coa a
                LEFT JOIN bms_fin_coa_type b ON b.coa_type_id = a.coa_type_id
                WHERE property_id = ".$property_id ." AND coa_code NOT LIKE '3000%' AND coa_code NOT LIKE '41%'
                 UNION 
                SELECT coa_id,coa_code,coa_name,d.coa_type_name,d.coa_type_id,payment_source,payment_enabled,deposit_enabled,
                bill_enabled,receipt_enabled,opening_debit,opening_credit 
                FROM bms_fin_coa c
                LEFT JOIN bms_fin_coa_type d ON d.coa_type_id = c.coa_type_id 
                WHERE property_id = ".$property_id ." AND coa_code IN('3000/000','4100/000')   
                ORDER BY coa_code ASC"; //(coa_code LIKE '1%' OR coa_code LIKE '2%' OR coa_code LIKE '5%'OR coa_code LIKE '9%')
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getTrailBalance ($property_id,$coa_id,$from,$to) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE c.property_id = ".$property_id." AND d.item_cat_id=".$coa_id ." AND receipt_date < '$from'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE e.property_id = ".$property_id." AND  f.item_cat_id=".$coa_id ." AND debit_note_date < '$from'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$coa_id ." AND jv_date < '$from' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    /** Pay & Receipt Enabled Start */
    function get_tb_pay_n_receipt_bf_debit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE e.property_id = ".$property_id." AND  f.item_cat_id=".$item_cat_id ." AND debit_note_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_pay_n_receipt_bf_credit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.pay_net_amount) AS amount
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                WHERE c.pay_property_id = ".$property_id." AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0
                ) bf_credit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_bill_receipt_ena_bf_debit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(f.adj_amount) AS amount
                FROM bms_fin_credit_note e
                JOIN bms_fin_credit_note_items f ON  f.credit_note_id=e.credit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND credit_note_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_bill_receipt_ena_bf_credit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(item_amount) AS amount
                FROM bms_fin_bills a
                JOIN bms_fin_bill_items b ON  b.bill_id=a.bill_id
                WHERE  a.property_id = ".$property_id." AND b.item_cat_id=".$item_cat_id ." AND bill_date  <= '".$as_of_date."' 
                UNION
                SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE  c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND debit_note_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0) bf_debit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_receipt_ena_bf_debit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_receipt_ena_bf_credit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE  c.property_id = ".$property_id." AND d.item_cat_id=".$item_cat_id ." AND bill_item_id=0 AND receipt_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE  e.property_id = ".$property_id." AND f.item_cat_id=".$item_cat_id ." AND debit_note_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0
                ) bf_credit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    /** Payment Source Enabled Start */
    function get_tb_pay_sour_ena_bf_debit ($property_id,$bank_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND receipt_date  <= '".$as_of_date."'
                UNION
                SELECT SUM(c.amount) AS amount
                FROM bms_fin_deposit_receive c                
                WHERE c.property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND deposit_date  <= '".$as_of_date."'
                UNION
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0
                ) bf_debit ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_pay_sour_ena_bf_credit ($property_id,$bank_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.pay_total) AS amount
                FROM bms_fin_payment c                
                WHERE c.pay_property_id = ".$property_id." AND c.bank_id=".$bank_id ." AND c.pay_date  <= '".$as_of_date."'
                UNION                 
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_debit_note e                
                WHERE e.property_id = ".$property_id." AND  e.bank_id=".$bank_id ." AND debit_note_date  <= '".$as_of_date."'
                UNION
                SELECT SUM(e.amount) AS amount
                FROM bms_fin_deposit_refund e                
                WHERE e.property_id = ".$property_id." AND  e.bank_id=".$bank_id ." AND depo_refund_date  <= '".$as_of_date."'
                UNION
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$bank_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0
                ) bf_credit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    /** Payment Enabled Start */ 
    
    function get_tb_pay_ena_bf_debit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.net_amount) AS amount
                FROM bms_fin_expense_invoice c
                JOIN bms_fin_exp_inv_items d ON  d.exp_id=c.exp_inv_id
                WHERE c.property_id = ".$property_id." AND d.coa_id=".$item_cat_id ." AND exp_date <= '".$as_of_date."'
                UNION
                SELECT SUM(d.pay_net_amount) AS amount
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                WHERE c.pay_property_id = ".$property_id." AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date  <= '".$as_of_date."'
                UNION               
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_pay_ena_bf_credit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    /** Debtors Start */
    function get_tb_debtors_bf_debit ($property_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(total_amount) AS amount
                FROM bms_fin_bills a                
                WHERE a.property_id=".$property_id ." AND bill_date <= '".$as_of_date."'
                UNION
                SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE c.property_id=".$property_id ." AND bill_item_id=0 AND receipt_date <= '".$as_of_date."'
                UNION                 
                SELECT SUM(f.paid_amount) AS amount
                FROM bms_fin_debit_note e
                JOIN bms_fin_debit_note_items f ON  f.debit_note_id=e.debit_note_id
                WHERE e.property_id=".$property_id ." AND debit_note_date <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date <= '".$as_of_date."' AND q.debit > 0
                AND (q.jv_coa_id = '11' OR q.jv_coa_id IN (SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."))
                ) bf_debit                
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }   
    
    function get_tb_debtors_bf_credit ($property_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.property_id=".$property_id ." AND receipt_date <= '".$as_of_date."'
                UNION
                SELECT SUM(f.adj_amount) AS amount
                FROM bms_fin_credit_note e
                JOIN bms_fin_credit_note_items f ON  f.credit_note_id=e.credit_note_id
                WHERE e.property_id=".$property_id ." AND credit_note_date <= '".$as_of_date."'
                UNION                 
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND jv_date <= '".$as_of_date."' AND q.credit > 0
                AND  (q.jv_coa_id = '11' OR q.jv_coa_id IN (SELECT coa_id from bms_property_units WHERE property_id =".$property_id ."))
                ) bf_credit
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    } 
    /** Debtors end */
    
    /** creditors Start */ 
    function get_tb_creditors_bf_debit ($property_id,$as_of_date) {
        $sql = "SELECT SUM(c.pay_total) AS amount
                FROM bms_fin_payment c                
                WHERE c.pay_property_id = ".$property_id." AND pay_date <= '".$as_of_date."'
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }   
    
    function get_tb_creditors_bf_credit ($property_id,$as_of_date) {
        $sql = "SELECT SUM(total) AS amount
                FROM bms_fin_expense_invoice a
                WHERE a.property_id=".$property_id ." AND exp_date <= '".$as_of_date."'
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();        
    }
    
    /** Creditors End */ 
    
     /** Non Enabled start */
    function get_tb_non_enabled_bf_debit ($property_id,$as_of_date,$item_cat_id) {
        $sql = "SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date <= '$as_of_date' AND q.debit > 0
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_tb_non_enabled_bf_credit ($property_id,$as_of_date,$item_cat_id) {
        $sql = "SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date <= '$as_of_date' AND q.credit  > 0
                ";
                
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    
    /** Non Enabled End */
    
    function get_fixed_assets ($property_id) {
        $sql = "SELECT coa_id,coa_code,coa_name,opening_debit,opening_credit
                FROM bms_fin_coa                 
                WHERE property_id = ".$property_id." AND  coa_type_id=2 
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_current_assets ($property_id) {
        $sql = "SELECT coa_id,coa_code,coa_name,opening_debit,opening_credit
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." AND coa_code IN('3000/000')
                UNION 
                SELECT coa_id,coa_code,coa_name,opening_debit,opening_credit
                FROM bms_fin_coa                 
                WHERE property_id = ".$property_id." AND  coa_type_id=3 AND coa_code NOT LIKE '%3000/%'
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function get_current_liabilities ($property_id) {
        $sql = "SELECT coa_id,coa_code,coa_name,opening_debit,opening_credit
                FROM bms_fin_coa 
                WHERE property_id = ".$property_id ." AND coa_code IN('4100/000')
                UNION 
                SELECT coa_id,coa_code,coa_name,opening_debit,opening_credit
                FROM bms_fin_coa                 
                WHERE property_id = ".$property_id." AND  coa_type_id=4 AND coa_code NOT LIKE '%4100/%' 
                ";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    /** Payment Enabled Start */ 
    
    function get_bs_pay_ena_bf_debit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.net_amount) AS amount
                FROM bms_fin_expense_invoice c
                JOIN bms_fin_exp_inv_items d ON  d.exp_id=c.exp_inv_id
                WHERE c.property_id = ".$property_id." AND d.coa_id=".$item_cat_id ." AND exp_date <= '".$as_of_date."'
                UNION
                SELECT SUM(d.pay_net_amount) AS amount
                FROM bms_fin_payment c
                JOIN bms_fin_payment_items d ON  d.pay_id=c.pay_id
                WHERE c.pay_property_id = ".$property_id." AND d.pay_coa_id=".$item_cat_id ." AND c.pay_date  <= '".$as_of_date."'
                UNION               
                SELECT SUM(q.debit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.debit > 0
                ) bf_debit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_bs_pay_ena_bf_credit ($property_id,$item_cat_id,$as_of_date) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(d.paid_amount) AS amount
                FROM bms_fin_receipt c
                JOIN bms_fin_receipt_items d ON  d.receipt_id=c.receipt_id
                WHERE c.property_id=".$property_id ." AND receipt_date <= '".$as_of_date."'
                UNION
                SELECT SUM(q.credit) AS amount
                FROM bms_fin_journal_entry p
                JOIN bms_fin_journal_entry_item q ON  q.jv_id=p.jv_id
                WHERE p.property_id = ".$property_id." AND  q.jv_coa_id=".$item_cat_id ." AND jv_date  <= '".$as_of_date."' AND q.credit > 0
                ) bf_credit";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
}