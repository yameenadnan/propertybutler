<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_dp_receive extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_dp_receive_model');
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

   
	public function dp_receive_list ($offset = 0, $per_page = 25) {
		
		$data['browser_title'] = 'Property Butler | Deposit Receive';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Receive';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);        
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/dp_receive/dp_receive_list_view',$data);
	}
    
    function get_dp_receive_list () {
        header('Content-type: application/json');        
        
        $bills = array('numFound'=>0,'records'=>array());
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            $bills = $this->bms_fin_dp_receive_model->getDpReceiveList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $unit_id,$from,$to);
        }       
        echo json_encode($bills);
    } 
    
    
	public function add_dp_receive($depo_receive_id = '', $act_type = ''){
	
		$data['act_type'] = $act_type;
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Deposit Receive';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Receive';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        if(!empty($depo_receive_id)) {
            $data['dp_receive'] = $this->bms_fin_dp_receive_model->getDpReceive($depo_receive_id);
            if(!empty($data['dp_receive']['depo_receive_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['dp_receive']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['dp_receive']['property_id'],$data['dp_receive']['block_id']);   
                $data['banks'] = $this->bms_fin_masters_model->getBanksForReceipt ($data['dp_receive']['property_id']);
                //$data['dp_receive_items'] = $this->bms_fin_dp_receive_model->getDpReceiveItems($data['dp_receive']['depo_receive_id']);
                           
            }            
        }
        //echo "<pre>";print_r($data);echo "</pre>";
        $data ['property_id'] = !empty($data['dp_receive']['property_id']) ? $data['dp_receive']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        $data['sales_items'] = $this->bms_fin_masters_model->getDpItems ($data ['property_id']);            
        $this->load->view('finance/dp_receive/dp_receive_add_view',$data);
	}
    
    function add_dp_receive_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        $dp_receive = $this->input->post('dp_receive');
        $items = $this->input->post('items');
        
        $type = 'add';
        if(!empty($dp_receive['deposit_date'])) {
            $dp_receive['deposit_date'] = date('Y-m-d',strtotime($dp_receive['deposit_date']));
        }
        $pm_details = $this->input->post('pm_details');
        switch ($dp_receive['payment_mode']) {
            case 2: 
                $dp_receive['bank'] = !empty($pm_details['cheq_bank']) ? $pm_details['cheq_bank'] : '';
                $dp_receive['cheq_card_txn_no'] = !empty($pm_details['cheq_no']) ? $pm_details['cheq_no'] : '';
                $dp_receive['cheq_txn_online_date'] = !empty($pm_details['cheq_date']) ? date('Y-m-d',strtotime($pm_details['cheq_date'])) : '';
                break;
            case 3: 
                $dp_receive['bank'] = !empty($pm_details['card_bank']) ? $pm_details['card_bank'] : '';
                $dp_receive['cheq_card_txn_no'] = !empty($pm_details['card_txn_no']) ? $pm_details['card_txn_no'] : '';
                $dp_receive['online_r_card_type'] = !empty($pm_details['card_type']) ? $pm_details['card_type'] : '';
                break;
            case 4: 
                $dp_receive['bank'] = !empty($pm_details['online_bank']) ? $pm_details['online_bank'] : '';
                $dp_receive['cheq_card_txn_no'] = !empty($pm_details['online_txn_no']) ? $pm_details['online_txn_no'] : '';
                $dp_receive['online_r_card_type'] = !empty($pm_details['online_type']) ? $pm_details['online_type'] : '';
                $dp_receive['cheq_txn_online_date'] = !empty($pm_details['online_date']) ? date('Y-m-d',strtotime($pm_details['online_date'])) : '';
                break;
        }   
        
        
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
        $_SESSION['flash_msg'] = 'Deposit Receive '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_dp_receive/dp_receive_details/'.$depo_receive_id);
    }   
    
    function dp_receive_details ($depo_receive_id,$act_type = 'view') {
        $data['browser_title'] = 'Property Butler | Deposit Receive Details';
        $data['page_header'] = '<i class="fa fa-file"></i> Deposit Receive Details';   
        if(!empty($depo_receive_id)) {
            $data['dp_receive'] = $this->bms_fin_dp_receive_model->getDpReceiveDetails($depo_receive_id);
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
    
    function unset_dp_receive () {
        $depo_receive_id = $this->input->post('depo_receive_id');        
        echo $this->bms_fin_dp_receive_model->deleteDpReceive ($depo_receive_id);
    }
    
    
    function dp_receive_summary () {
        $data['browser_title'] = 'Property Butler | Summary Of Deposit';
        $data['page_header'] = '<i class="fa fa-file"></i> Summary Of Deposit';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        if (!empty($_GET['property_id']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            //echo "query error!"; exit;
            $from = date('Y-m-d',strtotime(trim($_GET['from'])));
            $to = date('Y-m-d',strtotime(trim($_GET['to']))); 
            
            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = $val['property_name'];
                }
            }

            if ( !empty($_GET['dp_receives']) ) {

                $data['dp_receives'] = $this->bms_fin_dp_receive_model->getDpReceiveForSummary($_GET['property_id'],$from,$to);
                $data['sales_items'] = $this->bms_fin_masters_model->getDpItems ( $_GET['property_id'] );
                //echo "<pre>";print_r($data['dp_receives']);echo "</pre>";exit;
                require_once APPPATH.'/third_party/PHPExcel.php';
                require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';

                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();

                // Create a first sheet, representing sales data
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                //$objPHPExcel->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);

                $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
                $objPHPExcel->getActiveSheet()->setCellValue('A2', 'DpReceives: '.$_GET['from'] .' - '.$_GET['to']);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dated: '.date('d-m-Y h:i a'));
                $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');

                $sheet_head = array('DpReceive No',
                    'Date',
                    'Unit No',
                    'Received From',
                    'Amount Received',
                    'Payment Mode',
                    'Issued By',
                    'Created Date');

                $sales_item = array ();
                foreach ( $data['sales_items'] as $key=>$val ) {
                    array_push( $sheet_head,$val['coa_name'] );
                    $sales_item[$val['coa_id']] =  8+$key;
                }

                array_push ($sheet_head,'Total');
                $sales_item['sales_tot'] = end($sales_item);
                $tot_col = count($sheet_head) - 1;
                $objPHPExcel->getActiveSheet()->getStyle('A5:m5')->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A5');
                if ( !empty( $data['dp_receives'] ) ) {

                    $row = 6;
                    foreach ( $data['dp_receives'] as $key_receipt => $val_receipt ) {

                        $data['receipt_items'] = $this->bms_fin_dp_receive_model->getDepositeReceiveItemDetail ( $val_receipt['depo_receive_id'] );

                        $total = 0;
                        foreach ( $data['receipt_items'] as $key_items => $val_items ) {
                            // Filling different accounts
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $sales_item[$val_items['item_cat_id']], $row, $val_items['paid_amount']);
                            $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($sales_item[$val_items['item_cat_id']]) . $row . ':' . getExcelColNameFromNumber($sales_item[$val_items['item_cat_id']]).($row))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $total += $val_items['paid_amount'];
                        }

                        // Filling different account total on right side
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $tot_col, $row, $total);
                        $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col) . $row . ':' . getExcelColNameFromNumber($tot_col).($row))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber($tot_col))->setAutoSize(true);

                        $row++;

                        unset($val_receipt['depo_receive_id']);
                        $data['dp_receives'][$key_receipt] = $val_receipt;
                        $pmode[$val_receipt['pmode_name']][] = $val_receipt['amount'];
                    }

                    $objPHPExcel->getActiveSheet()->fromArray($data['dp_receives'], NULL, 'A6');
                    $rows_cnt = count($data['dp_receives']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$rows_cnt), 'Total' );
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$rows_cnt),'=SUM(E6:E'.(5+$rows_cnt).')');
                    $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$rows_cnt).':E'.(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);

                    // Filling Grand Total below all columns
                    foreach ( $data['sales_items'] as $key=>$val ) {
                        $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber(8+$key).(6+$rows_cnt),'=SUM(' . getExcelColNameFromNumber(8+$key) . '6:' . getExcelColNameFromNumber(8+$key) .(5+$rows_cnt).')');
                        $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber(8+$key) . '6:' . getExcelColNameFromNumber(8+$key).(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber(8+$key).(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                        $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber(8+$key))->setAutoSize(true);
                    }

                    // Filling Grand Total on bottom right only
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($tot_col).(6+$rows_cnt),'=SUM(' . getExcelColNameFromNumber($tot_col) . '6:' . getExcelColNameFromNumber($tot_col ) .(5+$rows_cnt).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col).(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col) . '6:' . getExcelColNameFromNumber($tot_col).(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber($tot_col))->setAutoSize(true);

                    // Filling Account modes
                    $pmode_counter = 10;
                    $pmode_total = 0;
                    foreach ( $pmode as $pmode_key =>  $pmode_val ) {
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.($pmode_counter+$rows_cnt), $pmode_key );
                        $objPHPExcel->getActiveSheet()->getStyle('D'.($pmode_counter+$rows_cnt))->getFont()->setBold(true)->setSize(12);

                        $objPHPExcel->getActiveSheet()->setCellValue('E'.($pmode_counter+$rows_cnt), array_sum( $pmode_val ) );
                        $objPHPExcel->getActiveSheet()->getStyle('E'.($pmode_counter+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.($pmode_counter+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                        $pmode_total += array_sum( $pmode_val );
                        $pmode_counter++;
                    }
                    // Filling Account modes total
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.($pmode_counter+$rows_cnt +1 ), 'Total' );
                    $objPHPExcel->getActiveSheet()->getStyle('D'.($pmode_counter+$rows_cnt +1 ))->getFont()->setBold(true)->setSize(12);

                    $objPHPExcel->getActiveSheet()->setCellValue('E'.($pmode_counter+$rows_cnt +1 ), $pmode_total );
                    $objPHPExcel->getActiveSheet()->getStyle('E'.($pmode_counter+$rows_cnt +1))->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.($pmode_counter+$rows_cnt +1 ))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No Data Found!' );
                    $objPHPExcel->getActiveSheet()->mergeCells('A6:H6');
                }

                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('DpReceives');

                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="DpReceiveList_'.$property_name.$_GET['from'] .' - '.$_GET['to'].'_'.date('Ymd').'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');

            }
            elseif ( !empty($_GET['dp_refund']) ) {

                $data['dp_refund'] = $this->bms_fin_dp_receive_model->getDpRefundForSummary($_GET['property_id'],$from,$to);

                //echo "<pre>";print_r($data['dp_receives']);echo "</pre>";exit;
                require_once APPPATH.'/third_party/PHPExcel.php';
                require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';

                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();

                // Create a first sheet, representing sales data
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                //$objPHPExcel->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);

                $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
                $objPHPExcel->getActiveSheet()->setCellValue('A2', 'DpRefund: '.$_GET['from'] .' - '.$_GET['to']);
                $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dated: '.date('d-m-Y h:i a'));
                $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');

                $sheet_head = array('DpRefund No',
                    'Date',
                    'Unit No',
                    'Refund From',
                    'Amount Refunded',
                    'Deposite Number',
                    'Deposite Amount',
                    'Balannce',
                    'Refund Type',
                    'Issued By',
                    'Created Date');
                $objPHPExcel->getActiveSheet()->getStyle('A5:K5')->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A5');
                if(!empty( $data['dp_refund'] )) {
                    $objPHPExcel->getActiveSheet()->fromArray($data['dp_refund'], NULL, 'A6');
                    $rows_cnt = count( $data['dp_refund'] );
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$rows_cnt), 'Total' );
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$rows_cnt),'=SUM(E6:E'.(5+$rows_cnt).')');
                    $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$rows_cnt).':E'.(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No Data Found!' );
                    $objPHPExcel->getActiveSheet()->mergeCells('A6:K6');
                }

                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('DpRefund');

                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="DpRefundList_'.$property_name.$_GET['from'] .' - '.$_GET['to'].'_'.date('Ymd').'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            }
        }
        $this->load->view ('finance/dp_receive/dp_receive_summary_view',$data);
    }
}