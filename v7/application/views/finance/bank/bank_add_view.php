<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="visible-xs">
      <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
    </h1>
    <h1 class="hidden-xs">
      <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
    </h1>
  </section>
  <!-- Main content -->
  <section class="content container-fluid cust-container-fluid">
    <!--------------------------
| Your Page Content Here |
-------------------------->
    <!-- general form elements -->
    <div class="box box-primary">
      <!--div class="box-header with-border">
<h3 class="box-title">Quick Example</h3>
</div-->
      <!-- /.box-header -->
      <div class="box-body" style="padding-top: 15px;">
        <?php if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
            unset($_SESSION['flash_msg']);
            }
            ?>
        
        
        <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 5px;padding:15px;">
          
          <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_banks/bank_submit'); ?>" class="form-horizontal">
            
                    <div class="form-group">
                      <label class="col-md-4 control-label">Property Name *</label>
                          <div class="col-md-4">                                                          
                               <select class="form-control" id="property_id" name="bank[property_id]">
                                <option value="">Select</option>
                                <?php 
                                foreach ($properties as $key=>$val) {
                                    $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                    echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                } ?> 
                              </select>
                              <!-- Hidden fields -->
                              <input type="hidden" name="bank[bank_id]" value="<?php echo !empty($bank['bank_id']) ? $bank['bank_id'] : '';?>" />
                              
                               
                      </div>
                    </div>
                    
                    
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label">Bank Name *</label>
                      <div class="col-md-4">
                        <input name="bank[bank_name]" type="text" placeholder="Enter Bank Name" value="<?php echo !empty($bank['bank_name']) ? $bank['bank_name'] : '';?>" class="form-control input-md" maxlength="150"  >
                        
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-4 control-label" >Account Number *</label>
                      <div class="col-md-4">
                        <input name="bank[acc_no]" type="text" placeholder="Enter Account Number" value="<?php echo !empty($bank['acc_no']) ? $bank['acc_no'] : '';?>" class="form-control input-md" maxlength="50"  >
                        
                      </div>
                    </div>
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label">Account Type</label>
                      <div class="col-md-4">
                        
                        <select class="form-control" name="bank[acc_type]">
                            <option value="0">Select</option>
                            <?php 
                                $acc_type = $this->config->item('bank_acc_type');
                                foreach ($acc_type as $key=>$val) { 
                                    $selected = !empty($bank['acc_type']) && $bank['acc_type'] == $key ? 'selected="selected" ' : '';  
                                    echo "<option value='".$key."' ".$selected." >".$val."</option>";
                                } ?> 
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label">Opening Balance</label>
                      <div class="col-md-4">
                        <input name="bank[opening_bal]" type="number" placeholder="Enter Opening Balance" value="<?php echo !empty($bank['opening_bal']) ? $bank['opening_bal'] : '';?>" class="form-control input-md" maxlength="13"  >
                        
                      </div>
                    </div>
                    
                    
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label">Date</label>
                      <div class="col-md-4">                                                          
                            
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right datepicker" name="bank[ob_date]" type="text" value="<?php echo !empty($bank['ob_date']) ? date('d-m-Y',strtotime($bank['ob_date'])) : '';?>" />
                            </div> <!-- /.input group -->
                                        
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="col-md-4 control-label">Account Status</label>
                      <div class="col-md-4">
                        
                        <select class="form-control" name="bank[acc_status]">                            
                            <?php 
                                $acc_status = $this->config->item('property_status');
                                foreach ($acc_status as $key=>$val) { 
                                    $selected = !empty($bank['acc_status']) && $bank['acc_status'] == $key ? 'selected="selected" ' : '';  
                                    echo "<option value='".$key."' ".$selected." >".$val."</option>";
                                } ?> 
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-4 text-right"> 
                        <p class="help-block"> * Required Fields.</p>
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
            
            
            		
          </form> 			
        
        </div>
          
        </div><!-- /.box-body -->
      </div><!-- /.box -->     
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- bootstrap datepicker -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script>
var sales_items = $.parseJSON('<?php echo !empty($sales_items) ? json_encode($sales_items) : json_encode(array());?>');
$(document).ready(function () {
    
     
     $('.msg_notification').fadeOut(5000);
        
    /** Form validation */   
    $( "#bms_frm" ).validate({
      rules: {            
        "bank[property_id]": "required",            
        "bank[bank_name]": "required",
        "bank[acc_no]": "required"
      },
      messages: {
        "bank[property_id]": "Please select Property",
        "bank[bank_name]": "Please enter Bank Name",
        "bank[acc_no]": "Please enter Account Number"
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );
        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        }
        else if ( element.prop( "id" ) === "datepicker" ) {
          error.insertAfter( element.parent( "div" ) );
        }
        else {
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
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });        
});     
</script>