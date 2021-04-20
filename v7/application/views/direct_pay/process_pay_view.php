<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler';?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/iCheck/square/blue.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_custom_styles.css">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
        a, a:active, a:focus, a:link {  outline: none !important; } 
        a:hover,a:active,a:visited, a:link  { text-decoration: none;}
        
        .txt_custom {
      -webkit-border-radius: 5px !important;
         -moz-border-radius: 5px !important;
              border-radius: 5px !important;
              
         -webkit-border-color: #333 !important;
         -moz-border-color: #333 !important;
              border-color: #333 !important;
      
    }
</style>
</head>

 <body class="hold-transition login-page" style="background-color: #FFF;">
<div class="login-box" style="margin-top: 25px;">
  
  <!-- /.login-logo -->
  <div class="login-box-body">    <?php //echo  base_url('index.php/bms_direct_pay/paymentCompleted'); ?>
    <form name="bms_frm" id="bms_frm" action="<?php echo $_POST['pymt_gateway_url'];?>" method="post" autocomplete="off">
    
        <input type="hidden" name="Revpay_Merchant_ID" value="<?php echo $_POST['payment_merchant_id'];?>" />
        <input type="hidden" name="Reference_Number" value="<?php echo $ref_no; ?>" />
        
        <input type="hidden" name="Key_Index" value="<?php echo $_POST['payment_merchant_key_index'];?>" />
        <input type="hidden" name="Currency" value="MYR" />
        <input type="hidden" name="Signature" value="<?php echo $signature;?>" />
        <input type="hidden" name="Transaction_Description" value="<?php echo $_POST['reference'];?>" />
        <input type="hidden" name="Return_URL" value="<?php echo base_url('index.php/bms_direct_pay/paymentCompleted').'/'.$_POST['property_id'].(isset($_POST['unit_id']) ? '/'.$_POST['unit_id'] :'') . '?email='.$_POST['email'].'&tid='.$_POST['tid'];?>" />
        <input type="hidden" name="Customer_IP" value="<?php echo $_SERVER['REMOTE_ADDR'];?>" />
        <input type="hidden" name="Customer_Name" value="" />
        <input type="hidden" name="Customer_Email" value="<?php echo $_POST['email'];?>" />
        <input type="hidden" name="Customer_Contact" value="" />
        <input type="hidden" name="Amount" value="<?php echo $_POST['amount']+$process_fee; ?>" />
        <input type="hidden" name="Payment_ID" value="<?php echo $_POST['Payment_ID'];?>" />
        <?php if($_POST['Payment_ID'] == 3) {
            echo '<input type="hidden" name="Bank_Code" value="'.$_POST['Bank_Code'].'" />';
        } ?>
        
        <div class="form-group" style="margin: 15px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Amount</label>
            <div class="col-xs-6 text-right">
                RM <span id="amount"><?php echo number_format($_POST['amount'],2); ?>
            </div>
        </div>
        
        <div style="clear: both;height: 1px;"></div>
        <div class="form-group" style="margin: 10px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Process Fee
                <span id="process_fee_per"></span>
            </label>
            <div class="col-xs-6 text-right" id="process_fee_amt">
            <?php echo $process_fee > 0 ? 'RM '.number_format($process_fee,2) : '-'; ?>  
            </div>
        </div>
        
        <div style="clear: both;height: 1px;"></div>
        <div class="form-group" style="margin: 25px 0 !important;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Payable Amount</label>
            <div class="col-xs-6 text-right">
                RM <span id="total_amt"><?php echo number_format($_POST['amount']+$process_fee,2); ?></span>
            </div>
        </div>
        
        <div style="clear: both;height: 1px;"></div>
        <div class="form-group" style="margin: 25px 0 !important;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Payment Mode</label>
            <div class="col-xs-6 text-right">
                <?php echo $_POST['Payment_ID'] == 2 ? 'Credit / Debit Card' : ($_POST['Payment_ID'] == 3 ? 'FPX' : '-'); ?>  
            </div>
        </div>
        
        <div style="clear: both;height: 1px;"></div>
        <!--div class="form-group" style="margin: 10px 0;">
          <label>Payment Mode : </label>  <br />
          <label class="radio-inline"  >
              <input type="radio" name="Payment_ID" value="2"> Credit / Debit Card<b>(RM<?php echo $prop_details['payment_cc_card'];?>%)</b>
          </label>
          <label class="radio-inline">
              <input type="radio" name="Payment_ID" value="3">FPX <b>(RM<?php echo $prop_details['payment_fpx'];?>)</b>  
          </label>
            
        </div>
        <div style="clear: both;height: 1px;"></div>
        <div class="form-group purple-border banks" style="margin: 10px 0; ">
          <label>Bank :</label>
          <select name="Bank_Code" class="form-control select2" id="bank_code">
            <option value="">Select</option>
            <?php            
                foreach ($bank as $key=>$val) {                    
                    echo "<option value='".$key."' >".$val."</option>";
                }           
            ?>
          </select>
        </div-->
        
      <div class="row" style="margin-top:25px ;">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat txt_custom">Proceed</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>
<script>
  $(document).ready(function () {
    
    $('.select2').select2();
    $('.banks').css('display','none');
    /** Form validation */
    $( "#bms_frm" ).validate({
				rules: {
					"Payment_ID": "required",
                    "Bank_Code": "required"					
                },                    
				messages: {
					"Payment_ID": "Please select Payment Mode",
                    "Bank_Code": "Please select Bank"                    
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
				}
			});
            
        
    
   }); 
  
  
  /*$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'      
    });
  });*/
  
</script>
</body>
</html>