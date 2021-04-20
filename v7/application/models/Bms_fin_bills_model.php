<?php defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_bills_model extends CI_Model {
    function __construct () { parent::__construct(); }
    

    
    function getLastBillNo ($bill_no_format) {
        $sql = "SELECT bill_no FROM bms_fin_bills WHERE bill_no LIKE '".$bill_no_format."%' ORDER BY bill_id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function insertBill ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $this->db->insert('bms_fin_bills', $data);
        //echo "<br /><pre>".$this->db->last_query()."</pre>";
        return $this->db->insert_id();
    }
    
    function updateBill ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_bills', $data, array('bill_id' => $id));   
    }
    
    function insertBillItem ($data) {
        $this->db->insert('bms_fin_bill_items', $data);
        //echo "<br /><br /><pre>".$this->db->last_query()."</pre>";        
    }
    
    function updateBillItem ($data,$id) {        
        $this->db->update('bms_fin_bill_items', $data,  array('bill_item_id' => $id));        
    }
    
    function getBillsList ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='',$from= '',$to='', $bill_no= '') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;
        
        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($unit_id != '') {            
            $cond .= " AND a.unit_id = ".$unit_id;
        }

        if ($bill_no != '') {
            $cond .= " AND a.bill_no = '".$bill_no . "' ";
        }

        if($from != '' && $to != '') {            
            $cond .= " AND a.bill_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {            
            $cond .= " AND a.bill_date >= '$from'";
        } else if($from == '' && $to != '') {            
            $cond .= " AND a.bill_date <= '$to'";
        }

        $sql_cnt = "SELECT COUNT(a.bill_id) AS num_rows, SUM(a.total_amount) AS grant_tot, SUM(d.total_amount) AS cn_amt
                FROM bms_fin_bills a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_fin_credit_note d ON d.invoice_id = a.bill_id                
                WHERE 1=1 ". $cond;
        
        $sql = "SELECT a.bill_id,a.bill_no,a.bill_date,a.bill_due_date,a.total_amount,a.bill_paid_status,
                b.property_name,c.unit_no,c.owner_name,d.total_amount AS cn_amt
                FROM bms_fin_bills a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_fin_credit_note d ON d.invoice_id = a.bill_id                 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id


        $query = $this->db->query($sql_cnt);
        $num_rows = $query->row_array();          
        
        $order_by = " ORDER BY bill_date DESC, bill_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
    
    }

    function getBillItemList ($offset = '0', $per_page = '25', $property_id ='', $coa_id ='', $unit_id ='',$from= '',$to='', $bill_no= '') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($unit_id != '') {
            $cond .= " AND b.unit_id = ".$unit_id;
        }

        if ($bill_no != '') {
            $cond .= " AND b.bill_no = '".$bill_no . "' ";
        }

        if ($coa_id != '') {
            $cond .= " AND a.item_cat_id = " . $coa_id;
        }

        if($from != '' && $to != '') {
            $cond .= " AND b.bill_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {
            $cond .= " AND b.bill_date >= '$from'";
        } else if($from == '' && $to != '') {
            $cond .= " AND b.bill_date <= '$to'";
        }

        $sql_cnt = "SELECT COUNT(a.bill_item_id) AS num_rows, SUM(a.item_amount) AS grant_tot, SUM(e.total_amount) AS cn_amt
                FROM bms_fin_bill_items a  
                LEFT JOIN bms_fin_bills b ON b.bill_id = a.bill_id
                LEFT JOIN bms_property c ON c.property_id = b.property_id
                LEFT JOIN bms_property_units d ON d.unit_id = b.unit_id 
                LEFT JOIN bms_fin_credit_note e ON e.invoice_id = b.bill_id                
                WHERE 1=1 ". $cond;

        $sql = "SELECT a.bill_id,b.bill_no,b.bill_date,b.bill_due_date,a.item_amount, a.paid_status,
                c.property_name,d.unit_no,d.owner_name,e.total_amount AS cn_amt
                FROM bms_fin_bill_items a  
                LEFT JOIN bms_fin_bills b ON b.bill_id = a.bill_id
                LEFT JOIN bms_property c ON c.property_id = b.property_id
                LEFT JOIN bms_property_units d ON d.unit_id = b.unit_id 
                LEFT JOIN bms_fin_credit_note e ON e.invoice_id = b.bill_id
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id

        $query = $this->db->query($sql_cnt);
        $num_rows = $query->row_array();

        $order_by = " ORDER BY bill_date DESC, bill_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);
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
        $sql = "SELECT bill_item_id,bill_id,item_cat_id,item_period,item_descrip,item_amount
                FROM bms_fin_bill_items a                        
                WHERE bill_id =".$bill_id;
        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function getBillDetails ($bill_id) {
        $sql = "SELECT bill_id,a.property_id,a.block_id,a.unit_id, b.bill_due_days, b.bank_name, b.account_no, account_title, a.remarks,
                b.property_name, b.jmb_mc_name, b.address_1, b.address_2, b.pin_code,b.city, b.email_addr, b.water_min_charg, b.water_charge_range, b.water_charge_range, b.water_charge_per_unit_rate_2, b.water_charge_range, b.water_charge_per_unit_rate_1, b.phone_no,
                f.country_name,  
                c.unit_no, c.owner_name, CONCAT(c.address_1, '<br>',c.address_2) as owner_address, c.city as owner_city, c.postcode as owner_postcode, c.state as owner_state, c.country as owner_country, bill_no,bill_date,bill_due_date,remarks,total_amount,a.created_date,d.first_name,d.last_name, c.share_unit 
                FROM bms_fin_bills a  
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                LEFT JOIN bms_state e ON e.state_id=b.state_id
                LEFT JOIN bms_countries f ON f.country_id=b.country_id
                WHERE bill_id =".$bill_id;

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    function getBillItemsDetail ($bill_id) {
        $sql = "SELECT a.bill_item_id,a.bill_id,a.item_cat_id,a.item_period,a.item_descrip,a.item_amount,
                b.coa_name as cat_name, DATE_FORMAT(c.bill_date,'%d-%m-%Y') as bill_date, DATE_FORMAT(c.bill_due_date,'%d-%m-%Y') as bill_due_date,  
                d.meter_reading_id, d.previous_reading, d.reading, 
                e.water_charge_range, e.water_charge_per_unit_rate_2, e.water_charge_per_unit_rate_1, e.water_min_charg, e.jmb_mc_name, e.property_name,e.email_addr
                FROM bms_fin_bill_items a 
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.item_cat_id
                LEFT JOIN bms_fin_bills c ON c.bill_id = a.bill_id
                LEFT JOIN bms_fin_meter_reading d ON a.meter_reading_id = d.meter_reading_id
                LEFT JOIN bms_property e ON b.property_id = e.property_id
                WHERE a.bill_id =".$bill_id;

        $query = $this->db->query($sql);        
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }
    
    function deleteBillItem ($bill_item_id) {
        return $this->db->delete('bms_fin_bill_items',array('bill_item_id'=>$bill_item_id));
    }
    
    function deleteBillItemByBillId ($bill_id) {
        return $this->db->delete('bms_fin_bill_items',array('bill_id'=>$bill_id));
    }
    
    function deleteBill ($bill_id) {
        return $this->db->delete('bms_fin_bills',array('bill_id'=>$bill_id));
    }
    
    function get_bf_debit ($unit_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(total_amount) AS amount
                FROM bms_fin_bills a                
                WHERE a.unit_id=".$unit_id ." AND bill_date < '$from' 
                UNION
                SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c
                WHERE c.unit_id=".$unit_id ." AND direct_receipt=1 AND receipt_date < '$from'
                UNION                 
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_debit_note e                
                WHERE e.unit_id=".$unit_id ." AND debit_note_date < '$from') bf_debit                
                ";

        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_bf_credit ($unit_id,$from) {
        $sql = "SELECT SUM(amount) AS amount FROM 
                (SELECT SUM(c.paid_amount) AS amount
                FROM bms_fin_receipt c                
                WHERE c.unit_id=".$unit_id ." AND receipt_date < '$from' 
                UNION
                SELECT SUM(e.total_amount) AS amount
                FROM bms_fin_credit_note e
                WHERE e.unit_id=".$unit_id ." AND credit_note_date < '$from') bf_credit
                ";

        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->row_array();
    }
    
    function get_soa ($unit_id,$from,$to) {
        $sql = "SELECT a.bill_id as id, a.unit_id,b.unit_no,b.owner_name,bill_date AS doc_date,bill_no AS doc_no,a.remarks AS descrip,
                a.total_amount AS amount, 'RINV' as item_type,  bill_time AS doc_time
                FROM bms_fin_bills a
                LEFT JOIN bms_property_units b on a.unit_id = b.unit_id
                WHERE a.unit_id=".$unit_id ." AND bill_date BETWEEN '$from' AND '$to'
                UNION
                SELECT c.receipt_id as id, c.unit_id,d.unit_no,d.owner_name,receipt_date AS doc_date,receipt_no AS doc_no,c.remarks AS descrip,
                c.paid_amount AS amount,'OR' as item_type, receipt_time AS doc_time
                FROM bms_fin_receipt c
                LEFT JOIN bms_property_units d on c.unit_id = d.unit_id
                WHERE c.unit_id=".$unit_id ." AND receipt_date BETWEEN '$from' AND '$to'
                UNION
                SELECT e.credit_note_id as id, e.unit_id,f.unit_no,f.owner_name,credit_note_date AS doc_date,credit_note_no AS doc_no,e.remarks AS descrip,
                e.total_amount AS amount,'CN' as item_type,  credit_note_time AS doc_time
                FROM bms_fin_credit_note e
                LEFT JOIN bms_property_units f on e.unit_id = f.unit_id
                WHERE e.unit_id=".$unit_id ." AND credit_note_date BETWEEN '$from' AND '$to'
                UNION
                SELECT g.debit_note_id as id, g.unit_id,h.unit_no,h.owner_name,debit_note_date AS doc_date,debit_note_no AS doc_no,g.remarks AS descrip,
                g.total_amount AS amount,'DN' as item_type, debit_note_time AS doc_time
                FROM bms_fin_debit_note g
                LEFT JOIN bms_property_units h on g.unit_id = h.unit_id
                WHERE g.unit_id=".$unit_id ." AND debit_note_date BETWEEN '$from' AND '$to'                               
                ORDER BY doc_date ASC, doc_time ASC";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }
    
    function getUnitsForQuitRent ($property_id) {
        $sql = "SELECT unit_id,block_id,share_unit FROM bms_property_units 
                WHERE property_id=".$property_id." AND share_unit<>'' 
                ORDER BY unit_no DESC";
        $query = $this->db->query($sql);        
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getBillsListExcel ($property_id ='', $unit_id ='',$from= '',$to='', $bill_no='') {

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($unit_id != '') {
            $cond .= " AND a.unit_id = ".$unit_id;
        }

        if ($bill_no != '') {
            $cond .= " AND a.bill_no = '".$bill_no . "' ";
        }

        if($from != '' && $to != '') {
            $cond .= " AND a.bill_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {
            $cond .= " AND a.bill_date >= '$from'";
        } else if($from == '' && $to != '') {
            $cond .= " AND a.bill_date <= '$to'";
        }

        $sql = "SELECT a.bill_id,a.bill_no,DATE_FORMAT(a.bill_date, '%d-%m-%Y'),c.unit_no,c.owner_name,a.total_amount, CONCAT (d.first_name,' ',d.last_name)
                FROM bms_fin_bills a
                LEFT JOIN bms_property_units c ON c.unit_id = a.unit_id 
                LEFT JOIN bms_staff d ON d.staff_id=a.created_by 
                WHERE 1=1 ". $cond ;

        $order_by = " ORDER BY bill_date DESC, bill_id DESC";
        $query = $this->db->query($sql.$order_by);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function getBillItemListExcel ($property_id ='', $coa_id ='', $unit_id ='',$from= '',$to='', $bill_no= '') {

        $cond = '';
        $cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if($unit_id != '') {
            $cond .= " AND b.unit_id = ".$unit_id;
        }

        if ($bill_no != '') {
            $cond .= " AND b.bill_no = '".$bill_no . "' ";
        }

        if ($coa_id != '') {
            $cond .= " AND a.item_cat_id = " . $coa_id;
        }

        if($from != '' && $to != '') {
            $cond .= " AND b.bill_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {
            $cond .= " AND b.bill_date >= '$from'";
        } else if($from == '' && $to != '') {
            $cond .= " AND b.bill_date <= '$to'";
        }

        $sql = "SELECT b.bill_no,DATE_FORMAT(b.bill_date, '%d-%m-%Y'),d.unit_no,d.owner_name,a.item_amount, CONCAT (c.first_name,' ',c.last_name)
                FROM bms_fin_bill_items a  
                LEFT JOIN bms_fin_bills b ON b.bill_id = a.bill_id
                LEFT JOIN bms_staff c ON c.staff_id=b.created_by
                LEFT JOIN bms_property_units d ON d.unit_id = b.unit_id 
                WHERE 1=1 ". $cond ;//LEFT JOIN bms_property b ON b.property_id = a.property_id

        $order_by = " ORDER BY bill_date DESC, b.bill_id DESC";
        $query = $this->db->query($sql.$order_by);
        return $query->result_array();
    }




    function getBillItemDetail ( $bill_id ) {
        $sql = "SELECT item_cat_id, sum(a.item_amount) as item_amount
        FROM bms_fin_bill_items a
        LEFT JOIN bms_fin_bills c ON c.bill_id = a.bill_id 
        WHERE a.bill_id = $bill_id group by item_cat_id";

        $query = $this->db->query($sql);
        //echo "<pre>".$this->db->last_query()."</pre>";
        return $query->result_array();
    }

    function getBillsListOutstanding ($property_id ='', $unit_id ='',$from= '',$to='', $bill_no= '') {

        $cond = '';
        $cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        if($unit_id != '') {
            $cond .= " AND b.unit_id = ".$unit_id;
        }

        if ($bill_no != '') {
            $cond .= " AND b.bill_no = '".$bill_no . "' ";
        }

        if($from != '' && $to != '') {
            $cond .= " AND b.bill_date BETWEEN '$from' AND '$to'";
        } else if($from != '' && $to == '') {
            $cond .= " AND b.bill_date >= '$from'";
        } else if($from == '' && $to != '') {
            $cond .= " AND b.bill_date <= '$to'";
        }

        $sql = "SELECT a.bill_no,DATE_FORMAT(a.bill_date, '%d-%m-%Y'),d.unit_no,d.owner_name,b.total_amount, CONCAT (c.first_name,' ',c.last_name)
                FROM bms_fin_bills a  
                LEFT JOIN bms_staff c ON c.staff_id=b.created_by
                LEFT JOIN bms_property_units d ON d.unit_id = b.unit_id 
                WHERE 1=1 AND a.bill_paid_status = '0'". $cond ;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_property_lpi_detail ( $property_id ) {
        $sql = "SELECT a.late_payment,a.late_pay_percent, a.late_pay_grace_value, a.late_pay_grace_type, 
                a.late_pay_effect_from, a.bill_due_days, property_abbrev 
                FROM bms_property a  
                LEFT JOIN bms_staff c ON c.staff_id=a.created_by
                WHERE 1=1 AND a.property_id = '$property_id'";

        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function getLpiCoaId ($properrty_id) {
        $sql = "Select coa_id from bms_fin_coa where property_id = '$properrty_id' and lpi = 1";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_unit_outstanding_bills ($unit_id, $properrty_id, $date_to_start, $lpi_coa_id, $late_pay_effect_from = '') {
        $cond = '';
        if ( !empty($late_pay_effect_from) ) {
            if ( $date_to_start > $late_pay_effect_from ) {
                $cond = " AND (a.bill_due_date  <= '" . $late_pay_effect_from . "' OR a.bill_due_date between '" . $late_pay_effect_from . "' and '" . $date_to_start . "')" ;
            } elseif ( $date_to_start == $late_pay_effect_from ) {
                $cond = " AND a.bill_due_date < '" . $late_pay_effect_from . "'" ;
            }
        }

        $sql = "SELECT b.bill_item_id, a.unit_id, a.bill_no, a.bill_date, a.bill_due_date, b.item_amount, a.block_id, b.paid_amount, b.bal_amount, c.coa_name, b.lpi_charged_date  
                FROM bms_fin_bills a
                LEFT JOIN bms_fin_bill_items b ON a.bill_id = b.bill_id
                LEFT JOIN bms_fin_coa c ON b.item_cat_id = c.coa_id
                WHERE 1=1 AND a.bill_paid_status = '0' AND a.unit_id = '$unit_id'
                AND b.item_cat_id <> $lpi_coa_id " . $cond;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_unit_outstanding_bills_amount ($unit_id, $date_to_start,$lpi_coa_id, $lpi_amount, $late_pay_effect_from = '') {
        $cond = '';
        if ( !empty($late_pay_effect_from) ) {
            if ( $date_to_start > $late_pay_effect_from ) {
                $cond = " AND (a.bill_due_date  <= '" . $late_pay_effect_from . "' OR a.bill_due_date between '" . $late_pay_effect_from . "' and '" . $date_to_start . "')" ;
            } elseif ( $date_to_start == $late_pay_effect_from ) {
                $cond = " AND a.bill_due_date < '" . $late_pay_effect_from . "'" ;
            }
        }

        $sql = "SELECT b.bill_item_id, a.unit_id, a.bill_no, a.bill_date, a.bill_due_date, b.item_amount, a.block_id, b.paid_amount, b.bal_amount, c.coa_name, b.lpi_charged_date  
                FROM bms_fin_bills a
                LEFT JOIN bms_fin_bill_items b ON a.bill_id = b.bill_id
                LEFT JOIN bms_fin_coa c ON b.item_cat_id = c.coa_id
                WHERE 1=1 AND a.bill_paid_status = '0' AND a.unit_id = '$unit_id'
                AND b.item_cat_id <> $lpi_coa_id
                AND a.total_amount >= '$lpi_amount'" . $cond ;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_all_outstanding_bills_of_a_unit ( $unit_id, $date_to_start ) {
        $sql = "SELECT b.bill_item_id, a.unit_id, b.item_descrip, 
                a.bill_no, DATE_FORMAT(a.bill_date,'%d-%m-%Y') as bill_date , b.item_amount, a.block_id, b.paid_amount, b.bal_amount, c.coa_name, DATE_FORMAT(a.bill_due_date,'%d-%m-%Y') as bill_due_date  
                FROM bms_fin_bill_items b 
                LEFT JOIN bms_fin_bills a ON a.bill_id = b.bill_id
                LEFT JOIN bms_property_units x ON x.unit_id = a.unit_id
                LEFT JOIN bms_fin_coa c ON b.item_cat_id = c.coa_id
                WHERE 1=1 AND b.paid_status = '0' AND a.unit_id = '$unit_id'
                AND a.bill_date < '$date_to_start' ORDER BY x.unit_no, a.bill_date, a.bill_time" ;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function chk_property_bill_generated_before ($property_id ='', $bill_generated) {

        $cond = '';

        $cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        $sql = "SELECT COUNT(a.meter_reading_id) as total_records  
                FROM bms_fin_meter_reading a
                LEFT JOIN bms_property b on a.property_id = b.property_id
                WHERE 1=1 AND bill_generated = $bill_generated". $cond ;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_past_bill_dates ($property_id ='', $bill_generated) {

        $cond = '';

        $cond .= " AND b.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        $sql = "SELECT reading_mon_year, meter_reading_id   
                FROM bms_fin_meter_reading a
                LEFT JOIN bms_property b on a.property_id = b.property_id
                WHERE 1=1 AND bill_generated = $bill_generated  $cond 
                GROUP BY reading_mon_year
                ORDER BY str_to_date(reading_mon_year, '%M-%y')";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function check_meter_reading_record_exists ($offset = '0', $per_page = '25', $property_id ='', $unit_id ='') {
        $limit = '';
        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';

        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($unit_id != '') {
            $cond .= " AND a.unit_id = ".$unit_id;
        }

        $sql = "SELECT a.reading_mon_year, a.reading, a.previous_reading, a.amount, a.bill_generated  
                FROM bms_fin_meter_reading a
                LEFT JOIN bms_property b on a.properrty_id = b.properrty_id
                LEFT JOIN bms_property_units c on a.unit_id = c.unit_id
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getUnits ($reading_mon_year, $property_id, $unit_id = '',$offset,$rows) {

        if ($reading_mon_year != '') {
            $sql = "Select COUNT(meter_reading_id) as total_records from bms_fin_meter_reading 
            where reading_mon_year = '$reading_mon_year' and property_id = '$property_id'";
            $query = $this->db->query($sql);
            $row = $query->row();

            if ( $row->total_records == 0 ) {
                $sql = "SELECT DATE_FORMAT(max(str_to_date(reading_mon_year, '%M-%y')),'%b-%y') as reading_mon_year 
                FROM bms_fin_meter_reading
                WHERE property_id = '$property_id'";

                $query = $this->db->query($sql);
                $row_reading_mon_year = $query->row();
                $row_reading_mon_year->reading_mon_year;
                $staff_id = $_SESSION['bms']['staff_id'];



                if ( $row_reading_mon_year->reading_mon_year != '' ) {
                    $sql = "INSERT INTO bms_fin_meter_reading (property_id, block_id, unit_id, reading_mon_year, previous_reading, bill_generated, created_by, created_date)
                    SELECT a.property_id, a.block_id, a.unit_id, '$reading_mon_year', b.reading, 0, '$staff_id', '" . date('Y-m-d') . "'
                    FROM bms_property_units a
                    LEFT JOIN bms_fin_meter_reading b ON a.unit_id = b.unit_id
                    WHERE b.reading_mon_year = '" . $row_reading_mon_year->reading_mon_year . "' AND  b.property_id = '".$property_id."'";

                } else {
                    $sql = "INSERT INTO bms_fin_meter_reading (property_id, block_id, unit_id, reading_mon_year, bill_generated, created_by, created_date)
                    SELECT a.property_id, a.block_id, a.unit_id, '$reading_mon_year', 0, '$staff_id', '" . date('Y-m-d') . "' 
                    FROM bms_property_units a
                    LEFT JOIN bms_fin_meter_reading b ON a.unit_id = b.unit_id
                    WHERE a.property_id = '" .$property_id . "'";
                }


                $this->db->query($sql);
            }
        }

        $limit = ' LIMIT '.$offset.','.$rows;

        $condi = '';
        if ($unit_id != '')
            $condi = " AND a.unit_id = '".$unit_id."'";

        $sql = "SELECT a.unit_id, a.unit_no, a.unit_status, a.floor_no, a.owner_name, a.email_addr, a.contact_1, a.is_defaulter, b.reading, b.previous_reading, b.amount, b.bill_generated, b.meter_reading_id, b.exclude_to_inv  
        FROM bms_property_units a
        LEFT JOIN bms_fin_meter_reading b ON a.unit_id = b.unit_id 
        WHERE b.reading_mon_year = '$reading_mon_year' AND  b.property_id = '".$property_id."' $condi";
        $order_by = " ORDER BY unit_no";

        $query = $this->db->query($sql.$order_by);
        $data['num_rows'] = $query->num_rows();

        $query = $this->db->query($sql . $order_by . $limit);
        $data['units'] = $query->result_array();
        return $data;
    }

    function getPropertyDetails ( $property_id ) {

        $cond = '';

        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";

        $sql = "SELECT a.water_min_charg, a.water_charge_per_unit_rate_1, a.water_charge_range, a.water_charge_per_unit_rate_2  
                FROM bms_property a 
                WHERE 1=1 $cond";

        $query = $this->db->query($sql);
        return $query->row();
    }

    function insert_meter_reading ($data) {
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date('Y-m-d');
        $this->db->insert('bms_fin_meter_reading', $data);
        return $this->db->insert_id();
    }

    function update_meter_reading ($data, $id) {
        $this->db->update('bms_fin_meter_reading', $data, array('meter_reading_id' => $id));
    }

    function check_invoice_already_generated ( $property_id, $reading_mon_year ) {
        $sql = "SELECT COUNT(meter_reading_id) as total_units 
                FROM bms_fin_meter_reading
                WHERE property_id = $property_id 
                AND reading_mon_year = '$reading_mon_year'
                AND bill_generated = 0
                AND exclude_to_inv = 0;";

        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->total_units;
    }

    function check_units_not_keyed_in ( $property_id, $reading_mon_year) {
        $sql = "SELECT COUNT(meter_reading_id) as total_units 
                FROM bms_fin_meter_reading
                WHERE ( reading < 0.01 OR reading IS NULL ) AND property_id = $property_id  AND bill_generated = 0  
                AND reading_mon_year = '$reading_mon_year'
                AND exclude_to_inv = 0;";

        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->total_units;
    }

    function getServiceChargeId ( $property_id, $item ) {
        $sql = "SELECT coa_id, coa_name
                FROM  bms_fin_coa
                WHERE property_id = '$property_id' AND $item = 1";
        $query = $this->db->query( $sql );
        return $query->row();
    }

    function get_next_meter_reading_date ($property_id, $bill_generated) {
        $sql = "SELECT DATE_FORMAT(max(str_to_date(reading_mon_year, '%M-%y')), '1-%M-%y') as next_date FROM bms_fin_meter_reading
        where bill_generated = $bill_generated AND property_id = $property_id";
        $query = $this->db->query( $sql );
        return $query->row();
    }

    function getPropertyUnitsForMeterReadingBillGeneration ( $property_id, $reading_mon_year ) {
        $sql = "SELECT a.unit_id, a.unit_no, a.block_id, a.unit_no, a.owner_name, a.email_addr, c.country_name, d.state_name, b.reading, b.previous_reading, b.amount, b.meter_reading_id, b.reading_mon_year 
                FROM bms_property_units a
                LEFT JOIN bms_fin_meter_reading b ON a.unit_id = b.unit_id
                LEFT JOIN bms_countries c ON a.country = c.country_id
                LEFT JOIN bms_state d ON a.state = d.state_id
                WHERE 1=1 AND b.property_id = '$property_id'
                AND b.reading_mon_year like '$reading_mon_year'
                AND b.exclude_to_inv = 0;";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_units_from_bills ($bill_list) {
        $sql = "SELECT b.bill_id, a.unit_id, a.unit_no, a.block_id, a.unit_no, a.owner_name, a.email_addr, a.valid_email, 
        b.bill_date 
        FROM bms_fin_bills b 
        LEFT JOIN bms_property_units a ON b.unit_id = a.unit_id 
        WHERE b.`bill_id` IN (" . implode(', ',$bill_list) . ")
        ORDER BY b.`unit_id`;";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function property_setting_chk ( $property_id ) {
        $sql = "SELECT water, water_min_charg, water_charge_per_unit_rate_1 
        FROM bms_property   
        WHERE property_id = '$property_id' limit 1;";
        $query = $this->db->query( $sql );
        return $query->row_array();
    }

    function property_setting_lpi_chk ( $property_id ) {
        $sql = "SELECT late_payment, late_pay_percent, late_pay_grace_type, late_pay_grace_value 
        FROM bms_property   
        WHERE property_id = '$property_id' limit 1;";
        $query = $this->db->query( $sql );
        return $query->row_array();
    }

    function getUnitsForScAndSf ($property_id, $unit_id = '',$offset,$rows) {

        $limit = ' LIMIT '.$offset.','.$rows;

        $condi = '';
        if ($unit_id != '')
            $condi = " AND a.unit_id = '".$unit_id."'";

        $sql = "SELECT a.unit_id, a.unit_no, generate_sc_sf, owner_name
                FROM bms_property_units a 
                WHERE a.property_id = '".$property_id."' $condi";
        $order_by = " ORDER BY unit_no";

        $query = $this->db->query($sql.$order_by);
        $data['num_rows'] = $query->num_rows();

        $query = $this->db->query($sql . $order_by . $limit);
        $data['units'] = $query->result_array();
        return $data;
    }

    function update_unit ($data,$unit_id) {
        $this->db->update('bms_property_units', $data, array('unit_id' => $unit_id));
    }

    function chk_property_setting_sc_sf ( $property_id ) {
        $sql = "SELECT calcul_base, sinking_fund, property_abbrev, bill_due_days, email_addr, sc_charge, jmb_mc_name, property_name, address_1, address_2, pin_code   
        FROM bms_property   
        WHERE property_id = '$property_id' limit 1;";
        $query = $this->db->query( $sql );
        return $query->row_array();
    }

    function getPropertyUnitsForSCSF ( $property_id ) {
        $sql = "SELECT a.state, a.country, a.unit_id, a.unit_no, a.block_id, b.tier_id, b.tier_value, a.unit_no, a.owner_name, c.country_name, d.state_name,
              a.square_feet, a.share_unit, a.email_addr
                FROM  bms_property_units a
                LEFT JOIN bms_property_tier b ON a.tier_id = b.tier_id
                LEFT JOIN bms_countries c ON a.country = c.country_id
                LEFT JOIN bms_state d ON a.state = d.state_id        
                WHERE a.property_id = '$property_id'
                AND A.generate_sc_sf = 1";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function verifyPropertyUnits ( $property_id, $value ) {
        $sql = "SELECT unit_id FROM bms_property_units
                WHERE property_id = '$property_id'
                AND ( $value IS NULL OR $value = '' OR tier_id IS NULL OR tier_id = '' OR unit_no IS NULL OR unit_no = '' )";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_units_for_riyang ($unit_ids) {
        $sql = "SELECT share_unit, block_id, unit_id FROM bms_property_units
                WHERE property_id = '191'
                AND unit_id IN (" . implode(', ',$unit_ids) . ")";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function get_units_for_selesa ($unit_ids) {
        $sql = "SELECT share_unit, block_id, unit_id FROM bms_property_units
                WHERE property_id = '194'
                AND unit_id IN (" . implode(', ',$unit_ids) . ")";

        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    function chk_unapplied_amount_for_property ($property_id) {
        $sql = "SELECT SUM(total_records) AS total_records FROM
        (SELECT COUNT(receipt_id) AS total_records FROM `bms_fin_receipt`
        WHERE 1 = 1 
        AND property_id = '$property_id' AND open_credit > 0
        UNION
        SELECT COUNT(a.coa_id) AS total_records 
        FROM `bms_fin_coa` a
        LEFT JOIN bms_property_units b on b.coa_id = a.coa_id
        WHERE a.property_id = $property_id AND a.opening_credit > 0 AND a.coa_code LIKE '3000/%' AND a.open_credit_used = 0) tt";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_meter_reading_ids_for_invoices ( $reading_mon_year, $property_id, $email_status='' ) {
        $cond = '';
        if ( $email_status === 0 ) {
            $cond .= " AND email_status = 0 ";
        }
        $sql = "SELECT a.meter_reading_id,  
        d.owner_name, CONCAT(d.address_1, ' ', d.address_2) as owner_address, d.email_addr as owner_email_addr, d.unit_no, d.unit_id, d.valid_email,    
        c.bill_no, c.bill_date, c.bill_due_date, c.remarks,
        e.first_name, e.last_name, c.created_date, f.coa_name as cat_name,
        b.item_period, b.item_descrip, b.item_amount, c.bill_id, a.previous_reading, a.reading   
        FROM bms_fin_meter_reading a
        LEFT JOIN bms_fin_bill_items b ON a.meter_reading_id = b.meter_reading_id 
        LEFT JOIN bms_fin_bills c ON b.bill_id = c.bill_id 
        LEFT JOIN bms_property_units d ON c.unit_id = d.unit_id 
        LEFT JOIN bms_staff e ON e.staff_id = c.created_by 
        LEFT JOIN bms_fin_coa f ON b.item_cat_id = f.coa_id 
        WHERE 1 = 1
        AND a.property_id = '$property_id'
        AND a.exclude_to_inv = 0
        AND a.reading_mon_year = '$reading_mon_year'" . $cond;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getBillItemsDetailEmailInvoicing ($bill_id) {
        $sql = "SELECT a.bill_item_id,a.bill_id,a.item_cat_id,a.item_period,a.item_descrip,a.item_amount,
                b.coa_name as cat_name, 
                DATE_FORMAT(c.bill_date,'%d-%m-%Y') as bill_date, DATE_FORMAT(c.bill_due_date,'%d-%m-%Y') as bill_due_date, c.bill_no, 
                e.water_charge_range, e.water_charge_per_unit_rate_2, e.water_charge_per_unit_rate_1, e.water_min_charg, e.jmb_mc_name, e.property_name,e.email_addr
                FROM bms_fin_bill_items a 
                LEFT JOIN bms_fin_coa b ON b.coa_id = a.item_cat_id
                LEFT JOIN bms_fin_bills c ON c.bill_id = a.bill_id
                LEFT JOIN bms_property e ON b.property_id = e.property_id
                WHERE a.bill_id =".$bill_id;

        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->result_array();
    }

    function check_email_already_sent ( $property_id, $reading_mon_year ) {
        $sql = "SELECT count(a.meter_reading_id) as total_units 
                FROM bms_fin_meter_reading a
                LEFT JOIN bms_fin_bill_items b ON b.meter_reading_id = a.meter_reading_id 
                LEFT JOIN bms_fin_bills c ON c.bill_id = b.bill_id 
                WHERE a.property_id = $property_id 
                AND a.reading_mon_year = '$reading_mon_year'
                AND a.bill_generated = 1
                AND c.email_status = 0";

        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->total_units;
    }

    function get_meter_reading_details ($meter_reading_id) {
        $sql = "SELECT reading, previous_reading, amount 
                FROM bms_fin_meter_reading 
                WHERE meter_reading_id = '$meter_reading_id' ";

        $query = $this->db->query($sql);
        return $row = $query->row();
    }

    function chk_total_meter_readings ( $property_id ) {
        $sql = "SELECT COUNT(DISTINCT(reading_mon_year)) as total_records FROM `bms_fin_meter_reading`
        WHERE `property_id` = '$property_id'";

        $query = $this->db->query($sql);
        return $query->row()->total_records;
    }
}