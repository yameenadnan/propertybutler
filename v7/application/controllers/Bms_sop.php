<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_sop extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        //if(!in_array($this->uri->segment(2), array('new_sop_submit','keyin_entry_submit','get_sop_report')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_sop_model');        
    }

    public function sop_list() {
		$data['browser_title'] = 'Property Butler | Routine Task';
        $data['page_header'] = '<i class="fa fa-server"></i> Routine Task List';
        
        
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
        //$data['os_offset'] = isset($_GET['os_offset']) && trim($_GET['os_offset']) != '' ? trim ($_GET['os_offset']) : 0;
        //$data['os_rows'] = isset($_GET['os_rows']) && trim($_GET['os_rows']) != '' ? trim ($_GET['os_rows']) : 10;
        
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
            $data['sop_main'] = $this->bms_sop_model->get_sop_main_jmb_mc('','',$_SESSION['bms']['property_id']);
            
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
            $data['sop_main'] = $this->bms_sop_model->get_sop_main ('','',$data['property_id']);
        }
        
        //$data['sop_main_os'] = $this->bms_sop_model->get_sop_main_os ('','',$data['property_id']);
        //echo "<pre>";print_r($data['sop_main']);echo "</pre>";
        $this->load->view('sop_list_view',$data);
	}
    
    function view_details ($property_id, $assign_to, $mode='view') {
        $data['mode'] = $mode;
        $data['browser_title'] = 'Property Butler | Routine Task Details';
        $data['page_header'] = '<i class="fa fa-server"></i> Routine Task Details';
        
        
        /*if($type == 'own')
            $data['sop_main'] = $this->bms_sop_model->get_sop_main ('','','',$assignment_id);
        else 
            $data['sop_main'] = $this->bms_sop_model->get_sop_main_os ('','','',$assignment_id);
        if(count($data['sop_main']) > 0) {
            $data['sop_os_desi'] = $this->bms_sop_model->get_sop_overseeing_desi ($assignment_id);
            $data['sop_os_jmb'] = $this->bms_sop_model->get_sop_overseeing_jmb ($assignment_id);
            $data['sop'] = $this->bms_sop_model->get_sop ($assignment_id);
            if(!empty($data['sop'])) {
                foreach($data['sop'] as $key=>$val) {
                    $data['sub_sop'][$key] = $this->bms_sop_model->get_subsop ($val['sopid']);
                }
            }
            $this->load->view('sop_details_view',$data);
        } else {
            redirect('index.php/bms_sop/sop_list');    
        }*/
        
        if(isset($property_id) && trim($property_id) != '' && isset($assign_to) && trim($assign_to) != '') {
            $data['sop'] = $this->bms_sop_model->get_sop_details ($property_id,$assign_to);
            if(!empty($data['sop'])) {
                foreach($data['sop'] as $key=>$val) {
                    $data['sub_sop'][$key] = $this->bms_sop_model->get_subsop ($val['sop_id']);
                }
            }
            $this->load->view('sop_details_view',$data);
        } else {
            redirect('index.php/bms_sop/sop_list');    
        }           
             
    }
    
    public function new_sop($sop_id = '') {
		$data['browser_title'] = 'Property Butler | Add Routine Task';
        $data['page_header'] = '<i class="fa fa-server"></i> Add Routine Task';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        //echo "<pre>";print_r($data['properties']); exit;
        $data['assign_to'] = array();
        if(isset($_GET['property_id']) && trim($_GET['property_id']) != '') {
            $data['assign_to'] = $this->bms_masters_model->getAssignTo ($_GET['property_id']);
            if(isset($_GET['assign_to']) && trim($_GET['assign_to']) != '' && !empty($sop_id)) {
                $data['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id);
                if(!empty($data['sop'])) {
                    foreach($data['sop'] as $key=>$val) {
                        $data['sub_sop'][$key] = $this->bms_sop_model->get_subsop ($val['sop_id']);
                    }
                }                
            }            
        }
        //echo "<pre>";print_r($data['sop']);echo "</pre>";
        $this->load->view('sop_new_view',$data);
	}
    
    function get_ov_jmb () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $ov_jmp = array ();
        if($property_id) {
            $ov_jmp = $this->bms_masters_model->getOvJMB ($property_id);       
        }
        echo json_encode($ov_jmp);
    }
    
    function new_sop_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        if(isset($_POST) && !empty($_POST['sop_glob'])) {
            
            $sop_glob = $this->input->post('sop_glob');
            
            if(!empty($_POST['sop'])) {
                $sop = $this->input->post('sop');
                foreach ($sop as $key=>$val) {
                    if(isset($val['start_date']) && trim ($val['start_date']) != '' && isset($val['sop_name']) && trim($val['sop_name']) != '') {
                        $val['property_id'] = $sop_glob['property_id'];
                        $val['assign_to']   = $sop_glob['assign_to'];
                        
                        if(isset($val['start_date']) && $val['start_date'] != '') 
                            $val['start_date'] = date('Y-m-d', strtotime($val['start_date']));
                        
                        if(isset($val['due_date']) && $val['due_date'] != '') {
                            $val['due_date'] = date('Y-m-d', strtotime($val['due_date']));
                            $val['no_due_date'] = 0;
                        } else {
                            $val['no_due_date'] = 1;    
                        }
                        $val['exclude_print'] = !empty($val['exclude_print']) ? $val['exclude_print'] : 0;
                         
                        if(isset($val['execute_time']) && $val['execute_time'] != '') 
                            $val['execute_time'] = date('H:i:s', strtotime($val['execute_time']));
                        if(isset($val['due_by']) && $val['due_by'] != '') 
                            $val['due_by'] = date('H:i:s', strtotime($val['due_by']));
                            
                        $val['mon'] = isset($val['mon']) && $val['mon'] == 1 ? 1 : 0;
                        $val['tue'] = isset($val['tue']) && $val['tue'] == 1 ? 1 : 0;
                        $val['wed'] = isset($val['wed']) && $val['wed'] == 1 ? 1 : 0;
                        $val['thu'] = isset($val['thu']) && $val['thu'] == 1 ? 1 : 0;
                        $val['fri'] = isset($val['fri']) && $val['fri'] == 1 ? 1 : 0;
                        $val['sat'] = isset($val['sat']) && $val['sat'] == 1 ? 1 : 0;
                        $val['sun'] = isset($val['sun']) && $val['sun'] == 1 ? 1 : 0;
                        if(isset($val['req_type']) && $val['req_type'] == 'reading_req'){
                            $val['reading_req'] = 1;
                            $val['condition_req'] = 0;
                        } else {
                            $val['reading_req'] = 0;
                            $val['condition_req'] = 1;
                        }
                        unset($val['req_type']);    
                               
                        if(isset($val['sop_id']) && $val['sop_id'] != '') {                            
                            $sop_id = $val['sop_id'];
                            $val['updated_by'] = $_SESSION['bms']['staff_id'];;
                            $val['updated_date'] = date('Y-m-d');
                            $this->bms_sop_model->sop_update($val);
                        } else {
                            $val['created_by'] = $_SESSION['bms']['staff_id'];;
                            $val['created_date'] = date('Y-m-d');
                            $sop_id = $this->bms_sop_model->sop_insert($val);
                        }  
                
                        if($sop_id && !empty($_POST['sub_sop'][$key])) {
                            $sub_sop = $_POST['sub_sop'][$key];
                            foreach($sub_sop as $key2=>$val2) {
                                if(isset($val2['sub_sop_name']) && trim($val2['sub_sop_name']) != '') {
                                    //$val2['AssignmentId'] = $sop_main_id;
                                    $val2['sop_id'] = $sop_id;
                                    if(isset($val2['req_type']) && $val2['req_type'] == 'reading_req'){
                                        $val2['reading_req'] = 1;
                                        $val2['condition_req'] = 0;
                                    } else {
                                        $val2['reading_req'] = 0;
                                        $val2['condition_req'] = 1;
                                    }
                                    unset($val2['req_type']);  
                                    
                                    if(isset($val2['sop_sub_id']) && $val2['sop_sub_id'] != '') {
                                        $this->bms_sop_model->sub_sop_update($val2);
                                    } else {
                                        $this->bms_sop_model->sub_sop_insert($val2);                                
                                    }
                                }
                            }
                        }
                    }
                }
            } 
            $_SESSION['flash_msg'] = 'SOP has been created / updated successfully!';            
        }
        redirect('index.php/bms_sop/view_details/'.$sop_glob['property_id'].'/'.$sop_glob['assign_to'].'/view');        
    }
    
    function delete_sop ($sop_id) {
        if(!empty($sop_id)) {
            /*$sub_sop = $this->bms_sop_model->get_subsop($sop_id);
            if(!empty($sub_sop)) {
                
            }*/
            $this->bms_sop_model->delete_sub_sop_by_sop_id ($sop_id);
            $this->bms_sop_model->delete_sop ($sop_id); 
            echo 1;
        } else {
            echo 0;
        }
    }
    
    function delete_sub_sop ($sop_sub_id) {
        if(!empty($sop_sub_id)) {
            /*$data['sop_sub_entry'] =  $this->bms_sop_model->get_sop_sub_entry_all ($sop_sub_id);
            
            if(!empty($data['sop_sub_entry'])) {
                foreach ($data['sop_sub_entry'] as $key2 => $val2) {
                    //foreach ($val2 as $key3 => $val3) {
                    $data['sop_sub_entry_img'] =  $this->bms_sop_model->get_sop_sub_entry_img ($val2['id']);
                    if(!empty($data['sop_sub_entry_img'])) {
                        foreach ($data['sop_sub_entry_img'] as $key3=>$val3) {
                            
                        }
                    }
                    //}
                    //echo "<pre>";print_r($data['sop_sub_entry_img'][$val2['id']]);echo "</pre>"; 
                }                    
            }*/
            $this->bms_sop_model->delete_sub_sop ($sop_sub_id);    
            echo 1;
        } else {
            echo 0;
        }
    }
    
    
    function entry_list () {
        $data['browser_title'] = 'Property Butler | Routine Task Entry List';
        $data['page_header'] = '<i class="fa fa-server"></i> Routine Task Entry List';
                
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
               
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['sop_main'] = $this->bms_sop_model->get_sop_entry_list ('','',$data ['property_id'],$_SESSION['bms']['designation_id'],$_SESSION['bms']['staff_id']);
        /*if(!empty($data['sop_main'])) {
            foreach($data['sop_main'] as $key=>$val) {                
                if(isset($val[strtolower(date('D'))]) && $val[strtolower(date('D'))] == 1) {
                    $data['start_date'] = $data['end_date'] = date('Y-m-d'); 
                    $data['sop_entry'][$val['sop_id']] =  $this->bms_sop_model->get_sop_entry ($val['sop_id'],$data['start_date'],$data['end_date']);
                }
            }
        }  */     
        //$data['sop_main_os'] = $this->bms_sop_model->get_sop_main_os ('','',$property_id);
        //echo "<pre>";print_r($data['sop_entry']);echo "</pre>";
        $this->load->view('sop_entry_list_view',$data);
    }
    
    function keyin_entry ($sop_id) {
        
        $data['browser_title'] = 'Property Butler | Routine Task Entry ';
        $data['page_header'] = '<i class="fa fa-server"></i> Routine Task Entry ';
                
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
               
        //$data['properties'] = $this->bms_masters_model->getMyProperties ();
        //$data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : '');
        //$data['sop_main'] = $this->bms_sop_model->get_sop_entry_list ('','',$data ['property_id']);
        $data['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id);
        $today_date = new DateTime(date('Y-m-d'));
        $start_date = new DateTime($data['sop'][0]['start_date']); 
        $due_date = isset($data['sop'][0]['due_date']) && $data['sop'][0]['due_date'] != '' && $data['sop'][0]['due_date'] != '0000-00-00' && $data['sop'][0]['due_date'] != '1970-01-01' ? new DateTime($data['sop'][0]['due_date']) : '';
        $data['start_date'] = $data['end_date'] = date('Y-m-d'); 
        
        $data['sop_entry'] =  $this->bms_sop_model->get_sop_entry ($data['sop'][0]['sop_id'],$data['start_date'],$data['end_date']);
        if(!empty($data['sop_entry'])){
            redirect('index.php/bms_sop/entry_list?property_id='.$data['sop'][0]['property_id']);   
        }
        
        if(!empty($data['sop'])) {
            foreach($data['sop'] as $key=>$val) {
                $data['sub_sop'][$key] = $this->bms_sop_model->get_subsop ($val['sop_id']);
            }
        } else {
            redirect('index.php/bms_sop/entry_list');                    
        }
               
        //$data['sop_main_os'] = $this->bms_sop_model->get_sop_main_os ('','',$property_id);
        //echo "<pre>";print_r($data['sop_main']);echo "</pre>";
        $this->load->view('sop_entry_view',$data);
    }
    
    function keyin_entry_submit () {
        //echo "<pre>";print_r($_FILES);echo "</pre>"; exit;
        $data['requirement_type'] = $_POST['requirement_type'];
        if(!empty($_POST['sop_condi'])) {
            //$data['requirement_type'] = 'C';
            $data['requirement_val'] = isset($_POST['sop_condi']) ? $_POST['sop_condi'] : '';
        } 
        if(!empty($_POST['sop_reading'])) {
            //$data['requirement_type'] = 'R';
            $data['requirement_val'] = isset($_POST['sop_reading']) ? $_POST['sop_reading'] : '';
        } 
        $data['sop_id'] = $_POST['sop_id'];
        $data['remarks'] = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';   
        if(!empty($_POST['ow_property_id']) && !empty($_POST['ow_report_date'])) {
            $data['entry_date'] = date("Y-m-d 17:00:00",strtotime($_POST['ow_report_date']));
            $data['entered_date'] = date("Y-m-d H:i:s"); 
            $data['is_overwtrite'] = 1; 
        } else {
            $data['entry_date'] = $data['entered_date'] = date("Y-m-d H:i:s"); 
        }
        
        
        $data['entered_by'] = $_SESSION['bms']['staff_id'];
        $sop_entry_id = $this->bms_sop_model->set_sop_entry ($data);
        
        $this->load->library('upload');
        $data['upload_err'] = array();
        if(!empty($_FILES['sop_img'])){
                
            $sop_entry_upload = $this->config->item('sop_entry_upload');            
            
            $sop_entry_upload['upload_path'] = $sop_entry_upload['upload_path'].'/'.$sop_entry_id; 
            if(!is_dir($sop_entry_upload['upload_path']));
                @mkdir($sop_entry_upload['upload_path'], 0777);
            foreach ($_FILES['sop_img']['name'] as $key=>$file) {
                
                $_FILES['temp_img']['name']= $_FILES['sop_img']['name'][$key];
                $_FILES['temp_img']['type']= $_FILES['sop_img']['type'][$key];
                $_FILES['temp_img']['tmp_name']= $_FILES['sop_img']['tmp_name'][$key];
                $_FILES['temp_img']['error']= $_FILES['sop_img']['error'][$key];
                $_FILES['temp_img']['size']= $_FILES['sop_img']['size'][$key];
                
                $sop_entry_upload['file_name'] = $sop_entry_id.'-'.date('dmYHis').'_'.rand(10000,99999);
                $this->upload->initialize($sop_entry_upload);
                //echo "<pre>";print_r($sop_entry_upload);exit;
                if ( ! $this->upload->do_upload('temp_img')) {
                    //if(count($_FILES) > 1)
                    //    echo $task_file_upload_err = 'One or more images are not uploaded!';
                    //else 
                    //    $task_file_upload_err = 'Image is not uploaded!';
                    array_push($data['upload_err'],$this->upload->display_errors());                        
                } else {
                    $img_data['sop_entry_id'] = $sop_entry_id;
                    $img_data['img_name'] = $this->upload->data('file_name'); 
                    $this->bms_sop_model->set_sop_entry_image_name ($img_data);
                }                  
            }
        }
        
        
        if(!empty($_POST['sub_sop'])){
            foreach($_POST['sub_sop'] as $key=>$val) {
                $data_sub = array();
                $data_sub['sop_sub_id'] = $val['sop_sub_id'];
                $data_sub['sop_id'] = $_POST['sop_id'];
                $data_sub['requirement_type'] = $val['requirement_type'];
                if(!empty($val['condi'])) {
                    //$data_sub['requirement_type'] = 'C';
                    $data_sub['requirement_val'] = isset($val['condi']) ? $val['condi'] : ''; 
                }
                if(!empty($val['reading'])) {
                    //$data_sub['requirement_type'] = 'R';
                    $data_sub['requirement_val'] = isset($val['reading']) ? $val['reading'] : ''; 
                }
                $data_sub['remarks'] = isset($val['remarks']) ? trim($val['remarks']) : '';   
                
                if(!empty($_POST['ow_property_id']) && !empty($_POST['ow_report_date'])) {
                    $data_sub['entry_date'] = date("Y-m-d 17:00:00",strtotime($_POST['ow_report_date']));
                    $data_sub['entered_date'] = date("Y-m-d H:i:s"); 
                    $data_sub['is_overwtrite'] = 1; 
                } else {
                    $data_sub['entry_date'] = $data_sub['entered_date'] = date("Y-m-d H:i:s"); 
                }
                
                $data_sub['entered_by'] = $_SESSION['bms']['staff_id'];
                $sop_sub_entry_id = $this->bms_sop_model->set_sop_sub_entry ($data_sub);
                
                if(!empty($_FILES['sop_sub_img']['name'][$key])){
                    
                    $img_data = array();
                    $sop_sub_entry_upload = $this->config->item('sop_sub_entry_upload');            
            
                    $sop_sub_entry_upload['upload_path'] = $sop_sub_entry_upload['upload_path'].'/'.$sop_sub_entry_id; 
                    if(!is_dir($sop_sub_entry_upload['upload_path']));
                        @mkdir($sop_sub_entry_upload['upload_path'], 0777);
                    foreach ($_FILES['sop_sub_img']['name'][$key] as $key2=>$file2) {
                        
                        $_FILES['temp_img']['name']= $_FILES['sop_sub_img']['name'][$key][$key2];
                        $_FILES['temp_img']['type']= $_FILES['sop_sub_img']['type'][$key][$key2];
                        $_FILES['temp_img']['tmp_name']= $_FILES['sop_sub_img']['tmp_name'][$key][$key2];
                        $_FILES['temp_img']['error']= $_FILES['sop_sub_img']['error'][$key][$key2];
                        $_FILES['temp_img']['size']= $_FILES['sop_sub_img']['size'][$key][$key2];
                        
                        $sop_sub_entry_upload['file_name'] = $sop_sub_entry_id.'-'.date('dmYHis').'_'.rand(10000,99999);
                        $this->upload->initialize($sop_sub_entry_upload);
                        //echo "<pre>";print_r($sop_sub_entry_upload);exit;
                        if ( ! $this->upload->do_upload('temp_img')) {
                            //if(count($_FILES) > 1)
                            //    echo $task_file_upload_err = 'One or more images are not uploaded!';
                            //else 
                            //    $task_file_upload_err = 'Image is not uploaded!';
                            array_push($data['upload_err'],$this->upload->display_errors());                        
                        } else {
                            $img_data['sop_sub_entry_id'] = $sop_sub_entry_id;
                            $img_data['img_name'] = $this->upload->data('file_name'); 
                            $this->bms_sop_model->set_sop_sub_entry_image_name ($img_data);
                        }                  
                    }
                }
            }
        }
        
        /*if($_SESSION['bms']['staff_id'] == 1273 || $_SESSION['bms']['staff_id'] == 1219) {
            echo "<pre>";print_r($_POST);print_r($_FILES);echo "</pre>"; exit;
        }*/
        if(!empty($_POST['ow_property_id']) && !empty($_POST['ow_report_date'])) {
            redirect('index.php/bms_daily_report/index?property_id='.$_POST['ow_property_id'].'&report_date='.$_POST['ow_report_date']);
        } else {
            $_SESSION['flash_msg'] = 'SOP Entry has been updated successfully!';
            redirect('index.php/bms_sop/entry_list?property_id='.$_POST['property_id']);
        }
        
    }
    
    
    function sop_history () {
        $data['browser_title'] = 'Property Butler | Routine Task History';
        $data['page_header'] = '<i class="fa fa-server"></i> Routine Task History';
                
        $data['offset'] = isset($_GET['offset']) && trim($_GET['offset']) != '' ? trim ($_GET['offset']) : 0;
        $data['rows'] = isset($_GET['rows']) && trim($_GET['rows']) != '' ? trim ($_GET['rows']) : 10;
               
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        //$data['sop_main'] = $this->bms_sop_model->get_sop_entry_list ('','',$data ['property_id']);
               
        //$data['sop_main_os'] = $this->bms_sop_model->get_sop_main_os ('','',$property_id);
        //echo "<pre>";print_r($data['sop_main']);echo "</pre>";
        $this->load->view('sop_history_view',$data);
    }
    
    function get_sop_title () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id')); 
        $sops = array();
        if($property_id) {
            $sops = $this->bms_sop_model->getSopTitle ($property_id);            
        }
        echo json_encode($sops);
    }
    
    function get_sop_for_desi_id () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id')); 
        $desi_id = trim($this->input->post('desi_id')); 
        $sops = array();
        if($property_id && $desi_id) {
            $sops = $this->bms_sop_model->get_sop ($property_id,$desi_id);            
        }
        echo json_encode($sops);
    }
    
    function get_sop_report () {
        $property_id = trim($this->input->post('property_id')); 
        $desi_id = trim($this->input->post('desi_id')); 
        $sop_id = trim($this->input->post('sop_id')); 
        $data['start_date'] = date('Y-m-d',strtotime(trim($this->input->post('start_date')))); 
        $data['end_date'] = date('Y-m-d',strtotime(trim($this->input->post('end_date')))); 
        //$sop = array();
        if($sop_id) {
            if($sop_id == 'all') {
                $data['sop'] = $this->bms_sop_model->get_sop ($property_id,$desi_id); 
            } else {
                $data['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id); 
            }
            
            if(!empty($data['sop'])) {
                foreach ($data['sop'] as $skey=>$sval) {
                    $data['sop_sub'][$sval['sop_id']] = $this->bms_sop_model->get_subsop ($sval['sop_id']);
                    $data['sop_entry'][$sval['sop_id']] =  $this->bms_sop_model->get_sop_entry ($sval['sop_id'],$data['start_date'],$data['end_date']);
                    if(!empty($data['sop_entry'][$sval['sop_id']])) {
                        foreach ($data['sop_entry'][$sval['sop_id']] as $val) {
                            $data['sop_entry_img'][$val['id']] =  $this->bms_sop_model->get_sop_entry_img ($val['id']);
                        }                    
                    }
                    if(!empty($data['sop_sub'][$sval['sop_id']])) {
                        foreach ($data['sop_sub'][$sval['sop_id']] as $val) {
                            $data['sop_sub_entry'][$val['sop_sub_id']] =  $this->bms_sop_model->get_sop_sub_entry ($val['sop_sub_id'],$data['start_date'],$data['end_date']);
                        
                            if(!empty($data['sop_sub_entry'][$val['sop_sub_id']])) {
                                foreach ($data['sop_sub_entry'][$val['sop_sub_id']] as $key2 => $val2) {
                                    //foreach ($val2 as $key3 => $val3) {
                                    $data['sop_sub_entry_img'][$val2['id']] =  $this->bms_sop_model->get_sop_sub_entry_img ($val2['id']);
                                    //}
                                    //echo "<pre>";print_r($data['sop_sub_entry_img'][$val2['id']]);echo "</pre>"; 
                                }                    
                            }
                        }
                        
                    }
                }
                
                //echo "<pre>";print_r($data['sop_sub_entry_img']);echo "</pre>"; 
                $this->load->view('sop_history_cont_view',$data);                
            }
                     
        }
    }
    
    function sop_copy () {
        $data['browser_title'] = 'Property Butler | Copy Routine Task';
        $data['page_header'] = '<i class="fa fa-server"></i> Copy Routine Task';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data['assign_to'] = $this->bms_masters_model->getDesignation ();
        
        //echo "<pre>";print_r($data['sop']);echo "</pre>";
        $this->load->view('sop_copy_view',$data);
        
    }
    
    function sop_copy_act () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $from_property_id = $this->input->post('from_property_id');
        $from_designation = $this->input->post('from_designation');
        $to_property_id = $this->input->post('to_property_id');
        $to_designation = $this->input->post('to_designation'); 
        //$properties = $this->bms_masters_model->getProperties();
        $sops = $this->bms_sop_model->get_sops_for_copy ($from_property_id,array($from_designation)); 
        
        if(!empty($sops)) {
            // copy sub sop's
            foreach ($sops as $key2=>$val2) {
                $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
                //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
            }
            //transfer sop's
            foreach ($sops as $key2=>$val2) {
                unset($val2['sop_id']);
                $val2['property_id'] = $to_property_id;
                $val2['assign_to'] = $to_designation;
                $val2['created_date'] = date('Y-m-d');
                $sop_id = $this->bms_sop_model->set_sops_for_copy ($val2);   
                if(!empty($sop_sub[$key2]['sub_sop'])) {
                    foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                        unset($val3['sop_sub_id']);
                        $val3['sop_id'] = $sop_id;
                        $this->bms_sop_model->sub_sop_insert($val3); 
                    }
                }                 
            }
            $_SESSION['flash_msg'] = 'Routine Task Copied successfully!';
        } else {
            $_SESSION['flash_msg'] = 'Routine Task Not Found!';
        }
        redirect('index.php/bms_sop/view_details/'.$to_property_id.'/'.$to_designation.'/view');        
            
    }
    
    function copy_sop () {
        
        // copy one to one
        /*$properties = $this->bms_masters_model->getProperties();
        $sops = $this->bms_sop_model->get_sops_for_copy (151,array(2,3,4,19)); 
        foreach ($sops as $key2=>$val2) {
            $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
            //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
        }
        //echo "<br />".count($sops);
        foreach ($properties as $key=>$val) {
            if(in_array($val['property_id'],array(156,159,160,161,163,164,165,166))) {
                //echo "<br />".$val['property_id'] . " =>".$val['property_name'];
                foreach ($sops as $key2=>$val2) {
                    unset($val2['sop_id']);
                    $val2['property_id'] = $val['property_id'];
                    $val2['created_date'] = date('Y-m-d');
                    $sop_id = $this->bms_sop_model->set_sops_for_copy ($val2);   
                    if(!empty($sop_sub[$key2]['sub_sop'])) {
                        foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                            unset($val3['sop_sub_id']);
                            $val3['sop_id'] = $sop_id;
                            $this->bms_sop_model->sub_sop_insert($val3); 
                        }
                    }                 
                }
            }
        }*/
        
        
        /*$properties = $this->bms_masters_model->getProperties();
        $sops = $this->bms_sop_model->get_sops_for_copy (151,array(2));
        foreach ($sops as $key2=>$val2) {
            $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
            //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
        }
        //echo "<br />".count($sops);
        foreach ($properties as $key=>$val) {
            if(!in_array($val['property_id'],array(151))) {
                //echo "<br />".$val['property_id'] . " =>".$val['property_name'];
                foreach ($sops as $key2=>$val2) {
                    unset($val2['sop_id']);
                    $val2['property_id'] = $val['property_id'];
                    $sop_id = $this->bms_sop_model->set_sops_for_copy ($val2);   
                    if(!empty($sop_sub[$key2]['sub_sop'])) {
                        foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                            unset($val3['sop_sub_id']);
                            $val3['sop_id'] = $sop_id;
                            $this->bms_sop_model->sub_sop_insert($val3); 
                        }
                    }                 
                }
            }
        }  
        */
        /*$sops = $this->bms_sop_model->get_sops_for_copy (151,array(2));
        foreach ($sops as $key2=>$val2) {
            $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
            //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
        }
        //echo "<br />".count($sops);
        foreach ($properties as $key=>$val) {
            if(in_array($val['property_id'],array(99,63,48,91,142,92))) {
                //echo "<br />".$val['property_id'] . " =>".$val['property_name'];
                foreach ($sops as $key2=>$val2) {
                    unset($val2['sop_id']);
                    $val2['property_id'] = $val['property_id'];
                    $sop_id = $this->bms_sop_model->set_sops_for_copy ($val2);   
                    if(!empty($sop_sub[$key2]['sub_sop'])) {
                        foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                            unset($val3['sop_sub_id']);
                            $val3['sop_id'] = $sop_id;
                            $this->bms_sop_model->sub_sop_insert($val3); 
                        }
                    }                 
                }
            }
        }  */
        
        /*$sops = $this->bms_sop_model->get_sops_for_copy (63,array(3,4,19));
        foreach ($sops as $key2=>$val2) {
            $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
            //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
        }
        //echo "<br />".count($sops);
        foreach ($properties as $key=>$val) {
            if(in_array($val['property_id'],array(79))) {
                //echo "<br />".$val['property_id'] . " =>".$val['property_name'];
                foreach ($sops as $key2=>$val2) {
                    unset($val2['sop_id']);
                    $val2['property_id'] = $val['property_id'];
                    $val2['assign_to'] = 3;
                    $sop_id = $this->bms_sop_model->set_sops_for_copy ($val2);   
                    if(!empty($sop_sub[$key2]['sub_sop'])) {
                        foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                            unset($val3['sop_sub_id']);
                            $val3['sop_id'] = $sop_id;
                            $this->bms_sop_model->sub_sop_insert($val3); 
                        }
                    }                 
                }
            }
        } */ 
        /*$i=1;
        $properties = array(array('property_id'=>63));
        $sops = $this->bms_sop_model->get_sops_for_copy (151,array(19));
        foreach ($sops as $key2=>$val2) {
            $sop_sub[$key2]['sub_sop'] = $this->bms_sop_model->get_subsop ($val2['sop_id']);
            //echo "<br />$key2   ".count($sop_sub[$key2]['sub_sop']);
        }
        
        //echo "<br />".count($sops);
           //echo "<br />".$val['property_id'] . " =>".$val['property_name'];
        foreach ($sops as $key2=>$val2) {
            echo "<br />".$i++;
            unset($val2['sop_id']);
            $val2['property_id'] = 63;
            //$val2['assign_to'] = 19;
            $sop_id = $this->bms_sop_model->sop_insert ($val2);   
            if(!empty($sop_sub[$key2]['sub_sop'])) {
                foreach($sop_sub[$key2]['sub_sop'] as $key3=>$val3) {
                    unset($val3['sop_sub_id']);
                    $val3['sop_id'] = $sop_id;
                    $this->bms_sop_model->sub_sop_insert($val3); 
                }
            }               
        }*/
            
           // exit;
        
        echo "Finished ALL";     
    }
}