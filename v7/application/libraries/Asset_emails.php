<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset_emails extends Mailersend {
      
    function __construct() { $this->CI = & get_instance();    }
    
    function getPropertyStaffs ($property_id) {
        $sql = "SELECT staff_id,first_name,last_name,email_addr
                FROM bms_staff                
                WHERE emp_type IN (1,2,3) 
                AND designation_id NOT IN (1,7,8,10,14,13,15,22)
                AND staff_id NOT IN (1312,1335,1336,1337,1341,1342)
                AND staff_id IN(
                SELECT staff_id from bms_staff_property WHERE property_id =".$property_id.")";
        $sql .= " ORDER BY first_name ASC,last_name ASC";
        
        $query = $this->CI->db->query($sql);  
        //echo "<pre>".$this->db->last_query();
        return $query->result_array();
    }
    
	    
    function setServiceSchedule ($asset_info,$service) {
        //echo "<pre>setServiceSchedule";print_r($_POST);
        $asset_service_name = $this->CI->config->item('asset_service_name'); 
        $mail_to = $this->getPropertyStaffs($asset_info['property_id']);  
        //echo "<pre>mail to";print_r($mail_to);    
        foreach($service['service_name'] as $skey=>$sval) {
            if($sval != '') {
                $mail_content = 'Hi,<br /><br />';
                
                $mail_content .= 'Recently an Asset added into BMS - Property Name:<b>'.($asset_info['property_name'] != '' ? $asset_info['property_name'] : ' - ').'</b>';
                $mail_content .= ', Asset Name: <b>'.$asset_info['asset_name'].'</b>';
                $mail_content .= ', Asset Description:<b>'.($asset_info['asset_descri'] != '' ? $asset_info['asset_descri'] : ' - ').'</b>';
                $mail_content .= ', Asset Location:<b> '.($asset_info['asset_location'] != '' ? $asset_info['asset_location'] : ' - ').'</b>';
                $mail_content .= ', Asset Serial No. <b>'.($asset_info['serial_no'] != '' ? $asset_info['serial_no'] : ' - ').'</b>. ';
                $mail_content .= 'For this Asset you have not updated/schedule the <b>'.$asset_service_name[$sval].'</b> date yet, ';
                $mail_content .= 'please Schedule the <b>'.$asset_service_name[$sval].'</b>  date in Asset Service Schedule option.';
                $mail_content .= '<br /><br /><br />Thank you,<br /><br />';
                $mail_content .= 'Building Management System (BMS)<br />Transpacc Property Management Sdn Bhd';
        		
        		
        		$from = 'admin@propertybutler.my';
        		$subject = "Reminder to Schedule the service date";
                
                
        		
                $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
                
        		if($mail_msg == 0) {
        			$subject .= " is not send";
                    $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
        			$mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        		}
            }
        }
    }
    
    
    
    function setMaintenanceComp($asset_info) {
        //echo "<pre>setMaintenanceComp";print_r($_POST);
        $subject = "Reminder to add Maintenance Company";
        
		$mail_content = 'Hi,<br /><br />';
                
        $mail_content .= 'Recently an Asset added into BMS - Property Name:<b>'.($asset_info['property_name'] != '' ? $asset_info['property_name'] : ' - ').'</b>';
        $mail_content .= ', Asset Name: <b>'.$asset_info['asset_name'].'</b>';
        $mail_content .= ', Asset Description:<b>'.($asset_info['asset_descri'] != '' ? $asset_info['asset_descri'] : ' - ').'</b>';
        $mail_content .= ', Asset Location:<b> '.($asset_info['asset_location'] != '' ? $asset_info['asset_location'] : ' - ').'</b>';
        $mail_content .= ', Asset Serial No. <b>'.($asset_info['serial_no'] != '' ? $asset_info['serial_no'] : ' - ').'</b>. ';
        $mail_content .= 'For this Asset you need to enter the Maintenance Company Details first before setting up the Service/Calibration date';
        
        $mail_content .= '<br /><br /><br />Thank you,<br /><br />';
        $mail_content .= 'Building Management System (BMS)<br />Transpacc Property Management Sdn Bhd';
		
		
		$from = 'admin@propertybutler.my';	
        
        $mail_to = $this->getPropertyStaffs($asset_info['property_id']);
		
        $mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
        
		if($mail_msg == 0) {
			$subject .= " is not send";
            $mail_to = array(array('email_addr'=>'naguwin@gmail.com','first_name'=>'Nagarajan'));
			$mail_msg = $this->send_email($mail_to,$subject,$mail_content,$from);
		}
		   
	}
      
}