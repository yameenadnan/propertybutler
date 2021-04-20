<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_banks extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_banks_model');
        $this->load->helper('common_functions');
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

   
	public function bank_list ($offset = 0, $per_page = 25) {
		//echo "fdfd";exit;
		// $this->load->model('vendors_model');
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Bank';
        $data['page_header'] = '<i class="fa fa-tree"></i> Bank';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/bank/bank_list_view',$data);
	}  
    
    function get_banks_list () {
        header('Content-type: application/json');        
        
        $banks = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $banks = $this->bms_fin_banks_model->getBanksList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }       
        echo json_encode($banks);
    } 
    
    
	public function add_bank($bank_id = ''){
		$data['browser_title'] = 'Property Butler | Bank';
        $data['page_header'] = '<i class="fa fa-tree"></i> Bank <i class="fa fa-angle-double-right"></i> '.($bank_id != '' ? 'Update' : 'New').' Bank';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if(!empty($bank_id)) {
            $data['bank'] = $this->bms_fin_banks_model->getBank($bank_id);               
        }
        $data ['property_id'] = !empty($data['bank']['property_id']) ? $data['bank']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
           
        $this->load->view('finance/bank/bank_add_view',$data);
	}
    
    function bank_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";       
        $bank = $this->input->post('bank');        
        
        if(!empty($bank['ob_date'])) {
            $bank['ob_date'] = date('Y-m-d',strtotime($bank['ob_date']));
        }
        
        $type = 'add';
        if(!empty($bank['bank_id'])) {
            $type = 'edit';            
            $this->bms_fin_banks_model->updateBank ($bank,$bank['bank_id']);
        } else {            
            $bank_id = $this->bms_fin_banks_model->insertBank ($bank);
        }        
        
        $_SESSION['flash_msg'] = 'Bank '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_banks/bank_list/0/25?property_id='.$bank['property_id']); 
    }   
    
    function unset_bank () {
        $bank_id = $this->input->post('bank_id');
        echo $this->bms_fin_banks_model->deleteBank ($bank_id);
    }    
      
}