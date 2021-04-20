<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_charge_codes extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_charge_code_model');  
    }

    
    
    public function charge_codes_list($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Chart Of Accounts';
        $data['page_header'] = '<i class="fa fa-code"></i> Chart Of Accounts';
                
        
        //$data['properties'] = $this->bms_masters_model->getMyProperties ();
        //$data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('finance/chart_of_account/charge_code_list_view',$data);
	}
    
    public function get_charge_code_list() {        
        
        header('Content-type: application/json');        
        
        $assets = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $assets = $this->bms_charge_code_model->get_charge_code_list ($_POST['offset'], $_POST['rows'], $search_txt);
        }               
        echo json_encode($assets);
	}
    
    function charge_code_form ($charge_code_id = '')  {
        $type = $charge_code_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' Chart Of Accounts';
        $data['page_header'] = '<i class="fa fa-code"></i> Chart Of Accounts <i class="fa fa-angle-double-right"></i> '.$type.' Chart Of Accounts';
        $data['charge_code_id'] = $charge_code_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['cat_group'] = $this->bms_charge_code_model->getChargeCodeCatGroup ();
        if($charge_code_id != '') {
            $data['charge_code_info'] = $this->bms_charge_code_model->get_charge_code_details($charge_code_id);
            if(empty($data['charge_code_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }
            $data['charge_code_sub_cat_info'] = $this->bms_charge_code_model->get_charge_code_sub_cat_details($charge_code_id);
            //echo "<pre>";print_r($data['charge_code_sub_cat_info']);echo "</pre>";                 
        }
        $this->load->view('finance/chart_of_account/charge_code_form_view',$data);
    }
    
    function charge_code_form_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;      
        $charge_code = $this->input->post('charge_code');
        
        $charge_code_id = $this->input->post('charge_code_id');
        
        $type = 'add';
        if(isset($charge_code['charge_code_category_name']) && trim($charge_code['charge_code_category_name']) !='') {
            $charge_code['payment'] = !empty($charge_code['payment']) ? $charge_code['payment'] : '0';
            $charge_code['expenses'] = !empty($charge_code['expenses']) ? $charge_code['expenses'] : '0';
            $charge_code['purchase_order'] = !empty($charge_code['purchase_order']) ? $charge_code['purchase_order'] : '0';
            $charge_code['receipt'] = !empty($charge_code['receipt']) ? $charge_code['receipt'] : '0';
            $charge_code['bills'] = !empty($charge_code['bills']) ? $charge_code['bills'] : '0';
            
            
            if($charge_code_id) {
                $this->bms_charge_code_model->update_charge_code($charge_code,$charge_code_id);
                $type = 'edit';                
            } else {
                $charge_code_id = $this->bms_charge_code_model ->insert_charge_code($charge_code);                
            }
            $sub_cat = $this->input->post('sub_cat');
            if(!empty($sub_cat['charge_code_sub_category_name'])) {
                //$service = $this->input->post('service');
                foreach($sub_cat['charge_code_sub_category_name'] as $skey=>$sval) {
                    $sub_cat_data['charge_code_category_id'] = $charge_code_id;
                    if($sval != '') {
                        $sub_cat_data['charge_code_sub_category_name'] = $sub_cat['charge_code_sub_category_name'][$skey];
                        $sub_cat_data['charge_code']= $sub_cat['charge_code'][$skey];
                        if(!empty($sub_cat['charge_code_sub_category_id'][$skey])) {
                            $this->bms_charge_code_model->update_charge_code_sub_cat($sub_cat_data,$sub_cat['charge_code_sub_category_id'][$skey]);    
                        } else {
                            $this->bms_charge_code_model->insert_charge_code_sub_cat($sub_cat_data);
                        }                             
                    }
                }
            }                                   
            $_SESSION['flash_msg'] = 'Charge Code '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        
        
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_charge_codes/charge_codes_list/0/25');
    }
    
    function asset_details () {
        if($asset_id != '') {
            $data['asset_info'] = $this->bms_property_model->get_asset_details($asset_id);
            if(empty($data['asset_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            } 
            $data['service'] = $this->bms_property_model->get_asset_service_period($asset_id);
            
            //echo "<pre>";print_r($data['service']);echo "</pre>";                 
        }
        $this->load->view('properties/add_asset_view',$data);
    }
    
    
    
    
}