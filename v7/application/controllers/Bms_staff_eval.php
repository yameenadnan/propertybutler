<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_staff_eval extends CI_Controller {	
    
    function __construct () { 
        parent::__construct (); 
        //echo $_SESSION['bms']['user_type']; exit;
        if((!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) || (isset($_SESSION['bms']['user_type']) && $_SESSION['bms']['user_type'] != 'jmb' && ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'],array(2,7,14,15,20))))) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    } 
        $this->load->model('bms_masters_model');        
        $this->load->model('bms_staff_eval_model');
    }
    
    public function index() {	
        
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
	   
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models        
        
        $data['browser_title'] = 'Property Butler | Staff Evaluation';
        $data['page_header'] = '<i class="fa fa-bullseye"></i> Staff Evaluation';
        
        $data['eval_message'] = array(1=>'Staff Evaluation can be done between 22nd and 28th of every month!');
        
        $data['eval_status'] = 0;
        
        /*$today_date = new DateTime(date('Y-m-d'));
        $eval_start_date = new DateTime(date('Y-m-22'));*/
        if(date('d') >= 22 && date('d') <= 31) {
            if($_SESSION['bms']['user_type'] == 'jmb') {
                $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
            } else {
                if($_SESSION['bms']['designation_id'] == 2)
                    $data['properties'] = $this->bms_masters_model->getMyProperties ();
                else 
                   $data['properties'] = $this->bms_masters_model->getProperties ();                 
            } 
            $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : '');
            
            $staff_eval_ids = implode(',',$this->config->item('staff_eval_desi'));
            if($_SESSION['bms']['user_type'] == 'jmb') {
                $data['mgt_staffs'] = $this->bms_staff_eval_model->getPropertyStaffsForJmbEval($data ['property_id'],$_SESSION['bms']['member_id'],$staff_eval_ids);
            } else if ($_SESSION['bms']['designation_id'] == 2) {
                $data['mgt_staffs'] = $this->bms_staff_eval_model->getPropertyStaffsForAmEval($data ['property_id'],$_SESSION['bms']['staff_id'],$staff_eval_ids);
            }  else {
                $data['mgt_staffs'] = $this->bms_staff_eval_model->getPropertyStaffsForHrEval($data ['property_id'],$_SESSION['bms']['staff_id'],$staff_eval_ids);
            }
            //echo "<pre>";print_r($data);echo "</pre>";   
        } else {
            $data['eval_status'] = 1;
        }
        
        if($_SESSION['bms']['user_type'] == 'jmb') {
            $this->load->view('staff_eval/staff_eval_jmb_view',$data);                
        } else if ($_SESSION['bms']['designation_id'] == 2) {
            $this->load->view('staff_eval/staff_eval_am_view',$data);      
        }  else {
           $this->load->view('staff_eval/staff_eval_hr_view',$data);       
        } 
	}
    
    function eval_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        if(!empty($_POST['eval'])) {
            $eval = $_POST['eval'];
            $eval['eval_date'] = date('Y-m-d');
            $eval['award_year'] = date('Y');
            $eval['award_month'] = date('n');
            if(!empty($eval['jmb_id']))
                $this->bms_staff_eval_model->setStaffEval($eval,'jmb');
            else if(!empty($eval['am_staff_id']))
                $this->bms_staff_eval_model->setStaffEval($eval,'am');
            else if(!empty($eval['hr_staff_id']))
                $this->bms_staff_eval_model->setStaffEval($eval,'hr');
            $_SESSION['flash_msg'] = 'Staff Evaluation submitted successfully!';        
        }
        redirect('index.php/bms_staff_eval/index?property_id='.$eval['property_id']);
    }
    
    function award () {
        $data['browser_title'] = 'Property Butler | Staff Evaluation Award';
        $data['page_header'] = '<i class="fa fa-trophy"></i> Staff Evaluation Award';
        
        $data['eval_message'] = array(1=>'Staff Evaluation can be done between 22nd and 28th of every month!');
        
        $data['eval_status'] = 0;
        
        $this->load->view('staff_eval/staff_eval_award_view',$data);
    }
    
    function award_staff () {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        header('Content-type: application/json');
        $data = array ();
        if(!empty($_POST['award_cat']) && !empty($_POST['award_year']) && !empty($_POST['award_month'])) {
            $data['awarded_staff'] = $this->bms_staff_eval_model->getAwardedStaff ($_POST['award_cat'],$_POST['award_year'],$_POST['award_month']);
            if(empty($data['awarded_staff'])) {
                //$data['award_staff'] = $this->bms_staff_eval_model->getAwardedStaff($_POST['award_cat'],$_POST['award_year'],$_POST['award_month']);                               
                $desi_ids = implode(',',$this->config->item('staff_desi_award_cat_'.$_POST['award_cat']));
                                
                $data['award_staff'] = $this->bms_staff_eval_model->getStaffForAward($desi_ids,$_POST['award_year'],$_POST['award_month']);
                if(!empty($data['award_staff'])) {
                    foreach ($data['award_staff'] as $key=>$val) {
                        if(!empty($val['tot_jmb']) && $val['tot_jmb'] != 0) {
                            $data['award_staff'][$key]['jmb_prtg'] = number_format((($val['proactive_jmb']+$val['communication_jmb']+$val['attitude_jmb']+$val['initiative_jmb']+$val['resposibility_jmb']+$val['courtesy_jmb'])/($val['tot_jmb']*30))*33.33,2);    
                        } else {
                            $data['award_staff'][$key]['jmb_prtg'] = 0;
                        }
                        $data['award_staff'][$key]['am_prtg'] =  number_format((($val['teamwork_am']+$val['guest_relation_am']+$val['attitude_am']+$val['dependability_am']+$val['resposibility_am']+$val['courtesy_am']+$val['proj_knowledge_am']+$val['billing_collec_am'])/40)*33.33,2);
                        $data['award_staff'][$key]['hr_prtg'] =  number_format((($val['punctuality_hr']+$val['attendance_hr']+$val['discipline_hr']+$val['communication_hr']+$val['attitude_hr']+$val['grooming_hr'])/30)*33.33,2);
                        $data['award_staff'][$key]['tot_prtg'] = number_format($data['award_staff'][$key]['jmb_prtg'] +$data['award_staff'][$key]['am_prtg'] +$data['award_staff'][$key]['hr_prtg'],2);
                    }                    
                    // sorting multi dimentional array
                    foreach ($data['award_staff'] as $key => $row) {
                        $percentage[$key]  = $row['tot_prtg'];                    
                    }
                    $volume  = array_column($data['award_staff'], 'tot_prtg');
                    array_multisort($percentage, SORT_DESC, $data['award_staff']);                    
                    
                    $data['message'] = 'award_staff_list';
                } else {
                   $data['message'] = 'Invalid_request'; 
                }                            
            } else {
                $data['message'] = 'staff_awarded';
            }            
        } else {
            $data['message'] = 'invalid_input';
        }
        echo json_encode($data);
    }
    
    function award_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        if(!empty($_POST['award_cat']) && !empty($_POST['award_year']) && !empty($_POST['award_month']) && !empty($_POST['award_staff'])) { 
            $awarded_staff_det = explode ('-',$_POST['award_staff']);
            $awarded_staff_id = $awarded_staff_det[0];
            $awarded_staff_property = $awarded_staff_det[1]; 
            //$data['awarded_staff'] = $this->bms_staff_eval_model->getAwardedStaff ($_POST['award_cat'],$_POST['award_year'],$_POST['award_month']);
            //if(empty($data['awarded_staff'])) {
                //$data['award_staff'] = $this->bms_staff_eval_model->getAwardedStaff($_POST['award_cat'],$_POST['award_year'],$_POST['award_month']);                               
            $desi_ids = implode(',',$this->config->item('staff_desi_award_cat_'.$_POST['award_cat']));
                            
            $data['award_staff'] = $this->bms_staff_eval_model->getStaffForAward($desi_ids,$_POST['award_year'],$_POST['award_month']);
            if(!empty($data['award_staff'])) {
                foreach ($data['award_staff'] as $key=>$val) {
                    if(!empty($val['tot_jmb']) && $val['tot_jmb'] != 0) {
                        $data['award_staff'][$key]['jmb_prtg'] = number_format((($val['proactive_jmb']+$val['communication_jmb']+$val['attitude_jmb']+$val['initiative_jmb']+$val['resposibility_jmb']+$val['courtesy_jmb'])/($val['tot_jmb']*30))*33.33,2);    
                    } else {
                        $data['award_staff'][$key]['jmb_prtg'] = 0;
                    }
                    $data['award_staff'][$key]['am_prtg'] =  number_format((($val['teamwork_am']+$val['guest_relation_am']+$val['attitude_am']+$val['dependability_am']+$val['resposibility_am']+$val['courtesy_am']+$val['proj_knowledge_am']+$val['billing_collec_am'])/40)*33.33,2);
                    $data['award_staff'][$key]['hr_prtg'] =  number_format((($val['punctuality_hr']+$val['attendance_hr']+$val['discipline_hr']+$val['communication_hr']+$val['attitude_hr']+$val['grooming_hr'])/30)*33.33,2);
                    $data['award_staff'][$key]['tot_prtg'] = number_format($data['award_staff'][$key]['jmb_prtg'] +$data['award_staff'][$key]['am_prtg'] +$data['award_staff'][$key]['hr_prtg'],2);
                }                    
                // sorting multi dimentional array
                foreach ($data['award_staff'] as $key => $row) {
                    $percentage[$key]  = $row['tot_prtg'];                    
                }
                $volume  = array_column($data['award_staff'], 'tot_prtg');
                array_multisort($percentage, SORT_DESC, $data['award_staff']); 
                
                foreach ($data['award_staff'] as $key => $row) {
                    $ins_data = array();
                    $ins_data['awarded_cat'] = $_POST['award_cat'];
                    $ins_data['property_id'] = $row['property_id'];
                    $ins_data['staff_id'] = $row['staff_id'];
                    $ins_data['award_year'] = $_POST['award_year'];
                    $ins_data['award_month'] = $_POST['award_month'];
                    $ins_data['proactive_jmb'] = $row['proactive_jmb'];
                    $ins_data['communication_jmb'] = $row['communication_jmb'];
                    $ins_data['attitude_jmb'] = $row['attitude_jmb'];
                    $ins_data['initiative_jmb'] = $row['initiative_jmb'];
                    $ins_data['resposibility_jmb'] = $row['resposibility_jmb'];
                    $ins_data['courtesy_jmb'] = $row['courtesy_jmb'];
                    $ins_data['teamwork_am'] = $row['teamwork_am'];
                    $ins_data['guest_relation_am'] = $row['guest_relation_am'];
                    $ins_data['attitude_am'] = $row['attitude_am'];
                    $ins_data['dependability_am'] = $row['dependability_am'];
                    $ins_data['resposibility_am'] = $row['resposibility_am'];
                    $ins_data['courtesy_am'] = $row['courtesy_am'];
                    $ins_data['proj_knowledge_am'] = $row['proj_knowledge_am'];
                    $ins_data['billing_collec_am'] = $row['billing_collec_am'];
                    $ins_data['punctuality_hr'] = $row['punctuality_hr'];
                    $ins_data['discipline_hr'] = $row['discipline_hr'];
                    $ins_data['attendance_hr'] = $row['attendance_hr'];
                    $ins_data['communication_hr'] = $row['communication_hr'];
                    $ins_data['attitude_hr'] = $row['attitude_hr'];
                    $ins_data['grooming_hr'] = $row['grooming_hr'];
                    $ins_data['cast_jmb'] = $row['cast_jmb'];
                    $ins_data['tot_jmb'] = $row['tot_jmb'];
                    $ins_data['jmb_percentage'] = $row['jmb_prtg'];
                    $ins_data['am_percentage'] = $row['am_prtg'];
                    $ins_data['hr_percentage'] = $row['hr_prtg'];
                    $ins_data['total_percentage'] = $row['tot_prtg'];
                    $ins_data['awarded'] = $row['staff_id'] == $awarded_staff_id && $row['property_id'] == $awarded_staff_property ? 1 : 0 ;
                    $this->bms_staff_eval_model->setAwardedStaff($ins_data);                 
                }
                //$data['message'] = 'award_staff_list';
            }                         
           $_SESSION['flash_msg'] = 'Staff Awarded successfully!';
           $qury_str = '?award_cat='.$_POST['award_cat'].'&award_year='.$_POST['award_year'].'&award_month='.$_POST['award_month'].'&auto=1';
        } else {
            $_SESSION['flash_msg'] = 'Staff Awarded Error!';
            $qury_str = '';
        }
        redirect('index.php/bms_staff_eval/award'.$qury_str);   
    }
    
    function get_staff_eval_det ($staff_id,$property_id,$ayear,$amonth) {
        //echo $staff_id."<br />".$property_id;
        $data['jmb_eval'] = $this->bms_staff_eval_model->getStaffEvalJmb ($staff_id,$property_id,$ayear,$amonth);
        $data['am_eval'] = $this->bms_staff_eval_model->getStaffEvalAm ($staff_id,$property_id,$ayear,$amonth);
        $data['hr_eval'] = $this->bms_staff_eval_model->getStaffEvalHr ($staff_id,$property_id,$ayear,$amonth);
        $this->load->view('staff_eval/staff_eval_details_view',$data);
        
    }
    
}