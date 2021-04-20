<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_user_access_log extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        if(!in_array($_SESSION['bms']['designation_id'],$this->config->item('user_access_log_access_desi')))
            redirect('index.php/bms_dashboard/index'); 
        //if(!in_array($this->uri->segment(2), array('get_staff_names','get_user_access_log','staff_property_details')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        //$this->load->model('bms_attendance_model');  
    }
    
    function user_access_log_list () {
        
        $data['browser_title'] = 'Property Butler | User Access Log List';
        $data['page_header'] = '<i class="fa fa-info-expeditedssl"></i> User Access Log List';
        $data['desi_id'] = isset($_GET['desi_id']) && trim($_GET['desi_id']) != '' ? trim ($_GET['desi_id']) : 0;
        $data['staff_id'] = isset($_GET['staff_id']) && trim($_GET['staff_id']) != '' ? trim ($_GET['staff_id']) : 0;
        $data['rec_date'] = isset($_GET['rec_date']) && trim($_GET['rec_date']) != '' ? trim ($_GET['rec_date']) : 0;
        
        $data['designations'] = $this->bms_masters_model->getDesignation ();
        $data['staff_names'] = $this->bms_masters_model->getStaffNames ($data['desi_id']);
        
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 100;
        
        $this->load->view('user_access_log_view',$data);
    }
    
    function get_staff_names (  ) {
        header('Content-type: application/json');
        $desi_id = trim($this->input->post('desi_id')); 
        $staff_names = array();
        if($desi_id) {
            $staff_names = $this->bms_masters_model->getStaffNames ($desi_id);            
        }
        echo json_encode($staff_names);                
        
    }
    
    function get_user_access_log () {
        header('Content-type: application/json');
        $desi_id = trim($this->input->post('desi_id'));
        $staff_id = trim($this->input->post('staff_id'));
        $rec_date = trim($this->input->post('rec_date'));
        $offset = trim($this->input->post('offset')); 
        $rows = trim($this->input->post('rows'));  
        $logs = array();        
        $staff_names = $this->bms_masters_model->get_user_access_log ($offset,$rows,$staff_id,$desi_id,$rec_date);
        echo json_encode($staff_names);          
    }
    
    /*function get_user_access_log_test () {
        $this->bms_masters_model->get_user_access_log (20,10,1137,2);
    }*/
    
    function staff_property_details ($staff_id) {
                
        $res = $this->bms_masters_model->getMyProperties($staff_id);
        //echo "<pre>";print_r($res); echo "</pre>";
        if(!empty($res)) {
            foreach ($res as $val) {
                echo '<div class="row" style="margin:15px 0;">';
                echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.$val['property_name'].' </span></div>';                
                echo "</div>";
                
            }
            
        } else {
            echo '<div class="">No Record Found.</div>';
        }
    }
    
    function staff_ip_details ($staff_id) {
        $data['mat_date'] = isset($_GET['mat_date']) && trim($_GET['mat_date']) != '' ? trim ($_GET['mat_date']) : '';
        $res = $this->bms_masters_model->get_user_accessed_ip($staff_id,$data['mat_date']);
        //echo "<pre>";print_r($res); echo "</pre>";
        if(!empty($res)) {
            foreach ($res as $val) {
                echo '<div class="row" style="margin:15px 0;">';
                echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.$val['ip_address'].' </span></div>';                
                echo "</div>";
                
            }
            
        } else {
            echo '<div class="">No Record Found.</div>';
        }
    }        
    
    function user_access_log_matching () {
        $data['browser_title'] = 'Property Butler | User Access Log List';
        $data['page_header'] = '<i class="fa fa-info-expeditedssl"></i> User Access Log List';        
        
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 100;
        
        $this->load->view('user_access_log_mat_view',$data);
    }
    
    
    function get_user_access_log_mat () {
        header('Content-type: application/json');
        $mat_date = trim($this->input->post('mat_date'));        
        $offset = trim($this->input->post('offset')); 
        $rows = trim($this->input->post('rows'));  
        $logs = array();        
        $staff_names = $this->bms_masters_model->get_user_access_log_mat ($offset,$rows,$mat_date);
        echo json_encode($staff_names);          
    }
    
}