<?php defined('BASEPATH') OR exit('No direct script access allowed');

// config for task category
//$config['task_cat'] = array("enq"=>"Enquiries","com"=>"Complaint","inf"=>"Information","int"=>"Internal","cmt"=>"Comments","opt"=>"Options");
//$config['task_cat'] = array("Enquiries"=>"Enquiries","Complaint"=>"Complaint","Information"=>"Information","Internal"=>"Internal","Comments"=>"Comments","Options"=>"Options");
//"4"=>"Internal","6"=>"Monthly Meeting", "7"=>"Committee Members",
$config['task_cat'] = array("1"=>"Enquiries","2"=>"Complaint","3"=>"Information","4"=>"Internal","5"=>"Comments","6"=>"Monthly Meeting","7"=>"Committee Members","8"=>"Others");


// config for Source of Assignment
//$config['source_assign'] = array("pho"=>"Phone Call","win"=>"Walk IN","app"=>"Apps","sms"=>"SMS","eml"=>"Email","oth"=>"Other");
//$config['source_assign'] = array("Phone Call"=>"Phone Call","Walk IN"=>"Walk IN","Apps"=>"Apps","SMS"=>"SMS","Email"=>"Email","Other"=>"Other");
$config['source_assign'] = array("1"=>"Phone Call","2"=>"Walk IN","3"=>"Apps","4"=>"SMS","5"=>"Email","6"=>"Other");

// config for Source of Assignment
$config['task_filter_status'] = array("al"=>"All","O"=>"Open","C"=>"Closed");

// Task Status
$config ['task_db_status'] = array('O'=>'OPEN','C'=>'Closed','R'=>'Re-assigned');

// Task Update
$config ['task_update'] = array(1=>'Work in progress', 2=>'Waiting for client reply', 3=>'Waiting for resident reply', 4=>'Waiting for JMB/MC approval', 5=>'Waiting for quotation', 6=>'Waiting for supplier', 7=>'To be discuss');

// Property under
$config['property_under'] = array (1 => 'JMB', 2 => 'MC', 3 => 'Developer');

$config['tax_type'] = array ('0'=>'None', '1'=>'GST', '2'=>'SST');

$config['finance_year_start_month'] = array(
                    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
                    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
                    "9" => "September", "10" => "October", "11" => "November", "12" => "December" );
                    
$config['property_status'] = array(1 => "Active", 0 => "Deactive");

// property charges history
$config['calcul_base'] = array (1=>'Sq. Foot',2=>'Share Unit',3=>'Fixed');
$config['billing_cycle'] = array (1=> "Monthly", 2 => "Two Month Once", 3 => "Quarterly", 4 => "Four Month Once",  5 => "Half Yearly", 6=>"Yearly");
$config['charg_cat'] = array (1=>'Caculation based on',2=>'Sinking Fund %',3=>'Value Per Sq. Foot',4=>'Value Per Share Unit',5=>'Monthly Billing');
$config['prem_quit_cat'] = array (1=>'Insurance Premium',2=>'Quit Rent');

// config for Source of Assignment
$config['defect_filter_status'] = array("all"=>"All","O"=>"Open","C"=>"Closed");


$config['asset_category'] = array('1'=>'Machinery','2'=>'Door & Lock Accessories','3'=>'Fire Equipment','4'=>'Electrical with Mechanical & Electrical (M&E)','5'=>'Communication','6'=>'Software','7'=>'Lighting','8'=>'Bathroom Accessories','9'=>'Office Equipment & Asset','10'=>'Gymnasium Equiment');

$config['asset_warranty_remin'] = array(1 => "1 Day", 2 => "2 Days", 3 => "3 Days", 4 => "4 Days", 5 => "5 Days", 6 => "6 Days", 7 => "1 Week", 8=>"2 Weeks",9=>"3 Weeks",10=>"1 Month",11=>"2 Months",12=>"3 Months");

$config['asset_service_name'] = array(1 => "Service", 2 => "Calibration");

$config['asset_service_period'] = array(1 => "Daily", 2 => "Weekly", 3 => "Two Week Once", 4 => "Three Week Once", 5 => "Monthly", 6 => "Two Month Once", 7 => "Three Month Once", 8=>"Six Month Once",9=>"Yearly",10=>"Two Years");
$config['asset_service_period_days'] = array(1 => 1, 2 => 7, 3 => 15, 4 => 21, 5 => 30, 6 => 60, 7 => 90, 8=>180,9=>365,10=>730);



