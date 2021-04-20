<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_fin_accounting extends CI_Controller {
   
    function __construct () { 
        parent::__construct ();
        if(!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false || $_SESSION['bms']['user_type'] != 'staff') {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.tpaccbms.com' ? 'https://www.tpaccbms.com' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);       
	    }
        //$this->user_access_log->user_access_log_insert(); // create user access log
        // load necessary models
        
        $this->load->model('bms_masters_model');  
        $this->load->model('bms_fin_masters_model'); 
        $this->load->model('bms_fin_accounting_model');
        $this->load->helper('common_functions');
    }
    
    function journal_entry ($offset = 0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Journal Entry';
        $data['page_header'] = '<i class="fa fa-file"></i> Journal Entry';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        //$data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
        
        $this->load->view('finance/accounting/jv_list_view',$data);
    }
    
    function get_jvs_list () {
        header('Content-type: application/json');        
        
        $jvs = array('numFound'=>0,'records'=>array());
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            //$unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            $jvs = $this->bms_fin_accounting_model->getJvsList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $from,$to);
        }   
        echo json_encode($jvs);
    }
    
    function add_journal_entry ($jv_id='') {
        $data['browser_title'] = 'Property Butler | Journal Entry';
        $data['page_header'] = '<i class="fa fa-file"></i> Journal Entry <i class="fa fa-angle-double-right"></i> '.($jv_id != '' ? 'Update' : 'New').' Journal Entry';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        if(empty($data ['property_id'])) {
            redirect('index.php/bms_dashboard/index');
        }
        $data['coas'] = $this->bms_fin_accounting_model->getAllCoa ($data ['property_id']);
        
        if(!empty($jv_id)) {
            $data['jv'] = $this->bms_fin_accounting_model->getJv($jv_id);
            if(!empty($data['jv']['jv_id'])) {
                $data['jv_items'] = $this->bms_fin_accounting_model->getJvItems($data['jv']['jv_id']);
            }
        }
        
        $this->load->view('finance/accounting/jv_add_view',$data);
    }
    
    function add_journal_entry_submit ($jv_id='') {
        //echo "<pre>";print_r($_POST);echo "</pre>"; exit;       
        
        $jv = $this->input->post('jv');
        $items = $this->input->post('jv_items');
        $type = 'add';
        if(!empty($jv['jv_date'])) {
            $jv['jv_date'] = date('Y-m-d',strtotime($jv['jv_date']));
        }        
        
        if(!empty($jv['jv_id'])) {
            $type = 'edit';
            $jv_id = $jv['jv_id'];
            $this->bms_fin_accounting_model->updateJv ($jv,$jv['jv_id']);
        } else {
            if(isset($jv['jv_id'])) unset($jv['jv_id']);
            $jv_date = !empty($jv['jv_date']) ? $jv['jv_date'] : date('d-m-Y');
            $prop_abbrev = $this->input->post('prop_abbr');
            $jv_no_format = ($prop_abbrev != '' ? $prop_abbrev : 'XX'). '/JV/'.date('y/m',strtotime($jv_date)).'/';
            $last_jv_no = $this->bms_fin_accounting_model->getLastJvNo ($jv_no_format);
            if(!empty ($last_jv_no)) {
                $last_no = explode('/',$last_jv_no['jv_no']);
                $jv['jv_no'] = $jv_no_format . (end($last_no) +1);
            } else {
                $jv['jv_no'] = $jv_no_format . 1001;
            }
            $jv['jv_time'] = date('H:i:s');
            $jv_id = $this->bms_fin_accounting_model->insertJv ($jv);
        }
        
        if(!empty($items['jv_coa_id'])) {
            $item['jv_id'] = $jv_id;
            foreach ($items['jv_coa_id'] as $key=>$val) {
                if(!empty($val)) {
                    $item['jv_coa_id'] = $val;                     
                    $item['description'] = $items['description'][$key];
                    $item['debit'] = $items['debit'][$key];
                    $item['credit'] = $items['credit'][$key];
                    
                    if(!empty($items['jv_item_id'][$key])) {
                        $this->bms_fin_accounting_model->updateJvItem ($item,$items['jv_item_id'][$key]);
                    } else {
                        $this->bms_fin_accounting_model->insertJvItem ($item);    
                    }
                }                                
            }
        }
        $_SESSION['flash_msg'] = 'Journal Entry '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!'; 
        redirect ('index.php/bms_fin_accounting/journal_entry');
    }
    
       
    function unset_jv_item () {        
        $jv_item_id = $this->input->post('jv_item_id');
        echo $this->bms_fin_accounting_model->deleteJvItem ($jv_item_id); 
    }
    
    function unset_jv () {        
        $jv_id = $this->input->post('jv_id');
        $this->bms_fin_accounting_model->deleteJvItemByJvId ($jv_id); 
        echo $this->bms_fin_accounting_model->deleteJv ($jv_id);
    }
    
    function general_ledger () {
        $data['browser_title'] = 'Property Butler | General Ledger';
        $data['page_header'] = '<i class="fa fa-file"></i> General Ledger';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        if(!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
            
            $from = date('Y-m-d',strtotime(trim($_GET['from_date'])));
            $to = date('Y-m-d',strtotime(trim($_GET['to_date']))); 
            
            $result = $this->bms_fin_accounting_model->get_coa ($data['property_id']);           
            
            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                }
            }           
            
            require_once APPPATH.'/third_party/PHPExcel.php';
            require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
            
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->mergeCells('E4:G4');
            
            
            
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'General Ledger' );
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period: '.$_GET['from_date'] .' - '.$_GET['to_date']);
            
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');
            $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Extracted: '.date('d-m-Y h:i a'));
            
            $row = 4;
            $sheet_head = array('Date', 'Description', 'Reference', 'Remarks', 'Debit', 'Credit', 'Balance');
            
            $styleArray = array(
                            	'borders' => array(
                            		'outline' => array(
                            			'style' => PHPExcel_Style_Border::BORDER_THICK,
                            			'color' => array('argb' => '00000000'),
                            		),
                                    'fill' => array(
                                		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                		'rotation' => 90,
                                		'startcolor' => array(
                                			'argb' => 'FFA0A0A0',
                                		),
                                		'endcolor' => array(
                                			'argb' => 'E1E1E1E1',
                                		),
                                	),

                            	),
                            );
            
            foreach ($result as $key=>$val) {
                
                $data['result'] = array ();
                
                //if( $val['coa_code'] != '5001/000') {
                
                
                    /// After Some Operations
                    
                    $row += 3;
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val['coa_code'] . ' - ' . $val['coa_name'] ); 
                    $row++;
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':G'.$row)->getFont()->setBold(true)->setSize(12);
                    $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                                   
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':G'.$row)->applyFromArray($styleArray);
                    //$val['opening_debit'] = $val['opening_credit'] = 0.00;
                    $row++;
                    $balance = $bf_debit = $bf_credit = 0; 
                    if((!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) || (!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001)) {
                        //echo "<br />".$val['coa_name'];
                        if(!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) {
                              $balance +=  $val['opening_debit']; 
                              $bf_debit += $val['opening_debit'];
                        }                   
                        
                        if(!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001) {
                            $balance -=  $val['opening_credit']; 
                            $bf_credit += $val['opening_credit'];
                        }
                    }
                    
                    //echo "<pre><br />".$val['coa_code'];print_r($val['coa_name']);echo "</pre>";
                    
                    // payment & Receipt Enabled 
                    /*if(isset($val['payment_enabled']) && $val['payment_enabled'] == 1 && isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_pay_n_receipt_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_pay_n_receipt_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        
                        $data['result'] = $this->bms_fin_accounting_model->get_pay_n_receipt_ena ($data['property_id'],$val['coa_id'],$from,$to);
                        
                    } 
                    else */  // Bill & Receipt Enabled 
                    if(isset($val['bill_enabled']) && $val['bill_enabled'] == 1 && isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_bill_receipt_ena_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_bill_receipt_ena_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        
                        $data['result'] = $this->bms_fin_accounting_model->get_bill_receipt_ena ($data['property_id'],$val['coa_id'],$from,$to);
                    
                    } // deposit Enabled
                    else if(isset($val['deposit_enabled']) && $val['deposit_enabled'] == 1) {
                        
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_deposit_ena_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_deposit_ena_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        
                        $data['result'] = $this->bms_fin_accounting_model->get_deposit_ena ($data['property_id'],$val['coa_id'],$from,$to);
                        
                        
                    
                    } else if(isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_receipt_ena_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_receipt_ena_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        
                        $data['result'] = $this->bms_fin_accounting_model->get_receipt_ena ($data['property_id'],$val['coa_id'],$from,$to);
                        
                        
                    
                    } else if(isset($val['payment_source']) && $val['payment_source'] == 1) { // Banks
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_pay_sour_ena_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_pay_sour_ena_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        
                        $data['result'] = $this->bms_fin_accounting_model->get_pay_sour_ena ($data['property_id'],$val['coa_id'],$from,$to);
                        
                        
                    } else if(isset($val['payment_enabled']) && $val['payment_enabled'] == 1) {
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        $data['result'] = $this->bms_fin_accounting_model->get_pay_ena ($data['property_id'],$val['coa_id'],$from,$to);
                        //echo "<pre>";print_r($data['result']);echo "</pre>";
                    } 
                    else if($val['coa_code'] == '3000/000') {  // DEBTOR CONTROL(Resident)
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_debtors_bf_debit ($data['property_id'],$from,$val['coa_id']);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_debtors_bf_credit ($data['property_id'],$from,$val['coa_id']);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        $data['result'] = $this->bms_fin_accounting_model->get_debtors_ledger ($data['property_id'],$from,$to,$val['coa_id']);
                        
                    } // TRADE CREDITORS (Service Provider / Vendor)
                    else if($val['coa_code'] == '4100/000') {
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_creditors_bf_debit ($data['property_id'],$from);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_creditors_bf_credit ($data['property_id'],$from);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        $data['result'] = $this->bms_fin_accounting_model->get_creditors ($data['property_id'],$from,$to);
                    } else { 
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_non_enabled_bf_debit ($data['property_id'],$from,$val['coa_id']);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_non_enabled_bf_credit ($data['property_id'],$from,$val['coa_id']);
                        if(!empty($bf_debit_arr['amount'])) {
                            $balance += $bf_debit_arr['amount'];
                            $bf_debit += $bf_debit_arr['amount'];
                        }
                        if(!empty($bf_credit_arr['amount'])) {
                            $balance -= $bf_credit_arr['amount'];
                            $bf_credit += $bf_credit_arr['amount'];
                        }
                        $data['result'] = $this->bms_fin_accounting_model->get_non_enabled_ledger ($data['property_id'],$val['coa_id'],$from,$to);
                    }
                    
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $_GET['from_date']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Balance B/F'); 
                    if(!empty($bf_debit)) {
                        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $bf_debit);    
                    }
                    if(!empty($bf_credit)) {
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $bf_credit);    
                    }
                    //echo "<br />".$val['coa_name'] ." => ".$balance;
                    if(!empty($balance)) {
                        $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $balance);    
                    }
                    $number_format_start = $row;
                    if(!empty($data['result'])) {
                        foreach ($data['result'] as $key2=>$val2) {
                            $row++;
                            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, date('d-m-Y',strtotime($val2['doc_date'])));
                            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val2['descrip']); 
                            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val2['doc_no']); 
                            if($val2['item_type'] == 'debit') {
                                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val2['amount']); 
                                $balance += $val2['amount']; 
                                $bf_debit += $val2['amount'];
                            }
                            if($val2['item_type'] == 'credit') {
                                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $val2['amount']);
                                $balance -= $val2['amount'];  
                                $bf_credit += $val2['amount'];
                            }
                            
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $balance);
                            
                        }
                        
                        //$range = 'A'.$row.':'.$latestBLColumn.$row;
                        //$objPHPExcel->getActiveSheet()->getStyle($range)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
                                                
                    }
                    
                    $row++;                        
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Total:');                 
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $bf_debit); 
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $bf_credit); 
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':F'.$row)->getFont()->setBold(true)->setSize(12);
                    //$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':F'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    $row++;                        
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Balance C/F:');                 
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $balance);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':E'.$row)->getFont()->setBold(true)->setSize(12);
                    //$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    $row += 2;                        
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Total Debits:');                 
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $bf_debit);
                    //$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $row++;
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Total Credits:');
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $bf_credit);
                    //$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    // number format with comma separated
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$number_format_start.':E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$number_format_start.':F'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$number_format_start.':G'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    // Text align right
                    $objPHPExcel->getActiveSheet()->getStyle('D'.($row-4).':D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                
                                
                
            }
            //exit;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            
            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('General Ledger');
            
                        
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="GL_'.$property_name.$_GET['from_date'] .' - '.$_GET['to_date'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
                   
            //echo "<pre>";print_r($data['result']); exit;
        }
        $this->load->view('finance/accounting/general_ledger_view',$data);
    }
    
    function debtor_aging_report () {
        $data['browser_title'] = 'Property Butler | Debtor Aging Report';
        $data['page_header'] = '<i class="fa fa-file"></i> Debtor Aging Report';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        if(!empty($_GET['property_id']) && !empty($_GET['from']) && !empty($_GET['aging_report']) && $_GET['aging_report'] == 'show') {
            //
            $data['debtors'] = $this->bms_fin_accounting_model->get_debtors_list ($data['property_id']);
            //echo "<pre>";print_r($data['debtors']);echo "</pre>";exit;
            $as_of_date = strtotime($_GET['from']);            
            if(!empty($data['debtors'])) {
                $date_four_mon_start = date('Y-m-d',strtotime('-119 days',$as_of_date));
                $date_four_mon_end = date('Y-m-d',strtotime('-90 days',$as_of_date));
                $date_three_mon_start = date('Y-m-d',strtotime('-89 days',$as_of_date));
                $date_three_mon_end = date('Y-m-d',strtotime('-60 days',$as_of_date));
                $date_two_mon_start = date('Y-m-d',strtotime('-59 days',$as_of_date));
                $date_two_mon_end = date('Y-m-d',strtotime('-30 days',$as_of_date));
                $date_curr_mon_start = date('Y-m-d',strtotime('-29 days',$as_of_date));
                $date_curr_mon_end = date('Y-m-d',strtotime($_GET['from']));
                
                $data['five_mon_plus'] = $this->bms_fin_accounting_model->get_debtors_aging ($data['property_id'],$date_four_mon_start);
                $data['four_mon'] = $this->bms_fin_accounting_model->get_debtors_aging ($data['property_id'],$date_four_mon_start,$date_four_mon_end);
                $data['three_mon'] = $this->bms_fin_accounting_model->get_debtors_aging ($data['property_id'],$date_three_mon_start,$date_three_mon_end);
                $data['two_mon'] = $this->bms_fin_accounting_model->get_debtors_aging ($data['property_id'],$date_two_mon_start,$date_two_mon_end);
                $data['curr_mon'] = $this->bms_fin_accounting_model->get_debtors_aging ($data['property_id'],$date_curr_mon_start,$date_curr_mon_end);
                
                $data['aging'][5]['total'] = 0;
                foreach($data['five_mon_plus'] as $key=>$val) {
                    $data['aging'][5][$val['unit_id']] = $val['amt'];
                    $data['aging'][5]['total'] += $val['amt'];
                }
                
                $data['aging'][4]['total'] = 0;
                foreach($data['four_mon'] as $key=>$val) {
                    $data['aging'][4][$val['unit_id']] = $val['amt'];
                    $data['aging'][4]['total'] += $val['amt'];
                }
                
                $data['aging'][3]['total'] = 0;
                foreach($data['three_mon'] as $key=>$val) {
                    $data['aging'][3][$val['unit_id']] = $val['amt'];
                    $data['aging'][3]['total'] += $val['amt'];
                }
                
                $data['aging'][2]['total'] = 0;
                foreach($data['two_mon'] as $key=>$val) {
                    $data['aging'][2][$val['unit_id']] = $val['amt'];
                    $data['aging'][2]['total'] += $val['amt'];
                }
                
                $data['aging'][1]['total'] = 0;
                foreach($data['curr_mon'] as $key=>$val) {
                    $data['aging'][1][$val['unit_id']] = $val['amt'];
                    $data['aging'][1]['total'] += $val['amt'];
                }
                
                
                $property_name = '';
                foreach ($data['properties'] as $key=>$val) {
                    if($val['property_id'] == $_GET['property_id'] ) {
                        $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                    }
                }
                
                
                require_once APPPATH.'/third_party/PHPExcel.php';
                require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
                
                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                
                // Create a first sheet, representing sales data
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                
                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
                //$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                //$objPHPExcel->getActiveSheet()->mergeCells('E4:G4');
                
                
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
                $objPHPExcel->getActiveSheet()->setCellValue('A2', 'TRADE DEBTORS - AGING REPORT AS AT ' .date('d-m-Y',$as_of_date));
                //$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period: '.$_GET['from_date'] .' - '.$_GET['to_date']);
                
                $objPHPExcel->getActiveSheet()->setCellValue('A3', 'All currencies are in RM');
                //$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Extracted: '.date('d-m-Y h:i a'));
                
                
                $sheet_head = array('S No', 'Account No', 'Unit No', 'Name', '121Days+', '91-120days', '61-90Days', '31-60Days','0-30Days','Total');
                
                $styleArray = array(
                            	'borders' => array(
                            		'outline' => array(
                            			'style' => PHPExcel_Style_Border::BORDER_THICK,
                            			'color' => array('argb' => '00000000'),
                            		),
                                    'fill' => array(
                                		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                		'rotation' => 90,
                                		'startcolor' => array(
                                			'argb' => 'FFA0A0A0',
                                		),
                                		'endcolor' => array(
                                			'argb' => 'E1E1E1E1',
                                		),
                                	),

                            	),
                            );
                            
                $row = 4;                      
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                               
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->applyFromArray($styleArray);
                
                
                $sno = 1;            
                foreach ($data['debtors'] as $key=>$val) {
                    
                    if(!empty($data['aging'][5][$val['unit_id']]) || !empty($data['aging'][4][$val['unit_id']]) || !empty($data['aging'][3][$val['unit_id']]) || !empty($data['aging'][2][$val['unit_id']]) || !empty($data['aging'][1][$val['unit_id']])) {                    
                    
                        $row++; $sub_total = 0;
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $sno++);
                        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_code']);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['unit_no']);
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['owner_name']);
                        if(!empty($data['aging'][5][$val['unit_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][5][$val['unit_id']]);
                            $sub_total += $data['aging'][5][$val['unit_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, ' - ');
                        }
                        if(!empty($data['aging'][4][$val['unit_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $data['aging'][4][$val['unit_id']]);
                            $sub_total += $data['aging'][4][$val['unit_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, ' - ');
                        }
                        if(!empty($data['aging'][3][$val['unit_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $data['aging'][3][$val['unit_id']]);
                            $sub_total += $data['aging'][3][$val['unit_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, ' - ');
                        }
                        if(!empty($data['aging'][2][$val['unit_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $data['aging'][2][$val['unit_id']]);
                            $sub_total += $data['aging'][2][$val['unit_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, ' - ');
                        }
                        if(!empty($data['aging'][1][$val['unit_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $data['aging'][1][$val['unit_id']]);
                            $sub_total += $data['aging'][1][$val['unit_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, ' - ');
                        }
                        
                        $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $sub_total);                          
                    }
                } 
                
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Total');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][5]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $data['aging'][4]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $data['aging'][3]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $data['aging'][2]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $data['aging'][1]['total']);           
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, ($data['aging'][5]['total']+$data['aging'][4]['total']+$data['aging'][3]['total']+$data['aging'][2]['total']+$data['aging'][1]['total'])); 
                //$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][5][$val['unit_id']]);
                
                $objPHPExcel->getActiveSheet()->getStyle('E5:E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H5:H'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I5:I'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J5:J'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $objPHPExcel->getActiveSheet()->getStyle('J5:J'.$row)->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':J'.$row)->getFont()->setBold(true)->setSize(12);
                
                
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                
                
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('Debtors Aging');
                
                            
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="AGING_'.$property_name.date('Ymd').'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');    
                
                //echo "<pre>";print_r($data['two_mon']);echo "</pre>";
                
            }
            
        }
        $this->load->view('finance/accounting/debtor_aging_view',$data);
    }
    
    function creditor_aging_report () {
        $data['browser_title'] = 'Property Butler | Creditor Aging Report';
        $data['page_header'] = '<i class="fa fa-file"></i> Creditor Aging Report';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        if(!empty($_GET['property_id']) && !empty($_GET['from']) && !empty($_GET['aging_report']) && $_GET['aging_report'] == 'show') {
            //
            $data['creditors'] = $this->bms_fin_accounting_model->get_creditors_list ($data['property_id']);
            //echo "<pre>";print_r($data['debtors']);echo "</pre>";exit;
            $as_of_date = strtotime($_GET['from']);
            if(!empty($data['creditors'])) {
                $date_four_mon_start = date('Y-m-d',strtotime('-119 days',$as_of_date));
                $date_four_mon_end = date('Y-m-d',strtotime('-90 days',$as_of_date));
                $date_three_mon_start = date('Y-m-d',strtotime('-89 days',$as_of_date));
                $date_three_mon_end = date('Y-m-d',strtotime('-60 days',$as_of_date));
                $date_two_mon_start = date('Y-m-d',strtotime('-59 days',$as_of_date));
                $date_two_mon_end = date('Y-m-d',strtotime('-30 days',$as_of_date));
                $date_curr_mon_start = date('Y-m-d',strtotime('-29 days',$as_of_date));
                $date_curr_mon_end = date('Y-m-d',strtotime($_GET['from']));
                
                
                
                $data['five_mon_plus'] = $this->bms_fin_accounting_model->get_creditors_aging ($data['property_id'],$date_four_mon_start);
                $data['four_mon'] = $this->bms_fin_accounting_model->get_creditors_aging ($data['property_id'],$date_four_mon_start,$date_four_mon_end);
                $data['three_mon'] = $this->bms_fin_accounting_model->get_creditors_aging ($data['property_id'],$date_three_mon_start,$date_three_mon_end);
                $data['two_mon'] = $this->bms_fin_accounting_model->get_creditors_aging ($data['property_id'],$date_two_mon_start,$date_two_mon_end);
                $data['curr_mon'] = $this->bms_fin_accounting_model->get_creditors_aging ($data['property_id'],$date_curr_mon_start,$date_curr_mon_end);
                
                $data['aging'][5]['total'] = 0;
                foreach($data['five_mon_plus'] as $key=>$val) {
                    $data['aging'][5][$val['service_provider_id']] = $val['amt'];
                    $data['aging'][5]['total'] += $val['amt'];
                }
                
                $data['aging'][4]['total'] = 0;
                foreach($data['four_mon'] as $key=>$val) {
                    $data['aging'][4][$val['service_provider_id']] = $val['amt'];
                    $data['aging'][4]['total'] += $val['amt'];
                }
                
                $data['aging'][3]['total'] = 0;
                foreach($data['three_mon'] as $key=>$val) {
                    $data['aging'][3][$val['service_provider_id']] = $val['amt'];
                    $data['aging'][3]['total'] += $val['amt'];
                }
                
                $data['aging'][2]['total'] = 0;
                foreach($data['two_mon'] as $key=>$val) {
                    $data['aging'][2][$val['service_provider_id']] = $val['amt'];
                    $data['aging'][2]['total'] += $val['amt'];
                }
                
                $data['aging'][1]['total'] = 0;
                foreach($data['curr_mon'] as $key=>$val) {
                    $data['aging'][1][$val['service_provider_id']] = $val['amt'];
                    $data['aging'][1]['total'] += $val['amt'];
                }
                
                
                $property_name = '';
                foreach ($data['properties'] as $key=>$val) {
                    if($val['property_id'] == $_GET['property_id'] ) {
                        $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                    }
                }
                
                
                require_once APPPATH.'/third_party/PHPExcel.php';
                require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
                
                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                
                // Create a first sheet, representing sales data
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                
                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
                //$objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
                
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
                $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                //$objPHPExcel->getActiveSheet()->mergeCells('E4:G4');
                
                
                
                $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
                $objPHPExcel->getActiveSheet()->setCellValue('A2', 'TRADE CREDITORS - AGING REPORT AS AT ' .date('d-m-Y',$as_of_date));
                //$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period: '.$_GET['from_date'] .' - '.$_GET['to_date']);
                
                $objPHPExcel->getActiveSheet()->setCellValue('A3', 'All currencies are in RM');
                //$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Extracted: '.date('d-m-Y h:i a'));
                
                
                $sheet_head = array('S No', 'Account No', 'Name', '121Days+', '91-120days', '61-90Days', '31-60Days','0-30Days','Total');
                
                $styleArray = array(
                            	'borders' => array(
                            		'outline' => array(
                            			'style' => PHPExcel_Style_Border::BORDER_THICK,
                            			'color' => array('argb' => '00000000'),
                            		),
                                    'fill' => array(
                                		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                		'rotation' => 90,
                                		'startcolor' => array(
                                			'argb' => 'FFA0A0A0',
                                		),
                                		'endcolor' => array(
                                			'argb' => 'E1E1E1E1',
                                		),
                                	),

                            	),
                            );
                            
                $row = 4;                      
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                               
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->applyFromArray($styleArray);
                
                
                $sno = 1;            
                foreach ($data['creditors'] as $key=>$val) {
                    
                    if(!empty($data['aging'][5][$val['service_provider_id']]) || !empty($data['aging'][4][$val['service_provider_id']]) || !empty($data['aging'][3][$val['service_provider_id']]) || !empty($data['aging'][2][$val['service_provider_id']]) || !empty($data['aging'][1][$val['service_provider_id']])) {                    
                    
                        $row++; $sub_total = 0;
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $sno++);
                        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_code']);
                        //$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['unit_no']);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['provider_name']);
                        if(!empty($data['aging'][5][$val['service_provider_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $data['aging'][5][$val['service_provider_id']]);
                            $sub_total += $data['aging'][5][$val['service_provider_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, ' - ');
                        }
                        if(!empty($data['aging'][4][$val['service_provider_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][4][$val['service_provider_id']]);
                            $sub_total += $data['aging'][4][$val['service_provider_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, ' - ');
                        }
                        if(!empty($data['aging'][3][$val['service_provider_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $data['aging'][3][$val['service_provider_id']]);
                            $sub_total += $data['aging'][3][$val['service_provider_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, ' - ');
                        }
                        if(!empty($data['aging'][2][$val['service_provider_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $data['aging'][2][$val['service_provider_id']]);
                            $sub_total += $data['aging'][2][$val['service_provider_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, ' - ');
                        }
                        if(!empty($data['aging'][1][$val['service_provider_id']])) {
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $data['aging'][1][$val['service_provider_id']]);
                            $sub_total += $data['aging'][1][$val['service_provider_id']];
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, ' - ');
                        }
                        
                        $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $sub_total);
                          
                    }
                }  
                
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Total');
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $data['aging'][5]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][4]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $data['aging'][3]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $data['aging'][2]['total']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $data['aging'][1]['total']);           
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, ($data['aging'][5]['total']+$data['aging'][4]['total']+$data['aging'][3]['total']+$data['aging'][2]['total']+$data['aging'][1]['total']));          
                
                
                $objPHPExcel->getActiveSheet()->getStyle('D5:D'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('E5:E'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H5:H'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I5:I'.$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $objPHPExcel->getActiveSheet()->getStyle('I5:I'.$row)->getFont()->setBold(true)->setSize(12);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':I'.$row)->getFont()->setBold(true)->setSize(12);
                
                
                //$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $data['aging'][5][$val['unit_id']]);
                
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                //$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
                
                
                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle('Creditors Aging');
                
                            
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="CREDITORS_AGING_'.$property_name.date('Ymd').'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');    
                
                //echo "<pre>";print_r($data['two_mon']);echo "</pre>";
                
            }
            
        }
        $this->load->view('finance/accounting/creditor_aging_view',$data);
    }
    
    function bank_recon ($offset = 0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Bank Reconciliation';
        $data['page_header'] = '<i class="fa fa-file"></i> Bank Reconciliation';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        $data['banks'] = $this->bms_fin_masters_model->getBanksForReceipt ($data ['property_id']);
        //echo "<pre>";print_r($data['banks']); echo "</pre>";
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;
        
        if (isset($_GET['property_id']) && $_GET['property_id'] != '' && isset($_GET['bank_id']) && $_GET['bank_id'] != '' && isset($_GET['from']) && $_GET['from'] != '') {
            //$unit_id = $this->input->post('unit_id');
            $from = $_GET['from'];
            //$to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            //$to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            $data['bank_recon'] = $this->bms_fin_accounting_model->getBankRecon ($_GET['property_id'], $from,$_GET['bank_id']);
            //echo "<pre>";print_r($bank_recon);echo "</pre>";
        }   
        
        $this->load->view('finance/accounting/bank_recon_view',$data);
    }
    
    function get_bank_recon () {
        header('Content-type: application/json');        
        
        $bank_recon = array('numFound'=>0,'records'=>array());
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '' && isset($_POST['bank_id']) && $_POST['bank_id'] != '') {
            //$unit_id = $this->input->post('unit_id');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $from = !empty($from) ? date('Y-m-d',strtotime($from)) : '';
            $to = !empty($to) ? date('Y-m-d',strtotime($to)) : ''; 
            $bank_recon = $this->bms_fin_accounting_model->getBankRecon ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'),$this->input->post('status'), $from,$to,$this->input->post('bank_id'));
        }   
        echo json_encode($bank_recon);
    }
    
    function set_bank_recon () {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $val = $this->input->post('val');
        $recon_date = $this->input->post('recon_date');
        if(!empty($recon_date))
            $recon_date = date('Y-m-d',strtotime($recon_date));
        if($id != '' && $type != '' && $val != '') {
            echo $this->bms_fin_accounting_model->setBankRecon ($id,$type,$val,$recon_date);
        }        
    }
    
    function income_expenses () {
        $data['browser_title'] = 'Property Butler | Income & Expenses';
        $data['page_header'] = '<i class="fa fa-file"></i> Income &amp; Expenses';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        if(!empty($_GET['from_date']) && !empty($_GET['to_date']) && strtotime(trim($_GET['from_date'])) < strtotime(trim($_GET['to_date']))) {
            $from = date('Y-m-01',strtotime(trim($_GET['from_date'])));
            $to = date('Y-m-t',strtotime(trim($_GET['to_date'])));
            
            $_GET['from_date'] = date('d-m-Y',strtotime(trim($from)));
            $_GET['to_date'] = date('d-m-Y',strtotime(trim($to)));
            
            $income_items = $this->bms_fin_accounting_model->get_income_item ($data['property_id'],$from,$to);
            $expense_items = $this->bms_fin_accounting_model->get_expense_item ($data['property_id'],$from,$to);
            
            // Month calculation
            $date1 = strtotime($from);
            $date2 = strtotime($to);
            $months = 0;        
            $sheet_head = array('','',date('M y',$date1));   
             
            while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2) {
                $months++;
                array_push($sheet_head,date('M y',$date1));
            }
            array_push($sheet_head,'YTD');
            //echo "<pre>";print_r($sheet_head);echo "</pre>";
            
            // Helper to get excel column name from Number 
            $this->load->helper('common_functions');           
            $excel_to_col = getExcelColNameFromNumber(count($sheet_head) -1);            
            
            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                }
            }
            
            
            require_once APPPATH.'/third_party/PHPExcel.php';
            require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$excel_to_col.'1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$excel_to_col.'2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$excel_to_col.'3');
            
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->mergeCells('E4:G4');           
            
            
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Income & Expenditure Statement' );
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period: '.$_GET['from_date'] .' - '.$_GET['to_date']);
            
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');
            $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Extracted: '.date('d-m-Y h:i a'));
            
            $row = 6;
            
            $styleArray = array(
                            	'borders' => array(
                            		'outline' => array(
                            			'style' => PHPExcel_Style_Border::BORDER_THICK,
                            			'color' => array('argb' => '00000000'),
                            		),
                                    'fill' => array(
                                		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                		'rotation' => 90,
                                		'startcolor' => array(
                                			'argb' => 'FFA0A0A0',
                                		),
                                		'endcolor' => array(
                                			'argb' => 'E1E1E1E1',
                                		),
                                	),

                            	),
                            );
                            
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.$excel_to_col.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                           
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.$excel_to_col.$row)->applyFromArray($styleArray);                
            
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Income' );  
            $incom_hori_total = array ();
            foreach ($income_items as $key=>$val) {
                //echo "<pre>";print_r($val);echo "</pre>";
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name'] );
                $row_total = 0;
                for ($i=0;$i<=$months;$i++) {
                    if($key == 0) $incom_hori_total[$i] = 0;                    
                    $amount = $this->bms_fin_accounting_model->get_income_item_amt ($data['property_id'],date ('Y-m-d', strtotime('+'.$i.' MONTH', strtotime($from))),$val['item_cat_id']);
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $amount['amount'] );
                    $row_total += $amount['amount'];
                    $incom_hori_total[$i] += $amount['amount'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total ); 
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            } 
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Income');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $row_total = 0;
            for ($i=0;$i<=$months;$i++) {
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $incom_hori_total[$i] );
                $row_total += $incom_hori_total[$i];
            } 
            $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total );
            $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
                       
            $row +=2;                
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Expenses' );
            
            // Expenses
            $expen_hori_total = array ();
            foreach ($expense_items as $key=>$val) {
                //echo "<pre>";print_r($val);echo "</pre>";
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name'] );
                $row_total = 0;
                for ($i=0;$i<=$months;$i++) {
                    if($key == 0) $expen_hori_total[$i] = 0;                    
                    $amount = $this->bms_fin_accounting_model->get_expense_item_amt ($data['property_id'],date ('Y-m-d', strtotime('+'.$i.' MONTH', strtotime($from))),$val['item_cat_id']);
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $amount['amount'] );
                    $row_total += $amount['amount'];
                    $expen_hori_total[$i] += $amount['amount'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total ); 
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            } 
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Expenses');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $row_total = 0;
            for ($i=0;$i<=$months;$i++) {
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $expen_hori_total[$i] );
                $row_total += $expen_hori_total[$i];
            } 
            $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total );
            $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                                 
            $row +=2;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Surplus/(Deficit)');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $row +=2;  
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Retained Surplus/(Deficit) BF');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $row +=2;  
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Retained Surplus/(Deficit) CF');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $styleRed = array( 'font'  => array('bold'  => true,'color' => array('rgb' => 'FF0000')));
            $inc_exp = 0;                
            for ($i=0;$i<=$months;$i++) {
                $row -= 4;  
                
                if($incom_hori_total[$i] >= $expen_hori_total[$i]) {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, ($incom_hori_total[$i] - $expen_hori_total[$i]));                    
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($incom_hori_total[$i] - $expen_hori_total[$i]).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                }
                
                $row +=2; 
                if($i > 0) {
                    if(($inc_exp - 0) >= 0.00001) {
                        $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $inc_exp);                    
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($inc_exp).')');
                        $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                    }
                }
                
                $row +=2;   
                $inc_exp = ($incom_hori_total[$i] - $expen_hori_total[$i]) + $inc_exp;
                if(($inc_exp - 0) >= 0.00001) {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $inc_exp);                    
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($inc_exp).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                }             
            }                 
                            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Income & Expenses');            
                        
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="I&E_'.$property_name.$_GET['from_date'] .' - '.$_GET['to_date'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');   
        }    
        
        $this->load->view('finance/accounting/income_expenses_view',$data);
    }
    
    function cash_flow () {
        $data['browser_title'] = 'Property Butler | Cash Flow';
        $data['page_header'] = '<i class="fa fa-file"></i> Cash Flow';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        if(!empty($_GET['from_date']) && !empty($_GET['to_date']) && strtotime(trim($_GET['from_date'])) < strtotime(trim($_GET['to_date']))) {
            $from = date('Y-m-01',strtotime(trim($_GET['from_date'])));
            $to = date('Y-m-t',strtotime(trim($_GET['to_date'])));
            
            $_GET['from_date'] = date('d-m-Y',strtotime(trim($from)));
            $_GET['to_date'] = date('d-m-Y',strtotime(trim($to)));
            
            $income_items = $this->bms_fin_accounting_model->get_cf_income_item ($data['property_id'],$from,$to);
            $expense_items = $this->bms_fin_accounting_model->get_cf_expense_item ($data['property_id'],$from,$to);
            //echo "<pre>";print_r($income_items);echo "</pre>";
            //echo "<pre>";print_r($expense_items);echo "</pre>"; exit;
            // Month calculation
            $date1 = strtotime($from);
            $date2 = strtotime($to);
            $months = 0;        
            $sheet_head = array('','',date('M y',$date1));   
             
            while (($date1 = strtotime('+1 MONTH', $date1)) <= $date2) {
                $months++;
                array_push($sheet_head,date('M y',$date1));
            }
            array_push($sheet_head,'YTD');
            //echo "<pre>";print_r($sheet_head);echo "</pre>";
            
            // Helper to get excel column name from Number 
            $this->load->helper('common_functions');           
            $excel_to_col = getExcelColNameFromNumber(count($sheet_head) -1);

            
            
            
            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                }
            }
            
            
            require_once APPPATH.'/third_party/PHPExcel.php';
            require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$excel_to_col.'1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$excel_to_col.'2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$excel_to_col.'3');
            
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->mergeCells('E4:G4');
            
            
            
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Cash Flow' );
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Period: '.$_GET['from_date'] .' - '.$_GET['to_date']);
            
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');
            $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Extracted: '.date('d-m-Y h:i a'));
            
            $row = 6;
            
            $styleArray = array(
                            	'borders' => array(
                            		'outline' => array(
                            			'style' => PHPExcel_Style_Border::BORDER_THICK,
                            			'color' => array('argb' => '00000000'),
                            		),
                                    'fill' => array(
                                		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                		'rotation' => 90,
                                		'startcolor' => array(
                                			'argb' => 'FFA0A0A0',
                                		),
                                		'endcolor' => array(
                                			'argb' => 'E1E1E1E1',
                                		),
                                	),

                            	),
                            );
                            
            
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.$excel_to_col.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                           
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.$excel_to_col.$row)->applyFromArray($styleArray);                
            
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Receipt' );  
            $incom_hori_total = array ();
            foreach ($income_items as $key=>$val) {
                //echo "<pre>";print_r($val);echo "</pre>";
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name'] );
                $row_total = 0;
                for ($i=0;$i<=$months;$i++) {
                    if($key == 0) $incom_hori_total[$i] = 0;                    
                    $amount = $this->bms_fin_accounting_model->get_cf_income_item_amt ($data['property_id'],date ('Y-m-d', strtotime('+'.$i.' MONTH', strtotime($from))),$val['item_cat_id']);
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $amount['amount'] );
                    $row_total += $amount['amount'];
                    $incom_hori_total[$i] += $amount['amount'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total ); 
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            } 
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Receipt Amount');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $row_total = 0;
            for ($i=0;$i<=$months;$i++) {
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $incom_hori_total[$i] );
                $row_total += $incom_hori_total[$i];
            } 
            $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total );
            $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
                       
            $row +=2;                
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Payment' );
            
            // Expenses
            $expen_hori_total = array ();
            foreach ($expense_items as $key=>$val) {
                //echo "<pre>";print_r($val);echo "</pre>";
                $row++;
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name'] );
                $row_total = 0;
                for ($i=0;$i<=$months;$i++) {
                    if($key == 0) $expen_hori_total[$i] = 0;                    
                    $amount = $this->bms_fin_accounting_model->get_cf_expense_item_amt ($data['property_id'],date ('Y-m-d', strtotime('+'.$i.' MONTH', strtotime($from))),$val['item_cat_id']);
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $amount['amount'] );
                    $row_total += $amount['amount'];
                    $expen_hori_total[$i] += $amount['amount'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total ); 
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            } 
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Payment Amount');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $row_total = 0;
            for ($i=0;$i<=$months;$i++) {
                $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $expen_hori_total[$i] );
                $row_total += $expen_hori_total[$i];
            } 
            $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $row_total );
            $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':'.getExcelColNameFromNumber($i+2).$row)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                                 
            $row +=2;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Net Balance');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $row +=2;  
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Balance B/F');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $row +=2;  
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Balance C/F');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $styleRed = array( 'font'  => array('bold'  => true,'color' => array('rgb' => 'FF0000')));
            $inc_exp = 0;                
            for ($i=0;$i<=$months;$i++) {
                $row -= 4;  
                
                if($incom_hori_total[$i] >= $expen_hori_total[$i]) {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, ($incom_hori_total[$i] - $expen_hori_total[$i]));                    
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($incom_hori_total[$i] - $expen_hori_total[$i]).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                }
                
                $row +=2; 
                if($i > 0) {
                    if(($inc_exp - 0) >= 0.00001) {
                        $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $inc_exp);                    
                    } else {
                        $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($inc_exp).')');
                        $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                    }
                }
                
                $row +=2;   
                $inc_exp = ($incom_hori_total[$i] - $expen_hori_total[$i]) + $inc_exp;
                if(($inc_exp - 0) >= 0.00001) {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, $inc_exp);                    
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue(getExcelColNameFromNumber($i+2).$row, '('.abs($inc_exp).')');
                    $objPHPExcel->getActiveSheet()->getStyle(getExcelColNameFromNumber($i+2).$row)->applyFromArray($styleRed);
                }             
            }                 
                            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);

            // Rename sheet
            $objPHPExcel->getActiveSheet()->setTitle('Cash Flow');           
                        
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="CASH_FLOW_'.$property_name.$_GET['from_date'] .' - '.$_GET['to_date'].'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }   
        
        $this->load->view('finance/accounting/cash_flow_view',$data);
    }
    
    function trail_balance () {
        $data['browser_title'] = 'Property Butler | Trail Balance';
        $data['page_header'] = '<i class="fa fa-file"></i> Trail Balance';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        if(!empty($_GET['from'])) {
            $as_of_date = date('Y-m-d',strtotime(trim($_GET['from'])));
            //$to = date('Y-m-d',strtotime(trim($_GET['to']))); 
            $data['result'] = $this->bms_fin_accounting_model->get_coa_for_tb ($data['property_id']);
            //echo "<pre>";print_r($data['result']);echo "</pre>";
            if(!empty($data['result'])) {
                //echo "<pre>";print_r($data['result']);echo "</pre>";
                $debit_total = $credit_total = 0;
                foreach ($data['result'] as $key=>$val) {
                    //$this->bms_fin_accounting_model->getTrailBalance ($data['property_id'],$val['coa_id'],$as_of_date);
                    $open_debit = $open_credit = 0; 
                    if((!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) || (!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001)) {
                        //echo "<br />".$val['coa_name'];
                        if(!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) {
                              //$balance +=  $val['opening_debit']; 
                              $debit_total += $open_debit = $val['opening_debit'];
                        }                   
                        
                        if(!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001) {
                            //$balance -=  $val['opening_credit']; 
                            $credit_total += $open_credit = $val['opening_credit'];
                        }
                    }
                    
                    // payment & Receipt Enabled 
                    /*if(isset($val['payment_enabled']) && $val['payment_enabled'] == 1 && isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_n_receipt_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_n_receipt_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        if(!empty($bf_debit_arr['amount']) && !empty($bf_credit_arr['amount'])){
                            if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                                $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                                $bf_credit_arr['amount'] = 0; 
                                $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                              } else {
                                $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                                $bf_debit_arr['amount'] = 0; 
                                $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];                                                              
                              }                               
                        } else if(!empty($bf_debit_arr['amount'])) {
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];
                        } else if(!empty($bf_credit_arr['amount'])) {
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }
                        //echo "<pre>";print_r($val);echo "</pre>";
                    } 
                    else*/ 
                    // Bill & Receipt Enabled 
                    if(isset($val['bill_enabled']) && $val['bill_enabled'] == 1 && isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_bill_receipt_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_bill_receipt_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_debit_arr['amount'] += $open_debit;
                        $bf_credit_arr['amount'] += $open_credit;
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }                               
                                                
                    
                    } else if(isset($val['receipt_enabled']) && $val['receipt_enabled'] == 1) {
                        
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_receipt_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_receipt_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_debit_arr['amount'] += $open_debit;
                        $bf_credit_arr['amount'] += $open_credit;
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }                               
                        
                    
                    } else if(isset($val['deposit_enabled']) && $val['deposit_enabled'] == 1) { // Deposit Enabled
                        
                        //echo "<pre>";print_r($val);echo "</pre>";
                        /*$bf_debit_arr = $this->bms_fin_accounting_model->get_tb_receipt_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_receipt_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_debit_arr['amount'] += $open_debit;
                        $bf_credit_arr['amount'] += $open_credit;
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                          } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                          }                               
                        */
                    } else if(isset($val['payment_source']) && $val['payment_source'] == 1) { // Banks
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_sour_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_sour_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_debit_arr['amount'] += $open_debit;
                        $bf_credit_arr['amount'] += $open_credit;
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }                               
                        
                        
                    } else if(isset($val['payment_enabled']) && $val['payment_enabled'] == 1) {
                        //echo "<pre>";print_r($val);echo "</pre>";
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                        $bf_debit_arr['amount'] += $open_debit;
                        $bf_credit_arr['amount'] += $open_credit;
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }                               
                        
                        //echo "<pre>";print_r($data['result']);echo "</pre>";
                    } else if($val['coa_code'] == '3000/000') {  // DEBTOR CONTROL(Resident)
                        
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_debtors_bf_debit ($data['property_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_debtors_bf_credit ($data['property_id'],$as_of_date);
                        //echo "<pre>";print_r($bf_credit_arr); echo "</pre>";
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }
                        
                    } // TRADE CREDITORS (Service Provider / Vendor)
                    else if($val['coa_code'] == '4100/000') {
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_creditors_bf_debit ($data['property_id'],$as_of_date);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_creditors_bf_credit ($data['property_id'],$as_of_date);
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }
                    } else {
                        $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_non_enabled_bf_debit ($data['property_id'],$as_of_date,$val['coa_id']);
                        $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_non_enabled_bf_credit ($data['property_id'],$as_of_date,$val['coa_id']);
                        if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                            $bf_debit_arr['amount'] = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];
                            $bf_credit_arr['amount'] = 0; 
                            $debit_total += $data['result'][$key]['debit_val'] = $bf_debit_arr['amount'];                                                                
                        } else {
                            $bf_credit_arr['amount'] = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];
                            $bf_debit_arr['amount'] = 0; 
                            $credit_total += $data['result'][$key]['credit_val'] = $bf_credit_arr['amount'];
                        }
                    }
                }
                $data['debit_total'] = $debit_total;
                $data['credit_total'] = $credit_total;
            }            
        }        
        
        $this->load->view('finance/accounting/trail_balance_view',$data);
    }
    
    function balance_sheet () {
        $data['browser_title'] = 'Property Butler | Balance Sheet';
        $data['page_header'] = '<i class="fa fa-file"></i> Balance Sheet';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        if(!empty($_GET['from'])) {
            
            $as_of_date = date('Y-m-d',strtotime(trim($_GET['from']))); 
            $fixed_assets = $this->bms_fin_accounting_model->get_fixed_assets ($data['property_id']);
            $current_assets = $this->bms_fin_accounting_model->get_current_assets ($data['property_id']);
            $current_liabilities = $this->bms_fin_accounting_model->get_current_liabilities ($data['property_id']);
            
            
            //$fixed_assets_id = array_column($fixed_assets,'coa_id');
            $property_name = '';
            foreach ($data['properties'] as $key=>$val) {
                if($val['property_id'] == $_GET['property_id'] ) {
                    $property_name = !empty($val['jmb_mc_name']) ? $val['jmb_mc_name']: $val['property_name'];
                }
            }
            
            require_once APPPATH.'/third_party/PHPExcel.php';
            require_once APPPATH.'/third_party/PHPExcel/IOFactory.php';
            
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();
            
            // Create a first sheet, representing sales data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
            
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
            $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
            
            
            
            $objPHPExcel->getActiveSheet()->setCellValue('A1', $property_name );
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Balance Sheet' );
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'As at : '.trim($_GET['from']));
            
            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'All currencies are in RM');
            $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Extracted: '.date('d-m-Y h:i a'));
            
            $row = 6;
            
            $styleArray = array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                        'color' => array('argb' => '00000000'),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => 'FFA0A0A0',
                        ),
                        'endcolor' => array(
                            'argb' => 'E1E1E1E1',
                        ),
                    ),

                ),
            );
                            
            
            //$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':D'.$row)->getFont()->setBold(true)->setSize(12);
            //$objPHPExcel->getActiveSheet()->fromArray($sheet_head, NULL, 'A'.$row);
                           
            //$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':D'.$row)->applyFromArray($styleArray);                
            
            //$row++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Assets' ); 
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Fixed Assets' ); 
            $fixed_assets_tot = $current_assets_tot = $current_liabilities_tot = 0;
            foreach ($fixed_assets as $key=>$val) {
                    //$this->bms_fin_accounting_model->getTrailBalance ($data['property_id'],$val['coa_id'],$as_of_date);
                    $open_debit = $open_credit = 0; 
                    if((!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) || (!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001)) {
                        //echo "<br />".$val['coa_name'];
                        if(!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) {
                              //$balance +=  $val['opening_debit']; 
                              $debit_total += $open_debit = $val['opening_debit'];
                        }                   
                        
                        if(!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001) {
                            //$balance -=  $val['opening_credit']; 
                            $credit_total += $open_credit = $val['opening_credit'];
                        }
                    }
                    $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_debit_arr['amount'] += $open_debit;
                    $bf_credit_arr['amount'] += $open_credit;
                    //$bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    //$bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $print_val = 0;
                    if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                        $print_val = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];                                                                                           
                    } else {
                        $print_val = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];                            
                    }
                    $row++;
                          
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name']);  
                    $fixed_assets_tot += $print_val;
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $print_val);                            
                    
            }
            
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Fixed Assets');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':C'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $fixed_assets_tot);
            
            $row += 2;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Current Assets' ); 
            
            foreach ($current_assets as $key=>$val) {
                    //$this->bms_fin_accounting_model->getTrailBalance ($data['property_id'],$val['coa_id'],$as_of_date);
                    $open_debit = $open_credit = 0; 
                    if((!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) || (!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001)) {
                        //echo "<br />".$val['coa_name'];
                        if(!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) {
                              //$balance +=  $val['opening_debit']; 
                              $debit_total += $open_debit = $val['opening_debit'];
                        }                   
                        
                        if(!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001) {
                            //$balance -=  $val['opening_credit']; 
                            $credit_total += $open_credit = $val['opening_credit'];
                        }
                    }
                    
                    $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_debit_arr['amount'] += $open_debit;
                    $bf_credit_arr['amount'] += $open_credit;
                    //$bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    //$bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $print_val = 0;
                    if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                        $print_val = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];                                                                                           
                    } else {
                        $print_val = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];                            
                    }
                    
                    $row++;
                            
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name']); 
                    $current_assets_tot += $print_val;
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $print_val);
                    
            }
            
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Current Assets');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':C'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $current_assets_tot);
            
            $row += 2;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(14);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Liabilities' ); 
            $row++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Current Liabilities' );
            
            foreach ($current_liabilities as $key=>$val) {
                    //$this->bms_fin_accounting_model->getTrailBalance ($data['property_id'],$val['coa_id'],$as_of_date);
                    $open_debit = $open_credit = 0; 
                    if((!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) || (!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001)) {
                        //echo "<br />".$val['coa_name'];
                        if(!empty($val['opening_debit']) && abs ($val['opening_debit'] - 0) > 0.00001) {
                              //$balance +=  $val['opening_debit']; 
                              $debit_total += $open_debit = $val['opening_debit'];
                        }                   
                        
                        if(!empty($val['opening_credit']) && abs ($val['opening_credit'] - 0) > 0.00001) {
                            //$balance -=  $val['opening_credit']; 
                            $credit_total += $open_credit = $val['opening_credit'];
                        }
                    }
                    
                    $bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $bf_debit_arr['amount'] += $open_debit;
                    $bf_credit_arr['amount'] += $open_credit;
                    //$bf_debit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_debit ($data['property_id'],$val['coa_id'],$as_of_date);
                    //$bf_credit_arr = $this->bms_fin_accounting_model->get_tb_pay_ena_bf_credit ($data['property_id'],$val['coa_id'],$as_of_date);
                    $print_val = 0;
                    if($bf_debit_arr['amount'] > $bf_credit_arr['amount']) {
                        $print_val = $bf_debit_arr['amount'] - $bf_credit_arr['amount'];                                                                                           
                    } else {
                        $print_val = $bf_credit_arr['amount'] - $bf_debit_arr['amount'];                            
                    }
                    
                    $row++;
                            
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['coa_name']);                            
                    $current_liabilities_tot += $print_val;
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $print_val);
            }
            
            $row++;
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Total Current Liabilities');  
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':C'.$row)->getFont()->setBold(true)->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $current_liabilities_tot);
            
            $objPHPExcel->getActiveSheet()->getStyle('C4:C'.($row+1))->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            
            $objPHPExcel->getActiveSheet()->setTitle('Balance Sheet');           
                        
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="BALANCE_SHEET_'.$property_name.$_GET['from'] .'_'.date('Ymd').'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
                        
        }
        $this->load->view('finance/accounting/balance_sheet_view',$data);
    }   
    
    
    function payment_receipt () {
        $data['browser_title'] = 'Property Butler | Receipt &amp; Payment';
        $data['page_header'] = '<i class="fa fa-file"></i> Receipt &amp; Payment';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $this->load->view('finance/accounting/payment_receipt_view',$data);
    }
    
    function reminder_letter () {
        $data['browser_title'] = 'Property Butler | Reminder Letter';
        $data['page_header'] = '<i class="fa fa-file"></i> Reminder Letter';
        
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));
        
        $data['units'] = $this->bms_masters_model->getUnit ($data ['property_id'],0);
        
        $this->load->view('finance/accounting/reminder_letter_view',$data);
    }

    function bank_trans_old_list ($offset=0, $per_page = 25) {

        //echo "<pre>";print_r($_SESSION);echo "</pre>";
        $data['browser_title'] = 'Property Butler | Bank Transaction (Old)';
        $data['page_header'] = '<i class="fa fa-file"></i> Bank Transaction (Old)';

        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        $data['offset'] = $offset;
        $data['per_page'] = $per_page;

        $this->load->view('finance/accounting/bank_trans_old_list_view',$data);
    }

    public function get_bank_trans_old_list () {

        header('Content-type: application/json');

        $units = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100 && isset($_POST['property_id']) && $_POST['property_id'] != '') {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $units = $this->bms_fin_accounting_model->get_bank_trans_old_list ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'), $search_txt);
        }
        echo json_encode($units);
    }

    public function create_bank_trans_old ($bank_trans_old_id = '') {
        $data['browser_title'] = 'Property Butler | Create bank transaction (Old)';
        $data['page_header'] = '<i class="fa fa-file"></i> Create bank transaction (Old)';
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
        $data ['property_id'] = !empty($data['bill']['property_id']) ? $data['bill']['property_id'] : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : (isset($data['properties'][0]['property_id']) ? $data['properties'][0]['property_id'] : ''));

        if ( !empty($bank_trans_old_id) ) {
            $data['bank_trans_old_id'] = $bank_trans_old_id;
            $data['old_bank'] = $this->bms_fin_accounting_model->get_bank_trans_old ($bank_trans_old_id);
        }

        $this->load->view('finance/accounting/create_bank_trans_old_view',$data);
    }

    public function create_bank_trans_old_submit () {
        // echo "<pre>";print_r($_POST);echo "</pre>"; exit;

        $old_bank = $this->input->post('old_bank');
        $bank_trans_old_id = $this->input->post('bank_trans_old_id');;

        $type = 'add';
        if(!empty($old_bank['trans_date'])) {
            $old_bank['trans_date'] = date('Y-m-d',strtotime($old_bank['trans_date']));
        }

        if( !empty( $bank_trans_old_id ) ) {
            $type = 'edit';
            $this->bms_fin_accounting_model->update_bank_trans_old ( $old_bank, $bank_trans_old_id );
        } else {
            if(isset($old_bank['bank_trans_old_id'])) unset($old_bank['bank_trans_old_id']);
            $trans_date = !empty($old_bank['trans_date']) ? $old_bank['trans_date'] : date('d-m-Y');
            $old_bank['trans_time'] = date('H:i:s');
            $this->bms_fin_accounting_model->insert_bank_trans_old ( $old_bank );
        }

        $_SESSION['flash_msg'] = 'Bank transaction ( Old ) '. ($type == 'edit' ? 'Edited' : 'Added') .' successfully!';
        redirect ('index.php/bms_fin_accounting/bank_trans_old_list');
    }

    public function delete_bank_trans_old () {
        $bank_trans_old_id = $this->input->post('bank_trans_old_id');
        echo $this->bms_fin_accounting_model->delete_bank_trans_old ($bank_trans_old_id);
    }
}