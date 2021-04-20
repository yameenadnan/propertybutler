<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader-theme-thumbnails.css" rel="stylesheet">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
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
            <!-- form start -->
            <form role="form" id="defect_new" action="<?php echo base_url('index.php/bms_defect/new_defect_submit');?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12" style="padding: 0;">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="property_id">Property Name *</label>
                                <select class="form-control" id="property_id" name="defect[property_id]">
                                <option value="">Select</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {
                                        $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' data-pname='".$val['property_name']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="">Block/Street</label>
                              <select class="form-control" id="block_id">
                                    <option value="">Select</option>                                
                                  </select>
                            </div>
                        </div>
                    </div>
                    
                    <div style="clear: both;height:1px"></div> 
                    
                    <div class="col-md-12" style="padding: 0;">
                        <div class="col-md-6 col-xs-12" style="padding: 0;">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Defect Name *</label>
                                  <input type="text" id="defect_name" name="defect[defect_name]" class="form-control" placeholder="Enter Defect Name" maxlength="250">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Defect Location</label>
                                  <input type="text" name="defect[defect_location]" class="form-control" placeholder="Enter Defect Location" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Defect Details</label>
                                  <textarea name="defect[defect_detail]" class="form-control" rows="2" placeholder="Enter Defect Details"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6 col-xs-12" style="padding: 0;">
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                  <label>Unit No</label>
                                  <select name="defect[unit_id]" class="form-control" id="unit_id">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                  <label>Unit Status</label>
                                  <input id="unit_status" type="text" class="form-control" placeholder="Unit Status" disabled>                                  
                                </div>
                            </div>
                            
                            
                            <div class="col-md-8 col-xs-8">
                                <div class="form-group">
                                  <label>Resident Name</label>
                                  <input id="resident_name" type="text" class="form-control" placeholder="Resident Name" disabled>                          
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-4" style="margin-top: 15px;">
                                <div class="form-group">
                                  <div class="checkbox">
                                    <label><input id="defaulter" type="checkbox" disabled>Defaulter </label>
                                  </div>
        
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                  <label>Resident Contact</label>
                                  <input id="contact_number" type="text" class="form-control" placeholder="" disabled>                          
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                  <label>Resident Email</label>
                                  <input id="email_addr" type="text" class="form-control" placeholder="" disabled>                          
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="clear: both;height:1px"></div> 
                    <div style="clear: both;height:10px"></div>
                    <div class="row no-margin">
    					<div class="col-md-6 col-xs-12  custom-left">
    						<div class="form-group floating-label"  style="float:left;width:100%;">
    							<label>Upload Image(s)</label>
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
              
              <!-- /.box-body -->
              <div class="row" style="text-align: right;margin:0 -10px;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary submit_btn" name="action" value="save_only">Submit</button> &ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
              
              <input type="hidden" name="property_name" id="property_name" value="" />
              <input type="hidden" name="resident_name_hidd" id="resident_name_hidd" value="" />
              <input type="hidden" name="unit_no" id="unit_no" value="" />
              <input type="hidden" name="resident_gender_hidd" id="resident_gender_hidd" value="" />
              <input type="hidden" name="resident_email_hidd" id="resident_email_hidd" value="" />
              <input type="hidden" name="resident_valid_email" id="resident_valid_email" value="" />
            </div> <!-- .row -->
          </div> <!-- .box-body -->
        </form>
      </div><!-- /.box -->

    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.fileuploader.min.js"></script>

<script>
var total_uploads = 0;
/** Image uload with preview */

$('#fileUp1').fileuploader({
	extensions: ['jpg', 'jpeg', 'png', 'gif','pdf'],
	upload: {
		url: '<?php echo base_url('index.php/bms_defect/defect_image_submit');?>',
		data: {			
			files : 'defect_file'
		},
		type: 'POST',
		enctype: 'multipart/form-data',
		start: true,
		synchron: true,
		beforeSend: null,
		onSuccess: function(data, item) {
            total_uploads++;
			console.log(data);
			var file_data = JSON.parse(data);
			if (typeof file_data !== 'undefined') {
				var html = '<input type="hidden" name="files[]" value="' + file_data['name'] + '" real_name="' + item['name'] + '" footer="footer" />';
				$("#defect_new").append(html);
			}
			if (total_uploads >= 4 )
                $('#fileUp1').prop('disabled', true);
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
			url: '<?php echo base_url('index.php/bms_defect/defect_image_remove');?>',
			type: 'POST',
			data: {file: real_name, uploadDir: '../' + 'uploads/Complaint - Defects Attachement/275/2018/05/2/14/' },
		})
		.done(function() {
            total_uploads--;
            if ( total_uploads < 4 )
                $('#fileUp1').prop('disabled', false);
			$('#defect_new :input[real_name="' + file_name + '"][footer="footer"]').remove();
		});
	}
	});


