<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_attendance extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //if(!in_array($this->uri->segment(2), array('capture_save')))
        //    $this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_attendance_model'); 
         
    }

    public function capture() {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
        /*if($_SESSION['bms']['staff_id'] == 1273 || $_SESSION['bms']['staff_id'] == 1219) {
            //echo "<pre>";print_r($_SERVER); exit;
            $this->load->library('mobile_detect');
            //require_once APPPATH.'third_party/Mobile_Detect.php';
            //$detect = new Mobile_Detect;
            //$data = $this->mobile_detect->isMobile();
            //echo "<pre>";print_r($data); exit;
            if($this->mobile_detect->isMobile()) {
                echo "mobile detected";
            } else {
                echo "It is not a mobile!";
            }
            exit;
            
            
        }
        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) {
            redirect('index.php/bms_dashboard/index'); exit;            
        }*/
        
		$data['browser_title'] = 'Property Butler | Attendance Capture';
        $data['page_header'] = '<i class="fa fa-book"></i> Attendance Capture';
        $data['last_capture'] = $this->bms_attendance_model->getMyAttendanceLastCapture();
        $data['my_properties'] = $this->bms_masters_model->getMyProperties ();
        //echo "<pre>";print_r($data['last_capture']);
        $this->load->view('attendance/attendance_capture_view',$data);
	}
    
    public function capture_save() {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		/*$filename =  time() . '.jpg';
        $filepath = 'attendance/';
         
        move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename);
         
        echo $filepath.$filename.'?'.$_GET['capture_for'].$_GET['remarks'];*/
        $data['upload_err'] = array();
        $attendance_capture_upload = $this->config->item('attendance_capture_upload');
        
        $attendance_capture_upload['upload_path'] = $attendance_capture_upload['upload_path'].date('Y').'/'; 
        //echo $attendance_capture_upload['upload_path'];
        //echo is_dir($attendance_capture_upload['upload_path']) ? ' directory exist' : 'directory not exist'; 
        if(!is_dir($attendance_capture_upload['upload_path']))
            @mkdir($attendance_capture_upload['upload_path'], 0777);
            
        $attendance_capture_upload['upload_path'] = $attendance_capture_upload['upload_path'].date('m').'/'; 
        if(!is_dir($attendance_capture_upload['upload_path']))
            @mkdir($attendance_capture_upload['upload_path'], 0777);
        
        $attendance_capture_upload['upload_path'] = $attendance_capture_upload['upload_path'].date('m.d.Y').'/'; 
        //echo $attendance_capture_upload['upload_path'];
        //echo is_dir($attendance_capture_upload['upload_path']) ? ' directory exist' : 'directory not exist'; 
        if(!is_dir($attendance_capture_upload['upload_path']));
            @mkdir($attendance_capture_upload['upload_path'], 0777);
        //chmod($attendance_capture_upload['upload_path'], 0777); 
        
        $attendance_capture_upload['file_name'] = date('His').'_'.$_SESSION['bms']['staff_id'];
        $this->load->library('upload',$attendance_capture_upload);
        //echo "<pre>";print_r($task_file_upload);exit;
        if ( ! $this->upload->do_upload('webcam')) {            
            array_push($data['upload_err'],$this->upload->display_errors());                        
        }
        if(empty($data['upload_err'])){
            $attend['img_name'] = $this->upload->data('file_name');
            $attend['in_out_type'] = $_GET['capture_for'];
            $attend['remarks'] = trim($_GET['remarks']);
            $attend['property_id'] = trim($_GET['property_id']);
            $attend['attn_date'] = date("Y-m-d");
            $attend['atten_time'] = date("H:i:s");
            $attend['staff_id'] = $_SESSION['bms']['staff_id'];
            $this->bms_attendance_model->attendance_insert($attend);
            $attendance_capture_for = $this->config->item('attendance_capture_for');
            echo '../../'.$attendance_capture_upload['upload_path'].$attend['img_name'].'~~~'.date('d-m-Y', strtotime($attend['attn_date'])).'~~~'.date('h:i:s a', strtotime($attend['atten_time'])).'~~~'.$attendance_capture_for[$attend['in_out_type']].'~~~'.($attend['remarks'] != '' ? $attend['remarks'] : ' - ');
            //$_SESSION['flash_msg'] = 'Document has been Uploaded Successfully!';            
        } else {
            echo 'error~~~'.'Document upload Error Message: '.$this->upload->display_errors() . $attendance_capture_upload['upload_path'];
            //$_SESSION['error_msg'] = 'Document upload Error Message: '.$this->upload->display_errors(); 
            
        }
	}
    
    function get_live_time () { echo date('d-m-Y h:i:s a'); }
    
    public function report() { 
        $data['browser_title'] = 'Property Butler | Attendance Report';
        $data['page_header'] = '<i class="fa fa-book"></i> Attendance Report';
        //$data['last_capture'] = $this->bms_attendance_model->getMyAttendanceLastCapture();
        if(in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi'))) 
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        //echo "<pre>";print_r($data['last_capture']);
        $this->load->view('attendance/attendance_report_view',$data);
    } 
    
    function get_staff_name_by_property () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $staff_id = $_SESSION['bms']['staff_id'];
        $assign_to = array ();
        if($property_id) {
            if($property_id == 'all' && in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_SA_desi')))
                $assign_to = $this->bms_masters_model->getStaffNamesForSA ($property_id,$staff_id);
            else 
                $assign_to = $this->bms_masters_model->getStaffNamesByProperty ($property_id,$staff_id);       
        }
        echo json_encode($assign_to);
    }
    
    function get_staff_attendance () {
        $start_date = date('Y-m-d',strtotime(trim($this->input->post('start_date'))));
        $end_date = date('Y-m-d',strtotime(trim($this->input->post('end_date'))));
        $property_id = trim($this->input->post('property_id'));
        $staff_id = trim($this->input->post('staff_id'));
        $act_type = trim($this->input->post('act_type'));
        if($property_id != '0' && in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi'))) {
            if($staff_id != 'all') 
                $res = $this->bms_attendance_model->get_staff_attendance($start_date,$end_date,$staff_id);
            else if($staff_id == 'all' && $property_id == 'all')
                if(in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_SA_desi')))
                    $staff_ids = $this->bms_attendance_model->get_all_staff_ids_for_SA();
                else 
                    $staff_ids = $this->bms_attendance_model->get_all_staff_ids();
                if(!empty($staff_ids)) {
                    foreach ($staff_ids as $s_key=>$s_val) {
                        $res_mul[$s_val['staff_id']] = $this->bms_attendance_model->get_staff_attendance($start_date,$end_date,$s_val['staff_id']);
                    }
                }
            else if($staff_id == 'all' && $property_id != 'all') {
                $staff_ids = $this->bms_attendance_model->get_staff_ids($property_id);
                if(!empty($staff_ids)) {
                    foreach ($staff_ids as $s_key=>$s_val) {
                        $res_mul[$s_val['staff_id']] = $this->bms_attendance_model->get_staff_attendance($start_date,$end_date,$s_val['staff_id']);
                    }
                }                
            }               
                
        } else if($property_id == '0') {
            $res = $this->bms_attendance_model->get_staff_attendance($start_date,$end_date,$staff_id);
        }
        $header = $str = '';
        if(isset($act_type) && $act_type == 'download_excel') {
            if($staff_id != 'all') {
                $staff_name = $this->bms_attendance_model->get_staff_name($staff_id);
                $header .= '<span style="font-weight:bold">Name: </span>';
                $header .= '<span>'.$staff_name['first_name'] .(!empty($staff_name['last_name']) ? ' '. $staff_name['last_name']: '') .'</span><br />';  
            }
            
            $header .= '<span style="font-weight:bold">Date: </span>';
            $header .= '<span>'.$this->input->post('start_date'). ' - ' . $this->input->post('end_date') .'</span><br />';                       
        }
        
        
        $header .= '<table id="example2" class="table table-bordered table-hover table-striped" '.(isset($act_type) && $act_type == 'download_excel' ? 'border="1"' : '').'>
                <thead>
                <tr>
                  <th>Date</th>
                  <th>Staff Name</th>
                  <th>Designation</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Status</th>
                  <th>Remarks</th>';
        if(isset($act_type) && $act_type != 'download_excel') 
        $header .= '<th>Images</th>';
        
        $header .='</tr>
                </thead>
                <tbody>';
        if(!empty($res) || !empty($res_mul)) {
            
            $startTime = strtotime( $start_date .' 12:00' );
            $endTime = strtotime( $end_date .' 12:00' );
            
            if(!empty($res_mul)) {
                foreach($res_mul as $res) {
                    $str .= $this->build_table($startTime,$endTime,$res,$act_type);                    
                }
            } else {
                $str .= $this->build_table($startTime,$endTime,$res,$act_type);
            }
        } 
        if($str == '') {
            $str = $header . '<tr><td colspan="7" class="text-center">No Record Found</td></tr>';
        } else $str = $header . $str;
        $str .= '</tbody> </table>';
        $str .= '<p class="help-block" style="margin:0 15px;"> * OIT = Other InTime, * OOT = Other OutTime</p>';
        if(isset($act_type) && $act_type == 'download_excel') {
            header("Content-type: application/vnd.ms-excel");  
            header('Content-disposition: attachment; filename="staff_attendance_'.date('m.d.Y H:i:s').'.xls"');           
        }
        echo $str;
        
       
    }
    
    function build_table ($startTime,$endTime,$res,$act_type) {
        $str = '';
        // Loop between timestamps, 24 hours at a time
                for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
                        $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
                        $staff_name = $design = $atten_date = $remarks = $status = $in_img = $out_img = '';
                        $intime = $outtime = ' - ';
                        foreach ($res as $key=>$val) {
                            if($thisDate == $val['attn_date']) {
                                
                                if($val['in_out_type'] == '1'  && $intime == ' - ') {
                                    $staff_name = $val['first_name']. ' '. $val['last_name'];
                                    $design = $val['desi_name'];
                                    $atten_date = $val['attn_date'];
                                    $remarks .= ($remarks != '' ? '<br />' : ''). ($val['remarks'] != '' ? '<b>In Time:</b> '.$val['remarks'] : '');
                                    $intime = $val['atten_time'];
                                    $in_img = $val['img_name'];
                                    
                                } else if($val['in_out_type'] == '2') {
                                    $staff_name = $val['first_name']. ' '. $val['last_name'];
                                    $design = $val['desi_name'];
                                    $atten_date = $val['attn_date'];
                                    $remarks .= ($remarks != '' ? '<br />' : ''). ($val['remarks'] != '' ? '<b>Out Time:</b> '.$val['remarks'] : '');
                                    $outtime = $val['atten_time'];
                                    $out_img = $val['img_name'];
                                } else if($val['in_out_type'] == '3') {
                                    $staff_name = $val['first_name']. ' '. $val['last_name'];
                                    $design = $val['desi_name'];
                                    $atten_date = $val['attn_date'];
                                    if(isset($val['atten_time']) && $val['atten_time'] != '') {
                                        $remarks .= ($remarks != '' ? '<br />' : '');                                        
                                        $remarks .= '<b>OIT:</b>';
                                        if(isset($val['img_name']) && $val['img_name'] != '' && isset($act_type) && $act_type != 'download_excel')
                                            $remarks .= '<a href="javascript:;" class="atten_img" data-date="'.date('Y', strtotime($val['attn_date'])).'/'.date('m', strtotime($val['attn_date'])).'/'.date('m.d.Y', strtotime($val['attn_date'])).'" data-value="'.$val['img_name'].'" data-att-type="Other In" data-att-time="'.date('d-m-Y', strtotime($val['attn_date'])).' '.date('h:i A', strtotime($val['atten_time'])).'" > ';
                                        $remarks .= date('h:i A', strtotime($val['atten_time']));
                                        if(isset($val['img_name']) && $val['img_name'] != '' && isset($act_type) && $act_type != 'download_excel')
                                            $remarks .='</a> ';
                                        $remarks .= ($val['remarks'] != '' ? ' ('.$val['remarks'] .') ' : '');                                       
                                    }
                                                                        
                                } else if($val['in_out_type'] == '4') {
                                    $staff_name = $val['first_name']. ' '. $val['last_name'];
                                    $design = $val['desi_name'];
                                    $atten_date = $val['attn_date'];
                                    if(isset($val['atten_time']) && $val['atten_time'] != '') {
                                        $remarks .= ($remarks != '' ? '<br />' : '');                                        
                                        $remarks .= '<b>OOT:</b>';
                                        if(isset($val['img_name']) && $val['img_name'] != '' && isset($act_type) && $act_type != 'download_excel')
                                            $remarks .= '<a href="javascript:;" class="atten_img" data-date="'.date('Y', strtotime($val['attn_date'])).'/'.date('m', strtotime($val['attn_date'])).'/'.date('m.d.Y', strtotime($val['attn_date'])).'" data-value="'.$val['img_name'].'" data-att-type="Other Out" data-att-time="'.date('d-m-Y', strtotime($val['attn_date'])).' '.date('h:i A', strtotime($val['atten_time'])).'" > ';
                                        $remarks .= date('h:i A', strtotime($val['atten_time']));
                                        if(isset($val['img_name']) && $val['img_name'] != '' && isset($act_type) && $act_type != 'download_excel')
                                            $remarks .='</a> ';
                                        $remarks .= ($val['remarks'] != '' ? ' ('.$val['remarks'] .') ' : '');                                       
                                    }                                    
                                }
                            }
                        }
                        if($intime != ' - ' || $outtime != ' - ') {
                            $status = 'Present';
                        } else {
                            $status = 'Absent';
                        }
                        if($staff_name != '' && $design != '') {
                            $str .= '<tr><td class="col-md-1">'.date('d-m-Y', strtotime($atten_date)).'</td>
                                    <td class="col-md-2">'.$staff_name.'</td>
                                    <td class="col-md-1">'.$design.'</td>
                                    <td class="col-md-1">'.($intime != ' - ' ? date('h:i A', strtotime($intime)) : $intime).'</td>
                                    <td class="col-md-1">'.($outtime != ' - ' ? date('h:i A', strtotime($outtime)) : $outtime).'</td>
                                    <td class="col-md-1">'.$status.'</td>
                                    <td class="col-md-3">'.($remarks != '' ? $remarks : ' - ').'</td>';
                          
                            if(isset($act_type) && $act_type != 'download_excel') {
                                $str .= '<td class="col-md-2">';
                                if($in_img != ''){
                                    $in_img = '<a href="javascript:;" class="atten_img" data-date="'.date('Y', strtotime($atten_date)).'/'.date('m', strtotime($atten_date)).'/'.date('m.d.Y', strtotime($atten_date)).'" data-value="'.$in_img.'" data-att-type="In Time" data-att-time="'.date('d-m-Y', strtotime($atten_date)).' '.date('h:i A', strtotime($intime)).'" > Sign In </a>';                    
                                }
                                if($out_img != '') {
                                    $out_img = '<a href="javascript:;" class="atten_img" data-date="'.date('Y', strtotime($atten_date)).'/'.date('m', strtotime($atten_date)).'/'.date('m.d.Y', strtotime($atten_date)).'" data-value="'.$out_img.'" data-att-type="Out Time" data-att-time="'.date('d-m-Y', strtotime($atten_date)).' '.date('h:i A', strtotime($outtime)).'">Sign Out</a>';
                                } 
                                $str .= $in_img != '' && $out_img != '' ? $in_img . ' | '. $out_img : ($in_img != '' ? $in_img : $out_img);
                                $str .= '</td>';
                              
                            }
                            $str .='</tr>';
                        }
                    }
                return $str;
    }
    
    function absence_report () {
        $data['browser_title'] = 'Property Butler | Absence Report';
        $data['page_header'] = '<i class="fa fa-book"></i> Absence Report';
        //$data['last_capture'] = $this->bms_attendance_model->getMyAttendanceLastCapture();
        if(!in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi')))
            redirect('index.php/bms_dashboard/index'); 
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        //echo "<pre>";print_r($data['last_capture']);
        $this->load->view('attendance/absence_report_view',$data);
    }
    
    function get_staff_absence () {
        
        $report_date = date('Y-m-d',strtotime(trim($this->input->post('report_date'))));        
        $property_id = trim($this->input->post('property_id'));        
        $act_type = trim($this->input->post('act_type'));
        
        if($property_id == 'all') {            
            $staff_ids = $this->bms_attendance_model->get_all_staff_ids_for_SA();            
            if(!empty($staff_ids)) {
                foreach ($staff_ids as $s_key=>$s_val) {
                    $res_mul[$s_val['staff_id']]['atten_info'] = $this->bms_attendance_model->get_staff_attendance_date($report_date,$s_val['staff_id']);
                    $res_mul[$s_val['staff_id']]['staff_info'] = $s_val;
                }
            }
        } else {
            $staff_ids = $this->bms_attendance_model->get_staff_ids($property_id);
            if(!empty($staff_ids)) {
                foreach ($staff_ids as $s_key=>$s_val) {
                    $res_mul[$s_val['staff_id']]['atten_info'] = $this->bms_attendance_model->get_staff_attendance_date($report_date,$s_val['staff_id']);
                    $res_mul[$s_val['staff_id']]['staff_info'] = $s_val;
                }
            }                
        }               
        //echo "<pre>";print_r($res_mul);echo "</pre>"; exit; 
        
        if($property_id != 'all') {
            $property_info = $this->bms_masters_model->getPropertyInfo ($property_id);        
        } 
        
        $str = '';
        if(isset($act_type) && $act_type == 'download_excel') {
            if($property_id != 'all') {
                
                $str .= '<span style="font-weight:bold">Property Name: </span>';
                $str .= '<span>'. ($property_id == 'all' ? 'All' : $property_info['property_name']) .'</span><br />';  
            }
            
            $str .= '<span style="font-weight:bold">Date: </span>';
            $str .= '<span>'.$this->input->post('report_date') .'</span><br />';                       
        }
              
        $str .= '<table id="example2" class="table table-bordered table-hover table-striped" '.(isset($act_type) && $act_type == 'download_excel' ? 'border="1"' : '').'>
                <thead>
                <tr>
                  <th style="text-align:center">S No</th>
                  <th>Staff Name</th>
                  <th>Designation</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                  <th>Status</th>
                  <th>Remarks</th>';      
        if(!empty($res_mul)) {
            $i = 1;
            foreach ($res_mul as $key=>$val) {
                
                if(empty($val['atten_info'])) {
                    $str .= '<tr><td class="col-md-1" style="text-align:center">'.$i++ .'</td>';
                    $str .= '<td class="col-md-3">'.$res_mul[$key]['staff_info']['first_name']. ' '. $res_mul[$key]['staff_info']['last_name'].'</td>';
                    $str .= '<td class="col-md-2">'.$res_mul[$key]['staff_info']['desi_name'].'</td>';
                    $str .= '<td class="col-md-1"> - </td>';
                    $str .= '<td class="col-md-1"> - </td>';
                    $str .= '<td class="col-md-2"> Absent </td>';
                    $str .= '<td class="col-md-2"> - </td>';
                    $str .= '</tr>';
                } else {
                    $in_out_type_arr = array_column($res_mul[$key]['atten_info'],'in_out_type');
                    if((($report_date != date('Y-m-d')) && (!in_array(1,$in_out_type_arr) || !in_array(2,$in_out_type_arr))) || ($report_date == date('Y-m-d')) && (!in_array(1,$in_out_type_arr))) {
                        $status = !in_array(1,$in_out_type_arr) ? 'Absent (No In Time)' : 'Absent (No Out Time)';
                        $staff_name = $design = $atten_date = $remarks = '';
                        $intime = $outtime = ' - ';
                        foreach ($res_mul[$key]['atten_info'] as $key2=>$val2) {                               
                            if($val2['in_out_type'] == '1') {
                                $staff_name = $val2['first_name']. ' '. $val2['last_name'];
                                $design = $val2['desi_name'];
                                $atten_date = $val2['attn_date'];
                                $remarks .= ($remarks != '' ? '<br />' : ''). ($val2['remarks'] != '' ? '<b>In Time:</b> '.$val2['remarks'] : '');
                                $intime = $val2['atten_time'];
                                $in_img = $val2['img_name'];
                                
                            } else if($val2['in_out_type'] == '2') {
                                $staff_name = $val2['first_name']. ' '. $val2['last_name'];
                                $design = $val2['desi_name'];
                                $atten_date = $val2['attn_date'];
                                $remarks .= ($remarks != '' ? '<br />' : ''). ($val2['remarks'] != '' ? '<b>Out Time:</b> '.$val2['remarks'] : '');
                                $outtime = $val2['atten_time'];
                                $out_img = $val2['img_name'];
                            } else if($val2['in_out_type'] == '3') {
                                $staff_name = $val2['first_name']. ' '. $val2['last_name'];
                                $design = $val2['desi_name'];
                                $atten_date = $val2['attn_date'];
                                if(isset($val2['atten_time']) && $val2['atten_time'] != '') {
                                    $remarks .= ($remarks != '' ? '<br />' : '');                                        
                                    $remarks .= '<b>OIT:</b>';
                                    $remarks .= date('h:i A', strtotime($val2['atten_time']));
                                    $remarks .= ($val2['remarks'] != '' ? ' ('.$val2['remarks'] .') ' : '');                                       
                                }
                                                                    
                            } else if($val2['in_out_type'] == '4') {
                                $staff_name = $val2['first_name']. ' '. $val2['last_name'];
                                $design = $val2['desi_name'];
                                $atten_date = $val2['attn_date'];
                                if(isset($val2['atten_time']) && $val2['atten_time'] != '') {
                                    $remarks .= ($remarks != '' ? '<br />' : '');                                        
                                    $remarks .= '<b>OOT:</b>';
                                    $remarks .= date('h:i A', strtotime($val2['atten_time']));
                                    $remarks .= ($val2['remarks'] != '' ? ' ('.$val2['remarks'] .') ' : '');                                       
                                }                                    
                            }                            
                        }
                        
                        if($staff_name != '' && $design != '') {
                            $str .= '<tr><td class="col-md-1" style="text-align:center">'.$i++ .'</td>
                                    <td class="col-md-3">'.$staff_name.'</td>
                                    <td class="col-md-2">'.$design.'</td>
                                    <td class="col-md-1">'.($intime != ' - ' ? date('h:i A', strtotime($intime)) : $intime).'</td>
                                    <td class="col-md-1">'.($outtime != ' - ' ? date('h:i A', strtotime($outtime)) : $outtime).'</td>
                                    <td class="col-md-2">'.$status.'</td>
                                    <td class="col-md-2">'.($remarks != '' ? $remarks : ' - ').'</td>';
                          
                            
                            $str .='</tr>';
                        }
                    }
                }
                //$desi_arr = !empty($assign_to) ? array_column($assign_to,'desi_id') : array ();
            }
        } else {
            $str .=  '<tr><td colspan="7" class="text-center">No Record Found</td></tr>';
        }
        
        $str .= '</tbody> </table>';
        $str .= '<p class="help-block" style="margin:0 15px;"> * OIT = Other InTime, * OOT = Other OutTime</p>';
        if(isset($act_type) && $act_type == 'download_excel') {
            header("Content-type: application/vnd.ms-excel");  
            header('Content-disposition: attachment; filename="staff_attendance_'.date('m.d.Y H:i:s').'.xls"');           
        }
        echo $str;  
       
    }
    
    
    
    
    function manual_attendance () {
        $data['browser_title'] = 'Property Butler | Attendance Entry';
        $data['page_header'] = '<i class="fa fa-book"></i> Attendance Entry';
        //$data['last_capture'] = $this->bms_attendance_model->getMyAttendanceLastCapture();
        if(!in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi')))
            redirect('index.php/bms_dashboard/index'); 
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $property_id = !empty($_GET['property_id']) ? $_GET['property_id']  : 'all';
        $staff_id = $_SESSION['bms']['staff_id'];
        $data['staffs'] = array ();
        if($property_id) {
            if($property_id == 'all')
                $data['staffs'] = $this->bms_masters_model->getStaffNamesForSA ($property_id,$staff_id);
            else 
                $data['staffs'] = $this->bms_masters_model->getStaffNamesByProperty ($property_id,$staff_id);       
        }
        if(!empty($_GET['staff_id']) && !empty($_GET['date'])) {
            $attn_date = date('Y-m-d',strtotime($_GET['date'])); 
            $data['staff_attendance'] = $this->bms_attendance_model->getStaffAttendance ($_GET['staff_id'],$attn_date);
            //echo "<pre>";print_r($data['staff_attendance']);echo "</pre>";
        }
        
        //echo "<pre>";print_r($data['last_capture']);
        $this->load->view('attendance/atten_manual_entry_view',$data);
    }
    
    function manual_attendance_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        
        $atten_det = $this->input->post('atten_det');
        if(!empty($atten_det)) {
            $staff_id = $this->input->post('staff_id');
            $date = $this->input->post('start_date');
            
            foreach ($atten_det['in_out_type'] as $key=>$val) {
                if(!empty($val) && !empty($atten_det['atten_time'][$key])) {
                    $data['in_out_type'] = $atten_det['in_out_type'][$key];
                    $data['atten_time'] = date("H:i:s", strtotime($atten_det['atten_time'][$key]));
                    $data['remarks'] =  $atten_det['remarks'][$key];
                    $data['attn_date'] = date("Y-m-d", strtotime($date));
                    $data['staff_id'] = $staff_id;
                    if(!empty($atten_det['atten_id'][$key])) {
                        $this->bms_attendance_model->attendance_update ($data,$atten_det['atten_id'][$key]);
                    } else {
                        $this->bms_attendance_model->attendance_insert ($data);
                    }                    
                }                
            }
        }
        
        $_SESSION['flash_msg'] = 'Staff Attendance updated successfully!'; 
        redirect ('index.php/bms_attendance/manual_attendance?property_id='.$this->input->post('property_id').'&staff_id='.$staff_id.'&date='.$date); 
        
    }
    
    
    
}