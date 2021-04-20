<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ob_start();
class Bms_home_butler extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();        
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        
        $this->load->model('bms_home_butler_model');        
    }

    public function vendor_cat_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Vendor Category List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Vendor Category List';        
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('home_butler/vendor_cat_list_view',$data);
	} 
    
    public function get_vendor_cat_list() {        
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_home_butler_model->get_vendor_cat_list ($_POST['offset'],$_POST['rows'],$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    public function vendor_cat_form($vendor_cat_id = '') {
        
        $type = $vendor_cat_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Vendor Category';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Vendor Category <i class="fa fa-angle-double-right"></i> '.$type.' Vendor Category';
        $data['vendor_cat_id'] = $vendor_cat_id;
        if($vendor_cat_id != '') {
            $data['vendor_cat'] = $this->bms_home_butler_model->get_vendor_cat_details($vendor_cat_id);          
        }
        
        $this->load->view('home_butler/vendor_cat_form_view',$data);
	}    
       
    function vendor_cat_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $vendor_cat = $this->input->post('vendor_cat');
        if(!empty($vendor_cat)) {
            $type = 'edit';
            //$vendor_cat['date'] = date('Y-m-d',strtotime($vendor_cat['date']));
            $vendor_cat_id = $this->input->post('vendor_cat_id');
            if(!empty($vendor_cat_id)) {
                $type = 'add';
                $this->bms_home_butler_model->update_vendor_cat($vendor_cat,$vendor_cat_id);     
            } else {
                $this->bms_home_butler_model->insert_vendor_cat($vendor_cat);     
            }
            
            $_SESSION['flash_msg'] = 'Vendor Category '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_home_butler/vendor_cat_list');
    }      
     
     
    public function vendor_list ($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Vendors List';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Vendors List';
        
        $data['states'] = $this->bms_home_butler_model->getStates ();    
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;  
        
        $this->load->view('home_butler/vendor_list_view',$data);
	} 
    
    public function get_vendor_list() {        
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_home_butler_model->get_vendor_list ($_POST['offset'],$_POST['rows'],$this->input->post('state_id'),$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    function get_city () {
        header('Content-type: application/json');
        $state_id = trim($this->input->post('state_id')); 
        $cities = array();
        if($state_id) {
            $cities = $this->bms_home_butler_model->getCities ($state_id);            
        }
        echo json_encode($cities);
    }
    
    function get_town () {
        header('Content-type: application/json');
        $state_id = trim($this->input->post('state_id')); 
        $city_id = trim($this->input->post('city_id')); 
        $towns = array();
        if($state_id && $city_id) {
            $towns = $this->bms_home_butler_model->getTowns ($state_id,$city_id);            
        }
        echo json_encode($towns);
    }
    
    public function vendor_form($vendor_id = '') {
        
        $type = $vendor_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Vendor';
        $data['page_header'] = '<i class="fa fa-certificate"></i> Vendors <i class="fa fa-angle-double-right"></i> '.$type.' Vendor';
        $data['vendor_id'] = $vendor_id;
        if($vendor_id != '') {
            $data['vendor'] = $this->bms_home_butler_model->get_vendor_details($vendor_id); 
            if(!empty($data['vendor']['vendor_state'])) {
                $data['cities'] = $this->bms_home_butler_model->getCities ($data['vendor']['vendor_state']);    
            } 
            if(!empty($data['vendor']['vendor_state']) && !empty($data['vendor']['vendor_city'])) {
                $data['towns'] = $this->bms_home_butler_model->getTowns ($data['vendor']['vendor_state'],$data['vendor']['vendor_city']);    
            }        
        }
        
        $data['states'] = $this->bms_home_butler_model->getStates (); 
        $data['vendor_cat'] = $this->bms_home_butler_model->get_all_vendor_cat_list (); 
        
        $this->load->view('home_butler/vendor_form_view',$data);
	}
    
    function vendor_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $vendor = $this->input->post('vendor');
        if(!empty($vendor)) {
            $type = 'add';
            //$vendor['date'] = date('Y-m-d',strtotime($vendor['date']));
            $vendor_city = trim($this->input->post('vendor_city_txt'));
            if(!empty($vendor_city)) {
                //
                $vendor_city_info = $this->bms_home_butler_model->checkCity ($vendor_city,$vendor['vendor_state']);
                if(empty($vendor_city_info['city_id'])) {
                    $vendor['vendor_city'] = $this->bms_home_butler_model->insertCity ($vendor_city,$vendor['vendor_state']);
                } else {
                    $vendor['vendor_city'] = $vendor_city_info['city_id'];
                }
            }
            
            $vendor_town = trim($this->input->post('vendor_town_txt'));
            if(!empty($vendor_town)) {
                //
                $vendor_town_info = $this->bms_home_butler_model->checkTown ($vendor_town,$vendor['vendor_city'],$vendor['vendor_state']);
                if(empty($vendor_town_info['town_id'])) {
                    $vendor['vendor_town'] = $this->bms_home_butler_model->insertTown ($vendor_town,$vendor['vendor_city'],$vendor['vendor_state']);
                } else {
                    $vendor['vendor_town'] = $vendor_town_info['town_id'];
                }
            }
            
            $vendor_id = $this->input->post('vendor_id');
            if(!empty($vendor_id)) {
                $type = 'edit';
                $this->bms_home_butler_model->update_vendor($vendor,$vendor_id);     
            } else {
                $this->bms_home_butler_model->insert_vendor($vendor);     
            }
            
            $_SESSION['flash_msg'] = 'Vendor '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_home_butler/vendor_list');
    }
    
}