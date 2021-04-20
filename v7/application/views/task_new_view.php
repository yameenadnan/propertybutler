<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
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
            <form role="form" id="task_new" action="<?php echo base_url('index.php/bms_task/new_task_submit');?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12" style="padding: 0;">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="property_id">Property Name *</label>
                                <select class="form-control" id="property_id" name="task[property_id]">
                                <option value="">Select</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {
                                        
                                        $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' data-pname='".$val['property_name']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                    
                              <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
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
                                  <label>Task Name *</label>
                                  <input type="text" id="task_name" name="task[task_name]" class="form-control" placeholder="Enter Task Name" maxlength="250">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Task Location</label>
                                  <input type="text" name="task[task_location]" class="form-control" placeholder="Enter Task Location" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Task Details</label>
                                  <textarea name="task[task_details]" class="form-control" rows="2" placeholder="Enter Task Details"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6 col-xs-12" style="padding: 0;">
                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                  <label>Unit No</label>
                                  <select name="task[unit_id]" class="form-control" id="unit_id">
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
                    
                    <div class="col-md-12" style="padding: 0;">
                    
                    
                    
                    
                    
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                          <label>Task Catagory *</label>
                          <select name="task[task_category]" class="form-control">
                            <option value="">Select</option>
                            <?php 
                                $task_cat = $this->config->item('task_cat');
                                asort($task_cat);
                                foreach ($task_cat as $key=>$val) {
                                    echo "<option value='".$key."'>".$val."</option>";
                                }
                            ?>
                            
                          </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                          <label>Source Of Assignment *</label>
                          <select name="task[task_source]" class="form-control">
                            <option value="">Select</option>
                            <?php 
                                $source_assign = $this->config->item('source_assign');
                                asort($source_assign);
                                foreach ($source_assign as $key=>$val) {
                                    echo "<option value='".$key."'>".$val."</option>";
                                }
                            ?>
                          </select>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                          <label>Assign To *</label>
                          <select id="assign_to" name="task[assign_to]" class="form-control">
                            <option value="">Select</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                            <label>Due Date *</label>
            
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input class="form-control pull-right" name="task[due_date]" id="datepicker" type="text" />
                            </div>
                            <!-- /.input group -->
                          </div>
                    </div>                                                                  
                   </div> 
                    <!-- File Upload -->
                    <!--div class="col-md-6 col-xs-12">
                        <div class="form-group">                          
                            <button class="add btn btn-success"><i class="glyphicon glyphicon-plus"></i>
                            <span>Add Images...</span></button>
                        
                        </div>
                    </div--> 
                    <div style="clear: both;height:10px"></div> 
                    <div class="row no-margin">
                          <div class="col-md-6 col-xs-12  custom-left">
                              <div class="form-group floating-label"  style="float:left;width:100%;">
                                  <label>Upload Image(s)</label>
                                  <input type="file" name="addRequestFile1" id="fileUp1">
                              </div>
                          </div>
                    </div>
                                     
                    
                
              
              <!--div style="clear: both;"></div>
              <div class="col-md-12" style="padding: 0;">              
                <div id="img_preview" class="row" style="margin: 10px;"></div>
              </div-->
                
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
                    <!--button type="submit" class="btn btn-primary submit_btn" name="action" value="save_print">Submit & Print</button> &ensp;-->
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
              
              <input type="hidden" name="property_name" id="property_name" value="" />
              <input type="hidden" name="resident_name_hidd" id="resident_name_hidd" value="" />
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
  
  
<?php include_once('footer.php');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.fileuploader.min.js"></script>

<script>

/** Image uload with preview */
/*var img_total_size = 0;
$('form button.add').click(function(e) {
    e.preventDefault();
    var nb_attachments = $('form input').length;
    var $input = $('<input type="file" name="img_' + nb_attachments + '">');
    $input.on('change', function(evt) {
        var f = evt.target.files[0];
        $('form').append($(this));
        //console.log(f);
        //$('ul.list').append('<li class="item">'+f.name+'('+f.size+')'+</li>');
        img_total_size += f.size;
        var ext = f.name.substring(f.name.lastIndexOf('.') + 1).toLowerCase();
        if (f && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_preview').append('<div class="col-xs-6 col-sm-4 col-md-3"><img class="img-responsive" src="'+e.target.result+'" /><br />'+f.name+'</div>');
              //$('#img_preview').attr('src', e.target.result);
            }    
            reader.readAsDataURL(evt.target.files[0]);
        }else{
             $('#img_preview').append('<div class="col-xs-6 col-sm-4 col-md-3"><br />'+f.name+'</div>');
        }
        //console.log(img_total_size +'bytes  ' + (img_total_size/1024) + 'KB  ' + (img_total_size/(1024*1024))+ 'MB');
    });
    $input.hide();
    $input.trigger('click');
    
});*/

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
                    $("#task_new").append(html);
                }
                //
                setTimeout(function() {
                    item.html.find('.progress-holder').hide();
                    item.renderImage();
                }, 400);
            },
        },
        onRemove: function ( listEl, parentEl, newInputEl, inputEl, data, item ) {
            var file_name = listEl['name'];
            var real_name = $("input[real_name='" + file_name + "']").val();
            $.ajax({
                url: '<?php echo base_url('index.php/bms_task/task_image_remove');?>',
                type: 'POST',
                data: {file: real_name, uploadDir: '../' + 'uploads/Complaint - Tasks Attachement/275/2018/05/2/14/' },
            })
            .done(function() {
                $('#task_new :input[real_name="' + file_name + '"][footer="footer"]').remove();
            });
        }
	});


