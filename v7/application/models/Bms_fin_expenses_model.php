<?php
defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_expenses_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function getServiceProvider ($property_id) {
        $sql = "SELECT service_provider_id,property_id,provider_name,a.service_provider_cat_id,
                address,postcode,city,state,country,contractual,office_ph_no,
                person_incharge,person_inc_mobile,person_inc_email,job_scope, b.country_name
                FROM bms_service_provider a 
                LEFT JOIN bms_countries b ON b.country_id = a.country
                WHERE property_id=".$property_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPropertyAssets ($property_id) {
        $sql = "SELECT asset_id,asset_name,asset_location 
                FROM bms_property_assets a                
                WHERE property_id=".$property_id ." ORDER BY asset_name";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function getLastInvoiceNo ($po_no_format) {
        $sql = "SELECT exp_doc_no FROM bms_fin_expense_invoice WHERE exp_doc_no LIKE '".$po_no_format."%' ORDER BY exp_inv_id DESC LIMIT 1";
        //echo $sql;
        //exit;
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }

    function insertExpenseOrder($data){

        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $this->db->set('created_date', date("Y-m-d"), FALSE);
        $this->db->insert('bms_fin_expense_invoice', $data);
        $sql = $this->db->last_query();
        $insertId = $this->db->insert_id();
        if(isset($data['po_id'])){
            $exppoid = $data['po_id'];
            $query = "select COUNT(*) as total from bms_fin_exp_inv_items where exp_id=$insertId";
            $que = $this->db->query($query);
            $res =  $que->result_array();
            $totalrec = $res[0]['total'];
            if($totalrec==0){
                $insQuery = "INSERT INTO bms_fin_exp_inv_items(exp_id, description,qty,uom,unit_price,amount,discount_amt,net_amount,asset_id,tax_percent,tax_amt,coa_id,sub_cat_id,balance_amount, paid_status) select $insertId, description,qty,uom,unit_price,amount,discount_amt,net_amount,asset_id,tax_percent,tax_amt,coa_id,sub_cat_id, net_amount, 0 from bms_fin_po_items where po_id= $exppoid";
                $this->db->query($insQuery);
            }
        }
        return $insertId;
    }


    function insertExpenceOrderItem ($data) {

        $this->db->insert('bms_fin_exp_inv_items', $data);
        return $this->db->insert_id();
    }

    function updateExpenceOrder ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $this->db->set('updated_date', date("Y-m-d"), FALSE);
        $this->db->update('bms_fin_expense_invoice', $data, array('exp_inv_id' => $id));
        $sql = $this->db->last_query();
        // echo $sql;
    }

    function updateExpenceOrderItem ($data,$id) {
        $this->db->update('bms_fin_exp_inv_items', $data,  array('exp_item_id' => $id));
        $sql = $this->db->last_query();

    }

    function updateInvoiceAmount ($data,$id) {
        $data['updated_by'] = $_SESSION['bms']['staff_id'];
        $data['updated_date'] = date("Y-m-d");
        $this->db->update('bms_fin_purchase_orders', $data,  array('pur_order_id' => $id));
        $sql = $this->db->last_query();

    }

    function getexpenseList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
        $limit = '';

        if($offset != '' && $per_page != '')
            $limit = ' LIMIT '. $offset .', '.$per_page;

        $cond = '';
        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
        if($search_txt != '') {
            $search_txt = $this->db->escape_str($search_txt);
            $cond .= " AND (LOWER(owner_name) LIKE '%".$search_txt."%' OR LOWER(unit_no) LIKE '%".$search_txt."%' OR LOWER(bill_date) LIKE '%".$search_txt."%' OR LOWER(total_amount) LIKE '%".$search_txt."%')";
        }

        $sql = "SELECT a.*,c.service_provider_id,c.provider_name,c.person_incharge,d.po_no, d.invoice_paid_amount, d.invoice_pending_amount
                FROM bms_fin_expense_invoice a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_service_provider c ON c.service_provider_id = a.service_provider_id
                LEFT JOIN bms_fin_purchase_orders d ON d.pur_order_id = a.po_id
                WHERE 1=1 ". $cond ;
        $query = $this->db->query($sql);
        $num_rows = $query->num_rows();
        $order_by = " ORDER BY exp_inv_id DESC".$limit;
        $query = $this->db->query($sql.$order_by);

        $data = $query->result_array();
        return array('numFound'=>$num_rows,'records'=>$data);

    }

    function getPurchaseOrderDetails ($po_id) {
        $sql = "SELECT a.*,a.tax_percent as taxpercent,a.tax_amt as taxamt, c.coa_name, b.*
                FROM bms_fin_exp_inv_items a INNER JOIN bms_fin_expense_invoice b
                LEFT JOIN bms_fin_coa c ON c.coa_id = a.coa_id
                WHERE b.exp_inv_id=a.exp_id  and a.exp_id =".$po_id ;
        //echo $sql;
        // exit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPurchaseOrderitems ($pur_order_id) {
        /* $sql = "SELECT po_item_id,po_id,description,qty,uom,unit_price,amount,discount_amt,net_amount,asset_id,cat_id,sub_cat_id, d.*
                 FROM bms_fin_po_items a
                 LEFT JOIN bms_fin_purchase_orders d ON dz.pur_order_id = a.po_id
                 WHERE po_id =".$pur_order_id;
         */

        $sqlres = "SELECT po_id, exp_inv_id FROM bms_fin_expense_invoice WHERE exp_inv_id IN($pur_order_id)";

        $query = $this->db->query($sqlres);
        $queres = $query->result_array();

        if($queres[0]['po_id']>0){
            $sql = "SELECT a.po_item_id ,a.po_id ,a.description,a.qty,a.uom,a.unit_price,a.amount,a.discount_amt,a.tax_percent as taxpercent, a.tax_amt as taxamt,a.net_amount,a.asset_id,a.coa_id,a.sub_cat_id, CONCAT(c.coa_name, ' ', c.coa_code) as coa_name, b.*, d.exp_inv_id,d.exp_inv_no,d.exp_doc_no,d.inv_paid_status,d.invoice_create_status,d.paid_amount,d.balance_amount
                FROM bms_fin_po_items a INNER JOIN  bms_fin_purchase_orders b
                LEFT JOIN bms_fin_coa c ON c.coa_id = a.coa_id
				LEFT JOIN bms_fin_expense_invoice d ON d.exp_inv_id = ".$queres[0]['exp_inv_id']."
                WHERE a.po_id=b.pur_order_id  and b.pur_order_id =".$queres[0]['po_id'] ;
        }else {
            $sql = "SELECT a.exp_item_id ,a.exp_id,a.description,a.qty,a.uom,a.unit_price,a.amount,a.tax_percent as taxpercent,a.tax_amt as taxamt,a.discount_amt,a.net_amount,a.asset_id,a.coa_id,a.sub_cat_id, CONCAT(c.coa_name, ' ', c.coa_code) as coa_name, b.*
                FROM bms_fin_exp_inv_items a INNER JOIN  bms_fin_expense_invoice b
                LEFT JOIN bms_fin_coa c ON c.coa_id = a.coa_id
                WHERE b.exp_inv_id=a.exp_id  and exp_inv_id IN($pur_order_id)" ;
        }


        $query = $this->db->query($sql);
        return $query->result_array();

    }


    function getExpenseOrderItemDetails ($po_id) {
        $sql = "SELECT a.exp_item_id as po_item_id,a.exp_id as po_id,a.description,a.tax_percent,a.tax_amt,a.qty,a.uom,a.unit_price,a.amount,a.discount_amt,a.net_amount,a.asset_id,a.cat_id,a.sub_cat_id, c.charge_code_category_name, b.exp_inv_no as po_no, b.*
                FROM bms_fin_exp_inv_items a INNER JOIN  bms_fin_expense_invoice b
                LEFT JOIN bms_charge_code_category c ON c.charge_code_category_id = a.cat_id
                WHERE b.exp_inv_id=a.exp_id  and a.exp_id =".$po_id ;
        // echo $sql;
        // exit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPurchaseOrderBasedPOdetails ($po_id) {
        $sql = "SELECT po_item_id,po_id,description,qty,uom,unit_price,amount,discount_amt,a.tax_percent as taxpercent,a.tax_amt as taxamt,net_amount,asset_id,c.coa_id,sub_cat_id, CONCAT( c.coa_name, ' ' , c.coa_code ) as coa_name, b.*
                FROM bms_fin_po_items a INNER JOIN  bms_fin_purchase_orders b
                LEFT JOIN bms_fin_coa c ON c.coa_id = a.coa_id
                WHERE b.pur_order_id=a.po_id  and po_id =".$po_id ;




        /*$sqlres = "SELECT po_id, exp_inv_id FROM bms_fin_expense_invoice WHERE exp_inv_id IN($pur_order_id)";

        if($queres[0]['po_id']>0){
            $sql = "SELECT a.po_item_id ,a.po_id ,a.description,a.qty,a.uom,a.unit_price,a.amount,a.discount_amt,a.tax_percent as taxpercent, a.tax_amt as taxamt,a.net_amount,a.asset_id,a.cat_id,a.sub_cat_id, c.charge_code_category_name, b.*, d.exp_inv_id,d.exp_inv_no,d.exp_doc_no,d.payment_status,d.invoice_create_status,d.payment_paid_amount,d.payment_pending_amount
                FROM bms_fin_po_items a INNER JOIN  bms_fin_purchase_orders b
                LEFT JOIN bms_charge_code_category c ON c.charge_code_category_id = a.cat_id
				LEFT JOIN bms_fin_expense_invoice d ON d.exp_inv_id = ".$queres[0]['exp_inv_id']."
                WHERE a.po_id=b.pur_order_id  and b.pur_order_id =".$queres[0]['po_id'] ;
        }else {
            $sql = "SELECT a.exp_item_id ,a.exp_id,a.description,a.qty,a.uom,a.unit_price,a.amount,a.tax_percent as taxpercent,a.tax_amt as taxamt,a.discount_amt,a.net_amount,a.asset_id,a.cat_id,a.sub_cat_id, c.charge_code_category_name, b.*
                FROM bms_fin_exp_inv_items a INNER JOIN  bms_fin_expense_invoice b
                LEFT JOIN bms_charge_code_category c ON c.charge_code_category_id = a.cat_id
                WHERE b.exp_inv_id=a.exp_id  and exp_inv_id IN($pur_order_id)" ;
        }


        $query = $this->db->query($sql);
        return $query->result_array();*/




        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function getPurchaseOrder ($exp_inv_id) {
        $sql = "SELECT a.exp_inv_id, a.property_id,a.exp_inv_no,a.exp_doc_no,a.po_id,a.service_provider_id,a.remarks,a.exp_doc_no,a.exp_date,a.delivery_date,a.subtotal,a.total,a.inv_paid_status,a.invoice_create_status, b.address,b.postcode,b.city,b.state,b.country,b.office_ph_no,b.person_incharge,b.person_inc_mobile,b.person_inc_email
                FROM bms_fin_expense_invoice a
                LEFT JOIN bms_service_provider b ON b.service_provider_id = a.service_provider_id
                WHERE a.exp_inv_id =".$exp_inv_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function deleteExpenseItem($exp_id) {
        return $this->db->delete('bms_fin_expense_invoice',array('exp_inv_id'=>$exp_id));
    }

    function deleteExpenses ($exp_id) {
        return $this->db->delete('bms_fin_exp_inv_items',array('exp_id'=>$exp_id));
    }

    function deleteExpenseSubItem($exp_item_id) {
        return $this->db->delete('bms_fin_exp_inv_items',array('exp_item_id'=>$exp_item_id));
    }

    function getPONumber ($sp_id) {
        $sql = "SELECT pur_order_id, po_no 
                FROM bms_fin_purchase_orders
                WHERE 	invoice_create_status =0 and service_provider_id =".$sp_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getPONumberdetails ($sp_id) {
        $sql = "SELECT pur_order_id, po_no 
                FROM bms_fin_purchase_orders
                WHERE service_provider_id =".$sp_id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_po_id_from_exp_inv_id ($exp_inv_id) {
        $sql = "SELECT * FROM bms_fin_expense_invoice a WHERE a.exp_inv_id =".$exp_inv_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function validate_vendor_invoice_number ( $exp_inv_no, $property_id, $service_provider_id, $exp_inv_id ) {
        $str = '';
        if( $exp_inv_id != 0 ) {
            $str = " and exp_inv_id NOT IN (" . $exp_inv_id . ")";
        }
        $sql = "SELECT count(*) as total FROM bms_fin_expense_invoice WHERE exp_inv_no = '".$exp_inv_no. "' and property_id  = " . $property_id . " and service_provider_id = " . $service_provider_id . $str;
        $query = $this->db->query($sql);
        $row = $query->row();
        return $row->total;
    }

    function getExpensesForSummary ( $from, $to, $property_id ) {
        $sql = "SELECT exp_doc_no, exp_inv_no, exp_date, a.total, CONCAT(d.first_name,' ',d.last_name), remarks
        FROM bms_fin_expense_invoice a 
        LEFT JOIN bms_staff d ON d.staff_id=a.created_by      
        WHERE exp_date BETWEEN '$from' AND '$to'
        AND exp_inv_id NOT IN (SELECT invoice_id FROM bms_fin_ap_credit_note WHERE property_id = '$property_id')
        AND a.property_id = '$property_id' ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_exp_item_details_from_exp_item_id ( $exp_item_id ) {
        $sql = "SELECT paid_amount 
              FROM bms_fin_exp_inv_items 
              WHERE exp_item_id = '".$exp_item_id. "'";
        $query = $this->db->query($sql);
        return $row = $query->row();
    }

}