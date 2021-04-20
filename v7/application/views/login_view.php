<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler' ;?></title>
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
</style>
</head>

 <body class="hold-transition login-page" style="_background-color: #19194D;">
<div class="login-box">
  <div class="login-logo">
    <a href="https://itechms.my/"><img src="<?php echo base_url();?>assets/images/2.png" alt="Property Butler" class="img-responsive center-block" /></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    
    <?php // Login failure message
        if(isset($_GET['login_err']) && trim( $_GET['login_err'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-danger log_err_msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.($_GET['login_err'] == 'invalid' ? 'Invalid username / Password!' : 'Please enter login credentials!').'</div>';
        }
    
    ?>
    
    
    <form name="bms_login" id="bms_login" action="<?php echo base_url('index.php/bms_index/login_action').(isset($_GET['return_url']) && $_GET['return_url'] != '' ? '?return_url='.$_GET['return_url'] : '');?>" method="post">
      <div class="form-group has-feedback">
        <input type="email" id="username" name="username" class="form-control" placeholder="Email" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : '';?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        
       
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="pass" class="form-control" placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : '';?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label class="">
              <div class="icheckbox_square-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
                <input type="checkbox" name="remember" value="rememberme" <?php echo isset($_COOKIE['username']) ? 'checked="checked"' : '';?> style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;"></ins>
              </div> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="#">I forgot my password</a>
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
<script>
  $(document).ready(function () {
    
    $('#username').focus();
    $('.log_err_msg').fadeOut(5000);
    /*$('#username').keypress(function () {        
        if($('.log_err_msg').length > 0) { }
    });*/
    /** Form validation */
    
    $( "#bms_login" ).validate({
				rules: {
					"username": {
					   required: true,
					   email: true
					},
					"pass": "required"
				},
				messages: {
					"username": {
					   required:"Please enter Email",
                       email: "Please enter  valid Email"
					   },
					"pass": "Please enter Password"
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
  
  
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
  
  
  
</script>
</body>
</html>