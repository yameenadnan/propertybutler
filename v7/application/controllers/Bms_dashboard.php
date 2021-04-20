<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_dashboard extends CI_Controller {

    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false) {
	       redirect('index.php/bms_index/login');	       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log 
        $this->load->model('bms_masters_model');
        $this->load->model('bms_task_model_v2_0','bms_task_model');
    }
    
    public function index() {
		$data['browser_title'] = 'Property Butler | Dashboard';
        $data['page_header'] = '<i class="fa fa-dashboard"></i> Dashboard';

        if ($_SESSION['bms']['user_type'] == 'jmb') {
            $data['properties'] = $this->bms_masters_model->getJmbProperties ($_SESSION['bms']['property_id']);
        } else {
            $data['properties'] = $this->bms_masters_model->getMyProperties ();
        }
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $_SESSION['bms_default_property'] = $data ['property_id'];
        $_SESSION['property_under'] = $this->bms_masters_model->getPropertyUnderFromPropertyId ( $_SESSION['bms_default_property'] );
        if($data ['property_id']) {
            
            $data['property_info'] = $this->bms_masters_model->getPropertyInfo($data ['property_id']);
            
            if($_SESSION['bms']['user_type'] == 'jmb') {
                $data['notice_board'] = $this->bms_masters_model->getResiNoticeBoard($data ['property_id']);
            } else {
                $data['notice_board'] = $this->bms_masters_model->getNoticeBoard();
            }

            //
            
            $exclude_desi_ids = implode(',',$this->config->item('exclude_desi_for_dashboard'));
            $data['staff_info'] = $this->bms_masters_model->getPropertyStaffs ($data ['property_id'],$exclude_desi_ids);
            
            $data['open_task_count'] = $this->bms_task_model->getMinorTaskCount ($data ['property_id'],'O');
            $data['closed_task_count'] = $this->bms_task_model->getMinorTaskCount ($data ['property_id'],'C');
            
            $data['awarded_year'] = date ('Y', strtotime ( '-1 month' , strtotime ( date('Y-M-d') )));
            $data['awarded_month'] = date ('n', strtotime ( '-1 month' , strtotime ( date('Y-M-d') )));//date('n',strtotime(date('Y-M-d'),'-1 month'));

            $data['invalid_email_count'] = $this->bms_masters_model->get_invalid_email_count ($data ['property_id']);

            // My Minor task count
            $data['my_open_task_count'] = $this->bms_task_model->getTaskCount ('=', $_SESSION['bms']['designation_id'], $data ['property_id'], '>=' );
            $data['my_over_due_task_count'] = $this->bms_task_model->getTaskCount ('=', $_SESSION['bms']['designation_id'], $data ['property_id'], '<' );

            // Overseeing Minor task
            $data['overseeing_open_task_count'] = $this->bms_task_model->getTaskCount ('<>', $_SESSION['bms']['designation_id'], $data ['property_id'], '>=' );
            $data['overseeing_over_due_task_count'] = $this->bms_task_model->getTaskCount ('<>', $_SESSION['bms']['designation_id'], $data ['property_id'], '<' );

            // Annual renewals
            $data['get_annual_renewal_count'] = $this->bms_task_model->getAnnualRenewalsCount ($data ['property_id']);
            $data['get_expired_annual_renewal_count'] = $this->bms_task_model->getExpiredAnnualRenewalsCount ($data ['property_id']);

            // Service schedule
            $data['get_service_schedule_count'] = $this->bms_task_model->getServiceScheduleCount ($data ['property_id']);
            $data['get_expired_service_schedule_count'] = $this->bms_task_model->getExpiredServiceScheduleCount ($data ['property_id']);

            if($_SESSION['bms']['user_type'] == 'staff') {                
                //$data['awarded_staff'] = $this->bms_masters_model->getAwardedStaffs($data['awarded_year'],$data['awarded_month']);
                //echo "<pre>";print_r($data['awarded_staff']);echo "</pre>";
            }
                
            //echo "<pre>";print_r($data['staff_info']); echo "</pre>";
            //$data['open_task_count'] = $this->bms_task_model->getMinorTaskCount($data ['property_id']);
            /*$data['chart_data'] = '';
            for($i =1; $i <=5 ; $i++) {
                $data['chart_data'] .= $data['chart_data'] != '' ? ',' : '';
                $data['chart_data'] .= $this->bms_task_model->getOverDueTaskForDaysInter($data ['property_id'], $i);
            }*/
            if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('prop_doc_download_desi_id')))) { 
                $collection_type = $this->config->item('collec_type');
                $start_date = $end_date = date('Y-m-d');
                foreach ($collection_type as $key=>$val) {
                    $data['today_collec'][$key] = $this->bms_task_model->getCollection($data ['property_id'], $val,$start_date,$end_date);
                }
                
                $start_date = date('Y-m-01');
                //$till_date_collec_sum = 0;
                foreach ($collection_type as $key=>$val) {            
                    $data['till_collec'][$key] = $this->bms_task_model->getCollection($data ['property_id'], $val,$start_date,$end_date);
                    //$till_date_collec_sum += $data['till_collec'][$key];
                }
                
                $data['monthly_collec'] = $this->bms_masters_model->getPropertyMonthlyCollec($data ['property_id']);
                
                //$data['collec_percentage'] = $till_date_collec_sum > 0 && $data['monthly_collec'] > 0 ? round((($till_date_collec_sum * 100) / $data['monthly_collec']),2) : 0; 
            }
        }
        //echo "<pre>";print_r($data);echo "</pre>";exit;     
        $this->load->view('dashboard/dashboard_view',$data);
	}
    
    function short_listed_names ($award_year,$award_month) {
        $data['staff_award_cat'] = $this->config->item('staff_award_category'); 
        foreach ($data['staff_award_cat'] as $key=>$val) {
            $data['short_listed_staff'][$key] = $this->bms_masters_model->getShortListedStaffs($key,$award_year,$award_month);
        }
        //echo "<pre>";print_r($data['short_listed_staff']);echo "</pre>";
        
        $this->load->view('/dashboard/dashboard_short_list_name_view',$data);
    }
    
    function minor_task_chart ($chart_type,$property_id) {
        //echo "test ".$chart_type;
        $data= array ();
        if($chart_type == 'daily') {
           
           for($i = 1; $i <=  date('t'); $i++) {
               // add the date to the dates array
               $data['x_axis'][] = $i; 
               $data['open_cnt'][$i] = 0;
               $data['close_cnt'][$i] = 0;   
               //$dates[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
            $start_date = date('Y') . "-" . date('m') . "-" . '01'; 
            $end_date = date('Y') . "-" . date('m') . "-" . date('t'); 
            $result = $this->bms_task_model->getMinorTaskCountForDailyChart($property_id,$start_date,$end_date,'O');
            foreach ($result as $key=>$val) {
                $data['open_cnt'][ltrim($val['md'], '0')] = $val['cnt'];
            }
            $result = $this->bms_task_model->getMinorTaskCountForDailyChart($property_id,$start_date,$end_date,'C');
            foreach ($result as $key=>$val) {
                $data['close_cnt'][ltrim($val['md'], '0')] = $val['cnt'];
            }
            //print_r($data['open_cnt']);
            $this->load->view('dashboard/dashboard_daily_chart_view',$data);            
        } else  if($chart_type == 'monthly') {
           
            $start = new DateTime("2018-04-01");
            $end   = new DateTime(date('Y-m-d'));
        	$diff  = $start->diff($end);
        	$months_diff =  $diff->format('%y') * 12 + $diff->format('%m'); 
             
            $start_at = $months_diff >= 10 ? 11 : $months_diff; 
            
            for ($i = $start_at; $i >= ($start_at - 11) ; $i--) {
                $oper = $i >= 0 ? "-$i" : "+".abs($i);  
                $data['open_cnt'][date("Y-m", strtotime( date( 'Y-m-01' )." $oper months"))] = 0;
                $data['close_cnt'][date("Y-m", strtotime( date( 'Y-m-01' )."  $oper months"))] = 0;
                $data['x_axis'][] = date("M y", strtotime( date( 'Y-m-01' )." $oper months"));            
            }
            //print_r($data['open_cnt']);  
            $months_str = implode("','",array_keys($data['open_cnt']));
            $result = $this->bms_task_model->getMinorTaskCountForChart($property_id,$months_str,$chart_type,'O');
            foreach ($result as $key=>$val) {
                $data['open_cnt'][$val['md']] = $val['cnt'];
            }
            $result = $this->bms_task_model->getMinorTaskCountForChart($property_id,$months_str,$chart_type,'C');
            foreach ($result as $key=>$val) {
                $data['close_cnt'][$val['md']] = $val['cnt'];
            }
            $this->load->view('dashboard/dashboard_monthly_chart_view',$data); 
        } else if ($chart_type == 'yearly') {
            $year_diff = date('Y') - 2018;
            if($year_diff >= 4) {
                $year_start = date('Y')-4;
            } else $year_start = 2018;
            for ($i = $year_start; $i <= ($year_start+4); $i++) {
                $data['open_cnt'][$i] = 0;
                $data['close_cnt'][$i] = 0;
                $data['x_axis'][] = $i;            
            }
            $months_str = implode("','",array_keys($data['open_cnt']));
            $result = $this->bms_task_model->getMinorTaskCountForChart($property_id,$months_str,$chart_type,'O');
            foreach ($result as $key=>$val) {
                $data['open_cnt'][$val['md']] = $val['cnt'];
            }
            $result = $this->bms_task_model->getMinorTaskCountForChart($property_id,$months_str,$chart_type,'C');
            foreach ($result as $key=>$val) {
                $data['close_cnt'][$val['md']] = $val['cnt'];
            }
            $this->load->view('dashboard/dashboard_yearly_chart_view',$data); 
        }
    }

    function get_minor_tasks_open () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getMinorTaskOpen( '=', $_SESSION['bms']['designation_id'],$data ['property_id'], '>=', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_minor_tasks_overdue () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getMinorTaskOpen( '=', $_SESSION['bms']['designation_id'],$data ['property_id'], '<', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_minor_tasks_overseeing_open () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getMinorTaskOpen( '!=', $_SESSION['bms']['designation_id'],$data ['property_id'], '>=', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_minor_tasks_overseeing_overdue () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getMinorTaskOpen( '!=', $_SESSION['bms']['designation_id'],$data ['property_id'], '<', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_annual_renewals () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getAnnualRenewals( $data ['property_id'], 'new', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_annual_renewals_expired () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $minor_task_open = $this->bms_task_model->getAnnualRenewals( $data ['property_id'], 'expired', $offset, $rows );
        echo json_encode($minor_task_open);
    }

    function get_asset_service_schedule () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $asset_service_schedule = $this->bms_task_model->getAssetServiceSchedule( $data ['property_id'], 'new', $offset, $rows );
        echo json_encode($asset_service_schedule);
    }

    function get_asset_service_schedule_expired () {
        $offset = $this->input->post('offset');
        $rows = $this->input->post('rows');
        header('Content-type: application/json');
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $asset_service_schedule = $this->bms_task_model->getAssetServiceSchedule( $data ['property_id'], 'expired', $offset, $rows );
        echo json_encode($asset_service_schedule);
    }

}
