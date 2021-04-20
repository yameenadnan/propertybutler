<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_daily_report extends CI_Controller {	
    
    function __construct () { 
        parent::__construct (); 
        
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false ) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    } 
        $this->load->model('bms_masters_model');
        $this->load->model('bms_daily_report_model');
        $this->load->model('bms_task_model_v2_0','bms_task_model');
        $this->load->model('bms_sop_model');
    }
    
    public function index() {
	   
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models        



        $data['browser_title'] = 'Property Butler | Daily Report';
        $data['page_header'] = '<i class="fa fa-folder"></i> Daily Report';	
        if($_SESSION['bms']['user_type'] == 'staff') {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        } else {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        }

       //

        $data['report_message'] = array(1 => 'Report date cannot be greater than current date',
            2 => 'Current date report will be generated after 4PM',
            3 => 'There are some Routine Task(s) active',
            4 => 'There are some Routine Task(s) pending. Need to Overwrite.',
            5 => 'Report is not genarated yet. Please try after sometimes.');
        $data['report_status'] = 0;



        if (!empty($_GET['property_id']) && !empty($_GET['report_date'])) {
            $property_id = $_GET['property_id'];
            $report_date = $_GET['report_date'];

            $report_year = date('Y', strtotime($report_date));
            $report_mon = date('m', strtotime($report_date));
            $file_name = "DR_" . $property_id . "_" . date('Y-m-d', strtotime($report_date)) . ".pdf";
            $daily_report_path = $this->config->item('daily_report_path');
            $result = array();

            if (file_exists($daily_report_path . $report_year . '/' . $report_mon . '/' . $file_name)) {
                $data['report_status'] = 6;
                $data['file_location'] = $daily_report_path . $report_year . '/' . $report_mon . '/' . $file_name;

            } else if (!file_exists($daily_report_path . $report_year . '/' . $report_mon . '/' . $file_name) && $_SESSION['bms']['user_type'] == 'jmb') {
                $data['report_status'] = 5;
            } else {

                $today_date = new DateTime(date('Y-m-d'));
                $report_date = new DateTime(date('Y-m-d', strtotime($_GET['report_date'])));
                $report_date_db_format = date('Y-m-d', strtotime($_GET['report_date']));
                $current_time = new DateTime(date('H:i:s'));
                $report_time = new DateTime(date('16:00:00'));

                $data['active_sop'] = 0;
                if ($report_date > $today_date) {
                    $data['report_status'] = 1;
                } else if ($report_date == $today_date && $current_time < $report_time) {
                    $data['report_status'] = 2;
                } else if ($report_date == $today_date && $current_time >= $report_time) {
                    $data['active_sop'] = $this->bms_daily_report_model->checkActiveSop($_GET['property_id']);
                    if ($data['active_sop'] > 0) {
                        $data['report_status'] = 3;
                    }
                }
                if ($report_date < $today_date || ($data['active_sop'] == 0 && $report_date == $today_date && $current_time >= $report_time)) {
                    $data['pending_sop'] = $this->bms_daily_report_model->getPendingSop($_GET['property_id'], $report_date_db_format);
                    if (count($data['pending_sop']) > 0) {
                        $data['report_status'] = 4;
                    }
                }
            }

            if ($data['report_status'] == 0) {

                //staff attendance
                $exclude_desi_ids = implode(',', $this->config->item('exclude_desi_for_daily_report'));

                $data['mgt_staffs'] = $this->bms_daily_report_model->getPropertyStaffs($_GET['property_id'], $exclude_desi_ids);
                if (!empty($data['mgt_staffs'])) {
                    foreach ($data['mgt_staffs'] as $key => $val) {
                        $data['mgt_staffs'][$key]['today'] = $this->bms_daily_report_model->getStaffAttendance($val['staff_id'], $report_date_db_format);
                    }
                }

                // Task created
                $data['tasks'] = $this->bms_daily_report_model->getTasks($_GET['property_id'], $report_date_db_format, 'created');
                if (!empty($data['tasks'])) {
                    foreach ($data['tasks'] as $tkey => $tval) {
                        $data['task_images'][$tval['task_id']] = $this->bms_daily_report_model->get_task_images($tval['task_id']);
                    }
                }
                // Task closed
                $data['tasks_closed'] = $this->bms_daily_report_model->getTasks($_GET['property_id'], $report_date_db_format, 'closed');
                if (!empty($data['tasks_closed'])) {
                    foreach ($data['tasks_closed'] as $tkey => $tval) {
                        $data['task_log'][$tval['task_id']] = $this->bms_task_model->getTaskLog($tval['task_id']);
                        $data['task_images'][$tval['task_id']] = $this->bms_daily_report_model->get_task_images($tval['task_id']);
                    }
                }

                // SOP
                $data['sop_entries'] = $this->bms_daily_report_model->getSopEntries($_GET['property_id'], $report_date_db_format);

                if (!empty($data['sop_entries'])) {
                    foreach ($data['sop_entries'] as $key => $val) {
                        $data['sop_sub'][$val['sop_id']] = $this->bms_sop_model->get_subsop($val['sop_id']);
                        //$data['sop_entry'] =  $this->bms_sop_model->get_sop_entry ($sop_id,$data['start_date'],$data['end_date']);

                        $data['sop_entry_img'][$val['sop_id']] = $this->bms_sop_model->get_sop_entry_img($val['id']);



                        if (!empty($data['sop_sub'][$val['sop_id']])) {
                            foreach ($data['sop_sub'][$val['sop_id']] as $val2) {
                                //echo $val2['sop_sub_id'];
                                $data['sop_sub_entry'][$val2['sop_sub_id']] = $this->bms_daily_report_model->get_sop_sub_entry($val2['sop_sub_id'], $report_date_db_format);

                                if (!empty($data['sop_sub_entry'][$val2['sop_sub_id']])) {
                                    //echo "<pre>";print_r($data['sop_sub_entry'][$val2['sop_sub_id']]['id']); echo "</pre>";
                                    //foreach ($data['sop_sub_entry'][$val2['sop_sub_id']] as $key3 => $val3) {
                                    $data['sop_sub_entry_img'][$data['sop_sub_entry'][$val2['sop_sub_id']]['id']] = $this->bms_sop_model->get_sop_sub_entry_img($data['sop_sub_entry'][$val2['sop_sub_id']]['id']);
                                    //}
                                }

                            }
                        }
                    }
                }
                //echo "<pre>";print_r($data['sop_entry_img']); echo "</pre>";
            }

            if ( $_SESSION['bms']['user_type'] != 'jmb' && (!isset($act) || $act != 'pdf'))
                $data['service_providers'] = $this->bms_daily_report_model->get_service_providers ($_GET['property_id'], $_GET['report_date']);
            }
            
            if (!empty($_GET['property_id']) && !empty($_GET['report_date']) && !empty($_GET['act']) && $_GET['act'] == 'pdf') {

                // Increasing memory
                ini_set('memory_limit', '3072M');
                ini_set('max_execution_time', 5000);

                $data['act'] = $_GET['act'];
                $data['gen_by_desi'] = $this->bms_masters_model->getDesignation($_SESSION['bms']['designation_id']);
                //$this->load->view('daily_report_view',$data);
                $filename = "DR_" . $_GET['property_id'] . "_" . date('Y-m-d', strtotime($_GET['report_date'])) . ".pdf";

                $html = $this->load->view('daily_report_view', $data, true);

                // unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has infor mation that you want to render on view.

                $this->load->library('M_pdf');

                $this->m_pdf->pdf->WriteHTML($html);

                //download it D save F.
                $daily_report_path = $this->config->item('daily_report_path');
                $report_year = date('Y', strtotime($_GET['report_date']));
                $report_mon = date('m', strtotime($_GET['report_date']));
                if (!is_dir($daily_report_path . $report_year . '/')) ;
                @mkdir($daily_report_path . $report_year . '/', 0777);
                if (!is_dir($daily_report_path . $report_year . '/' . $report_mon . '/')) ;
                @mkdir($daily_report_path . $report_year . '/' . $report_mon . '/', 0777);
                $this->m_pdf->pdf->debug = true;
                $this->m_pdf->pdf->Output($daily_report_path . $report_year . '/' . $report_mon . '/' . $filename, "F");
                $_SESSION['flash_msg'] = 'Report has been generated successfully!';
                redirect('index.php/bms_daily_report/index?property_id=' . $_GET['property_id'] . '&report_date=' . $_GET['report_date']);

            } else {
                $this->load->view('daily_report_view', $data);
            }

	}

	function add_service_provider_attendance_submit () {
        $service_provider_attendance = $_POST['service_provider_attendance'] ;



        $property_id = $this->input->post('property_id_attendence');
        $date = $this->input->post('date');

        foreach ( $service_provider_attendance['service_provider_id'] as $key => $val ) {
            $data = array (
                'property_id' => $property_id,
                'service_provider_id' => $service_provider_attendance['service_provider_id'][$key],
                'date' => date('Y-m-d',strtotime( $date ) ),
                'head_count' => $service_provider_attendance['head_count_attended'][$key],
            );
            $this->bms_daily_report_model->insert_service_provider_attendence ( $data );
        }
        redirect('index.php/bms_daily_report/index?property_id=' . $property_id . '&report_date=' . $date);
    }


    function add_service_provider_attendance_submit123 () {
        $property_id = $this->input->post('property_id');
        $service_provider_id = $this->input->post('service_provider_id');
        $date = $this->input->post('date');
        $head_count = $this->input->post('head_count');
        $data = array (
            'property_id' => $property_id,
            'service_provider_id' => $service_provider_id,
            'date' => $date,
            'head_count' => $head_count,
        );
        $this->bms_daily_report_model->insert_service_provider_attendence ( $data );
    }
    
    function my_mPDF(){

        
        
        
        $data['browser_title'] = 'Property Butler | Daily Report';
        $data['page_header'] = '<i class="fa fa-folder"></i> Daily Report';	
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data['report_message'] = array(1=>'Report date cannot be greater than current date',
                                 2=>'Current date report will be generated after 5PM',
                                 3=>'There are some Routine Task(s) active',
                                 4=>'There are some Routine Task(s) pending. Need to Overwrite.');
        $data['report_status'] = 0;
        
        if(!empty($_GET['property_id']) && !empty($_GET['report_date'])) { 
            $today_date = new DateTime(date('Y-m-d'));
            $report_date = new DateTime(date('Y-m-d', strtotime($_GET['report_date']))); 
            $report_date_db_format = date('Y-m-d', strtotime($_GET['report_date']));
            $current_time =  new DateTime(date('H:i:s'));
            $report_time = new DateTime(date('17:00:00'));
            
            if($report_date > $today_date)   {
                $data['report_status'] = 1;
            } else if ($report_date == $today_date && $current_time < $report_time ) {
                $data['report_status'] = 2;
            } else if ($report_date == $today_date && $current_time >= $report_time ) {
                $data['active_sop'] = $this->bms_daily_report_model->checkActiveSop($_GET['property_id']);
                if($data['active_sop'] > 0) {
                    $data['report_status'] = 3;
                } 
            } else if ($report_date < $today_date || ($report_date == $today_date && $current_time >= $report_time )) {
                $data['pending_sop'] = $this->bms_daily_report_model->getPendingSop($_GET['property_id'],$report_date_db_format); 
                if(count($data['pending_sop']) > 0) {
                    $data['report_status'] = 4;
                }                 
            }
            if($data['report_status'] == 0) {
                
                //staff attendance
                $exclude_desi_ids = implode(',',$this->config->item('exclude_desi_for_daily_report'));
                
                $data['mgt_staffs'] = $this->bms_daily_report_model->getPropertyStaffs($_GET['property_id'],$exclude_desi_ids);
                if(!empty($data['mgt_staffs'])) {
                    foreach ($data['mgt_staffs'] as $key=>$val) {
                        $data['mgt_staffs'][$key]['today'] = $this->bms_daily_report_model->getStaffAttendance($val['staff_id'],$report_date_db_format);
                    }
                }
                
                // Task created
                $data['tasks'] = $this->bms_daily_report_model->getTasks($_GET['property_id'],$report_date_db_format,'created');
                if(!empty($data['tasks'])) {
                    foreach ($data['tasks'] as $tkey=>$tval) {
                        $data['task_images'][$tval['task_id']] = $this->bms_daily_report_model->get_task_images($tval['task_id']);
                    }
                }
                // Task closed
                $data['tasks_closed'] = $this->bms_daily_report_model->getTasks($_GET['property_id'],$report_date_db_format,'closed');
                if(!empty($data['tasks_closed'])) {
                    foreach ($data['tasks_closed'] as $tkey=>$tval) {
                        $data['task_log'][$tval['task_id']] = $this->bms_task_model->getTaskLog($tval['task_id']);
                        $data['task_images'][$tval['task_id']] = $this->bms_daily_report_model->get_task_images($tval['task_id']);
                    }
                }
                
                // SOP
                $data['sop_entries'] = $this->bms_daily_report_model->getSopEntries($_GET['property_id'],$report_date_db_format);
                if(!empty($data['sop_entries'])) {
                    foreach ($data['sop_entries'] as $key=>$val) {
                        $data['sop_sub'][$val['sop_id']] = $this->bms_sop_model->get_subsop ($val['sop_id']);
                        //$data['sop_entry'] =  $this->bms_sop_model->get_sop_entry ($sop_id,$data['start_date'],$data['end_date']);
                        
                        $data['sop_entry_img'][$val['sop_id']] =  $this->bms_sop_model->get_sop_entry_img ($val['id']);
                            
                        if(!empty($data['sop_sub'][$val['sop_id']])) {
                            foreach ($data['sop_sub'][$val['sop_id']] as $val2) {
                                //echo $val2['sop_sub_id'];
                                $data['sop_sub_entry'][$val2['sop_sub_id']] =  $this->bms_daily_report_model->get_sop_sub_entry ($val2['sop_sub_id'],$report_date_db_format);
                            
                                if(!empty($data['sop_sub_entry'][$val2['sop_sub_id']])) {
                                    //echo "<pre>";print_r($data['sop_sub_entry'][$val2['sop_sub_id']]['id']); echo "</pre>";
                                    //foreach ($data['sop_sub_entry'][$val2['sop_sub_id']] as $key3 => $val3) {
                                       $data['sop_sub_entry_img'][$data['sop_sub_entry'][$val2['sop_sub_id']]['id']] =  $this->bms_sop_model->get_sop_sub_entry_img ($data['sop_sub_entry'][$val2['sop_sub_id']]['id']);
                                    //}                    
                                }
                                
                            }                            
                        }
                    }
                    
                }
                //echo "<pre>";print_r($data['sop_entry_img']); echo "</pre>";
                
                
            }
            
            $filename = "DR_".$_GET['property_id']."_".date('Y-m-d', strtotime($_GET['report_date'])).".pdf";
            
            $html = $this->load->view('daily_report_view',$data,true);
            
            // unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has infor mation that you want to render on view.
            
            $this->load->library('M_pdf');
            
            $this->m_pdf->pdf->WriteHTML($html);
            
            //download it D save F.
            $daily_report_path = $this->config->item('daily_report_path');
            $report_year = date('Y', strtotime($_GET['report_date']));
            $report_mon  = date('m', strtotime($_GET['report_date']));
            if(!is_dir($daily_report_path.$report_year.'/'));
                @mkdir($daily_report_path.$report_year.'/', 0777);
            if(!is_dir($daily_report_path.$report_year.'/'.$report_mon.'/'));
                @mkdir($daily_report_path.$report_year.'/'.$report_mon.'/', 0777);
            
            $this->m_pdf->pdf->Output($daily_report_path.$report_year.'/'.$report_mon.'/'.$filename, "F");
            
        }        
    }
    
    function overwrite_sop_entry ($sop_id) {
        
        $data['browser_title'] = 'Property Butler | Daily Report | Routine Task Entry Overwrite ';
        $data['page_header'] = '<i class="fa fa-folder"></i> Daily Report <i class="fa fa-angle-double-right"></i> Routine Task Entry Overwrite';
        
        $data['sop'] = $this->bms_sop_model->get_sop_by_id ($sop_id);
        
        if(!empty($data['sop'])) {
            foreach($data['sop'] as $key=>$val) {
                $data['sub_sop'][$key] = $this->bms_sop_model->get_subsop ($val['sop_id']);
            }
        } else {
            redirect('index.php/bms_sop/entry_list');                    
        }        
        //echo "<pre>";print_r($data['sop_main']);echo "</pre>";
        $this->load->view('sop_entry_view',$data);
    }
    
   
}