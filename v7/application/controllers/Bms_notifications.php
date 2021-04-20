<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_notifications extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }       
        // load necessary models          
    }
    
    function notifications_list () {
        $this->load->model('bms_masters_model');
        $data['browser_title'] = 'Property Butler | Notification List';
        $data['page_header'] = '<i class="fa fa-bell-o"></i> Notification List';
        
        $property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';
        $data['n_type'] = isset($_GET['n_type']) && trim($_GET['n_type']) != '' ? trim ($_GET['n_type']) : 'create';
        //$data['notifications'] = $this->notifications->get_notification_details ();
        $user_id = $_SESSION['bms']['user_type'] == 'staff' ? $_SESSION['bms']['staff_id'] : $_SESSION['bms']['member_id'];
        $data['create_cnt'] = $this->notifications->get_notification_count ($_SESSION['bms']['user_type'],$user_id,'create',$property_id);
        $data['update_cnt'] = 0; //$this->notifications->get_notification_count ($_SESSION['bms']['user_type'],$user_id,'update',$property_id);
        $data['close_cnt'] = 0; //$this->notifications->get_notification_count ($_SESSION['bms']['user_type'],$user_id,'close',$property_id);
        //echo "<pre>";print_r($data);echo "</pre>";
        if($_SESSION['bms']['user_type'] == 'staff') {
            $data['sop_cnt'] = 0;// $this->notifications->get_sop_notification_count ($_SESSION['bms']['staff_id'],$property_id);
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }
        else 
        $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
            
        //$data['sop_notifications'] = $this->notifications->get_sop_notification_details ($_SESSION['bms']['staff_id']);
            
        $this->load->view('notifications_view',$data);
    }
    
    
    function get_notify_count () { 
        $user_id = $_SESSION['bms']['user_type'] == 'staff' ? $_SESSION['bms']['staff_id'] : $_SESSION['bms']['member_id'];        
        echo $this->notifications->get_notification_count($_SESSION['bms']['user_type'],$user_id);             
    }
    
    function get_notification_content () { 
        header('Content-type: application/json'); 
        $user_type = $_POST['staff_type'];      
        $user_id = $_POST['staff_id'];  
        $n_type = $_POST['n_type'];  
        $property_id = $_POST['property_id'];
        if($n_type == 'sop') {
            echo json_encode($this->notifications->get_sop_notification_details($user_id,$property_id));
        } else {
            echo json_encode($this->notifications->get_notification_details($user_type,$user_id,$n_type,$property_id));
        }    
                       
    }
    
    
    function to_do_list ($offset=0, $per_page = 25) {
        
        //$this->load->model('bms_masters_model');
        $data['browser_title'] = 'Property Butler | Personal Assistant - Reminder List';
        $data['page_header'] = 'Personal Assistant - Reminder List';
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;      
        
        $this->load->view('to_do/to_do_view',$data);
    }
    
    function get_todo_content () {       
        
        header('Content-type: application/json');        
        
        $result = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : '';
            
            $staff_id = $this->input->post('staff_id');
            $status_type = $this->input->post('status_type');
            
            $result['numFound'] = $this->notifications->get_todo_count ($staff_id,$status_type,$search_txt);
            if($result['numFound'] > 0) {
                $result['records'] = $this->notifications->get_todo_cont ($staff_id,$_POST['offset'], $_POST['rows'],$status_type,$search_txt);
            }
            
        }       
        echo json_encode($result);
	
    }
    
    function add_todo ($todo_id = '') {
        $data['todo_id'] = $todo_id;
        
        //$this->load->model('bms_masters_model');
        $data['browser_title'] = 'Property Butler | Personal Assistant - Reminder';
        $data['page_header'] = 'Personal Assistant - Reminder <i class="fa fa-angle-double-right"></i> New';
        
        if(!empty($todo_id)) {
            $data['todo_info'] = $this->notifications->get_todo_details($todo_id);
        }
        $this->load->view('to_do/to_do_form_view',$data);
    }
    
    function add_todo_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $to_do = $this->input->post('to_do');
        $to_do['complete_date'] = date('Y-m-d',strtotime($to_do['complete_date'])); 
        $type = 'add';
        if(!empty($_POST['todo_id'])){
            $type = 'edit';
            $this->notifications->update_todo ($to_do,$_POST['todo_id']);
        } else {
            $this->notifications->insert_todo ($to_do);
        }
        $_SESSION['flash_msg'] = 'Personal Assistant - Reminder '. ($type == 'edit' ? 'Updated' : 'Added') .' successfully!';
        redirect('index.php/bms_notifications/to_do_list/0/25?status_type=0');
    }
    
    function set_todo_done () {
        $todo_id = $this->input->post('todo_id');
        if(!empty($todo_id)) {
            $to_do['actual_complete_date'] = date('Y-m-d');
            $to_do['status'] = 1;
            $this->notifications->update_todo ($to_do,$todo_id);
            echo date('d-m-Y');
        } else {
            echo false;
        }
    }
    
}