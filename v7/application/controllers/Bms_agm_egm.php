<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_agm_egm extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false ) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //$this->prop_name = 'Hill Villa 1 AGM 2019,'; // AGM/EGM
        //$this->agm_url = 'https://bit.ly/2Tsw80L';
        //$this->sms_prefix = 'Welcome to '. $this->prop_name;
        
        $this->load->model('bms_masters_model');
        $this->load->model('bms_agm_egm_model');         
    }    
    
    function agm_attendance () {
        $data['browser_title'] = 'Property Butler | AGM Attendance';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM Attendance';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        if (isset($data ['property_id']) && $data ['property_id'] != '') {
            $data['agms'] = $this->bms_agm_egm_model->get_agm_for_attendance ($data ['property_id']);         
        }
        if (isset($data['agm_id']) && $data['agm_id'] != '') {
            $data['units'] = $this->bms_agm_egm_model->get_eligible_voters($data['agm_id']);            
        }
        $this->load->view('agm_egm/agm_attendance_view',$data);
    }
    
    function agm_attendance_report ($type = '') {
        //echo "<br />".md5('123456');exit; // 
        //$result = file('https://api.silverstreet.com/send.php?username=armada&password=yavTOhy8&destination=60102345717,60146495993,60173051009&sender=PropertyBTL&body=Welcome to EGM, your one time user name is s3l0m4 and password is 123456. pls open the link https://bit.ly/313SqIP for cast your vote.');
        //echo "<pre>";print_r($result);echo "</pre>"; exit;
        $data['browser_title'] = 'Property Butler | AGM/EGM Attendance';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM/EGM <i class="fa fa-angle-double-right"></i> Attendance';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        if(!empty($data ['property_id'])) {
            $data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
            if(!empty($data['agm_id'])) {
                $data['units'] = $this->bms_agm_egm_model->get_attendance_report ($data['agm_id']);
                if($type == 'print') {
                    $data['no_of_ev'] = $this->bms_agm_egm_model->get_elibible_voters_cnt ($data['agm_id']);
                    $data['no_of_attendees'] = $this->bms_agm_egm_model->get_attendees_cnt ($data['agm_id']);
                }
            }                
        }
        //echo "<pre>";print_r($data['units']);echo "</pre>";
        if($type == 'print') {
            $this->load->view('agm_egm/agm_attend_report_print',$data);   
        } else {
            $this->load->view('agm_egm/agm_attend_report_view',$data);            
        }        
    }
    
    function getTodaysAgm () {
        
        header('Content-type: application/json');
        $agms = array();
        if (isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $agms = $this->bms_agm_egm_model->get_agm_for_attendance ($_POST['property_id']);            
        }       
        echo json_encode($agms);        
    }
    
    function getAgmEligibleVoters () {
        
        header('Content-type: application/json');
        $agms = array();
        if (isset($_POST['agm_id']) && $_POST['agm_id'] != '') {
            $agms = $this->bms_agm_egm_model->get_eligible_voters($_POST['agm_id']);            
        }       
        echo json_encode($agms);        
    }
    
    function atten_capture () {
        $data['upload_err'] = array();
        $agm_atten_upload = $this->config->item('agm_atten_upload');
        $attend['agm_id'] = $_GET['agm_id'];
        $agm_created_date = $_GET['agm_created_date'];
        
        $agm_atten_upload['upload_path'] = $agm_atten_upload['upload_path'].date('Y',strtotime($agm_created_date)).'/'; 
        //echo $agm_atten_upload['upload_path'];
        //echo is_dir($agm_atten_upload['upload_path']) ? ' directory exist' : 'directory not exist'; 
        if(!is_dir($agm_atten_upload['upload_path']))
            @mkdir($agm_atten_upload['upload_path'], 0777);
            
        $agm_atten_upload['upload_path'] = $agm_atten_upload['upload_path'].date('m',strtotime($agm_created_date)).'/'; 
        if(!is_dir($agm_atten_upload['upload_path']))
            @mkdir($agm_atten_upload['upload_path'], 0777);
        
        $agm_atten_upload['upload_path'] = $agm_atten_upload['upload_path'].date('Y-m-d',strtotime($agm_created_date)).'/'; 
        if(!is_dir($agm_atten_upload['upload_path']))
            @mkdir($agm_atten_upload['upload_path'], 0777);
            
        $agm_atten_upload['upload_path'] = $agm_atten_upload['upload_path'].$attend['agm_id'].'/'; 
        if(!is_dir($agm_atten_upload['upload_path']))
            @mkdir($agm_atten_upload['upload_path'], 0777);            
        
        $agm_atten_upload['file_name'] = date('His');
        $this->load->library('upload',$agm_atten_upload);
        //echo "<pre>";print_r($task_file_upload);exit;
        if ( ! $this->upload->do_upload('webcam')) {            
            array_push($data['upload_err'],$this->upload->display_errors());                        
        }
        if(empty($data['upload_err'])){
            $attend['image_name'] = $this->upload->data('file_name');
            $attend['agm_id'] = $_GET['agm_id'];
            $eli_voter_ids = trim($_GET['eli_voter_id']);
            $attend['mobile_no'] = trim($_GET['mobile_no']);
            $attend['atten_date'] = date("Y-m-d");
            $attend['atten_time'] = date("H:i:s");
            
            $attend['password'] = '123456';
            $attend['changed_pass'] = 0;
            if(!empty($_GET['mobile_no'])) {
                $mobile_no = trim($_GET['mobile_no']);
                $first_char = substr($mobile_no,0,1);
                $attend['mobile_no'] = $first_char == '6' ? $mobile_no : '6'.$mobile_no;
                
                
                /*$body = $this->sms_prefix.' your one time username is '.$attend['user_name'].' and password is 123456. Pls click the link '.$this->agm_url.' to login.';
                
                $url = 'https://api.silverstreet.com/send.php?username=armada&password=yavTOhy8&destination='.$attend['mobile_no'].'&sender=PropertyBTL&body='.$body; 
                
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                $contents = curl_exec($ch);
                curl_close($ch);*/
            }
            $attend['user_name'] = $this->generate_username();
            $attend['created_date'] = date("Y-m-d H:i:s");        
            $attend['created_by'] = isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '';
            $insert_id = $this->bms_agm_egm_model->set_agm_attendance($attend);
            if(!empty($eli_voter_ids)) {
                $eli_voter_ids_arr = explode(',',$eli_voter_ids);
                $ins_arr = array();
                foreach($eli_voter_ids_arr as $key=>$val) {
                    $ins_arr[$key]['agm_id'] = $attend['agm_id'];
                    $ins_arr[$key]['agm_attendance_id'] = $insert_id;
                    $ins_arr[$key]['eli_voter_id'] = $val;
                }
                $this->bms_agm_egm_model->set_agm_atten_eligi_mapp($ins_arr);
            }
            //$attendance_capture_for = $this->config->item('attendance_capture_for');
            echo $attend['user_name'];
            //$_SESSION['flash_msg'] = 'Document has been Uploaded Successfully!';            
        } else {
            echo 'error~~~'.'Document upload Error Message: '.$this->upload->display_errors() . $agm_atten_upload['upload_path'];
            //$_SESSION['error_msg'] = 'Document upload Error Message: '.$this->upload->display_errors(); 
            
        }
    }
    
    function agm_username_print () {
        $data['atten_info'] = $this->bms_agm_egm_model->get_username_print($_GET['user_name']);
        $this->load->view('agm_egm/agm_username_print',$data);
    }
    
    function generate_username($len = 6) {
        //$data = '1234567890abcdefghijklmnopqrstuvwxyz';
        $alpha = 'abcdefghijklmnopqrstuvwxyz';
        $numeric = '0123456789';
        $username= substr(str_shuffle($alpha), 0, 4).substr(str_shuffle($numeric), 0, 2);
        if($this->bms_agm_egm_model->check_agm_username_existence($username)) {
            $this->generate_username();
        } else {
            return  $username;    
        }        
    }
    
    function generate_pin() { 
        //$data = '1234567890abcdefghijklmnopqrstuvwxyz';
        $pin = rand(1000,9999);
        if($this->bms_agm_egm_model->check_agenda_pin_existence($pin)) {
            $this->generate_pin();
        } else {
            return  $pin;    
        }        
    }
    
    function reSendSms () {
        if (isset($_POST['username']) && $_POST['username'] != '') {
            $username = trim($_POST['username']);
            $result = $this->bms_agm_egm_model->agm_voter_by_username($username);
            if(count($result) == 1) {
                $data['password'] = '123456';
                $data['changed_pass'] = 0;
                $this->bms_agm_egm_model->agm_update_vote_pass($data,$username);
                /*$agm_url = 'https://bit.ly/2Tsw80L';
                $body = $this->sms_prefix.' your one time username is '.$username.' and password is 123456. Pls click the link '.$this->agm_url.' to login.';
                //$result = file('https://api.silverstreet.com/send.php?username=armada&password=yavTOhy8&destination='.$result[0]['mobile_no'].'&sender=PropertyBTL&body='.$body);
                $url = 'https://api.silverstreet.com/send.php?username=armada&password=yavTOhy8&destination='.$result[0]['mobile_no'].'&sender=PropertyBTL&body='.$body;
                
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                $contents = curl_exec($ch);
                curl_close($ch);*/
                
                echo true;
            } else {
                echo false;
            }
            
        } else {
            echo false;
        }
    }
    
    function agm_master () {
        $data['browser_title'] = 'Property Butler | AGM Master';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM Master';
        $data['agm_types'] = $this->config->item('agm_types');
        //echo $_GET['agm_type'];
        if (!empty($_GET['agm_type']) && in_array ($_GET['agm_type'],array_keys($data['agm_types'])) ) {
            $data['check_list'] = $this->bms_agm_egm_model->getAgmMasterChkList($_GET['agm_type']);
            if(!empty($data['check_list'])) {
                foreach ($data['check_list'] as $key=>$val) {
                    $data['chk_list_reminder'][$key] = $this->bms_agm_egm_model->getAgmMasterChkListRemin ($val['agm_master_id']);
                }
            }
            $data['designations'] = $this->bms_agm_egm_model->getDesignations ();
        }
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('agm_egm/agm_master_view',$data);
    }
    
    function agm_master_save () {       
        
        $agm  = $this->input->post('agm');
        $agm_reminder  = $this->input->post('agm_reminder');
        if(!empty($agm)) {
            $agm_master_ids = array_filter(array_values($agm['agm_master_id']));
            //echo "<pre>"; print_r($agm['agm_master_id']); print_r($_POST);echo "</pre>";   exit;
            if(!empty($agm_master_ids)) {
                $toBeDeleteAgm = $this->bms_agm_egm_model->getDeleteAgm ($agm['agm_type'],$agm_master_ids);
                if(!empty($toBeDeleteAgm)) {
                    foreach ($toBeDeleteAgm as $key=>$val) {
                        $toBeDeleteAgmMasterIds[] = $val['agm_master_id'];
                    }
                    if(!empty($toBeDeleteAgmMasterIds))  {
                        $this->bms_agm_egm_model->deleteAgmReminder ($toBeDeleteAgmMasterIds);
                        $this->bms_agm_egm_model->deleteAgmMaster ($toBeDeleteAgmMasterIds);
                    }
                }
            }
            foreach ($agm['agm_master_id'] as $key=>$val) {
                
                $update_data['agm_type'] = $agm['agm_type'];
                $update_data['agm_descrip'] = $agm['agm_descrip'][$key];
                $update_data['agm_responsibility'] = $agm['agm_responsibility'][$key];
                
                if(empty($val)) {
                    // insert process
                    $agm_master_id = $this->bms_agm_egm_model->insertAgmMaster ($update_data);
                } else {
                    // update process
                    $agm_master_id = $val;
                    $this->bms_agm_egm_model->updateAgmMaster ($update_data,$val);
                }    
                    
                if(!empty($agm_reminder[$key]['agm_master_reminder_id'])) {
                    $agm_reminder_ids = array_filter(array_values($agm_reminder[$key]['agm_master_reminder_id']));
                    if(!empty($agm_reminder_ids))
                        $this->bms_agm_egm_model->deleteAgmReminderById ($agm_master_id,$agm_reminder_ids);
                }
                if(!empty($agm_reminder[$key]['remind_before'])) {
                    foreach ($agm_reminder[$key]['remind_before'] as $key2=>$val2) {
                        $update_data2['agm_master_id'] = $agm_master_id;
                        $update_data2['remind_before'] = $agm_reminder[$key]['remind_before'][$key2];
                        $update_data2['email_content'] = !empty($agm_reminder[$key]['email_content'][$key2]) ? $agm_reminder[$key]['email_content'][$key2] : '-';
                        $update_data2['email_staff'] = !empty($agm_reminder[$key]['email_staff'][$key2]) ? $agm_reminder[$key]['email_staff'][$key2] : 0;
                        $update_data2['email_jmb'] = !empty($agm_reminder[$key]['email_jmb'][$key2]) ? $agm_reminder[$key]['email_jmb'][$key2] : 0;
                        if(!empty($agm_reminder[$key]['agm_master_reminder_id'][$key2])) {
                            $agm_master_reminder_id = $agm_reminder[$key]['agm_master_reminder_id'][$key2];                                
                            $this->bms_agm_egm_model->updateAgmReminder ($update_data2,$agm_master_reminder_id);
                        } else {
                            $this->bms_agm_egm_model->insertAgmReminder ($update_data2);
                        }
                    }
                }
            }
            $_SESSION['flash_msg'] = 'AGM Master Saved successfully!';            
        }
        redirect('index.php/bms_agm_egm/agm_master?agm_type='.$agm['agm_type']);
    }
    
    
    function agm_list ($property_id = '',$agm_type = '') {
        $data['browser_title'] = 'Property Butler | AGM Checklist';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM Checklist';
        
        $data['agm_types'] = $this->config->item('agm_types');
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($property_id) ? $property_id : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data ['agm_type'] = !empty($agm_type) ? $agm_type : '';
        
        $data['agm_list'] = $this->bms_agm_egm_model->getAgmList($data ['property_id'],$data ['agm_type']);
        
        $this->load->view('agm_egm/agm_list_view',$data);
    }
    
    function agm_details ($agm_id) {
        //echo $agm_id;
        $data['agm_types'] = $this->config->item('agm_types');
        $data['agm_main'] = $this->bms_agm_egm_model->getAgmMainWithPropName($agm_id);
        $data['check_list'] = $this->bms_agm_egm_model->getAgmChkListDesignation($agm_id);
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('agm_egm/agm_update_view',$data);
    }
    
    function checklist_status_update () {
        //print_r($_POST);
        if(!empty($_POST)) {
            foreach ($_POST as $key=>$val) {
                //$data['agm_checklist_id'] = $key;
                $data['agm_checklist_status'] = 1;
                $data['agm_checklist_remarks'] = trim($val);                
                $this->bms_agm_egm_model->update_checklist_status ($data,$key);
            }
            echo 1;
        } else {
            echo 0;
        }
    }
    
    function add_agm ($property_id='',$agm_type='',$mode='',$agm_id='') {
        $data['browser_title'] = 'Property Butler | AGM Checklist';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> Add AGM Checklist';
        $data['agm_types'] = $this->config->item('agm_types');
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if (!empty($agm_type) && in_array ($agm_type,array_keys($data['agm_types'])) && !empty($property_id) ) {
            
            if($mode == 'edit' && !empty ($agm_id)) {
                $data['agm_main'] = $this->bms_agm_egm_model->getAgmMain($agm_id);
                $data['check_list'] = $this->bms_agm_egm_model->getAgmChkList($agm_id);
                if(!empty($data['check_list'])) {
                    foreach ($data['check_list'] as $key=>$val) {
                        $data['chk_list_reminder'][$key] = $this->bms_agm_egm_model->getAgmChkListRemin ($val['agm_checklist_id']);
                    }
                }
                //echo "<pre>";print_r($data);echo "</pre>"; exit;
            } else {
                $data['check_list'] = $this->bms_agm_egm_model->getAgmMasterChkList($agm_type);
                if(!empty($data['check_list'])) {
                    foreach ($data['check_list'] as $key=>$val) {
                        $data['chk_list_reminder'][$key] = $this->bms_agm_egm_model->getAgmMasterChkListRemin ($val['agm_master_id']);
                    }
                }
            }            
            $data['designations'] = $this->bms_masters_model->getAssignTo ($property_id);
        }
        
        $data['agm_type'] = $agm_type;
        $data['property_id'] = !empty($property_id) ? $property_id : (!empty($_SESSION['bms_default_property']) ? $_SESSION['bms_default_property'] : '');
        $data['mode'] = $mode;
        $data['agm_id'] = $agm_id;
        $this->load->view('agm_egm/add_agm_view',$data);
    }
    
    function add_agm_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        $agm_main  = $this->input->post('agm_main');
        $agm  = $this->input->post('agm');
        $agm_reminder  = $this->input->post('agm_reminder');
        $agm_id = '';
        
        if(!empty($agm_main)) {
            $agm_main['agm_last_date'] = date('Y-m-d',strtotime($agm_main['agm_last_date']));
            $agm_main['agm_date'] = !empty($agm_main['agm_date']) ? date('Y-m-d',strtotime($agm_main['agm_date'])) : '';
            $type = 'add';
            
            $agm_types = $this->config->item('agm_types');
            $str = !empty($agm_main['agm_type']) ? $agm_types[$agm_main['agm_type']] : 'AGM';
            
            $min_date_y = date('Y', strtotime("-1 Day",strtotime("+12 months", strtotime($agm_main['agm_last_date']))));
            $str .= ' '.$min_date_y;
            if(!empty($agm_main['agm_date']) && $agm_main['agm_date'] != '0000-00-00') {
                $agm_date_y = date('Y', strtotime($agm_main['agm_date']));
                $str .= $agm_date_y != $min_date_y ? '/'.$agm_date_y : '';
            }
            $agm_main['agm_term'] = $str;
            if(!empty($agm_main['agm_id'])) {
                $type = 'edit'; 
                $agm_id = $agm_main['agm_id'];
                $this->bms_agm_egm_model->updateAgm($agm_main,$agm_id);
            } else {
                $agm_id = $this->bms_agm_egm_model->insertAgm($agm_main);
            }
            
            if(!empty($agm) && !empty($agm_id)) {
                $agm_master_ids = array_filter(array_values($agm['agm_checklist_id']));
                //echo "<pre>"; print_r($agm['agm_master_id']); print_r($_POST);echo "</pre>";   exit;
                if(!empty($agm_master_ids)) {
                    $toBeDeleteAgm = $this->bms_agm_egm_model->getDeleteAgmChecklist ($agm_id,$agm_master_ids);
                    if(!empty($toBeDeleteAgm)) {
                        foreach ($toBeDeleteAgm as $key=>$val) {
                            $toBeDeleteAgmMasterIds[] = $val['agm_checklist_id'];
                        }
                        if(!empty($toBeDeleteAgmMasterIds))  {
                            $this->bms_agm_egm_model->deleteAgmChecklistReminder ($toBeDeleteAgmMasterIds);
                            $this->bms_agm_egm_model->deleteAgmChecklist ($toBeDeleteAgmMasterIds);
                        }
                    }
                }
                foreach ($agm['agm_checklist_id'] as $key=>$val) {
                    
                    $update_data['agm_id'] = $agm_id;
                    $update_data['agm_descrip'] = $agm['agm_descrip'][$key];
                    $update_data['agm_responsibility'] = $agm['agm_responsibility'][$key];
                    $agm_checklist_id = '';
                    if(empty($val)) {
                        // insert process
                        $agm_checklist_id = $this->bms_agm_egm_model->insertAgmCheckList ($update_data);
                    } else {
                        // update process
                        $agm_checklist_id = $val;
                        $this->bms_agm_egm_model->updateAgmChecklist ($update_data,$val);
                    }    
                        
                    if(!empty($agm_reminder[$key]['agm_checklist_reminder_id'])) {
                        $agm_reminder_ids = array_filter(array_values($agm_reminder[$key]['agm_checklist_reminder_id']));
                        if(!empty($agm_reminder_ids))
                            $this->bms_agm_egm_model->deleteAgmChklistReminderById ($agm_checklist_id,$agm_reminder_ids);
                    }
                    if(!empty($agm_reminder[$key]['remind_before'])) {
                        foreach ($agm_reminder[$key]['remind_before'] as $key2=>$val2) {
                            $update_data2['agm_checklist_id'] = $agm_checklist_id;
                            $update_data2['remind_before'] = $agm_reminder[$key]['remind_before'][$key2];
                            $update_data2['email_content'] = !empty($agm_reminder[$key]['email_content'][$key2]) ? $agm_reminder[$key]['email_content'][$key2] : '-';
                            $update_data2['email_staff'] = !empty($agm_reminder[$key]['email_staff'][$key2]) ? $agm_reminder[$key]['email_staff'][$key2] : 0;
                            $update_data2['email_jmb'] = !empty($agm_reminder[$key]['email_jmb'][$key2]) ? $agm_reminder[$key]['email_jmb'][$key2] : 0;
                            if(!empty($agm_reminder[$key]['agm_checklist_reminder_id'][$key2])) {
                                $agm_checklist_reminder_id = $agm_reminder[$key]['agm_checklist_reminder_id'][$key2];                                
                                $this->bms_agm_egm_model->updateAgmChecklistReminder ($update_data2,$agm_checklist_reminder_id);
                            } else {
                                $this->bms_agm_egm_model->insertAgmChecklistReminder ($update_data2);
                            }
                        }
                    }
                }                        
            }
            $_SESSION['flash_msg'] = 'AGM '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
            redirect ('index.php/bms_agm_egm/agm_list/'.$agm_main['property_id'].'/'.$agm_main['agm_type']);
        }
        redirect ('index.php/bms_agm_egm/agm_list/');
    }
    
    function getMinMaxDate ($last_date) {
        //echo $last_date;
        $min_date = date('d-m-Y', strtotime("-1 Day",strtotime("+12 months", strtotime($last_date))));
        $max_date = date('d-m-Y', strtotime("-1 Day",strtotime("+15 months", strtotime($last_date))));
        echo $min_date.'~~~'.$max_date;
    }
    
    function eligible_voters ($offset = 0,$rows = 25) {
        $data['browser_title'] = 'Property Butler | AGM Eligible Voters';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM Eligible Voters';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        
        $data['offset'] = $offset;
        $data['rows'] = $rows;
        if(!empty($data ['property_id'])) {
            $data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
            if(!empty($data['agm_id']))
                $data['units'] = $this->bms_agm_egm_model->get_units ($data ['property_id'],$data['agm_id'],$offset,$rows);
        }
        //echo "<pre>";print_r($data['units']);echo "</pre>";
        $this->load->view('agm_egm/agm_eligible_voters_view',$data);
    }
    
    function eligible_voters_report () {
        $data['browser_title'] = 'Property Butler | AGM Eligible Voters';
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        
        if(!empty($data ['property_id']) && !empty($data['agm_id'])) {
            $data['units'] = $this->bms_agm_egm_model->get_eligible_voters_report ($data['agm_id']);
        }
        //echo "<pre>";print_r($data['units']);echo "</pre>";
        $this->load->view('agm_egm/agm_eli_vot_report_print',$data);
    }
    
    function set_eligible_voters () {
        
        //echo "<pre>";print_r($_POST); echo "</pre>";exit; 
        $agm_id = $this->input->post('agm_id');
        $eli_voter_id = $this->input->post('eli_voter_id');
        $eli_voter_unit_id = $this->input->post('eligible');
        $loaded_units = $this->input->post('loaded_units');
        $owner_name = $this->input->post('owner_name');
        $unit_no = $this->input->post('unit_no');
        $ic_no = $this->input->post('ic_no');
        $unit_type = $this->input->post('unit_type');
        $share_unit = $this->input->post('share_unit');
        $no_of_owners = $this->input->post('no_of_owners');
        $proxy_req = $this->input->post('proxy_req');
        $proxy_name = $this->input->post('proxy_name');
        $proxy_nric = $this->input->post('proxy_nric');
        $diff = array_diff($loaded_units,$eli_voter_unit_id);
        //echo "<pre>";print_r($diff);print_r($_POST); echo "</pre>";exit; 
        if(!empty($diff))
            $this->bms_agm_egm_model->deleteEligibleVoters ($agm_id,$diff);  
        
        foreach ($eli_voter_unit_id as $key=>$val) {
            $data['unit_id'] = $val;
            $data['agm_id'] = $agm_id;
            $data['owner_name'] = isset($owner_name[$key]) ? $owner_name[$key] : '';
            $data['unit_no'] = isset($unit_no[$key]) ? $unit_no[$key] : '';
            $data['ic_no'] = isset($ic_no[$key]) ? $ic_no[$key] : '';
            $data['unit_type'] = isset($unit_type[$key]) ? $unit_type[$key] : '';
            $data['share_unit'] = isset($share_unit[$key]) ? $share_unit[$key] : '';
            $data['no_of_owners'] = isset($no_of_owners[$key]) ? $no_of_owners[$key] : '';
            $data['proxy_required'] = isset($proxy_req[$key]) ? $proxy_req[$key] : 0;
            $data['proxy_name'] = isset($proxy_name[$key]) ? $proxy_name[$key] : '';
            $data['proxy_ic_no'] = isset($proxy_nric[$key]) ? $proxy_nric[$key] : '';
            if(!empty($eli_voter_id[$key])) {
                $data['updated_by'] = $_SESSION['bms']['staff_id'];
                $data['updated_date'] = date("Y-m-d");
                $this->bms_agm_egm_model->updateEligibleVoters ($data,$eli_voter_id[$key]);      
            } else {
                $data['created_by'] = $_SESSION['bms']['staff_id'];
                $data['created_date'] = date("Y-m-d");
                $this->bms_agm_egm_model->insertEligibleVoters ($data);
            }            
        }                      
        $_SESSION['flash_msg'] = 'Eligible Voters updated successfully!';
        $sub_url = ''; 
        if(!empty($_POST['act_type'])) {
            switch($_POST['act_type']) {
                
                case 'save_pre': $sub_url = $_POST['offset']-$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save_nxt': $sub_url = $_POST['offset']+$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save': 
                default:
                    $sub_url = $_POST['offset'].'/'.$_POST['rows']; break;
            }
        }
        redirect ('index.php/bms_agm_egm/eligible_voters/'.$sub_url.'?property_id='.$_POST['property_id'].'&agm_id='.$_POST['agm_id']);
    }
    
    function agm_agenda () {
        $data['browser_title'] = 'Property Butler | AGM Agenda';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM Agenda';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        if(!empty($data ['property_id'])) {
            $data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
            if (!empty($data['agm_id']))
                $data['agendas'] = $this->bms_agm_egm_model->getAgenda ($data['agm_id']);
        }
        //echo "<pre>";print_r($data['agendas']);echo "</pre>";
        $this->load->view('agm_egm/agm_agenda_view',$data);
    }
    
    function set_agm_agenda () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $agm_id = $this->input->post('agm_id');
        $agm = $this->input->post('agm');
        if(!empty($agm_id) && !empty($agm)) {
            $agm_agenda_ids = array_filter(array_values($agm['agm_agenda_id']));
            $this->bms_agm_egm_model->unsetAgenda ($agm_agenda_ids,$agm_id);
            foreach ($agm['agenda_resol'] as $key=>$val) {
                if(!empty($val)) {
                    $data['agm_id'] = $agm_id;
                    $data['agenda_resol'] = nl2br ($agm['agenda_resol'][$key]);
                    $data['seq_no'] = $agm['seq_no'][$key];
                    $data['resolu_type'] = !empty($agm['resolu_type'][$key]) ? $agm['resolu_type'][$key] : NULL ;
                    if(!empty($agm['agm_agenda_id'][$key])) {
                        $this->bms_agm_egm_model->updateAgenda($data,$agm['agm_agenda_id'][$key]);
                    } else {
                        $this->bms_agm_egm_model->insertAgenda($data);
                    }
                }
            }
        }
        $_SESSION['flash_msg'] = 'Agenda saved successfully!';
        redirect ('index.php/bms_agm_egm/agm_agenda?property_id='.$_POST['property_id'].'&agm_id='.$_POST['agm_id']);
    }
    
    function agm_process () {
        $data['browser_title'] = 'Property Butler | AGM Voting';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM Voting';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        if(!empty($data ['property_id'])) {
            $data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
            if(!empty($data['agm_id'])) {
                $data['no_of_ev'] = $this->bms_agm_egm_model->get_elibible_voters_cnt ($data['agm_id']);
                $data['no_of_attendees'] = $this->bms_agm_egm_model->get_attendees_cnt ($data['agm_id']);
                $vote_by = $no_of_committee = '';
                foreach ($data['agms'] as $key=>$val) { 
                    if($data['agm_id'] == $val['agm_id']) {
                        $vote_by = $val['vote_by'];
                        $no_of_committee = $val['no_of_committee'];                        
                    }
                }
                
                
                $data['agm_details'] = $this->bms_agm_egm_model->getAgenda ($data['agm_id']);
                $data['units'] = $this->bms_agm_egm_model->get_attendees ($data['agm_id']); 
                
                if(!empty($data['agm_details'])) {
                    foreach($data['agm_details'] as $key=>$val) {
                        switch ($val['resolu_type']) {
                            case 1:
                                $data['pc_nominee'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_full_nominees ($val['agm_agenda_id']);
                                $pc_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_pc_result ($val['agm_agenda_id'],$vote_by);          
                                    
                                $data['pc_result'][$val['agm_agenda_id']] = '';
                                if(!empty($val['agenda_status']) && !empty($pc_res[$val['agm_agenda_id']])) {
                                    $data['pc_result'][$val['agm_agenda_id']] = '';
                                    if(!empty($val['start_time']) && !empty($val['end_time'])) {
                                        $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding">
                                                    <label>Vote Start Time:</label>'.date(' h:i:s a',strtotime($val['start_time'])).'<br />
                                                    <label>Vote Close Time:</label>'.date(' h:i:s a',strtotime($val['end_time'])).'
                                                    </div>';
                                    }
                                    
                                    $tot_votes = array_sum(array_column($pc_res[$val['agm_agenda_id']],'vote_cnt'));
                                    foreach ($pc_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                        $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-8">'.$val2['nom_unit_no']. ' - ' .( $val2['proxy_required'] == 1 ? $val2['proxy_name'] ."(Proxy)" : $val2['nom_owner_name']).'</div>
                                        <div class="col-md-2"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['unit_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$val2['vote_cnt'].'</a></div>
                                        <div class="col-md-2 text-center"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['unit_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</a></div>
                                        </div>';
                                    }
                                    // abstains count
                                    $pc_abstains = $this->bms_agm_egm_model->get_pc_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                    //echo "<pre>";print_r($pc_abstains);exit;
                                    if(!empty($pc_abstains)) {
                                        $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-8">Abstain</div>
                                        <div class="col-md-2"><a href="javascript:;" class="result_details" data-unit-id="a_'.$data['agm_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.(!empty($pc_abstains['vote_cnt']) ? $pc_abstains['vote_cnt'] : 0).'</a></div>                                        
                                        </div>';
                                    }
                                    $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">
                                    <button type="button" class="btn btn-danger re_voting_btn" data-agenda-id="'.$val['agm_agenda_id'].'">Re-Vote</button>
                                    </div>';
                                    
                                }
                            break;
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                                $vote_resol_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_vote_resol_result ($val['agm_agenda_id'],$vote_by);
                                if(!empty($val['agenda_status']) && !empty($vote_resol_res[$val['agm_agenda_id']])) {
                                    $data['vote_resol_result'][$val['agm_agenda_id']] = '';
                                    if(!empty($val['start_time']) && !empty($val['end_time'])) {
                                        $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding">
                                                    <label>Vote Start Time:</label>'.date(' h:i:s a',strtotime($val['start_time'])).'<br />
                                                    <label>Vote Close Time:</label>'.date(' h:i:s a',strtotime($val['end_time'])).'
                                                    </div>';
                                    }
                                    $tot_votes = array_sum(array_column($vote_resol_res[$val['agm_agenda_id']],'vote_cnt'));
                                    foreach ($vote_resol_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                        $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-6">'.($val2['vote_for'] == 1 ? 'FOR' : 'AGAINST').'</div>
                                        <div class="col-md-3"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['vote_for'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$val2['vote_cnt'].'</a></div>
                                        <div class="col-md-3 text-center"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['vote_for'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</a></div>
                                        </div>';
                                    }
                                    
                                    $vote_resol_abstains = $this->bms_agm_egm_model->get_vote_resol_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                    if(!empty($vote_resol_abstains)) {
                                        $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-6">Abstain</div>
                                        <div class="col-md-3"><a href="javascript:;" class="result_details" data-unit-id="a_'.$data['agm_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$vote_resol_abstains['vote_cnt'].'</a></div>                                        
                                        </div>';
                                    }
                                    
                                    $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">
                                    <button type="button" class="btn btn-danger re_voting_btn" data-agenda-id="'.$val['agm_agenda_id'].'">Re-Vote</button>
                                    </div>';
                                } 
                                break;
                            case 6:                            
                                $data['ps_res'][$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_ps_result ($val['agm_agenda_id']);  
                                break;
                            case 7:
                                $data['no_of_comm'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_no_of_comm_full_nomination ($val['agm_agenda_id']);
                                $no_of_comm_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_no_of_comm_result ($val['agm_agenda_id'],$vote_by);  
                                $data['no_of_comm_result'][$val['agm_agenda_id']] = '';
                                if(!empty($val['agenda_status']) && !empty($no_of_comm_res[$val['agm_agenda_id']])) {
                                    $data['no_of_comm_result'][$val['agm_agenda_id']] = '';
                                    if(!empty($val['start_time']) && !empty($val['end_time'])) {
                                        $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding">
                                                    <label>Vote Start Time:</label>'.date(' h:i:s a',strtotime($val['start_time'])).'<br />
                                                    <label>Vote Close Time:</label>'.date(' h:i:s a',strtotime($val['end_time'])).'
                                                    </div>';
                                    }
                                    $tot_votes = array_sum(array_column($no_of_comm_res[$val['agm_agenda_id']],'vote_cnt'));
                                    foreach ($no_of_comm_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                        $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-6">'.$val2['item'].'</div>
                                        <div class="col-md-3"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['item'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$val2['vote_cnt'].'</a></div>
                                        <div class="col-md-3 text-center"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['item'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</a></div>
                                        </div>';
                                    }
                                    
                                    $no_of_comm_abstains = $this->bms_agm_egm_model->get_no_of_comm_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                    if(!empty($no_of_comm_abstains)) {
                                        $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-6">Abstain</div>
                                        <div class="col-md-3"><a href="javascript:;" class="result_details" data-unit-id="a_'.$data['agm_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$no_of_comm_abstains['vote_cnt'].'</a></div>                                        
                                        </div>';
                                    }
                                    
                                    $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">
                                    <button type="button" class="btn btn-danger re_voting_btn" data-agenda-id="'.$val['agm_agenda_id'].'">Re-Vote</button>
                                    </div>';
                                }
                                break;
                            case 8:
                                $data['eli_mc_units'] = $this->bms_agm_egm_model->get_eli_mc_nomin ($data['agm_id']);
                                $data['mc_nominee'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_mc_full_nominees ($val['agm_agenda_id']);
                                $mc_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_mc_result ($val['agm_agenda_id'],$vote_by);

                                $data['mc_result'][$val['agm_agenda_id']] = '';
                                if(!empty($val['agenda_status']) && !empty($mc_res[$val['agm_agenda_id']])) {
                                    $data['mc_result'][$val['agm_agenda_id']] = '';
                                    if(!empty($val['start_time']) && !empty($val['end_time'])) {
                                        $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding">
                                                    <label>Vote Start Time:</label>'.date(' h:i:s a',strtotime($val['start_time'])).'<br />
                                                    <label>Vote Close Time:</label>'.date(' h:i:s a',strtotime($val['end_time'])).'
                                                    </div>';
                                    }
                                    //$tot_votes = array_sum(array_column($mc_res[$val['agm_agenda_id']],'vote_cnt'));
                                    foreach ($mc_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                        $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-8">'.$val2['nom_unit_no']. ' - '  .( $val2['proxy_required'] == 1 ? $val2['proxy_name'] ."(Proxy)" : $val2['nom_owner_name']).'</div>
                                        <div class="col-md-2"><a href="javascript:;" class="result_details" data-unit-id="'.$val2['unit_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.$val2['vote_cnt'].'</a></div>                                        
                                        </div>';
                                    }
                                    
                                    $mc_abstains = $this->bms_agm_egm_model->get_mc_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                    if(!empty($mc_abstains)) {
                                        $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding" style="padding-top:5px !important;">
                                        <div class="col-md-8">Abstain</div>
                                        <div class="col-md-2"><a href="javascript:;" class="result_details" data-unit-id="a_'.$data['agm_id'].'" data-agenda-id="'.$val['agm_agenda_id'].'" data-resol-type="'.$val['resolu_type'].'" data-vote-by="'.$vote_by.'">'.(!empty($mc_abstains['vote_cnt']) ? $mc_abstains['vote_cnt'] : 0).'</a></div>                                        
                                        </div>';
                                    }
                                    
                                    $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">
                                    <button type="button" class="btn btn-danger re_voting_btn" data-agenda-id="'.$val['agm_agenda_id'].'">Re-Vote</button>
                                    </div>';
                                }
                                break;
                        } 
                    }
                }               
            }                
        }
        //echo "<pre>";print_r($data['pc_nominee']);echo "</pre>";
        $this->load->view('agm_egm/agm_process_view',$data);
    }
    
    function set_agm_process () {
        
        // echo "<pre>";print_r($_POST);echo "</pre>";exit;
        $agm_id = $this->input->post('agm_id');
        $vote_by = $this->input->post('vote_by');
        if(!empty($vote_by)) {
            $this->bms_agm_egm_model->set_vote_by ($agm_id,$vote_by);
        }
        $agenda_id = $this->input->post('agenda_id');
        $resolu_type = $this->input->post('resolu_type');
        $agenda_min = $this->input->post('agenda_min');
        $naminee = $this->input->post('naminee');
        $proposer = $this->input->post('proposer');
        $seconder = $this->input->post('seconder');
        
        $start_vote = $this->input->post('start_vote');
        $close_vote = $this->input->post('close_vote');
        $re_vote = $this->input->post('re_vote');
        
        if(!empty($agenda_id) && empty($re_vote)) {
            foreach($agenda_id as $key=>$val) {
                $data = array();
                
                if(!empty($agenda_min[$key])) {
                    $data['minutes'] = nl2br($agenda_min[$key]);
                }
                
                if(!empty($start_vote)  && $start_vote == $val) {
                    $data['pin'] = $this->generate_pin();
                    $data['start_time'] = date("H:i:s");
                }
                
                if(!empty($close_vote)  && $close_vote == $val) { 
                    $data['pin'] = '';
                    $data['agenda_status'] = 1;
                    $data['end_time'] = date("H:i:s");
                }
                
                switch ($resolu_type[$key]) {
                    case 1:
                        //echo "<pre>";print_r($data);echo "</pre>";exit;
                        if(!empty($naminee[$val])) {
                            $pc_id = $this->input->post('pc_id');
                            $data_ins['agm_id'] = $agm_id;
                            $data_ins['agenda_id'] = $val;
                            foreach($naminee[$val] as $key2=>$val2) {
                                $data_ins['nominee'] = isset($val2) ? $val2 : NULL;
                                $data_ins['proposer'] = !empty($proposer[$val][$key2]) ? $proposer[$val][$key2] : NULL; 
                                $data_ins['seconder'] = !empty($seconder[$val][$key2]) ? $seconder[$val][$key2] : NULL;
                                if($data_ins['nominee'] != 0 || ($data_ins['nominee'] == 0 && !empty($data_ins['proposer']) && !empty($data_ins['seconder']))) { // to avoid nomination close
                                    if(!empty($pc_id[$val][$key2])) {
                                        $this->bms_agm_egm_model->update_pre_chair ($data_ins,$pc_id[$val][$key2]);    
                                    } else {
                                        $this->bms_agm_egm_model->insert_pre_chair ($data_ins);
                                    }
                                }                                 
                            }
                        }
                        break;
                    case 2:
                    case 3:
                    case 4:
                    case 5:                        
                        break;
                    case 6:
                    
                        if(!empty($proposer[$val])) {
                            $ps_id = $this->input->post('ps_id');
                            $data_ps['agm_id'] = $agm_id;
                            $data_ps['agenda_id'] = $val;
                            foreach($proposer[$val] as $key2=>$val2) {
                                //$data_ins['nominee'] = isset($val2) ? $val2 : NULL;
                                $data_ps['proposer'] = !empty($proposer[$val][$key2]) ? $proposer[$val][$key2] : NULL; 
                                $data_ps['seconder'] = !empty($seconder[$val][$key2]) ? $seconder[$val][$key2] : NULL;
                                if(!empty($ps_id[$val][$key2])) {
                                    $this->bms_agm_egm_model->update_proposer_seconder ($data_ps,$ps_id[$val][$key2]);    
                                } else {
                                    $this->bms_agm_egm_model->insert_proposer_seconder ($data_ps);
                                }                                
                            }
                        }
                        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
                        break;
                    case 7:
                        $number = $this->input->post('item');
                        
                        if(!empty($number[$val])) {
                            $propose_id = $this->input->post('propose_id');
                            $data_no_comm['agm_id'] = $agm_id;
                            $data_no_comm['agenda_id'] = $val;
                            foreach($number[$val] as $key2=>$val2) {
                                $data_no_comm['item'] = isset($val2) ? $val2 : NULL;
                                $data_no_comm['proposer'] = !empty($proposer[$val][$key2]) ? $proposer[$val][$key2] : NULL; 
                                $data_no_comm['seconder'] = !empty($seconder[$val][$key2]) ? $seconder[$val][$key2] : NULL;
                                if($data_no_comm['item'] != '0' || ($data_no_comm['item'] == '0' && !empty($data_no_comm['proposer']) && !empty($data_no_comm['seconder']))) { // to avoid nomination close
                                    if(!empty($propose_id[$val][$key2])) {
                                        $this->bms_agm_egm_model->update_no_of_committe ($data_no_comm,$propose_id[$val][$key2]);
                                    } else {
                                        $this->bms_agm_egm_model->insert_no_of_committe ($data_no_comm);
                                    }
                                }
                            }
                        }
                        break;
                    case 8:
                        
                        if(!empty($naminee[$val])) {
                            $mc_nomin_id = $this->input->post('mc_nomin_id');
                            $data_ins['agm_id'] = $agm_id;
                            $data_ins['agenda_id'] = $val;
                            foreach($naminee[$val] as $key2=>$val2) {
                                $data_ins['nominee'] = isset($val2) ? $val2 : NULL;
                                $data_ins['proposer'] = !empty($proposer[$val][$key2]) ? $proposer[$val][$key2] : NULL; 
                                $data_ins['seconder'] = !empty($seconder[$val][$key2]) ? $seconder[$val][$key2] : NULL;
                                if($data_ins['nominee'] != 0 || ($data_ins['nominee'] == 0 && !empty($data_ins['proposer']) && !empty($data_ins['seconder']))) { // to avoid nomination close
                                    if(!empty($mc_nomin_id[$val][$key2])) {
                                        $this->bms_agm_egm_model->update_mc_nomin ($data_ins,$mc_nomin_id[$val][$key2]);    
                                    } else {
                                        $this->bms_agm_egm_model->insert_mc_nomin ($data_ins);
                                    }
                                }
                            }
                        }
                        break;
                    default:
                        
                        break;
                }
                
                if(!empty($data)) {
                    $this->bms_agm_egm_model->updateAgenda ($data,$val);
                }
            }
        } else if(!empty($re_vote)) {
            $agendas = $this->bms_agm_egm_model->getAgendaForRevote($re_vote);//getAgendaAfterAgenda($agm_id,$re_vote);
            if(!empty($agendas)) {
                foreach ($agendas as $key=>$val) {
                    //if($key == 0) {
                        $data_agen['agm_id'] = $val['agm_id'];
                        $data_agen['agenda_resol'] = $val['agenda_resol'];
                        $data_agen['seq_no'] = $val['seq_no']+0.1;
                        $data_agen['resolu_type'] = $val['resolu_type'];
                        $this->bms_agm_egm_model->insertAgenda ($data_agen);
                    /*} else {
                        $data_agen2['seq_no'] = $val['seq_no']+1;
                        $this->bms_agm_egm_model->updateAgenda ($data_agen2,$val['agm_agenda_id']);
                    }*/
                }
            }
        }
        //exit;
        redirect ('index.php/bms_agm_egm/agm_process?property_id='.$_POST['property_id'].'&agm_id='.$_POST['agm_id'].(!empty($start_vote) ? '#div_'.$start_vote : (!empty($close_vote) ? '#div_'.$close_vote : '')));
    }
    
    function agm_report () {
        $data['browser_title'] = 'Property Butler | AGM Voting';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM Report';
        //$data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : "";
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        //if(!empty($data ['property_id'])) {
            //$data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
        if(!empty($data ['property_id']) && !empty($data['agm_id'])) {
            $data['agm'] = $this->bms_agm_egm_model->get_agm_details ($data ['agm_id']);
            $data['no_of_ev'] = $this->bms_agm_egm_model->get_elibible_voters_cnt ($data['agm_id']);
            $data['no_of_attendees'] = $this->bms_agm_egm_model->get_attendees_cnt ($data['agm_id']);
            $vote_by = $data['agm']['vote_by'];
            $no_of_committee = $data['agm']['no_of_committee'];
            
            
            $data['agm_details'] = $this->bms_agm_egm_model->getAgenda ($data['agm_id']);
            //echo "<pre>";print_r($data['agm_details']);echo "</pre>";
            $data['units'] = $this->bms_agm_egm_model->get_eligible_voters ($data['agm_id']); 
            if(!empty($data['agm_details'])) {
                foreach($data['agm_details'] as $key=>$val) {
                    switch ($val['resolu_type']) {
                        case 1:
                            $data['pc_nominee'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_full_nominees_details ($val['agm_agenda_id']);
                            $pc_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_pc_result ($val['agm_agenda_id'],$vote_by);          
                                
                            $data['pc_result'][$val['agm_agenda_id']] = '';
                            if(!empty($pc_res[$val['agm_agenda_id']])) {
                                
                                $data['pc_result'][$val['agm_agenda_id']] = '';
                                $tot_votes = array_sum(array_column($pc_res[$val['agm_agenda_id']],'vote_cnt'));
                                foreach ($pc_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                    $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-8 col-xs-8">'.$val2['nom_unit_no']. ' - ' .$val2['nom_owner_name'].'</div>
                                    <div class="col-md-2 col-xs-2">'.$val2['vote_cnt'].'</div>
                                    <div class="col-md-2 col-xs-2 text-center no-padding">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</div>
                                    </div>';
                                }
                                // abstains count
                                $pc_abstains = $this->bms_agm_egm_model->get_pc_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                if(!empty($pc_abstains)) {
                                    $data['pc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-8 col-xs-8">Abstain</div>
                                    <div class="col-md-2 col-xs-2">'.$pc_abstains['vote_cnt'].'</div>                                        
                                    </div>';
                                }
                                
                                
                            }
                        break;
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                            $vote_resol_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_vote_resol_result ($val['agm_agenda_id'],$vote_by);
                            if(!empty($vote_resol_res[$val['agm_agenda_id']])) {
                                $data['vote_resol_result'][$val['agm_agenda_id']] = '';
                                $tot_votes = array_sum(array_column($vote_resol_res[$val['agm_agenda_id']],'vote_cnt'));
                                foreach ($vote_resol_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                    $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-6 col-xs-6">'.($val2['vote_for'] == 1 ? 'FOR' : 'AGAINST').'</div>
                                    <div class="col-md-3 col-xs-3">'.$val2['vote_cnt'].'</div>
                                    <div class="col-md-3 col-xs-3 text-center no-padding">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</div>
                                    </div>';
                                }
                                
                                $vote_resol_abstains = $this->bms_agm_egm_model->get_vote_resol_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                if(!empty($vote_resol_abstains)) {
                                    $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-6 col-xs-6">Abstain</div>
                                    <div class="col-md-3 col-xs-3">'.$vote_resol_abstains['vote_cnt'].'</div>                                        
                                    </div>';
                                }
                                
                                $data['vote_resol_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">
                                
                                </div>';
                            } 
                            break;
                        case 6:                            
                            $data['ps_res'][$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_ps_result ($val['agm_agenda_id']);  
                            break;
                        case 7:
                            $data['no_of_comm'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_no_of_comm_full_nomination_details ($val['agm_agenda_id']);
                            $no_of_comm_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_no_of_comm_result ($val['agm_agenda_id'],$vote_by);  
                            $data['no_of_comm_result'][$val['agm_agenda_id']] = '';
                            if(!empty($no_of_comm_res[$val['agm_agenda_id']])) {
                                $data['no_of_comm_result'][$val['agm_agenda_id']] = '';
                                $tot_votes = array_sum(array_column($no_of_comm_res[$val['agm_agenda_id']],'vote_cnt'));
                                foreach ($no_of_comm_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                    $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-6 col-xs-6">'.$val2['item'].'</div>
                                    <div class="col-md-3 col-xs-3">'.$val2['vote_cnt'].'</div>
                                    <div class="col-md-3 col-xs-3 text-center no-padding">'.number_format((($val2['vote_cnt'] / $tot_votes)*100), 2) .'%</div>
                                    </div>';
                                }
                                
                                $no_of_comm_abstains = $this->bms_agm_egm_model->get_no_of_comm_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                if(!empty($no_of_comm_abstains)) {
                                    $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-6 col-xs-6">Abstain</div>
                                    <div class="col-md-3 col-xs-3">'.$no_of_comm_abstains['vote_cnt'].'</div>                                        
                                    </div>';
                                }
                                
                                $data['no_of_comm_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding text-right" style="padding-top:15px !important;">
                                
                                </div>';
                            }
                            break;
                        case 8:
                            $data['mc_nominee'][$val['agm_agenda_id']] =  $this->bms_agm_egm_model->get_mc_full_nominees_details ($val['agm_agenda_id']);
                            $mc_res[$val['agm_agenda_id']] = $this->bms_agm_egm_model->get_mc_result ($val['agm_agenda_id'],$vote_by);  
                            $data['mc_result'][$val['agm_agenda_id']] = '';
                            if(!empty($mc_res[$val['agm_agenda_id']])) {
                                $data['mc_result'][$val['agm_agenda_id']] = '';
                                //$tot_votes = array_sum(array_column($mc_res[$val['agm_agenda_id']],'vote_cnt'));
                                foreach ($mc_res[$val['agm_agenda_id']] as $key2=>$val2) {
                                    $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-8 col-xs-8">'.$val2['nom_unit_no']. ' - ' .$val2['nom_owner_name'].'</div>
                                    <div class="col-md-2 col-xs-2">'.$val2['vote_cnt'].'</div>                                        
                                    </div>';
                                }
                                
                                $mc_abstains = $this->bms_agm_egm_model->get_mc_abstains ($data['agm_id'],$val['agm_agenda_id'],$vote_by);
                                if(!empty($mc_abstains)) {
                                    $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 col-xs-12 no-padding" style="padding-top:10px !important;">
                                    <div class="col-md-8 col-xs-8">Abstain</div>
                                    <div class="col-md-2 col-xs-2">'.$mc_abstains['vote_cnt'].'</div>                                        
                                    </div>';
                                }
                                
                                $data['mc_result'][$val['agm_agenda_id']] .= '<div class="col-md-12 no-padding text-right" style="padding-top:15px !important;">                                
                                </div>';
                            }
                            break;
                    } 
                }
            }               
        }
                
        
        //echo "<pre>";print_r($data['pc_nominee']);echo "</pre>";
        $this->load->view('agm_egm/agm_report_view',$data);
    }
    
    function get_abstrain_cnt () {
        //echo $_POST['agenda_id'];
        $str = '';
        /*if(!empty($_POST['agenda_id'])){
            $data['pin'] = NULL;
            $data['agenda_status'] = 1;
            $this->bms_agm_egm_model->updateAgenda($data,$_POST['agenda_id']);                            
        }
        echo $str;  */ 
        $resol_type = !empty($_POST['resol_type']) ? $_POST['resol_type'] : '';
        $agenda_id = !empty($_POST['agenda_id']) ? $_POST['agenda_id'] : '';
        $agm_id = !empty($_POST['agm_id']) ? $_POST['agm_id'] : '';
        $vote_by = !empty($_POST['vote_by']) ? $_POST['vote_by'] : '';
        switch ($resol_type) {
            case 1:
                $abstains = $this->bms_agm_egm_model->get_pc_abstains ($agm_id,$agenda_id,$vote_by);
                break;
            case 2:
            case 3:
            case 4:
            case 5:
                $abstains = $this->bms_agm_egm_model->get_vote_resol_abstains ($agm_id,$agenda_id,$vote_by);
                break;
            case 7:
                $abstains = $this->bms_agm_egm_model->get_no_of_comm_abstains ($agm_id,$agenda_id,$vote_by);
                break;
            case 8:
                $abstains = $this->bms_agm_egm_model->get_mc_abstains ($agm_id,$agenda_id,$vote_by);
                break;
        }
        echo $abstains['vote_cnt'];
    }
    
    function result_details () {
        //echo "<pre>";print_r($_GET);echo "</pre>";
        
        $data['resol_type'] = $_GET['resol_type'];
        $unit_id = $_GET['unit_id'];
        $data['vote_by'] = $_GET['vote_by'];
        $agenda_id = $_GET['agenda_id'];
        $data['check_abst'] = explode('_',$unit_id);
        if(!empty($data['resol_type'])) {
            switch ($data['resol_type']) {
                case 1:
                    if($data['check_abst'][0] == 'a')
                        $data['abstrain'] = $this->bms_agm_egm_model->get_pc_abstains_details ($data['check_abst'][1],$agenda_id,$data['vote_by']);
                    else 
                        $data['pc_res'] = $this->bms_agm_egm_model->get_pc_result_details ($unit_id,$agenda_id,$data['vote_by']);
                    break;
                case 2:
                case 3:
                case 4:
                case 5:
                    if($data['check_abst'][0] == 'a')
                        $data['abstrain'] = $this->bms_agm_egm_model->get_vote_resol_abstains_details ($data['check_abst'][1],$agenda_id,$data['vote_by']);
                    else 
                        $data['vote_res'] = $this->bms_agm_egm_model->get_vote_resol_result_details ($unit_id,$agenda_id,$data['vote_by']);
                    break;
                case 7:
                    if($data['check_abst'][0] == 'a')
                        $data['abstrain'] = $this->bms_agm_egm_model->get_no_of_comm_abstains_details ($data['check_abst'][1],$agenda_id,$data['vote_by']);
                    else 
                        $data['vote_res'] = $this->bms_agm_egm_model->get_no_of_comm_result_details ($unit_id,$agenda_id,$data['vote_by']);                
                    break;
                case 8:
                    if($data['check_abst'][0] == 'a')
                        $data['abstrain'] = $this->bms_agm_egm_model->get_mc_abstains_details ($data['check_abst'][1],$agenda_id,$data['vote_by']);
                    else 
                        $data['vote_res'] = $this->bms_agm_egm_model->get_mc_result_details ($unit_id,$agenda_id,$data['vote_by']);
                    break;                                                        
            }
        }
        //echo "<pre>";print_r($data['pc_res']);echo "</pre>";
        $this->load->view('agm_egm/agm_res_details_view',$data);
    }
    
    function agm_process2 () {
        $data['browser_title'] = 'Property Butler | AGM';
        $data['page_header'] = '<i class="fa fa-bandcamp"></i> AGM <i class="fa fa-angle-double-right"></i> AGM ';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['agm_id'] = !empty($_GET['agm_id']) ? $_GET['agm_id'] : '';
        if(!empty($data ['property_id'])) {
            $data['agms'] = $this->bms_agm_egm_model->get_agms ($data ['property_id']);
            if(!empty($data['agm_id']))
                $data['units'] = $this->bms_agm_egm_model->get_eligible_voters ($data['agm_id']);         
        }
        //echo "<pre>";print_r($data['units']);echo "</pre>";
        $this->load->view('agm_egm/agm_process2_view',$data);
    }
    
    function agm_notice () {
        
    }

    function get_bms_agm_agenda_details () {
        $agm_agenda_id = $this->input->post('agm_agenda_id');
        $bms_agm_agenda_details = $this->bms_agm_egm_model->get_bms_agm_agenda_details ( $agm_agenda_id );
        echo $bms_agm_agenda_details->agenda_resol;
        die;
    }

    function update_bms_agm_agenda_details () {
        $agm_agenda_id = $this->input->post('agm_agenda_id');
        $agenda_resol = $this->input->post('agenda_resol');
        $this->bms_agm_egm_model->update_bms_agm_agenda_details ( $agm_agenda_id, $agenda_resol );
        $bms_agm_agenda_details = $this->bms_agm_egm_model->get_bms_agm_agenda_details ( $agm_agenda_id );
        echo $bms_agm_agenda_details->agenda_resol;
        die;
    }

    
}