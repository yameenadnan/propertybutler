<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_cron_jobs extends CI_Controller {
    
    function __construct () {
        parent::__construct ();
        //header('Content-type: application/json');
        
        /*if(!isset($_GET['auth_key']) || $_GET['auth_key'] != 'gXR65_*4!sU-8paCyuvwR7Efv*DLBDV$') {
            $data['Data'] = array();
            $data['Status'] = false;
            $data['ErrorLog'] = array(array('message'=>'Authentication failed!'));
            echo json_encode(array($data));	exit;       	       
	    } */
        $this->load->model('bms_masters_model'); 
        $this->load->model('bms_cron_jobs_model'); 
        $this->load->library('agm_emails');

    }

    function sendAgmReminder () {
        // get all the agms which are about to held in comming 3 months
        $agms = $this->bms_cron_jobs_model->getAgms ();
        //echo "<pre>";print_r($agms);echo "</pre>";
        if(!empty($agms)) {
            $agm_types = $this->config->item('agm_types');
            $agm_remin = $this->config->item('agm_remin');
            foreach ($agms as $key=>$val) {
                // get all the records from bms_agm_checklist where agm_checklist_status IS NULL
                $agm_checklist = $this->bms_cron_jobs_model->getAgmChecklist ($val['agm_id']);
                $staff_emails = array ();
                $jmb_emails = array ();
                if(!empty($agm_checklist)) {
                    foreach ($agm_checklist as $key2=>$val2) {
                        // get all the previous reminder of each check_list_id
                        $agm_reminders = $this->bms_cron_jobs_model->getAgmreminders ($val2['agm_checklist_id']);
                        if(!empty($agm_reminders)) {
                            //echo "<pre>";print_r($agm_reminders);echo "</pre>";
                            foreach ($agm_reminders as $key3=>$val3) {
                                $date2=new DateTime (date('Y-m-d'));
                                if($val['agm_date'] == '0000-00-00') {
                                    $date_temp =date('d-m-Y', strtotime("-1 Day",strtotime("+12 months", strtotime($val['agm_last_date']))));
                                } else {
                                    $date_temp = $val['agm_date'];
                                }
                                $date1 = new DateTime($date_temp);
                                //$diff=date_diff($date1,$date2,TRUE);

                                $day_diff = $date2->diff($date1)->format("%r%d");
                                $days_diff = $date2->diff($date1)->format("%r%a");
                                $months_diff = $date2->diff($date1)->format("%r%m");
                                $weeks_diff = $date2->diff($date1)->format("%r%a") / 7;
                                //echo "<br />".$agm_remin[$val3['remind_before']];
                                $remind_bef = !empty($val3['remind_before']) ? $agm_remin[$val3['remind_before']] : 0;
                                if((($remind_bef == $months_diff.' Months' || $remind_bef == $months_diff.' Month') && $day_diff == 0)
                                    || (($remind_bef == $weeks_diff.' Weeks' || $remind_bef == $weeks_diff.' Week') && $day_diff % 7 == 0)
                                    || ($remind_bef == $days_diff.' Days' || $remind_bef == $weeks_diff.' Day')) {

                                    //echo "send Email for remind before ".$remind_bef;
                                    $mail_subj = $val['property_name'] . ' | Reminder Before '.$remind_bef .' For '. $agm_types[$val['agm_type']];
                                    if($val3['email_staff'] == 1) {
                                        if(empty($staff_emails)) {
                                            $staff_emails = $this->agm_emails->getPropertyStaffs($val['property_id']);
                                        }
                                        if(!empty($staff_emails)) {
                                            $mail_msg = $this->agm_emails->send_email($staff_emails,$mail_subj,$val3['email_content'],$val['email_addr']);
                                            if(!$mail_msg) {
                                                $mail_subj .= " is not send";
                                                $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                                                $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$val3['email_content'],$val['email_addr']);
                                            }
                                        }
                                    }

                                    if($val3['email_jmb'] == 1) {
                                        if(empty($jmb_emails)) {
                                            $jmb_emails = $this->agm_emails->getPropertyJmb($val['property_id']);
                                        }
                                        if(!empty($jmb_emails)) {
                                            $mail_msg = $this->agm_emails->send_email($jmb_emails,$mail_subj,$val3['email_content'],$val['email_addr']);
                                            if(!$mail_msg) {
                                                $mail_subj .= " is not send";
                                                $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                                                $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$val3['email_content'],$val['email_addr']);
                                            }
                                        }
                                    }
                                    //echo "<pre>";print_r($staff_emails);print_r($jmb_emails);echo "</pre>"; 
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    function sendSupplierContractExpirationReminder () {
        // get all the agms which are about to held in comming 3 months
        $property_list = $this->bms_cron_jobs_model->getPropertyList ();
        $service_provider_list = array();
        //echo "<pre>";print_r($agms);echo "</pre>";
        if ( !empty($property_list) ) {
            $service_provider_remin = $this->config->item('service_provider_remin');
            foreach ( $property_list as $key=>$val ) {
                // Get list of all service providers
                $service_provkder_list = $this->bms_cron_jobs_model->getServiceProviderList ( $val['property_id'] );

                if ( !empty( $service_provkder_list ) ) {
                    foreach ( $service_provkder_list as $key2 => $val2 ) {

                        $date2=date_create(date('Y-m-d'));
                        $date1=date_create($val2['contract_end_date']);

                        $diff = date_diff($date1,$date2,TRUE);

                        $day_diff = $diff->format("%d");
                        $days_diff = $diff->format("%a");
                        $months_diff = $diff->format("%m");
                        $weeks_diff = $diff->format("%a")/7;
                        //echo "<br />".$agm_remin[$val3['remind_before']];
                        $remind_bef = !empty($val2['remind_before']) ? $service_provider_remin[$val2['remind_before']] : 0;

                        if ((($remind_bef == $months_diff.' Months' || $remind_bef == $months_diff.' Month') && $day_diff == 0)
                            || (($remind_bef == $weeks_diff.' Weeks' || $remind_bef == $weeks_diff.' Week') && $day_diff % 7 == 0)
                            || ($remind_bef == $days_diff.' Days' || $remind_bef == $weeks_diff.' Day')) {

                            $mail_subj = 'Test';
                            $email_body = 'This is a reminder email that contract of service provider ' . $val2['provider_name'] . ', blong to property ' . $val['property_name'] .  ' is expiring on ' . $val2['contract_end_date'];


                            $staff_emails = $this->agm_emails->getPropertyStaffs ( $val['property_id'] );

                            $email1 = array (
                                'staff_id' => 1000,
                                'first_name' => 'Yameen',
                                'last_name' => 'Adnan',
                                'email_addr' => 'yameenadnan@hotmail.com'
                            );
                            $email[] = $email1;

                            // $mail_msg = $this->agm_emails->send_email($staff_emails,$mail_subj,$email_body,$val['email_addr']);

                            $mail_msg = $this->agm_emails->send_email($email,$mail_subj,$email_body,'yameenadnan@propertybutler.my');

                            echo 'Test >> ' . $mail_msg;
                            die;


                            /*if ( !$mail_msg ) {
                                $mail_subj .= " is not send";
                                $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                                $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                                // $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$email_body,$val['email_addr']);
                            }

                            $jmb_emails = $this->agm_emails->getPropertyJmb($val['property_id']);
                            if(!empty($jmb_emails)) {
                                $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                                // $mail_msg = $this->agm_emails->send_email($jmb_emails,$mail_subj,$email_body,$val['email_addr']);
                                if(!$mail_msg) {
                                    $mail_subj .= " is not send";
                                    $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                                    $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                                    // $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$val3['email_content'],$val['email_addr']);
                                }
                            }*/

                        }
                    }
                }
            }
        }
    }

    function sendAnnualRenewalReminder () {
        $this->load->library('email');
        $property_list = $this->bms_cron_jobs_model->getPropertyListForAnnualRenewal ();
        if ( !empty($property_list) ) {
            foreach ( $property_list as $key=>$val ) {
                $email_body = '';
                $staff_emails = array ();
                $date2=date_create(date('Y-m-d'));
                $date1=date_create($val['license_expiry_date']);
                $diff = date_diff($date1,$date2,TRUE);
                $day_diff = $diff->format("%d");
                if ( $day_diff % 7 == 0 ) {
                    $mail_subj = 'Annual renewal reminder';
                    $email_body = 'This is a reminder email that item "' . (!empty($val['item_descrip'])?$val['item_descrip']:'N/A') . '", Serial No. "' . ( !empty($val['serial_no'])?$val['serial_no']:'N/A' ) . '", License No. "' . ( !empty($val['license_no'])?$val['license_no']:"N/A" ) . '" is being expired on ' . date("d-m-Y", strtotime($val['license_expiry_date']));

                    $staff_emails = $this->agm_emails->getPropertyStaffs ( $val['property_id'] );

                    if ( !empty($staff_emails) ) {
                        $this->email
                        ->from('noreply@propertybutler.my');
                        if ( !empty($val_unit['email_addr']) && (filter_var($val_unit['email_addr'], FILTER_VALIDATE_EMAIL))  ) {
                            $this->email->to($val['email_addr']);
                        }
                        foreach ( $staff_emails as $key_email => $val_email ) {
                            $this->email->bcc( $val_email['email_addr'] );
                        }
                        $this->email->bcc('naguwin@gmail.com','Nagarajan')
                        ->subject ( $mail_subj )
                        ->message ( $email_body )
                        ->send ();
                    }
                }
            }
        }
    }

    function sendAssetServiceReminder () {
        // get all the records from bms_asset_service_schedule where date between now and future 3 months
        $asset_list = $this->bms_cron_jobs_model->getAssetList ();
        if ( !empty($asset_list) ) {
            $asset_service_remin = $this->config->item('asset_warranty_remin');
            foreach ( $asset_list as $key => $val ) {

                $date2=date_create(date('Y-m-d'));
                $date1=date_create($val['service_date']);

                $diff = date_diff($date1,$date2,TRUE);

                $day_diff = $diff->format("%d");
                $days_diff = $diff->format("%a");
                $months_diff = $diff->format("%m");
                $weeks_diff = $diff->format("%a")/7;
                //echo "<br />".$agm_remin[$val3['remind_before']];
                $remind_bef = !empty($val['service_reminder']) ? $asset_service_remin[$val['service_reminder']] : 0;

                if ((($remind_bef == $months_diff.' Months' || $remind_bef == $months_diff.' Month') && $day_diff == 0)
                    || (($remind_bef == $weeks_diff.' Weeks' || $remind_bef == $weeks_diff.' Week') && $day_diff % 7 == 0)
                    || ($remind_bef == $days_diff.' Days' || $remind_bef == $weeks_diff.' Day')) {

                    $mail_subj = 'Test';
                    $email_body = 'This is a reminder email that service of <b>' . $val['asset_name'] .  '</b> belong to property <b>' . $val['property_name'] . '</b> is due on <b>' . date('d-m-Y',strtotime($val['service_date'])) .  '</b> ';

                    $email1 = array (
                        'staff_id' => 1000,
                        'first_name' => 'Yameen',
                        'last_name' => 'Adnan',
                        'email_addr' => 'yameenadnan@hotmail.com'
                    );
                    $email[] = $email1;

                    $mail_msg = $this->agm_emails->send_email($email,$mail_subj,$email_body,'admin@propertybutler.my');

                    /*
                    $person_incharge = array (
                        'staff_id' => 1000,
                        'first_name' => $val['person_incharge'],
                        'last_name' => '',
                        'email_addr' => $val['person_inc_email']
                    );
                    $staff_emails_array = $this->agm_emails->getPropertyStaffs ( $val['property_id'] );
                    $staff_emails = array_merge($person_incharge, $staff_emails_array);
                    $mail_msg = $this->agm_emails->send_email($staff_emails,$mail_subj,$email_body,$val['email_addr']);
                    */

                    /*if ( !$mail_msg ) {
                        $mail_subj .= " is not send";
                        $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                        $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                        // $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$email_body,$val['email_addr']);
                    }

                    $jmb_emails = $this->agm_emails->getPropertyJmb($val['property_id']);
                    if(!empty($jmb_emails)) {
                        $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                        // $mail_msg = $this->agm_emails->send_email($jmb_emails,$mail_subj,$email_body,$val['email_addr']);
                        if(!$mail_msg) {
                            $mail_subj .= " is not send";
                            $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
                            $mail_msg = $this->agm_emails->send_email('yameenadnan@hotmail.com',$mail_subj,$email_body,$val['email_addr']);
                            // $mail_msg = $this->agm_emails->send_email($mail_to,$mail_subj,$val3['email_content'],$val['email_addr']);
                        }
                    }*/

                }
            }
        }
    }








    function testCronJob () {
        $mail_subj = 'Testing Cron Job 5 | Reminder Before Notice for AGM';
        $to_emails = array (array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan P'));
        $email_content = " This is test email for cron job setup";
        $from_addr = 'from@cron.com';
        $mail_msg = $this->agm_emails->send_email($to_emails,$mail_subj,$email_content,$from_addr);
        echo $mail_msg ? ' Mail send successfully' : 'Mail send Failed';
    }
    
    function cny_grettings () {
        //CNY_2019_Greetings.png
        ini_set("allow_url_fopen", 1);
        
        $mail_subj = 'HAPPY CHINESE NEW YEAR 2019';
        //$to_emails = array (array('email_addr'=>'jasonlim81@gmail.com','first_name'=>'HOCK WAH LIM, JASON'));
        //$to_emails = array (array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan P'));
        $email_content = " <img src='https://www.propertybutler.my/CNY_2019_Greetings.png' />";
        $from_addr = 'admin@propertybutler.my';
        $custom_error_folder = base_url().'bms_uploads'.DIRECTORY_SEPARATOR.'custom_error_log'.DIRECTORY_SEPARATOR;
        $invalid_email_file = $custom_error_folder.'invalid_email_addr.txt';
        $email_not_send_file = $custom_error_folder.'email_not_send.txt';
        $unit_emails = $this->bms_cron_jobs_model->get_unit_email();
        $count = 0;
        foreach($unit_emails as $key=>$val) {
            if (filter_var($val['email_addr'], FILTER_VALIDATE_EMAIL)) {
                $to_emails = array (array('email_addr'=>$val['email_addr'],'first_name'=>''));
                $mail_msg = $this->agm_emails->send_email($to_emails,$mail_subj,$email_content,$from_addr);
                if(!$mail_msg) {
                    $content = array('unit_id'=>$val['unit_id'], 'property_id'=>$val['property_id'],'email_addr'=>$val['email_addr']);
                    //file_put_contents($email_not_send_file,$content,FILE_APPEND);
                    $this->bms_cron_jobs_model->set_email_not_send($content);                    
                } 
            } else {
                //$content = "Unit Id: ".$val['unit_id']." Property_id: ".$val['property_id']. " Email Address: ".$val['email_addr'];
                //file_put_contents($invalid_email_file,$content,FILE_APPEND);
                $content = array('unit_id'=>$val['unit_id'], 'property_id'=>$val['property_id'],'email_addr'=>$val['email_addr']);
                $this->bms_cron_jobs_model->set_email_addr_invalid($content);                
            }
            if(++$count % 100 == 0) {
                sleep(10);
            }
        }
        
        //if (filter_var($email, FILTER_VALIDATE_EMAIL))
        //file_put_contents($email_not_send_file,$content,FILE_APPEND);
        
        //$mail_msg = $this->agm_emails->send_email($to_emails,$mail_subj,$email_content,$from_addr);
        //echo $mail_msg ? ' Mail send successfully' : 'Mail send Failed';
        echo "List completed";
    }

    function smtp_email() {

        /*$to = "Adnsn <clickpencil2010@gmail.com>";
        $subject = "My subject";
        $txt = "Hello world!";
        $headers = "From: yameenadnan@hotmail.com" . "\r\n" .
            "CC: clickpencil2010@gmail.com";

        mail($to,$subject,$txt,$headers);*/

        $mail_subj = 'Subject';
        $email_body = 'Body';

        $email1 = array (
            'staff_id' => 1000,
            'first_name' => 'Yameen',
            'last_name' => 'Adnan',
            'email_addr' => 'yameenadnan@hotmail.com'
        );
        $email[] = $email1;

        $mail_msg = $this->agm_emails->send_email($email,$mail_subj,$email_body,'yameenadnan@hotmail.com');

        echo $mail_msg;
        die;
    }

    function sendENoticeQueue () {
        $custom_error_folder = '/home/propertybutler/public_html/'.'bms_uploads'.DIRECTORY_SEPARATOR.'custom_error_log'.DIRECTORY_SEPARATOR;
        $invalid_email_file = $custom_error_folder.'invalid_email_addr.txt';
        $content = "\nCron Job Started @ ".date('d-m-Y H:i:s');
        file_put_contents($invalid_email_file,$content,FILE_APPEND);
        $notices = $this->bms_cron_jobs_model->getENoticeQueue ();
        $service_requests = $this->bms_cron_jobs_model->getServiceReqQueue ();
        $this->load->library('email');

        if (count($service_requests) > 0) {
            foreach ($service_requests as $key => $val) { //>email->clear(true);
                if (filter_var($val['to'], FILTER_VALIDATE_EMAIL)) {
                    //$this->email->ClearAllRecipients();
                    $this->email->clear(TRUE);

                    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                        <title>' . html_escape($val['subject']) . '</title>
                        <style type="text/css">
                            body {
                                font-family: Arial, Verdana, Helvetica, sans-serif;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                    ' . nl2br($val['message']). '
                    
                    <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;"> 
                        This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office
                        '.(!empty($val['email_addr']) ? '<b>Email:</b> '. $val['email_addr'] : ''). (!empty($val['phone_no']) ? ' <b>Phone:</b> '. $val['phone_no'] : '').'
                        </div>
                        <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 10px;Margin-top:15px;"> 
                        If you do not wish to receive further email communications from us, please <a href="'.base_url().'unsubscribe.html'.'">unsubscribe here</a>.
                        </div>
                    </body>
                    </html>';

                    $this->email
                        ->from('noreply@propertybutler.my', 'Propertybutler'); // jmb_mc_name

                    $this->email
                        ->bcc($val['to'],$val['to_name'])
                        ->subject(' Subject: '. $val['subject'])
                        ->message($body);
                    $result = $this->email->send();
                    $data_ins['sent_dt'] = date('Y-m-d H:i:s');
                    $data_ins['is_sent'] = $result ? 1 : 2;
                    $this->bms_cron_jobs_model->updateServiceReqQueue($data_ins,$val['queue_id']);
                }
            }
        } else if(count($notices) > 0) {
            foreach($notices as $key=>$val) { //>email->clear(true);
                if (filter_var($val['to'], FILTER_VALIDATE_EMAIL)) {
                    //$this->email->ClearAllRecipients();
                    $this->email->clear(TRUE);

                    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
                        <title>' . html_escape($val['subject']) . '</title>
                        <style type="text/css">
                            body {
                                font-family: Arial, Verdana, Helvetica, sans-serif;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                    ' . nl2br($val['message']). '
                    
                    <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color:red;Margin-top:15px;"> 
                        This is system generated email. Please do not reply to this email. For any clarification or enquaries contact management office
                        '.(!empty($val['email_addr']) ? '<b>Email:</b> '. $val['email_addr'] : ''). (!empty($val['phone_no']) ? ' <b>Phone:</b> '. $val['phone_no'] : '').'
                        </div>
                        <div style="font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 10px;Margin-top:15px;"> 
                        If you do not wish to receive further email communications from us, please <a href="'.base_url().'unsubscribe.html'.'">unsubscribe here</a>.
                        </div>
                    </body>
                    </html>';

                    $this->email
                        ->from('noreply@propertybutler.my',$val['property_name']. '(MO)'); // jmb_mc_name
                    if (!empty($val['email_addr'])){
                        $this->email->reply_to($val['email_addr'],$val['property_name']. '(MO)'); // jmb_mc_name
                    }

                    //->from($property_info['email_addr'],'Propertybutler')
                    //->reply_to($property_info['email_addr'],'Propertybutler')    // Optional, an account where a human being reads.
                    $this->email
                        ->bcc($val['to'],$val['to_name'])
                        //->bcc('tanbenghwa@gmail.com')
                        //->bcc('email@transpacc.com.my','Transpacc Emails')
                        ->subject('e-Notice Ref # '.$val['notice_id'] . ' | '. $val['property_name']. ' |'.(!empty($val['unit_no']) ? $val['unit_no'] .' |' : '' ). ' Subject: '. $val['subject'])
                        ->message($body);
                    if(!empty($val['attachment_name'])) {
                        $attachment = $e_notice_attach_upload = $this->config->item('e_notice_attach_upload');
                        $attachment['upload_path'] .= '/'.date('Y',strtotime($val['start_date'])).'/'.date('m',strtotime($val['start_date']));
                        $this->email->attach($attachment['upload_path'].'/'.$val['attachment_name']);
                    }

                    $result = $this->email->send();
                    $data_ins['sent_dt'] = date('Y-m-d H:i:s');
                    $data_ins['is_sent'] = $result ? 1 : 2;
                    $this->bms_cron_jobs_model->updateENoticeQueue($data_ins,$val['queue_id']);

                }
            }
        }
        $custom_error_folder = '/home/propertybutler/public_html/'.'bms_uploads' . DIRECTORY_SEPARATOR . 'custom_error_log' . DIRECTORY_SEPARATOR;
        $invalid_email_file = $custom_error_folder.'invalid_email_addr.txt';
        $content = "\nCron Job Ended @ ".date('d-m-Y H:i:s');
        file_put_contents($invalid_email_file,$content,FILE_APPEND);
    }

    function sendPaymentsToSite () {
        $date = date('Y-m-d',strtotime('-1 day',strtotime(date('Y-m-d')))); //'2020-04-13';//
        $cnt_details = $this->bms_cron_jobs_model->getTransactionCntForTheDay ($date);
        if(!empty($cnt_details)) {
            foreach ($cnt_details as $key => $val) {
                if(!empty($val['property_id']) && !empty($val['cnt']) && $val['cnt'] > 0) {
                    $transactions = $this->bms_cron_jobs_model->getTransactionOfTheProperty ($val['property_id'],$date);
                    if(!empty($transactions) && !empty($transactions[0]['email_addr'])) {

                        $this->load->library('email');

                        $subject = 'Payment Transaction Details';

                        $message = '<h3> Payment Transaction Details </h3>';
                        $message .= '<p>';
                        $message .= '<b>Please find the payment details for the day of '.date('d-m-Y',strtotime($date)).' </b>';
                        $message .= '</p>';

                        $message .= '<table align="left" cellspacing="0" cellpadding="0" width="820px" border="0" style="margin:0px !importasnt;">';
                        $message .= '<tr>';
                        $message .= '<td width="40"><b>S No</b></td>';
                        $message .= '<td width="120"><b>Transaction ID</b></td>';
                        $message .= '<td width="170"><b>Reference No.</b></td>';
                        $message .= '<td width="120"><b>Payment Mode</b></td>';
                        $message .= '<td width="100"><b>Payment For</b></td>';
                        $message .= '<td width="100"><b>Amount</b></td>';
                        $message .= '<td width="170"><b>Trans Date& Time</b></td>';
                        $message .= '</tr>';
                        $i = 1;
                        foreach($transactions as $tkey =>$tval) {
                            $message .= '<tr>';
                            $message .= '<td>'.($i++).'</td>';
                            $message .= '<td>'.($tval['Transaction_ID']).'</td>';
                            $message .= '<td>'.($tval['Reference_Number']).'</td>';
                            $message .= '<td>'.($tval['Payment_ID'] == 2 ? ' Cerdit/Debit Card ' : ' FPX ').'</td>';
                            $message .= '<td>'.(empty($tval['pymt_for']) ? ' - ' : ($tval['pymt_for'] == '1' ? 'Clamping' : 'Over night parking')).'</td>';
                            $message .= '<td>RM '.($tval['Amount']).'</td>';
                            $message .= '<td>'.($tval['trans_date']).'</td>';
                            $message .= '</tr>';

                        }
                        $message .= '</table>';
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
                            ->to($transactions[0]['email_addr'],'');
                        $this->email
                            ->bcc('naguwin@gmail.com','Nagarajan')
                            ->bcc('skumarrcg@gmail.com','Kumar Kanason')
                            ->subject($subject)
                            ->message($body)
                            ->send();

                    }
                }
            }
        }


        //echo "<pre>";print_r($cnt_details);
    }

    function readBounceBackEmailSetUnitTbl () {
        /*

        $hostname = '{mail.propertybutler.my:993/imap/ssl}INBOX'; // Email server connection
        $username = 'noreply@propertybutler.my';
        $password = 'D3nyAcc355@@';


        $mbox = imap_open($hostname,$username,$password) or die('Cannot connect to mailbox: ' . imap_last_error());


        //$emails = imap_search($inbox,'ALL');

        $mboxCheck = imap_check($mbox);

        // get the total amount of messages
        $totalMessages = $mboxCheck->Nmsgs;

        // select how many messages you want to see
        $showMessages = 20;

        // get those messages
        $result = imap_fetch_overview($mbox,($totalMessages-$showMessages+1).":".$totalMessages);

        //echo "<pre>";print_r($result);echo "</pre>";




        $email_list = array ();

        // if emails are returned, cycle through each...
        if ( $result ) {

            // begin output var /
            $output = '';

            // put the newest emails on top /
            //rsort($result);
            $counter = 1;
            // for every email...
            foreach($result as $email) {

                echo $body = imap_fetchbody ($mbox,$email->msgno,0);
                echo "<pre>";print_r($body); echo "</pre>";

                // get information specific to this email
                //$overview = imap_fetch_overview($mbox,$email_number,0);
                //$message = imap_fetchbody($mbox,$email_number, '2');
                //$message2 = imap_fetchbody($mbox,$email_number, '1');
                //$header_info = imap_headerinfo ( $mbox, $email_number);

                //echo "<pre>";print_r($message); echo "</pre>";

                if ( $email_number->subject == "Undelivered Mail Returned to Sender" ) {
                    if (
                        strpos($message2, "The email account that you tried to reach does not exist.") !== false || // gmail
                        strpos($message2, "This user doesn\'t have a yahoo.com account") !== false || // yahoo.com
                        strpos($message2, "This user doesn\'t have a ymail.com account") !== false || // ymail
                        strpos($message2, "Requested action not taken: mailbox unavailable.") !== false || // hotmail
                        strpos($message2, "Host or domain name not found.") !== false
                    ) {
                        $msg = explode ('Final-Recipient: rfc822; ', $message);
                        $msg1 = explode ('Original-Recipient: ', $msg[1]);
                        $msg1 = explode ('Original-Recipient: ', $msg[1]);

                        if ( !in_array ($msg1[0], $email_list) ) {
                            $email_list[] = $msg1[0];
                            // imap_delete ($inbox, $email_number );
                        }
                    }
                }
            }
            //imap_expunge ( $inbox );
        }
        // close the connection
        imap_close($mbox);

        $invalid_email_list = "'" . implode ("', '",$email_list) . "'";

        print_r ( $invalid_email_list );
        exit;

        $this->bms_fin_cron_jobs_model->setValidEmailByEmailAddress ( $invalid_email_list );
        */
    }

}