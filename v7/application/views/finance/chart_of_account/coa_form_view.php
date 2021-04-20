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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_coa/coa_form_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                  
                  
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Peoperty Name *</label>
                                    <select class="form-control" id="property_id" name="coa[property_id]">
                                        <option value="">Select</option>
                                        <?php 
                                        foreach ($properties as $key=>$val) {
                                            $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                                  </select>
                                </div>
                                <input type="hidden" id="coa_id" name="coa_id" value="<?php echo $coa_id;?>"/>
                            </div>                                    
                        </div>
                        
                    </div> <!-- . col-md-12 -->
                  
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                    <label >Account Type *</label>
                                  <select class="form-control" id="coa_type_id" name="coa[coa_type_id]">
                                            <option value="">Select</option>
                                            <?php 
                                                foreach ($acc_type as $key=>$val) { 
                                                    $selected = !empty($coa_info['coa_type_id']) && $coa_info['coa_type_id'] == $val['coa_type_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['coa_type_id']."' ".$selected."'>".$val['coa_type_name']."</option>";
                                                } ?> 
                                        </select>
                                </div>
                                
                            </div>                                    
                        </div>                        
                        
                    </div> <!-- . col-md-12 -->
                    
                    
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >                   
                    
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Account Code *</label>
                                <input type="text" name="coa[coa_code]" id="coa_code" class="form-control" value="<?php echo isset($coa_info['coa_code']) && $coa_info['coa_code'] != '' ? $coa_info['coa_code'] : '';?>" placeholder="" maxlength="12">
                            </div>
                        </div>                                                                                      
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >                        
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Account Name *</label>
                                <input type="text" name="coa[coa_name]" class="form-control" value="<?php echo isset($coa_info['coa_name']) && $coa_info['coa_name'] != '' ? $coa_info['coa_name'] : '';?>" placeholder="" maxlength="150">
                            </div>
                        </div>                                                                
                        
                    </div> <!-- . col-md-12 -->
                    
                    <!--div class="col-md-12 col-sm-12 col-xs-12" >                        
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Account Description</label>
                                <input type="text" name="coa[coa_sub_acc_code]" class="form-control" value="<?php echo isset($coa_info['coa_sub_acc_code']) && $coa_info['coa_sub_acc_code'] != '' ? $coa_info['coa_sub_acc_code'] : '';?>" placeholder="" maxlength="10">
                            </div>
                        </div>                                                                
                        
                    </div--> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                                <div class="form-group">
                                  <label >Period Format</label>
                                  <select name="coa[period]" class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                        $period_format = $this->config->item('period_format');
                                        
                                        foreach ($period_format as $key=>$val) {
                                            $selected = !empty($coa_info['period']) && $coa_info['period'] == $val ? 'selected="selected"' : '';
                                            echo "<option value='".$val."' ".$selected.">".$val."</option>";
                                        }
                                    ?>
                                    
                                  </select>
                                </div>
                            </div> 
                        </div> 
                        
                        
                        <!--div class="col-md-12 col-sm-12 col-xs-12" >                        
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Opening Debit</label>
                                <input type="number" name="coa[opening_debit]" class="form-control" value="<?php echo isset($coa_info['opening_debit']) && $coa_info['opening_debit'] != '' ? $coa_info['opening_debit'] : '';?>" placeholder="" maxlength="14">
                            </div>
                        </div>                                                                
                        
                    </div-->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >                        
                        <div class="col-md-6 col-sm-12 col-xs-12">                                    
                            <div class="form-group">
                                <label >Opening Credit</label>
                                <input type="number" name="coa[opening_credit]" class="form-control" value="<?php echo isset($coa_info['opening_credit']) && $coa_info['opening_credit'] != '' ? $coa_info['opening_credit'] : '';?>" placeholder="" maxlength="14">
                            </div>
                        </div>                                                          
                        
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">     
                        <div class="col-md-2">   
                            <label>Opening Credit Date </label>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right datepicker" name="coa[opening_cr_date]" type="text" value="<?php echo !empty($coa_info['opening_cr_date']) ? date('d-m-Y',strtotime($coa_info['opening_cr_date'])) : ''; ?>" />
                            </div>
                            <!-- /.input group -->
                        </div>     
                    </div>    
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 25px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Payment Source</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" id="payment_source" name="coa[payment_source]" value="1" <?php echo isset($coa_info['payment_source']) && $coa_info['payment_source'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp;
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[payment_source]" value="0" <?php echo !isset($coa_info['payment_source']) || $coa_info['payment_source'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                           <div class="col-md-2 col-xs-4 default_acc_container" style="visibility: hidden">
                                <label class="radio-inline">
                                    <input type="checkbox" name="coa[default_acc]" value="1" <?php echo isset($coa_info['default_acc']) && $coa_info['default_acc'] == 1 ? 'checked="checked"' : '';?>> Default account?
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Payment Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[payment_enabled]" value="1" <?php echo isset($coa_info['payment_enabled']) && $coa_info['payment_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[payment_enabled]" value="0" <?php echo !isset($coa_info['payment_enabled']) || $coa_info['payment_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Bill Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[bill_enabled]" value="1" <?php echo isset($coa_info['bill_enabled']) && $coa_info['bill_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[bill_enabled]" value="0" <?php echo !isset($coa_info['bill_enabled']) || $coa_info['bill_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Receipt Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[receipt_enabled]" value="1" <?php echo isset($coa_info['receipt_enabled']) && $coa_info['receipt_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[receipt_enabled]" value="0" <?php echo !isset($coa_info['receipt_enabled']) || $coa_info['receipt_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Deposit Enabled</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[deposit_enabled]" value="1" <?php echo isset($coa_info['deposit_enabled']) && $coa_info['deposit_enabled'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[deposit_enabled]" value="0" <?php echo !isset($coa_info['deposit_enabled']) || $coa_info['deposit_enabled'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>SC</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[sc]" value="1" <?php echo isset($coa_info['sc']) && $coa_info['sc'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[sc]" value="0" <?php echo !isset($coa_info['sc']) || $coa_info['sc'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>SF</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[sf]" value="1" <?php echo isset($coa_info['sf']) && $coa_info['sf'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[sf]" value="0" <?php echo !isset($coa_info['sf']) || $coa_info['sf'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
                                </label>
                           </div>
                        </div>
                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">
                          <div class="col-md-2 col-xs-4">
                              <label>LPI</label>
                          </div>
                          <div class="col-md-2 col-xs-4">
                              <label class="radio-inline">
                                  <input type="radio" name="coa[lpi]" value="1" <?php echo isset($coa_info['lpi']) && $coa_info['lpi'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp;
                              </label>
                              <label class="radio-inline">
                                  <input type="radio" name="coa[lpi]" value="0" <?php echo !isset($coa_info['lpi']) || $coa_info['lpi'] == 0 ? 'checked="checked"' : '';?>>No &ensp;
                              </label>
                          </div>
                        </div>

                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">
                          <div class="col-md-2 col-xs-4">
                              <label>Fire Insurance</label>
                          </div>
                          <div class="col-md-2 col-xs-4">
                              <label class="radio-inline">
                                  <input type="radio" name="coa[fi]" value="1" <?php echo isset($coa_info['fi']) && $coa_info['fi'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp;
                              </label>
                              <label class="radio-inline">
                                  <input type="radio" name="coa[fi]" value="0" <?php echo !isset($coa_info['fi']) || $coa_info['fi'] == 0 ? 'checked="checked"' : '';?>>No &ensp;
                              </label>
                          </div>
                        </div>

                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">
                          <div class="col-md-2 col-xs-4">
                              <label>Quit Rent</label>
                          </div>
                          <div class="col-md-2 col-xs-4">
                              <label class="radio-inline">
                                  <input type="radio" name="coa[qr]" value="1" <?php echo isset($coa_info['qr']) && $coa_info['qr'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp;
                              </label>
                              <label class="radio-inline">
                                  <input type="radio" name="coa[qr]" value="0" <?php echo !isset($coa_info['qr']) || $coa_info['qr'] == 0 ? 'checked="checked"' : '';?>>No &ensp;
                              </label>
                          </div>
                        </div>

                        <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 10px;">  
                            <div class="col-md-2 col-xs-4">
                              <label>Water</label> 
                           </div>   
                           <div class="col-md-2 col-xs-4">        
                                <label class="radio-inline">
                                  <input type="radio" name="coa[water]" value="1" <?php echo isset($coa_info['water']) && $coa_info['water'] == 1 ? 'checked="checked"' : '';?>>Yes &ensp; 
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="coa[water]" value="0" <?php echo !isset($coa_info['water']) || $coa_info['water'] == 0 ? 'checked="checked"' : '';?>>No &ensp; 
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
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/

    set_default_cc ();

    $('input[name="coa[payment_source]"').click (function () {
        set_default_cc ();
    });



    $('#coa_acc_type_id').focus();
    
    $( "#bms_frm" ).validate({
		rules: {	
            "coa[property_id]": "required",
            "coa[coa_type_id]": "required",
            
            "coa[coa_code]":{
					   required: true,					   
                       remote: {
                        url: "<?php echo base_url('index.php/bms_fin_coa/check_coa_code');?>",
                        type: "post",
                        data: { property_id: function() { return $( "#property_id" ).val(); },coa_code: function() { return $( "#coa_code" ).val(); },coa_id: function() { return $( "#coa_id" ).val(); } }
                      }
					}, 
            "coa[coa_name]": "required"                 
		},
		messages: {	
		    "coa[property_id]": "Please select Peoperty Name",
            "coa[coa_type_id]": "Please select Account Type",            
            "coa[coa_code]":{
					   required:"Please enter Account Code",                      
                       remote:"Account Code exists already!"
					   },
            "coa[coa_name]": "Please enter Account Name "            
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
    $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
            
        });
});

function set_default_cc () {
    if ( $("input[name='coa[payment_source]']:checked").val() == 1 ) {
        $('.default_acc_container').css('display','block');
        $('.default_acc_container').css('visibility','visible');
    } else {
        $('.default_acc_container').find('input[type=checkbox]').prop("checked", false);
        $('.default_acc_container').css('display','none');
        $('.default_acc_container').css('visibility','hidden');
    }
}

</script>