// AGM / EGM 
$config['agm_types'] = array(1 => "AGM", 2 => "EGM");

$config['agm_remin'] = array(1 => "1 Day", 2 => "2 Days", 3 => "3 Days", 4 => "4 Days", 5 => "5 Days", 6 => "6 Days", 7 => "1 Week", 8=>"2 Weeks",9=>"3 Weeks",10=>"1 Month",11=>"2 Months",12=>"3 Months");

$config['meeting_remin'] = array(1 => "1 Day", 2 => "2 Days", 3 => "3 Days", 4 => "4 Days", 5 => "5 Days", 6 => "6 Days", 7 => "1 Week", 8=>"2 Weeks",9=>"3 Weeks",10=>"1 Month",11=>"2 Months",12=>"3 Months");


// service provider
//$config['service_provider_cat'] = array (1 => "Cleaners", 2=>'Landscaping', 3 => "Security");
$config['service_provider_remin'] = array(1 => "1 Day", 2 => "2 Days", 3 => "3 Days", 4 => "4 Days", 5 => "5 Days", 6 => "6 Days", 7 => "1 Week", 8=>"2 Weeks",9=>"3 Weeks",10=>"1 Month",11=>"2 Months",12=>"3 Months");
$config['service_provider_billing_cycle'] = array(1 => "Monthly", 2 => "2 Month Once", 3 => "Quarterly", 4 => "Half Yearly", 5=>"Yearly");
// section to setup designation based access (hard coded)           
// $config['property_access_desi_id'] = array (1,2,3,7,14,15,25); 

// section to setup the designation for access download property document     
$config['prop_doc_download_desi_id'] = array (1,2,3,7,9,11,13,14,15,17,18,19,20,21,25,27,29); 

// section to setup attendance in and out type           
$config['attendance_capture_for'] = array("1"=>"In Time","2"=>"Out Time","3"=>"Other In","4"=>"Other Out");

// section to setup attendance            
$config['attendance_report_access_desi'] = array(1,2,3,7,14,17,15,20,25);

// section to setup for designation to view all staff attendance 
$config['attend_rep_view_all_access_desi'] = array(1,2,7,14,15,20,25,17,30);//

$config['attend_rep_view_SA_desi'] = array(1,14,15,20,25,17,7,30);

// section to Human Resource            
$config['hr_access_desi'] = array(1,7,14,15,20,25,17);

// for daily report
$config['exclude_desi_for_daily_report'] = array(1,2,7,8,10,13,14,15,16,17,20,22,23,24,25,26,27,28,29,30);
// for dashboard property staff
$config['exclude_desi_for_dashboard'] = array(1,7,8,10,13,14,15,16,17,20,22,23,25,26,27,29,30);
// section to setup User Access Log 
$config['daily_report_access_desi'] = array(1,2,3,7,9,11,14,15,17,18,19,20,21,25,27,29,30);//14,15,7,25

$config['accounts_edit_del_desi'] = array(1,7,10,14,15,23,25,27,28);

// staff evaluation
$config['staff_eval_desi'] = array(3,4,6,9,11,19);
$config['staff_award_category'] = array('1'=>'BUILDING MANAGER (BM) / BUILDING EXECUTIVE (BE)','2'=>'ADMIN EXECUTIVE / ADMIN CLERK','3'=>'TECHNICIAN / HANDYMAN');
$config['staff_desi_award_cat_1'] = array (3,9); 
$config['staff_desi_award_cat_2'] = array (11,19);
$config['staff_desi_award_cat_3'] = array (4,6);

// section to setup User Access Log 
$config['user_access_log_access_desi'] = array(14,15,7);

//$config['unit_status'] = array('Occupied Owner','Unoccupied','Occupied Tenant','Commercial Unit');
$config['unit_type'] = array(1=>'Individual',2=>'Company');
$config['vehicle_type'] = array (1=>'Car',2=>'Motorcycle');
$config['charges_mand_name'] = array (1=>'Service Charge',2=>'Sinking Fund',3=>'Insurance Premium',4=>'Quit Rent');
$config['pay_by'] = array (1=>'Owner',2=>'Tenant');
$config['parking_type'] = array (1=>'Owned',2=>'Rented');
$config['access_card_type'] = array (1=>'All Access',2=>'Vehicle Access',3=>'Walk Access');
$config['race'] = array('Chinese','Indian','Malay','Others');
$config['religion'] = array('Buddhist','Christian','Hindu','Islam','Others');

