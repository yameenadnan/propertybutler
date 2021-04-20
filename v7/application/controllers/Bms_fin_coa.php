<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_coa extends CI_Controller {
    
    function __construct () {
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_fin_coa_model');  
    }

     
    public function coa_list ($offset=0, $per_page = 100) {
        $data['browser_title'] = 'Property Butler | COA';
        $data['page_header'] = '<i class="fa fa-code"></i> COA';
                
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        
        $this->load->view('finance/chart_of_account/coa_list_view',$data);
	}
    
    
   
      
    public function get_coa_list () {        
        
        header('Content-type: application/json');        
        
        $list_arr = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;            
            $list_arr = $this->bms_fin_coa_model->get_coa_list ($_POST['offset'], $_POST['rows'], $_POST['property_id'], $search_txt);
        }               
        echo json_encode($list_arr);
	} 
    
    function coa_form ($coa_id = '')  {
        $type = $coa_id != '' ? 'Edit' : 'Add';
        $data['browser_title'] = 'Property Butler | '.$type.' COA';
        $data['page_header'] = '<i class="fa fa-code"></i> COA <i class="fa fa-angle-double-right"></i> '.$type.' COA';
        $data['coa_id'] = $coa_id;
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['acc_type'] = $this->bms_fin_coa_model->getCoaType ();
        $data['acc_name'] = array ();
        if($coa_id != '') {
            $data['coa_info'] = $this->bms_fin_coa_model->get_coa_details($coa_id);                      
        }
        $data ['property_id'] = !empty($data['coa_info']['property_id']) ? $data['coa_info']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $this->load->view('finance/chart_of_account/coa_form_view',$data);
    } 
    
    function get_coa_name_with_code () {
        header('Content-type: application/json');
        $coa_acc_group_id = trim($this->input->post('coa_acc_group_id')); 
        $list_arr = array();
        if($coa_acc_group_id) {
            $list_arr = $this->bms_fin_coa_model->getCoaNameWithCode ($coa_acc_group_id);            
        }
        echo json_encode($list_arr);
    }
    
    function check_coa_code () {
        if(!empty($_POST['property_id']) && !empty($_POST['coa_code']) ) { 
            $property_id = trim($_POST['property_id']);
            $coa_code = trim($_POST['coa_code']);
            $coa_id = trim($_POST['coa_id']);
            $result = $this->bms_fin_coa_model->check_coa_code($property_id,$coa_code,$coa_id);
            if($result['cnt'] > 0 ) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else 
            echo 'false';
    } 
    
    
    function coa_form_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;  
            
        $coa_id = $this->input->post('coa_id');
        
        $coa = $this->input->post('coa');

        $coa['default_acc'] = ( isset( $coa['default_acc'] ) && $coa['default_acc'] == 1 )?1:0;

        $type = 'add';
        if(isset($coa['coa_code']) && trim($coa['coa_code']) !='' && isset($coa['coa_name']) && trim($coa['coa_name']) !='') {
            $coa['opening_cr_date'] = !empty($coa['opening_cr_date']) ? date('Y-m-d',strtotime($coa['opening_cr_date'])): NULL;
            $coa['opening_credit'] = !empty($coa['opening_cr_date']) && !empty($coa['opening_credit']) ? $coa['opening_credit'] : '';
            //echo "<pre>";print_r($coa);echo "</pre>"; exit;
            if($coa_id) {
                $this->bms_fin_coa_model->update_coa($coa,$coa_id);
                $type = 'edit';                
            } else {
                $this->bms_fin_coa_model->insert_coa($coa);
            }                                   
            $_SESSION['flash_msg'] = 'COA '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        
        
        //redirect('index.php/bms_chart_of_accounts/coa_sub_acc_list/0/100');
        redirect('index.php/bms_fin_coa/coa_list');
    }
    
    function generate_coa_for_service_provider ($property_id) {
        
        
        
        if(!empty($property_id)) {
            $sp = $this->bms_fin_coa_model->get_service_provider($property_id);
            
            if(!empty($sp)) {
                $last_sp_code = $this->bms_fin_coa_model->get_last_sp_code($property_id);
                if(!empty($last_sp_code)) {
                    //echo "<pre>";print_r($sp);exit;
                    $list = explode('/',$last_sp_code['coa_code']);
                    $last_no = end($list);
                    foreach ($sp as $key=>$val) {
                        ++$last_no;
                        $data['property_id'] = $property_id;
                        $data['coa_type_id'] = 4;
                        $data['coa_code'] = '4100/'.str_pad($last_no, 3, '0', STR_PAD_LEFT);
                        $data['coa_name'] = $val['provider_name'];
                        $data['payment_enabled'] = 1;
                        $insert_id = $this->bms_fin_coa_model ->insert_coa($data); 
                        $this->bms_fin_coa_model->set_service_provider($insert_id,$val['service_provider_id']);
                    }
                }
            }
        }
        /*if(!empty($property_id)) {
            $units = $this->bms_masters_model->getUnit($property_id);
            if(!empty($units)) {
                $last_sp_code = $this->bms_fin_coa_model->get_last_sp_code($property_id);
                if(!empty($last_sp_code)) {
                    $list = explode('/',$last_sp_code['coa_code']);
                    $last_no = end($list);
                    foreach ($units as $key=>$val) {
                        ++$last_no;
                        $data['property_id'] = $property_id;
                        $data['coa_type_id'] = 3;
                        $data['coa_code'] = '3000/'.str_pad($last_no, 3, '0', STR_PAD_LEFT);
                        $data['coa_name'] = $val['owner_name'];
                        $insert_id = $this->bms_fin_coa_model ->insert_coa($data); 
                        $this->bms_fin_coa_model->set_unit_coa($insert_id,$val['unit_id']);
                    }
                }
            }
        } */       
    } 
    
    function generate_coa_for_units ($property_id) {
        
        
        if(!empty($property_id)) {
            $units = $this->bms_fin_coa_model->getUnit($property_id);
            if(!empty($units)) {
                $last_sp_code = $this->bms_fin_coa_model->get_last_unit_code($property_id);
                if(!empty($last_sp_code)) {
                    $list = explode('/',$last_sp_code['coa_code']);
                    $last_no = end($list);
                    foreach ($units as $key=>$val) {
                        ++$last_no;
                        $data['property_id'] = $property_id;
                        $data['coa_type_id'] = 3;
                        $data['coa_code'] = '3000/'.str_pad($last_no, 3, '0', STR_PAD_LEFT);
                        $data['coa_name'] = $val['unit_no'] . ' - ' . $val['owner_name'];
                        $insert_id = $this->bms_fin_coa_model ->insert_coa($data); 
                        $this->bms_fin_coa_model->set_unit_coa($insert_id,$val['unit_id']);
                    }
                }
            }
        }      
                
    } 
}