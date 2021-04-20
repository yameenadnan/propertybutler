<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_bills extends CI_Controller {
   
    function __construct () {
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_bills_model');
        $this->load->model('bms_fin_coa_model');
        $this->load->model('bms_property_model');
        $this->load->model('bms_fin_cron_jobs_model');

        $this->load->library('soa_emails');
        $this->load->helper('common_functions');
    }

	public function manual_bill_list ($offset = 0, $per_page = 25) {
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Sales Invoice';
        $data['page_header'] = '<i class="fa fa-file"></i> Sales Invoice';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);

        $data['sales_items'] = $this->bms_fin_masters_model->getSalesItemsForBill ($data ['property_id']);

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        $this->load->view('finance/manual_bill/manual_bill_list_view',$data);
	}

    function get_bills_list () {
        header('Content-type: application/json');
        $bills = array('numFound'=>0,'records'=>array());
        if ( (isset($_POST['coa_id'])) ) {

            if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
                $unit_id = $this->input->post('unit_id');
                $from = $this->input->post('from');
                $to = $this->input->post('to');
                $coa_id = $this->input->post('coa_id');
                $bill_no = $this->input->post('bill_no');
                $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
                $to = !empty($to) ? date('Y-m-d',strtotime($to)) : '';
                $bills = $this->bms_fin_bills_model->getBillItemList ( $_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $coa_id, $unit_id,$from,$to, $bill_no );
            }
        } else {
            if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
                $unit_id = $this->input->post('unit_id');
                $from = $this->input->post('from');
                $to = $this->input->post('to');
                $bill_no = $this->input->post('bill_no');
                $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
                $to = !empty($to) ? date('Y-m-d',strtotime($to)) : '';
                $bills = $this->bms_fin_bills_model->getBillsList ( $_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $unit_id,$from,$to, $bill_no );
            }
        }

        echo json_encode($bills);
    }

    function get_bill_item_list () {
        header('Content-type: application/json');

        $bills = array('numFound'=>0,'records'=>array());
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $coa_id = $this->input->post('coa_id');
            $bill_no = $this->input->post('bill_no');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : '';
            $bills = $this->bms_fin_bills_model->getBillItemList ( $_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $coa_id, $unit_id,$from,$to, $bill_no );
        }
        echo json_encode($bills);
    }

    public function download_manual_bill_list () {
        $data['browser_title'] = 'Property Butler | Sales Invoice';
        $data['page_header'] = '<i class="fa fa-file"></i> Sales Invoice';
        $unit_id = $this->input->get('unit_id');
        $bill_no = $this->input->get('bill_no');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $coa_id = $this->input->get('coa_id');


        $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
        $to = !empty($to) ? date('Y-m-d',strtotime($to)) : '';
        // common function helper

        $invoice_detail = '';
        if ($unit_id != '') {
            $invoice_detail .= " Unit #: " . $this->input->get('unit_no');
        }

        if ($coa_id != '') {
            $invoice_detail .= $invoice_detail != '' ? ' - ' : '';
            $invoice_detail .=  ' Item: ' . $this->input->get('coa_name');
        }

        if ($bill_no != '') {
            $invoice_detail .= ($invoice_detail != '')? ' - ' : '';
            $invoice_detail .= ' Invoice #: ' . $bill_no;
        }

        if($from != '' && $to != '') {
            $invoice_detail .= ($invoice_detail != '')? ' - ' : '';
            $invoice_detail .= ' Date (From): ' . $this->input->get('from') . ' - Date(to): ' . $this->input->get('to');
        } else if($from != '' && $to == '') {
            $invoice_detail .= ($invoice_detail != '')? ' - ' : '';
            $invoice_detail .= ' Date (From): ' . $this->input->get('from');
        } else if($from == '' && $to != '') {
            $invoice_detail .= ($invoice_detail != '')? ' - ' : '';
            $invoice_detail .= ' Date (To): ' . $this->input->get('to');
        }

        $this->load->helper('common_functions');

        $data['properties'] = $this->bms_masters_model->getMyProperties ();

        $property_name = '';
        foreach ($data['properties'] as $key=>$val) {
            if($val['property_id'] == $_GET['property_id'] ) {
                $property_name = $val['property_name'];
            }
        }

        if ( isset( $_GET['coa_id']) && $_GET['coa_id'] != '' ) {

            $coa_id = $this->input->get('coa_id');
            $data['receipts'] = $this->bms_fin_bills_model->getBillItemListExcel ($this->input->get('property_id'), $coa_id, $unit_id,$from,$to, $bill_no);

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

            $objPHPExcel->getActiveSheet()->setCellValue ('A2', 'Invoice (Filter 1-2-3): '. $invoice_detail);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dated: '.date('d-m-Y h:i a'));
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');

            $sheet_head = array ('Invoice No',
                'Date',
                'Unit No',
                'Name',
                'Total Amount',
                'Issued By'
            );

            $tot_col = count($sheet_head);
            $excel_last_col = getExcelColNameFromNumber($tot_col -1);
            $objPHPExcel->getActiveSheet()->getStyle('A5:'.$excel_last_col.'5')->getFont()->setBold(true)->setSize(12);

            // Filling headers
            $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A5');
            if ( !empty($data['receipts']) ) {
                $row = 6;

                $objPHPExcel->getActiveSheet()->fromArray($data['receipts'], NULL, 'A6');

                $rows_cnt = count($data['receipts']);

                // Filling Grand total of D (Amount Received) and K(Open Credit) columns
                $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$rows_cnt).':E'.(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$rows_cnt), 'Total' );
                $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$rows_cnt),'=SUM(E6:E'.(5+$rows_cnt).')');
                $objPHPExcel->getActiveSheet()->getStyle('E6:E'.(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


                //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                for ($i =1; $i < $tot_col-1; $i++) {
                    $col_name = getExcelColNameFromNumber($i);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col_name)->setAutoSize(true);
                }

            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No Data Found!' );
                $objPHPExcel->getActiveSheet()->mergeCells('A6:'.$excel_last_col.'6');
            }

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Sale Invoice Summary');

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="InvoiceList_(Item_wise)_'.$property_name.$_GET['from'] .' - '.$_GET['to'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

        }
        else
            {
            $data['receipts'] = $this->bms_fin_bills_model->getBillsListExcel ($this->input->get('property_id'), $unit_id,$from,$to, $bill_no);
            $data['sales_items'] = $this->bms_fin_masters_model->getSalesItemsForBill ($_GET['property_id']);

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

            $objPHPExcel->getActiveSheet()->setCellValue ('A2', 'Invoice (Filter 1-2-3): ' . $invoice_detail );

            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Dated: '.date('d-m-Y h:i a'));
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');

            $sheet_head = array('Invoice No',
                'Date',
                'Unit No',
                'Name',
                'Total Amount',
                'Issued By'
            );
            $sales_item = array ();

            foreach ($data['sales_items'] as $key=>$val) {
                array_push($sheet_head,$val['coa_name']);
                $sales_item[$val['coa_id']] =  6+$key;
            }

            array_push ($sheet_head,'Total');
            $sales_item['sales_tot'] = end($sales_item) + 1;
            /*array_push($sheet_head,'Notes');
            array_push($sheet_head,'Debit A/c');
            array_push($sheet_head,'Issued By');*/
            $tot_col = count($sheet_head);
            $excel_last_col = getExcelColNameFromNumber($tot_col -1);
            $objPHPExcel->getActiveSheet()->getStyle('A5:'.$excel_last_col.'5')->getFont()->setBold(true)->setSize(12);

            // Filling headers
            $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A5');
            if ( !empty($data['receipts']) ) {
                $row = 6;
                foreach ( $data['receipts'] as $key_receipt => $val_receipt ) {
                    $data['receipt_items'] = $this->bms_fin_bills_model->getBillItemDetail ( $val_receipt['bill_id'] );
                    $total = 0;

                    foreach ( $data['receipt_items'] as $key_items => $val_items ) {
                        // Filling different accounts
                        if ( isset( $sales_item[$val_items['item_cat_id']] ) && $sales_item[$val_items['item_cat_id']] != '' ) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $sales_item[$val_items['item_cat_id']], $row, $val_items['item_amount'] );
                            $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($sales_item[$val_items['item_cat_id']]) . $row . ':' . getExcelColNameFromNumber($sales_item[$val_items['item_cat_id']]).($row))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $total += $val_items['item_amount'];
                        }
                    }

                    // Filling different account total on right side
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $tot_col -1, $row, $total);
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col -1) . $row . ':' . getExcelColNameFromNumber($tot_col -1).($row))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber($tot_col -1))->setAutoSize(true);

                    $row++;
                    unset($val_receipt['bill_id']);
                    $data['receipts'][$key_receipt] = $val_receipt;
                }

                $objPHPExcel->getActiveSheet()->fromArray($data['receipts'], NULL, 'A6');

                $rows_cnt = count($data['receipts']);

                // Filling Grand Total on bottom right only
                foreach ($data['sales_items'] as $key=>$val) {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber(6+$key).(6+$rows_cnt),'=SUM(' . getExcelColNameFromNumber(6+$key) . '6:' . getExcelColNameFromNumber(6+$key) .(5+$rows_cnt).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber(6+$key) . '6:' . getExcelColNameFromNumber(6+$key).(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber(6+$key).(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber(6+$key))->setAutoSize(true);
                }

                // Filling Grand Total below all columns
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($tot_col -1).(6+$rows_cnt),'=SUM(' . getExcelColNameFromNumber($tot_col -1) . '6:' . getExcelColNameFromNumber($tot_col -1) .(5+$rows_cnt).')');
                $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col -1).(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($tot_col -1) . '5:' . getExcelColNameFromNumber($tot_col -1).(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getColumnDimension(getExcelColNameFromNumber($tot_col -1))->setAutoSize(true);

                // Filling Grand total of D (Amount Received) and K(Open Credit) columns
                $objPHPExcel->getActiveSheet()->getStyle('D'.(6+$rows_cnt).':E'.(6+$rows_cnt))->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$rows_cnt), 'Total' );
                $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$rows_cnt),'=SUM(E6:E'.(5+$rows_cnt).')');
                $objPHPExcel->getActiveSheet()->getStyle('E6:E'.(6+$rows_cnt))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);



                //$objPHPExcel->getActiveSheet()->
                // ->setCellValue('F18', '=SUM(B2:C12)');

                //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                for ($i =1; $i < $tot_col-1; $i++) {
                    $col_name = getExcelColNameFromNumber($i);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col_name)->setAutoSize(true);
                }

            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No Data Found!' );
                $objPHPExcel->getActiveSheet()->mergeCells('A6:'.$excel_last_col.'6');
            }

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Sales Invoice Summary');

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="InvoiceList_(Bill_wise)_'.$property_name.$_GET['from'] .' - '.$_GET['to'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }

	public function add_manual_bill($bill_id = '') {
		//echo "fdfd";exit;
		// $this->load->model('vendors_model');
        //echo "<pre>";print_r($_SESSION);echo "</pre>";
		$data['browser_title'] = 'Property Butler | Sales Invoice';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice <i class="fa fa-angle-double-right"></i> '.($bill_id != '' ? 'Update' : 'New').' Sales Invoice';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();

        if (!empty($bill_id)) {
            $data['bill'] = $this->bms_fin_bills_model->getBill($bill_id);
            if(!empty($data['bill']['bill_id'])) {
                $data['blocks'] = $this->bms_masters_model->getBlocks ($data['bill']['property_id']); 
                $data['units'] = $this->bms_masters_model->getUnit ($data['bill']['property_id'],$data['bill']['block_id']);   
                $data['bill_items'] = $this->bms_fin_bills_model->getBillItems ($data['bill']['bill_id']);
                /*if(!empty($data['bill_items'])) {
                    foreach ($data['bill_items'] as $key=>$val) {
                        if(!empty($val['item_sub_cat_id'])){
                            $data['bill_items'][$key]['sub_cat_dd'] = $this->bms_fin_masters_model->getSubCategory ($val['item_cat_id']);    
                        } else {
                            $data['bill_items'][$key]['sub_cat_dd'] = array();
                        }                        
                    }                       
                } */               
            }
        }
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
               
        $data['sales_items'] = $this->bms_fin_masters_model->getSalesItemsForBill ($data ['property_id']);

        //echo "<pre>";print_r($data['bill_items']);echo "</pre>";
        //$property_id = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : '';        
        $this->load->view('finance/manual_bill/manual_bill_add_view',$data);
	}

    function manual_bill_submit () {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;
        
        $bill = $this->input->post('bill');
        $items = $this->input->post('items');
        $type = 'add';
        if(!empty($bill['bill_date'])) {
            $bill['bill_date'] = date('Y-m-d',strtotime($bill['bill_date']));
        }
        if(!empty($bill['bill_due_date'])) {
            $bill['bill_due_date'] = date('Y-m-d',strtotime($bill['bill_due_date']));
        }
        
        if(!empty($bill['bill_id'])) {
            $type = 'edit';
            $bill_id = $bill['bill_id'];
            $this->bms_fin_bills_model->updateBill ($bill,$bill['bill_id']);
        } else {
            if(isset($bill['bill_id'])) unset($bill['bill_id']);
            $bill_date = !empty($bill['bill_date']) ? $bill['bill_date'] : date('d-m-Y');
            $prop_abbrev = $this->input->post('prop_abbr');
            $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/SI/'.date('y/m',strtotime($bill_date)).'/';
            $last_bill_no = $this->bms_fin_bills_model->getLastBillNo ($bill_no_format);
            if(!empty ($last_bill_no)) {
                $last_no = explode('/',$last_bill_no['bill_no']);
                $bill['bill_no'] = $bill_no_format . (end($last_no) +1);
            } else {
                $bill['bill_no'] = $bill_no_format . 1001;
            }
            $bill['bill_time'] = date('H:i:s');
            $bill['email_status'] = 1;
            $bill_id = $this->bms_fin_bills_model->insertBill ($bill);
        }

        if(!empty($items['item_cat_id'])) {
            $item['bill_id'] = $bill_id;
            foreach ($items['item_cat_id'] as $key=>$val) {
                if(!empty($val)) {
                    $item['item_cat_id'] = $val;
                    //$item['item_sub_cat_id'] = $items['item_sub_cat_id'][$key] != 'None' ? $items['item_sub_cat_id'][$key]: ''; 
                    $item['item_descrip'] = $items['item_descrip'][$key];
                    $item['item_period'] = $items['item_period'][$key];
                    $item['item_amount'] = $items['item_amount'][$key];
                    $item['bal_amount'] = $items['item_amount'][$key];
                    $item['paid_amount'] = 0; 
                    
                    if(!empty($items['bill_item_id'][$key])) {
                        $this->bms_fin_bills_model->updateBillItem ($item,$items['bill_item_id'][$key]);
                    } else {
                        $this->bms_fin_bills_model->insertBillItem ($item);    
                    }               
                }                                
            }
        }
        $_SESSION['flash_msg'] = 'Bill '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_bills/manual_bill_details/'.$bill_id); //manual_bill_list/0/25?property_id='.$bill['property_id']
    }

    public function manual_bill_details ($bill_id,$act_type = 'view') {
		$data['browser_title'] = 'Property Butler | Sales Invoice ';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice  Details';
        
        if(!empty($bill_id)) {
            $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($bill_id);
            if(!empty($data['manual_bill']['bill_id'])) {
                $data['manual_bill_items'] = $this->bms_fin_bills_model->getBillItemsDetail($data['manual_bill']['bill_id']);
            }
        }
        //echo "<pre>";print_r($data['manual_bill_items']);echo "</pre>";
        $data ['act_type'] = $act_type;
        $this->load->helper('number_to_word');
        if($act_type == 'download') {
            
            $this->load->library('M_pdf');
            
            $html = $this->load->view('finance/manual_bill/manual_bill_details_print_view',$data,true);
            
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($data['manual_bill']['bill_no'].'.pdf', 'D');
        } else if($act_type == 'print') {
            $this->load->view('finance/manual_bill/manual_bill_details_print_view',$data);
        } else {
            $this->load->view('finance/manual_bill/manual_bill_details_view',$data);
        }
	}

    function unset_bill_item () {
        $bill_item_id = $this->input->post('bill_item_id');
        echo $this->bms_fin_bills_model->deleteBillItem ($bill_item_id); 
    }

    function unset_bill () {
        $bill_id = $this->input->post('bill_id');
        $this->bms_fin_bills_model->deleteBillItemByBillId ($bill_id); 
        echo $this->bms_fin_bills_model->deleteBill ($bill_id);
    }

    function get_period () {
        $period_format = $this->input->post('period_format');
        echo get_period_dd($period_format);
    }

    function soa () {
        $this->soa_emails->send_soa();
    }

    function lpi_calc_list () {
        $data['browser_title'] = 'Property Butler | LPI Calculations';
        $data['page_header'] = '<i class="fa fa-file"></i> LPI Calculations';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['pro_units'] = $this->bms_masters_model->getUnit ($data['property_id']);
        $data['unit_id'] = isset( $_GET['unit_id'] )?$_GET['unit_id']:'';
        $data['property_setting_chk'] = $this->bms_fin_bills_model->property_setting_lpi_chk ( $data ['property_id'] );
        $data['unapplied_amount'] = $this->bms_fin_bills_model->chk_unapplied_amount_for_property ( $data ['property_id'] );
        if ( isset($_GET['property_id']) && !empty($_GET['lpi_calc_date']) ) {
            // Save invoice
            $property_id = $this->input->get('property_id');
            $lpi_calc_date = $this->input->get('lpi_calc_date');
            $lpi_calc_date = date('Y-m-d',strtotime($lpi_calc_date));

            $lpi_coa_id = $this->bms_fin_bills_model->getLpiCoaId( $property_id );
            if ( !empty( $lpi_coa_id->coa_id) ) {
                $data['properties_detail'] = $this->bms_fin_bills_model->get_property_lpi_detail ( $property_id );

                if ( !empty( $data['properties_detail'] ) ) {
                    $units = array ();
                    if ( $data['properties_detail']->late_payment == 1 && $data['properties_detail']->late_pay_grace_type == 1 ) {
                        // for days
                        $late_pay_percent = $data['properties_detail']->late_pay_percent;
                        $late_pay_grace_value = $data['properties_detail']->late_pay_grace_value;
                        $bill_due_days = $data['properties_detail']->bill_due_days;

                        $total_days_to_subtract = ($late_pay_grace_value);

                        if ( !empty($_GET['unit_id']) ) {
                            $data['property_units'] = $this->bms_masters_model->getUnitById ( $_GET['unit_id'] );
                        } else {
                            $data['property_units'] = $this->bms_masters_model->getUnit ($property_id);
                        }

                        if ( !empty ( $data['property_units'] ) ) {

                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                                if ( !empty($val_inv['lpi_charged_date']) ) {
                                    $date_from = $val_inv['lpi_charged_date'];
                                } else {
                                    $date_from = $data['properties_detail']->late_pay_effect_from;
                                }

                                $date_to_start = date('Y-m-d', strtotime($lpi_calc_date . ' - ' . round($total_days_to_subtract) . ' days'));

                                if ( $date_to_start >= $date_from ) {

                                    $bills = $this->bms_fin_bills_model->get_unit_outstanding_bills ($val_unit['unit_id'], $_GET['property_id'], $date_to_start, $lpi_coa_id->coa_id, $date_from);

                                    if (!empty($bills)) {
                                        //$data['invoices'] = $bills;
                                        $invoice = array();
                                        $invoice_total = 0;
                                        $date_from = '';

                                        foreach ($bills as $key_inv => $val_inv) {
                                            // echo 'Item ID = ' . $val_inv['bill_item_id'] . ', Bal Amount = ' . $val_inv['bal_amount'] . ', Unit ID = ' . $val_inv['unit_id'] . ', Total = ' .
                                            if (!empty($val_inv['lpi_charged_date'])) {
                                                $charge_start_date = new DateTime ( $val_inv['lpi_charged_date'] );
                                                $date_from = date('d-m-Y', strtotime($val_inv['lpi_charged_date']) );
                                            } else {
                                                if ( !empty ($data['properties_detail']->late_pay_effect_from) && $val_inv['bill_due_date'] < $data['properties_detail']->late_pay_effect_from ) {
                                                    $charge_start_date = date('Y-m-d', strtotime($data['properties_detail']->late_pay_effect_from . ' + ' . round($total_days_to_subtract) . ' days'));
                                                    $charge_start_date = new DateTime ($charge_start_date);
                                                    $date_from = date('d-m-Y', strtotime($data['properties_detail']->late_pay_effect_from) );
                                                } else {
                                                    $charge_start_date = date('Y-m-d', strtotime($val_inv['bill_due_date'] . ' + ' . round($total_days_to_subtract) . ' days'));
                                                    $charge_start_date = new DateTime ($charge_start_date);
                                                    $date_from = date('d-m-Y', strtotime($val_inv['bill_due_date'] . ' + ' . round($data['properties_detail']->late_pay_grace_value) . ' days')) ;
                                                }
                                            }

                                            $date_to = date('d-m-Y', strtotime( $lpi_calc_date ));

                                            $obj_lpi_calc_date = new DateTime($lpi_calc_date);

                                            $number_of_days = $charge_start_date->diff($obj_lpi_calc_date)->format("%r%a");

                                            if ($number_of_days > 0) {
                                                $calc_lpi_amount = $number_of_days * ((($late_pay_percent * $val_inv['bal_amount']) / 100) / 365);
                                                $calc_lpi_amount = round($calc_lpi_amount, 2);
                                                if ($calc_lpi_amount > 0.01) {
                                                    $invoice_row[$val_unit['unit_id']]['bill_no'] = $val_inv['bill_no'];
                                                    $invoice_row[$val_unit['unit_id']]['desc'] = 'LPI for ' . $val_inv['coa_name'] . ' - ' . $val_inv['bal_amount'] . ' - ' . $number_of_days . ' Days' . ' - ' . round($late_pay_percent) . '%';
                                                    $invoice_row[$val_unit['unit_id']]['amount'] = $calc_lpi_amount;
                                                    $invoice_row[$val_unit['unit_id']]['bill_item_id'] = $val_inv['bill_item_id'];
                                                    $invoice_row[$val_unit['unit_id']]['date_from'] = $date_from;
                                                    $invoice_row[$val_unit['unit_id']]['date_to'] = $date_to;
                                                    $invoice[] = $invoice_row;
                                                    $invoice_total = $invoice_total + $calc_lpi_amount;
                                                }
                                            }
                                            // $lpi_data = $this->calculate_lpi_percentage ( $val_inv['bill_date'], $val_inv['lpi_charged_date'], $lpi_calc_date, $total_days_to_subtract, $late_pay_percent, $val_inv['bal_amount'] );
                                        }

                                        if (!empty($invoice)) {
                                            // insert lpi invoices
                                            $units[$val_unit['unit_id']]['invoices'] = $invoice;
                                            $units[$val_unit['unit_id']]['unit_id'] = $val_unit['unit_id'];
                                            $units[$val_unit['unit_id']]['unit_no'] = $val_unit['unit_no'];
                                            $units[$val_unit['unit_id']]['name'] = $val_unit['owner_name'];

                                            if (isset($_GET['save_invoices'])) {
                                                $prop_abbrev = $data['properties_detail']->property_abbrev;
                                                $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/LPI/' . date('y') . '/' . date('m') . '/';
                                                $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                                if (!empty ($last_bill_no)) {
                                                    $last_no = explode('/', $last_bill_no['bill_no']);
                                                    $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                                } else {
                                                    $bill_no_format = $bill_no_format . 1001;
                                                }

                                                $data_bill = array(
                                                    'property_id' => $_GET['property_id'],
                                                    'block_id' => $val_unit['block_id'],
                                                    'unit_id' => $val_unit['unit_id'],
                                                    'bill_no' => $bill_no_format,
                                                    'bill_date' => $lpi_calc_date,
                                                    'bill_time' => date('H:i:s'),
                                                    'bill_due_date' => date('Y-m-d', strtotime($lpi_calc_date . ' + ' . round($bill_due_days) . ' days')),
                                                    'remarks' => 'Late Payment interest on ' . $this->input->get('lpi_calc_date'),
                                                    'total_amount' => $invoice_total,
                                                    'bill_paid_status' => 0,
                                                    'bill_type' => 1,
                                                    'created_by' => isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '',
                                                    'created_date' => date('Y-m-d')
                                                );

                                                $bill_id = $this->bms_fin_bills_model->insertBill($data_bill);

                                                foreach ($units[$val_unit['unit_id']]['invoices'] as $key_item => $val_item) {
                                                    $data_item = array(
                                                        'bill_id' => $bill_id,
                                                        'item_cat_id' => $lpi_coa_id->coa_id,
                                                        'item_descrip' => $val_item[$val_unit['unit_id']]['desc'],
                                                        'item_amount' => $val_item[$val_unit['unit_id']]['amount'],
                                                        'paid_amount' => 0,
                                                        'bal_amount' => $val_item[$val_unit['unit_id']]['amount'],
                                                        'paid_status' => 0,
                                                        'lpi_charged_date' => $lpi_calc_date,
                                                    );
                                                    $this->bms_fin_bills_model->insertBillItem($data_item);
                                                    $val_item[$val_unit['unit_id']]['bill_item_id'];

                                                    $data_item_update = array(
                                                        'lpi_charged_date' => $lpi_calc_date,
                                                    );
                                                    $this->bms_fin_bills_model->updateBillItem($data_item_update, $val_item[$val_unit['unit_id']]['bill_item_id']);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ( isset($_GET['save_invoices']) ) { // flash message and redirect
                            $_SESSION['flash_msg'] = 'LPI Invoices Generated successfully!';
                            $_SESSION['flash_msg_class'] = 'alert-success';
                            redirect ('index.php/bms_fin_bills/lpi_calc_list?property_id='.$property_id.'&lpi_calc_date='.date('d-m-Y',strtotime($lpi_calc_date)).'&unit_id='.$data['unit_id']);
                        } else {
                            $data['units'] = $units;
                        }
                    }
                    else if ($data['properties_detail']->late_payment == 1 && $data['properties_detail']->late_pay_grace_type == 2) {
                        // For amounts
                        $late_pay_percent = $data['properties_detail']->late_pay_percent;
                        $lpi_amount = $data['properties_detail']->late_pay_grace_value;

                        if ( !empty($_GET['unit_id']) ) {
                            $data['property_units'] = $this->bms_masters_model->getUnitById ( $_GET['unit_id'] );
                        } else {
                            $data['property_units'] = $this->bms_masters_model->getUnit ($property_id);
                        }
                        if ( !empty ( $data['property_units'] ) ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                                if ( !empty($val_inv['lpi_charged_date']) ) {
                                    $date_from = $val_inv['lpi_charged_date'];
                                } else {
                                    $date_from = $data['properties_detail']->late_pay_effect_from;
                                }

                                $bills = $this->bms_fin_bills_model->get_unit_outstanding_bills_amount ( $val_unit['unit_id'], $lpi_calc_date, $lpi_coa_id->coa_id, $lpi_amount, $date_from );

                                if (!empty($bills)) {
                                    //$data['invoices'] = $bills;
                                    $invoice = array();
                                    $invoice_total = 0;
                                    $date_from = '';

                                    foreach ($bills as $key_inv => $val_inv) {
                                        // echo 'Item ID = ' . $val_inv['bill_item_id'] . ', Bal Amount = ' . $val_inv['bal_amount'] . ', Unit ID = ' . $val_inv['unit_id'] . ', Total = ' .
                                        if ( !empty($val_inv['lpi_charged_date']) ) {
                                            $charge_start_date = new DateTime ( $val_inv['lpi_charged_date'] );
                                            $date_from = date('d-m-Y', strtotime($val_inv['lpi_charged_date']) );
                                        } else {
                                            $charge_start_date = $val_inv['bill_due_date'] ;
                                            $charge_start_date = new DateTime ( $charge_start_date );
                                            $date_from = date('d-m-Y', strtotime( $val_inv['bill_due_date']) ) ;
                                        }

                                        $date_to = date('d-m-Y', strtotime( $lpi_calc_date ));

                                        $obj_lpi_calc_date = new DateTime($lpi_calc_date);

                                        $number_of_days = $charge_start_date->diff($obj_lpi_calc_date)->format("%r%a");

                                        if ($number_of_days > 0) {
                                            $calc_lpi_amount = $number_of_days * ((($late_pay_percent * $val_inv['bal_amount']) / 100) / 365);
                                            $calc_lpi_amount = round($calc_lpi_amount, 2);
                                            if ($calc_lpi_amount > 0.01) {
                                                $invoice_row[$val_unit['unit_id']]['bill_no'] = $val_inv['bill_no'];
                                                $invoice_row[$val_unit['unit_id']]['desc'] = 'LPI for ' . $val_inv['coa_name'] . ' - ' . $val_inv['bal_amount'] . ' - ' . $number_of_days . ' Days' . ' - ' . round($late_pay_percent) . '%';
                                                $invoice_row[$val_unit['unit_id']]['amount'] = $calc_lpi_amount;
                                                $invoice_row[$val_unit['unit_id']]['bill_item_id'] = $val_inv['bill_item_id'];
                                                $invoice_row[$val_unit['unit_id']]['date_from'] = $date_from;
                                                $invoice_row[$val_unit['unit_id']]['date_to'] = $date_to;
                                                $invoice[] = $invoice_row;
                                                $invoice_total = $invoice_total + $calc_lpi_amount;
                                            }
                                        }
                                        // $lpi_data = $this->calculate_lpi_percentage ( $val_inv['bill_date'], $val_inv['lpi_charged_date'], $lpi_calc_date, $total_days_to_subtract, $late_pay_percent, $val_inv['bal_amount'] );
                                    }

                                    if (!empty($invoice)) {
                                        // insert lpi invoices
                                        $units[$val_unit['unit_id']]['invoices'] = $invoice;
                                        $units[$val_unit['unit_id']]['unit_id'] = $val_unit['unit_id'];
                                        $units[$val_unit['unit_id']]['unit_no'] = $val_unit['unit_no'];
                                        $units[$val_unit['unit_id']]['name'] = $val_unit['owner_name'];

                                        if (isset($_GET['save_invoices'])) {
                                            $prop_abbrev = $data['properties_detail']->property_abbrev;
                                            $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/LPI/' . date('y') . '/' . date('m') . '/';
                                            $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                            if (!empty ($last_bill_no)) {
                                                $last_no = explode('/', $last_bill_no['bill_no']);
                                                $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                            } else {
                                                $bill_no_format = $bill_no_format . 1001;
                                            }

                                            $data_bill = array(
                                                'property_id' => $_GET['property_id'],
                                                'block_id' => $val_unit['block_id'],
                                                'unit_id' => $val_unit['unit_id'],
                                                'bill_no' => $bill_no_format,
                                                'bill_date' => $lpi_calc_date,
                                                'bill_time' => date('H:i:s'),
                                                'bill_due_date' => date('Y-m-d', strtotime($lpi_calc_date . ' + ' . round($data['properties_detail']->bill_due_days) . ' days')),
                                                'remarks' => 'Late Payment interest on ' . $this->input->get('lpi_calc_date'),
                                                'total_amount' => $invoice_total,
                                                'bill_paid_status' => 0,
                                                'bill_type' => 1,
                                                'created_by' => isset($_SESSION['bms']['staff_id']) ? $_SESSION['bms']['staff_id'] : '',
                                                'created_date' => date('Y-m-d')
                                            );

                                            $bill_id = $this->bms_fin_bills_model->insertBill($data_bill);

                                            foreach ($units[$val_unit['unit_id']]['invoices'] as $key_item => $val_item) {
                                                $data_item = array(
                                                    'bill_id' => $bill_id,
                                                    'item_cat_id' => $lpi_coa_id->coa_id,
                                                    'item_descrip' => $val_item[$val_unit['unit_id']]['desc'],
                                                    'item_amount' => $val_item[$val_unit['unit_id']]['amount'],
                                                    'paid_amount' => 0,
                                                    'bal_amount' => $val_item[$val_unit['unit_id']]['amount'],
                                                    'paid_status' => 0,
                                                    'lpi_charged_date' => $lpi_calc_date,
                                                );
                                                $this->bms_fin_bills_model->insertBillItem($data_item);
                                                $val_item[$val_unit['unit_id']]['bill_item_id'];

                                                $data_item_update = array(
                                                    'lpi_charged_date' => $lpi_calc_date,
                                                );
                                                $this->bms_fin_bills_model->updateBillItem($data_item_update, $val_item[$val_unit['unit_id']]['bill_item_id']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ( isset($_GET['save_invoices']) ) { // flash message and redirect
                            $_SESSION['flash_msg'] = 'LPI Invoices Generated successfully!';
                            $_SESSION['flash_msg_class'] = 'alert-success';
                            redirect ('index.php/bms_fin_bills/lpi_calc_list?property_id='.$property_id.'&lpi_calc_date='.date('d-m-Y',strtotime($lpi_calc_date)).'&unit_id='.$data['unit_id']);
                        } else {
                            $data['units'] = $units;
                        }
                    } else {
                        $_SESSION['flash_msg'] = 'Unable to find "LPI settings" from property settings page';
                        $_SESSION['flash_msg_class'] = 'alert-danger';
                    }
                } else {
                    $_SESSION['flash_msg'] = 'Unable to find "LPI settings" from property settings page';
                    $_SESSION['flash_msg_class'] = 'alert-danger';
                }
            } else {
                $_SESSION['flash_msg'] = 'Unable to find "LPI" from chart of account';
                $_SESSION['flash_msg_class'] = 'alert-danger';
            }
        }
        $this->load->view('finance/manual_bill/lpi_calc_list_view',$data);
    }

    function calculate_lpi_percentage ( $bill_date, $lpi_charged_date, $lpi_calc_date, $total_days_to_subtract, $late_pay_percent, $bal_amount ) {
        $obj_bill_date = new DateTime( $bill_date );
        $obj_lpi_calc_date = new DateTime( $lpi_calc_date );
        $diff = $obj_lpi_calc_date->diff($obj_bill_date)->format("%a");
        $days_number = $diff - $total_days_to_subtract;
        if ( $lpi_charged_date != '' ) {
            $obj_bill_date = new DateTime( $bill_date );
            $obj_charged_date = new DateTime( $lpi_charged_date );
            $diff_charged = $obj_charged_date->diff($obj_bill_date)->format("%a");
            $diff_charged = $diff_charged - $total_days_to_subtract;
            $days_number = $days_number - $diff_charged;
        }

        if ( $days_number < 0 )
            $days_number = 0;

        $calc_lpi_amount = $days_number * ((($late_pay_percent * $bal_amount) /100 ) / 365);
        $calc_lpi_amount = round($calc_lpi_amount, 2);
        $result = array (
            'calc_lpi_amount' => $calc_lpi_amount,
            'total_days' => $days_number,
        );
        return $result;
    }

    function meter_reading_list ( $offset = 0, $rows = 5 ) {

        $data['browser_title'] = 'Property Butler | Meter Reading';
        $data['page_header'] = '<i class="fa fa-file"></i> Meter Reading';

        $data['unit_id'] = ($this->input->get('unit_id'))?$this->input->get('unit_id'):'' ;
        $data['reading_mon_year'] = ($this->input->get('reading_mon_year'))?$this->input->get('reading_mon_year'):'' ;

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;
        $data['rows'] = $rows;

        if ( !empty($data ['property_id']) ) {
            $data['pro_units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
            $data['units'] = $this->bms_fin_bills_model->getUnits ( $data['reading_mon_year'], $data ['property_id'],$data['unit_id'],$offset,$rows );
            $data['property_detail'] = $this->bms_fin_bills_model->getPropertyDetails ( $data ['property_id'] );
        }

        $data['check_units_not_keyed_in'] = $this->bms_fin_bills_model->check_units_not_keyed_in ( $data ['property_id'], $data['reading_mon_year'] );

        $data['check_email_already_sent'] = $this->bms_fin_bills_model->check_email_already_sent ( $data ['property_id'], $data['reading_mon_year'] );

        $data['check_invoice_already_generated'] = $this->bms_fin_bills_model->check_invoice_already_generated ( $data ['property_id'], $data['reading_mon_year'] );

        $data['reading_mon_year'] = $this->get_reading_month_of_selected_dropdown ( $data ['property_id'], $data['reading_mon_year'] );

        $data['total_meter_readings'] = $this->bms_fin_bills_model->chk_total_meter_readings ( $data ['property_id'] );

        /*echo $data['chk_total_meter_readings'];
        die;*/

        $data['property_setting_chk'] = $this->bms_fin_bills_model->property_setting_chk ( $data ['property_id'], $data['reading_mon_year'] );

        $this->load->view('finance/manual_bill/meter_reading_list_view',$data);
    }

    function set_meter_reading () {

        //echo "<pre>";print_r($_POST); echo "</pre>";exit;
        $property_id = $this->input->post('property_id');
        $receipt = $this->input->post('receipt');
        $unit_id = $receipt['unit_id'];

        $reading_mon_year = $this->input->post('reading_mon_year');
        $bill_generate_date = $this->input->post('bill_generate_date');
        $meter_reading = $_POST['meter_reading'];

        foreach ($meter_reading['reading'] as $key => $val ) {
            $exclude_to_inv = 0;
            $exclude_to_inv = in_array ($meter_reading['meter_reading_id'][$key],$meter_reading['exclude_to_inv'])?1:0;
            $data_meter = array (
                'property_id' => $property_id,
                'unit_id' => $meter_reading['unit_id'][$key],
                'reading_mon_year' => $reading_mon_year,
                'reading' => $meter_reading['reading'][$key],
                'previous_reading' => $meter_reading['previous_reading'][$key],
                'amount' =>  $meter_reading['amount'][$key],
                'bill_generated' => 0,
                'exclude_to_inv' => $exclude_to_inv
            );

            if ( $meter_reading['meter_reading_id'][$key] != '' ) {
                $meter_reading_data = $this->bms_fin_bills_model->get_meter_reading_details ( $meter_reading['meter_reading_id'][$key] );
                if ( $meter_reading_data->reading == $meter_reading['reading'][$key] && $meter_reading_data->amount == $meter_reading['amount'][$key] && $meter_reading_data->previous_reading = $meter_reading['previous_reading'][$key] ) {
                    $this->bms_fin_bills_model->update_meter_reading( $data_meter, $meter_reading['meter_reading_id'][$key] );
                } else {
                    $data_meter['updated_by'] = $_SESSION['bms']['staff_id'];
                    $data_meter['updated_date'] = date("Y-m-d");
                    $this->bms_fin_bills_model->update_meter_reading( $data_meter, $meter_reading['meter_reading_id'][$key] );
                }
            } else {
                $this->bms_fin_bills_model->insert_meter_reading( $data_meter );
            }

        }

        $_SESSION['flash_msg'] = 'Meter reading added/updated Successfully!';
        $sub_url = '';
        if(!empty($_POST['act_type'])) {
            switch($_POST['act_type']) {

                case 'save_pre': $sub_url = $_POST['offset']-$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save_nxt': $sub_url = $_POST['offset']+$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save':
                default:
                    $sub_url = $_POST['offset'].'/'.$_POST['rows']; break;
            }
        }
        redirect ('index.php/bms_fin_bills/meter_reading_list/'.$sub_url.'?property_id='.$_POST['property_id'].'&reading_mon_year='.$_POST['reading_mon_year'].'&unit_id='.$unit_id );
    }

    function meter_reading_generate_bill ( $offset = 0, $rows = 25 ) {
        $this->load->helper('number_to_word');

        $data['unit_id'] = ($this->input->get('unit_id'))?$this->input->get('unit_id'):'' ;
        $data['reading_mon_year'] = $_GET['reading_mon_year'] ? $_GET['reading_mon_year'] :'';
        $bill_date = !empty($_GET['bill_generate_date']) ? date('Y-m-d',strtotime($_GET['bill_generate_date'])) : '';

        $data['reading_mon_year'] = ($this->input->get('reading_mon_year'))?$this->input->get('reading_mon_year'):'' ;

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;

        $data['rows'] = $rows;

        if ( $data['reading_mon_year'] != '' && $data ['property_id'] != '' && !empty($bill_date) ) {
            $property_detail = $this->bms_property_model->getPropertyDetails ( $data ['property_id'] );

            $check_invoice_already_generated = $this->bms_fin_bills_model->check_invoice_already_generated ( $data ['property_id'], $data['reading_mon_year'] );

            if ( !empty($check_invoice_already_generated) ) {

                $check_units_not_keyed_in = $this->bms_fin_bills_model->check_units_not_keyed_in ( $data ['property_id'], $data['reading_mon_year'] );

                if ( empty($check_units_not_keyed_in) ) {
                    // Generate bills
                    $data['property_units'] = $this->bms_fin_bills_model->getPropertyUnitsForMeterReadingBillGeneration ( $data ['property_id'], $data['reading_mon_year'] );

                    $coa_service_charge_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($data ['property_id'], 'water');

                    if ( !empty($coa_service_charge_data->coa_id) ) {
                        if (!empty($data['property_units'])) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                $manual_bill_items = array ();
                                $period = $data['reading_mon_year'];

                                $prop_abbrev = $property_detail['property_abbrev'];
                                $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                if (!empty ($last_bill_no)) {
                                    $last_no = explode('/', $last_bill_no['bill_no']);
                                    $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                } else {
                                    $bill_no_format = $bill_no_format . 1001;
                                }

                                $remarks = $coa_service_charge_data->coa_name . "(" . $period . ")";

                                $bill_data = array (
                                    'property_id' => $data ['property_id'],
                                    'block_id' => $val_unit['block_id'],
                                    'unit_id' => $val_unit['unit_id'],
                                    'bill_no' => $bill_no_format,
                                    'bill_date' => $bill_date,
                                    'bill_time' => date('H:i:s'),
                                    'bill_due_date' => date('Y-m-d', strtotime($bill_date . "+" . $property_detail['bill_due_days'] . " day")),
                                    'remarks' => $remarks, // 'Water Meter Reading -' . $period . '- Previous reading = ' . $val_unit['previous_reading'] . ', Current reading = ' . $val_unit['reading'] . ', Consumption = ' . ($val_unit['reading'] - $val_unit['previous_reading']) . ', Minimum charges = ' . $property_detail['water_min_charg'] . ', Range = ' . $property_detail['water_charge_range'] . ', Charge per m3(Rate 1) = ' . $property_detail['water_charge_per_unit_rate_1'] . ', Charge per m3(Rate 2) = ' . $property_detail['water_charge_per_unit_rate_2'],
                                    'total_amount' => $val_unit['amount'],
                                    'bill_paid_status' => 0,
                                    'bill_type' => 1,
                                    'created_by' => 1541,
                                    'created_date' => date('Y-m-d')
                                );
                                $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                $data['manual_bill'] = array (
                                    'jmb_mc_name' => $property_detail['jmb_mc_name'],
                                    'property_name' => $property_detail['property_name'],
                                    'address_1' => $property_detail['address_1'],
                                    'address_2' => $property_detail['address_1'],
                                    'pin_code' => $property_detail['pin_code'],
                                    'state_name' => $val_unit['state_name'],
                                    'country_name' => $val_unit['country_name'],
                                    'unit_no' => $val_unit['unit_no'],
                                    'bill_date' => $bill_date,
                                    'owner_name' => $val_unit['owner_name'],
                                    'bill_no' => $bill_no_format,
                                    'bill_due_date' => date('Y-m-d', strtotime($bill_date . "+" . $property_detail['bill_due_days'] . " day")),
                                    'remarks' => $remarks, //'Water Meter Reading-' . $period,
                                    'first_name' => 'System',
                                    'last_name' => '',
                                    'created_date' => date('Y-m-d')
                                );

                                $bill_item_data = array (
                                    'bill_id' => $insert_id,
                                    'item_cat_id' => $coa_service_charge_data->coa_id,
                                    'item_period' => $period,
                                    'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                    'item_amount' => $val_unit['amount'],
                                    'paid_amount' => 0,
                                    'bal_amount' => $val_unit['amount'],
                                    'paid_status' => 0,
                                    'meter_reading_id' => $val_unit['meter_reading_id']
                                );
                                $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                $items = array (
                                    'cat_name' => $coa_service_charge_data->coa_name,
                                    'item_period' => $period,
                                    'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                    'item_amount' => $val_unit['amount'],
                                );
                                array_push ( $manual_bill_items, $items );

                                $data['manual_bill_items'] = $manual_bill_items;

                                $data_meter_reading = array (
                                    'bill_generated' => 1
                                );
                                $this->bms_fin_bills_model->update_meter_reading ($data_meter_reading, $val_unit['meter_reading_id']);



                                /*$this->load->library('M_pdf');
                                $m_pdf = new M_pdf();
                                $owner_name = !empty( $data['UnitDetail']->owner_name )? $data['UnitDetail']->owner_name: '';
                                $sc_and_sf_PDF = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);
                                $m_pdf->pdf->WriteHTML ( $sc_and_sf_PDF );
                                $pdf_attachment = $m_pdf->pdf->Output('', 'S');
                                $filename = 'meter_reading_invoice.pdf';
                                $this->load->library('email');
                                $this->email->clear(true);
                                $message = "Dear " . $owner_name . ", <br>
                                Please find attached invoices for your prompt payment <br><br>
                                Thank you,<br>" .
                                "Email address for invoice: " . $val_unit['email_addr'] . ", <br>" .
                                $property_detail['jmb_mc_name'] . ", <br>" .
                                $property_detail['property_name'];
                                $result = $this->email
                                ->from( $property_detail['email_addr'], $property_detail['property_name'] )
                                ->reply_to( $property_detail['email_addr'], $property_detail['property_name'] )    // Optional, an account where a human being reads.
                                // ->to( $val_unit['email_addr'] )
                                ->to( 'naguwin@gmail.com','Nagarajan' )
                                ->bcc( 'yameenadnan@hotmail.com', 'Yameen Adnan' )
                                ->subject ( $property_detail['property_name'] . ', ' . $val_unit['unit_no'] . ', Water Meter Invoice for the Month of ' . $period )
                                ->attach ($pdf_attachment, 'attachment', $filename, 'application/pdf')
                                ->message ( $message )
                                ->send ();*/
                            }
                        }
                    } else {
                        $_SESSION['flash_msg_key_in'] = '<div style="width: 100%;" class="alert-danger no-padding">Unable to find "water" from Chart Of Account table</div>';
                    }
                    redirect ('index.php/bms_fin_bills/meter_reading_list/?property_id='.$_GET['property_id'].'&reading_mon_year='.$_GET['reading_mon_year']);
                } else {
                    $_SESSION['flash_msg_key_in'] = '<div style="width: 100%;" class="alert-danger no-padding">Some meter readings not keyed in</div>';
                    $sub_url = $_POST['offset'].'/'.$_POST['rows'];
                    redirect ('index.php/bms_fin_bills/meter_reading_list/'.$sub_url.'?property_id='.$_GET['property_id'].'&reading_mon_year='.$_GET['reading_mon_year'].'&bill_generate_date='.$_GET['bill_generate_date']);
                }
            } else {
                $_SESSION['flash_msg_key_in'] = '<div style="width: 100%;" class="alert-danger no-padding">Invoice already generated</div>';
                $sub_url = $_POST['offset'].'/'.$_POST['rows'];
                redirect ('index.php/bms_fin_bills/meter_reading_list/'.$sub_url.'?property_id='.$_GET['property_id'].'&reading_mon_year='.$_GET['reading_mon_year'].'&bill_generate_date='.$_GET['bill_generate_date']);
            }
        } else {
            $_SESSION['flash_msg_key_in'] = '<div style="width: 100%;" class="alert-danger no-padding">Please select property and reading month and input bill generate date</div>';
            $sub_url = $_POST['offset'].'/'.$_POST['rows'];
            redirect ('index.php/bms_fin_bills/meter_reading_list/'.$sub_url.'?property_id='.$_GET['property_id'].'&reading_mon_year='.$_GET['reading_mon_year'].'&bill_generate_date='.$_GET['bill_generate_date']);
        }
    }

    function meter_reading_generate_pdf ( $offset = 0, $rows = 25 ) {
        $this->load->helper('number_to_word');
        $this->load->library('M_pdf');

        $data['unit_id'] = ($this->input->get('unit_id'))?$this->input->get('unit_id'):'' ;
        $data['reading_mon_year'] = $_GET['reading_mon_year'] ? $_GET['reading_mon_year'] :'';
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['properties'] = $this->bms_masters_model->getMyProperties ();

        if ( $data['reading_mon_year'] != '' && $data ['property_id'] != '' ) {
            $meter_reading_ids_for_invoices = $this->bms_fin_bills_model->get_meter_reading_ids_for_invoices($data['reading_mon_year'], $data ['property_id']);
            $html = array ();
            if ( !empty ( $meter_reading_ids_for_invoices ) ) {
                foreach ( $meter_reading_ids_for_invoices as $key => $val ) {
                    $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                    $data['manual_bill_items'] = $this->bms_fin_bills_model->getBillItemsDetail($val['bill_id']);
                    $html[] = $this->load->view('finance/manual_bill/manual_bill_details_print_view', $data, true);
                }
            }

            if ( !empty ($html) ) {
                foreach ( $html as $key => $val ) {
                    $this->m_pdf->pdf->AddPage();
                    $this->m_pdf->pdf->WriteHTML($val);
                }
                $this->m_pdf->pdf->Output('sales_invoices.pdf', 'D');
            }
        }
    }

    function meter_reading_send_mail () {
        $this->load->helper('number_to_word');
        $this->load->library('M_pdf');

        $data['unit_id'] = ($this->input->get('unit_id'))?$this->input->get('unit_id'):'' ;
        $data['reading_mon_year'] = $_GET['reading_mon_year'] ? $_GET['reading_mon_year'] :'';
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['properties'] = $this->bms_masters_model->getMyProperties ();

        if ( $data['reading_mon_year'] != '' && $data ['property_id'] != '' ) {
            $meter_reading_ids_for_invoices = $this->bms_fin_bills_model->get_meter_reading_ids_for_invoices ($data['reading_mon_year'], $data ['property_id'], 0);

            if ( !empty ( $meter_reading_ids_for_invoices ) ) {
                foreach ( $meter_reading_ids_for_invoices as $key => $val ) {

                    $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                    $data['manual_bill_items'] = $this->bms_fin_bills_model->getBillItemsDetail($val['bill_id']);

                    if ( !empty($val['owner_email_addr']) && (filter_var($val['owner_email_addr'], FILTER_VALIDATE_EMAIL)) && $val['valid_email'] == 1 ) {
                        $this->load->library('M_pdf');
                        $m_pdf = new M_pdf();
                        $sc_and_sf_PDF = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);
                        $m_pdf->pdf->AddPage ();
                        $m_pdf->pdf->WriteHTML ( $sc_and_sf_PDF );
                        $pdf_attachment = $m_pdf->pdf->Output('', 'S');
                        $filename = 'meter_reading_invoice.pdf';
                        $this->load->library('email');
                        $this->email->clear(true);
                        $message = "Dear " . $val['owner_name'] . ",<br><br>
                        Please find attached invoice for your kind reference.<br><br>
                        Thank you,<br><br>" .
                        $val['property_name'] . "<br>" .
                        "Management office <br><br><br>" .
                        "<i><u>This is an automated email, please do not reply to this message.</u></i><br><br>" .
                        "For any query you can contact us at:<br>" .
                        "<b>Email: </b><a mailto: " . $data['manual_bill']['email_addr'] . ">" . $data['manual_bill']['email_addr'] . "</a><br>" .
                        "<b>Phone number:  </b>" . $data['manual_bill']['phone_no'];
                        $result = $this->email
                        ->from ( 'noreply@propertybutler.my' )
                        // ->to( $val['owner_email_addr'] )
                        ->to ( 'naguwin@gmail.com','Nagarajan' )
                        ->bcc ( 'yameenadnan@hotmail.com', 'Yameen Adnan' )
                        ->subject ( $data['manual_bill']['property_name'] . ', ' . $val['unit_no'] . ', ' . $data['manual_bill']['remarks'] )
                        ->attach ( $pdf_attachment, 'attachment', $filename, 'application/pdf' )
                        ->message ( $message )
                        ->send ();
                    } else {
                        $data_unit = array (
                            'valid_email' => 0
                        );
                        $this->bms_masters_model->update_unit_set_invalid_email ($data_unit, $val['unit_id']);
                    }
                    $bill_data = array (
                        'email_status' => 1
                    );
                    $this->bms_fin_bills_model->updateBill($bill_data, $val['bill_id']);
                }
                $_SESSION['flash_msg'] = '<div style="width: 100%; text-align: center;"><b>Email sent!</b></div>';
            } else {
                $_SESSION['flash_msg'] = '<div style="width: 100%; text-align: center;"><b>No record found</b></div>';
            }
        }
        redirect ('index.php/bms_fin_bills/meter_reading_list/?property_id=' . $data ['property_id'] . '&reading_mon_year=' . $data['reading_mon_year']);
    }

    function outstanding_invoices_list () {
        $data['browser_title'] = 'Property Butler | Outstanding Invoices';
        $data['page_header'] = '<i class="fa fa-file"></i> Outstanding Invoices';
        $lpi_calc_date = $this->input->get('lpi_calc_date');

        $lpi_calc_date = date('Y-m-d',strtotime($lpi_calc_date));
        $data['act'] = $this->input->get('act');
        $data['sendmail'] = $this->input->get('sendmail');
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['pro_units'] = $this->bms_masters_model->getUnit ( $data['property_id'] );
        $data['unit_id'] = isset( $_GET['unit_id'] )?$_GET['unit_id']:'';
        $lpi_calc_date = date('Y-m-d',strtotime($lpi_calc_date));
        if ( isset($_GET['property_id']) && !empty($_GET['lpi_calc_date']) ) {

            $property_id = $this->input->get('property_id');
            $unit_id = !empty($_GET['unit_id']) ? $_GET['unit_id'] : '';
            $data['property_units'] = $this->bms_masters_model->getUnit ($property_id,'0',$unit_id); // 0 for block id

            $data['PropertyInfo'] = $this->bms_masters_model->getPropertyInfo ( $property_id );

            if ( !empty ( $data['property_units'] ) ) {
                foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                    $data['unit_os_items'][$val_unit['unit_id']] = $this->bms_fin_bills_model->get_all_outstanding_bills_of_a_unit ( $val_unit['unit_id'], $lpi_calc_date );
                    if ( empty ($data['unit_os_items'][$val_unit['unit_id']] ) ) {
                        unset( $data['property_units'][$key_unit] );
                    }
                }
            }

            if ( $data['act'] == 'pdf' ) {

                $this->load->library('M_pdf');

                $counter = 1;

                foreach ( $data['property_units'] as $key_soa => $val_soa ) {
                    $m_pdf = new M_pdf();
                    if ( isset ( $data['sendmail'] ) && $data['sendmail'] == 'yes' ) {
                        $data['property_units'] = '';
                        $data['property_units'][0] = $val_soa;
                        $filename = "outstanding-invoices.pdf";
                        $soa_PDF = $this->load->view ( 'finance/manual_bill/outstanding_invoices_list_view', $data, true );
                        $m_pdf->pdf->WriteHTML ($soa_PDF);
                        $content = $m_pdf->pdf->Output('', 'S');
                        $counter++;
                        $this->load->library('email');
                        $this->email->clear(true);
                        $message = "Dear " . $val_soa['owner_name'] .  ", <br>
                        Please find attached invoices for your prompt payment.<br><br>
                        Regards,<br>" .
                        "Email address for the invoice is: " . $val_soa['email_addr'] . "<br>" .
                        $data['PropertyInfo']['jmb_mc_name'] . ", <br>" .
                        $data['PropertyInfo']['property_name'];

                        $result = $this->email
                            ->from( $data['PropertyInfo']['email_addr'], $data['PropertyInfo']['property_name'] )
                            ->reply_to( $data['PropertyInfo']['email_addr'], $data['PropertyInfo']['property_name'] )    // Optional, an account where a human being reads.
                            // ->to($val_soa['email_addr'])
                            ->to('yameenadnan@hotmail.com')
                            ->bcc('naguwin@gmail.com','Nagarajan')
                            ->subject ( $val_soa['unit_no'] . ( !empty ( $val_soa['owner_name']) ?  ' - ' . $val_soa['owner_name']:'' ) . ' - Outstanding Invoices' )
                            ->message ( $message )
                            ->attach ( $content, 'attachment', $filename, 'application/pdf' )
                            ->send ();
                        // sleep (5);
                    }
                }

                /*$soa_PDF = $this->load->view ('finance/manual_bill/outstanding_invoices_list_view', $data, true);
                $this->m_pdf->pdf->WriteHTML ($soa_PDF);*/

                if ( isset ( $data['sendmail'] ) && $data['sendmail'] == 'yes' ) {
                    $_SESSION['flash_msg'] = '<div style="width: 100%; text-align: center;"><b>Email sent</b></div>';
                    redirect ('index.php/bms_fin_bills/outstanding_invoices_list?property_id='. $data ['property_id'] . '&unit_id=' . $_GET['unit_id'] . '&lpi_calc_date=' . $_GET['lpi_calc_date'] );
                } else {
                    $this->load->view ('finance/manual_bill/outstanding_invoices_list_view', $data);
                    // $this->m_pdf->pdf->Output("outstanding_invoice.pdf", "D");
                }
            } else {
            }
        }
        $this->load->view('finance/manual_bill/outstanding_invoices_list_view',$data);
    }

    function manual_bill_sendmail () {
        $data['browser_title'] = 'Property Butler | Sales Invoice';
        $data['page_header'] = '<i class="fa fa-search-plus"></i> Sales Invoice Details';

        $this->load->helper('number_to_word');
        $this->load->library('M_pdf');
        $data['property_id'] = isset($_POST['property_id']) && trim($_POST['property_id']) != '' ? trim($_POST['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['PropertyInfo'] = $this->bms_masters_model->getPropertyInfo ( $data['property_id'] );

        $data ['act_type'] = 'download';
        $bill_list = $this->input->post('bill_list');

        $bill_list_arry = array ();
        foreach ( $bill_list as $key => $val ) {
            array_push ($bill_list_arry, $key);
        }

        $unit_array = $this->bms_fin_bills_model->get_units_from_bills ( $bill_list_arry );
        $attachment = array ();
        $last_unit_id = '';
        $counter = 0;
        if(!empty($unit_array)) {
            foreach ( $unit_array as $key => $val ) {
                $m_pdf = new M_pdf();
                if ($last_unit_id == $val['unit_id']) {
                    if (!empty($val['bill_id'])) {
                        $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                        if (!empty($data['manual_bill']['bill_id'])) {
                            $data['manual_bill_items'] = $this->bms_fin_bills_model->getBillItemsDetail($data['manual_bill']['bill_id']);
                            $html = $this->load->view('finance/manual_bill/manual_bill_details_print_view', $data, true);
                            $m_pdf->pdf->WriteHTML($html);
                            $attachment[] = $m_pdf->pdf->Output('', 'S');
                        }
                    }
                } else {
                    if ( !empty($attachment) ) {
                        $this->send_invoice_email ( $unit_array[$counter - 1], $attachment, $data['PropertyInfo'] );
                        $attachment = array();
                    }
                    $last_unit_id = $val['unit_id'];
                    if ( !empty($val['bill_id']) ) {
                        $data['manual_bill'] = $this->bms_fin_bills_model->getBillDetails($val['bill_id']);
                        if (!empty($data['manual_bill']['bill_id'])) {
                            $data['manual_bill_items'] = $this->bms_fin_bills_model->getBillItemsDetail($data['manual_bill']['bill_id']);
                            $html = $this->load->view('finance/manual_bill/manual_bill_details_print_view', $data, true);
                            $m_pdf->pdf->WriteHTML($html);
                            $attachment[] = $m_pdf->pdf->Output('', 'S');
                        }
                    }
                }
                $counter++;
            }
            if ( !empty($attachment) )
                $this->send_invoice_email ( $unit_array[$counter - 1], $attachment, $data['PropertyInfo'] );
        }
        $receipt = $this->input->post('receipt');

        $_SESSION['flash_msg'] = '<div style="width: 100%; text-align: center;"><b>Email sent</b></div>';
        redirect ('index.php/bms_fin_bills/manual_bill_list?property_id='. $data ['property_id'] . '&unit_id=' . $receipt['unit_id'] . '&from=' . $this->input->post('from_date') . '&to=' . $this->input->post('to') . '&bill_no=' . $this->input->post('bill_no') );
    }

    function send_invoice_email ( $UnitInfo, $attachment, $PropertyInfo ) {

        if ( !empty($UnitInfo['email_addr']) && (filter_var($UnitInfo['email_addr'], FILTER_VALIDATE_EMAIL)) && $UnitInfo['valid_email'] == 1 ) {
            $filename = "sales-invoices.pdf";
            $this->load->library('email');
            $this->email->clear(true);
            $message = '';
            $message = "Dear " . (!empty( $UnitInfo['owner_name'] ) ? $UnitInfo['owner_name'] : 'Unit Owner') . ",<br><br>
            Please find attached invoice for your kind reference. <br><br>
            Thank you,<br><br>" .
            $PropertyInfo['property_name'] . ",<br>" .
            "Management office <br><br><br>" .
            "<i><u>This is an automated email, please do not reply to this message.</u></i><br><br>" .
            "For any query you can contact us at:<br>" .
            "<b>Email: </b><a mailto: " . $PropertyInfo['email_addr'] . ">" . $PropertyInfo['email_addr'] . "</a><br>" .
            "<b>Phone number:  </b>" . $PropertyInfo['phone_no'];

            $result = $this->email
                ->from( $PropertyInfo['email_addr'], $PropertyInfo['property_name'] )
                ->reply_to( $PropertyInfo['email_addr'],$PropertyInfo['property_name'] )    // Optional, an account where a human being reads.
                //->to( $UnitInfo['email_addr'] )
                ->to('yameenadnan@hotmail.com')
                ->bcc('naguwin@gmail.com','Nagarajan')
                ->subject( $PropertyInfo['property_name'] . '-' . $UnitInfo['unit_no'] . ( !empty( $UnitInfo['owner_name'] )? ' - ' . $UnitInfo['owner_name']: '' ) . ' - Invoice ' )
                ->message( $message );
            foreach ( $attachment as $kye_email ) {
                $this->email->attach( $kye_email, 'attachment', $filename, 'application/pdf' );
            }
            $this->email->send();
        } else {
            $data_unit = array (
                'valid_email' => 0
            );
            $this->bms_masters_model->update_unit_set_invalid_email ($data_unit, $UnitInfo['unit_id']);
        }
    }

    function get_reading_month_of_selected_dropdown ($property_id , $reading_mon_year) {
        $record_exists = $this->bms_fin_bills_model->chk_property_bill_generated_before ($property_id,0);
        $bill_generated = $this->bms_fin_bills_model->chk_property_bill_generated_before ($property_id,1);

        $str = '';
        if ( $bill_generated->total_records > 0 ) {
            $get_past_bill_dates = $this->bms_fin_bills_model->get_past_bill_dates ( $property_id,1);
            if (!empty ($get_past_bill_dates) ) {
                $str .= '<option value="">Select</option>';
                foreach ($get_past_bill_dates as $key => $val ) {

                    $next_date = $this->bms_fin_bills_model->get_next_meter_reading_date ( $property_id, 1);
                    // echo $next_date->next_date;
                    $next_date = strtotime($next_date->next_date);
                    $next_date = date("M-y", strtotime("+1 month", $next_date));
                    $select='';
                    $select = ( $reading_mon_year != '' && $reading_mon_year == $val['reading_mon_year'] )? 'selected="selected"':'';
                    $str .= '<option ' . $select .' value="' . $val['reading_mon_year'] . '">' . $val['reading_mon_year'] . '</option>';
                }
                $select = '';
                $select = ( $reading_mon_year != '' && $reading_mon_year == $next_date )? 'selected="selected"':'';
                $str .= '<option ' . $select .' value="' . $next_date . '">' . $next_date . '</option>';
            }
            return $str;
        } elseif ( $record_exists->total_records > 0 ) {
            $get_past_bill_dates = $this->bms_fin_bills_model->get_past_bill_dates ($property_id,0);
            if (!empty ($get_past_bill_dates) ) {
                $str .= '<option value="">Select</option>';
                foreach ($get_past_bill_dates as $key => $val ) {
                    $next_date = $this->bms_fin_bills_model->get_next_meter_reading_date ($property_id, 0);
                    $next_date = strtotime($next_date->next_date);
                    // $next_date = date("M-y", strtotime("+1 month", $next_date));
                    $select='';
                    $select = ( $reading_mon_year != '' && $reading_mon_year == $val['reading_mon_year'] )? 'selected="selected"':'';
                    $str .= '<option ' . $select .' value="' . $val['reading_mon_year'] . '">' . $val['reading_mon_year'] . '</option>';
                }
            }
            return $str;

        } else {
            $this->load->helper('common_functions');
            return get_period_dd ('mmm-yy', $reading_mon_year );
        }
    }

    function get_unit () {
        header('Content-type: application/json');
        $property_id = trim($this->input->post('property_id'));
        $reading_mon_year = ($this->input->post('reading_mon_year'))?$this->input->post('reading_mon_year'):'' ;

        $reading_mon_year = array ();
        $unit = array();
        if( $property_id ) {
            $unit = $this->bms_masters_model->getUnit ($property_id);
            $reading_mon_year = $this->get_reading_month_of_selected_dropdown ($property_id , $reading_mon_year);
        }


        $data ['units'] = $unit;
        $data ['reading_mon_year'] = $reading_mon_year;

        echo json_encode( $data );
    }

    function semi_auto_invoice_list ( $offset = 0, $rows = 25 ) {

        $data['browser_title'] = 'Property Butler | Generate Invoice';
        $data['page_header'] = '<i class="fa fa-file"></i>  Generate Invoice';

        $data['unit_id'] = ($this->input->get('unit_id'))?$this->input->get('unit_id'):'' ;
        $data['reading_mon_year'] = ($this->input->get('reading_mon_year'))?$this->input->get('reading_mon_year'):'' ;

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;
        $data['rows'] = $rows;

        if ( !empty($data ['property_id']) ) {
            $data['units'] = $this->bms_fin_bills_model->getUnitsForScAndSf ( $data ['property_id'],$data['unit_id'],$offset,$rows );
            $data['property_detail'] = $this->bms_fin_bills_model->getPropertyDetails ( $data ['property_id'] );
        }

        $data['property_setting_chk'] = $this->bms_fin_bills_model->chk_property_setting_sc_sf ( $data ['property_id']);

        $this->load->view('finance/manual_bill/semi_auto_invoice_list_view',$data);
    }

    function set_semi_auto_invoice_list () {

        // echo "<pre>";print_r($_POST); echo "</pre>";exit;
        $property_id = $this->input->post('property_id');
        $unit_id = $this->input->post('unit_id');
        $unit_id_checked = $this->input->post('unit_id_checked');

        foreach ($unit_id as $key => $val ) {
            if ( in_array($val, $unit_id_checked) ) {
                $data_sc_sf = array (
                    'generate_sc_sf' => 1,
                );
            } else {
                $data_sc_sf = array (
                    'generate_sc_sf' => 0,
                );
            }

            $this->bms_fin_bills_model->update_unit( $data_sc_sf, $val );
        }

        $_SESSION['flash_msg'] = 'Units updated Successfully!';
        $sub_url = '';
        if(!empty($_POST['act_type'])) {
            switch($_POST['act_type']) {
                case 'save_pre': $sub_url = $_POST['offset']-$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save_nxt': $sub_url = $_POST['offset']+$_POST['rows'].'/'.$_POST['rows']; break;
                case 'save':
                default:
                    $sub_url = $_POST['offset'].'/'.$_POST['rows']; break;
            }
        }
        redirect ('index.php/bms_fin_bills/semi_auto_invoice_list/'.$sub_url.'?property_id='.$_POST['property_id'] );
    }

    function semi_auto_invoice_generate_bill () {
        // echo "<pre>";print_r($_GET); echo "</pre>";exit;
        $this->load->library('M_pdf');
        $this->load->helper('number_to_word');
        $property_id = $this->input->get('property_id');
        $billing_cycle = $this->input->get('billing_cycle');
        $invoice_type = $this->input->get('invoice_type');
        $bill_generate_date = $this->input->get('bill_generate_date');
        $bill_generate_date = date("Y-m-d", strtotime($bill_generate_date));

        $val = $this->bms_fin_bills_model->chk_property_setting_sc_sf ( $property_id );
        if ( !empty($val) && !empty($val['calcul_base']) && !empty($val['property_abbrev']) && !empty($val['bill_due_days']) ) {

        }

        switch ($invoice_type) {
            case 'fi':
                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {

                    $coa_fire_insurance_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'fi');

                    if ( !empty ($coa_fire_insurance_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );
                        if ( !empty( $data['property_units'] )  ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {

                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {
                                    $manual_bill_items = array ();
                                    $fire_insurance = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $fire_insurance = ($val['insurance_prem'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $fire_insurance = $val['insurance_prem'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Fire Insurance -' . $period,
                                        'total_amount' => $fire_insurance,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $data['manual_bill'] = array (
                                        'jmb_mc_name' => $val['jmb_mc_name'],
                                        'property_name' => $val['property_name'],
                                        'address_1' => $val['address_1'],
                                        'address_2' => $val['address_2'],
                                        'pin_code' => $val['pin_code'],
                                        'state_name' => $val_unit['state_name'],
                                        'country_name' => $val_unit['country_name'],
                                        'unit_no' => $val_unit['unit_no'],
                                        'bill_date' => $bill_date,
                                        'owner_name' => $val_unit['owner_name'],
                                        'bill_no' => $bill_no_format,
                                        'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                        'remarks' =>'Fire Insurance -' . $period,
                                        'first_name' => 'System',
                                        'last_name' => '',
                                        'created_date' => date('Y-m-d')
                                    );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_fire_insurance_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_fire_insurance_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $fire_insurance,
                                        'paid_amount' => 0,
                                        'bal_amount' => $fire_insurance,
                                        'paid_status' => 0
                                    );

                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                    $items = array (
                                        'cat_name' => $coa_fire_insurance_data->coa_name,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_fire_insurance_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $fire_insurance,
                                    );
                                    array_push ( $manual_bill_items, $items );

                                    $data['manual_bill_items'] = $manual_bill_items;

                                    $m_pdf = new M_pdf();

                                    // Generate pdf and sed attach to email
                                    $fire_insurance_view = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                    $m_pdf->pdf->WriteHTML ( $fire_insurance_view );
                                    $pdf_attachment = $m_pdf->pdf->Output('', 'S');

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                            "Email address for invoice is: " . $val_unit['email_addr'] .
                                            $val['jmb_mc_name'] . ", <br>" .
                                            $val['property_name'];
                                        $result = $this->email
                                            ->from( $val['email_addr'], $val['property_name'] )
                                            ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                            // ->to($val_unit['email_addr'])
                                            ->to('yameenadnan@hotmail.com')
                                            ->bcc('naguwin@gmail.com','Nagarajan')
                                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                            ->message ( $message )
                                            ->attach ($pdf_attachment, 'attachment', $filename, 'application/pdf')
                                            ->send ();

                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', 'Unable to find \'fi\' value in chart of Account. Check Chart Of Account');
                    }
                } else { $this->generate_log ('auto_billing_fire_insurance', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings'); }
                break;
            case 'qr':
                if ( !empty($val['calcul_base']) && !empty($val['sc_charge']) && !empty($val['property_abbrev']) && !empty($val['total_units']) && !empty($val['bill_due_days']) && !empty($val['email_addr']) ) {
                    $coa_quit_rent_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($val['property_id'], 'qr');
                    if ( !empty ($coa_quit_rent_data) ) {
                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_cron_jobs_model->getPropertyUnitsForAutoBilling ( $val['property_id'] );

                        if ( !empty( $data['property_units'] ) ) {
                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) ) || ($val['calcul_base'] == 3) ) ) {

                                    $manual_bill_items = array ();
                                    $quite_rent = 0;

                                    switch ( $val['calcul_base'] ) {
                                        case 1:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['square_feet'];
                                            break;
                                        case 2:
                                            $quite_rent = ($val['quit_rent'] / $val['sc_charge'] ) * $val_unit['share_unit'];
                                            break;
                                        case 3:
                                            $quite_rent = $val['quit_rent'] / $val['total_units'];
                                            break;
                                    }

                                    $bill_date = date ("Y-m-d");

                                    $period = date_format(date_create($bill_date),"Y");

                                    $prop_abbrev = $val['property_abbrev'];
                                    $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                    $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                    if (!empty ($last_bill_no)) {
                                        $last_no = explode('/', $last_bill_no['bill_no']);
                                        $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                    } else {
                                        $bill_no_format = $bill_no_format . 1001;
                                    }

                                    $bill_data = array (
                                        'property_id' => $val['property_id'],
                                        'block_id' => $val_unit['block_id'],
                                        'unit_id' => $val_unit['unit_id'],
                                        'bill_no' => $bill_no_format,
                                        'bill_date' => $bill_date,
                                        'bill_time' => date('H:i:s'),
                                        'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                        'remarks' => 'Quite Rent -' . $period,
                                        'total_amount' => $quite_rent,
                                        'bill_paid_status' => 0,
                                        'bill_type' => 1,
                                        'created_by' => 1541,
                                        'created_date' => date('Y-m-d')
                                    );
                                    $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                    $data['manual_bill'] = array (
                                        'jmb_mc_name' => $val['jmb_mc_name'],
                                        'property_name' => $val['property_name'],
                                        'address_1' => $val['address_1'],
                                        'address_2' => $val['address_2'],
                                        'pin_code' => $val['pin_code'],
                                        'state_name' => $val_unit['state_name'],
                                        'country_name' => $val_unit['country_name'],
                                        'unit_no' => $val_unit['unit_no'],
                                        'bill_date' => $bill_date,
                                        'owner_name' => $val_unit['owner_name'],
                                        'bill_no' => $bill_no_format,
                                        'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                        'remarks' =>'Quite Rent -' . $period,
                                        'first_name' => 'System',
                                        'last_name' => '',
                                        'created_date' => date('Y-m-d')
                                    );

                                    $bill_item_data = array(
                                        'bill_id' => $insert_id,
                                        'item_cat_id' => $coa_quit_rent_data->coa_id,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_quit_rent_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $quite_rent,
                                        'paid_amount' => 0,
                                        'bal_amount' => $quite_rent,
                                        'paid_status' => 0
                                    );

                                    $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                    $items = array (
                                        'cat_name' => $coa_quit_rent_data->coa_name,
                                        'item_period' => $period,
                                        'item_descrip' => $coa_quit_rent_data->coa_name . "(" . $period . ")",
                                        'item_amount' => $quite_rent,
                                    );
                                    array_push ( $manual_bill_items, $items );

                                    $data['manual_bill_items'] = $manual_bill_items;

                                    $m_pdf = new M_pdf();

                                    // Generate pdf and sed attach to email
                                    $quite_rent_view = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                    $m_pdf->pdf->WriteHTML ( $quite_rent_view );
                                    $pdf_attachment = $m_pdf->pdf->Output('', 'S');

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                            "Email address for invoice is: " . $val_unit['email_addr'] .
                                            $val['jmb_mc_name'] . ", <br>" .
                                            $val['property_name'];
                                        $result = $this->email
                                            ->from( $val['email_addr'], $val['property_name'] )
                                            ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                            // ->to($val_unit['email_addr'])
                                            ->to('yameenadnan@hotmail.com')
                                            ->bcc('naguwin@gmail.com','Nagarajan')
                                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                            ->message ( $message )
                                            ->attach ($pdf_attachment, 'attachment', $filename, 'application/pdf')
                                            ->send ();
                                        // sleep (5);
                                    } else {
                                        $this->generate_log ('auto_billing_quit_rent', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check unit setup.');
                                    }
                                } else {
                                    $this->generate_log ('auto_billing_quit_rent', $val['property_id'], $val_unit['unit_id'], 'Unable to find \'square_feet\' OR \'share_unit\' value. Check unit set up and match with property settings');
                                }
                            }
                        }
                    } else {
                        $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', 'Unable to find \'qr\' value in chart of Account. Check Chart Of Account');
                    }
                } else { $this->generate_log ('auto_billing_quit_rent', $val['property_id'], '', '\'calcul_base\' OR \'sc_charge\' OR \'property_abbrev\' OR \'total_units\' OR \'bill_due_days\' OR \'email_addr\' is NOT SET. Check property settings'); }
                break;
            case 'scsf':
                $val = $this->bms_fin_bills_model->chk_property_setting_sc_sf ( $property_id);
                if ( !empty($val) && !empty($val['calcul_base']) && !empty($val['sinking_fund']) && !empty($val['property_abbrev']) && !empty($val['bill_due_days']) ) {
                    $coa_service_charge_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($property_id, 'sc');
                    $coa_sinking_fund_data = $this->bms_fin_cron_jobs_model->getServiceChargeId ($property_id, 'sf');
                    if ( !empty ($coa_service_charge_data) && !empty ($coa_sinking_fund_data) ) {

                        if ( $val['calcul_base'] == 1 ) {
                            $verif_sq_units = $this->bms_fin_bills_model->verifyPropertyUnits ( $property_id, 'square_feet' );
                            if ( !empty($verif_sq_units) ) {
                                $_SESSION['flash_msg'] = 'Some units missing Unit No OR Square Feet OR Tier Name';
                                $_SESSION['flash_msg_class'] = 'alert-danger';
                                redirect ('index.php/bms_fin_bills/sc_sf_list/?property_id='.$_GET['property_id'] );
                                exit ();
                            }
                        } elseif ( $val['calcul_base'] == 2 ) {
                            $verif_su_units = $this->bms_fin_bills_model->verifyPropertyUnits ( $property_id, 'share_unit' );
                            if ( !empty($verif_su_units) ) {
                                $_SESSION['flash_msg'] = 'Some units missing Unit No OR Share Unit OR Tier Name';
                                $_SESSION['flash_msg_class'] = 'alert-danger';
                                redirect ('index.php/bms_fin_bills/sc_sf_list/?property_id='.$_GET['property_id'] );
                                exit ();
                            }
                        } elseif ( $val['calcul_base'] == 3 ) {
                            if ( empty($val['sc_charge'])  ) {
                                $_SESSION['flash_msg'] = 'Property Setting missing Amount';
                                $_SESSION['flash_msg_class'] = 'alert-danger';
                                redirect ('index.php/bms_fin_bills/sc_sf_list/?property_id='.$_GET['property_id'] );
                                exit ();
                            }
                        }

                        $bill_date = '';
                        $data['property_units'] = $this->bms_fin_bills_model->getPropertyUnitsForSCSF ( $property_id );

                        if ( !empty( $data['property_units'] ) ) {
                            $limit = 1;
                            switch ( $billing_cycle ) {
                                case 2:
                                    $limit = 2;
                                    break;
                                case 3:
                                    $limit = 3;
                                    break;
                                case 4:
                                    $limit = 4;
                                    break;
                                case 5:
                                    $limit = 6;
                                    break;
                                case 6:
                                    $limit = 12;
                                    break;
                            }

                            foreach ( $data['property_units'] as $key_unit => $val_unit ) {
                                if ( !empty($val_unit['unit_no']) && ( ($val['calcul_base'] == 1 && !empty($val_unit['square_feet']) && !empty ($val_unit['tier_value']) ) || ($val['calcul_base'] == 2 && !empty($val_unit['share_unit']) && !empty($val_unit['tier_value']) ) || ($val['calcul_base'] == 3 && !empty($val['sc_charge']) ) ) ) {
                                    $pdf_attachment = array ();
                                    for ( $i = 0; $i<$limit;$i++ ) {
                                        $manual_bill_items = array ();
                                        $service_charge = 0;
                                        $sinking_fund = 0;

                                        switch ( $val['calcul_base'] ) {
                                            case 1:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['square_feet'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 2:
                                                $service_charge = $val_unit['tier_value'] * $val_unit['share_unit'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                            case 3:
                                                $service_charge = $val['sc_charge'];
                                                $sinking_fund = ($val['sinking_fund'] * $service_charge) / 100;
                                                break;
                                        }

                                        $bill_date = date ('Y-m-d', strtotime ("+$i month", strtotime ( $bill_generate_date )) );

                                        $period = date_format(date_create($bill_date),"M-y");

                                        $prop_abbrev = $val['property_abbrev'];

                                        $bill_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX') . '/SI/' . date('y',strtotime($bill_date)) . '/' . date('m',strtotime($bill_date)) . '/';
                                        $last_bill_no = $this->bms_fin_bills_model->getLastBillNo($bill_no_format);
                                        if (!empty ($last_bill_no)) {
                                            $last_no = explode('/', $last_bill_no['bill_no']);
                                            $bill_no_format = $bill_no_format . (end($last_no) + 1);
                                        } else {
                                            $bill_no_format = $bill_no_format . 1001;
                                        }

                                        $bill_data = array (
                                            'property_id' => $property_id,
                                            'block_id' => $val_unit['block_id'],
                                            'unit_id' => $val_unit['unit_id'],
                                            'bill_no' => $bill_no_format,
                                            'bill_date' => $bill_date,
                                            'bill_time' => date('H:i:s'),
                                            'bill_due_date' => date('Y-m-d', strtotime($bill_date."+" . $val['bill_due_days'] . " day")),
                                            'remarks' => 'SC & SF-' . $period,
                                            'total_amount' => $service_charge + $sinking_fund,
                                            'bill_paid_status' => 0,
                                            'bill_type' => 1,
                                            'created_by' => 1541,
                                            'created_date' => date('Y-m-d')
                                        );
                                        $insert_id = $this->bms_fin_cron_jobs_model->insertBill ( $bill_data );

                                        $data['manual_bill'] = array (
                                            'jmb_mc_name' => $val['jmb_mc_name'],
                                            'property_name' => $val['property_name'],
                                            'address_1' => $val['address_1'],
                                            'address_2' => $val['address_2'],
                                            'pin_code' => $val['pin_code'],
                                            'state_name' => $val_unit['state_name'],
                                            'country_name' => $val_unit['country_name'],
                                            'unit_no' => $val_unit['unit_no'],
                                            'bill_date' => $bill_date,
                                            'owner_name' => $val_unit['owner_name'],
                                            'bill_no' => $bill_no_format,
                                            'bill_due_date' => date('d-m-Y', strtotime($bill_date . "+" . $val['bill_due_days'] . " day")),
                                            'remarks' =>'SC & SF-' . $period,
                                            'first_name' => 'System',
                                            'last_name' => '',
                                            'created_date' => date('Y-m-d')
                                        );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_service_charge_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                            'paid_amount' => 0,
                                            'bal_amount' => $service_charge,
                                            'paid_status' => 0
                                        );
                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $bill_item_data = array(
                                            'bill_id' => $insert_id,
                                            'item_cat_id' => $coa_sinking_fund_data->coa_id,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                            'paid_amount' => 0,
                                            'bal_amount' => $sinking_fund,
                                            'paid_status' => 0
                                        );

                                        $this->bms_fin_bills_model->insertBillItem ( $bill_item_data );

                                        $items = array (
                                            'cat_name' => $coa_service_charge_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_service_charge_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $service_charge,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $items = array (
                                            'cat_name' => $coa_sinking_fund_data->coa_name,
                                            'item_period' => $period,
                                            'item_descrip' => $coa_sinking_fund_data->coa_name . "(" . $period . ")",
                                            'item_amount' => $sinking_fund,
                                        );
                                        array_push ( $manual_bill_items, $items );

                                        $data['manual_bill_items'] = $manual_bill_items;

                                        $m_pdf = new M_pdf();

                                        // Generate pdf and sed attach to email
                                        $sc_and_sf_PDF = $this->load->view ( 'finance/manual_bill/manual_bill_details_print_view', $data, true);

                                        $m_pdf->pdf->WriteHTML ( $sc_and_sf_PDF );
                                        $pdf_attachment[] = $m_pdf->pdf->Output('', 'S');
                                    }

                                    if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                                        $filename = 'sales_invoice.pdf';
                                        $this->load->library('email');
                                        $this->email->clear(true);
                                        $message = "Dear " . $val_unit['owner_name'] . ",<br>
                                        Please find attached invoice(s) for your prompt payment.<br><br>
                                        Thank you,<br>" .
                                            "Email address for invoice is: " . $val_unit['email_addr'] .
                                            $val['jmb_mc_name'] . ", <br>" .
                                            $val['property_name'];
                                        $result = $this->email
                                            ->from( $val['email_addr'], $val['property_name'] )
                                            ->reply_to( $val['email_addr'] , $val['property_name'] )    // Optional, an account where a human being reads.
                                            // ->to($val_unit['email_addr'])
                                            ->to('yameenadnan@hotmail.com')
                                            ->bcc('naguwin@gmail.com','Nagarajan')
                                            ->subject ( $val['property_name'] . '-' . $val_unit['unit_no'] . (!empty($val_unit['owner_name'])? ' - ' . $val_unit['owner_name']:'') . ' Invoice for the period of ' . $period )
                                            ->message ( $message );
                                        if( !empty( $pdf_attachment ) ) {
                                            foreach ($pdf_attachment as $kye_email) {
                                                $this->email->attach($kye_email, 'attachment', $filename, 'application/pdf');
                                            }
                                        }
                                        $this->email->send ();
                                        // sleep (5);
                                    } else {
                                        // $this->generate_log ('auto_billing', $val['property_id'], $val_unit['unit_id'], 'Owner Email Address is Empty or Invalid. Check owner email in unit setup.');
                                    }
                                    $_SESSION['flash_msg'] = 'Invoices generated Successfully! ';
                                    $_SESSION['flash_msg_class'] = 'alert-success';
                                } else {
                                    $faulty_unit_array[] = $val_unit['unit_no'];
                                    $_SESSION['flash_msg'] = 'Unable to find \'square_feet\' OR \'share_unit\' OR \'tier_value\' OR \'sc_charge\' values. ';
                                    $_SESSION['flash_msg_class'] = 'alert-danger';
                                }
                            }
                        }
                        if ( !empty ( $faulty_unit_array ) ) {
                            $_SESSION['flash_msg'] = 'Unable to find \'square_feet\' OR \'share_unit\' OR \'tier_value\' OR \'sc_charge\' values. Check property setting and these units and ' . implode (", ",$faulty_unit_array);
                            $_SESSION['flash_msg_class'] = 'alert-danger';
                        }
                    } else {
                        $_SESSION['flash_msg'] = 'Unable to find \'sc\' OR \'sf\' value in chart of Account. Check Chart Of Account';
                        $_SESSION['flash_msg_class'] = 'alert-danger';
                    }
                } else {}
                break;
        }

        redirect ('index.php/bms_fin_bills/semi_auto_invoice_list/?property_id='.$_GET['property_id'] );
    }

    public function generate_log ($script_name, $property_id, $unit_id = '', $log_message) {
        $custom_error_folder = FCPATH .'bms_uploads'.DIRECTORY_SEPARATOR.'custom_error_log'.DIRECTORY_SEPARATOR;
        $invalid_email_file = $custom_error_folder.'cron_job_error_msg.txt';
        $content = "\n\r". date('d-m-Y H:i:s') . "\n" . 'Script Name: ' . $script_name. "\n" . 'File Name: ' . basename(__FILE__) .  "\n" . 'property_id=>' . $property_id . ( (!empty($unit_id))? ', Unit_id=>' . $unit_id:'' ) . ', Error_msg=>' . $log_message;
        echo $log_message;
        file_put_contents($invalid_email_file,$content,FILE_APPEND);
    }

}