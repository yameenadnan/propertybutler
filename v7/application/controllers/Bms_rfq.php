<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_rfq extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        //$this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_rfq_model');
        //$this->load->helper('common_functions');
    } 
   
	public function quotation_list ($offset = 0, $per_page = 25) {
		
		$data['browser_title'] = 'Property Butler | Request For Quotation';
        $data['page_header'] = '<i class="fa fa-list-ol"></i> Request For Quotation';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        //$data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);        
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('rfq/rfq_list_view',$data);
	}
    
    function get_quotation_list () {
        header('Content-type: application/json');        
        
        $results = array('numFound'=>0,'records'=>array());
        /*if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            //$results = $this->bms_fin_dp_receive_model->getDpReceiveList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $unit_id,$from,$to);
        }  */     
        echo json_encode($results);
    } 
    
    
	public function add_rfq($rfq_id = '', $act_type = ''){
	
		$data['act_type'] = $act_type;
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Request For Quotation';
        $data['page_header'] = '<i class="fa fa-list-ol"></i> Request For Quotation';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        /*if(!empty($depo_receive_id)) {
            $data['dp_receive'] = $this->bms_fin_dp_receive_model->getDpReceive($depo_receive_id);
            if(!empty($data['dp_receive']['depo_receive_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['dp_receive']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['dp_receive']['property_id'],$data['dp_receive']['block_id']);   
                $data['banks'] = $this->bms_fin_masters_model->getBanksForReceipt ($data['dp_receive']['property_id']);
                //$data['dp_receive_items'] = $this->bms_fin_dp_receive_model->getDpReceiveItems($data['dp_receive']['depo_receive_id']);
                           
            }            
        }*/
        //echo "<pre>";print_r($data);echo "</pre>";
        
        $data['category'] = $this->bms_rfq_model->getVendorCategory ();
        $data['keywords'] = $this->bms_rfq_model->getKeywords ();
        $data['state'] = $this->bms_rfq_model->getState ();
        //$data['city'] = $this->bms_rfq_model->getCity ();
        if(!empty($data['keywords'])) {
            //$data['keywords'] = array_column($data['keywords'],'vendor_keywords');
            $data['keywords_arr'] = array ();
            foreach ($data['keywords'] as $key => $val) {
                if(!empty($val['vendor_keywords']) && $val['vendor_keywords'] != '-') {
                    $val_arr = explode('; ',$val['vendor_keywords']);
                    if(!empty($val_arr)){
                        foreach ($val_arr as $key2=>$val2) {
                            array_push($data['keywords_arr'],trim($val2));                            
                        }
                    }
                    
                }
            }
            //echo "<pre>";print_r($data['keywords_arr']); echo "</pre>";
            $data['keywords_arr'] = array_unique ($data['keywords_arr']);
            //echo "<pre>";print_r($data['keywords_arr']); echo "</pre>";
            //$desi_arr = !empty($assign_to) ? array_column($assign_to,'desi_id') : array ();    
        }
        
        
        $data ['property_id'] = !empty($data['dp_receive']['property_id']) ? $data['dp_receive']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        //$data['sales_items'] = $this->bms_fin_masters_model->getDpItems ($data ['property_id']);            
        $this->load->view('rfq/add_rfp_view',$data);
	}
    
    function get_city () {
        header('Content-type: application/json');        
        
        $result = array ();
        if (isset($_POST['state_id']) && $_POST['state_id'] != '') {
            $state_id = $this->input->post('state_id');
            //$from = $this->input->post('from');
            
            $result = $this->bms_rfq_model->getCity ($state_id);
        }       
        echo json_encode($result);
    }
    
    function get_vendor () {
        header('Content-type: application/json');        
        
        $result = array ();
        if (isset($_POST['s_type']) && $_POST['s_type'] != '' && isset($_POST['val']) && $_POST['val'] != '') {
            $s_type = $this->input->post('s_type');
            $val = $this->input->post('val');
            $state = $this->input->post('state_id');
            $city = $this->input->post('city');
            //$from = $this->input->post('from');
            
            $result = $this->bms_rfq_model->getVendor ($s_type,$val,$state,$city);
        }       
        echo json_encode($result);
    }
    
    function add_rfq_submit () {
        echo "<pre>";print_r($_POST);print_r($_FILES);echo "</pre>"; exit;
        $dp_receive = $this->input->post('dp_receive');
        $items = $this->input->post('items');
        
        $type = 'add';
        if(!empty($dp_receive['deposit_date'])) {
            $dp_receive['deposit_date'] = date('Y-m-d',strtotime($dp_receive['deposit_date']));
        }
        $pm_details = $this->input->post('pm_details');
        
        
        if(!empty($dp_receive['depo_receive_id'])) {
            $type = 'edit';
            $depo_receive_id = $dp_receive['depo_receive_id'];
            $this->bms_fin_dp_receive_model->updateDpReceive ($dp_receive,$dp_receive['depo_receive_id']);
        } else {
            if(isset($dp_receive['depo_receive_id'])) unset($dp_receive['depo_receive_id']);
            $deposit_date = !empty($dp_receive['deposit_date']) ? $dp_receive['deposit_date'] : date('d-m-Y');
            
            $prop_abbrev = $this->input->post('prop_abbr');
            $doc_ref_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/DRE/'.date('y', strtotime($deposit_date)).'/'.date('m', strtotime($deposit_date)).'/';
            $last_no = $this->bms_fin_dp_receive_model->getLastDpReceiveNo ($doc_ref_no_format);
            if(!empty ($last_no)) {
                $last_no = explode('/',$last_no['doc_ref_no']);
                $dp_receive['doc_ref_no'] = $doc_ref_no_format . (end($last_no) +1);
            } else {
                $dp_receive['doc_ref_no'] = $doc_ref_no_format . 1001;
            }
            $dp_receive['deposit_time'] = date('H:i:s');
            $depo_receive_id = $this->bms_fin_dp_receive_model->insertDpReceive ($dp_receive);
        }
        $_SESSION['flash_msg'] = 'Request For Quotation '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_dp_receive/dp_receive_details/'.$depo_receive_id);
    }   
    
    function dp_receive_details ($depo_receive_id,$act_type = 'view') {
        $data['browser_title'] = 'Property Butler | Request For Quotation Details';
        $data['page_header'] = '<i class="fa fa-list-ol"></i> Request For Quotation Details';   
        if(!empty($depo_receive_id)) {
            $data['dp_receive'] = $this->bms_fin_dp_receive_model->getDpReceiveDetails($depo_receive_id);
            
            //echo "<pre>";print_r($data['dp_receive']);echo "</pre>";            
        }
        
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');
        if($act_type == 'download') {
            
            $this->load->library('M_pdf');
            
            $html = $this->load->view('finance/dp_receive/dp_receive_details_print_view',$data,true);
            
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['dp_receive']['doc_ref_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/dp_receive/dp_receive_details_print_view',$data);
        } else {
            $this->load->view('finance/dp_receive/dp_receive_details_view',$data);
        }
    } 
   	
}