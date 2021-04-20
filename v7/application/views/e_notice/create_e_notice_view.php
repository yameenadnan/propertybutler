<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_e_notice/create_e_notice_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    
                                    <div class="form-group">
                                      <label >Property Name *</label>
                                        <select class="form-control" id="property_id" name="notice[property_id]">
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = isset($unit_info['property_id']) && $unit_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';  
                                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        
                                    </div>
                                   
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    <div class="form-group">
                                      <label for="">Block/Street *</label>
                                      <select class="form-control" name="notice[block_id]" id="block_id">
                                            <option value="">Select</option>                                
                                          </select>
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Sending Date </label>
                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control" disabled="disabled" value="<?php echo date('d-m-Y');?>" type="text" />
                                              <input type="hidden" name="notice[start_date]" value="<?php echo date('d-m-Y');?>" />
                                            </div>
                                            <!-- /.input group -->
                                          </div>
                                    </div>
                                    <!--div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>End Date </label>
                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control datepicker" name="notice[end_date]" id="datepicker" type="text" />
                                            </div>
                                            
                                          </div>
                                    </div-->
                                </div>                           
                            
                        </div>
                        
                        <div class="col-md-6 col-xs-12 no-padding">
                            
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <label for="">Units</label>                                      
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 unit_div"  style="padding:0px;margin-bottom:5px;max-height: 200px; overflow-y: scroll;">
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                        Please select Property Name and Block / Street to load Units.
                                    </div>
                                    
                                </div>                            
                            
                        </div> <!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-9 col-xs-12">
                                <div class="form-group">
                                  <label>Subject *</label>
                                  <input type="text" id="subject" name="notice[subject]" class="form-control" placeholder="Enter Notice Subject" maxlength="250">
                                </div>
                            </div>                    
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-9 col-xs-12">
                                <div class="form-group">
                                  <label>Message *</label>
                                  <textarea name="notice[message]" class="form-control" rows="4" placeholder="Enter Notice Message"></textarea>
                                </div>
                            </div>
                    
                    </div>
                    
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="">Attachment</label>
                              <div >
                            		<label class="btn-bs-file btn btn-primary">
                                    Choose File...
                            			
                            			<!--input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="document" size="40"  onchange='$("#upload-file-info").html($(this).val());'-->
                                        <input type="file" id="attach_file" name="attach" size="40" onchange='$("#upload-file-info").html($(this).val());' />
                            		</label>
                            		&nbsp;
                            		<span class='label label-info' id="upload-file-info"></span>
                        	  </div>
                            </div>
                        </div>
                    </div>
                    
                  </div><!-- . row -->
                  
                  <!-- . row -->
                        
                
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
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>

$(document).ready(function () {
    
    // right side box hight adjustments    
    /*if($('.cust-container-fluid').width() > 715) {
        $('.right-box').height($('.left-box').height());
    }*/
    
    /*if($('#unit_id').val() != '') {        
        loadBlock ('<?php echo isset($unit_info['block_id']) && $unit_info['block_id'] != '' ? $unit_info['block_id'] : '';?>');
    }*/
    $('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
    
    /** Form validation */   
    $( "#bms_frm" ).validate({
		rules: {
			"notice[property_id]": "required",
            "notice[block_id]": "required",
            "notice[start_date]": "required", 
            "notice[subject]": "required",
            "notice[message]": "required",          
            
		},
		messages: {
			"notice[property_id]": "Please select Property Name",
            "notice[block_id]": "Please select Block/Street",
            "notice[start_date]": "Please select Start Date",   
            "notice[subject]": "Please enter Subject",
            "notice[message]": "Please enter Message",          
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else if ( element.hasClass( "datepicker" ) ) {
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
    
    // On property name change
    $('#property_id').change(function () {
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
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select</option>'; 
                if(data.length > 0) {   
                    str += '<option value="0">All</option>'; 
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str); 
                $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
                //unset_resident_info(); // unset the resident onfo if loaded already
                $('#assign_to').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already               
                $("#content_area").LoadingOverlay("hide", true);
                //loadAssignTo ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
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
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<div class="form-group"> <div class="checkbox"> <label><input type="checkbox" name="unit_all" value="unit_all" class="notice_all_chk"><b>All</b></label></div></div>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<div class="col-xs-6"><div class="form-group">';
                        str += '<div class="checkbox">';
                        str += '<label><input name="notice_unit[]" value="'+item.unit_id+'"  type="checkbox" class="notice_chk"> '+item.unit_no+' </label>';
                        str += '</div>';
                        str += '</div></div>';
                        //str += '<option value="'+item.unit_id+'" data-owner="'+item.owner_name+'" data-gender="'+item.gender+'" data-status="'+item.unit_status+'" data-contact="'+item.contact_1+'" data-email="'+item.email_addr+'" data-defaulter="'+item.is_defaulter +'">'+item.unit_no+'</option>';
                    });
                }
                //console.log(str);
                  $('.unit_div').html(str);   
                //unset_resident_info(); // unset the resident onfo if loaded already             
                $("#content_area").LoadingOverlay("hide", true);
                $("#content_area").LoadingOverlay("hide", true);
                
                $(".notice_all_chk").unbind("change");
                $(".notice_chk").unbind("change");
                $(".notice_all_chk").bind("change",function () {
                    $(".notice_chk").prop('checked', $(this).prop("checked"));
                });
            
                $('.notice_chk').bind("change",function(){ 
                    if(false == $(this).prop("checked")){ 
                        $(".notice_all_chk").prop('checked', false); 
                    }                    
                    if ($('.notice_chk:checked').length == $('.notice_chk').length ){
                        $(".notice_all_chk").prop('checked', true);
                    }
                });
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });
     
   
             
});

/*function loadBlock (block_id) {
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
                        var selected = block_id != '' && block_id == item.block_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.block_id+'" '+selected+'>'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str);               
                $("#content_area").LoadingOverlay("hide", true);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}*/

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });   
    
  })
</script>