$config['gender'] = array('Female','Male');

$config['collec_type'] = array('sc_sf_collec'=>'Daily Total Collections for SC&SF',
                               'oth_char_collec'=>'Daily Total Collections of Other Charges',
                               'deposit_collec'=>'Daily Total Collections for Deposits',
                               'cheq_collec'=>'Daily Total Cheque Collection',
                               'cash_collec'=>'Daily Total Cash Collection',
                               'ibg_collec'=>'Daily Total IBG Collection',
                               'cre_card_collec'=>'Daily Total Credit Card Collection',
                               'total_collec'=>'Daily Total Collection');

$config['r_task_schedule'] = array(1 => "Daily", 2 => "Two Week Once", 3 => "Three Week Once", 4 => "Monthly", 5 => "Quarterly", 6 => "Half Yearly", 7 => "Annually");

$config['countries'] = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein",  
                             "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

$config['daily_report_path'] = 'bms_uploads/daily_reports/';
// File Upload configuration

$config ['task_file_upload'] = array (
                                'upload_path' => 'bms_uploads/task_uploads/',  
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );
                                
$config ['task_file_upload_temp'] = array (
                                'upload_path' => 'bms_uploads/task_uploads_temp/',  
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );


$config ['defect_file_upload'] = array (
    'upload_path' => 'bms_uploads/defect_uploads/',
    'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
    'file_ext_tolower' => true
);

$config ['defect_file_upload_temp'] = array (
    'upload_path' => 'bms_uploads/defect_uploads_temp/',
    'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
    'file_ext_tolower' => true
);






$config ['incident_file_upload'] = array (
    'upload_path' => 'bms_uploads/incident_uploads/',
    'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
    'file_ext_tolower' => true
);

$config ['incident_file_upload_temp'] = array (
    'upload_path' => 'bms_uploads/incident_uploads_temp/',
    'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
    'file_ext_tolower' => true
);
                                
$config ['common_docs_upload'] = array (
                                'upload_path' => 'bms_uploads/document_center/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xslx|ppt|pptx',
                                'file_ext_tolower' => true                                
                                );                                
                                
/*$config ['routine_task_file_upload_temp'] = array (
                                'upload_path' => 'bms_uploads/task_uploads_temp/',  
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );      */                          

$config ['property_logo_upload'] = array (
                                'upload_path' => 'bms_uploads/property_logo/',
                                'allowed_types'=> 'gif|jpg|jpeg|png',
                                'file_ext_tolower' => true                                
                                );
                                                                
