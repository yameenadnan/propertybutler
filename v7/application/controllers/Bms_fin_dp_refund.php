<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_dp_refund extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_dp_refund_model');
        $this->load->helper('common_functions');
    }  
    
    
    function get_banks () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id')); 
        $banks = array();
        if($property_id) {
            $banks = $this->bms_fin_masters_model->getBanksForReceipt ($property_id);            
        }
        echo json_encode($banks);
    }
    
    function get_unit_deposits () {
        header('Content-type: application/json');
        $unit_id = trim($this->input->post('unit_id'));
        $results = array ();
        if($unit_id) {
            $results = $this->bms_fin_dp_refund_model->get_unit_deposits ($unit_id);       
        }
        echo json_encode($results);
    }

   
	public function dp_refund_list ($offset = 0, $per_page = 25) {
		
		$data['browser_title'] = 'Property Butler | Deposit Refund';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Refund';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);        
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/dp_refund/dp_refund_list_view',$data);
	}
    
    function get_dp_refund_list () {
        header('Content-type: application/json');        
        
        $bills = array('numFound'=>0,'records'=>array());
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            $bills = $this->bms_fin_dp_refund_model->getDpRefundList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $unit_id,$from,$to);
        }       
        echo json_encode($bills);
    } 
    
    
	public function add_dp_refund($depo_receive_id = '', $act_type = ''){
	
		$data['act_type'] = $act_type;
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Deposit Refund';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Refund';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if(!empty($depo_receive_id)) {
            $data['dp_refund'] = $this->bms_fin_dp_refund_model->getDpRefund($depo_receive_id);
            if(!empty($data['dp_refund']['depo_receive_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['dp_refund']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['dp_refund']['property_id'],$data['dp_refund']['block_id']);   
                $data['banks'] = $this->bms_fin_masters_model->getBanksForReceipt ($data['dp_refund']['property_id']);
                $data['dp_refund_items'] = $this->bms_fin_dp_refund_model->getDpReceiveItems($data['dp_refund']['depo_receive_id']);
                           
            }            
        }
        //echo "<pre>";print_r($data);echo "</pre>";
        $data ['property_id'] = !empty($data['dp_refund']['property_id']) ? $data['dp_refund']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        //$data['sales_items'] = $this->bms_fin_masters_model->getDpItems ($data ['property_id']);            
        $this->load->view('finance/dp_refund/dp_refund_add_view',$data);
	}
    
    function add_dp_refund_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $dp_refund = $this->input->post('dp_refund');
        $items = $this->input->post('items');
        
        $type = 'add';
        if(!empty($dp_refund['depo_refund_date'])) {
            $dp_refund['depo_refund_date'] = date('Y-m-d',strtotime($dp_refund['depo_refund_date']));
        }
        $pm_details = $this->input->post('pm_details');
        switch ($dp_refund['payment_mode']) {
            case 2: 
                $dp_refund['bank'] = !empty($pm_details['cheq_bank']) ? $pm_details['cheq_bank'] : '';
                $dp_refund['cheq_card_txn_no'] = !empty($pm_details['cheq_no']) ? $pm_details['cheq_no'] : '';
                $dp_refund['cheq_txn_online_date'] = !empty($pm_details['cheq_date']) ? date('Y-m-d',strtotime($pm_details['cheq_date'])) : '';
                break;
            case 3: 
                $dp_refund['bank'] = !empty($pm_details['card_bank']) ? $pm_details['card_bank'] : '';
                $dp_refund['cheq_card_txn_no'] = !empty($pm_details['card_txn_no']) ? $pm_details['card_txn_no'] : '';
                $dp_refund['online_r_card_type'] = !empty($pm_details['card_type']) ? $pm_details['card_type'] : '';
                break;
            case 4: 
                $dp_refund['bank'] = !empty($pm_details['online_bank']) ? $pm_details['online_bank'] : '';
                $dp_refund['cheq_card_txn_no'] = !empty($pm_details['online_txn_no']) ? $pm_details['online_txn_no'] : '';
                $dp_refund['online_r_card_type'] = !empty($pm_details['online_type']) ? $pm_details['online_type'] : '';
                $dp_refund['cheq_txn_online_date'] = !empty($pm_details['online_date']) ? date('Y-m-d',strtotime($pm_details['online_date'])) : '';
                break;
        }   
        
        
        if(!empty($dp_refund['depo_refund_id'])) {
            $type = 'edit';
            $depo_refund_id = $dp_refund['depo_refund_id'];
            $this->bms_fin_dp_refund_model->updateDpRefund ($dp_refund,$dp_refund['depo_refund_id']);
        } else {
            if(isset($dp_refund['depo_refund_id'])) unset($dp_refund['depo_refund_id']);
            $deposit_date = !empty($dp_refund['depo_refund_date']) ? $dp_refund['depo_refund_date'] : date('d-m-Y');
            
            $prop_abbrev = $this->input->post('prop_abbr');
            $doc_ref_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/DRD/'.date('y', strtotime($deposit_date)).'/'.date('m', strtotime($deposit_date)).'/';
            $last_no = $this->bms_fin_dp_refund_model->getLastDpRefundNo ($doc_ref_no_format);
            if(!empty ($last_no)) {
                $last_no = explode('/',$last_no['doc_ref_no']);
                $dp_refund['doc_ref_no'] = $doc_ref_no_format . (end($last_no) +1);
            } else {
                $dp_refund['doc_ref_no'] = $doc_ref_no_format . 1001;
            }
            $dp_refund['depo_refund_time'] = date('H:i:s');
            $depo_refund_id = $this->bms_fin_dp_refund_model->insertDpRefund ($dp_refund);
        }
        $this->bms_fin_dp_refund_model->setDepositRefundStatus ($dp_refund['depo_receive_id']);
        
        $_SESSION['flash_msg'] = 'Deposit Refund '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_dp_refund/dp_refund_details/'.$depo_refund_id);
    }   
    
    function dp_refund_details ($depo_refund_id,$act_type = 'view') {
        $data['browser_title'] = 'Property Butler | Deposit Refund Details';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Refund Details';   
        if(!empty($depo_refund_id)) {
            $data['dp_refund'] = $this->bms_fin_dp_refund_model->getDpRefundDetails($depo_refund_id);
            
            //echo "<pre>";print_r($data['dp_refund']);echo "</pre>";            
        }
        
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');
        if($act_type == 'download') {
            
            $this->load->library('M_pdf');
            
            $html = $this->load->view('finance/dp_refund/dp_refund_details_print_view',$data,true);
            
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['dp_refund']['doc_ref_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/dp_refund/dp_refund_details_print_view',$data);
        } else {
            $this->load->view('finance/dp_refund/dp_refund_details_view',$data);
        }
    } 
    
    function unset_dp_refund () {
        $depo_refund_id = $this->input->post('depo_refund_id');
        $depo_receive_id = $this->input->post('depo_receive_id');        
        $this->bms_fin_dp_refund_model->unsetDpReceiveStatus ($depo_receive_id);
        echo $this->bms_fin_dp_refund_model->deleteDpRefund ($depo_refund_id);
    }
	
}