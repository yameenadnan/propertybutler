<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_meetings extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff' || !in_array('3',$_SESSION['bms']['access_mod'])) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //if(!in_array($this->uri->segment(2), array('get_unit_list','check_email')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_meetings_model');
        $this->load->model('bms_property_model');       
    }

    public function meetings_list($offset=0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Meetings List';
        $data['page_header'] = '<i class="fa fa-comment"></i> Meetings <i class="fa fa-angle-double-right"></i> Meetings List';
                
        /*parse_str($_SERVER['QUERY_STRING'], $output);
        if(isset($output['doc_type']))  unset($output['doc_type']);
        $data['query_str'] = http_build_query($output);*/
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;        
        
        $this->load->view('meetings/meetings_list_view',$data);
	}
    
    public function get_meetings_list() {        
        
        header('Content-type: application/json');        
        $meetings = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $meetings = $this->bms_meetings_model->get_meetings_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($meetings);
	} 
    
    
    public function add_meeting($meeting_id = '') {
        
        $type = $meeting_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | '.$type.' Meetings';
        $data['page_header'] = '<i class="fa fa-comment"></i> Meetings <i class="fa fa-angle-double-right"></i> '.$type.' Meeting';
        
        $data['meeting_id'] = $meeting_id;
        $data['properties'] = $this->bms_masters_model->getMyPropertiesWithCharg ();
        $data['meeting_remin'] = $this->config->item('meeting_remin');
        
        if($meeting_id != '') {
            $data['meetings_info'] = $this->bms_meetings_model->get_meeting_details($meeting_id);            
            if(empty($data['meetings_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }            
            $data['staff'] = $this->bms_meetings_model->getPropertyStaffsForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['jmb'] = $this->bms_meetings_model->getPropertyJmbForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['minot_task'] = $this->bms_meetings_model->getToBeDiscussMinorTaskForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['externals'] = $this->bms_meetings_model->getExternals ($meeting_id);
            $data['designations'] = $this->bms_masters_model->getAssignTo ($data['meetings_info']['property_id']);
            
            $data['check_list'] = $this->bms_meetings_model->getMeetingChkList($meeting_id);
                if(!empty($data['check_list'])) {
                    foreach ($data['check_list'] as $key=>$val) {
                        $data['chk_list_reminder'][$key] = $this->bms_meetings_model->getMeetingChkListRemin ($val['meeting_checklist_id']);
                    }
                }
                                 
        }        
        //echo "<pre>";print_r($data['chk_list_reminder']);echo "</pre>"; exit;
        $this->load->view('meetings/add_meeting_view',$data);
	}   
    
    public function attendes_list() {        
        
        header('Content-type: application/json');        
        $property_id = $this->input->post('property_id');            
        $data['staff'] = $this->bms_meetings_model->getPropertyStaffs ($property_id);
        $data['jmb'] = $this->bms_meetings_model->getPropertyJmb ($property_id);
        $data['minot_task'] = $this->bms_meetings_model->getToBeDiscussMinorTask ($property_id);
        $data['designations'] = $this->bms_masters_model->getAssignTo ($property_id);
        echo json_encode($data);
	}
    
    function add_meeting_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        $meetings  = $this->input->post('meetings');
        $meeting_chklist  = $this->input->post('meeting_chklist');
        $meeting_remin  = $this->input->post('meeting_remin');
        $meeting_id = '';
        
        if(!empty($meetings)) {
            $meetings['meeting_date'] = date('Y-m-d',strtotime($meetings['meeting_date']));
            $meetings['meeting_time'] = !empty($meetings['meeting_time']) ? date("H:i:s",strtotime($meetings['meeting_time'])) : '';
            //$meetings['agenda_to_discuss'] = !empty($meetings['agenda_to_discuss']) ? nl2br($meetings['agenda_to_discuss']) : '';
            
            $type = 'add';
            if(!empty($meetings['meeting_id'])) {
                $type = 'edit'; 
                $meeting_id = $meetings['meeting_id'];
                $this->bms_meetings_model->updateMeeting($meetings,$meeting_id);
            } else {
                $meeting_id = $this->bms_meetings_model->insertMeeting($meetings);
            }
            
            if(!empty($meeting_id)) {
                $building_staff = $this->input->post('building_staff');               
                
                $this->bms_meetings_model->deleteMeetingStaffAttende ($meeting_id);
                if(!empty($building_staff))  {
                    foreach ($building_staff as $key=>$val) {
                        $data_attende_staff['user_id'] = $val;
                        $data_attende_staff['user_type'] = 1; 
                        $data_attende_staff['meeting_id'] = $meeting_id;
                        $this->bms_meetings_model->insertMeetingAttende ($data_attende_staff);
                    }
                }
                
                $minor_task = $this->input->post('minor_task');               
                
                $this->bms_meetings_model->deleteMeetingMinorTask ($meeting_id);
                if(!empty($minor_task))  {
                    foreach ($minor_task as $key=>$val) {
                        $data_minor_task['meeting_id'] = $meeting_id;
                        $data_minor_task['minor_task_id'] = $val;                        
                        $this->bms_meetings_model->insertMeetingMinorTask ($data_minor_task);
                    }
                }
                
                $jmb_member = $this->input->post('jmb_member');               
                
                $this->bms_meetings_model->deleteMeetingJmbAttende ($meeting_id);
                if(!empty($jmb_member))  {
                    foreach ($jmb_member as $key=>$val) {
                        $data_attende_staff['user_id'] = $val;
                        $data_attende_staff['user_type'] = 2; 
                        $data_attende_staff['meeting_id'] = $meeting_id;
                        $this->bms_meetings_model->insertMeetingAttende ($data_attende_staff);
                    }
                }
                
                $meetings_attend_oth = $this->input->post('meetings_attend_oth'); 
                if(!empty($meetings_attend_oth)) {
                    $meetings_attend_oth_ids = array_filter(array_values($meetings_attend_oth['meetings_attend_oth_id']));
                    if(!empty($meetings_attend_oth_ids))
                        $this->bms_meetings_model->deleteMeetingAttendeOth ($meeting_id,$meetings_attend_oth_ids);
                    foreach ($meetings_attend_oth['name'] as $key=>$val) {
                        if($meetings_attend_oth['name'][$key]) {
                            $data_attende_oth['meeting_id'] = $meeting_id;
                            $data_attende_oth['name'] = $meetings_attend_oth['name'][$key];
                            $data_attende_oth['email_addr'] = $meetings_attend_oth['email_addr'][$key]; 
                            $data_attende_oth['contact_no'] = $meetings_attend_oth['contact_no'][$key];
                            $data_attende_oth['person_name'] = $meetings_attend_oth['person_name'][$key];
                            if(!empty($meetings_attend_oth['meetings_attend_oth_id'][$key]))
                                $this->bms_meetings_model->updateMeetingAttendeOth ($data_attende_oth,$meetings_attend_oth['meetings_attend_oth_id'][$key]);
                            else 
                                $this->bms_meetings_model->insertMeetingAttendeOth ($data_attende_oth);
                        }
                        
                    }
                }
                
                //$meeting_chklist = $this->input->post('meeting_chklist');                      
                $meeting_master_ids = array_filter(array_values($meeting_chklist['meeting_checklist_id']));
                //echo "<pre>"; print_r($agm['agm_master_id']); print_r($_POST);echo "</pre>";   exit;
                if(!empty($meeting_master_ids)) {
                    $toBeDeleteMeeting = $this->bms_meetings_model->getDeleteMeetingChecklist ($meeting_id,$meeting_master_ids);
                    if(!empty($toBeDeleteMeeting)) {
                        foreach ($toBeDeleteMeeting as $key=>$val) {
                            $toBeDeleteMeetingMasterIds[] = $val['meeting_checklist_id'];
                        }
                        if(!empty($toBeDeleteMeetingMasterIds))  {
                            $this->bms_meetings_model->deleteMeetingChecklistReminder ($toBeDeleteMeetingMasterIds);
                            $this->bms_meetings_model->deleteMeetingChecklist ($toBeDeleteMeetingMasterIds);
                        }
                    }
                }
                foreach ($meeting_chklist['meeting_checklist_id'] as $key=>$val) {
                    
                    $update_data['meeting_id'] = $meeting_id;
                    $update_data['meeting_descrip'] = $meeting_chklist['meeting_descrip'][$key];
                    $update_data['meeting_responsibility'] = $meeting_chklist['meeting_responsibility'][$key];
                    $meeting_checklist_id = '';
                    if(empty($val)) {
                        // insert process
                        $meeting_checklist_id = $this->bms_meetings_model->insertMeetingCheckList ($update_data);
                    } else {
                        // update process
                        $meeting_checklist_id = $val;
                        $this->bms_meetings_model->updateMeetingChecklist ($update_data,$val);
                    }    
                        
                    if(!empty($meeting_remin[$key]['meeting_checklist_reminder_id'])) {
                        $meeting_remin_ids = array_filter(array_values($meeting_remin[$key]['meeting_checklist_reminder_id']));
                        if(!empty($meeting_remin_ids))
                            $this->bms_meetings_model->deleteMeetingChklistReminderById ($meeting_checklist_id,$meeting_remin_ids);
                    }
                    if(!empty($meeting_remin[$key]['remind_before'])) {
                        foreach ($meeting_remin[$key]['remind_before'] as $key2=>$val2) {
                            $update_data2['meeting_checklist_id'] = $meeting_checklist_id;
                            $update_data2['remind_before'] = $meeting_remin[$key]['remind_before'][$key2];
                            $update_data2['email_content'] = !empty($meeting_remin[$key]['email_content'][$key2]) ? $meeting_remin[$key]['email_content'][$key2] : '-';
                            $update_data2['email_staff'] = !empty($meeting_remin[$key]['email_staff'][$key2]) ? $meeting_remin[$key]['email_staff'][$key2] : 0;
                            $update_data2['email_jmb'] = !empty($meeting_remin[$key]['email_jmb'][$key2]) ? $meeting_remin[$key]['email_jmb'][$key2] : 0;
                            $update_data2['email_external'] = !empty($meeting_remin[$key]['email_external'][$key2]) ? $meeting_remin[$key]['email_external'][$key2] : 0;
                            if(!empty($meeting_remin[$key]['meeting_checklist_reminder_id'][$key2])) {
                                $meeting_checklist_reminder_id = $meeting_remin[$key]['meeting_checklist_reminder_id'][$key2];                                
                                $this->bms_meetings_model->updateMeetingChecklistReminder ($update_data2,$meeting_checklist_reminder_id);
                            } else {
                                $this->bms_meetings_model->insertMeetingChecklistReminder ($update_data2);
                            }
                        }
                    }
                }                        
            }
            $_SESSION['flash_msg'] = 'Meeting '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
            redirect ('index.php/bms_meetings/meetings_list/0/25?property_id='.$meetings['property_id']);
        }        
        redirect ('index.php/bms_meetings/meetings_list/');
    }
    
    function meetings_checklist_update ($meeting_id) {
        //echo $agm_id;
        //$data['agm_types'] = $this->config->item('agm_types');
        $data['meetings'] = $this->bms_meetings_model->getMeetingWithPropName($meeting_id);
        $data['check_list'] = $this->bms_meetings_model->getMeetingChkListDesignation($meeting_id);
        //echo "<pre>";print_r($data);echo "</pre>";
        $this->load->view('meetings/meeting_chklist_update_view',$data);
    }
    
    function checklist_status_update () {
        //print_r($_POST);
        if(!empty($_POST)) {
            foreach ($_POST as $key=>$val) {
                //$data['agm_checklist_id'] = $key;
                $data['meeting_checklist_status'] = 1;
                $data['meeting_checklist_remarks'] = trim($val);                
                $this->bms_meetings_model->update_checklist_status ($data,$key);
            }
            echo 1;
        } else {
            echo 0;
        }
    }
    
    public function meeting_minutes($meeting_id) {
        
        $type = $meeting_id != '' ? 'Edit' : 'Add';    
		$data['browser_title'] = 'Property Butler | Minutes of Meeting';
        $data['page_header'] = '<i class="fa fa-comment"></i> Meetings <i class="fa fa-angle-double-right"></i> Minutes of Meeting';
        
        $data['meeting_id'] = $meeting_id;
        $data['properties'] = $this->bms_masters_model->getMyPropertiesWithCharg ();
        $data['meeting_remin'] = $this->config->item('meeting_remin');
        
        if($meeting_id != '') {
            $data['meetings_info'] = $this->bms_meetings_model->get_meeting_details($meeting_id);            
            if(empty($data['meetings_info'])) {
                redirect('index.php/bms_dashboard/index'); 
            }            
            $data['staff'] = $this->bms_meetings_model->getPropertyStaffsForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['jmb'] = $this->bms_meetings_model->getPropertyJmbForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['minot_task'] = $this->bms_meetings_model->getToBeDiscussMinorTaskForEdit ($data['meetings_info']['property_id'],$meeting_id);
            $data['externals'] = $this->bms_meetings_model->getExternals ($meeting_id);
            $data['meeting_minutes'] = $this->bms_meetings_model->getMeetingMinutes ($meeting_id);
            //$data['designations'] = $this->bms_masters_model->getAssignTo ($data['meetings_info']['property_id']);
            
            /*$data['check_list'] = $this->bms_meetings_model->getMeetingChkList($meeting_id);
                if(!empty($data['check_list'])) {
                    foreach ($data['check_list'] as $key=>$val) {
                        $data['chk_list_reminder'][$key] = $this->bms_meetings_model->getMeetingChkListRemin ($val['meeting_checklist_id']);
                    }
                }*/
                                 
        }        
        //echo "<pre>";print_r($data['chk_list_reminder']);echo "</pre>"; exit;
        $this->load->view('meetings/meeting_minutes_view',$data);
	}
    
    function meeting_minutes_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>";exit;
        $meetings  = $this->input->post('meetings');
        
        $meeting_id = $meetings['meeting_id'];
        
        if(!empty($meeting_id)) {
            
            $meeting_minutes = $this->input->post('meeting_minutes'); 
            if(!empty($meeting_minutes)) {
                $meeting_minutes_ids = array_filter(array_values($meeting_minutes['meeting_minutes_id']));
                if(!empty($meeting_minutes_ids))
                    $this->bms_meetings_model->deleteMeetingMinutes ($meeting_id,$meeting_minutes_ids);
                foreach ($meeting_minutes['minutes_title'] as $key=>$val) {
                    $data_mm['meeting_id'] = $meeting_id;
                    $data_mm['minutes_title'] = $meeting_minutes['minutes_title'][$key];
                    $data_mm['minutes_descrip'] = $meeting_minutes['minutes_descrip'][$key];
                    $data_mm['action_by'] = $meeting_minutes['action_by'][$key];
                    $data_mm['progress'] = $meeting_minutes['progress'][$key];
                    
                    if(!empty($meeting_minutes['meeting_minutes_id'][$key]))
                        $this->bms_meetings_model->updateMeetingMinutes ($data_mm,$meeting_minutes['meeting_minutes_id'][$key]);
                    else 
                        $this->bms_meetings_model->insertMeetingMinutes ($data_mm);
                }
            }
                                       
            
            $_SESSION['flash_msg'] = 'Minutes of Meeting updated successfully!'; 
            redirect ('index.php/bms_meetings/meetings_list/0/25?property_id='.$meetings['property_id']);
        }
        
        redirect ('index.php/bms_meetings/meetings_list/');
    }
    
}