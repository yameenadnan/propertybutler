<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-primary">
        <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }
        
        ?>
        
        <?php if(isset($_SESSION['error_msg']) && trim( $_SESSION['error_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-danger msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['error_msg'].'</div>'; 
            unset($_SESSION['error_msg']);
        }
        if(isset($_GET['changed']) && $_GET['changed'] == '1') {
            echo '<meta http-equiv="refresh" content="2;url='.base_url('index.php/bms_index/logout').'" />';
        } else {
        ?>
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="box-body">
            <div class="row">
                    <form id="change_password" action="<?php echo base_url('index.php/bms_index/change_password_submit');?>" method="post" class="form-horizontal">
                    <fieldset>
                    
                    <!-- Form Name -->
                    
                    <!-- Password input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piCurrPass">Current Password</label>
                      <div class="col-md-4">
                        <input id="current_pass" name="current_pass" type="password" placeholder="" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    
                    <!-- Password input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piNewPass">New Password</label>
                      <div class="col-md-4">
                        <input id="new_pass" name="new_pass" type="password" placeholder="" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    
                    <!-- Password input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piNewPassRepeat">Confirm Password</label>
                      <div class="col-md-4">
                        <input id="confirm_pass" name="confirm_pass" type="password" placeholder="" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    
                    <!-- Button (Double) -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="bCancel"></label>
                      <div class="col-md-8">
                        <button id="bGodkend" type="submit" name="bGodkend" class="btn btn-success">Save</button> &ensp;
                        <button  type="Reset" id="bCancel" name="bCancel" class="btn btn-default">Reset</button> 
                        
                      </div>
                    </div>
                    
                    </fieldset>
                    </form>
        
                </div>
          
          </div>
          <?php } ?>
       </div>       
              
    </section> <!-- /.content -->
 
  </div> <!-- /.content-wrapper -->
<?php include_once('footer.php');?> 
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script> 
<script>
$(document).ready(function () {
    
    $('#current_pass').focus();
    $('.msg_notification').fadeOut(3000);
    
    /** Form validation */    
    $( "#change_password" ).validate({
				rules: {
					current_pass:{
        			   required: true,        			  
                       remote: {
                        url: "<?php echo base_url('index.php/bms_index/check_password');?>",
                        type: "post",
                        data: {
                          current_pass: function() {
                            return $( "#current_pass" ).val();
                          }
                        }
                      }                       
        			},
					"new_pass": {
					   required: true,
                       minlength:6
					},
                    "confirm_pass":{
                        required: true,
                        equalTo: "#new_pass" 
                    }                   
				},
				messages: {
					"current_pass":{
        			   required:"Please enter Current Password",                       
                       remote:"Your current passwors is wrong!"
        			   },
					"new_pass": {
					       required: "Please enter New Password",
                           minlength: "Required  minimum 6 Characters" 
		             },
                    "confirm_pass":{
                        required:"Please enter Confirm Password",
                        equalTo: "Confirm Password is not matching with New Password!"
                    }
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
</script>