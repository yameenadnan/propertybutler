<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
<?php
    $today_date = new DateTime(date('Y-m-d'));
    $warranty_due_date = !empty($asset_info['warranty_due']) && !in_array($asset_info['warranty_due'], array('0000-00-00','1970-01-01')) ? new DateTime($asset_info['warranty_due']) : '';
    
    $service_provider = array();
    
    if(!empty($asset_info['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp)) {
        $service_provider = $asset_info;
        
    } else if (!empty($asset_maint_comp)){
        $service_provider = $asset_maint_comp[0];
    }
   
?>  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css"> 
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader-theme-thumbnails.css" rel="stylesheet"> 
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/asset_service_details_entry_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    
                                    <div class="form-group">
                                      <label >Property Name </label>
                                        <select class="form-control" id="property_id" name="asset[property_id]" disabled="disabled">
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = isset($asset_info['property_id']) && $asset_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        <input type="hidden" id="property_id" name="property_id" value="<?php echo isset($asset_info['property_id']) && $asset_info['property_id'] != '' ? $asset_info['property_id'] : '';?>"/>
                                        <input type="hidden" id="asset_id" name="service_details[asset_id]" value="<?php echo isset($asset_info['asset_id']) && $asset_info['asset_id'] != '' ? $asset_info['asset_id'] : '';?>"/>
                                        <input type="hidden" id="service_schedule_id" name="service_details[service_schedule_id]" value="<?php echo isset($asset_info['asset_service_schedule_id']) && $asset_info['asset_service_schedule_id'] != '' ? $asset_info['asset_service_schedule_id'] : '';?>"/>
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                        <div class="form-group">
                                            <label >Asset Name</label>
                                          <input type="text" name="asset[asset_name]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['asset_name']) && $asset_info['asset_name'] != '' ? $asset_info['asset_name'] : '';?>" placeholder="Enter Asset Name" maxlength="255">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <label >Asset Description</label>
                                          <textarea name="asset[asset_descri]" class="form-control" rows="2" disabled="disabled"  ><?php echo isset($asset_info['asset_descri']) && $asset_info['asset_descri'] != '' ? $asset_info['asset_descri'] : '';?></textarea> 
                                
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12 "  style="">                                    
                                        <div class="form-group">
                                          <label >Asset Location</label>
                                            <input type="text" name="asset[asset_location]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['asset_location']) && $asset_info['asset_location'] != '' ? $asset_info['asset_location'] : '';?>"  maxlength="150">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-sm-12 col-xs-12  ">
                                
                                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding"  >
                                            <div class="form-group">
                                              <label >Serial No.</label>
                                              <input type="text" name="asset[serial_no]" class="form-control" disabled="disabled" value="<?php echo isset($asset_info['serial_no']) && $asset_info['serial_no'] != '' ? $asset_info['serial_no'] : '';?>"  maxlength="100">  
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-sm-6 col-xs-12">  
                                            <div class="form-group">
                                                <label >Scheduled Servicing Date</label>
                                                <div class="input-group date">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                  </div>
                                                  <input class="form-control pull-right datepicker" disabled="disabled" id="schedule_date" name="schedule_date" value="<?php echo isset($asset_info['service_schedule_date']) && $asset_info['service_schedule_date'] != '' ? $asset_info['service_schedule_date'] : '';?>" type="text">
                                                </div>
                                            </div>
                                       </div>
                                       
                                    </div>
                                
                                </div>  
                                
                            </div>
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box " style="padding-left:0px !important;-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;" >
                                    <!--div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"><?php echo !empty($asset_info['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Supplier Details ' : 'Maintenance Company Details';?>&nbsp; </span></h5></div-->
                                    <div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"><?php echo 'Maintenance Company Details';?>&nbsp; </span></h5></div>
                                </div>  
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Supplier Name' : 'Maintenance Company Name';?>:  </label>
                                        <?php echo !empty($service_provider['supplier_name'])  ? $service_provider['supplier_name'] : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Warranty Start Date ' : 'Maintenance Contract Start Date';?>:  </label>
                                        <?php echo !empty($service_provider['warranty_start'])  ? date('d-m-Y',strtotime($service_provider['warranty_start'])) : ' - ';?> 
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label><?php echo !empty($service_provider['warranty_due']) && $warranty_due_date != '' && $today_date <= $warranty_due_date && empty($asset_maint_comp) ? 'Warranty Due Date ' : 'Maintenance Contract Due Date';?>:  </label>
                                        <?php echo !empty($service_provider['warranty_due'])  ? date('d-m-Y',strtotime($service_provider['warranty_due'])) : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Remind Before:  </label>
                                        <?php  
                                        $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                                        echo !empty($service_provider['remind_before'])  ? $asset_warranty_remin[$service_provider['remind_before']]  : ' - ';?> 
                                    </div>
                                </div>
                                
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Company Address:  </label>
                                        <?php  
                                        $countries = $this->config->item('countries');
                                        $address = !empty($service_provider['address'])  ? $service_provider['address'] : '';
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['city'])  ? $service_provider['city'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['postcode'])  ? $service_provider['postcode'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['state'])  ? $service_provider['state'] : '');
                                        $address .= ($address != '' ? ', ': ' '). (!empty($service_provider['country'])  ? $countries[$service_provider['country']] : '');
                                        echo $address != '' ? $address : ' - ';
                                        ?>
                                         
                                    </div>
                                </div> 
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Office Number:  </label>
                                        <?php echo !empty($service_provider['office_ph_no'])  ? $service_provider['office_ph_no'] : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Name:  </label>
                                        <?php echo !empty($service_provider['person_incharge'])  ? $service_provider['person_incharge'] : ' - ';?> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Number:  </label>
                                        <?php echo !empty($service_provider['person_inc_mobile'])  ? $service_provider['person_inc_mobile'] : ' - ';?> 
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    <div class="form-group" style="margin-bottom: 5px;">
                                      <label>Contact Person Email:  </label>
                                        <?php echo !empty($service_provider['person_inc_email'])  ? $service_provider['person_inc_email'] : ' - ';?> 
                                    </div>
                                </div>
                                          
                                
                            </div>
                            
                        </div> <!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="-border: 1px solid #999;border-radius: 5px;">
                                
                               
                                <div class="col-md-6 col-sm-6 col-xs-12 ">  
                                    <div class="form-group">
                                        <label>Servicing Date</label>
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker" name="service_details[service_date]" value="<?php echo date('d-m-Y');?>" type="text">
                                        </div>  
                            
                                    </div>
                               </div>
                               <div class="col-md-6 col-sm-6 col-xs-12">  
                                    <div class="form-group">
                                        <label >Job Sheet No *</label>
                                      <input type="text" name="service_details[job_sheet_no]" class="form-control" value="" placeholder="Enter Job Sheet No" maxlength="100">
                                    </div>
                               </div>
                                
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12  "  style="">
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding ">
                                        <div class="form-group">
                                          <label >Service By *</label>
                                            <input type="text" name="service_details[service_by]" class="form-control" value="" placeholder="Enter Service By" maxlength="150">
                                        </div>
                                    </div>
                                    
                                </div>                                          
                                
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 no-padding">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box " style="padding-left:0px;-border: 1px solid #999;border-radius: 5px;">
                                                                
                                
                            </div>
                            
                        </div> <!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                    
                     <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                              <label>Service Description *</label>
                              <textarea name="service_details[service_description]" class="form-control" rows="2" placeholder="Enter Service Description"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                              <label>Remarks</label>
                              <textarea name="service_details[remarks]" class="form-control" rows="2" placeholder="Enter Remarks"></textarea>
                            </div>
                        </div>
                        
                        <!--div class="col-md-6 col-sm-6 col-xs-6 ">                                    
                            <div class="form-group">
                              <label for="">Attachment</label>
                              <div style="position:relative;">
                            		<label class="btn-bs-file btn btn-primary">
                                    Choose File...
                            			
                                        <input type="file" id="attach_file" name="document" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                            		</label>
                            		&nbsp;
                            		<span class='label label-info' id="upload-file-info"></span>
                        	  </div>
                            </div>
                        </div-->
                        
                        
                     </div>
                    
                  </div><!-- . row -->
                  <div style="clear: both;height:10px"></div> 
                    <div class="row no-margin">
    					<div class="col-md-6 col-xs-12  custom-left">
    						<div class="form-group floating-label"  style="float:left;width:100%;">
    							<label>Upload Attachment</label>
    							<input type="file" name="addRequestFile1" id="fileUp1">
    						</div>
    					</div>
    				</div>
                
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
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
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
<script src="<?php echo base_url();?>assets/js/jquery.fileuploader.min.js"></script>

