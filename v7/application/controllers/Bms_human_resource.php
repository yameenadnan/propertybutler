<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_human_resource extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff' || !in_array($_SESSION['bms']['designation_id'],$this->config->item('hr_access_desi'))) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);
	    }
        //if(!in_array($this->uri->segment(2), array('get_staff_list','check_email')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_hr_model');         
    }

    public function staff_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Staff List';
        $data['page_header'] = '<i class="fa fa-users"></i> Staff Setup <i class="fa fa-angle-double-right"></i> Staff List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('hr/staff_list_view',$data);
	}
    
    public function get_staff_list() {        
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_hr_model->get_staff_list ($_POST['offset'],$_POST['rows'],$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    public function staff_profile($staff_id = '') {
        
        $type = $staff_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Staff';
        $data['page_header'] = '<i class="fa fa-info-users"></i> Staff <i class="fa fa-angle-double-right"></i> '.$type.' Staff';
        $data['staff_id'] = '';
        if($staff_id != '') {
            $data['staff_info'] = $this->bms_hr_model->get_staff_details($staff_id);
            if(empty($data['staff_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }
            
            $data['staff_duty'] = $this->bms_hr_model->get_staff_duty($staff_id);
            $data['staff_property'] = $this->bms_hr_model->get_staff_property($staff_id);
            $data['staff_module'] = $this->bms_hr_model->get_staff_module($staff_id);  
            
            $data['staff_leave_ent'] = $this->bms_hr_model->get_staff_bms_leave_entitlement($staff_id);
            $data['staff_id'] = $staff_id;          
        }
        
        $data['properties'] = $this->bms_masters_model->getProperties ();        
        $data['shift_pattern'] = $this->bms_masters_model->getShiftPattern ();
        $data['designation'] = $this->bms_masters_model->getDesignation ();
        $data['emp_type'] = $this->bms_masters_model->getEmpType ();
        $data['module'] = $this->bms_masters_model->getModule ();
        
        //echo "<pre>";print_r($data);echo "</pre>"; exit;
        $this->load->view('hr/staff_new_view',$data);
	} 
    
    function check_email () {
        if(isset($_POST['email_addr']) && $_POST['email_addr'] != '' ) { 
            $email_addr = trim($_POST['email_addr']);
            $staff_id = $this->input->post('staff_id');
            $result = $this->bms_hr_model->check_email($email_addr,$staff_id);
            if(count($result) == 1 ) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    } 
    
    function staff_profile_submit () {
        $staff_id = $this->input->post('staff_id');
        
        
        $staff_info = $this->input->post('staff');
        $staff_info['dob'] = date('Y-m-d', strtotime($staff_info['dob']));
        
        $staff_duty = $this->input->post('staff_duty');
        $staff_leave_ent = $this->input->post('staff_leave_ent');
        
        $staff_property = $this->input->post('staff_property');
        $staff_module = $this->input->post('staff_module');
        
        $type = 'add';
        if($staff_id) {
            $type = 'edit';
            $this->bms_hr_model->update_staff($staff_info,$staff_id);
            $staff_duty_upd_stat = $this->bms_hr_model->update_staff_duty($staff_duty,$staff_id);
            if(!$staff_duty_upd_stat) {
                $staff_duty['staff_id'] = $staff_id;
                $this->bms_hr_model->insert_staff_duty($staff_duty);
            }
            
            $staff_leave_ent_upd_stat = $this->bms_hr_model->update_staff_leave_ent($staff_leave_ent,$staff_id);
            if(!$staff_leave_ent_upd_stat) {
                $staff_leave_ent['staff_id'] = $staff_id;
                $this->bms_hr_model->insert_staff_leave_ent($staff_leave_ent);
            }
            $this->bms_hr_model->delete_staff_property($staff_id);
            $this->bms_hr_model->delete_staff_module($staff_id);
            
                
        } else {
            $staff_id = $this->bms_hr_model->insert_staff($staff_info);
            $staff_duty['staff_id'] = $staff_id;
            $this->bms_hr_model->insert_staff_duty($staff_duty);
            
            $staff_leave_ent['staff_id'] = $staff_id;
            $this->bms_hr_model->insert_staff_leave_ent($staff_leave_ent);
        }
        
        if(!empty($staff_property)) {
            $ins_sp_data['staff_id'] = $staff_id;
            foreach($staff_property as $key=>$val) {
                $ins_sp_data['property_id'] = $val;
                $this->bms_hr_model->insert_staff_property($ins_sp_data);
            }
        }
        
        if(!empty($staff_module)) {
            $ins_sm_data['staff_id'] = $staff_id;
            foreach($staff_module as $key=>$val) {
                $ins_sm_data['module_id'] = $val;
                $this->bms_hr_model->insert_staff_module($ins_sm_data);
            }
        }
        //echo "<pre>";print_r($staff_module); exit;
        $_SESSION['flash_msg'] = 'Staff '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        redirect('index.php/bms_human_resource/staff_list');
        
    }
    
    function notice_board () {
        
        $data['browser_title'] = 'Property Butler | Notice Board';
        $data['page_header'] = '<i class="fa fa-envelope-open"></i> Notice Board';
         
        $data['notice_board'] = $this->bms_masters_model->getNoticeBoard();
        $this->load->view('hr/notice_board_view',$data);
    } 
    
    function notice_board_submit () {
        
        if(!empty($_POST['message'])) {
            //$_POST['message'] = $_POST['message'];
            $staff_id = $this->bms_hr_model->insert_notice_board($_POST['notice_id'],nl2br ($_POST['message']));
            $_SESSION['flash_msg'] = 'Notice Board Message Updated successfully!';
        }
        redirect('index.php/bms_human_resource/notice_board');
    }
    
    public function holiday_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Holiday Setup';
        $data['page_header'] = '<i class="fa fa-undo"></i> Holiday Setup <i class="fa fa-angle-double-right"></i> Holidays List';
                
        $data['states'] = $this->bms_hr_model->getStates ();
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('hr/holiday_list_view',$data);
	}
    
    public function get_holiday_list() {        
        
        header('Content-type: application/json');        
        
        $holidays = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $holidays = $this->bms_hr_model->get_holiday_list ($_POST['offset'],$_POST['rows'],$_POST['state_id'],$search_txt);
        }      
                
        echo json_encode($holidays);
	}
    
    public function holiday_form($holiday_id = '') {
        
        $type = $holiday_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | Holiday Setup';
        $data['page_header'] = '<i class="fa fa-undo"></i> Holiday Setup <i class="fa fa-angle-double-right"></i> '.$type.' Holiday';
        
        $data['holiday_id'] = $holiday_id;
        $data['states'] = $this->bms_hr_model->getStates ();
        
        if($holiday_id != '') {
            $data['holiday'] = $this->bms_hr_model->get_holiday_details($holiday_id);          
        }
        
        
        //echo "<pre>";print_r($data);echo "</pre>"; exit;
        $this->load->view('hr/holiday_form_view',$data);
	}
    
    function holiday_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $holiday = $this->input->post('holiday');
        if(!empty($holiday)) {
            $type = 'add';
            $holiday['date'] = date('Y-m-d',strtotime($holiday['date']));
            $holiday_id = $this->input->post('holiday_id');
            if(!empty($holiday_id)) {
                $this->bms_hr_model->update_holiday($holiday,$holiday_id);     
            } else {
                $this->bms_hr_model->insert_holiday($holiday);     
            }
            //$staff_id = $this->bms_hr_model->insert_notice_board($_POST['notice_id'],$_POST['message']);
            $_SESSION['flash_msg'] = 'Holiday '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_human_resource/holiday_list');
    }
    
    public function designation_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Designation List';
        $data['page_header'] = '<i class="fa fa-object-group"></i> Designation Setup <i class="fa fa-angle-double-right"></i> Designations List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('hr/designation_list_view',$data);
	} 
    
    public function get_designation_list() {        
        
        header('Content-type: application/json');        
        
        $staff = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $staff = $this->bms_hr_model->get_designation_list ($_POST['offset'],$_POST['rows'],$search_txt);
        }      
                
        echo json_encode($staff);
	}
    
    public function designation_form($desi_id = '') {
        
        $type = $desi_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Designation';
        $data['page_header'] = '<i class="fa fa-object-group"></i> Designations <i class="fa fa-angle-double-right"></i> '.$type.' Designation';
        $data['desi_id'] = $desi_id;
        if($desi_id != '') {
            $data['designation'] = $this->bms_hr_model->get_designation_details($desi_id);          
        }
        
        $this->load->view('hr/designation_form_view',$data);
	}
    
    function designation_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $designation = $this->input->post('designation');
        if(!empty($designation)) {
            $type = 'add';
            //$designation['date'] = date('Y-m-d',strtotime($designation['date']));
            $desi_id = $this->input->post('desi_id');
            if(!empty($desi_id)) {
                $this->bms_hr_model->update_designation($designation,$desi_id);     
            } else {
                $this->bms_hr_model->insert_designation($designation);     
            }
            //$staff_id = $this->bms_hr_model->insert_notice_board($_POST['notice_id'],$_POST['message']);
            $_SESSION['flash_msg'] = 'Designation '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        }
        redirect('index.php/bms_human_resource/designation_list');
    } 
    
}