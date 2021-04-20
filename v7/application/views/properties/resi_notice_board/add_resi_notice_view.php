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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/add_resi_notice_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                        <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 ">
                                    
                                    <div class="form-group">
                                      <label >Property Name *</label>
                                        <select class="form-control" id="property_id"  <?php echo !empty($resi_notice) ? 'disabled="disabled"' : 'name="resi_notice[property_id]"';?>  >
                                            <option value="">Select Property</option>
                                            <?php 
                                                foreach ($properties as $key=>$val) { 
                                                    $selected = '';
                                                    if(isset($resi_notice['property_id'])) {
                                                        $selected = isset($resi_notice['property_id']) && $resi_notice['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                    } else if(isset($_SESSION['bms_default_property'])) {
                                                        $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                                    }
                                                    //$selected = isset($resi_notice['property_id']) && $resi_notice['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                } ?> 
                                        </select>
                                        
                                        <?php if(!empty($resi_notice)) { echo '<input type="hidden" name="resi_notice[property_id]" value="'.$resi_notice['property_id'].'">'; } ?>
                                        <input type="hidden" id="resident_notice_id" name="resi_notice[resident_notice_id]" value="<?php echo $resi_notice_id;?>"/>
                                        
                                    </div>
                                   
                                </div>
                                                               
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Start Date *</label>
                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control datepicker" name="resi_notice[start_date]" value="<?php echo !empty($resi_notice['start_date']) ? $resi_notice['start_date'] : '';?>" type="text" />
                                              
                                            </div>
                                            <!-- /.input group -->
                                          </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>End Date *</label>
                            
                                            <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                              <input class="form-control datepicker" name="resi_notice[end_date]" value="<?php echo !empty($resi_notice['end_date']) ? $resi_notice['end_date'] : '';?>" type="text" />
                                            </div>
                                            
                                          </div>
                                    </div>
                                </div>                           
                            
                        </div>
                        
                    </div> <!-- . col-md-12 -->
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-9 col-xs-12">
                                <div class="form-group">
                                  <label>Notice Title *</label>
                                  <input type="text" id="subject" name="resi_notice[notice_title]" class="form-control" value="<?php echo !empty($resi_notice['notice_title']) ? $resi_notice['notice_title'] : '';?>" placeholder="Enter Notice Title" maxlength="150">
                                </div>
                            </div>                    
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-9 col-xs-12">
                                <div class="form-group">
                                  <label>Notice Message *</label>
                                  <textarea name="resi_notice[message]" class="form-control" rows="6" placeholder="Enter Notice Message"><?php echo !empty($resi_notice['message']) ? $resi_notice['message'] : '';?></textarea>
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
                            
                            <input type="hidden" name="attachment_name_old" value="<?php echo !empty($resi_notice['attachment_name']) ? $resi_notice['attachment_name'] : '';?>" />
                            <?php if(!empty($resi_notice['attachment_name'])) { 
                                    $resident_notice_attach = $this->config->item('resident_notice_attach_upload');
                                    
                                    ?>
                                   <div class="form-group">
                                   <label><a href="<?php echo '../../../'.$resident_notice_attach['upload_path'].$resi_notice['property_id'].'/'.$resi_notice['attachment_name'];?>" target="_blank" >Click here to view / download Current Attachment</a></label><br />                                    
                                   </div>
                                <?php } ?>
                                
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
			"resi_notice[property_id]": "required",
            "resi_notice[start_date]": "required",
            "resi_notice[end_date]": "required", 
            "resi_notice[notice_title]": "required",
            "resi_notice[message]": "required",          
            
		},
		messages: {
			"resi_notice[property_id]": "Please select Property Name",
            "resi_notice[start_date]": "Please select Start Date",
            "resi_notice[end_date]": "Please select End Date ",   
            "resi_notice[notice_title]": "Please enter Notice Title ",
            "resi_notice[message]": "Please enter Notice Message",          
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
    /*$('#property_id').change(function () {
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
    });*/
    
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