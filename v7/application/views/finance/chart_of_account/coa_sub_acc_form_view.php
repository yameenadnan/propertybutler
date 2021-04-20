<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
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
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_chart_of_accounts/coa_sub_acc_form_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Group *</label>
                                  <select class="form-control" id="coa_acc_group_id" name="coa_sub_acc_name[coa_acc_group_id]">
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($acc_group as $key=>$val) { 
                                                    $selected = !empty($coa_sub_acc_info['coa_acc_group_id']) && $coa_sub_acc_info['coa_acc_group_id'] == $val['coa_acc_group_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['coa_acc_group_id']."' ".$selected."'>".$val['coa_acc_group_name']."</option>";
                                                } ?> 
                                        </select>
                                </div>
                                <input type="hidden" id="coa_acc_sub_name_id" name="coa_acc_sub_name_id" value="<?php echo $coa_acc_sub_name_id;?>"/>
                            </div>                                    
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Name &amp; Code *</label>
                                    <select class="form-control" id="coa_acc_name_id" name="coa_sub_acc_name[coa_acc_name_id]">
                                        <option value="">Select</option>
                                        <?php 
                                            foreach ($acc_name as $key=>$val) { 
                                                $selected = !empty($coa_sub_acc_info['coa_acc_name_id']) && $coa_sub_acc_info['coa_acc_name_id'] == $val['coa_acc_name_id'] ? 'selected="selected" ' : '';  
                                                echo "<option value='".$val['coa_acc_name_id']."' ".$selected."'>".$val['coa_acc_name']. " (".$val['coa_acc_code'].")</option>";
                                            } ?> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                    
                    
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Subaccount Name *</label>
                                <input type="text" name="coa_sub_acc_name[coa_acc_sub_name]" class="form-control" value="<?php echo isset($coa_sub_acc_info['coa_acc_sub_name']) && $coa_sub_acc_info['coa_acc_sub_name'] != '' ? $coa_sub_acc_info['coa_acc_sub_name'] : '';?>" placeholder="" maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Subaccount Code *</label>
                                <input type="text" name="coa_sub_acc_name[coa_sub_acc_code]" class="form-control" value="<?php echo isset($coa_sub_acc_info['coa_sub_acc_code']) && $coa_sub_acc_info['coa_sub_acc_code'] != '' ? $coa_sub_acc_info['coa_sub_acc_code'] : '';?>" placeholder="" maxlength="10">
                            </div>
                        </div>  
                            
                             
                                                                
                        
                    </div> <!-- . col-md-12 -->
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                  <label >Period Format</label>
                                  <select name="coa_sub_acc_name[period]" class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                        $period_format = $this->config->item('period_format');
                                        
                                        foreach ($period_format as $key=>$val) {
                                            echo "<option value='".$val."'>".$val."</option>";
                                        }
                                    ?>
                                    
                                  </select>
                                </div>
                            </div> 
                        </div> 
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Is Editable</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[is_editable]" value="1" <?php echo isset($coa_sub_acc_info['is_editable']) && $coa_sub_acc_info['is_editable'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[is_editable]" value="0" <?php echo !isset($coa_sub_acc_info['is_editable']) || $coa_sub_acc_info['is_editable'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Payment Source</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[payment_source]" value="1" <?php echo isset($coa_sub_acc_info['payment_source']) && $coa_sub_acc_info['payment_source'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[payment_source]" value="0" <?php echo !isset($coa_sub_acc_info['payment_source']) || $coa_sub_acc_info['payment_source'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Payment Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[payment_enabled]" value="1" <?php echo isset($coa_sub_acc_info['payment_enabled']) && $coa_sub_acc_info['payment_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[payment_enabled]" value="0" <?php echo !isset($coa_sub_acc_info['payment_enabled']) || $coa_sub_acc_info['payment_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Bill Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[bill_enabled]" value="1" <?php echo isset($coa_sub_acc_info['bill_enabled']) && $coa_sub_acc_info['bill_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[bill_enabled]" value="0" <?php echo !isset($coa_sub_acc_info['bill_enabled']) || $coa_sub_acc_info['bill_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Receipt Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[receipt_enabled]" value="1" <?php echo isset($coa_sub_acc_info['receipt_enabled']) && $coa_sub_acc_info['receipt_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[receipt_enabled]" value="0" <?php echo !isset($coa_sub_acc_info['receipt_enabled']) || $coa_sub_acc_info['receipt_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Allow Prepayment</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[allow_prepayment]" value="1" <?php echo isset($coa_sub_acc_info['allow_prepayment']) && $coa_sub_acc_info['allow_prepayment'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[allow_prepayment]" value="0" <?php echo !isset($coa_sub_acc_info['allow_prepayment']) || $coa_sub_acc_info['allow_prepayment'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Deposit Tracking</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[deposit_tracking]" value="1" <?php echo isset($coa_sub_acc_info['deposit_tracking']) && $coa_sub_acc_info['deposit_tracking'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[deposit_tracking]" value="0" <?php echo !isset($coa_sub_acc_info['deposit_tracking']) || $coa_sub_acc_info['deposit_tracking'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Exclude from LPI</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[exclude_from_lpi]" value="1" <?php echo isset($coa_sub_acc_info['exclude_from_lpi']) && $coa_sub_acc_info['exclude_from_lpi'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[exclude_from_lpi]" value="0" <?php echo !isset($coa_sub_acc_info['exclude_from_lpi']) || $coa_sub_acc_info['exclude_from_lpi'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Consolidate R&amp;P</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[consolidate_r_p]" value="1" <?php echo isset($coa_sub_acc_info['consolidate_r_p']) && $coa_sub_acc_info['consolidate_r_p'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa_sub_acc_name[consolidate_r_p]" value="0" <?php echo !isset($coa_sub_acc_info['consolidate_r_p']) || $coa_sub_acc_info['consolidate_r_p'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>                        
                    
                  </div><!-- . row -->
                                  
              <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div>
          </div><!-- /.box-body -->
          
          <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
                <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                    <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                  </div>
                </div>
            </div>
          </div>
        </form>
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    $('#coa_acc_group_id').focus();
    
    $( "#bms_frm" ).validate({
		rules: {			
            "coa_sub_acc_name[coa_acc_group_id]": "required",
            "coa_sub_acc_name[coa_acc_name_id]": "required",
            "coa_sub_acc_name[coa_acc_sub_name]": "required",  
            "coa_sub_acc_name[coa_sub_acc_code]": "required"                 
		},
		messages: {			
            "coa_sub_acc_name[coa_acc_group_id]": "Please select Account Group",
            "coa_sub_acc_name[coa_acc_name_id]": "Please select Account Name & Code",
            "coa_sub_acc_name[coa_acc_sub_name]": "Please enter Subaccount Name",
            "coa_sub_acc_name[coa_sub_acc_code]": "Please enter Subaccount Code"            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.hasClass( "datepicker" ) ) {
				error.insertAfter( element.parent( "div" ) );
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
      
    $('#coa_acc_group_id').change(function () {
        account_name ();
    });
      
});

// Load block and assign to drop down
function account_name () {
    if($('#coa_acc_group_id').val() != '') {
    
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_chart_of_accounts/get_coa_name_with_code');?>',
            data: {'coa_acc_group_id':$('#coa_acc_group_id').val()},
            datatype:"json", // others: xml, json; default is html
    
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.coa_acc_name_id+'">'+item.coa_acc_name+' ('+item.coa_acc_code+')'+'</option>';
                    });
                }
                $('#coa_acc_name_id').html(str); 
                
                $("#content_area").LoadingOverlay("hide", true);
                //loadBank ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
     } else {
        $('#coa_acc_name_id').html('<option value="">Select</option>');
    }
}


</script>