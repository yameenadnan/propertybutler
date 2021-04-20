<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bms_fin_payment extends CI_Controller {
    function __construct() {
        parent::__construct();
        if (!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
            redirect('index.php/bms_index/login?return_url=' . ($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1') . $_SERVER['REQUEST_URI']);
        }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        //$this->load->model('bms_purchase_model');
        $this->load->model('bms_masters_model');
        $this->load->model('bms_fin_masters_model');
        $this->load->model('bms_fin_payment_model');
        $this->load->model('bms_fin_expenses_model');
    }

    public function payment_list ($offset = 0, $per_page = 25) {
        
        $data['browser_title'] = 'Property Butler | Payment';
        $data['page_header']   = '<i class="fa fa-file"></i> Payment';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
    
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_payment_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_payment_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        $this->load->view('finance/payment/payment_list_view', $data); //bms_purchase_add
    }

    public function add_payment ($pv_order_id = '') {
        $data['browser_title'] = 'Property Butler | Payment Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Payment';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_payment_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_payment_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }

		if(  !empty( $pv_order_id ) ) {
            // get bms_fin_payment_items details
            $data['po_sub_items'] = $this->bms_fin_payment_model->getPaymentOrderDetails ( $pv_order_id );

            // get bms_fin_payment details
            $data['pv_item'] = $this->bms_fin_payment_model->getPaymentOrder($pv_order_id);

            // get all invioces
            $data['invnum'] = $this->bms_fin_payment_model->getExpNumber($data['pv_item']['pay_service_provider_id']);


            if ( !empty($data['pv_item'] ) ) {
                // get all items of order
               $data['pv_sub_items'] = $this->bms_fin_payment_model->getPVOrderDetails( $pv_order_id );
               if(!empty($data['pv_sub_items'])) {
                   foreach ($data['pv_sub_items'] as $key=>$val) {
                       if(!empty($val['pay_sub_cat_id'])){
                            $data['pv_sub_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory($val['pay_cat_id']);;
                       } else {
                            $data['pv_sub_items'][$key]['sub_cat_dd'] = array();
                       }
                   }
               }
            }

            $payinvid = $data['pv_item']['pay_inv_id'];
            if ( !empty($payinvid) ) {
                $purdetails = array(); $purdetails1 = array(); $purdetails2 = array();

                // get purchase order details
                $getpoid = $this->bms_fin_payment_model->getpoidchecked($payinvid);
                foreach ($getpoid as $pid => $pval) {
                    if ( $pval['po_id']!=0 ) {
                        $purdetails1 = $this->bms_fin_payment_model->getExpInvoiceDetailswithPOPrint($payinvid, $pv_order_id);
                    } else {
                        $purdetails2 = $this->bms_fin_payment_model->getExpInvoiceDetailswithoutPOPrint($payinvid, $pv_order_id);
                    }
                }

                $purdetails = array_merge($purdetails1,$purdetails2);

                $exparr = array();
                //foreach ($purdetails as $mid =>$mval){
                    $exparr = $this->bms_fin_payment_model->getInvoiceName($payinvid);
                //}
                $data['invnamedisp'] = $exparr;
                $data['expsubitem'] = $purdetails;

                /*echo '<pre>';
                print_r( $data['expsubitem'] );
                echo '</pre>';
                die;*/
            }
        }
        $this->load->view('finance/payment/payment_add_view', $data);
    }

    function get_payment_list () {
        header('Content-type: application/json');
        $poorder = array();

        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $prop_id =  $this->input->post('property_id');
            $poorder = $this->bms_fin_payment_model->getPaymentList ($_POST['offset'], $_POST['rows'], $prop_id, $search_txt);
        }
        echo json_encode($poorder);
    }

    function payment_ins_upd ($inv_id, $invoice_num) {
         
        $pvdate = $this->input->post('pv_date');
		$paymt = $this->input->post('paymt');
		$pmdetails = $this->input->post('pm_details');
		$ser_pro = $this->input->post('service_provider');
		$onlinedate = $pmdetails['online_date'];
		$cheqdate = $pmdetails['cheq_date'];
        $pvitem = $this->input->post('pv_item');
        $paymt_id = $pvitem['pay_id'];
		if($ser_pro=="new"){
			$ser_pro = 0;
		}
        $pvdated = '0000-00-00';
        if(!empty($pvdate)) {
            $pvdated = date('Y-m-d',strtotime($pvdate));
        }
        $onlndate = '0000-00-00';
        if(!empty($onlinedate)) {
            $onlndate = date('Y-m-d',strtotime($onlinedate));
        }
        $chqdate = '0000-00-00';
        if(!empty($cheqdate)) {
            $chqdate = date('Y-m-d',strtotime($cheqdate));
        }
       // if(!empty($inv_id)){
       //     $payval = 0;
      //  }else {
            $payval = 1;
       // }
        $data = array (
            'pay_property_id' => $this->input->post('po_id'),
            'pay_no' =>  $invoice_num,
            'pay_service_provider_id' => $ser_pro,
            'pay_service_provider_name' => $this->input->post('provider_name'),
            'pay_service_invoice_number' => $this->input->post('provider_inv_num'),
            'pay_service_provider_address' => $this->input->post('provider_address'),
            'pay_date' => $pvdated,
            'remarks' => $this->input->post('remarks'),
            'pay_total' => $this->input->post('maintotat'),	
            'bank_id' => $paymt['bank_id'],
            'pay_mod' => $paymt['payment_mode'],
        );

        $pm_details = $this->input->post('pm_details');
        switch ($paymt['payment_mode']) {
            case 2: 
                $data['bank'] = !empty($pm_details['cheq_bank']) ? $pm_details['cheq_bank'] : '';
                $data['cheq_card_txn_no'] = !empty($pm_details['cheq_no']) ? $pm_details['cheq_no'] : '';
                $data['cheq_txn_online_date'] = !empty($pm_details['cheq_date']) ? date('Y-m-d',strtotime($pm_details['cheq_date'])) : '';
                break;
            case 3: 
                $data['bank'] = !empty($pm_details['card_bank']) ? $pm_details['card_bank'] : '';
                $data['cheq_card_txn_no'] = !empty($pm_details['card_txn_no']) ? $pm_details['card_txn_no'] : '';
                $data['online_r_card_type'] = !empty($pm_details['card_type']) ? $pm_details['card_type'] : '';
                break;
            case 4: 
                $data['bank'] = !empty($pm_details['online_bank']) ? $pm_details['online_bank'] : '';
                $data['cheq_card_txn_no'] = !empty($pm_details['online_txn_no']) ? $pm_details['online_txn_no'] : '';
                $data['online_r_card_type'] = !empty($pm_details['online_type']) ? $pm_details['online_type'] : '';
                $data['cheq_txn_online_date'] = !empty($pm_details['online_date']) ? date('Y-m-d',strtotime($pm_details['online_date'])) : '';
                break;
        }

        if(!empty($paymt_id)) {
           // $type = "edit";
            $this->bms_fin_payment_model->updateExpenceOrder($data,$paymt_id);
            $insert_id = $paymt_id;
        }else {
            $data['pay_time'] = date("H:i:s");
            $insert_id = $this->bms_fin_payment_model->insertPaymentOrder($data);
        }
        return $insert_id;
    }

    function getPaymentId($inv_id, $invoice_num){
        $prop_abbrev = $this->input->post('prop_abbr');
        if ($inv_id=='') {
           $pur_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/PV/'.date('y/m').'/';
           $last_iv_no = $this->bms_fin_payment_model->getLastPaymentNo ($pur_no_format);
           if(!empty ($last_iv_no)) {
               $pay_no = $pur_no_format . (end(explode('/',$last_iv_no['pay_no'])) +1);
           } else {
               $pay_no = $pur_no_format . 1001;
           }
        }else{
            $pay_no = $invoice_num;
        }
        return $pay_no;
    }

    function view_receipt ($pv_order_id = '', $act_type = 'view') {

        $data['browser_title'] = 'Property Butler | Payment Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Payment';
        // $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');

        $property_detail = $this->bms_fin_payment_model->getPropertyIdFromPayId ( $pv_order_id );

        $data['pro_name'] = $property_detail['jmb_mc_name'];
        $data['pro_address'] = $property_detail['address_1'] . ' ' . $property_detail['address_2'] . "<br>" . $property_detail['phone_no'];
        $data['provid_name'] = $property_detail['provider_name'];
        $data['pv_item'] = $property_detail;
        $data['expsubitem'] = $this->bms_fin_payment_model->getPaymentItemsDetails ( $pv_order_id );



        /*
        $expsubitem
        $val['coa_name']
        $val['description']
        $val['net_amount']
        $val['net_amount'] - (
        $val['item_payable_amt'] +
        $val['item_balance_amount'])
        */

        /*if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_payment_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_payment_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }*/


        /*if( !empty($pv_order_id) ) {
            $data['pv_item'] = $this->bms_fin_payment_model->getPaymentOrder($pv_order_id);
            //$data['pvitem'] = $this->bms_fin_payment_model->getPONumber($data['pv_item']['pay_service_provider_id']);
            if(!empty($data['pv_item']['pay_id'])) {
                $data['pv_sub_items'] = $this->bms_fin_payment_model->getPVOrderDetails($data['pv_item']['pay_id']);
                if(!empty($data['pv_sub_items'])) {
                    foreach ($data['pv_sub_items'] as $key=>$val) {
                        if(!empty($val['pay_sub_cat_id'])){
                            $data['pv_sub_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory($val['pay_coa_id']);;
                        } else {
                            $data['pv_sub_items'][$key]['sub_cat_dd'] = array();
                        }
                    }
                }
            }
        }*/

        // GET Property name
       // print_r($data['properties']);

        /*foreach ($data['properties'] as $key=>$val) {
           if( $val['property_id']==$data['pv_item']['pay_property_id'] ) {
            $data['pro_name'] = $val['jmb_mc_name'];
            $data['pro_address'] = $val['address_1'] . ' ' . $val['address_2'] . "<br>" . $val['phone_no'] ;
           }
        }*/
        
 
        /*foreach($data['service_provider'] as $skey => $skval) {
        	//echo $skval['service_provider_id']."<br>";
         	 if($skval['service_provider_id']==$data['pv_item']['pay_service_provider_id']) {
            	$data['provid_name'] = $skval['provider_name'];
           	}
		}*/

       /* // GEt Invoice details
        if($data['pv_item']['pay_inv_id']>0){
            $data['expsubitem'] = $this->bms_fin_payment_model->getPaymentBasedINVdetails($data['pv_item']['pay_inv_id']);
        }*/

        /*$payinvid = $data['pv_item']['pay_inv_id'];
        if(!empty($payinvid)) {
            $purdetails = array(); $purdetails1 = array(); $purdetails2 = array();
            $getpoid = $this->bms_fin_payment_model->getpoidchecked($payinvid);
            foreach ($getpoid as $pid => $pval){
                if($pval['po_id']!=0){
                    $purdetails1 = $this->bms_fin_payment_model->getExpInvoiceDetailswithPOPrint($payinvid, $pv_order_id);
                }else {
                    $purdetails2 = $this->bms_fin_payment_model->getExpInvoiceDetailswithoutPOPrint($payinvid, $pv_order_id);
                }
            }
            $purdetails = array_merge($purdetails1,$purdetails2);

            $exparr = array();
            //foreach ($purdetails as $mid =>$mval){
            $exparr = $this->bms_fin_payment_model->getInvoiceName($payinvid);
            //}
            $data['invnamedisp'] = $exparr;
            $data['expsubitem'] = $purdetails;

        }*/








        $data ['act_type'] = $act_type;
        if($act_type == 'download') {
            $this->load->library('M_pdf');
            $html = $this->load->view('finance/payment/payment_receipt_pdf',$data,true);
            $res = $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['pv_item']['pay_no'].'.pdf', 'D');
        } else if($act_type == 'print') {

            $this->load->view('finance/payment/payment_receipt',$data);
        }
         //$this->load->view('finance/payment/payment_receipt', $data); //bms_purchase_add
    }
	
	public function payment_popup_view ($pv_order_id = '') {
        $data['browser_title'] = 'Property Butler | Payment Orders';
        $data['page_header']   = '<i class="fa fa-file"></i> Payment';
        $data['properties']  = $this->bms_masters_model->getMyProperties();
        $data['property_id'] = isset($_GET['property_id']) ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        if(!empty($data['property_id'])) {
            $data['service_provider'] = $this->bms_fin_payment_model->getServiceProvider($data['property_id']);
            $data['property_assets'] = $this->bms_fin_payment_model->getPropertyAssets($data['property_id']);
            $data['expense_items'] = $this->bms_fin_masters_model->getExpenseItems ($_SESSION['bms_default_property']);
        }
        
		if(!empty($pv_order_id)) {
            $data['pv_item'] = $this->bms_fin_payment_model->getPaymentOrder($pv_order_id);
            $data['invnum'] = $this->bms_fin_payment_model->getExpNumber($data['pv_item']['pay_service_provider_id']);
            if(!empty($data['pv_item']['pay_id'])) {
                   $data['pv_sub_items'] = $this->bms_fin_payment_model->getPVOrderDetails($data['pv_item']['pay_id']);
                   if(!empty($data['pv_sub_items'])) {
                       foreach ($data['pv_sub_items'] as $key=>$val) {
                           if(!empty($val['pay_sub_cat_id'])){
                                $data['pv_sub_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory($val['pay_cat_id']);;
                           } else {
                                $data['pv_sub_items'][$key]['sub_cat_dd'] = array();
                           }
                       }
                   }
            }
            if($data['pv_item']['pay_inv_id']>0){
                $data['expsubitem'] = $this->bms_fin_payment_model->getPaymentBasedINVdetails($data['pv_item']['pay_inv_id']);
            }
        }


        $this->load->view('finance/payment/payment_popup_view', $data);
    }

    function add_payment_order () {

        // echo "<pre>";print_r($_POST);echo "</pre>";die;

        $po_select_po_no = $this->input->post('po_select_po_no');
        $pv_number = $this->input->post('pv_number');
        $item_val = $this->input->post('items');

        if ( !empty( $_POST['exp_inv_no'] ) ) {

             $inv_paid_status = 1;

			 $pvnum = implode(",",$pv_number);
             $invoice_num = $this->getPaymentId("", "");
             $data_inv_item = array();
             $data_inv = array();
             $paidat = 0;
             $data_inv['balance_amount'] = 0;
             $data_inv['paid_amount'] = 0;
             foreach ( $item_val['exp_item_id'] as $key=>$val ) {

                 $data_inv_item['paid_status'] = 0;
                 if ($item_val['bal_amount'][$key] == '0') {
                     $data_inv_item['paid_status'] = 1;
                 }
                 $data_inv_item['paid_amount'] = $item_val['pay_amount'][$key] + $item_val['settled_amt'][$key];
                 $data_inv_item['balance_amount'] = $item_val['bal_amount'][$key];
                 $this->bms_fin_payment_model->updateItemPayAmount ($data_inv_item, $item_val['exp_item_id'][$key] );

                 $data_inv['paid_amount'] += $data_inv_item['paid_amount'];
                 $data_inv['balance_amount'] += $data_inv_item['balance_amount'];
			 }
             if ( $data_inv['balance_amount'] > 0 )
                $data_inv['inv_paid_status'] = 0;
             else
                $data_inv['inv_paid_status'] = 1;

			 $this->bms_fin_payment_model->updatePayAmount ($data_inv, $_POST['exp_inv_no'] );

             $invdate = $this->input->post('pv_date');
             if (!empty($invdate)) {
                 $invdate = date('Y-m-d', strtotime($invdate));
             }
             $pvdate = $this->input->post('pv_date');
             $paymt = $this->input->post('paymt');
             $pmdetails = $this->input->post('pm_details');
             $ser_pro = $this->input->post('service_provider');
             $onlinedate = $pmdetails['online_date'];
             $cheqdate = $pmdetails['cheq_date'];
             $pvitem = $this->input->post('pv_item');
             $paymt_id = $pvitem['pay_id'];

             $pvdated = '0000-00-00';
             if (!empty($pvdate)) {
                 $pvdated = date('Y-m-d', strtotime($pvdate));
             }
             $onlndate = '0000-00-00';
             if (!empty($onlinedate)) {
                 $onlndate = date('Y-m-d', strtotime($onlinedate));
             }
             $chqdate = '0000-00-00';
             if (!empty($cheqdate)) {
                 $chqdate = date('Y-m-d', strtotime($cheqdate));
             }
             if (!empty($inv_id)) {
                 $payval = 0;
             } else {
                 $payval = 1;
             }

             $paytotamt = $this->input->post('curtot');
             if(!empty($paytotamt)){
                 $paidtotamt = $paytotamt;
             }else {
                 $paidtotamt = $paidat;
             }

            $data = array (
                'pay_property_id' => $this->input->post('po_id'),
                'pay_service_provider_id' => $ser_pro,
                'pay_date' => $invdate,
                'pay_inv_id' => $pvnum,
                'remarks' => $this->input->post('remarks'),
                'pay_total' => $this->input->post('total'),
                'bank_id' => $paymt['bank_id'],
                'pay_mod' => $paymt['payment_mode'],
            );

            if ( !empty($paymt_id) ) {

            } else {
                $data['pay_no'] = $invoice_num;
            }

             $pm_details = $this->input->post('pm_details');
             switch ($paymt['payment_mode']) {
                 case 2:
                     $data['bank'] = !empty($pm_details['cheq_bank']) ? $pm_details['cheq_bank'] : '';
                     $data['cheq_card_txn_no'] = !empty($pm_details['cheq_no']) ? $pm_details['cheq_no'] : '';
                     $data['cheq_txn_online_date'] = !empty($pm_details['cheq_date']) ? date('Y-m-d',strtotime($pm_details['cheq_date'])) : '';
                     break;
                 case 3:
                     $data['bank'] = !empty($pm_details['card_bank']) ? $pm_details['card_bank'] : '';
                     $data['cheq_card_txn_no'] = !empty($pm_details['card_txn_no']) ? $pm_details['card_txn_no'] : '';
                     $data['online_r_card_type'] = !empty($pm_details['card_type']) ? $pm_details['card_type'] : '';
                     break;
                 case 4:
                     $data['bank'] = !empty($pm_details['online_bank']) ? $pm_details['online_bank'] : '';
                     $data['cheq_card_txn_no'] = !empty($pm_details['online_txn_no']) ? $pm_details['online_txn_no'] : '';
                     $data['online_r_card_type'] = !empty($pm_details['online_type']) ? $pm_details['online_type'] : '';
                     $data['cheq_txn_online_date'] = !empty($pm_details['online_date']) ? date('Y-m-d',strtotime($pm_details['online_date'])) : '';
                     break;
             }
             if ( !empty($paymt_id) ) {
                 $type = "edit";
                 $this->bms_fin_payment_model->updateExpenceOrder ($data, $paymt_id);
                 $pay_id = $paymt_id;

                 foreach ( $item_val['pay_item_id'] as $key=>$val ) {
                     $data_paynent_item = array (
                         'pay_net_amount' => $item_val['pay_amount'][$key],
                     );
                     $this->bms_fin_payment_model->updatePaymentOrderItem ($data_paynent_item, $item_val['pay_item_id'][$key]);
                 }
             } else {
                 $type = "add";
                 $data['pay_time'] = date("H:i:s");
                 $pay_id = $this->bms_fin_payment_model->insertPaymentOrder ($data);

                 foreach ( $item_val['exp_item_id'] as $key=>$val ) {
                     $data_paynent_item = array (
                         'pay_id' => $pay_id,
                         'pay_coa_id' => $item_val['pay_coa_id'][$key],
                         'pay_net_amount' => !empty($item_val['pay_amount'][$key])? $item_val['pay_amount'][$key]:0,
                         'exp_inv_item_id' => $item_val['exp_item_id'][$key],
                     );
                     $this->bms_fin_payment_model->insertPaymentOrderItemFromInvoice ($data_paynent_item);
                 }
             }
        } else {
            $pay_no = $this->input->post('pay_no');
            $pv_item = $this->input->post('pv_item');
            //echo "pay_no ==> ".$pv_item['pay_no'];
            $pay_inv_id = $pv_item['pay_id'];
            $invoice_num = $pv_item['pay_no'];
            $invoice_num = $this->getPaymentId($pay_inv_id, $invoice_num);
            $insert_payid = $this->payment_ins_upd ($pay_inv_id, $invoice_num);
            $items = $this->input->post('items');
            foreach ($items['description'] as $key=>$val) {
                $item['pay_description'] = $val;
                $item['pay_id'] = $insert_payid;
                $item['pay_asset_id'] = $items['assetlst'][$key];
                $item['pay_coa_id'] = $items['category'][$key];
                $item['pay_qty'] = $items['quantity'][$key];
                $item['pay_uom'] = $items['uom'][$key];
                $item['pay_unit_price'] = $items['subunitprice'][$key];
                $item['pay_amount'] = $items['amount'][$key];
                $item['pay_tax_percent'] = $items['pay_tax_percent'][$key];
                $item['pay_tax_amt'] = $items['pay_tax_amt'][$key];
                $item['pay_discount_amt'] = $items['distamount'][$key];
                $item['pay_net_amount'] = $items['netamount'][$key];
                $pay_item_id = $items['pay_item_id'][$key];

               // echo "RESULT ==>".$exp_item_id."<br>";
                if ($pay_item_id>0) {
                    $type = 'edit';
                    //echo "inside record for UPDATE===><br><br><br>";
                    $this->bms_fin_payment_model->updatePaymentOrderItem($item,$pay_item_id);
                } else {
                    $type = 'add';
                   // echo "inside record for INSERT ===> <br><br><br>";
                    $data['pay_time'] = date("H:i:s");
                    $this->bms_fin_payment_model->insertPaymentOrderItem ($item);
                }
            }
        }

        $_SESSION['flash_msg'] = 'Payment Order '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!';
        redirect ('index.php/bms_fin_payment/payment_list');

    }

    function unset_payment () {

        // echo "<pre>";print_r($_POST);echo "</pre>";die;

        $pay_id = $this->input->post('pay_id');
        $payment_detail = $this->bms_fin_payment_model->get_pay_inv_id_from_pay_id ( $pay_id );
        if ( !empty ($payment_detail) ) {
            $pay_inv_id = $payment_detail['pay_inv_id'];

            if ( trim($pay_inv_id) == '' ) {
                // Direct payment. simply delete values from bms_fin_payment and bms_fin_payment_items
                $this->bms_fin_payment_model->deletePaymentItem($pay_id);
                echo $this->bms_fin_payment_model->deletePayment($pay_id);
            } else {
                // Invoiced payment. Perform business logic as per discussion

                // update bms_fin_exp_inv_items table
                $payment_items_detail = $this->bms_fin_payment_model->get_payment_items_from_pay_id ( $pay_id );
                if ( !empty ($payment_items_detail) ) {
                    foreach ( $payment_items_detail as $key=>$val ) {
                        $exp_inv_items_detail = $this->bms_fin_payment_model->get_exp_inv_items_detail ( $val['exp_inv_item_id'] );
                        $paid_amount = $exp_inv_items_detail['paid_amount'] - $val['pay_net_amount'];
                        $balance_amount = $exp_inv_items_detail['balance_amount'] + $val['pay_net_amount'];
                        if ( $balance_amount == 0 )
                            $paid_status = 1;
                        else
                            $paid_status = 0;

                        $exp_inv_item_data = array (
                            'paid_amount' => $paid_amount,
                            'balance_amount' => $balance_amount,
                            'paid_status' => $paid_status,
                        );

                        $this->bms_fin_payment_model->updateItemPayAmount( $exp_inv_item_data, $val['exp_inv_item_id'] );
                    }
                }

                // update expense_invoice table
                $pay_total = $payment_detail['pay_total'];
                $expense_invoice_details = $this->bms_fin_payment_model->get_expense_invoice_detail ($pay_inv_id);
                $paid_amount = $expense_invoice_details['paid_amount'];
                $inv_paid_status = $expense_invoice_details['inv_paid_status'];
                $total = $expense_invoice_details['total'];
                $paid_amount = $paid_amount - $pay_total;
                $balance_amount = $total - $paid_amount;
                if ( $balance_amount == 0  ) {
                    $inv_paid_status = 1;
                } else {
                    $inv_paid_status = 0;
                }

                $data = array (
                    'paid_amount' => $paid_amount,
                    'balance_amount' => $balance_amount,
                    'inv_paid_status' => $inv_paid_status
                );

                $this->bms_fin_expenses_model->updateExpenceOrder ($data,$pay_inv_id);

                // Delete payment_items and delete payment
                $this->bms_fin_payment_model->deletePaymentItem($pay_id);
                $this->bms_fin_payment_model->deletePayment($pay_id);
            }
        }
    }

    public function getINVorderNumber(){
        header('Content-type: application/json');
        $category_id = trim($this->input->post('servprov_id'));
        $subcategory = array();
        if($category_id) {
            $subcategory = $this->bms_fin_payment_model->getINVNumber($category_id);
        }
        echo json_encode($subcategory);
    }

    public function getInvoiceOrderDetails(){
       header('Content-type: application/json');
        $inv_id = $this->input->post('po_id');
        //echo "inside => ".$inv_id;
        $purdetails = array();
        if($inv_id) {
            $purdetails = $this->bms_fin_payment_model->getPaymentBasedINVdetails($inv_id);
        }
        echo json_encode($purdetails);
    }

    public function getExpInvoiceDetails () {
        header('Content-type: application/json');
        $inv_id = $this->input->post('po_id');
        $purdetails = array(); $purdetails1 = array(); $purdetails2 = array();
        if($inv_id) {
            $getpoid = $this->bms_fin_payment_model->getpoidchecked($inv_id);
            foreach ($getpoid as $pid => $pval) {
                if($pval['po_id']!=0) {
                    $purdetails1 = $this->bms_fin_payment_model->getExpInvoiceDetailswithPO($inv_id);
                }else {
                    $purdetails2 = $this->bms_fin_payment_model->getExpInvoiceDetailswithoutPO($inv_id);
                }
            }

            $purdetails = array_merge($purdetails1,$purdetails2);
        }
        echo json_encode($purdetails);
    }


     function getBanks () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $banks = array ();
        if($property_id) {
            $banks = $this->bms_fin_masters_model->getBanks($property_id);       
        }
        echo json_encode($banks);
    }

    public function getSubCategoryList(){
        header('Content-type: application/json');
        $category_id = trim($this->input->post('category_id'));
        $subcategory = array();
        if($category_id) {
            $subcategory = $this->bms_fin_masters_model->getSubCategory($category_id);
        }
        echo json_encode($subcategory);
    }

    function validate_new_user_invoice_number () {
        $pay_id = $this->input->post('pay_id');
        $pay_service_invoice_number = $this->input->post('pay_service_invoice_number');
        $pay_property_id = trim($this->input->post('pay_property_id'));
        $total_records = $this->bms_fin_payment_model->validate_new_user_invoice_number($pay_property_id, $pay_service_invoice_number, $pay_id);
        echo $total_records;
    }

    function payment_summary () {
        $data['browser_title'] = 'Property Butler | Summary Of Payment';
        $data['page_header'] = '<i class="fa fa-file"></i> Summary Of Payment';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();

        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        //$data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
        if(!empty($_GET['property_id']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            //echo "query error!"; exit;
            $from = date('Y-m-d',strtotime(trim($_GET['from'])));
            $to = date('Y-m-d',strtotime(trim($_GET['to'])));

            // common function helper
            $this->load->helper('common_functions');

            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = $val['property_name'];
                }
            }

            $data['payments'] = $this->bms_fin_payment_model->getPaymentForSummary ($from, $to, $_GET['property_id']);

            require_once APPPATH.'/third_party/PHPExcel.php';
            require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';

            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);

            $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Payments: '.$_GET['from'] .' - '.$_GET['to']);
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dated: '.date('d-m-Y h:i a'));
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');

            $sheet_head = array('PV No',
                'Date',
                'Amount',
                'Issued By',
                'Payment Mode',
                'Notes',
                'Bank Name',
                'Service Provider'
            );
            $sales_item = array ();

            $sales_item['sales_tot'] = end($sales_item) + 1;

            $tot_col = count($sheet_head);
            $excel_last_col = getExcelColNameFromNumber($tot_col -1);
            $objPHPExcel->getActiveSheet()->getStyle('A5:'.$excel_last_col.'5')->getFont()->setBold(true)->setSize(12);

            // Filling headers
            $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A5');

            if(!empty( $data['payments'] )) {
                $row = 6;
                foreach ( $data['payments'] as $key_receipt => $val_receipt ) {
                    $pmode[$val_receipt['pmode_name']][] = $val_receipt['pay_total'];
                    $data['payments'][$key_receipt]['pay_date'] = date("d-m-Y", strtotime($val_receipt['pay_date']));
                }

                $objPHPExcel->getActiveSheet()->fromArray($data['payments'], NULL, 'A6');
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('Payments');
                $rows_cnt = count( $data['payments'] );

                // Filling Grand total of D (Amount Received) and K(Open Credit) columns
                $objPHPExcel->getActiveSheet()->getStyle('B'.(6+$rows_cnt).':C'.(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$rows_cnt), 'Total' );
                $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$rows_cnt),'=SUM(C6:C'.(5+$rows_cnt).')');
                $objPHPExcel->getActiveSheet()->getStyle('C6:C'.(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                // Filling Account modes
                $pmode_counter = 10;
                $pmode_total = 0;
                foreach ( $pmode as $pmode_key =>  $pmode_val ) {
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.($pmode_counter+$rows_cnt), $pmode_key );
                    $objPHPExcel->getActiveSheet()->getStyle('B'.($pmode_counter+$rows_cnt))->getFont()->setBold(true)->setSize(12);

                    $objPHPExcel->getActiveSheet()->setCellValue('C'.($pmode_counter+$rows_cnt), array_sum( $pmode_val ) );
                    $objPHPExcel->getActiveSheet()->getStyle('C'.($pmode_counter+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.($pmode_counter+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $pmode_total += array_sum( $pmode_val );
                    $pmode_counter++;
                }
                // Filling Account modes total
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($pmode_counter+$rows_cnt +1 ), 'Total' );
                $objPHPExcel->getActiveSheet()->getStyle('B'.($pmode_counter+$rows_cnt +1 ))->getFont()->setBold(true)->setSize(12);

                $objPHPExcel->getActiveSheet()->setCellValue('C'.($pmode_counter+$rows_cnt +1 ), $pmode_total );
                $objPHPExcel->getActiveSheet()->getStyle('C'.($pmode_counter+$rows_cnt +1))->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle('C'.($pmode_counter+$rows_cnt +1 ))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No Data Found!' );
                $objPHPExcel->getActiveSheet()->mergeCells('A6:G6');
            }

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="PaymentList_'.$property_name.$_GET['from'] .' - '.$_GET['to'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }

        $this->load->view ('finance/payment/payment_summary_view',$data);
    }

}
