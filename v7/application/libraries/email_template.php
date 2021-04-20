<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email_template extends Mailersend {
      
    function __construct() {

       $CI =& get_instance(); // get the CodeIgniter object
    	
    }
    
	function header() {
	
		
	 $content = '<html><head><style>body{margin-left:20px;margin-right:20px;font-family:font-family:Verdana;font-size: 13px;color: #333333;}.rowStyleOdd{background:#EEEEEE;}.rowStyleEven{background:#FFFFFF;}</style></head><body>
	 <img src="'.base_url().'images/um_mail_header_logo.png" style="margin-bottom:5px;" width="250" height="91">
	 <BR><div style="min-height:300px;line-height:19px;">';
	 return $content;
	
	}
    
	function footer() {
	
	    $content = '</div><br>
					<hr style="width:100%">
					<span style="font-size:10px;">University of Malaya, 50603 Kuala Lumpur</span><br/>
					</body></html>';
	   return $content;
	
	}
    
    function send_test_email () {
        $mail_content = $this->header();
        
        $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		
		$from = 'admin@propertybutler.my';
		$subject = "Test Email";
        
        $mail_to = 'naguwin@gmail.com';
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
			$mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
		}
    }
    
    function activity_notification_to_ydp_sec($name,$mail_to,$data='',$no_user='',$is_auto_approved_noti='') {
	
		$mail_content = $this->header();
        $mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
                ';
        if($is_auto_approved_noti == '')
            $mail_content .= 'Adalah dimaklumkan Pengarah program  telah menghantar permohonan mengadakan aktiviti untuk pengesahan saudara/i selaku Yang Dipertua Badan Pelajar bagi program berikut :-';
        else 
            $mail_content .= 'Adalah dimaklumkan sistem telah auto-approve permohonan mengadakan aktiviti disebabkan tiada tindakan daripada pihak tuan. Berikut adalah butiran aktiviti yang dimohon :-';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        if($is_auto_approved_noti == '') {
            $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
    				Sila ikuti langkah berikut untuk proses \'Pengesahan\':<br />
    				Login ke dalam MYUM<br />
    				Klik ikon \'Cocuriculum Activities\'<br />
    				Klik ikon \'Yang Dipertua/Secretary\'<br />
    				Klik butang \'Support/Not Support\' untuk memberi jawapan terhadap permohonan tersebut<br />
                    Klik butang \'Save\'<br />
    				<br />				
    			</p>
    		</div>';
        } 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | ".($is_auto_approved_noti == '' ? "New Activity/Project Approval" : "Auto Approved");
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : " is not send due to ydp/sec not exist. Activity id =".$data['ACTIVITY_ID']; 
    		$mail_to = 'nagarajan@um.edu.my';
        }
        else {
            if($no_user != '') {
                $subject.=  " is not send due to YDP / Sec email id not exist. Activity id =".$data['ACTIVITY_ID'];
                $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is failed";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
		   
	}
    
    // Its required for cron jobs 
    function activity_notification_to_mentor($name,$mail_to,$data='',$no_user='',$is_auto_approved_noti='') {  // Mentor 
	
		$mail_content = $this->header();
        $mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
                Adalah dimaklumkan Pengarah program  telah memohon pengesyoran daripada Tuan/Puan selaku Mentor badan pelajar bagi program berikut :-';
        if($is_auto_approved_noti == '')
            $mail_content .= 'Adalah dimaklumkan Pengarah program  telah menghantar permohonan mengadakan aktiviti untuk pengesahan saudara/i selaku Yang Dipertua Badan Pelajar bagi program berikut :-';
        else 
            $mail_content .= 'Adalah dimaklumkan sistem telah auto-approve permohonan mengadakan aktiviti disebabkan tiada tindakan daripada pihak tuan. Berikut adalah butiran aktiviti yang dimohon :-';
        
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        if($is_auto_approved_noti == ''){
            $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
    				Sila ikuti langkah berikut untuk proses \'Pengesyoran\':<br />
    				Login ke dalam UMPortal<br />
    				Klik Tab \'ISIS\'<br />
    				Klik ikon \'Student Activities\'<br />
    				Klik ikon \'HEP\'<br />
                    Pilih Modul \'View & Approve / Disapprove Activity\'<br />
                    Pilih Modul \Comments\'<br />
                    Klik butang \'Support/Not Support\' untuk memberi jawapan terhadap permohonan tersebut<br />
                    Klik butang \'Save\'<br />
    				<br />				
    			</p>
    		</div>';
        } 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | ".($is_auto_approved_noti == '' ? "New Activity/Project Approval" : "Auto Approved");
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : " is not send due to Mentor email id not exist. Activity id =".$data['ACTIVITY_ID']; 
    		$mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.= " is not send due to Mentor email id not exist. Activity id =".$data['ACTIVITY_ID']; 
    		    $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
	}
    
    function activity_notification_to_hep_officer($name,$mail_to,$data,$no_user='') {
	
		$mail_content = $this->header();
		$mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				<!--b>Dear '.$name.'</b>,<br />
				<br /-->
                Adalah dimaklumkan Pengarah program  telah memohon kelulusan daripada Tuan/Puan selaku Timbalan Naib Canselor, Bahagian Hal Ehwal Pelajar bagi program berikut :-';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				Sila ikuti langkah berikut untuk proses \'Kelulusan\':<br />
				Login ke dalam UMPortal<br />
				Klik Tab \'ISIS\'<br />
				Klik ikon \'Student Activities\'<br />
				Klik ikon \'HEP\'<br />
                Pilih Modul \'Activity Approval\'<br />
                Pilih Modul \'Details & Approve / Disapprove\'<br />
                Klik butang \'Approve / Not Approved\' untuk kelulusan<br />
                Masukkan \'Comments\' & jumlah subsidi yang diluluskan<br />
                Klik butang \'Save\' untuk memberi jawapan terhadap permohonan tersebut<br />
				<br />				
			</p>
		</div>'; 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | New Activity/Project Approval";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : " is not send due to HEP officer email id not exist. Activity id =".$data['ACTIVITY_ID'];
            $mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.=  " is not send due to HEP Officer email id not exist. Activity id =".$data['ACTIVITY_ID'];
                $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is failed";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
	}
    
    function activity_notification_to_tnc($name,$mail_to,$data,$no_user='') {
	
		$mail_content = $this->header();
		$mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				<!--b>Dear '.$name.'</b>,<br />
				<br /-->
                Adalah dimaklumkan Pengarah program  telah memohon kelulusan daripada Tuan/Puan selaku Timbalan Naib Canselor, Bahagian Hal Ehwal Pelajar bagi program berikut :-';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				Sila ikuti langkah berikut untuk proses \'Kelulusan\':<br />
				Login ke dalam UMPortal<br />
				Klik Tab \'ISIS\'<br />
				Klik ikon \'Student Activities\'<br />
				Klik ikon \'TNC\'<br />
                Pilih Modul \'Activity Approval\'<br />
                Pilih Modul \'Details & Approve / Disapprove\'<br />
                Klik butang \'Approve / Not Approved\' untuk kelulusan<br />
                Masukkan \'Comments\' & jumlah subsidi yang diluluskan<br />
                Klik butang \'Save\' untuk memberi jawapan terhadap permohonan tersebut<br />
				<br />				
			</p>
		</div>'; 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
        $mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | New Activity/Project Approval";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : " is not send due to TNC email id not exist. Activity id =".$data['ACTIVITY_ID'];
            $mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.=  " is not send due to TNC email id not exist. Activity id =".$data['ACTIVITY_ID'];
                $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
		   
	}
    
    function activity_notification_to_fakulti($name,$mail_to,$whom='',$data='',$no_user='') {
	    
		$mail_content = $this->header();
		$mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				<!--b>Dear '.$name.'</b>,<br />
				<br /-->';
        $mail_content .= 'Adalah dimaklumkan Pengarah program  telah memohon pengesyoran daripada Tuan/Puan selaku '.($whom == 'pc' ? 'Program Coordinator' : 'Dekan Fakulti').' bagi program berikut :-';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				Sila ikuti langkah berikut untuk proses \'Pengesahan\':<br />
				Login ke dalam UMPortal<br />
				Klik Tab \'ISIS\'<br />
				Klik ikon \'Student Activities\'<br />
				Klik ikon \'Fakulti\'<br />
                Pilih Modul \'View & Approve / Disapprove Activity\'<br />
                Pilih Modul \'Comments\'<br />
                Klik butang \'Support/Not Support\' untuk memberi jawapan terhadap permohonan tersebut<br />
                Klik butang \'Save\'<br />
				<br />				
			</p>
		</div>'; 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | New Activity/Project Approval";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : ' is not send due to '.($whom == 'pc' ? 'Program Coordinator' : 'Dekan Fakulti').' email id not exist. Activity id='.$activity_id; 
    		$mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.=  ' is not send due to '.($whom == 'pc' ? 'Program Coordinator' : 'Dekan Fakulti').' email id not exist';
                $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
		   
	}
    
    function activity_notification_to_college($name,$mail_to,$whom, $data='',$no_user='') {
	
		$mail_content = $this->header();
		$mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				<!--b>Dear '.$name.'</b>,<br />
				<br /-->';
        $mail_content .= 'Adalah dimaklumkan Pengarah program  telah memohon pengesyoran daripada Tuan/Puan selaku '.($whom == 'fel' ? 'Felo Kolej' : 'Pengetua Kolej').' bagi program berikut :-';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
            $mail_content .= "</table>";
        }
        $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				Sila ikuti langkah berikut untuk proses \'Pengesahan\':<br />
				Login ke dalam UMPortal<br />
				Klik Tab \'ISIS\'<br />
				Klik ikon \'Student Activities\'<br />
				Klik ikon \'Kolej\'<br />
                Pilih Modul \'View & Approve / Disapprove Activity\'<br />
                Pilih Modul \'Comments\'<br />
                Klik butang \'Support/Not Support\' untuk memberi jawapan terhadap permohonan tersebut<br />
                Klik butang \'Save\'<br />
				<br />				
			</p>
		</div>'; 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">
                            Emel ini dijana secara automatik. Mohon untuk tidak membalas emel ini. Sila hubungi kegiatan@um.edu.my untuk maklumat lanjut.
                          </p>';
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | New Activity/Project Approval";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : ' is not send due to '.($whom == 'fel' ? 'Fellow college' : 'Master').' email id not exist'; 
    		$mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.=  ' is not send due to '.($whom == 'pc' ? 'Fellow college' : 'Master college').' email id not exist';
                $mail_to = SA_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
		   
	}
    
    function activity_notification_to_student($name,$mail_to,$by,$what,$data='',$contact_to='',$no_user='',$cc_ids='')
    {
        $mail_content = $this->header();
        
        switch ($by)
        {
            case 'REPORT_REV':
                if($what == 'N'){
                    $msg = 'Your activity report has been disapproved by HEP Adinm';
                } else {
                    $msg = 'Your activity report has been approved by HEP Admin';
                }               
                break;
                
            case 'SA':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED due to incomplete application form. Please contact the HEP Admin at '.$contact_to.' for further infomation. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by HEP Admin';
                }
                break;
            
            case 'YDP':
                if($what == 'N') {
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the YDP / President at '.$contact_to.'  for  further discussion
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by YDP/President';
                }
                
                break; 
                   
            case 'MEN':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the project Mentor at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by Mentor';
                }
                break;
            case 'FEL':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Felow College at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by Felow College';
                }
                //$msg = 'Your activity has been '.($what == 'N' ? 'dis' : '').'approved by Fellow';
                break;
            case 'PC':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Program Coordinator at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by Program Coordinator';
                }
                break;
            case 'DEAN':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Dean at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by Dean';
                }
                break;
            case 'MAS':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Master College at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by Master College';
                }
                break;
            case 'HEP':
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Officer in charge at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    $msg = 'Your activity has been approved by HEP Officer';
                }
                //$msg = 'Your activity has been '.($what == 'N' ? 'dis' : '').'approved by HEP';
                break;
            case 'HEP_CHANGE':
                $msg = 'Your request for changes in activity details has been '.($what == 'N' ? 'dis' : '').'approved by HEP';
                break;
            case 'HEP_START_END_DATE':
                $msg = 'Your activity details(Start Date / End Date / Agenda) has been updated by Administrator';
                break;
            case 'HEP_CANCEL':
                $msg = 'Your request for cancellation of activity has been approved by HEP';
                break;
            case 'TNC':
                
                if($what == 'N'){
                    $msg = 'Please be informed your application as follows:-';
                    if(is_array($data) && count($data)) {
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                    }
                    $msg .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
            				is REJECTED. Please contact the Officer in charge at  '.$contact_to.' for further discussion. 
            				<br />				
            			</p>'; 
                } else {
                    //$msg = 'Your activity has been approved by HEP Officer';
                    $msg = 'Your activity has been approved by TNC';
                    if(is_array($cc_ids) && count($cc_ids)) {
                        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR)     $msg .= "<br /><br />The CC id's are <b>".implode('</b>,<b>',$cc_ids)."</b><br />";
                        
                        $msg .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Project Title  </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['SA_VENUE']."</td></tr>";
                        $msg .= "</table>";
                        /*$msg .= "<table width='80%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                                 <tr> <td width='40%'> <b>Activity Name </b></td><td width='5%'>:</td><td width='55%'>".$data['SA_ACTVITY_NAME']."</td></tr>";
                        $msg .= "<tr> <td width='40%'> <b>Society / Faculty / College </b></td><td width='5%'>:</td><td width='55%'>".$data['SFC']."</td></tr>";
                        $msg .= "<tr> <td width='40%'> <b>Start Date </b></td><td width='5%'>:</td><td width='55%'>".$data['SA_START_DATE']."</td></tr>";
                        $msg .= "<tr> <td width='40%'> <b>End Date </b></td><td width='5%'>:</td><td width='55%'>".$data['SA_END_DATE']."</td></tr>";
                        $msg .= "</table>";*/
                    }
                }
                break;
        }
        
        if($what == 'N') {
            $mail_content .= '<div style="text-align: justify; width: 100%;">
        			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
        				<!--b>Dear '.$name.'</b>,<br />
        				<br /-->
        				'.$msg.'
        			</p></div>';
        } else {
            
            $mail_content .= '<div style="text-align: justify; width: 100%;">
        			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
        				<!--b>Dear '.$name.'</b>,<br />
        				<br /-->
        				'.$msg.($cc_ids == '' ? '.<br />': '').'
        			</p>
        						
        			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
        				Login at MyUM to view the comments.<br />
        				<br />
        				Thank you
        				<br />
        			</p>
        		</div>';
            $mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">PLEASE DO NOT REPLY TO THIS AUTO-GENERATED MESSAGE.</p>';
        }
		
		$mail_content .= $this->footer();
		
		$from = SA_EMAIL_FROM_ID;
		$subject = "Student Activity | Activity Status";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject.= $no_user == '' ?  " | ".$mail_to : " is not send due to Student invalid email id. Activity id = ".$data['ACTIVITY_ID']; 
    		$mail_to = DEV_ALL_EMAIL_TO;
		} else {
		  if($no_user != '') {
                $subject.= " is not send due to Student invalid email id. Activity id = ".$data['ACTIVITY_ID']; 
    		    $mail_to = SA_FAILED_EMAIL_ID;
            }
		}
        if(is_array($cc_ids) && count($cc_ids)) 
           $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from,'',$cc_ids); 
        else 
            $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
		if($mail_msg == 0) {
			$subject .= " is failed";
			$mail_msg = $this->send_email(SA_FAILED_EMAIL_ID,$subject,$mail_content,$from);
		}
    }
    
    function meeting_approval_notification_to_student($name,$mail_to,$data,$no_user='') {
	
		$mail_content = $this->header();
		$mail_content .= '<div style="text-align: justify; width: 100%;">
			<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
				
                We would like to inform that your meeting application status as the following details:<br />';
        if(is_array($data) && count($data)) {
            $mail_content .= "<table width='70%' cellspacing='0' cellpadding='0' border='0' style='font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;'>
                            <tr> <td width='20%'> <b>Name </b></td><td width='5%'>:</td><td width='75%'>".$data['STUDENT_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Meeting Type</b></td><td width='5%'>:</td><td width='75%'>".$data['MEETING_TYPE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Society Name</b></td><td width='5%'>:</td><td width='75%'>".$data['SOCIETY_NAME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Date </b></td><td width='5%'>:</td><td width='75%'>".$data['MEETING_DATE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Time </b></td><td width='5%'>:</td><td width='75%'>".$data['MEETING_TIME']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Venue</b></td><td width='5%'>:</td><td width='75%'>".$data['MEETING_VENUE']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Status </b></td><td width='5%'>:</td><td width='75%'>".$data['STATUS']."</td></tr>";
            $mail_content .= "<tr> <td width='20%'> <b>Comment</b></td><td width='5%'>:</td><td width='75%'>".$data['COMMENTS']."</td></tr>";
            $mail_content .= "</table>";
        }
        $mail_content .= '		
			</p>
		</div>'; 
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;">
                            Thank you
                          </p>';
		$mail_content .= '<p style="font: 14px normal Arial,San-sarif;line-height: 18px;text-align: justify;color:#DF4E26;">PLEASE DO NOT REPLY TO THIS AUTO-GENERATED MESSAGE.</p>';
        $mail_content .= $this->footer();
		//$mail_content .= $this->footer();
		
		$from = MEETING_EMAIL_FROM_ID;
		$subject = "MEETING APPROVAL APPLICATION";
        
        if (defined('IS_DEVELOPMENT_ENVIR') && IS_DEVELOPMENT_ENVIR) { 
            $subject = $subject . ' | '.$mail_to;
            $mail_to = DEV_ALL_EMAIL_TO;
        } else {
            if($no_user != '') {
                $subject.=  " is not send due to student email id not found for the meeting id =".$data['DM_SERIAL_ID'];
                $mail_to = MEETING_FAILED_EMAIL_ID;
            }
        }
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from,'','',MEETING_EMAIL_FROM_NAME);
        
		if($mail_msg == 0) {
			$subject .= " is failed due to Invalid email id / mail server problem";
			$mail_msg = $this->send_email(MEETING_FAILED_EMAIL_ID,$subject,$mail_content,$from,'','',MEETING_EMAIL_FROM_NAME);
		}
	}
    
    
}