/** end of Image uload with preview */


/** Reset button click */

/*$('.reset_btn').click(function () {
    //console.log('reset clicked');
    $('input[type=file]').remove();
    $('#img_preview').html('');        
});*/


//var has_error = false;

$(document).ready(function () {    
    
    /** Form validation */
    
    $( "#task_new" ).validate({
		rules: {
			"task[property_id]": "required",
			"task[task_name]": "required",
            "task[task_category]":"required",
            "task[task_source]":"required",
            "task[assign_to]":"required",
            "task[due_date]":"required"
		},
		messages: {
			"task[property_id]": "Please select Property Name",
			"task[task_name]": "Please enter Task Name",
            "task[task_category]":"Please select Task Catagory",
            "task[task_source]":"Please select Source Of Assignment",
            "task[assign_to]":"Please select Assign To",
            "task[due_date]":"Please select Due Date"
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
		},
        submitHandler: function(form) {
           $("#content_area").LoadingOverlay("show"); 
           $( "#task_new").submit();
        }
	});
    
    $('.submit_btn').click(function () {
        if($('#property_id').val() != '' && $('#task_name').val() != '' && $('#assign_to').val() != '' && $('#datepicker').val() != '' ) {
            $("#content_area").LoadingOverlay("show"); 
        }   
    }); 
    
    
    /*$('#task_new').on('submit', function(e){
        e.preventDefault();
        $("#content_area").LoadingOverlay("show"); 
            this.submit();
    });*/
    
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
            url: '<?php echo base_url('index.php/bms_task/get_blocks');?>',
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
                $('#assign_to').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already               
                $("#content_area").LoadingOverlay("hide", true);
                loadAssignTo ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
    
    // On document ready
    if($('#property_id').val() != '') {
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
            url: '<?php echo base_url('index.php/bms_task/get_unit');?>',
            data: {'property_id':$('#property_id').val(),'block_id':$('#block_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-validemail="'+item.valid_email+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
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
    
    function loadAssignTo (property_id) { 
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/assign_to');?>',
            data: {'property_id':property_id},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $('#assign_to').html('<option value="">Loading...</option>'); }, //
            success: function(data) {
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.desi_id+'">'+item.desi_name+'</option>';
                    });
                }
                $('#assign_to').html(str);                
                
            },
            error: function (e) {   
                $('#assign_to').html('');
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
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