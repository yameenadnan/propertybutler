<?php include_once('agm_voter_header.php');?>
<div class="row">
                    <form id="change_password" action="<?php echo base_url('index.php/bms_agm_egm_vote/agm_change_password_submit');?>" method="post" class="form-horizontal">
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
                        <button id="bGodkend" type="submit" name="bGodkend" class="btn btn-success">Submit</button> &ensp;
                        <button  type="Reset" id="bCancel" name="bCancel" class="btn btn-default">Reset</button> 
                        
                      </div>
                    </div>
                    
                    </fieldset>
                    </form>
        
                </div>
                
                
<?php include_once('agm_voter_footer.php');?>
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
                        url: "<?php echo base_url('index.php/bms_agm_egm_vote/agm_check_password');?>",
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
</body>
</html>