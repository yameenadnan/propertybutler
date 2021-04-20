<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  

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
                    <form id="bms_frm" action="<?php echo base_url('index.php/bms_human_resource/holiday_submit');?>" method="post" class="form-horizontal">
                    <fieldset>
                    
                    <!-- Form Name -->
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piCurrPass">Date</label>
                      <div class="col-md-4">                                                          
                            
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right" name="holiday[date]"  id="datepicker" type="text" value="<?php echo !empty($holiday['date']) ? date('d-m-Y',strtotime($holiday['date'])) : '';?>" />
                            </div> <!-- /.input group -->
                            <input type="hidden" name="holiday_id" value="<?php echo $holiday_id;?>" />              
                      </div>
                    </div>
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piNewPass">Description</label>
                      <div class="col-md-4">
                        <input name="holiday[description]" type="text" placeholder="Enter the Description" value="<?php echo !empty($holiday['description']) ? $holiday['description'] : '';?>" class="form-control input-md" maxlength="150"  >
                        
                      </div>
                    </div>
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="piNewPassRepeat">State</label>
                      <div class="col-md-4">
                        
                        <select class="form-control" id="state_id" name="holiday[state_id]">
                            <option value="">Select State</option>
                            <?php 
                                foreach ($states as $key=>$val) { 
                                    $selected = !empty($holiday['state_id']) && $holiday['state_id'] == $val['state_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['state_id']."' ".$selected." >".$val['state_name']."</option>";
                                } ?> 
                        </select>
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
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script> 
<script>
$(document).ready(function () {
    
    //$('#current_pass').focus();
    $('.msg_notification').fadeOut(3000);
    
    /** Form validation */    
    $( "#bms_frm" ).validate({
			rules: {
				"holiday[date]": "required",
			    "holiday[description]": "required",
                "holiday[state_id]": "required"
			},
			messages: {
				"holiday[date]": "Please select Date",
			    "holiday[description]": "Please enter Description",
                "holiday[state_id]": "Please select State"
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

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
</script>