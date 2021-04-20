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
    
    
    <form name="bms_frm" id="bms_frm" action="<?php echo base_url('index.php/bms_direct_pay/process_pay');?>" method="post" autocomplete="off">
    
        <input type="hidden" name="property_id" value="<?php echo $prop_details['property_id'];?>" />
        <input type="hidden" name="pymt_gateway_url" value="<?php echo $prop_details['pymt_gateway_url'];?>" />
        <input type="hidden" name="payment_bear_by" value="<?php echo $prop_details['payment_bear_by'];?>" />
        <input type="hidden" name="payment_fpx" value="<?php echo $prop_details['payment_fpx'];?>" />
        <input type="hidden" name="payment_cc_card" value="<?php echo $prop_details['payment_cc_card'];?>" />
        <input type="hidden" name="payment_merchant_id" value="<?php echo $prop_details['payment_merchant_id'];?>" />
        <input type="hidden" name="payment_merchant_key_index" value="<?php echo $prop_details['payment_merchant_key_index'];?>" />
        <input type="hidden" name="payment_merchant_key" value="<?php echo $prop_details['payment_merchant_key'];?>" />
        <input type="hidden" name="tid" value="<?php echo $tid;?>" />
      
        <?php if(!empty($units)) { ?>
        <div class="form-group purple-border">
          <label>Unit No :</label>
          <select name="unit_id" class="form-control select2" id="unit_id">
            <option value="">Select</option>
            <?php            
                foreach ($units as $key=>$val) {                    
                    echo "<option value='".$val['unit_id']."' data-email='".$val['email_addr']."' >".$val['unit_no']."</option>";
                }           
            ?>
          </select>
        </div>
        <?php } ?>
        
        <div class="form-group purple-border">
          <label>Amount :</label>
          <input type="text" name="amount" id="amount" class="form-control txt_custom" value=""  />
        </div>
        
        <div class="form-group purple-border">
          <label >Reference:</label>
          <textarea class="form-control txt_custom" name="reference" id="reference" rows="3"><?php echo $tid == 'clamp' ? 'Clamping charge' :($tid == 'ovp' ? 'Over night parking charge' : '')  ;?></textarea>
          <b>Note:</b> Please enter your invoice number or payment reference here.
        </div>
        <div class="form-group purple-border">
          <label>Email Address :</label>
          <input type="text" name="email" id="email" class="form-control txt_custom" value="" />
        </div>
        <div style="clear: both;height: 1px;"></div>
        <div class="row">
        <div class="col-md-12 col-sm-12 " style="margin: 10px 0;">
          <div class="col-md-12 col-sm-12 no-padding"  >
            <label>Payment Mode : </label>
          </div>
          <div class="col-md-12 col-sm-12 no-padding"  >
          
              <div class="col-md-8 col-sm-8 col-xs-8 no-padding"  >
                  <label class="radio-inline"  >
                      <input type="radio" name="Payment_ID" value="2"> Credit / Debit Card
                  </label>
                  <span style="display:block;font-weight: bold;">(Process Fee:<span id="cd_char_per"><?php echo trim($prop_details['payment_cc_card'],0);?></span>%)</span>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4 no-padding text-right cd_amt" >&nbsp;</div>
          </div>
          <div class="col-md-12 no-padding" style="padding-top: 10px !important;" >
             
              <div class="col-md-8 col-sm-8 col-xs-8 no-padding">
                <label class="radio-inline"  >
                  <input type="radio" name="Payment_ID" value="3">FPX
                 </label>  
                 <span style="display:block;font-weight: bold;">(Process Fee:RM <span id="fpx_char"><?php echo $prop_details['payment_fpx'];?></span>)</span>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4 no-padding text-right fpx_amt" style="vertical-align: top;">&nbsp;</div>
              
          </div>  
        </div>
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
        </div>
      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat txt_custom">Pay Now</button>
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
    $('.msg_div').fadeOut(5000);
    
    /** Form validation */    
    $( "#bms_frm" ).validate({
				rules: {
					"unit_id": "required" ,
					"amount": "required",
                    "reference": "required",
                    "email": {
					   required: true,
					   email: true
					},
                    "Payment_ID": "required",
                    "Bank_Code": "required"	
                },                    
				messages: {
					"unit_id": "Please select Unit No",
					"amount": "Please enter Amount",
                    "reference": "Please enter Reference",
                    "email": {
					   required:"Please enter Email Address",
                       email: "Please enter valid Email Address"
					   },
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
            
        $('#unit_id').change (function () {
            
            if(typeof($(this).find('option:selected').data('email')) != 'undefined') {
                $('#email').val($(this).find('option:selected').data('email'));  
                $('#email-error').css('display','none');              
            } else {
                $('#email').val('');
            }
        });
        
        $('input:radio[name="Payment_ID"]').change(function(){
            if($('#amount').val() == '') {
                alert('Please enter the amount!'); $('#amount').focus();$('input:radio[name="Payment_ID"]').prop('checked', false);return false;
            } 
            //console.log('test');
            if ($(this).is(':checked') && $(this).val() == '2') {
                $('.banks').slideUp();//.css('display','none');
                /*var p_fee_amt = parseFloat('<?php echo $_POST['amount'];?>')*parseFloat('<?php echo $prop_details['payment_cc_card'];?>')/100;
                $('#total_amt').html(parseFloat(parseFloat('<?php echo $_POST['amount'];?>')+p_fee_amt).toFixed(2));
                $('#process_fee_amt').html('RM '+p_fee_amt);
                $('input:hidden[name="Amount"]').val($('#total_amt').html());*/
            } else if ($(this).is(':checked') && $(this).val() == '3') {
                $('.banks').slideDown();//.css('display','block');
                //$('.banks').addClass('col-xs-6');
                /*var p_fee_amt = parseFloat('<?php echo $prop_details['payment_fpx'];?>');
                $('#total_amt').html(parseFloat(parseFloat('<?php echo $_POST['amount'];?>')+p_fee_amt).toFixed(2));                
                $('#process_fee_amt').html('RM '+p_fee_amt);
                $('input:hidden[name="Amount"]').val($('#total_amt').html());*/
            }
        });
        $('#amount').keyup(function(){
            
            var p_fee_amt = ((parseFloat($('#amount').val())*parseFloat($('#cd_char_per').html()))/100).toFixed(2); 
            $('.cd_amt').html('RM '+(parseFloat($('#amount').val())+parseFloat(p_fee_amt)).toFixed(2)); 
            var p_fee_amt = (parseFloat($('#amount').val())+parseFloat($('#fpx_char').html())).toFixed(2); 
            $('.fpx_amt').html('RM '+p_fee_amt);                       
        });
        $('input:radio[name="Payment_ID"]').change(function(){
            
        });
    
   }); 
  
  
  /*$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '10%' 
    });
  });*/
  
</script>
</body>
</html>