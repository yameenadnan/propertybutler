<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//ob_start();
class Bms_incident extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        //unset($_SESSION['bms']);
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //if(!in_array($this->uri->segment(2), array('get_task_forum','get_os_task_list','get_blocks','get_unit','assign_to','task_image_submit','task_image_remove')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_incident_model','bms_incident_model');
    }

    public function incident_list() {
        // echo "<pre>";print_r($_SESSION);echo "</pre>";
        $data['browser_title'] = 'Property Butler | Incident List';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Incident List';
        $search_txt = '';
        if(isset($_GET['search_txt']) && trim($_GET['search_txt']) != '') {
            $search_txt = trim($_GET['search_txt']);
        }



        $data['hr_access_desi'] = $this->config->item('hr_access_desi');
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');

        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;

        if(in_array($_SESSION['bms']['designation_id'],$data['hr_access_desi']))
            $data['incident'] = $this->bms_incident_model->get_all_incident ($_SESSION['bms']['staff_id'], $data['property_id'], $data['offset'], $data['rows']);
        else
            $data['incident'] = $this->bms_incident_model->get_all_incident ($_SESSION['bms']['staff_id'], $data['property_id'], $data['offset'], $data['rows']);

            $data['properties'] = $this->bms_masters_model->getMyProperties ();



            $task_status = isset($_GET['task_status']) && trim($_GET['task_status']) != '' ? trim ($_GET['task_status']) : '';
            $task_sear_id = isset($_GET['search_id']) && trim($_GET['search_id']) != '' ? trim ($_GET['search_id']) : '';
            $task_search_txt = isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim ($_GET['search_txt']) : '';
            $sort_by = isset($_GET['sort_by']) && trim($_GET['sort_by']) != '' ? trim ($_GET['sort_by']) : 'due_date';
            $this->load->view('incident/incident_list_view',$data);

    }

    public function new_incident( $incident_id = '' ) {
        $data['browser_title'] = 'Property Butler | Add Incident';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Add Incident';

        $data['hr_access_desi'] = $this->config->item('hr_access_desi');
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');

        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }

        if( !empty( $incident_id ) ) {
            $data['incident'] = $this->bms_incident_model->get_incident_detals ( $incident_id );
            $data['incident_images'] = $this->bms_incident_model->get_image_detals ( $incident_id );
            //$data['property_chang_hist'] = $this->bms_property_model->getPropertyChargHist ($property_id);
            //$data['property_chang_hist_arr'] = !empty($data['property_chang_hist']) ? explode(',',$data['property_chang_hist']['charg_hist_cat']) : array();

            //$data['property_prem_quit_hist'] = $this->bms_property_model->getPropertyPremQuitHist ($property_id);
            //$data['property_prem_quit_hist_arr'] = !empty($data['property_chang_hist']) ? explode(',',$data['property_prem_quit_hist']['cat']) : array();

        }

        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('incident/incident_new_view',$data);
    }

    function new_incident_submit ()
    {
        // echo "<pre>";print_r($_POST);echo "</pre>"; echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        if (isset($_POST) && !empty($_POST['incident'])) {
            $incident = $this->input->post('incident');
            $incident['incident_date'] = date('Y-m-d', strtotime($incident['incident_date']));
            /// $val
            if (isset($_POST['incident_id']) && $_POST['incident_id'] != '') {
                $incident_id = $this->input->post('incident_id');
                $incident['updated_by'] = $_SESSION['bms']['staff_id'];;
                $incident['updated_date'] = date('Y-m-d');
                $this->bms_incident_model->incident_update($incident, $incident_id);
                $insert_id = $incident_id;
            } else {
                $incident['created_by'] = $_SESSION['bms']['staff_id'];;
                $incident['created_date'] = date('Y-m-d');
                $insert_id = $this->bms_incident_model->incident_insert($incident);
            }
            if(!empty($_POST['files'])) {

                $task_file_upload = $this->config->item('incident_file_upload');
                $task_file_upload['upload_path'] = $task_file_upload['upload_path'].$insert_id.'/';
                if(!is_dir($task_file_upload['upload_path']));
                @mkdir($task_file_upload['upload_path'], 0777);

                $task_file_upload_temp = $this->config->item('incident_file_upload_temp');

                foreach ($_POST['files'] as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $task_file_upload['upload_path'].$val);
                    $img_data['incident_id'] = $insert_id;
                    $img_data['img_name'] = $val;
                    $this->bms_incident_model->incident_image_name_insert ($img_data);
                }
            }
        }
        redirect('index.php/bms_incident/incident_list');
    }


    function get_task_list () {
        header('Content-type: application/json');
        $tasks = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $property_id = isset($_POST['property_id']) && $_POST['property_id'] != '' ? $_POST['property_id'] : '';
            $task_status = isset($_POST['task_status']) && $_POST['task_status'] != '' ? $_POST['task_status'] : ''; 
            $task_sear_id = isset($_POST['search_id']) && trim($_POST['search_id']) != '' ? trim ($_POST['search_id']) : '';
            $task_search_txt = isset($_POST['search_txt']) && trim($_POST['search_txt']) != '' ? trim ($_POST['search_txt']) : '';
            $sort_by = isset($_POST['sort_by']) && trim($_POST['sort_by']) != '' ? trim ($_POST['sort_by']) : 'due_date';
            $tasks = $this->bms_task_model->get_task ('own',$_SESSION['bms']['designation_id'],$_SESSION['bms']['staff_id'],$_POST['offset'],$_POST['rows'],$property_id,$task_status,$task_sear_id,$task_search_txt,$sort_by);                
        }
        echo json_encode($tasks);        
    }

    function incident_image_submit () {
        //header('Content-type: application/json');
        //echo "<pre>";print_r($_POST);print_r($_FILES);echo "</pre>";
        $data = array ();
        if(!empty($_FILES)){

            $task_file_upload_temp = $this->config->item('incident_file_upload_temp');

            $this->load->library('upload');

            $_FILES['temp_img']['name']= $_FILES['addRequestFile1']['name'][0];
            $_FILES['temp_img']['type']= $_FILES['addRequestFile1']['type'][0];
            $_FILES['temp_img']['tmp_name']= $_FILES['addRequestFile1']['tmp_name'][0];
            $_FILES['temp_img']['error']= $_FILES['addRequestFile1']['error'][0];
            $_FILES['temp_img']['size']= $_FILES['addRequestFile1']['size'][0];

            $task_file_upload_temp['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize($task_file_upload_temp);
            //echo "<pre>";print_r($task_file_upload_temp);exit;
            if ( ! $this->upload->do_upload('temp_img') ) {
                //if(count($_FILES) > 1)
                //    echo $task_file_upload_temp_err = 'One or more images are not uploaded!';
                //else
                //    $task_file_upload_temp_err = 'Image is not uploaded!';
                $data['upload_err'] = $this->upload->display_errors();
            } else {
                $data['name'] = $this->upload->data('file_name');
            }

        }
        echo json_encode($data);
    }

    
    function get_os_task_list () {
        header('Content-type: application/json');
        $os_tasks = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $property_id = isset($_POST['property_id']) && $_POST['property_id'] != '' ? $_POST['property_id'] : '';
            $task_status = isset($_POST['task_status']) && $_POST['task_status'] != '' ? $_POST['task_status'] : ''; 
            $task_sear_id = isset($_POST['search_id']) && trim($_POST['search_id']) != '' ? trim ($_POST['search_id']) : '';
            $task_search_txt = isset($_POST['search_txt']) && trim($_POST['search_txt']) != '' ? trim ($_POST['search_txt']) : '';
            $sort_by = isset($_POST['sort_by']) && trim($_POST['sort_by']) != '' ? trim ($_POST['sort_by']) : 'due_date';
            if($_SESSION['bms']['user_type'] == 'jmb') {
                $os_tasks = $this->bms_task_model->get_task_with_num_rows_jmb ($property_id,$task_status,$_POST['offset'],$_POST['rows'],$task_sear_id,$task_search_txt,$sort_by);
            } else {
                $os_tasks = $this->bms_task_model->get_task_with_num_rows ('oversee',$_SESSION['bms']['designation_id'],$_SESSION['bms']['staff_id'],$_POST['offset'],$_POST['rows'],$property_id,$task_status,$task_sear_id,$task_search_txt,$sort_by);
            }                
        }
        echo json_encode($os_tasks);        
    }
    
    function get_blocks () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id')); 
        $blocks = array();
        if($property_id) {
            $blocks = $this->bms_masters_model->getBlocks ($property_id);            
        }
        echo json_encode($blocks);
    }
    
    function get_unit () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $block_id = trim($this->input->post('block_id')); 
        
        $unit = array();         
        if($property_id) {
            $unit = $this->bms_masters_model->getUnit ($property_id,$block_id);            
        }
        echo json_encode($unit);
    }
    
    function assign_to () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $assign_to = array ();
        if($property_id) {
            $assign_to = $this->bms_masters_model->getAssignTo ($property_id);       
        }
        echo json_encode($assign_to);
    }
    
    function re_assign ($task_id,$property_id) {
        $data['task_id'] = $task_id;
        $data['property_id'] = $property_id;
        $data['assign_to'] = $this->bms_masters_model->getAssignTo ($property_id);   
        $this->load->view('task_reassign_view',$data);
    }
    function set_reassign () {
        $task = $this->input->post('task');
        $this->bms_task_model->set_re_assign($task,$task['task_id']);
        $_SESSION['flash_msg'] = 'Task Re-assigned successfully!';
        //}
        redirect('index.php/bms_task/task_list/'); 
    }
    
    public function new_task($type = '') {
        
		$data['browser_title'] = 'Property Butler | Add Minor Task';
        $data['page_header'] = '<i class="fa fa-info-circle"></i> Add Minor Task';
        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }            
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        $this->load->view('task_new_view',$data);
	}
    
    function task_image_submit () {
        //header('Content-type: application/json');
        //echo "<pre>";print_r($_POST);print_r($_FILES);echo "</pre>";
        $data = array ();
        if(!empty($_FILES)){
                
            $task_file_upload_temp = $this->config->item('task_file_upload_temp');
            
            $this->load->library('upload');
            
            $_FILES['temp_img']['name']= $_FILES['addRequestFile1']['name'][0];
            $_FILES['temp_img']['type']= $_FILES['addRequestFile1']['type'][0];
            $_FILES['temp_img']['tmp_name']= $_FILES['addRequestFile1']['tmp_name'][0];
            $_FILES['temp_img']['error']= $_FILES['addRequestFile1']['error'][0];
            $_FILES['temp_img']['size']= $_FILES['addRequestFile1']['size'][0];
            
            $task_file_upload_temp['file_name'] = date('dmYHis').'_'.rand(10000,99999);
            $this->upload->initialize($task_file_upload_temp);
            //echo "<pre>";print_r($task_file_upload_temp);exit;
            if ( ! $this->upload->do_upload('temp_img') ) {
                //if(count($_FILES) > 1)
                //    echo $task_file_upload_temp_err = 'One or more images are not uploaded!';
                //else 
                //    $task_file_upload_temp_err = 'Image is not uploaded!';
                $data['upload_err'] = $this->upload->display_errors();                        
            } else {                
                $data['name'] = $this->upload->data('file_name');               
            }                  
            
        }
        echo json_encode($data);
    }
    
    function task_image_remove () {
        if(!empty($_POST['file'])){            
            $task_file_upload_temp = $this->config->item('task_file_upload_temp');
            if(file_exists($task_file_upload_temp['upload_path'].$_POST['file']))
                @unlink($task_file_upload_temp['upload_path'].$_POST['file']);            
        }
    }
    
    function new_task_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        if(isset($_POST) && !empty($_POST['task'])) {
            //$this->load->model('bms_task_model');
            $task = $this->input->post('task');
            $task['created_date'] = date('Y-m-d');
            if(isset($task['due_date']) && $task['due_date'] != '') 
                $task['due_date'] = date('Y-m-d', strtotime($task['due_date']));
            else 
                $task['due_date'] = '';
                
            $task['task_status'] = 'O';
            $task['created_by'] = $_SESSION['bms']['staff_id'];
            $task['created_by_type'] = $_SESSION['bms']['staff_id'] == '0' ? 'J' : 'S';
            $insert_id = $this->bms_task_model->task_insert($task);
            
            if(!empty($_POST['files'])){
                
                $task_file_upload = $this->config->item('task_file_upload');
                $task_file_upload['upload_path'] = $task_file_upload['upload_path'].$insert_id.'/'; 
                if(!is_dir($task_file_upload['upload_path']));
                    @mkdir($task_file_upload['upload_path'], 0777);
                    
                $task_file_upload_temp = $this->config->item('task_file_upload_temp');
                
                foreach ($_POST['files'] as $key=>$val) {
                    rename($task_file_upload_temp['upload_path'].$val, $task_file_upload['upload_path'].$val);
                    $img_data['task_id'] = $insert_id;
                    $img_data['img_name'] = $val; 
                    $this->bms_task_model->task_image_name_insert ($img_data);                 
                                   
                }
                
            }
            
            // for task log
            $this->bms_task_model->set_task_create_log($insert_id,$_SESSION['bms']['staff_id'],$task['due_date']);
            
            // for notification
            $this->bms_task_model->task_alert_insert ($insert_id,$task['property_id'],'1');
            
            $_SESSION['flash_msg'] = 'Task has been created successfully!';            
            
            // push notification 
            $this->notifications->sendPushNotification($task['property_id'],$_POST['property_name'],$task['task_name']);          
            
            // Notification emil to resident for task creation
            if(!empty($_POST['resident_email_hidd'])) {
                
                $property_info = $this->bms_masters_model->getPropertyInfo ($task['property_id']);
                
                $to = $_POST['resident_email_hidd'];
                $r_name = !empty($_POST['resident_name_hidd']) ? $_POST['resident_name_hidd'] : '';
                
                $task_cat = $this->config->item('task_cat');
                $source_assign = $this->config->item('source_assign');
                
                
                $this->load->library('email');
    
                $subject = $_POST['task']['task_name'] .' | '. $_POST['property_name'];
                $message = '<p>To <b>';
                if(!empty($_POST['resident_gender_hidd'])) {
                    $message .= $_POST['resident_gender_hidd'] == 'Male' ? 'Mr ' : ($_POST['resident_gender_hidd'] == 'Female' ? 'Ms ' : '');
                }                
                $message .= $r_name;                
                $message .= ',</b><br /><br />';                
                
                $message .= 'We thank you for your '.$source_assign[$_POST['task']['task_source']].' highlighting the ';
                $message .= $task_cat[$_POST['task']['task_category']] . '. A task has been created as per below description. We keep this task as our highest priority and looking forward to solve as soon as possible. We will notify you when the task is solved for your kind reference.<br /><br />';
                
                $message .= '<b>Task Id:</b> '.str_pad($insert_id, 5, '0', STR_PAD_LEFT) .'<br />';
                $message .= '<b>Task Name:</b> '.$_POST['task']['task_name'] .'<br />';
                $message .= '<b>Task Location:</b> '.(!empty($_POST['task']['task_location']) ? $_POST['task']['task_location'] : ' - ' ) .'<br />';
                $message .= '<b>Task Details:</b> '.(!empty($_POST['task']['task_details']) ? $_POST['task']['task_details'] : ' - ' ) .'<br /><br />';
                
                $message .= 'Thank you,<br />Transpacc <br />'.$_POST['property_name'];  
                            
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
                    ->from($property_info['email_addr'],'TRANSPACC BMS')
                    ->reply_to($property_info['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                    ->to($to,$r_name)
                    ->bcc('naguwin@gmail.com','Nagarajan')
                    //->bcc('tanbenghwa@gmail.com')
                    ->bcc('email@transpacc.com.my','Transpacc Emails')
                    ->subject($subject)
                    ->message($body)
                    ->send();
                    
            }
                
        }
        if(isset($_POST) && !empty($_POST['task']) && empty($data['upload_err'])) {
            $qry_str = '';
            /*if($_POST['action'] == 'save_print') {
                //echo "<script>window.open('".base_url('index.php/bms_task/print_task/'.$insert_id)."','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no')</script>";
                $qry_str = '?act=print&task_id='.$insert_id;                
            } */
            //echo "<script>window.location.href='".base_url('index.php/bms_task/task_list/')."';</script>";
            redirect('index.php/bms_task/task_list'.$qry_str);
        }
    }
    
    function task_details ($task_id) {
       
        $data['browser_title'] = 'Property Butler | Minor Task Details';
        $data['page_header'] = '<i class="fa fa-info-circle"></i>  Minor Task Details';
       
        $data['task_id'] = $task_id;
        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['task_details'] = $this->bms_task_model->get_task_details_jmb_mc ($task_id,$_SESSION['bms']['property_id']);
        } else {
            $data['task_details'] = $this->bms_task_model->get_task_details($task_id,$_SESSION['bms']['staff_id']);
        }
        if(empty($data['task_details'])) {
            redirect('index.php/bms_task/task_list');
        }
        
        if($data['task_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['task_details']->block_id);
        $data['task_images'] = $this->bms_task_model->get_task_images($task_id);
        
        // remove from notification
        $user_id = $_SESSION['bms']['user_type'] == 'staff' ? $_SESSION['bms']['staff_id'] : $_SESSION['bms']['member_id'];
        $this->bms_task_model->task_alert_delete ($task_id,$_SESSION['bms']['user_type'],$user_id);
        /*if($data['task_details']->UnitNo)
            $data['unit_details'] = $this->bms_masters_model->getUnitDetails($data['task_details']->UnitNo);*/
        //echo "<pre>";print_r($data['block_street']);echo "</pre>";
        //echo "<pre>";print_r($data['task_details']);echo "</pre>";
        $this->load->view('task_details_view',$data);
    }
    
    function print_task ($task_id) {
        $data['task_id'] = $task_id;        
        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['task_details'] = $this->bms_task_model->get_task_details_jmb_mc ($task_id,$_SESSION['bms']['property_id']);
        } else {
            $data['task_details'] = $this->bms_task_model->get_task_details($task_id,$_SESSION['bms']['staff_id']);
        }       
        if($data['task_details']->block_id)
            $data['block_street'] = $this->bms_masters_model->getBlock($data['task_details']->block_id);
        $data['task_images'] = $this->bms_task_model->get_task_images($task_id);
        $this->load->view('task_details_print_view',$data);
    }
    
    function set_task_status () {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        if(isset($_POST['task_id']) && $_POST['task_id'] != '') {
            $task_id = $_POST['task_id'];
            if(isset($_POST['task_update']) && $_POST['task_update'] == 'Closed') {
                $data['task_status'] = 'C';
                $data['task_close_remarks'] = trim($_POST['close_rem']);
                // for notification
                //$this->bms_task_model->task_alert_insert ($task_id,$_POST['property_id'],'4');
            } else if (isset($_POST['task_update']) && $_POST['task_update'] != '') {
                $data['task_update'] = trim($_POST['task_update']);
                if(isset($_POST['due_date']) && $_POST['due_date'] != '') {
                    $data['due_date'] = date('Y-m-d', strtotime($_POST['due_date']));
                } 
                // for notification
                //$this->bms_task_model->task_alert_insert ($task_id,$_POST['property_id'],'2');
            }
            $data['updated_by'] = $_SESSION['bms']['staff_id'];
            $data['updated_date'] = date("Y-m-d");
            $this->bms_task_model->set_task_update_with_log($task_id,$data,$_SESSION['bms']['staff_id']);
            
            // Notification emil to resident for task close
            if(isset($_POST['task_update']) && $_POST['task_update'] == 'Closed') {
                $unit_details = $this->bms_task_model->get_task_details_for_email($task_id);
                //echo "<pre>";print_r($unit_details);echo "</pre>";
                if(!empty($unit_details) && !empty($unit_details['email_addr'])) {
                    //$property_info = $this->bms_masters_model->getPropertyInfo ($task['property_id']);
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
                    
                    $message .= 'We are pleased to inform you that your complaint has been resolved. Please refer to below task details and remarks.<br /><br />';
                    
                    $message .= '<b>Task Id:</b> '.str_pad($unit_details['task_id'], 5, '0', STR_PAD_LEFT) .'<br />';
                    $message .= '<b>Task Name:</b> '.$unit_details['task_name'] .'<br />';
                    $message .= '<b>Task Location:</b> '.(!empty($unit_details['task_location']) ? $unit_details['task_location'] : ' - ' ) .'<br />';
                    $message .= '<b>Task Details:</b> '.(!empty($unit_details['task_details']) ? $unit_details['task_details'] : ' - ' ) .'<br />';
                    $message .= '<b>Close Remarks:</b> '.(!empty($_POST['close_rem']) ? $_POST['close_rem'] : ' - ' ) .'<br /><br />';
                    
                    $message .= 'We hope you are pleased with our service. Should you have any other comments pertaining to this task, please do contact our management office. ';
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
                        ->from($unit_details['email_addr'],'TRANSPACC BMS')
                        ->reply_to($unit_details['email_addr'],'TRANSPACC BMS')    // Optional, an account where a human being reads.
                        ->to($to,$r_name)
                        ->bcc('naguwin@gmail.com')
                        //->bcc('tanbenghwa@gmail.com')
                        ->bcc('email@transpacc.com.my','Transpacc Emails')
                        ->subject($subject)
                        ->message($body)
                        ->send();
                }
            }
            echo true;                     
        } else {
            echo false;
        }
    }
    
    function set_task_forum () {
        //echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        if(isset($_POST['task_id']) && $_POST['task_id'] != '' && ((isset($_POST['chat_text']) && $_POST['chat_text'] != '') || (!empty($_FILES) && $_FILES['attach']['error'] == 0))) {
            $task_id = $_POST['task_id'];
            $chat_text = trim($_POST['chat_text']);
            $img_name = '';
            if(!empty($_FILES) && $_FILES['attach']['error'] == 0 ){
                $task_forum_upload = $this->config->item('task_forum_upload');
                
                $this->load->library('upload');
                $task_forum_upload['upload_path'] = $task_forum_upload['upload_path'].$task_id.'/'; 
                if(!is_dir($task_forum_upload['upload_path']));
                    @mkdir($task_forum_upload['upload_path'], 0777);
                    
                $task_forum_upload['file_name'] = date('dmYHis');
                $this->upload->initialize($task_forum_upload);
                
                if ( ! $this->upload->do_upload('attach')) {                    
                     $this->upload->display_errors();       
                } else {//echo "<pre>";print_r($task_forum_upload);exit;
                    $img_name = $this->upload->data('file_name'); 
                }   
            }
            
            $this->bms_task_model->set_task_forum($task_id,$chat_text,$img_name,$_SESSION['bms']['staff_id']);
            // for notification
            //$this->bms_task_model->task_alert_insert ($task_id,$_POST['property_id'],'3');
            echo true;            
        } else {
            echo false;
        }
    }
    
    function get_task_forum ($task_id) {
        $res = $this->bms_task_model->getTaskForum($task_id);
        //echo "<pre>";print_r($res); echo "</pre>";
        if(!empty($res)) {
            foreach ($res as $key=>$val) {
                $margin = $key == 0 ? '5px 0 15px 0' : '15px 0';
                echo '<div class="row" style="margin:'.$margin.';">';
                echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.($val['comment_by'] == '0' ? 'JMB / MC ' : ($val['comment_by'] == '-1' ? 'Resident ' : $val['first_name'] .' '.(!empty($val['last_name']) ? $val['last_name'] : ''))).' </span> on '.date('d-m-Y h:i:s a',strtotime($val['comment_date']));
                if($val['img_name'] != '') {
                    echo '&ensp;&ensp;<a href="../../../bms_uploads/task_forum_upload/'.$val['task_id'].'/'.$val['img_name'].'" target="_blank" title="view/Download">Attachment</a>';                    
                }
                echo '</div>';
                echo '<div class="col-md-12">'.$val['comment'].'</div>';
                echo "</div>";
                
            }            
        } 
    }
    
    function get_log_details ($task_id) {
        $res = $this->bms_task_model->getTaskLog($task_id);
        //echo "<pre>";print_r($res); echo "</pre>";
        if(!empty($res)) {
            foreach ($res as $val) {
                echo '<div class="row" style="margin:15px 0;">';
                echo '<div class="col-md-12"><span style="color:#000;font-weight:bold">'.($val['staff_id'] == '0' ? 'JMB / MC ' : $val['first_name']).' </span> on '.date('d-m-Y h:i:s a',strtotime($val['entry_date'])).'</div>';
                echo '<div class="col-md-12">'.$val['description'].'</div>';
                echo "</div>";
                
            }            
        } else {
            echo '<div class="">No Record Found.</div>';
        }
    }
    
    function email_test () {
        
        $this->load->library('email');

        $subject = 'This is a test mail';
        $message = '<p>This message has been sent for testing purposes.</p>';
        
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
            ->from('admin@propertybutler.my')
            ->reply_to('admin@propertybutler.my')    // Optional, an account where a human being reads.
            ->to('naguwin@gmail.com')
            ->subject($subject)
            ->message($body)
            ->send();
        
        var_dump($result);
        echo '<br />';
        echo $this->email->print_debugger();
        
        exit;
        
        /*$this->load->library('mailersend');
        $this->load->library('email_template');  
        $this->email_template->send_test_email(); */     
    }
    
    function testNotification () {
        //$this->notifications->sendPushNotification();
        echo $url = 'https://fcm.googleapis.com/fcm/send';//'https://android.googleapis.com/gcm/send';
        $registration_ids = array ('dmi4nty_YZM:APA91bHukPC6ds8xRJ4ly3-ZuJ52Nop6ycxLwBH-wck7mNooCfUcjz7PGRfkYVtDW0rWkeOF0raQUkSUeDEmFETxsljHlrInPbEaHrwSyBbC9EmKuf84z7jG_oRGRB8PdZUPjwNB8XmE'); //dNznetGaOVI:APA91bHXJ1wFVxsOYGcZGpR4HakIX_25srS1rNaJfdoTeQZbEHTtVS3rtXsLnH9pq1LkZQyoUbdfXftZNDyjXLcg5NxbhfhZaogKEVw9n8jG-RZJ2xDuG2LfB1Jfs6v-749yXeBGHUym
        $batch_count  = 5;
        $title = 'Test Notification';
        $body = 'Please ignore this notification';
        $fields = array(
            'registration_ids' => $registration_ids,
            'notification' => array('title' => $title,
                                    'body' => $body,
                                    'sound' => 'default',
                                    'badge' => $batch_count,
                                    'count' =>$batch_count),
            'data' => array('push_type' => 'Chat',
                                    'push_data' => array('FromId' => '1003',
                                                         'ToId' => '1024',
                                                         'FromType' => 'ME',
                                                         'ToType' => 'ME',
                                                         'MsgText' => 'How is this notification!',
                                                         'FileURL' =>'',
                                                         'ReadStatus'=>'0'))
        );

        //define('GOOGLE_API_KEY', 'AIzaSyCjctNK2valabAWL7rWUTcoRA-UAXI_3ro');
    
        $headers = array(
            'Authorization:key=AAAAeZ_p7LE:APA91bH2MZha6bOXdcONf7yDiYrpflmVnMy_HNgofjM3XICW4GyC8KPQ5medrqpLF19z2TxsMFz_nsYFvTTqyKrV_dgkhaL0nX_YbJ_MmsFI_QtLMx0dFz95-4ph3u12OjT2ioVrqpJ7',
            'Content-Type: application/json'
        );
        //echo json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
        $result = curl_exec($ch);
        if($result === false)
            die('Curl failed ' . curl_error());
    
        curl_close($ch);
        echo "<br />123". $result;
    }       
     
    
}