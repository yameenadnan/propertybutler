<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/add_annual_renewal_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 "  >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="-border: 1px solid #999;border-radius: 5px;">
                                
                                <div class="form-group">
                                  <label >Property Name *</label>
                                    <select class="form-control" id="property_id" name="annual_renew[property_id]">
                                        <option value="">Select Property</option>
                                        <?php 
                                            foreach ($properties as $key=>$val) { 
                                                $selected = !empty($renewal_info['property_id']) && $renewal_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : (!empty($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '');  
                                                echo "<option value='".$val['property_id']."' ".$selected."' data-pname='".$val['property_name']."'>".$val['property_name']."</option>";
                                            } ?> 
                                    </select>
                                    <input type="hidden" id="annual_renewal_id" name="annual_renewal_id" value="<?php echo $annual_renewal_id;?>"/>
                                    <input type="hidden" name="property_name" id="property_name" value="" />
                                </div>
                                   
                                    
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 no-padding"> 
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                        <div class="form-group">
                                            <label >Item Description *</label>
                                          <input type="text" name="annual_renew[item_descrip]" class="form-control" value="<?php echo isset($renewal_info['item_descrip']) && $renewal_info['item_descrip'] != '' ? $renewal_info['item_descrip'] : '';?>" placeholder="Enter Item Description" maxlength="255">
                                        </div>
                                    </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-6" >
                                <div class="form-group">
                                  <label >Serial No.</label>
                                  <input type="text" name="annual_renew[serial_no]" class="form-control" value="<?php echo isset($renewal_info['serial_no']) && $renewal_info['serial_no'] != '' ? $renewal_info['serial_no'] : '';?>" placeholder="Enter Serial No." maxlength="100">  
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                <div class="form-group">
                                  <label >Location</label>
                                    <input type="text" name="annual_renew[location]" class="form-control" value="<?php echo isset($renewal_info['location']) && $renewal_info['location'] != '' ? $renewal_info['location'] : '';?>" placeholder="Enter Location" maxlength="150">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 no-padding">
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                      <label >License No *</label>
                                      <input type="text" name="annual_renew[license_no]" id="license_no" class="form-control" value="<?php echo isset($renewal_info['license_no']) && $renewal_info['license_no'] != '' ? $renewal_info['license_no'] : '';?>" placeholder="Enter License No" maxlength="100"> 
                            
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6  no-padding">                                    
                                <div class="form-group">
                                    <label >License Start Date *</label>
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right datepicker" id="license_start_date" name="annual_renew[license_start_date]" value="<?php echo isset($renewal_info['license_start_date']) && $renewal_info['license_start_date'] != '' && $renewal_info['license_start_date'] != '0000-00-00' && $renewal_info['license_start_date'] != '1970-01-01' ? date('d-m-Y', strtotime($renewal_info['license_start_date'])) : '';?>" type="text">
                                    </div>    
                        
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 " >
                                <div class="form-group">
                                    <label>License Expiry Date *</label>                            
                                    <div class="input-group date">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                      <input class="form-control pull-right datepicker" name="annual_renew[license_expiry_date]" value="<?php echo isset($renewal_info['license_expiry_date']) && $renewal_info['license_expiry_date'] != '' && $renewal_info['license_expiry_date'] != '0000-00-00' && $renewal_info['license_expiry_date'] != '1970-01-01' ? date('d-m-Y', strtotime($renewal_info['license_expiry_date'])) : '';?>" type="text">
                                    </div>  
                                </div>
                            </div>
                                    
                            <div class="col-md-6 col-sm-6 col-xs-12  no-padding">
                                                                  
                                <div class="form-group">
                                    <label >Supplier Name</label>
                                  <input type="text" name="annual_renew[supplier_name]" id="supplier_name" class="form-control" value="<?php echo isset($renewal_info['supplier_name']) && $renewal_info['supplier_name'] != '' ? $renewal_info['supplier_name'] : '';?>" placeholder="Enter Supplier Name" maxlength="150">
                                </div>
                               
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12">                                    
                                    <div class="form-group">
                                        <label >Address</label>
                                      <input type="text" name="annual_renew[address]" id="address" class="form-control" value="<?php echo isset($renewal_info['address']) && $renewal_info['address'] != '' ? $renewal_info['address'] : '';?>" placeholder="Enter Address" maxlength="255">
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                  <label >Office Phone No.</label>
                                    <input type="text" name="annual_renew[office_ph_no]" id="office_ph_no" class="form-control" value="<?php echo isset($renewal_info['office_ph_no']) && $renewal_info['office_ph_no'] != '' ? $renewal_info['office_ph_no'] : '';?>" placeholder="Enter Office Phone No." maxlength="50">
                        
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                  <label >Person Incharge Name </label>
                                  <input type="text" name="annual_renew[person_incharge]" class="form-control" value="<?php echo isset($renewal_info['person_incharge']) && $renewal_info['person_incharge'] != '' ? $renewal_info['person_incharge'] : '';?>" placeholder="Enter Person Incharge Name" maxlength="150">  
                        
                                </div>
                            </div> 
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                  <label >Person Incharge Mobile No.</label>
                                    <input type="text" name="annual_renew[person_inc_mobile]" id="person_inc_mobile" class="form-control" value="<?php echo isset($renewal_info['person_inc_mobile']) && $renewal_info['person_inc_mobile'] != '' ? $renewal_info['person_inc_mobile'] : '';?>" placeholder="Enter Person Incharge Mobile No." maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                  <label >Email Address</label>
                                  <input type="text" name="annual_renew[person_inc_email]" id="person_inc_email" class="form-control" value="<?php echo isset($renewal_info['person_inc_email']) && $renewal_info['person_inc_email'] != '' ? $renewal_info['person_inc_email'] : '';?>" placeholder="Enter Email Address" maxlength="150">  
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-6 ">                                    
                                <div class="form-group">
                                    <label >Remind Before (Days)</label>
                                    <select name="annual_renew[remind_before]" class="form-control">
                                        <option value="">Select</option>   
                                        <?php 
                                        
                                            $asset_warranty_remin = $this->config->item('asset_warranty_remin');
                                            foreach ($asset_warranty_remin as $key=>$val) { 
                                                $selected = isset($renewal_info['remind_before']) && trim($renewal_info['remind_before']) == $key ? 'selected="selected" ' : '';  
                                                echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                            }
                                            
                                            /*for ($i =1; $i <=31;$i++) { 
                                                $selected = isset($renewal_info['remind_before']) && trim($renewal_info['remind_before']) == $i ? 'selected="selected" ' : '';  
                                                echo "<option value='".$i."' ".$selected.">".$i." Day".($i == 1 ? "" : "s")."</option>";
                                            }*/ ?> 
                                             
                                                                       
                                    </select>    
                        
                                </div>
                            </div>  
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row --> 
                  <?php if(!empty($annual_renewal_att)) { ?>
                  <div class="col-md-12" style="padding-top: 10px;padding-bottom: 10px;" >
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                            <label >Attachment(s): &nbsp;</label>
                                <?php foreach ($annual_renewal_att as $aKey=>$aVal) { ?>
                                    <a href="<?php echo base_url().'bms_uploads/annual_renewal_docs/'.$aVal['file_name'];?>" target="_blank" >Attachment <?php echo ($aKey+1);?></a> &ensp; &ensp; 
                                <?php } ?>
                            </div>
                        </div>
                  </div>
                  <?php } ?>
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
      
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"annual_renew[property_id]": "required",
            "annual_renew[item_descrip]": "required",
            "annual_renew[license_no]": "required",
            "annual_renew[license_start_date]": "required",
            "annual_renew[license_expiry_date]": "required"                     
		},
		messages: {
			"annual_renew[property_id]": "Please select Property Name",
            "annual_renew[item_descrip]": "Please enter Item Description",
            "annual_renew[license_no]": "Please enter License No",
            "annual_renew[license_start_date]": "Please select License Start Date",
            "annual_renew[license_expiry_date]": "Please select License Expiry Date"           
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "label" ).parent('div') );
			} else if ( element.hasClass("datepicker")) {
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
    var date = new Date();
    date.setDate(date.getDate()-0);
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //startDate: date,
        autoclose: true
    });
    $('#wd_datepicker').datepicker({        
        format: 'dd-mm-yyyy',
        startDate: date,
        autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>