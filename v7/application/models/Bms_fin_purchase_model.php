<?php
defined('BASEPATH') or exit('No direct script access allowed');

Class Bms_fin_purchase_model extends CI_Model {
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
    
    
    function getLastPurchaseNo ($po_no_format) {
        $sql = "SELECT po_no FROM bms_fin_purchase_orders WHERE po_no LIKE '".$po_no_format."%' ORDER BY pur_order_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        return $query->row_array();
    }
    
    
    function insertPurchaseOrder($data){
        $data['created_by'] = $_SESSION['bms']['staff_id'];
        $data['created_date'] = date("Y-m-d");
        $data['po_time'] = date('H:i:s');
        $this->db->insert('bms_fin_purchase_orders', $data);
        return $this->db->insert_id();   
       }
       
       function insertPOItem ($data) {
            $this->db->insert('bms_fin_po_items', $data);
            return $this->db->insert_id();  
       }
    
       
	
	function updatePurchaseOrder ($data,$id) {
	    $data['updated_by'] = $_SESSION['bms']['staff_id'];
	    $data['updated_date'] = date("Y-m-d");
	    $this->db->update('bms_fin_purchase_orders', $data, array('pur_order_id' => $id));
	}
	 
	function updatePurchaseOrderItem ($data,$id) {
	   $this->db->update('bms_fin_po_items', $data,  array('po_item_id' => $id));
	   $sql = $this->db->last_query();
	 
	}
 
	function getPurchaseList ($offset = '0', $per_page = '25', $property_id ='', $search_txt ='') {
 	    $limit = '';
 	   
	    if($offset != '' && $per_page != '')
	        $limit = ' LIMIT '. $offset .', '.$per_page;
	        
	        $cond = '';
	        $cond .= " AND a.property_id=(SELECT property_id FROM bms_staff_property WHERE property_id = ".$property_id." AND staff_id = ".$_SESSION['bms']['staff_id'].") ";
	        if($search_txt != '') {
	            $search_txt = $this->db->escape_str($search_txt);
	            $cond .= " AND (LOWER(owner_name) LIKE '%".$search_txt."%' OR LOWER(unit_no) LIKE '%".$search_txt."%' OR LOWER(bill_date) LIKE '%".$search_txt."%' OR LOWER(total_amount) LIKE '%".$search_txt."%')";
	        }
	        
	        $sql = "SELECT pur_order_id,property_name,po_no,unit_id,DATE,delivery_date,total,payment_status,invoice_create_status,c.service_provider_id,c.provider_name,c.person_incharge
                FROM bms_fin_purchase_orders a
                LEFT JOIN bms_property b ON b.property_id = a.property_id
                LEFT JOIN bms_service_provider c ON c.service_provider_id = a.service_provider_id
                WHERE 1=1 ". $cond ;
	        
	        
	        $query = $this->db->query($sql);
	        $num_rows = $query->num_rows();
	        
	        $order_by = " ORDER BY pur_order_id DESC".$limit;
	        $query = $this->db->query($sql.$order_by);
 
	        $data = $query->result_array();
	        return array('numFound'=>$num_rows,'records'=>$data);
	        
	}
	
	function getPurchaseOrderitems ($pur_order_id) {
	    $sql = "SELECT po_item_id,po_id,description,qty,uom,unit_price,amount,discount_amt,net_amount,asset_id,coa_id,sub_cat_id,tax_percent,tax_amt
                FROM bms_fin_po_items a
                WHERE po_id =".$pur_order_id;
	    $query = $this->db->query($sql);
	    return $query->result_array();
	}
	
	function getPurchaseOrder ($pur_order_id) {
	    $sql = "SELECT a.pur_order_id, a.property_id,a.po_no,a.service_provider_id,a.date,a.delivery_date,a.subtotal,a.discounts,a.nettotal,a.tax_percent,a.tax_amt,a.total,a.payment_status,a.invoice_create_status,a.remarks, b.address,b.postcode,b.city,b.state,b.country,b.office_ph_no,b.person_incharge,b.person_inc_mobile,b.person_inc_email
                FROM bms_fin_purchase_orders a
                LEFT JOIN bms_service_provider b ON b.service_provider_id = a.service_provider_id
                WHERE a.pur_order_id =".$pur_order_id;
 	    $query = $this->db->query($sql);
	    return $query->row_array();
	}
	
	function deletePurchaseItem($pur_id) {
	    return $this->db->delete('bms_fin_purchase_orders',array('pur_order_id'=>$pur_id));
	}
	
 
	function deletePurchase ($pur_id) {
	    return $this->db->delete('bms_fin_po_items',array('po_id'=>$pur_id));
	}

	function deletePOItems($pur_itm){
        return $this->db->delete('bms_fin_po_items',array('po_item_id'=>$pur_itm));
    }
    
}