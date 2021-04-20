<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_finance extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_receipt_model');
        $this->load->helper('common_functions');
    }
    
    function general_ledger () {
        $data['browser_title'] = 'Property Butler | SGeneral Ledger';
        $data['page_header'] = '<i class="fa fa-file"></i> General Ledger';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/general_ledger_view',$data);
    }
    
    function income_expenses () {
        $data['browser_title'] = 'Property Butler | Income & Expenses';
        $data['page_header'] = '<i class="fa fa-file"></i> Income &amp; Expenses';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/income_expenses_view',$data);
    }
    
    function trail_balance () {
        $data['browser_title'] = 'Property Butler | Trail Balance';
        $data['page_header'] = '<i class="fa fa-file"></i> Trail Balance';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/trail_balance_view',$data);
    }
    
    function balance_sheet () {
        $data['browser_title'] = 'Property Butler | Balance Sheet';
        $data['page_header'] = '<i class="fa fa-file"></i> Balance Sheet';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/balance_sheet_view',$data);
    }
    
    function aging_report () {
        $data['browser_title'] = 'Property Butler | Aging Report';
        $data['page_header'] = '<i class="fa fa-file"></i> Aging Report';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/aging_report_view',$data);
    }
    
    function payment_receipt () {
        $data['browser_title'] = 'Property Butler | Receipt &amp; Payment';
        $data['page_header'] = '<i class="fa fa-file"></i> Receipt &amp; Payment';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/finance/payment_receipt_view',$data);
    }
    
    function reminder_letter () {
        $data['browser_title'] = 'Property Butler | Reminder Letter';
        $data['page_header'] = '<i class="fa fa-file"></i> Reminder Letter';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
        
        $this->load->view('finance/finance/reminder_letter_view',$data);
    }
    
    function bank_reconciliation () {
        $data['browser_title'] = 'Property Butler | Bank Reconciliation';
        $data['page_header'] = '<i class="fa fa-file"></i> Bank Reconciliation';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['banks'] = $this->bms_fin_masters_model->getBanks ($data ['property_id']);
        
        $this->load->view('finance/finance/bank_reconciliation_view',$data);
    }
    
	function journal_entry () {
        $data['browser_title'] = 'Property Butler | Journal entry';
        $data['page_header'] = '<i class="fa fa-file"></i> Journal entry';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        //$data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
        
        $this->load->view('finance/finance/journal_entry_view',$data);
    }
    
}