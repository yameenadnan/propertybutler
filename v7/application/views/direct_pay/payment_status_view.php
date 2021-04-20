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
  <div class="login-box-body">    
  
  <?php 
  if(isset($_POST['response_code']) && $_POST['response_code'] == '00') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><strong>';
                    echo '</strong>Transaction successful!</div>';
                    unset($_SESSION['flash_msg']);
  }
  else if(!isset($_POST['response_code']) || $_POST['response_code'] != '00') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-danger msg_notification"><strong>';
                    echo '</strong>Your transaction was not successful!</div>';
                    unset($_SESSION['flash_msg']);
                }
                
          ?>
            
        <div class="form-group" style="margin: 10px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Transaction Id </label>
            <div class="col-xs-6 text-right no-padding" >
                <?php echo $_POST['transaction_id']; ?>  
            </div>
        </div>
        <div style="clear: both;height: 1px;"></div>
        
        
        <div class="form-group" style="margin: 10px 0;">
            <label class="col-xs-5 control-label" style="padding-left:0;">Reference Number</label>
            <div class="col-xs-7 text-right no-padding" >
                <?php echo $_POST['reference_number']; ?>  
            </div>
        </div>
        <div style="clear: both;height: 1px;"></div>
        <div class="form-group" style="margin: 10px 0;">
            <label class="col-xs-5 control-label" style="padding-left:0;">Transaction Description</label>
            <div class="col-xs-7 text-right no-padding" >
                <?php echo $_POST['trans_descrip']; ?>  
            </div>
        </div>
        <div style="clear: both;height: 1px;"></div>
        
        <?php if (!empty($_POST['pymt_for'])) { ?>
        <div class="form-group" style="margin: 10px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Payment For</label>
            <div class="col-xs-6 text-right no-padding" >
                <?php echo isset($_POST['pymt_for']) && $_POST['pymt_for'] == '1' ? 'Clamping charges' :(isset($_POST['pymt_for']) && $_POST['pymt_for'] == '2' ? 'Over night parking charges' : ''); ?>  
            </div>
        </div>
        <div style="clear: both;height: 1px;"></div>
        <?php } ?>
        
        
        <div class="form-group" style="margin: 15px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Transaction Date</label>
            <div class="col-xs-6 text-right">
                <span id="amount"><?php echo !empty($_POST['response_datetime']) ? date('d-m-Y H:i a', strtotime($_POST['response_datetime'])) : ' - '; ?>
            </div>
        </div>        
        <div style="clear: both;height: 1px;"></div>
        
        <div class="form-group" style="margin: 15px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Amount</label>
            <div class="col-xs-6 text-right">
                RM <span id="amount"><?php echo number_format($_POST['amount'],2); ?>
            </div>
        </div> 
        
        <div style="clear: both;height: 1px;"></div>
        
        <div class="form-group" style="margin: 15px 0;">
            <label class="col-xs-6 control-label" style="padding-left:0;">Transaction Status</label>
            <div class="col-xs-6 text-right">
            <?php 
                if(isset($_POST['response_code']) && $_POST['response_code'] == '00') {
                    echo 'Your transaction successful!';
                } else {
                    echo 'Your transaction failed!';
                }
                
                ?>
                
                
            </div>
        </div>
               
        <div style="clear: both;height: 1px;"></div>
        
        
        
      <div class="row" style="margin-top:25px ;">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="button" class="btn btn-primary btn-block btn-flat txt_custom close_btn">Close</button>
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
    $('.close_btn').click(function () { window.close();});
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
            
        $('input:radio[name="Payment_ID"]').change(function(){
            //console.log('test');
            if ($(this).is(':checked') && $(this).val() == '2') {
                $('.banks').css('display','none');
                var p_fee_amt = parseFloat('<?php echo $_POST['amount'];?>')*parseFloat('<?php echo $prop_details['payment_cc_card'];?>')/100;
                $('#total_amt').html(parseFloat(parseFloat('<?php echo $_POST['amount'];?>')+p_fee_amt).toFixed(2));
                $('#process_fee_amt').html('RM '+p_fee_amt);
                $('input:hidden[name="Amount"]').val($('#total_amt').html());
            } else if ($(this).is(':checked') && $(this).val() == '3') {
                $('.banks').css('display','block');
                //$('.banks').addClass('col-xs-6');
                var p_fee_amt = parseFloat('<?php echo $prop_details['payment_fpx'];?>');
                $('#total_amt').html(parseFloat(parseFloat('<?php echo $_POST['amount'];?>')+p_fee_amt).toFixed(2));                
                $('#process_fee_amt').html('RM '+p_fee_amt);
                $('input:hidden[name="Amount"]').val($('#total_amt').html());
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