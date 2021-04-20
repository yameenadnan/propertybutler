<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
#header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

class Bms_api_for_mobile extends CI_Controller {	
    
    function __construct () { 
        parent::__construct ();
        header('Content-type: application/json');
        
        if(!isset($_REQUEST['auth_key']) || $_REQUEST['auth_key'] != 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$') {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Authentication failed!'));
            echo json_encode(array($data));	exit;       	       
	    } 
        $this->load->model('bms_masters_model'); 
        $this->load->model('bms_task_model'); 
    }
    
    public function checkMemberLogin() {
        
        $data = array ();
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && isset($inputs->username) && trim($inputs->username) != '' && isset($inputs->password) && trim($inputs->password) != '') {
                        
            $username = trim($inputs->username);//trim($this->input->post('username'));
            $pass = trim($inputs->password);//trim($this->input->post('password'));
            $result = $this->bms_masters_model->auth($username,$pass);
            $user_info = array();
            if(count($result) == 1 ) {  
                
                $user_info['user_type'] = 'staff';
                $user_info['full_name'] = trim($result[0]['first_name']).' '.  trim($result[0]['last_name']);
                $user_info['first_name'] = isset($result[0]['first_name']) && $result[0]['first_name'] != '' ? $result[0]['first_name'] : '';                
                $user_info['email'] = $result[0]['email_addr']; 
                $user_info['staff_id'] = $result[0]['staff_id']; 
                $user_info['designation_id'] = $result[0]['designation_id'];                
                
                $data['Data'] = array('user_info'=>array($user_info));
                $data['Status'] = true;
                $data['ErrorLog'] = array(); 
                
            } else {            
                $result = $this->bms_masters_model->auth_jmb($username,$pass);                
                if(count($result) > 0 ) {                          
                    $user_info['first_name'] = $user_info['full_name'] = trim($result[0]['owner_name']);                                
                    $user_info['email'] = $result[0]['email_addr']; 
                    $user_info['staff_id'] = 0;
                    $user_info['property_id'] = $result[0]['property_id'];
                    $user_info['unit_id'] = $result[0]['unit_id'];  
                    $user_info['member_id'] = $result[0]['member_id'];
                    $user_info['designation_id'] = $user_info['user_type']  = 'jmb'; 
                    $data['Data'] = array('user_info'=>array($user_info));
                    $data['Status'] = true;
                    $data['ErrorLog'] = array();                    
                } else {                    
                    $data['Data'] = array();
                    $data['Status'] = false;
                    $data['ErrorLog'] = array(array('message'=>'Invalid Email or Password!')); 
                }
            }
            
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Please enter Email and Password!'));   
        }
        echo json_encode(array($data));
	   	//echo json_encode();
	}  
    
    public function getAllProperty() {	
        
        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getProperties ());
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	   	//echo json_encode();
	}
    
    public function getMyProperty() {	   
	   	$inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getMyProperties ($inputs->staff_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getJmbProperties() {	   
	   	$inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property'=>$this->bms_masters_model->getJmbProperties ($inputs->property_id));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getAllBlockRStreet() {	   
	   	$inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property_block'=>$this->bms_masters_model->getBlocks ($inputs->propertyID));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getAllUnitNumber() {	   
	   	$inputs = json_decode(file_get_contents('php://input'));
        $data['Data'] = array('bms_property_units'=>$this->bms_masters_model->getUnit ($inputs->propertyID,$inputs->blockID));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getAllTaskCategories() {	   
	   	
        $task_cat = $this->config->item('task_cat');
        foreach ($task_cat as $key=>$val) {
            $task_cat_arr[$key-1]['task_cat_id'] = $key;
            $task_cat_arr[$key-1]['task_cat_name'] = $val; 
        }
            
        $data['Data'] = array('bms_task_category'=>$task_cat_arr);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getAllSourceofAssignment() {	   
	   	$source_assign = $this->config->item('source_assign');
        foreach ($source_assign as $key=>$val) {
            $source_assign_arr[$key-1]['sa_id'] = $key;
            $source_assign_arr[$key-1]['sa_name'] = $val; 
        }
        $data['Data'] = array('bms_source_assign'=>$source_assign_arr);
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    public function getAllAssignToDesignation() {	   
	   	$inputs = json_decode(file_get_contents('php://input'));        
        $data['Data'] = array('bms_designation'=>$this->bms_masters_model->getAssignTo ($inputs->propertyID));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));
	}
    
    
    function task_image_submit () {
        
        $data = array ();
        if(!empty($_FILES)){
            $file_ifo_at_start = $_FILES;    
            $task_file_upload_temp = $this->config->item('task_file_upload_temp');
            
            $this->load->library('upload');
            
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $task_file_upload_temp['file_name'] = $date->format("YmdHisu");
            
            /*$file_ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            if($file_ext == "") {
                //$exten = array_pop(explode('/',$_FILES["file"]["type"]));
                $_FILES["file"]["name"] = $_FILES["file"]["name"].".jpg";
            } else if(strtolower($file_ext) == 'png') {
                $_FILES["file"]["type"] = 'image/png';
            }*/
            /*$file_name_arr = explode('?',$_FILES["file"]["name"]);
            $_FILES["file"]["name"] = $file_name_arr[0];
            $file_ext = end(explode('.',$file_name_arr[0]));
            if(strtolower($file_ext) == 'png') {
                $_FILES["file"]["type"] = 'image/png';
            }*/
            //$data['debug'] = array('debug_msg'=>$file_ext . ' - '. $_FILES["file"]["name"] ." - ".$_FILES["file"]["type"]);
            
            $this->upload->initialize($task_file_upload_temp);
            
            if ( ! $this->upload->do_upload('file') ) {                
                $data['Data'] = array();
                $data['Status'] = false;
                $data['ErrorLog'] = array(array('message'=>$this->upload->display_errors(),'file_info_init'=>$file_ifo_at_start,'file_info_end'=>$_FILES));                        
            } else {   
                $data['Data'] = array('file_name'=>$this->upload->data('file_name'));
                $data['Status'] = true;
                $data['ErrorLog'] = array();                              
            }                  
            
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'File is not posted'));  
            
        }
        echo json_encode(array($data));
    }
    
    
    function getAllMyMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $desi_id = isset($inputs->desiID) && trim($inputs->desiID) != '' ? trim ($inputs->desiID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        //$data['tasks'] = $this->bms_task_model->get_task ('own','','',$property_id,$task_status);
        $data['Data'] = array('bms_task'=>$this->bms_task_model->get_task ('own',$desi_id,$staff_id,'','',$property_id,$task_status));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));   
    } 
    
    function getAllOverseeingMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $desi_id = isset($inputs->desiID) && trim($inputs->desiID) != '' ? trim ($inputs->desiID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 10;
                 
        $data['Data'] = array('bms_task_os'=>$this->bms_task_model->get_task_with_num_rows ('oversee',$desi_id,$staff_id,$offset,$rows,$property_id,$task_status));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));   
    }
    
    function getAllJmbMinorTasks() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $task_status = isset($inputs->taskStatus) && trim($inputs->taskStatus) != '' ? trim ($inputs->taskStatus) : '';
        $offset = isset($inputs->offset) && trim($inputs->offset) != '' ? trim ($inputs->offset) : 0;
        $rows = isset($inputs->rows) && trim($inputs->rows) != '' ? trim ($inputs->rows) : 10;
                 
        $data['Data'] = array('bms_task_jmb'=>$this->bms_task_model->get_task_with_num_rows_jmb ($property_id,$task_status,"",$offset,$rows));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));   
    }
    
    
    function createMinorTask () {
        
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && !empty($inputs)) {
            //$this->load->model('bms_task_model');
            //$task = $this->input->post('task');
            
            $task['created_date'] = date('Y-m-d');
            $task['property_id'] = $inputs->property_id;
            $task['unit_id'] = $inputs->unit_id;
            $task['task_name'] = $inputs->task_name;
            $task['task_location'] = $inputs->task_location;
            $task['task_details'] = $inputs->task_details;
            $task['task_category'] = $inputs->task_category;
            $task['task_source'] = $inputs->task_source;
            $task['assign_to'] = $inputs->assign_to;
            $task['created_by'] = $inputs->created_by;
            $task['created_by_type'] = $task['created_by'] == '0' ? 'J' : 'S';
            
            if(isset($inputs->due_date) && $inputs->due_date != '') 
                $task['due_date'] = date('Y-m-d', strtotime($inputs->due_date));
            else 
                $task['due_date'] = '';
                
            $task['task_status'] = 'O';
            
            $insert_id = $this->bms_task_model->task_insert($task);            
            
            if(!empty($inputs->files)){
                
                $task_file_upload = $this->config->item('task_file_upload');
                $task_file_upload['upload_path'] = $task_file_upload['upload_path'].$insert_id.'/'; 
                if(!is_dir($task_file_upload['upload_path']));
                    @mkdir($task_file_upload['upload_path'], 0777);
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                
                foreach ($inputs->files as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $task_file_upload['upload_path'].$val);
                    $img_data['task_id'] = $insert_id;
                    $img_data['img_name'] = $val; 
                    $this->bms_task_model->task_image_name_insert ($img_data);                               
                }
                
            }
            
            // for task log
            $this->bms_task_model->set_task_create_log($insert_id,$task['created_by'],$task['due_date']);
            
            // for notification
            $this->bms_task_model->task_alert_insert ($insert_id,$task['property_id'],'1');                        
                        
            // Notification emil to resident for task creation
            if(!empty($inputs->resident_email_hidd)) {
                
                $to = $inputs->resident_email_hidd;
                $r_name = !empty($inputs->resident_name_hidd) ? $inputs->resident_name_hidd : '';
                
                $task_cat = $this->config->item('task_cat');
                $source_assign = $this->config->item('source_assign');               
                
                $this->load->library('email');
    
                $subject = $inputs->task_name .' | '. $inputs->property_name;
                $message = '<p>To <b>';
                if(!empty($inputs->resident_gender_hidd)) {
                    $message .= $inputs->resident_gender_hidd == 'Male' ? 'Mr ' : ($inputs->resident_gender_hidd == 'Female' ? 'Ms ' : '');
                }                
                $message .= $r_name;                
                $message .= ',</b><br /><br />';                
                
                $message .= 'We thank you for your '.$source_assign[$inputs->task_source].' highlighting the ';
                $message .= $task_cat[$inputs->task_category] . '. A task has been created as per below description. We keep this task as our highest priority and looking forward to solve as soon as possible. We will notify you when the task is solved for your kind reference.<br /><br />';
                
                $message .= 'Task Name: '.$inputs->task_name .'<br />';
                $message .= 'Task Location : '.(!empty($inputs->task_location) ? $inputs->task_location : ' - ' ) .'<br />';
                $message .= 'Task Details : '.(!empty($inputs->task_details) ? $inputs->task_details : ' - ' ) .'<br /><br />';
                
                $message .= 'Thank you,<br />Transpacc <br />'.$inputs->property_name;  
                            
                $message .= '</p>';
                
                // Get full html:
                $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                    <title>' . html_escape($subject) . '</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, Verdana, Helvetica, sans-serif;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                ' . $message . '
                </body>
                </html>';
                // Also, for getting full html you may use the following internal method:
                //$body = $this->email->full_html($subject, $message);
                
                $result = $this->email
                    ->from('admin@tpaccbms.com','TRANSPACC BMS')
                    ->reply_to('admin@tpaccbms.com','TRANSPACC BMS')    // Optional, an account where a human being reads.
                    ->to($to,$r_name)
                    ->bcc('naguwin@gmail.com','Nagarajan')
                    //->bcc('tanbenghwa@gmail.com')
                    ->bcc('email@transpacc.com.my','Transpacc Emails')
                    ->subject($subject)
                    ->message($body)
                    ->send();
                    
            }
               
            $data['Data'] = array('message'=>'Task Created Successfully!');
            $data['Status'] = true;
            $data['ErrorLog'] = array();   
            echo json_encode(array($data));    
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task Creation error!'));  
            echo json_encode($data);   
        }
    } 
    
    function getTaskDetails () {
        $inputs = json_decode(file_get_contents('php://input'));
        $task_id = isset($inputs->taskID) && trim($inputs->taskID) != '' ? trim ($inputs->taskID) : '';
        $staff_id = isset($inputs->staffID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $property_id = isset($inputs->propertyID) && trim($inputs->propertyID) != '' ? trim ($inputs->propertyID) : '';
        $user_type = isset($inputs->userType) && trim($inputs->userType) != '' ? trim ($inputs->userType) : '';
        
        if(isset($user_type) && $user_type == 'jmb') {
            $data['task_details'] = $this->bms_task_model->get_task_details_jmb_mc ($task_id,$property_id);
        } else {
            $data['task_details'] = $this->bms_task_model->get_task_details($task_id,$staff_id);
        }
        if(!empty($data['task_details'])) {
            $task_cat = $this->config->item('task_cat');
            $source_assign = $this->config->item('source_assign');
            $data['task_details']->task_category_name = !empty($data['task_details']->task_category) ? $task_cat[$data['task_details']->task_category] : '';
            $data['task_details']->task_source_name = !empty($data['task_details']->task_source) ? $source_assign[$data['task_details']->task_source] : '';
                        
        }
        
        if($data['task_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['task_details']->block_id);
        $data['task_images'] = $this->bms_task_model->get_task_images($task_id);
        $data['task_forum_cnt'] = $this->bms_task_model->getTaskForum($task_id,'cnt');
        // fetch task forum new entry count 
        
        
        // remove from notification
        $this->bms_task_model->task_alert_delete ($task_id,$user_type,$staff_id);
        $data_out['Data'] = array('task_data'=>array($data));
        $data_out['Status'] = true;
        $data_out['ErrorLog'] = array();
        echo json_encode(array($data_out)); 
    } 
    
    function setTaskStatus () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->taskID) && $inputs->taskID != '') {
            $task_id = $inputs->taskID;
            if(isset($inputs->task_update) && $inputs->task_update == 'Closed') {
                $data['task_status'] = 'C';
                // for notification
                $this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'4');
            } else if (isset($inputs->task_update) && $inputs->task_update != '') {
                $data['task_update'] = trim($inputs->task_update);
                if(isset($inputs->due_date) && $inputs->due_date != '') {
                    $data['due_date'] = date('Y-m-d', strtotime($inputs->due_date));
                } 
                // for notification
                $this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'2');
            }
            $data['updated_by'] = $inputs->staff_id;
            $data['updated_date'] = date("Y-m-d");
            $this->bms_task_model->set_task_update_with_log($task_id,$data,$inputs->staff_id);
            
            // Notification emil to resident for task close
            if(isset($inputs->task_update) && $inputs->task_update == 'Closed') {
                $unit_details = $this->bms_task_model->get_task_details_for_email($task_id);
                //echo "<pre>";print_r($unit_details);echo "</pre>";
                if(!empty($unit_details) && !empty($unit_details['email_addr'])) {
                    $to = $unit_details['email_addr'];
                    $r_name = !empty($unit_details['owner_name']) ? $unit_details['owner_name'] : '';
                    
                    //$task_cat = $this->config->item('task_cat');
                    //$source_assign = $this->config->item('source_assign');
                    
                    
                    $this->load->library('email');
        
                    $subject = $unit_details['task_name'] .' | '. $unit_details['property_name'];
                    $message = '<p>To <b>';
                    if(!empty($unit_details['gender'])) {
                        $message .= $unit_details['gender'] == 'Male' ? 'Mr ' : ($unit_details['gender'] == 'Female' ? 'Ms ' : '');
                    }                
                    $message .= $r_name;                
                    $message .= ',</b><br /><br />';                
                    
                    $message .= 'We are pleased to inform you that your complaint has been resolved. We hope you are pleased with our service. Should you have any other comments pertaining to this task, please do contact our management office. ';
                    $message .= 'We are here to serve you better.<br /><br />';                  
                    $message .= 'Thank you,<br />Transpacc <br />'.$unit_details['property_name'];  
                                
                    $message .= '</p>';
                    
                    // Get full html:
                    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                        <title>' . html_escape($subject) . '</title>
                        <style type="text/css">
                            body {
                                font-family: Arial, Verdana, Helvetica, sans-serif;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                    ' . $message . '
                    </body>
                    </html>';
                    // Also, for getting full html you may use the following internal method:
                    //$body = $this->email->full_html($subject, $message);
                    
                    $result = $this->email
                        ->from('admin@tpaccbms.com','TRANSPACC BMS')
                        ->reply_to('admin@tpaccbms.com','TRANSPACC BMS')    // Optional, an account where a human being reads.
                        ->to($to,$r_name)
                        ->bcc('naguwin@gmail.com')
                        //->bcc('tanbenghwa@gmail.com')
                        ->bcc('email@transpacc.com.my','Transpacc Emails')
                        ->subject($subject)
                        ->message($body)
                        ->send();
                }
            }
            $data_out['Data'] = array('message'=>'Task status updated Successfully!');
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();   
            echo json_encode(array($data_out));    
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Task status update error!'));  
            echo json_encode($data_out);   
        }
    } 
    
    function getTaskForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->taskID) {
            $data['Data'] = array('bms_task_forum'=>$this->bms_task_model->getTaskForum($inputs->taskID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task id is mandatory!'));            
        }   
        echo json_encode(array($data)); 
    }
    
    /*function task_forum_image_submit () {
        
        $data = array ();
        if(!empty($_FILES)){
                
            $task_file_upload_temp = $this->config->item('task_file_upload_temp');
            
            $this->load->library('upload');      
            
            $task_file_upload_temp['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize($task_file_upload_temp);
            if ( ! $this->upload->do_upload('file') ) {
                $data['Data'] = array();
                $data['Status'] = false;
                $data['ErrorLog'] = array(array('message'=>$this->upload->display_errors(),'file_info'=>$_FILES));                        
            } else {   
                $data['Data'] = array('file_name'=>$this->upload->data('file_name'));
                $data['Status'] = true;
                $data['ErrorLog'] = array();               
            }
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'File is not posted'));             
        }
        echo json_encode(array($data));
    }*/
    
    function setTaskForum () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs->task_id) && $inputs->task_id != '' && ((isset($inputs->chat_text) && $inputs->chat_text != '') || !empty($inputs->file))) {
            $task_id = $inputs->task_id;
            $chat_text = isset($inputs->chat_text) ? trim($inputs->chat_text) : '';
            $img_name = '';
            if(!empty($inputs->file)){
                
                $task_forum_upload = $this->config->item('task_forum_upload');
                $task_forum_upload['upload_path'] = $task_forum_upload['upload_path'].$task_id.'/'; 
                if(!is_dir($task_forum_upload['upload_path']));
                    @mkdir($task_forum_upload['upload_path'], 0777);
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');             
                
                rename($task_file_upload_temp['upload_path'].$inputs->file, $task_forum_upload['upload_path'].$inputs->file);
                $img_name = $inputs->file;                
                
            }
            /*if(!empty($_FILES) && $_FILES['file']['error'] == 0 ){
                $task_forum_upload = $this->config->item('task_forum_upload');
                
                $this->load->library('upload');
                $task_forum_upload['upload_path'] = $task_forum_upload['upload_path'].$task_id.'/'; 
                if(!is_dir($task_forum_upload['upload_path']));
                    @mkdir($task_forum_upload['upload_path'], 0777);
                    
                $task_forum_upload['file_name'] = date('dmYHis');
                $this->upload->initialize($task_forum_upload);
                
                if ( ! $this->upload->do_upload('file')) {                    
                     $this->upload->display_errors();       
                } else {//echo "<pre>";print_r($task_forum_upload);exit;
                    $img_name = $this->upload->data('file_name'); 
                }   
            }*/
            
            $this->bms_task_model->set_task_forum($task_id,$chat_text,$img_name,$inputs->staff_id);
            // for notification
            $this->bms_task_model->task_alert_insert ($task_id,$inputs->property_id,'3');
            $data['Data'] = array('bms_task_forum'=>$this->bms_task_model->getTaskForum($inputs->task_id));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
            echo json_encode(array($data));     
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Task forum update error!'));  
            echo json_encode($data_out);   
        }
    }
    
    function getTaskLog () {
        $inputs = json_decode(file_get_contents('php://input'));
        if($inputs->taskID) {
            $data['Data'] = array('bms_task_log'=>$this->bms_task_model->getTaskLog($inputs->taskID));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Task id is mandatory!'));            
        }     
        echo json_encode(array($data)); 
    }
    
    // Property Docs
    
    function getPropertyDocCategory () {
        $this->load->model('bms_property_model'); 
        $data['Data'] = array('bms_property_doc_cat'=>$this->bms_property_model->getPropertyDocCategory ());
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        echo json_encode(array($data));

    }
    
    function getMyPropertiesDocsJmb () {
        $this->load->model('bms_property_model'); 
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($property_id) {
            $doc_cat_id = isset($inputs->doc_cat_id) && trim($inputs->doc_cat_id) != '' ? trim ($inputs->doc_cat_id) : '';
            $data['Data'] = array('bms_task_log'=>$this->bms_property_model->getMyPropertiesDocsJmb($inputs->property_id,$doc_cat_id));
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Property id is mandatory!'));            
        }        
        echo json_encode(array($data)); 
    }
    
    function getNoficationCount() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        $n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';
            
        $data['Data'] = array('bms_notify_count'=>$this->notifications->get_notification_count($user_type,$user_id,$n_type));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        
        echo json_encode(array($data)); 
    }
    
    function getNoficationContent() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        $n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';
        
        if($n_type == 'sop') {
            $data['Data'] = array('bms_notify_count'=>$this->notifications->get_sop_notification_details($user_id));
        } else {
           $data['Data'] = array('bms_notify_count'=>$this->notifications->get_notification_details($user_type,$user_id,$n_type)); 
        }        
        
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        
        echo json_encode(array($data));
    }
    
    function getNoficationCountByCat() {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = $inputs->user_id;
        //$n_type = isset($inputs->n_type) && trim($inputs->n_type) != '' ? trim ($inputs->n_type) : '';
        $bms_notify_count['create'] = $this->notifications->get_notification_count($user_type,$user_id,'create');
        $bms_notify_count['update'] = $this->notifications->get_notification_count($user_type,$user_id,'update');
        $bms_notify_count['close'] = $this->notifications->get_notification_count($user_type,$user_id,'close');
        if(!empty($user_type) && $user_type == 'staff') {
            $bms_notify_count['sop'] = $this->notifications->get_sop_notification_count($user_id);
        }
        //$data['Data'] = array(array($bms_notify_count)); // 
        $data['Data'] = array(array('bms_notify_count_by_cat'=>$bms_notify_count));
        $data['Status'] = true;
        $data['ErrorLog'] = array();
        
        echo json_encode(array($data));
    }
    
    function getDailyReport() {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $report_date = isset($inputs->report_date) && trim($inputs->report_date) != '' ? trim ($inputs->report_date) : '';
        if($property_id && $report_date) {
            $report_year = date('Y',strtotime($report_date));
            $report_mon = date('m',strtotime($report_date));
            $file_name = "DR_".$property_id."_".date('Y-m-d', strtotime($report_date)).".pdf";
            $daily_report_path = $this->config->item('daily_report_path');
            $result = array ();
            
            if(file_exists($daily_report_path.$report_year.'/'.$report_mon.'/'.$file_name)) {
               $result['file_name'] = $file_name;
               $result['relative_path'] = $daily_report_path.$report_year.'/'.$report_mon.'/';
               $result['absoulte_path'] = 'https://www.tpaccbms.com/bms_uploads/daily_reports/'.$report_year.'/'.$report_mon.'/'.$file_name;
               $result = array($result); 
            }
            $data['Data'] = array('bms_report'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Property id is mandatory!'));            
        }        
        echo json_encode(array($data));  
    }
    
    function rTaskEntryList () {
        $inputs = json_decode(file_get_contents('php://input'));
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $desi_id = $inputs->desi_id;
        $staff_id = $inputs->staff_id;
        if($property_id) {
            $this->load->model('bms_sop_model');
            $result['sop_main'] = $this->bms_sop_model->get_sop_entry_list ('','',$property_id,$desi_id,$staff_id);
            /*if(!empty($result['sop_main'])) {
                foreach($result['sop_main'] as $key=>$val) {    
                    $result['sop_main'][$key]['entry_req'] = 1;
                    if(isset($val[strtolower(date('D'))]) && $val[strtolower(date('D'))] == 1) {
                        $start_date = $end_date = date('Y-m-d'); 
                        $result['sop_entry'][$val['sop_id']] =  $this->bms_sop_model->get_sop_entry ($val['sop_id'],$start_date,$end_date);
                    }
                    $today_date = new DateTime(date('Y-m-d'));
                    $start_date = new DateTime($val['start_date']); 
                    $due_date = isset($val['due_date']) && $val['due_date'] != '' && $val['due_date'] != '0000-00-00' && $val['due_date'] != '1970-01-01' ? new DateTime($val['due_date']) : '';
                                
                    if(isset($val[strtolower(date('D'))]) && $val[strtolower(date('D'))] == 1 && empty($result['sop_entry'][$val['sop_id']]) && $today_date >= $start_date && ((isset($val['no_due_date']) && $val['no_due_date'] == 1) || ($due_date != '' && $today_date <= $due_date)) ) {
                        $result['sop_main'][$key]['entry_req'] = 1;
                    } else {
                        $result['sop_main'][$key]['entry_req'] = 0;
                    }                    
                }
            } */
            $data['Data'] = array('routine_task_entry_list'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Missing mandatory inputs!'));            
        }        
        echo json_encode(array($data));     
    }
    
    function rTaskKeyIn () {
        $inputs = json_decode(file_get_contents('php://input'));
        $sop_id = $inputs->sop_id;
        if($sop_id) {
            $this->load->model('bms_sop_model');
            $result['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id);
            $today_date = new DateTime(date('Y-m-d'));
            $start_date = new DateTime($result['sop'][0]['start_date']); 
            $due_date = isset($result['sop'][0]['due_date']) && $result['sop'][0]['due_date'] != '' && $result['sop'][0]['due_date'] != '0000-00-00' && $result['sop'][0]['due_date'] != '1970-01-01' ? new DateTime($result['sop'][0]['due_date']) : '';
                                    
            /*if(!empty($result['sop']) && isset($result['sop'][0][strtolower(date('D'))]) && $result['sop'][0][strtolower(date('D'))] == 1 && $today_date >= $start_date && ((isset($result['sop'][0]['no_due_date']) && $result['sop'][0]['no_due_date'] == 1) || ($due_date != '' && $today_date <= $due_date))) {
                $result['start_date'] = $result['end_date'] = date('Y-m-d'); 
                $result['sop_entry'] =  $this->bms_sop_model->get_sop_entry ($result['sop'][0]['sop_id'],$result['start_date'],$result['end_date']);
                if(!empty($result['sop_entry'])){
                    redirect('index.php/bms_sop/entry_list?property_id='.$result['sop'][0]['property_id']);   
                }
            } */
            if(!empty($result['sop'])) {
                foreach($result['sop'] as $key=>$val) {
                    $result['sub_sop'] = $this->bms_sop_model->get_subsop ($val['sop_id']);
                }
            }
            $data['Data'] = array('routine_task_keyin'=>$result);
            $data['Status'] = true;
            $data['ErrorLog'] = array();
        } else {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Missing mandatory inputs!'));            
        }        
        echo json_encode(array($data));
            
    }
    
    function rTaskKeyInSubmit () {
        $inputs = json_decode(file_get_contents('php://input'));
        if(isset($inputs) && !empty($inputs)) {
            
            $this->load->model('bms_sop_model');
                                    
            if(!empty($inputs->sop_condi)) {
                $data['requirement_type'] = 'C';
                $data['requirement_val'] = $inputs->sop_condi;
            } 
            if(!empty($inputs->sop_reading)) {
                $data['requirement_type'] = 'R';
                $data['requirement_val'] = $inputs->sop_reading;
            } 
            $data['sop_id'] = $inputs->sop_id;
            $data['remarks'] = isset($inputs->remarks) ? trim($inputs->remarks) : '';   
            $data['entry_date'] = $data['entered_date'] = date("Y-m-d H:i:s");         
            
            $data['entered_by'] = $inputs->entered_by;
            $sop_entry_id = $this->bms_sop_model->set_sop_entry ($data);
            
            if(!empty($inputs->files)) {
                
                $sop_entry_upload = $this->config->item('sop_entry_upload'); 
                $sop_entry_upload['upload_path'] = $sop_entry_upload['upload_path'].'/'.$sop_entry_id.'/'; 
                if(!is_dir($sop_entry_upload['upload_path']));
                    @mkdir($sop_entry_upload['upload_path'], 0777);
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                
                foreach ($inputs->files as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $sop_entry_upload['upload_path'].$val);
                    $img_data['sop_entry_id'] = $sop_entry_id;
                    $img_data['img_name'] = $val; 
                    $this->bms_sop_model->set_sop_entry_image_name ($img_data);                              
                }                
            }
             
            if(!empty($inputs->sub_sop)){
                foreach($inputs->sub_sop as $key=>$val) {
                    $data_sub = array();
                    $data_sub['sop_sub_id'] = $val->sop_sub_id;
                    $data_sub['sop_id'] = $inputs->sop_id;
                    if(!empty($val->condi)) {
                        $data_sub['requirement_type'] = 'C';
                        $data_sub['requirement_val'] = isset($val->condi) ? $val->condi : ''; 
                    }
                    if(!empty($val->reading)) {
                        $data_sub['requirement_type'] = 'R';
                        $data_sub['requirement_val'] = isset($val->reading) ? $val->reading : ''; 
                    }
                    $data_sub['remarks'] = isset($val->remarks) ? trim($val->remarks) : '';                     
                    
                    $data_sub['entry_date'] = $data_sub['entered_date'] = date("Y-m-d H:i:s");                   
                    
                    $data_sub['entered_by'] = $inputs->entered_by;
                    $sop_sub_entry_id = $this->bms_sop_model->set_sop_sub_entry ($data_sub);
                    
                    if(!empty($val->files)) {
                
                        $img_data = array();
                        $sop_sub_entry_upload = $this->config->item('sop_sub_entry_upload');            
                
                        $sop_sub_entry_upload['upload_path'] = $sop_sub_entry_upload['upload_path'].'/'.$sop_sub_entry_id.'/'; 
                        if(!is_dir($sop_sub_entry_upload['upload_path']));
                            @mkdir($sop_sub_entry_upload['upload_path'], 0777);
                             
                        $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                        
                        foreach ($val->files as $key2=>$val2) {
                            rename($task_file_upload_temp['upload_path'].$val2, $sop_sub_entry_upload['upload_path'].$val2);
                            $img_data['sop_sub_entry_id'] = $sop_sub_entry_id;
                            $img_data['img_name'] = $val2; 
                            $this->bms_sop_model->set_sop_sub_entry_image_name ($img_data);                         
                        }                
                    }
                    
                } // end of each sub sop
            } // end of sop
            $data_r['Data'] = array('message'=>'Routine Task Entry Submitted Successfully!');
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();   
            echo json_encode(array($data_r));   
            
        } // end of inputs
        else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Routine Task Entry Submision error!'));  
            echo json_encode($data_r);   
        }        
        
    } // end of function
    
    function getDashboard () {
        
        $inputs = json_decode(file_get_contents('php://input'));
        //$task_id = isset($inputs->taskID) && trim($inputs->taskID) != '' ? trim ($inputs->taskID) : '';
        //$staff_id = isset($inputs->staff_idID) && trim($inputs->staffID) != '' ? trim ($inputs->staffID) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $user_type = isset($inputs->user_type) && trim($inputs->user_type) != '' ? trim ($inputs->user_type) : '';
        $desi_id = isset($inputs->desi_id) && trim($inputs->desi_id) != '' ? trim ($inputs->desi_id) : '';
        if($property_id && $user_type) {
            $data['open_task_count'] = $this->bms_task_model->getMinorTaskCount($property_id);
            $data['chart_data'] = '';
            for($i =1; $i <=5 ; $i++) {
                $data['chart_data'] .= $data['chart_data'] != '' ? ',' : '';
                $data['chart_data'] .= $this->bms_task_model->getOverDueTaskForDaysInter($property_id, $i);
            }
            if($user_type == 'jmb' || ($user_type == 'staff' && in_array($desi_id,$this->config->item('prop_doc_download_desi_id')))) {
                
                $collection_type = $this->config->item('collec_type');
                $start_date = $end_date = date('Y-m-d');
                foreach ($collection_type as $key=>$val) {
                    $data['today_collec'][$key] = $this->bms_task_model->getCollection($property_id, $val,$start_date,$end_date);
                }
                
                $start_date = date('Y-m-01');
                $till_date_collec_sum = 0;
                foreach ($collection_type as $key=>$val) {            
                    $data['till_collec'][$key] = $this->bms_task_model->getCollection($property_id, $val,$start_date,$end_date);
                    $till_date_collec_sum += $data['till_collec'][$key];
                }
                
                $data['monthly_collec'] = $this->bms_masters_model->getPropertyMonthlyCollec($property_id);
                
                $data['collec_percentage'] = $till_date_collec_sum > 0 && $data['monthly_collec'] > 0 ? round((($till_date_collec_sum * 100) / $data['monthly_collec']),2) : 0; 
            }
            $data_r['Data'] = array('bms_dashboard'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();   
            echo json_encode(array($data_r));   
            
        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));  
            echo json_encode($data_r);   
        }        
        //echo "<pre>";print_r($data);echo "</pre>";exit;    
    } 
    
    function getChatList () {
        
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $last_list_sync = isset($inputs->last_list_sync) && trim($inputs->last_list_sync) != '' ? trim ($inputs->last_list_sync) : '';
        $staff_id = isset($inputs->staff_id) && trim($inputs->staff_id) != '' ? trim ($inputs->staff_id) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($user_type) {
            
            $data['chat_list'] = $this->bms_masters_model->getChatList($user_type,$last_list_sync,$staff_id,$property_id);
            
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_synced'] =$date->format("Y-m-d H:i:s.u");
            
            $data_r['Data'] = array('bms_chat_list'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();   
            echo json_encode(array($data_r));  
            
        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));  
            echo json_encode($data_r);   
        }     
                
    }
    
    function getChatMessages () {
        
        $inputs = json_decode(file_get_contents('php://input'));
        $last_sync = isset($inputs->last_sync) && trim($inputs->last_sync) != '' ? trim ($inputs->last_sync) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        if($property_id) {
            
            $data['chat_messages'] = $this->bms_masters_model->getChatMessages($property_id,$last_sync);
            
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_synced'] =$date->format("Y-m-d H:i:s.u");
            
            $data_r['Data'] = array('bms_chat_list'=>$data);
            $data_r['Status'] = true;
            $data_r['ErrorLog'] = array();   
            echo json_encode(array($data_r));  
            
        } else {
            $data_r['Data'] = array();
            $data_r['Status'] = false;
            $data_r['ErrorLog'] = array(array('message'=>'Mandatory Field missing!'));  
            echo json_encode($data_r);   
        }     
                
    }
    
    function setChat () {
        $inputs = json_decode(file_get_contents('php://input'));
        $user_type = $inputs->user_type;
        $user_id = isset($inputs->user_id) && trim($inputs->user_id) != '' ? trim ($inputs->user_id) : '';        
        //$last_sync = isset($inputs->last_sync) && trim($inputs->last_sync) != '' ? trim ($inputs->last_sync) : '';
        $property_id = isset($inputs->property_id) && trim($inputs->property_id) != '' ? trim ($inputs->property_id) : '';
        $chat_text = isset($inputs->chat_text) ? trim($inputs->chat_text) : '';
                
        if($user_type && $property_id && $user_id) {
            $data['user_type'] = $user_type == 'jmb' ? 2 : 1;
            $data['property_id'] = $property_id;
            $data['user_id'] = $user_id;
            $data['chat_txt'] = $chat_text;
            $data['file_name'] = isset($inputs->file) ? $inputs->file : '';
            
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data['chat_date'] =$date->format("Y-m-d H:i:s.u");            
            $chat_id = $this->bms_masters_model->set_chat($data);
            if(!empty($inputs->file)){
                
                $chat_file_upload = $this->config->item('chat_file_upload');
                $chat_file_upload['upload_path'] = $chat_file_upload['upload_path'].$property_id.'/'; 
                if(!is_dir($chat_file_upload['upload_path']));
                    @mkdir($chat_file_upload['upload_path'], 0777);
                    
                /*$chat_file_upload['upload_path'] = $chat_file_upload['upload_path'].$chat_id.'/'; 
                if(!is_dir($chat_file_upload['upload_path']));
                    @mkdir($chat_file_upload['upload_path'], 0777);*/
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');             
                
                rename($task_file_upload_temp['upload_path'].$inputs->file, $chat_file_upload['upload_path'].$inputs->file);
                
            }
            
            /*$data_r['chat_messages'] = $this->bms_masters_model->getChatMessages($property_id,$last_sync);
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $data_r['chat_synced'] =$date->format("Y-m-d H:i:s.u");    */        
            
            $data_out['Data'] = array('message'=>'Chat Submitted Successfully!');
            $data_out['Status'] = true;
            $data_out['ErrorLog'] = array();
            echo json_encode(array($data_out));     
        } else {
            $data_out['Data'] = array();
            $data_out['Status'] = false;
            $data_out['ErrorLog'] = array(array('message'=>'Mandatory fields missing!'));  
            echo json_encode($data_out);   
        }
    }
    
} // End of class 