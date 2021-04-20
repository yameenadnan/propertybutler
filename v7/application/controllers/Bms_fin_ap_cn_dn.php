<?php
//error_reporting(1);
defined('BASEPATH') OR exit('No direct script access allowed');
class Bms_fin_ap_cn_dn extends CI_Controller {
    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
            redirect('index.php/bms_index/login?return_url=' . ($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1') . $_SERVER['REQUEST_URI']);
        }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_fin_masters_model');
        $this->load->model('bms_fin_expenses_model');
        $this->load->model('bms_fin_ap_cn_dn_model');
        $this->load->model('bms_fin_purchase_model');
    }

    public function cn_list ($offset = 0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Credit Note';
        $data['page_header']   = '<i class="fa fa-file"></i> Credit Note';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');

        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_ap_cn_dn_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_ap_cn_dn_model->getPropertyAssets($data['property_id']);
        }
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        $this->load->view('finance/ap_cn_dn/cn_list_view', $data); //bms_purchase_add
    }

    function get_cn_list () {
        header('Content-type: application/json');
        $poorder = array();

        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $poorder = $this->bms_fin_ap_cn_dn_model->getCnList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($poorder);
    }

    public function add_cn ($ap_cr_id = '') {

        $data['browser_title'] = 'Property Butler | Credit Note';
        $data['page_header']   = '<i class="fa fa-file"></i> Credit Note';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_ap_cn_dn_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_ap_cn_dn_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }

        $this->load->view('finance/ap_cn_dn/cn_add_view', $data);
    }

    function add_cn_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;

        $ap_cn = $this->input->post('ap_cn');
        $items = $this->input->post('items');
        $type = 'add';
        if(!empty($ap_cn['credit_note_date'])) {
            $ap_cn['credit_note_date'] = date('Y-m-d',strtotime($ap_cn['credit_note_date']));
        }

        if(!empty($ap_cn['ap_cr_id'])) {
            $type = 'edit';
            $ap_cr_id = $ap_cn['ap_cr_id'];
            $this->bms_fin_ap_cn_dn_model->update_ap_credit_note ($ap_cn,$ap_cn['ap_cr_id']);
        } else {
            if(isset($ap_cn['ap_cr_id'])) unset($ap_cn['ap_cr_id']);
            $prop_abbrev = $this->input->post('prop_abbr');
            $ap_cr_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/APCN/'.date('y').'/'.date('m').'/';
            $ap_cr_no = $this->bms_fin_ap_cn_dn_model->get_ap_cr_no ($ap_cr_no_format);
            if(!empty ($ap_cr_no)) {
                $last_no = explode('/',$ap_cr_no['ap_cr_no']);
                $ap_cn['ap_cr_no'] = $ap_cr_no_format . (end($last_no) +1);
            } else {
                $ap_cn['ap_cr_no'] = $ap_cr_no_format . 1001;
            }
            $ap_cn['credit_note_time'] = date('H:i:s');
            $ap_cr_id = $this->bms_fin_ap_cn_dn_model->insert_ap_credit_note ($ap_cn);
        }

        if(!empty($items['coa_id'])) {
            $item['ap_cr_id'] = $ap_cr_id;
            $bill_item_ids = array();
            foreach ($items['coa_id'] as $key=>$val) {
                if(!empty($items['adj_amount'][$key])) {
                    $item['coa_id'] = $val;
                    $item['item_descrip'] = $items['item_descrip'][$key];
                    $item['item_amount'] = $items['item_amount'][$key];
                    $item['adj_amount'] = $items['adj_amount'][$key];
                    $item['bal_amount'] = $items['balance_amount'][$key];
                    $item['exp_item_id'] = $items['exp_item_id'][$key];
                    if(!empty($items['ap_cr_item_id'][$key])) {
                        $this->bms_fin_ap_cn_dn_model->update_ap_credit_note_items ($item,$items['ap_cr_item_id'][$key]);
                    } else {
                        $this->bms_fin_ap_cn_dn_model->insert_ap_credit_note_items ($item);
                    }

                    $exp_inv_items_detail = $this->bms_fin_ap_cn_dn_model->get_exp_inv_items ( $items['exp_item_id'][$key] );

                    $expense_update_data = array (
                        'paid_amount' => $exp_inv_items_detail->paid_amount + $item['adj_amount'],
                        'balance_amount' => $exp_inv_items_detail->balance_amount - $item['adj_amount'],
                    );

                    if ( $exp_inv_items_detail->balance_amount - $item['adj_amount'] == 0 )
                        $expense_update_data['paid_status'] = 1;
                    else
                        $expense_update_data['paid_status'] = 0;

                    $this->bms_fin_expenses_model->updateExpenceOrderItem ( $expense_update_data ,$items['exp_item_id'][$key] );
                }
            }

            if ( !empty( $ap_cn ) ) {
                $expense_invoice_detail = $this->bms_fin_ap_cn_dn_model->get_expense_invoice_detail ( $ap_cn['invoice_id'] );
                $expense_invoice = array ();
                $expense_invoice['paid_amount'] = $expense_invoice_detail->paid_amount + $ap_cn['total_amount'];
                $expense_invoice['balance_amount'] = $this->input->post('invoice_balance_amount') ;
                if ( $this->input->post('invoice_balance_amount') == 0 )
                    $expense_invoice['inv_paid_status'] = 1 ;
                else
                    $expense_invoice['inv_paid_status'] = 0 ;

                $this->bms_fin_expenses_model->updateExpenceOrder( $expense_invoice, $ap_cn['invoice_id'] );
            }
        }

        $_SESSION['flash_msg'] = 'Credit Note '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!';
        redirect ('index.php/bms_fin_ap_cn_dn/cn_list/');
    }

    public function getExpeseInvoiceNumber() {
        header('Content-type: application/json');
        $service_provider_id = trim($this->input->post('service_provider_id'));
        $subcategory = array();
        if($service_provider_id) {
            $subcategory = $this->bms_fin_ap_cn_dn_model->get_exp_inv_no ($service_provider_id);
        }
        echo json_encode($subcategory);
    }

    public function getExpensesOrderDetails () {
        $exp_id = trim($this->input->post('exp_id'));
        $property_id = trim($this->input->post('property_id'));
        $data=array();
        $purdetails = array();
        if($exp_id) {
            $data['bill_items'] = $this->bms_fin_ap_cn_dn_model->getInvoiceOrderitems ($exp_id);
            $data['sales_items'] = $this->bms_fin_ap_cn_dn_model->getSalesItemsForBill ($property_id);
        }
        $this->load->view('finance/ap_cn_dn/cn_invoicel_items_view',$data);
    }

    public function cn_details ($ap_cr_id,$act_type = 'view') {

        $data['browser_title'] = 'Property Butler | Credit Note Details';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Credit Note Details';

        if(!empty($ap_cr_id)) {
            $data['cn_note'] = $this->bms_fin_ap_cn_dn_model->get_ap_cn_details($ap_cr_id);
            if(!empty($data['cn_note']['ap_cr_id'])) {
                $data['cn_items'] = $this->bms_fin_ap_cn_dn_model->get_cn_items_detail($data['cn_note']['ap_cr_id']);
            }
        }
        //echo "<pre>";print_r($data['credit_note_items']);echo "</pre>";
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');

        if($act_type == 'download') {

            $this->load->library('M_pdf');

            $html = $this->load->view('finance/ap_cn_dn/cn_details_print_view',$data,true);

            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['credit_note']['credit_note_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/ap_cn_dn/cn_details_print_view',$data);
        } else {
            $this->load->view('finance/ap_cn_dn/cn_details_view',$data);
        }
    }







































    public function dn_list ($offset = 0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Debit Note';
        $data['page_header']   = '<i class="fa fa-file"></i> Debit Note';
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_ap_cn_dn_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_ap_cn_dn_model->getPropertyAssets($data['property_id']);
        }
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        $this->load->view('finance/ap_cn_dn/dn_list_view', $data); //bms_purchase_add
    }

    function get_dn_list () {
        header('Content-type: application/json');
        $poorder = array();

        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $poorder = $this->bms_fin_ap_cn_dn_model->getDnList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($poorder);
    }

    public function add_dn ($ap_cr_id = '') {

        $data['browser_title'] = 'Property Butler | Debit Note';
        $data['page_header']   = '<i class="fa fa-file"></i> Debit Note';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_ap_cn_dn_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_ap_cn_dn_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }

        $this->load->view('finance/ap_cn_dn/dn_add_view', $data);
    }

    function add_dn_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;

        // Step 1: insert in bms_fin_ap_debit_note
        // Step 2: Take items from bms_fin_payment_items and insert in bms_fin_ap_debit_note_items
        // Step 3: update bms_fin_exp_inv_items table
        // Step 4: bms_fin_expense_invoice

        $ap_dn = $this->input->post('ap_dn');
        $prop_abbrev = $this->input->post('prop_abbr');

        if ( !empty ($ap_dn['property_id']) && !empty ($ap_dn['debit_note_date']) && !empty ($ap_dn['service_provider_id']) && !empty ($ap_dn['pay_id']) ) {
            $type = 'add';
            if(!empty($ap_dn['debit_note_date'])) {
                $ap_dn['debit_note_date'] = date('Y-m-d',strtotime($ap_dn['debit_note_date']));
            }

            if(!empty($ap_dn['ap_dn_id'])) {
                $type = 'edit';
                $this->bms_fin_ap_cn_dn_model->update_ap_debit_note ($ap_dn,$ap_dn['ap_dn_id']);
            } else {
                if(isset($ap_dn['ap_dn_id'])) unset($ap_dn['ap_dn_id']);
                $ap_dn_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/APDN/'.date('y').'/'.date('m').'/';
                $ap_dn_no = $this->bms_fin_ap_cn_dn_model->get_ap_dn_no ($ap_dn_no_format);
                if(!empty ($ap_dn_no)) {
                    $last_no = explode('/',$ap_dn_no['ap_dn_no']);
                    $ap_dn['ap_dn_no'] = $ap_dn_no_format . (end($last_no) +1);
                } else {
                    $ap_dn['ap_dn_no'] = $ap_dn_no_format . 1001;
                }
                // Step 1: insert in bms_fin_ap_debit_note
                $payment_detail = $this->bms_fin_ap_cn_dn_model->get_payment_detail ($ap_dn['pay_id']);
                $ap_dn['total_amount'] = $payment_detail->pay_total;
                $ap_dn['debit_note_time'] = date('H:i:s');
                $ap_dn['bank_id'] = $payment_detail->bank_id;
                $ap_dn_id = $this->bms_fin_ap_cn_dn_model->insert_ap_debit_note ($ap_dn);

                // Step 2: Take items from bms_fin_payment_items and insert in bms_fin_ap_debit_note_items table
                $payment_items_data = $this->bms_fin_ap_cn_dn_model->get_payment_items_detail ( $ap_dn['pay_id'] );
                if ( !empty($payment_items_data) ) {
                    foreach ( $payment_items_data as $payment_item_key => $payment_item_val ) {
                        $debit_note_items_data = array (
                            'ap_dn_id' => $ap_dn_id,
                            'pay_item_id' => $payment_item_val['pay_item_id'],
                        );
                        $this->bms_fin_ap_cn_dn_model->insert_ap_debit_note_items ( $debit_note_items_data );
                    }
                }

                // Step 3: update bms_fin_exp_inv_items table
                if ( !empty($payment_items_data) ) {
                    foreach ( $payment_items_data as $payment_item_key => $payment_item_val ) {
                        $exp_inv_items_data = array (
                            'paid_status' => 0,
                            'paid_amount' => $payment_item_val['paid_amount'] - $payment_item_val['pay_net_amount'],
                            'balance_amount' => $payment_item_val['balance_amount'] + $payment_item_val['pay_net_amount'],
                        );
                        $this->bms_fin_expenses_model->updateExpenceOrderItem ( $exp_inv_items_data, $payment_item_val['exp_item_id'] );
                    }
                }

                // Step 4: bms_fin_expense_invoice
                $invoice_detail = $this->bms_fin_ap_cn_dn_model->get_invoice_detail ( $payment_detail->pay_inv_id  );
                $invoice_data = array(
                    'paid_amount' => $invoice_detail->paid_amount - $payment_detail->pay_total,
                    'balance_amount' => $invoice_detail->balance_amount + $payment_detail->pay_total,
                    'inv_paid_status' => 0
                );
                $this->bms_fin_expenses_model->updateExpenceOrder ( $invoice_data, $payment_detail->pay_inv_id  );
            }

            $_SESSION['flash_msg'] = 'Debit Note '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!';
            $_SESSION['flash_class'] = 'alert-success';
        } else {
        }
        redirect ('index.php/bms_fin_ap_cn_dn/dn_list/');
    }

    public function getPVNumber() {
        header('Content-type: application/json');
        $service_provider_id = trim($this->input->post('service_provider_id'));
        $subcategory = array();
        if( $service_provider_id ) {
            $subcategory = $this->bms_fin_ap_cn_dn_model->get_pay_id ( $service_provider_id );
        }
        echo json_encode($subcategory);
    }

    public function dn_details ($ap_dn_id,$act_type = 'view') {

        $data['browser_title'] = 'Property Butler | Debit Note Details';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Debit Note Details';

        if(!empty($ap_dn_id)) {
            $data['dn_note'] = $this->bms_fin_ap_cn_dn_model->get_ap_dn_details ($ap_dn_id);
            if (!empty($data['dn_note']['ap_dn_id'])) {
                $data['dn_items'] = $this->bms_fin_ap_cn_dn_model->get_dn_items_detail($data['dn_note']['ap_dn_id']);
            }
        }
        //echo "<pre>";print_r($data['credit_note_items']);echo "</pre>";
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');

        if($act_type == 'download') {

            $this->load->library('M_pdf');

            $html = $this->load->view('finance/ap_cn_dn/dn_details_print_view',$data,true);

            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['credit_note']['credit_note_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/ap_cn_dn/dn_details_print_view',$data);
        } else {
            $this->load->view('finance/ap_cn_dn/dn_details_view',$data);
        }
    }
}