$config ['property_docs_upload'] = array (
                                'upload_path' => 'bms_uploads/property_docs/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );
                                
$config ['asset_service_entry_docs_upload'] = array (
                                'upload_path' => 'bms_uploads/asset_service_entry_docs/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                ); 
                                
$config ['annual_renewal_docs_upload'] = array (
                                'upload_path' => 'bms_uploads/annual_renewal_docs/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                ); 
                                                                                                
$config ['asset_maint_cont_docs_upload'] = array (
                                'upload_path' => 'bms_uploads/asset_maint_cont_docs/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );  
$config ['service_provider_attach_upload'] = array (
                                'upload_path' => 'bms_uploads/service_provider_attach/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );  
                                                                
$config ['resident_notice_attach_upload'] = array (
                                'upload_path' => 'bms_uploads/resident_notice_attach/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );                                                                                           
                                
$config ['e_notice_attach_upload'] = array (
                                'upload_path' => 'bms_uploads/e_notice_attach/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );                                
                                
$config ['attendance_capture_upload'] = array (
                                'upload_path' => 'bms_uploads/atten_captures/',
                                'allowed_types'=> 'jpg',
                                'file_ext_tolower' => true                                
                                );

$config ['task_forum_upload'] = array (
                                'upload_path' => 'bms_uploads/task_forum_upload/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );

$config ['defect_forum_upload'] = array (
                                'upload_path' => 'bms_uploads/defect_forum_upload/',
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true
                                );

$config ['sop_entry_upload'] = array (
                                'upload_path' => 'bms_uploads/sop_entry_upload/',
                                'allowed_types'=> 'gif|jpg|jpeg|png',
                                'file_ext_tolower' => true                                
                                );
                                
$config ['sop_sub_entry_upload'] = array (
                                'upload_path' => 'bms_uploads/sop_sub_entry_upload/',
                                'allowed_types'=> 'gif|jpg|jpeg|png',
                                'file_ext_tolower' => true                                
                                );                                                                                            

$config ['agm_atten_upload'] = array (
                                'upload_path' => 'bms_uploads/agm_attendance/',  
                                'allowed_types'=> 'gif|jpg|jpeg|png|pdf',
                                'file_ext_tolower' => true                                
                                );

$config ['tsp_document_upload'] = array (
                                'upload_path' => 'bms_uploads/tsp_document/',
                                'allowed_types'=> '*',
                                'file_ext_tolower' => true
);

$config ['facility_picture_upload'] = array (
    'upload_path' => 'bms_uploads/facility_pictures/',
    'allowed_types'=> 'gif|jpg|jpeg|png',
    'file_ext_tolower' => true
);


$config['monthly_report_path'] = 'bms_uploads/monthly_reports/';

$config['soa_report_path'] = 'bms_uploads/accounts/soa';

/**
 *  Accounts Module Configs
 *  
 * */
 
$config['period_format'] = array('mmm-yy','yyyy','yyyy/yy');
 
$config['payment_mode'] = array (1=>'CASH', 2=>'CHEQUE', 3=>'CARD', 4=>'ONLINE/CDM', 5=>'CONTRA DEPOSIT');

$config['card_type'] = array (1=>'Master', 2=>'VISA', 3=>'Amex');

$config['online_type'] = array (1=>'CDM Cash', 2=>'CDM Cheque', 3=>'Internet Banking', 4=>'ATM');

$config['bank_acc_type'] = array (1=>'Maintenance Account', 2=>'Sinking Fund Account', 3=>'Current Account', 4=>'Fix Deposit Account', 5=>'Other Account');

$config['pg_bank_code'] = array('ABB0233'=>'Affin Bank Berhad','ABMB0212'=>'Alliance Bank Malaysia Berhad',
                             'AMBB0209'=>'AmBank Malaysia Berhad','BIMB0340'=>'Bank Islam Malaysia Berhad',
                             'BKRM0602'=>'Bank Kerjasama Rakyat Malaysia Berhad','BMMB0341'=>'Bank Muamalat Malaysia Berhad',
                             'BSN0601'=>'Bank Simpanan Nasional','BCBB0235'=>'CIMB Bank Berhad',
                             'HLB0224'=>'Hong Leong Bank Berhad','HSBC0223'=>'HSBC Bank Malaysia Berhad',
                             'KFH0346'=>'Kuwait Finance House (Malaysia) Berhad','MB2U0227'=>'Malayan Banking Berhad (M2U)',
                             'MBB0228'=>'Malayan Banking Berhad (M2E)','OCBC0229'=>'OCBC Bank Malaysia Berhad',
                             'PBB0233'=>'Public Bank Berhad','RHB0218'=>'RHB Bank Berhad',
                             'SCB0216'=>'Standard Chartered Bank','UOB0226'=>'United Overseas Bank',
                             'TEST0021'=>'SBI Bank A');

$config['sfs_question_input_type'] = array ('Number', 'Radio', 'Date', 'Textarea', 'Image', 'Checkbox', 'Textbox', 'Others radio textbox', 'Others checkbox textbox');

if(isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] == 'propertybutler.my' || $_SERVER['SERVER_NAME'] == 'www.propertybutler.my')) {
    $upload_path = '/home/propertybutler/services.propertybutler.my/sfs_uploads/';
    $upload_path_output = 'https://services.propertybutler.my/sfs_uploads/';
}  else {
    $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/services/v1/sfs_uploads/';
    $upload_path_output = 'http://' . $_SERVER['SERVER_NAME']. '/services/v1/sfs_uploads/';
}

$config ['sfs_category_picture_upload'] = array (
    'upload_path' => $upload_path . 'categories/',
    'upload_path_output' => $upload_path_output . 'categories/',
    'allowed_types'=> 'gif|jpg|jpeg|png',
    'file_ext_tolower' => true
);

$config ['sfs_service_picture_upload'] = array (
    'upload_path' => $upload_path. 'services/',
    'upload_path_output' => $upload_path_output . 'services/',
    'allowed_types'=> 'gif|jpg|jpeg|png',
    'file_ext_tolower' => true
);