/** end of Image uload with preview */


/** Reset button click */

$(document).ready(function () {
    
    /** Form validation */
    
    $( "#defect_new" ).validate({
		rules: {
			"defect[property_id]": "required",
			"defect[defect_name]": "required"
		},
		messages: {
			"defect[property_id]": "Please select Property Name",
			"defect[defect_name]": "Please enter Defect Name"
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
    
    $('.submit_btn').click(function () {
        if($('#property_id').val() != '' && $('#defect_name').val() != '' && $('#assign_to').val() != '' && $('#datepicker').val() != '' ) {
            $("#content_area").LoadingOverlay("show");
        }   
    }); 
    

    // Load block and assign to drop down
    function property_change_eve () {
        if($('#property_id').val() != '') {
            $('#property_name').val($(this).find('option:selected').data('pname'));
        } else {
            $('#property_name').val('');
        }
        
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_defect/get_blocks');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str); 
                $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
                unset_resident_info(); // unset the resident onfo if loaded already
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
    
    // On document ready
    if ($('#property_id').val() != '') {
        //console.log($('#property_id').val());
        property_change_eve ();//$('#property_id').trigger("change");
    }
    
    // On property name change
    $('#property_id').change(function () {
        //console.log('triggered');
        property_change_eve ();        
    });  
    
    // On block/street change
    $('#block_id').change(function () {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_defect/get_unit');?>',
            data: {'property_id':$('#property_id').val(),'block_id':$('#block_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-unit_no="' + item.unit_no + '" data-validemail="'+item.valid_email+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                unset_resident_info(); // unset the resident onfo if loaded already             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });
    
    $('#unit_id').change (function () {
        unset_resident_info(); // unset the resident onfo if loaded already        
        if(typeof($(this).find('option:selected').data('owner')) != 'undefined') {
            $('#resident_name').val($(this).find('option:selected').data('owner'));
            $('#resident_name_hidd').val($(this).find('option:selected').data('owner'));
            $('#unit_no').val($(this).find('option:selected').data('unit_no'));
        }
        if(typeof($(this).find('option:selected').data('status')) != 'undefined') {
            $('#unit_status').val($(this).find('option:selected').data('status'));
            //$('#unit_status_hid').val($(this).find('option:selected').data('status'));            
        }
        if(typeof($(this).find('option:selected').data('contact')) != 'undefined') {
            $('#contact_number').val($(this).find('option:selected').data('contact'));
            //$('#contact_number_hid').val($(this).find('option:selected').data('contact'));            
        } 
        if(typeof($(this).find('option:selected').data('email')) != 'undefined'){
            $('#email_addr').val($(this).find('option:selected').data('email'));
            $('#resident_email_hidd').val($(this).find('option:selected').data('email'));            
        }

        if (typeof($(this).find('option:selected').data('validemail')) != 'undefined') {
            $('#resident_valid_email').val($(this).find('option:selected').data('validemail'));
        }

        if(typeof($(this).find('option:selected').data('defaulter')) != 'undefined' && $(this).find('option:selected').data('defaulter') == 1) $('#defaulter').attr('checked',true);    
        if(typeof($(this).find('option:selected').data('gender')) != 'undefined'){            
            $('#resident_gender_hidd').val($(this).find('option:selected').data('gender'));            
        }    
        //console.log($(this).find('option:selected').data('defaulter'));
    });
    
    function unset_resident_info () {
        $('#resident_name').val('');    $('#resident_name_hidd').val('');
        $('#unit_status').val('');      //$('#unit_status_hid').val('');
        $('#contact_number').val('');   $('#resident_gender_hidd').val('');
        $('#email_addr').val('');       $('#resident_email_hidd').val('');
        $('#defaulter').attr('checked',false);
    }
             
});

//Date picker
$(function () {
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: '<?php echo date('d-m-Y');?>'
    });
    
});
</script>