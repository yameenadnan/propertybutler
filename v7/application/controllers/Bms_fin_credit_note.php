<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_credit_note extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_credit_note_model');
        $this->load->model('bms_fin_receipt_model');
        $this->load->helper('common_functions');
    }

   
	public function credit_note_list ($offset = 0, $per_page = 25) {
		//echo "fdfd";exit;
		// $this->load->model('vendors_model');
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Credit Note';
        $data['page_header'] = '<i class="fa fa-file"></i> Credit Note';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/credit_note/credit_note_list_view',$data);
	}  
    
    function get_credit_notes_list () {
        header('Content-type: application/json');        
        
        $credit_notes = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $credit_notes = $this->bms_fin_credit_note_model->getCreditNotesList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }       
        echo json_encode($credit_notes);
    } 
    
    
    function getSubCategory () {
        header('Content-type: application/json');
        $cat_id = trim($this->input->post('cat_id'));
        $sub_cats = array ();
        if($cat_id) {
            $sub_cats = $this->bms_fin_masters_model->getSubCategory ($cat_id);       
        }
        echo json_encode($sub_cats);
    }
    
	public function add_credit_note($credit_note_id = '') {
		
		//echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Credit Note';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Credit Note <i class="fa fa-angle-double-right"></i> '.($credit_note_id != '' ? 'Update' : 'New').' Credit Note';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if(!empty($credit_note_id)) {
            $data['credit_note'] = $this->bms_fin_credit_note_model->getCreditNote($credit_note_id);
            if(!empty($data['credit_note']['credit_note_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['credit_note']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['credit_note']['property_id'],$data['credit_note']['block_id']);   
                $data['credit_note_items'] = $this->bms_fin_credit_note_model->getCreditNoteItems($data['credit_note']['credit_note_id']);
                /*if(!empty($data['credit_note_items'])) {
                    foreach ($data['credit_note_items'] as $key=>$val) {
                        if(!empty($val['item_sub_cat_id'])){
                            $data['credit_note_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory ($val['item_cat_id']);    
                        } else {
                            $data['credit_note_items'][$key]['sub_cat_dd'] = array();
                        }                        
                    }                       
                } */               
            }            
        }
        $data ['property_id'] = !empty($data['credit_note']['property_id']) ? $data['credit_note']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        $data['sales_items'] = $this->bms_fin_masters_model->getSalesItemsForBill ($data ['property_id']);        
        //echo "<pre>";print_r($data['credit_note_items']);echo "</pre>";
        //$property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';        
        $this->load->view('finance/credit_note/credit_note_add_view',$data);
	}
    
    function credit_note_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        
        $credit_note = $this->input->post('credit_note');
        $items = $this->input->post('items');
        $type = 'add';
        if(!empty($credit_note['credit_note_date'])) {
            $credit_note['credit_note_date'] = date('Y-m-d',strtotime($credit_note['credit_note_date']));
        }
        
        
        if(!empty($credit_note['credit_note_id'])) {
            $type = 'edit';
            $credit_note_id = $credit_note['credit_note_id'];
            $this->bms_fin_credit_note_model->updateCreditNote ($credit_note,$credit_note['credit_note_id']);
        } else {
            if(isset($credit_note['credit_note_id'])) unset($credit_note['credit_note_id']);
            $prop_abbrev = $this->input->post('prop_abbr');
            $credit_note_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/CN/'.date('y').'/'.date('m').'/';
            $last_credit_note_no = $this->bms_fin_credit_note_model->getLastCreditNoteNo ($credit_note_no_format);
            if(!empty ($last_credit_note_no)) {
                $last_no = explode('/',$last_credit_note_no['credit_note_no']);
                $credit_note['credit_note_no'] = $credit_note_no_format . (end($last_no) +1);
            } else {
                $credit_note['credit_note_no'] = $credit_note_no_format . 1001;
            }
            $credit_note['credit_note_time'] = date('H:i:s');
            $credit_note_id = $this->bms_fin_credit_note_model->insertCreditNote ($credit_note);
        }
        
        if(!empty($items['item_cat_id'])) {
            $item['credit_note_id'] = $credit_note_id;
            $bill_item_ids = array();
            foreach ($items['item_cat_id'] as $key=>$val) {
                if(!empty($items['adj_amount'][$key])) {
                    $item['item_cat_id'] = $val;
                    $item['item_sub_cat_id'] = $items['item_sub_cat_id'][$key] != 'None' ? $items['item_sub_cat_id'][$key]: ''; 
                    $item['item_descrip'] = $items['item_descrip'][$key];
                    $item['item_period'] = $items['item_period'][$key];
                    $item['item_amount'] = $items['item_amount'][$key]; 
                    $item['adj_amount'] = $items['adj_amount'][$key];
                    $item['bal_amount'] = $items['bal_amount'][$key];
                    
                    if(!empty($items['bill_item_id'][$key])) {
                        $bill_item['item_amount'] = $item['item_amount'];
                        $bill_item['bal_amount'] = $item['bal_amount'];
                        if(abs($item['bal_amount'] - 0) < 0.00001) {
                            $bill_item['paid_amount'] = $item['item_amount'];
                            $bill_item['paid_status'] = 1;
                        } else {
                            $bill_item['paid_amount'] = $item['item_amount'] - $item['bal_amount'];
                            $bill_item['paid_status'] = 0;
                        }
                        $this->bms_fin_receipt_model->setBillItem($items['bill_item_id'][$key],$bill_item);
                        array_push($bill_item_ids,$items['bill_item_id'][$key]);
                        $item['bill_item_id'] = $items['bill_item_id'][$key] ;
                    } else {
                        $item['bill_item_id'] = '';
                    }
                    
                    if(!empty($items['credit_note_item_id'][$key])) {
                        $this->bms_fin_credit_note_model->updateCreditNoteItem ($item,$items['credit_note_item_id'][$key]);
                    } else {
                        $this->bms_fin_credit_note_model->insertCreditNoteItem ($item);    
                    }
                }               
            }
            
            if(!empty($bill_item_ids)) {
                $bill_ids = $this->bms_fin_receipt_model->getBillItemPaidStatusForBill (implode(',',$bill_item_ids));
                if(!empty($bill_ids)) {                    
                    foreach ($bill_ids as $key=>$val) {
                        if(!empty($val['bill_cnt']) && $val['bill_cnt'] > 0 && $val['bill_cnt'] == $val['paid_cnt']) {
                            $this->bms_fin_receipt_model->setBillAsPaid($val['bill_id']);
                        }
                    }
                }    
            }
        }
        
        $_SESSION['flash_msg'] = 'Credit Note '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_credit_note/credit_note_details/'.$credit_note_id);
    }
    
    public function credit_note_details ($credit_note_id,$act_type = 'view') {		
		
		$data['browser_title'] = 'Property Butler | Credit Note';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Credit Note Details';              
        
        if(!empty($credit_note_id)) {
            $data['credit_note'] = $this->bms_fin_credit_note_model->getCreditNoteDetails($credit_note_id);
            if(!empty($data['credit_note']['credit_note_id'])) {
                $data['credit_note_items'] = $this->bms_fin_credit_note_model->getCreditNoteItemsDetail($data['credit_note']['credit_note_id']);
            }            
        }
        //echo "<pre>";print_r($data['credit_note_items']);echo "</pre>";
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');
        
        if($act_type == 'download') {
            
            $this->load->library('M_pdf');
            
            $html = $this->load->view('finance/credit_note/credit_note_details_print_view',$data,true);
            
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['credit_note']['credit_note_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/credit_note/credit_note_details_print_view',$data);
        } else {
            $this->load->view('finance/credit_note/credit_note_details_view',$data);
        }
	}
    
    function unset_credit_note_item () {
        $credit_note_item_id = $this->input->post('credit_note_item_id');
        echo $this->bms_fin_credit_note_model->deleteCreditNoteItem ($credit_note_item_id); 
    }
    
    function unset_credit_note () {
        $credit_note_id = $this->input->post('credit_note_id');
        $this->bms_fin_credit_note_model->deleteCreditNoteItemByCreditNoteId ($credit_note_id); 
        echo $this->bms_fin_credit_note_model->deleteCreditNote ($credit_note_id);
    }
    
    function get_period () {
        $period_format = $this->input->post('period_format');
        echo get_period_dd($period_format);
    }
    
    
    function getOutstandingBillsNo () {
	    header('Content-type: application/json');        
        
        $bill_nos = array();
        $unit_id = $_POST['unit_id'];
        //$data['sales_items'] = $this->bms_fin_masters_model->getSalesItems ();
        if(!empty($unit_id)) {
            $bill_nos = $this->bms_fin_credit_note_model->getOutstandingBillNos($unit_id);            
        }
        echo json_encode($bill_nos);   
	}
    
    function get_bill_items () {
        $bill_id = $this->input->post('bill_id');
        $property_id = $this->input->post('property_id');
        $data=array();
        if(!empty($bill_id)) {
            $data['bill_items'] = $this->bms_fin_credit_note_model->getBillItems($bill_id);   
            $data['sales_items'] = $this->bms_fin_masters_model->getSalesItemsForBill ($property_id);            
            /*if(!empty($data['bill_items'])) {
                
                foreach ($data['bill_items'] as $key=>$val) {
                    if(!empty($val['item_sub_cat_id'])){
                        $data['bill_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory ($val['item_cat_id']);    
                    } else {
                        $data['bill_items'][$key]['sub_cat_dd'] = array();
                    }                        
                }
                //echo "<pre>";print_r($data['bill_items']); echo "</pre>";
                //$this->load->view('finance/receipt/outstanding_items_view',$data);                       
            }  */        
               
        }
        $this->load->view('finance/credit_note/cn_bill_items_view',$data);
    }	
	
}