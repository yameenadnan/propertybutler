<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_debit_note extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_debit_note_model');
        $this->load->helper('common_functions');
    }

   
	public function debit_note_list ($offset = 0, $per_page = 25) {
		//echo "fdfd";exit;
		// $this->load->model('vendors_model');
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Debit Note';
        $data['page_header'] = '<i class="fa fa-file"></i> Debit Note';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/debit_note/debit_note_list_view',$data);
	}  
    
    function get_debit_notes_list () {
        header('Content-type: application/json');        
        
        $debit_notes = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $debit_notes = $this->bms_fin_debit_note_model->getDebitNotesList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }       
        echo json_encode($debit_notes);
    } 
    
    
    function get_unit_receipt () {
        header('Content-type: application/json');
        $unit_id = trim($this->input->post('unit_id'));
        $receipts = array ();
        if($unit_id) {
            $receipts = $this->bms_fin_debit_note_model->get_unit_receipt ($unit_id);       
        }
        echo json_encode($receipts);
    }
    
	public function add_debit_note($debit_note_id = ''){
		
		//echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Debit Note';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Debit Note <i class="fa fa-angle-double-right"></i> '.($debit_note_id != '' ? 'Update' : 'New').' Debit Note';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if(!empty($debit_note_id)) {
            $data['debit_note'] = $this->bms_fin_debit_note_model->getDebitNote($debit_note_id);
            if(!empty($data['debit_note']['debit_note_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['debit_note']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['debit_note']['property_id'],$data['debit_note']['block_id']);
                $data['receipts'] = $this->bms_fin_debit_note_model->get_unit_receipt ($data['debit_note']['unit_id'],$data['debit_note']['receipt_id']);   
                /*$data['debit_note_items'] = $this->bms_fin_debit_note_model->getCreditNoteItems($data['debit_note']['debit_note_id']);
                if(!empty($data['debit_note_items'])) {
                    foreach ($data['debit_note_items'] as $key=>$val) {
                        if(!empty($val['item_sub_cat_id'])){
                            $data['debit_note_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory ($val['item_cat_id']);    
                        } else {
                            $data['debit_note_items'][$key]['sub_cat_dd'] = array();
                        }                        
                    }                       
                } */               
            }            
        }
        $data ['property_id'] = !empty($data['debit_note']['property_id']) ? $data['debit_note']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        //$data['sales_items'] = $this->bms_fin_masters_model->getSalesItems ();        
        //echo "<pre>";print_r($data['debit_note_items']);echo "</pre>";
        //$property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';        
        $this->load->view('finance/debit_note/debit_note_add_view',$data);
	}
    
    function debit_note_submit () {
        
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        
        $debit_note = $this->input->post('debit_note');
        //$items = $this->input->post('items');
        $type = 'add';
        if(!empty($debit_note['debit_note_date'])) {
            $debit_note['debit_note_date'] = date('Y-m-d',strtotime($debit_note['debit_note_date']));
        }
        
        if(!empty($debit_note['debit_note_id'])) {
            $type = 'edit';
            $debit_note_id = $debit_note['debit_note_id'];
            $this->bms_fin_debit_note_model->updateDebitNote ($debit_note,$debit_note['debit_note_id']);
        } else {
            if(isset($debit_note['debit_note_id'])) unset($debit_note['debit_note_id']);
            $prop_abbrev = $this->input->post('prop_abbr');
            $debit_note_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/DN/'.date('y').'/'.date('m').'/';
            $last_debit_note_no = $this->bms_fin_debit_note_model->getLastDebitNoteNo ($debit_note_no_format);
            if(!empty ($last_debit_note_no)) {
                $last_no = explode('/',$last_debit_note_no['debit_note_no']);
                $debit_note['debit_note_no'] = $debit_note_no_format . (end($last_no) +1);
            } else {
                $debit_note['debit_note_no'] = $debit_note_no_format . 1001;
            }
            $debit_note['debit_note_time'] = date('H:i:s');
            $debit_note_id = $this->bms_fin_debit_note_model->insertDebitNote ($debit_note);
            
            $this->bms_fin_debit_note_model->setDebitNoteItems($debit_note['receipt_id'],$debit_note_id);
            
            /** reverse the invoice knock off process */
            
            $this->load->model('bms_fin_receipt_model');
            $items = $this->bms_fin_receipt_model->getReceiptItems ($debit_note['receipt_id']);
        
            if(!empty($items)) {
                foreach ($items as $key=>$val) {
                    $bill_item = $this->bms_fin_receipt_model->getBillItem ($val['bill_item_id']);
                    if(!empty($bill_item)) {
                        $bill_item['bal_amount'] = $bill_item['bal_amount'] + $val['paid_amount'];
                        $bill_item['paid_amount'] = $bill_item['paid_amount'] - $val['paid_amount'];
                        $bill_item['paid_status'] = 0;
                        $this->bms_fin_receipt_model->setBillItem ($val['bill_item_id'],$bill_item);
                        $this->bms_fin_receipt_model->setBillAsUnPaid ($bill_item['bill_id']);
                    }
                }
            }
            
        }        
      
        $_SESSION['flash_msg'] = 'Debit Note '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_debit_note/debit_note_details/'.$debit_note_id);
    }
    
    public function debit_note_details ($debit_note_id,$act_type = 'view') {		
		
		$data['browser_title'] = 'Property Butler | Debit Note';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Debit Note Details';              
        
        if(!empty($debit_note_id)) {
            $data['debit_note'] = $this->bms_fin_debit_note_model->getDebitNoteDetails($debit_note_id);
            if(!empty($data['debit_note']['debit_note_id'])) {
                $data['debit_note_items'] = $this->bms_fin_debit_note_model->getDebitNoteItemsDetail($data['debit_note']['debit_note_id']);
            }            
        }
        //echo "<pre>";print_r($data['debit_note_items']);echo "</pre>";
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');
        
        if($act_type == 'download') {
            
            $this->load->library('M_pdf');
            
            $html = $this->load->view('finance/debit_note/debit_note_details_print_view',$data,true);
            
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['debit_note']['debit_note_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/debit_note/debit_note_details_print_view',$data);
        } else {
            $this->load->view('finance/debit_note/debit_note_details_view',$data);
        }
	}
    
    
    
    function unset_debit_note () {
        $debit_note_id = $this->input->post('debit_note_id');
        //$this->bms_fin_debit_note_model->deleteCreditNoteItemByCreditNoteId ($debit_note_id); 
        echo $this->bms_fin_debit_note_model->deleteDebitNote ($debit_note_id);
    }
    
    function get_period () {
        $period_format = $this->input->post('period_format');
        echo get_period_dd($period_format);
    }
	
}