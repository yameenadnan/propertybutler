<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
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
        
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <div class="box-body">
            <div class="row">
                    <form id="bms_frm" action="<?php echo base_url('index.php/bms_home_butler/vendor_cat_submit');?>" method="post" class="form-horizontal">
                    <fieldset>
                    
                    <!-- Form Name -->
                    
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piNewPass">Category Name</label>
                      <div class="col-md-4">
                        <input name="vendor_cat[vendor_cat_name]" type="text" placeholder="Enter Category Name" value="<?php echo !empty($vendor_cat['vendor_cat_name']) ? $vendor_cat['vendor_cat_name'] : '';?>" class="form-control input-md" maxlength="150"  >
                        <input type="hidden" name="vendor_cat_id" value="<?php echo $vendor_cat_id;?>" />     
                      </div>
                    </div>
                    
                    <!-- Button (Double) -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="bCancel"></label>
                      <div class="col-md-8">
                        <button id="bGodkend" type="submit"  class="btn btn-primary">Save</button> &ensp;
                        <button  type="Reset" id="bCancel" class="btn btn-default">Reset</button> 
                        
                      </div>
                    </div>
                    
                    </fieldset>
                    </form>
        
                </div>
          
          </div>
          
       </div>       
              
    </section> <!-- /.content -->
 
  </div> <!-- /.content-wrapper -->
<?php $this->load->view('footer');?> 
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script> 
<script>
$(document).ready(function () {
    
    //$('#current_pass').focus();
    $('.msg_notification').fadeOut(3000);
    
    /** Form validation */    
    $( "#bms_frm" ).validate({
			rules: {				
			    "vendor_cat[vendor_cat_name]": "required"                
			},
			messages: {				
			    "vendor_cat[vendor_cat_name]": "Please enter Category Name"
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else if ( element.prop( "id" ) === "datepicker" ) {
				    error.insertAfter( element.parent( "div" ) );
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