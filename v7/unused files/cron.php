<?php 
$conn = mysqli_connect('localhost','vitmates_tpbms','FdXVRSlm)c-7','vitmates_transpacc_bms'); 




           // echo "gfgfg";exit;
    
$SiteTitle = "Transpacc";	 
$SiteURL   = "http://tpaccbms.com/"; 
$SiteEmail ="info@mydomain.com";
$mail_subject = "Transpacc";

         $query = "select unit_id,property_id,unit_no,email_addr,square_feet,share_unit,owner_name from bms_property_units where property_id ='153'";
     	 $rs=mysqli_query($conn,$query);
	     $res=mysqli_num_rows($rs);
	// echo $res;exit;
	 
	$i=1;
               while($row = mysqli_fetch_array($rs)){
                  $dbVals =$i++; 
               	 $dbValues = "MB-".str_pad($dbVals, 7, "0", STR_PAD_LEFT);
         //echo $dbValues;exit; 
               $pro = $row['property_id'];
              // echo $pro;exit;
                $unid = $row['unit_id']; 
               // echo $unid;exit;
                $uni = $row['unit_no'];    
                $qua = $row['email_addr'];
                $nam = $row['owner_name'];
                $qu2 = $row['square_feet'];
                $sh = $row['share_unit'];
                $tot = $qu2*$sh;
          
          
           $sq= "select * from bms_meter_reading where unit_no ='$unid' AND type = 'Water'";
           $rss=mysqli_query($conn,$sq);
	       $ress=mysqli_num_rows($rss);
                              while($rows = mysqli_fetch_array($rss)) {
                              
                                     $ca = $rows['type'];
                                     $charg = $rows['charges'];
                                     $ext = $rows['extra_charges'];
                                    //echo $ca; exit;
          
          
                
     $sqq= "select * from bms_meter_reading where unit_no ='$unid' AND type = 'Electricity'";
     $rsss=mysqli_query($conn,$sqq);
	       $resss=mysqli_num_rows($rsss);
	       $j=1;
                             while($rowss = mysqli_fetch_array($rsss)) {
                              
                                     $cap = $rowss['type'];
                                     $charge = $rowss['charges'];
                                     $extr = $rowss['extra_charges'];
                                   //  echo $cap;exit;
                                $total = $tot + $ext +  $extr;      
       //echo $unid;exit;
       
       $data = mysqli_fetch_row(mysqli_query($conn,"SELECT max(id)+1 FROM bms_send_cronjob")); 
       //echo $data[0]; exit;
      
         $dbVal = $data[0]; 
		 $dbValue = "MB-".str_pad($dbVal, 7, "0", STR_PAD_LEFT);
        // echo $dbValue;exit;  
        
 $date = date('Y-m-d');   
 $inndata = "INSERT INTO  bms_send_cronjob (unit_id,property_id,bill_no,unit_no,maintenance,electricity,water,total_amt,send_date) VALUES ('$unid','$pro','$dbValue','$uni','$tot','$extr','$ext','$total','$date')";  
 $rssss=mysqli_query($conn,$inndata);
      // include'CSS/main.css';
      $todays_date = date("Y/m/d");
  $message ='
        
        <html><body style="background: #00c6ff;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;">
           
            <div style="max-width: 560px;padding:0px;background: #ffffff;border-radius: 5px;margin:40px auto;font-family: Open Sans,Helvetica,Arial;font-size: 15px;color: #666;border:1px solid #ccc;">
            <div style=""><h3 style="text-align:center;margin:0px;font-weight:bold;color:#fff;background:#60addf;padding:24px;">Transpacc Bill</h3></div>
            <div style="color: #444444;font-weight: normal;">
            
            <div ><p style="text-align: left;font-weight:600;font-size:26px;padding: 10px 10px;">
            <img src="http://webbinart.tk/diet/account/assets/lg.png" width="90px"></p><p style="float: right;padding-right: 17px;margin-top: -10%;">'.$dbValues.'</p></div>
             
            <div style="clear:both"></div></div>	
           <div ><p style="float: left;padding-left: 11px;">Hello :'.$nam.' </p><p style="float: right;padding-right: 17px;"> '.$todays_date.'</p></div>	
            <div style="margin-top: 4%;"><p style="margin:0px;padding-left:10px;margin-top: 12%;">Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum has been the industry s standard dummy Lorem Ipsum has been the industrys standard dummy </p>	</div>
           <div class="row rws2">
           
				   <table class="table table-sm table-dark" style="color: #fff; background-color: #212529;background-color: #c6c8ca;width: 100%;max-width: 100%;margin-bottom: 1rem;background-color: transparent; margin-top: 4%; ">
                        <tbody>
							<tr style="background:#848181;">
								
								<td class="txtfnt" style="padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;" ><i class="fa fa-dot-circle-o"></i>&nbsp; Maintenance Bill</td>
								<td class="txtfnt" style="padding: .75rem;">'.$tot.'</td>
								
							</tr>
							<tr style="background:#848181;">
											 
									
								<td class="txtfnt" style="padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><i class="fa fa-dot-circle-o"></i>&nbsp;     Water Bill </td>
								<td class="txtfnt" style="padding: .75rem;">'.$ext.'</td>
							</tr>
							<tr style="background:#848181;">
							<td class="txtfnt" style="padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;"><i class="fa fa-dot-circle-o"></i>&nbsp;      Electricity Bill </td>
								<td class="txtfnt" style="padding: .75rem;">'.$extr.'</td>
							</tr>
							
							
								<tr style="background:#848181;">
							<td class="total-all" style="    padding-left: 4%;;padding: .75rem;vertical-align: top;border-top: 1px solid #dee2e6;">Grand Total </td>
								<td class="total-all" style="padding: .75rem;">'.$total.'</td>
							</tr>
							
							
							
							
						</tbody>
					</table>
				   
				   </div>
           
            <div style="padding: 30px 30px 30px 30px;border-bottom: 3px solid #eeeeee;"><p><strong>Thank You</strong><br><span class="sbtrans">Transpacc</span></div>
            <div style="color: #999;padding: 5px 30px;background:#60addf;">
            <div style="text-align:center;"></div><p style="text-align:center;color:#fff;font-weight:bold;">Transpacc.com</p></div></div></body></html>';
            $mail_to = $qua;
          // echo $mail_to;exit;
            $headers = "From: \"$SiteTitle\" <$SiteEmail>\r\n";
            $headers .= "Reply-To: $SiteEmail\r\n";
            $headers .= "Organization: $SiteTitle \r\n";
            $headers .= "X-Sender: $SiteEmail \r\n";
            $headers .= "X-Priority: 3 \r\n";
            $headers .= "X-Mailer: php\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
           $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          // mail($mail_to, $mail_subject, $message, $headers);
           echo $mail_to, $mail_subject, $message;
       
    
  
    
    

          
       
       
                }
                
          
          
                } 
			 
                } $i++;
   
        
    
    

?>

