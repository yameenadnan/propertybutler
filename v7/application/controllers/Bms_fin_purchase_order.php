<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
class Bms_fin_purchase_order extends CI_Controller {
    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
            redirect('index.php/bms_index/login?return_url=' . ($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1') . $_SERVER['REQUEST_URI']);
        }   
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_fin_purchase_model');
        $this->load->model('bms_masters_model');
        $this->load->model('bms_fin_masters_model');
    }
    public function purchase_list_view($offset = 0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Purchase Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Purchase Orders';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_purchase_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_purchase_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/purchase_order/purchase_order_list_view', $data); //bms_purchase_add
    }

    public function purchase_add ($po_order_id = '') {
        $data['browser_title'] = 'Property Butler | Purchase Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Purchase Orders';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_purchase_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_purchase_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
         }
         
         if(!empty($po_order_id)) {
             $data['poitem'] = $this->bms_fin_purchase_model->getPurchaseOrder($po_order_id);
               if(!empty($data['poitem']['pur_order_id'])) {
                   $data['po_sub_items'] = $this->bms_fin_purchase_model->getPurchaseOrderitems($data['poitem']['pur_order_id']);
                  /* if(!empty($data['po_sub_items'])) {
                       foreach ($data['po_sub_items'] as $key=>$val) { 
                           if(!empty($val['sub_cat_id'])){ 
                               $data['po_sub_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory($val['cat_id']);;
                           } else {
                               $data['po_sub_items'][$key]['sub_cat_dd'] = array();
                           }
                       }
                   }   */
             }
         }
      
        $this->load->view('finance/purchase_order/purchase_order_add_view', $data); //bms_purchase_add
    }
    
    
    function get_purchase_list () {
        header('Content-type: application/json');
        $poorder = array();
             
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
              
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $poorder = $this->bms_fin_purchase_model->getPurchaseList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($poorder);
    }
    
    
    
    function add_purchase_order () {
 
      //echo "<pre>";print_r($_POST); echo "</pre>";
      //exit;
       $poitem = $this->input->post('poitem');
       if($poitem[po_no]==''){
            $prop_abbrev = $this->input->post('prop_abbr');
            $pur_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/PO/'.date('y').'/'.date('m').'/';
            
            $last_po_no = $this->bms_fin_purchase_model->getLastPurchaseNo ($pur_no_format);
            if(!empty ($last_po_no)) {
                $po_no = $pur_no_format . (end(explode('/',$last_po_no['po_no'])) +1);
            } else {
                $po_no = $pur_no_format . 1001;
            }
       }else{
           $po_no = $poitem[po_no];
       }
       
        $podate = $this->input->post('po_date');
        if(!empty($podate)) {
            $podate = date('Y-m-d',strtotime($podate));
        }
        
        $delidate = $this->input->post('delivery_date');
        if(!empty($delidate)) {
            $delidate = date('Y-m-d',strtotime($delidate));
        }    
                
        $data = array(
            'property_id' => $this->input->post('po_id'),
            'po_no' =>  $po_no,
            'service_provider_id' => $this->input->post('service_provider'),
            'date' => $podate,
            'delivery_date' => $delidate,
            'remarks' => $this->input->post('remarks'),
            'subtotal' => $this->input->post('rstsubtot'),
            'discounts' => $this->input->post('maindiscount'),
            'nettotal' => $this->input->post('mainntat'),
            'tax_percent' => $this->input->post('taxpers'),
            'tax_amt' => $this->input->post('taxamt'),
            'total' => $this->input->post('maintotat'),
            'invoice_pending_amount' =>$this->input->post('maintotat')
        );
        if(!empty($poitem['po_no'])) {
            $type = "edit";
            $this->bms_fin_purchase_model->updatePurchaseOrder($data,$poitem['pur_order_id']);
            $insert_id = $poitem['pur_order_id'];
        }else {
            $insert_id = $this->bms_fin_purchase_model->insertPurchaseOrder($data);
        }
         $items = $this->input->post('items');
        foreach ($items['description'] as $key=>$val) {
            $item['description'] = $val; 
            $item['po_id'] = $insert_id;
            $item['asset_id'] = $items['assetlst'][$key];
            $item['coa_id'] = $items['category'][$key];
            //$item['sub_cat_id'] = $items['subcategory'][$key];
            $item['qty'] = $items['quantity'][$key];
            $item['uom'] = $items['uom'][$key];
            $item['unit_price'] = $items['subunitprice'][$key];
            $item['amount'] = $items['amount'][$key];
            $item['tax_percent'] = $items['amtitemtaxper'][$key];
            $item['tax_amt'] = $items['amtitemtaxamt'][$key];
            $item['discount_amt'] = $items['distamount'][$key];
            $item['net_amount'] = $items['netamount'][$key];
            $po_item_id = $items['po_item_id'][$key];
            
            if(!empty($po_item_id)) {
                $this->bms_fin_purchase_model->updatePurchaseOrderItem($item,$po_item_id);
            } else {
                $this->bms_fin_purchase_model->insertPOItem($item);
            }
        } 
             $_SESSION['flash_msg'] = 'Purchase Order '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!';
             redirect ('index.php/bms_fin_purchase_order/purchase_list_view');
    }
     
    function unset_bill () {
        $pur_id = $this->input->post('pur_id');
        $this->bms_fin_purchase_model->deletePurchaseItem ($pur_id);
        echo $this->bms_fin_purchase_model->deletePurchase ($pur_id);
    }

    function unset_purchase_item () {
        $po_item = $this->input->post('po_item_id');
        $this->bms_fin_purchase_model->deletePOItems($po_item);
    }
    public function getSubCategoryList(){
        header('Content-type: application/json');
        $category_id = trim($this->input->post('category_id'));
        $subcategory = array();
        if($category_id) {
            $subcategory = $this->bms_fin_masters_model->getSubCategory($category_id);
        }
        echo json_encode($subcategory);
    }
	
	 public function purchase_popup($po_order_id = '', $act_type='') {

        $data['browser_title'] = 'Property Butler | Purchase Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Purchase Orders';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_purchase_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_purchase_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }

        if(!empty($po_order_id)) {
            $data['poitem'] = $this->bms_fin_purchase_model->getPurchaseOrder($po_order_id);
            if(!empty($data['poitem']['pur_order_id'])) {
                $data['po_sub_items'] = $this->bms_fin_purchase_model->getPurchaseOrderitems($data['poitem']['pur_order_id']);
               /* if(!empty($data['po_sub_items'])) {
                    foreach ($data['po_sub_items'] as $key=>$val) {
                        if(!empty($val['sub_cat_id'])){
                            $data['po_sub_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory($val['cat_id']);;
                        } else {
                            $data['po_sub_items'][$key]['sub_cat_dd'] = array();
                        }
                    }
                } */
            }

            foreach ($data['properties'] as $key=>$val){
                if($val['property_id']==$data['poitem']['property_id']){
                    $data['pro_name'] = $val['property_name'];
                }
            }
        }
		
		$data ['act_type'] = $act_type;
        if($act_type == 'download') {
            $this->load->library('M_pdf');
            $html = $this->load->view('finance/purchase_order/purchase_popup_view_pdf',$data,true);
            $res = $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['pv_item']['pay_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/purchase_order/purchase_popup_view_print',$data);
        }else {
		    $this->load->view('finance/purchase_order/purchase_popup_view', $data); //bms_purchase_add
		}
    }
}