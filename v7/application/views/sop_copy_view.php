<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
            <!--small>Optional description</small-->
        </h1>      
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
        <div class="box box-primary">
        
        <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }
        
        ?>
            
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_sop/sop_copy_act');?>" method="post" >
                  
                <div class="box-body">
                    <!--div class="box-header with-border" style="padding: 10px 0;">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task</h3>
                    </div-->
                  
                    <div class="row">
                        <div class="col-md-2 col-xs-2" style="padding-top: 10px;padding-left: 25px;">
                            <label >From</label>
                        </div>
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group">
                              
                                <select class="form-control" id="from_property_id" name="from_property_id" >                              
                                <option value="">Property From</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {                                        
                                        
                                        echo "<option value='".$val['property_id']."' >".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                                            
                        <div class="col-md-5 col-xs-6">
                            <div class="form-group">
                              
                              <select name="from_designation" class="form-control">
                                <option value="">Designation From</option>
                                <?php 
                                    foreach ($assign_to as $key=>$val) {
                                        
                                        echo "<option value='".$val['desi_id']."'>".$val['desi_name']."</option>";
                                    } ?> 
                              </select>
                            </div>
                        </div> 
                        
                        <div class="col-md-2 col-xs-2" style="padding-top: 10px;padding-left: 25px;">
                            <label >To</label>
                        </div>
                        <div class="col-md-5 col-xs-12">
                            <div class="form-group">
                              
                                <select class="form-control" id="to_property_id" name="to_property_id" >                              
                                <option value="">Property To</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {                                        
                                        
                                        echo "<option value='".$val['property_id']."'>".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                                            
                        <div class="col-md-5 col-xs-6">
                            <div class="form-group">
                              
                              <select name="to_designation" class="form-control">
                                <option value="">Designation To</option>
                                <?php 
                                    foreach ($assign_to as $key=>$val) {
                                        echo "<option value='".$val['desi_id']."'>".$val['desi_name']."</option>";
                                    } ?> 
                              </select>
                            </div>
                        </div>                                            
                    
                </div>
                
                
                
                
                
                
                
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Copy</button> &ensp;
                    <button type="Reset" class="btn btn-default">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
            
            
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once('footer.php');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    /** Form validation */
    
    $("#bms_frm" ).validate({
		rules: {
			"from_property_id": "required",					
            "from_designation":"required",
            "to_property_id": "required",					
            "to_designation":"required"            
		},
		messages: {
			"from_property_id": "Please select Property From",					
            "from_designation":"Please select Designation From",
            "to_property_id": "Please select Property To",					
            "to_designation":"Please select Designation To"            
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