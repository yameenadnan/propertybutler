<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_direct_pay extends CI_Controller { 
    
    function __construct () { 
        parent::__construct ();
        if(in_array($this->uri->segment(2), array('payments')) && (!isset($_SESSION['bms']['is_logged_in']) || $_SESSION['bms']['is_logged_in'] == false)) {
	       redirect('index.php/bms_index/login?return_url='.($_SERVER['SERVER_NAME'] == 'www.propertybutler.my' ? 'https://www.propertybutler.my' : 'http://127.0.0.1').$_SERVER['REQUEST_URI']);	       
	    }
        
        // load necessary models
        $this->load->model('bms_masters_model');
        $this->load->model('bms_direct_pay_model');    
    }
    
    function index () {
        header('Content-Type: text/html; charset=utf-8');
        if (!empty($_GET['tid']) && !empty($_GET['pid'])) {


            //parse_str($_SERVER['QUERY_STRING'], $output);
            //echo "<pre>";print_r($output);exit;
            $tid = str_replace (array('%2F','%3D'),array('/','='),urlencode($_GET['tid']));
            $pid = str_replace (array('%2F','%3D'),array('/','='),urlencode($_GET['pid']));
             
            $data['tid'] = trim($this->getDecryptStr ($tid));
            $pid = (int) $this->getDecryptStr ($pid);
            //echo "<br />".$tid. " => " . $pid; 
            
            if(in_array($data['tid'],array('resi','ovp','clamp')) && is_numeric($pid) && $pid > 0 ) {



                $data['prop_details'] = $this->bms_direct_pay_model->getPayPropertyDetails($pid);

                $data['units'] = array (); 
                if($data['tid'] == 'resi') {
                    $data['units'] = $this->bms_direct_pay_model->getPayUnit($pid);
                }

                $data['bank'] = $this->config->item('pg_bank_code');
                
            }
                
           $this->load->view('direct_pay/landing_view',$data); 
        } else {
            echo "<h3 style='color:red;'>Invalid URL!</h3>";    
        }       
                
    }
    
    function process_pay () { 
        if(!empty($_POST)) {
            //echo "<pre>";print_r($_POST);echo "</pre>";
            $data = array();
            /*foreach ($_POST as $key => $val) {
                $data[$key] = $val;
            }*/
            
            $data['prop_details'] = $this->bms_direct_pay_model->getPayPropertyDetails($_POST['property_id']);
            
            $time =microtime(true);
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $date_time =$date->format("YmdHisu");
            $data['ref_no'] = $_POST['property_id'] . '-' . $date_time;
            $process_fee = 0;
            if($_POST['Payment_ID'] == 2) {
                $process_fee = ($_POST['amount'] * $_POST['payment_cc_card']) /100; 
            } else if ($_POST['Payment_ID'] == 3) {
                $process_fee = $_POST['payment_fpx'];
            }
            $data['process_fee'] = $process_fee;
            $signature_original = $_POST['payment_merchant_key'].$_POST['payment_merchant_id'].$data['ref_no'].($_POST['amount']+$process_fee).'MYR';
            $data['signature'] = hash('sha512', $signature_original);
            
            
            $data['bank'] = $this->config->item('pg_bank_code');
            
            $this->load->view('direct_pay/process_pay_view',$data);
        } 
        
    }
    
    function paymentCompleted ($property_id,$unit_id = '') {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        
        $merchant_id = $_POST['Revpay_Merchant_ID'];
        $reference_number = $_POST['Reference_Number'];
        //$actualAmount = $_POST['actualAmount'];
        $amount = $_POST['Amount'];
        $key_index = $_POST['Key_Index'];
        $currency = $_POST['Currency'];
        $signature = $_POST['Signature'];
        $transaction_description = $_POST['Transaction_Description'];
        $payment_id = $_POST['Payment_ID'];
        //$checkedBill = $_POST['checkedBill'];
        
        $response_code = $_POST['Response_Code'];
        $transaction_id = $_POST['Transaction_ID'];
        $error_description = $_POST['Error_Description'];
        $settlement_amount = $_POST['Settlement_Amount'];
        $settlement_currency = $_POST['Settlement_Currency'];
        $settlement_fx_rate = $_POST['Settlement_FX_Rate'];
        $bankCode = $_POST['Bank_Code'];
        $request_datetime = $_POST['Request_Datetime'];
        $response_datetime = $_POST['Response_Datetime'];
        
        // Validation the response parameter from revPAY
        if (
            !empty($merchant_id) &&
            !empty($payment_id) &&
            !empty($transaction_id) &&
            !empty($reference_number) &&
            !empty($amount) &&
            !empty($currency) &&
            !empty($transaction_description) &&
            !empty($response_code) &&
            !empty($key_index) &&
            !empty($signature) &&
            !empty($request_datetime) &&
            !empty($response_datetime)) {
        
        $data = array(
                    "property_id" => $property_id,
                    "Revpay_Merchant_ID" => $merchant_id,
                    "Payment_ID" => $payment_id,
                    "Bank_Code" => ($bankCode != '' ? $bankCode : NULL),
                    "Transaction_ID" => $transaction_id,                    
                    "Reference_Number" => $reference_number,
                    "Amount"=>$amount,
                    "Currency"=>$currency,
                    "Response_Code"=>$response_code,
                    "Transaction_Description" => $transaction_description,
                    "Settlement_Amount" => $settlement_amount,
                    "Settlement_Currency" => $settlement_currency,
                    "Settlement_Amount" => $settlement_fx_rate,
                    "Error_Description" => $error_description,
                    "Key_Index" => $key_index,
                    "Signature" => $signature,
                    "Request_Datetime" => $request_datetime,
                    "Response_Datetime" => $response_datetime,
                  );
                  
                  $data['pymt_for'] = isset($_GET['tid']) ? ($_GET['tid'] == 'clamp' ? 1 :( $_GET['tid'] == 'ovp' ? 2 : NULL)) : NULL;
                  $data['unit_id'] = $unit_id != '' ? $unit_id : NULL;
                  $data['email_addr'] = isset($_GET['email']) && $_GET['email'] != '' ? $_GET['email'] : NULL;
                  
            $this->bms_direct_pay_model->direct_pay_web_insert($data);
            
            
            // mail functionality
            if($response_code == '00' && $data['email_addr'] != NULL) {
                
                $data['prop_details'] = $this->bms_direct_pay_model->getPayPropertyDetails($property_id);
                
                $to = $data['email_addr'];                
                $this->load->library('email');
    
                $subject = 'Payment Transaction Status';
                
                $message = '<h3> Dear Cutomer, </h3>';                        
                $message .= '<p>';
                $message .= 'Your payment of <b>MYR '.number_format($amount,2).' has been authorized and confirmed,</b>';
                $message .= 'Thank you for making payment using Property Butler payment services.';
                $message .= '</p>';
                
                $message .= '<table align="center" cellspacing="0" cellpadding="0" width="600px" border="0">';
                $message .= '<tr><td colspan="3"><p style="font-size:16pt;margin-bottom:1em"><strong><span>Payment Receipt</span></strong></p></td>';
                $message .= '<tr><td width="150"><b>Transaction ID:</b></td><td width="400">'.$transaction_id .'</td></tr>';
                $message .= '<tr><td width="150"><b>Reference Number:</b> </td><td width="400">'.$reference_number .'</td></tr>';
                $message .= '<tr><td width="150"><b>Payment For:</b> </td><td width="400">'.(isset($_GET['tid']) ? ($_GET['tid'] == 'Clamping charges' ? 1 :( $_GET['tid'] == 'Over night parking charges' ? 2 : '-')) : '-') .'</td></tr>';
    
                $message .= '<tr><td width="150"><b>Transaction Date:</b> </td><td width="400">'.(!empty($response_datetime) ? date('d-m-Y H:i a', strtotime($response_datetime)) : ' - ').'</td></tr>';
                $message .= '<tr><td width="150"><b>Transaction Description:</b> </td><td width="400">'.$transaction_description.'</td></tr>';
                $message .= '<tr><td width="150"><b>Amount:</b> </td><td width="400">'.$amount .'</td></tr>';
                $message .= '</table>';
                
                $message .= '<p>';
                $message .= 'Please do not reply to this email. This mailbox is not monitored and you will not receive a response.';
                $message .= '</p>';
                $message .= '<h2>Need Assistance?</h2>';               
               
                $message .= '<table align="center" cellspacing="0" cellpadding="0" width="600px" border="0">';
                $message .= '<tr><td colspan="3"><p style="font-size:16pt;margin-bottom:1em"><strong><span>Please contact '.$data['prop_details']['property_name'].' at:</span></strong></p></td>';
                $message .= '<tr><td width="150"><b>Tel No:</b></td><td width="400">'.$data['prop_details']['phone_no'] .($data['prop_details']['phone_no2'] != '' ? ', '.$data['prop_details']['phone_no2'] : '').'</td></tr>';
                $message .= '<tr><td width="150"><b>Email:</b> </td><td width="400">'.$data['prop_details']['email_addr'] .'</td></tr>';
                $message .= '</table>';
                
                $message .= '<table align="center" border="0" cellspacing="0" cellpadding="0" style="width:600px"><tbody><tr><td><p style="text-align:justify">******************************<wbr>******************************<wbr>***************</p></td></tr><tr><td><p style="text-align:justify">DISCLAIMER: This e-mail and its attachments contains confidential information. It is intended solely for the use of the intended recipient to which it has been addressed. If you are not the intended recipient, you are hereby&nbsp;<span>notified</span>&nbsp;that disclosing, copying, distributing or taking any action in reliance on the contents of this information is strictly prohibited. Please delete the email from your system. The recipient should check this email and any attachments for the presence of viruses. Please note that neither the payment gateway nor the sender accepts any responsibility for any damage caused by any virus that may be contained in this e-mail or its attachments.</p></td></tr><tr><td><p style="text-align:justify">******************************<wbr>******************************<wbr>***************</p></td></tr></tbody></table>';
                
                // Get full html:
                $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                    <title>' . html_escape($subject) . '</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, Verdana, Helvetica, sans-serif;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                ' . $message . '
                </body>
                </html>';
                // Also, for getting full html you may use the following internal method:
                //$body = $this->email->full_html($subject, $message);
                
                $result = $this->email
                    ->from('noreply@propertybutler.my','Propertybutler')
                    ->reply_to('noreply','noreply')    // Optional, an account where a human being reads.
                    ->to($to,'');
                if(!empty($data['prop_details']['email_addr']))
                    $this->email->cc($data['prop_details']['email_addr']);
                
                $this->email
                    ->bcc('naguwin@gmail.com','Nagarajan')                    
                    //->bcc('email@transpacc.com.my','Transpacc Emails')
                    ->subject($subject)
                    ->message($body)
                    ->send();
            }
            echo "
                <form action='".base_url('index.php/bms_direct_pay/payment_status')."' method=\"post\" id=\"bms_frm\">
                <input name=\"response_code\" type=\"hidden\" value=\"$response_code\">
                <input name=\"response_datetime\" type=\"hidden\" value=\"$response_datetime\">
                <input name=\"pymt_for\" type=\"hidden\" value=\"".$data['pymt_for']."\">
                <input name=\"amount\" type=\"hidden\" value=\"".$amount."\">
                <input name=\"reference_number\" type=\"hidden\" value=\"$reference_number\">
                <input name=\"trans_descrip\" type=\"hidden\" value=\"$transaction_description\">
                <input name=\"transaction_id\" type=\"hidden\" value=\"$transaction_id\">
                <input type=\"submit\">
                </form>";      
            
            
            ?>

                <script type="text/javascript">
                    document.getElementById('bms_frm').submit(); // SUBMIT FORM
                </script>
                
            <?php 
                            
            
        } else {
              echo
                '<div style="padding-top:100px">
                  <p style="font-size:40px;text-align:center">
                    <b>Your payment was not successful.</b><br /><br />
                    <p style="font-size:25px;text-align:center">
                      Error ('.$response_code.') : '.$error_description.'<br /><br /><br />
                      Redirect back in <span id="timer" style="font-size:25px;text-align:center">5</span> seconds ...
                    </p>
                  </p>
                </div>';
            
                ?>
            
                <script>
                  var x = 4
                  var send = false
                  setInterval(function(){
                    if(x == 0 && !send){
                      document.getElementById("timer").innerHTML = x;
                      window.ReactNativeWebView.postMessage(false)
                      send = true
                      clearInterval()
                    }else{
                      document.getElementById("timer").innerHTML = x;
                      x --;
                    }
                  }, 1000);
                </script>
            
                <?php
        }


    }
    
    function payment_status () {
        //echo "<pre>";print_r($_POST);echo "</pre>";
        $this->load->view('direct_pay/payment_status_view');
    }
    
    function getDecryptStr ($param){
        $cry_key = 'itechmanagementsolutions2017augu';
        $iv_size = 16;
        
        $decode_str = base64_decode($param);
        $iv_str = substr($decode_str, 0, $iv_size);
        $cry_str = substr($decode_str, $iv_size); 
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cry_key,$cry_str, MCRYPT_MODE_CBC, $iv_str);
    }

    function checkEncrypt ($text = '') {
        $cry_key = 'itechmanagementsolutions2017augu';
        $text = $text != '' ? $text : 'nresi';
        $iv_size = 16;//mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        $ciphertextstr1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $cry_key,$text, MCRYPT_MODE_CBC, $iv);
        $ciphertextstr1 = $iv . $ciphertextstr1;
        echo "<br /><br />Encrypt Text=> ".base64_encode($ciphertextstr1);
    }
    
    function checkDecrypt () {
        $cry_key = 'itechmanagementsolutions2017augu';
        $text = $_GET['text'] != '' ? $_GET['text'] :  'sGGwEnmk50dQhMF9PysHnQb+E7mxsnvNU1srQ14tJD0=';
        $iv_size = 16;//mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        //$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        $ciphertext_str2 = base64_decode($text);
        $iv_str1 = substr($ciphertext_str2, 0, $iv_size);
        $ciphertext_str2 = substr($ciphertext_str2, $iv_size); 
        
        echo "<br /><br />Plain Text=> ".$plaintext_str2 = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $cry_key,$ciphertext_str2, MCRYPT_MODE_CBC, $iv_str1);
    }


    function payments ($offset=0, $per_page = 25) {
        $data['browser_title'] = 'Property Butler | Payments';
        $data['page_header'] = '<i class="fa fa-dollar"></i> Payments';
        $data['property_id'] = isset($_GET['property_id']) && trim($_GET['property_id']) != '' ? trim ($_GET['property_id']) : (isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] != '' ? $_SESSION['bms_default_property'] : '');
        $data['properties'] = $this->bms_masters_model->getMyProperties ();
              
        $data['offset'] = $offset;
        $data['per_page'] = $per_page;   
        
        //$data['properties_docs'] = $this->bms_property_model->getMyPropertiesDocs ($property_id,$doc_cat_id);
        //echo "<pre>";print_r($data['properties']);echo "</pre>"; exit;
        
        $this->load->view('direct_pay/payments_list_view',$data);
    }
    
    function getPaymentsList () {
        header('Content-type: application/json');        
        
        $res = array();
        if (isset($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] >= 0 && isset($_POST['rows']) && is_numeric($_POST['rows']) && $_POST['rows'] >= 0 && $_POST['rows'] <= 100) {
            $search_txt = $this->input->post('search_txt');
            $search_txt = $search_txt ? strtolower($search_txt) : $search_txt;
            $from = !empty($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : '';
            $to = !empty($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : '';
            $res = $this->bms_direct_pay_model->getPaymentsList ($_POST['offset'], $_POST['rows'], $this->input->post('property_id'),$from,$to, $search_txt);   
        }      
                
        echo json_encode($res);
    }
    
}