<script>

$('#fileUp1').fileuploader({
	extensions: ['jpg', 'jpeg', 'png', 'gif','pdf'],
	upload: {
		url: '<?php echo base_url('index.php/bms_task/task_image_submit');?>',
		data: {			
			files : 'task_file'
		},
		type: 'POST',
		enctype: 'multipart/form-data',
		start: true,
		synchron: true,
		beforeSend: null,
		onSuccess: function(data, item) {
			console.log(data);
			var file_data = JSON.parse(data);
			if (typeof file_data !== 'undefined') {
				var html = '<input type="hidden" name="files[]" value="' + file_data['name'] + '" real_name="' + item['name'] + '" footer="footer" />';
				$("#bms_frm").append(html);
			}
			//
			setTimeout(function() {
				item.html.find('.progress-holder').hide();
				item.renderImage();
			}, 400);
		},
	},
	onRemove: function(listEl, parentEl, newInputEl, inputEl, data, item){
		var file_name = listEl['name'];
		var real_name = $("input[real_name='" + file_name + "']").val();
		$.ajax({
			url: '<?php echo base_url('index.php/bms_task/task_image_remove');?>',
			type: 'POST',
			data: {file: real_name, uploadDir: '../' + 'uploads/Complaint - Tasks Attachement/275/2018/05/2/14/' },
		})
		.done(function() {
			$('#bms_frm :input[real_name="' + file_name + '"][footer="footer"]').remove();
		});
	}
	});
    
    

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    $('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
    
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"service_details[job_sheet_no]": "required",
            "service_details[service_by]": "required",
            "service_details[service_description]": "required"
            
		},
		messages: {
			"service_details[job_sheet_no]": "Please enter Job Sheet No",
            "service_details[service_by]": "Please enter Service By",
            "service_details[service_description]": "Please enter Service Description"
            
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "label" ).parent('div') );
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
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>