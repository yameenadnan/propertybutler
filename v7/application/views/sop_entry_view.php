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
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary" style="margin-bottom: 0px !important;">
            
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_sop/keyin_entry_submit');?>" method="post"  enctype="multipart/form-data" >
                  
                  <!--div class="box-body">
                    <div class="box-header with-border" style="padding: 10px 0;">
                    <h3 class="box-title" style="font-weight: bold;">SOP</h3>
                  </div-->
                  <?php 
                    // for SOP entry overwrite
                    if(!empty($_GET['property_id']) && !empty($_GET['report_date'])) {
                        echo "<input type='hidden' name='ow_property_id' value='".$_GET['property_id']."' />";
                        echo "<input type='hidden' name='ow_report_date' value='".$_GET['report_date']."' />";                            
                    } 
                  
                  ?>
                  
                  <div class="row" style="padding: 10px 0 0 10px;">
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Property Name : </label>
                            <?php echo $sop[0]['property_name'];?>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                          <label>Assigned To : </label>
                          <?php echo $sop[0]['desi_name'];?>
                        </div>
                    </div>                                                                             
                    
                </div>
                
                <?php 
                    
                    if(!empty($sop)){
                        foreach ($sop as $key=>$val) {
                    
                ?>
                
                
                
                <div class="row" style="margin: 0 0 5px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;">Routine Task</h3>
                    </div>
                  
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label>Routine Task Title : </label>
                        <?php echo $val['sop_name'];?>
                    </div>
                    
                    <!--div class="col-md-3 col-xs-6">
                        <div class="form-group">
                            <label>Start Date :</label>
                            <?php echo isset($val['start_date']) ? date('d/m/Y',strtotime($val['start_date'])) : ' - ';?>          
                            
                          </div>
                    </div> 
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                            <label> Due Date : </label>
            
                            <?php   if(isset($val['no_due_date']) && $val['no_due_date'] == 1)
                                        echo 'No Due Date.';
                                    else 
                                        echo isset($val['due_date']) ? date('d/m/Y',strtotime($val['due_date'])) : ' - ';?>
                          </div>
                    </div>  
                    
                    <div class="col-md-6 col-xs-12" style="margin-bottom: 0px;">
                    
                        <div class="form-group">
                            <label> Task Day(s) : </label>
                                
                                <?php   
                                $result_str = '';
                                if(isset($val['mon']) && $val['mon'] == 1)
                                    $result_str .= 'Mon';
                                if(isset($val['tue']) && $val['tue'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : ''). 'Tue';
                                if(isset($val['wed']) && $val['wed'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Wed';
                                if(isset($val['thu']) && $val['thu'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Thu';
                                if(isset($val['fri']) && $val['fri'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Fri';
                                if(isset($val['sat']) && $val['sat'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sat'; 
                                if(isset($val['sun']) && $val['sun'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sun';
                                echo $result_str;           
                            ?>
                         </div> 
                    
                    </div>
                    
                    <div class="col-md-3 col-xs-6">
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Execute Time : </label>
                                <?php echo isset($val['execute_time']) && $val['execute_time'] != '' ? date('h:i:s a',strtotime($val['execute_time'])) : ' - ';?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xs-6">
                        
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Due By : </label>
                              <?php echo isset($val['due_by']) && $val['due_by'] != '' ? date('h:i:s a',strtotime($val['due_by'])) : ' - ';?>                              
                            </div>
                        </div>
                        
                    </div-->
                    
                    
                    <!--div class="col-md-6 col-xs-6">
                        <div class="form-group">
                          <label>Reminder : </label>
                          <?php echo isset($val['reminder']) && $val['reminder'] != '' ? $val['reminder'].' Min(s)' : ' - ';?>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <label>Repeat Timer : </label>            
                            <?php echo isset($val['repeat_rem']) && $val['repeat_rem'] != '' ? $val['repeat_rem'].' Min(s)' : ' - ';?>
                          </div>
                    </div-->  
                    
                    
                    <!--div class="col-md-6 col-xs-12" style="">
                        <div class="form-group">
                          <label>Requirement Type : </label>            
                            <?php echo isset($val['condition_req']) && $val['condition_req'] == '1' ? 'Condition Required ' : ' Reading Required ';?>
                          </div>
                    </div-->
                    
                    <div class="col-md-12 col-xs-12" style="padding: 0;margin-top: 10px;">
                        <!--div class="box-header with-border" style="padding: 15px 0 10px 15px; ">
                            <h3 class="box-title text-info" style="font-weight: bold;">SOP <?php echo $key+1;?> Entry:</h3>
                        </div-->
                          <input type="hidden" name="property_id" value="<?php echo $val['property_id']?>" />
                        <input type="hidden" name="sop_id" value="<?php echo $val['sop_id']?>" />
                        <div class="col-md-6 col-xs-12" >
                            <div class="form-group">
                              <?php if( isset($val['condition_req']) && $val['condition_req'] == '1' ) { 
                                $validation_rule = '"sop_condi":"required"';
                                $validation_msg = '"sop_condi":"Please select Condition"';
                                ?>
                                <input type="hidden" name="requirement_type" value="C">
                                <div>
                                <label> Condition : &ensp;</label>
                                  <div class="radio-inline">                                
                                      <input name="sop_condi" class="sop_<?php echo $val['sop_id'];?>" value="Y" type="radio"> Ok                                
                                  </div>
                                  <div class="radio-inline">                                
                                      <input name="sop_condi" class="sop_<?php echo $val['sop_id'];?>" value="N" type="radio"> Not Ok                                
                                  </div>
                                  <div class="input-group" style="display: inline;padding-left: 15px;">
                                    <button class="sop_img btn btn-success"><i class="glyphicon glyphicon-plus"></i>
                                    <span>Add Images...</span></button>
                                    		
                               	  </div>
                                  </div>
                                  <div style="clear: both;"></div>
                                  <div class="col-md-12" style="padding: 0;">
                                  <!-- The container for the uploaded files -->
                                    <div id="sop_img_preview" class="row" style="margin: 10px;"></div>
                                  </div>
                             <?php } else { 
                                $validation_rule = '"sop_reading":"required"';
                                $validation_msg = '"sop_reading":"Please Enter Reading "';
                                ?>
                             
                                  <input type="hidden" name="requirement_type" value="R">
                                  <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;"> Reading : </label>
                                  <div class="col-xs-9 col-sm-10">
                                    <div class="input-group">
                                        <input type="number" name="sop_reading" class="form-control inline sop_<?php echo $val['sop_id'];?>" placeholder="Enter Reading">
                                        <!--span class="input-group-btn">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-camera"></i></button>
                                        </span-->
                                        
                                        <div class="input-group-btn">
                                    		<!--a class='btn btn-primary' href='javascript:;'>
                                    			Browse Image <input type="file" name="sop_document"  style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                                    		</a-->
                                            <button class="sop_img btn btn-success"><i class="glyphicon glyphicon-plus"></i>
                                            <span>Add Images...</span></button>
                                    		
                                	  </div>
                                      
                                    </div>
                                    &nbsp;
                                      <span class='label label-info upl-file-info' id="upload-file-info"></span>
                                    <span class="text-danger" style="font-size: 10px;" >Last Reading:  - &ensp; &ensp;</span> &ensp; &ensp; 
                                    <span class="text-danger" style="font-size: 10px;" >Variance: - </span> 
                                  </div>
                                  <div style="clear: both;"></div>
                                  <div class="col-md-12" style="padding: 0;">
                                  <!-- The container for the uploaded files -->
                                    <div id="sop_img_preview" class="row" style="margin: 10px;"></div>
                                  </div>                          
                              <?php } ?>
                              </div>
                        </div>
                        <div class="col-md-6 col-xs-12" style="">
                            <div class="form-group">                          
                                <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;">Remarks:</label>                            
                                <div class="col-xs-9 col-sm-10">
                                    <textarea name="remarks" class="form-control inline" placeholder="Enter Remarks"></textarea>
                                </div>                              
                            </div>
                        </div>
                    </div>
                    
                    
                    </div> <!-- /.row -->
                    <!-- Sub SOP tasks if any -->
                    <?php if(!empty($sub_sop[$key])) {
                            
                            foreach ($sub_sop[$key] as $key2=>$val2) { ?>
                            
                            <input type="hidden" name="sub_sop[<?php echo $key2;?>][sop_sub_id]" value="<?php echo $val2['sop_sub_id']?>" />
                            <!--input type="hidden" name="sub_sop[<?php echo $key2;?>][sop_id]" value="<?php echo $val['sop_id']?>" /-->
                            <div class="row sub_sop_sop_1 sub_sop_details" style="padding: 10px 0 5px 35px;">
                            	<div class="box-header with-border">
                            		<h3 class="box-title" style="font-weight: bold;">
                            			Sub Routine Task <?php echo $key2+1;?>
                            		</h3>
                            	</div>
                            	<div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                            		<label> Sub Routine Task Title : </label>
                            		<?php echo isset($val2['sub_sop_name']) && $val2['sub_sop_name'] != '' ? $val2['sub_sop_name'] : ' - ';?>
                            	</div>
                                <!--div class="col-md-6 col-xs-12" style="">
                                    <div class="form-group">
                                      <label>Requirement Type : </label>            
                                        <?php echo isset($val['condition_req']) && $val['condition_req'] == '1' ? 'Condition Required ' : ' Reading Required ';?>
                                      </div>
                                </div-->
                                
                                
                                <div class="col-md-12 col-xs-12" style="padding: 0;margin-top: 10px;">
                                    <!--div class="box-header with-border" style="padding: 15px 0 10px 15px; ">
                                        <h3 class="box-title text-info" style="font-weight: bold;">Sub SOP <?php echo $key2+1;?> Entry:</h3>
                                    </div-->
                                    <div class="col-md-6 col-xs-12" style="">
                                        <div class="form-group">
                                          <?php if( isset($val2['condition_req']) && $val2['condition_req'] == '1' ) { 
                                            $validation_rule .= ',"sub_sop['.$key2.'][condi]":"required"';
                                            $validation_msg .= ',"sub_sop['.$key2.'][condi]":"Please select Condition"';
                                            ?>
                                            <input type="hidden" name="sub_sop[<?php echo $key2;?>][requirement_type]" value="C">
                                            <label> Condition : &ensp;</label>
                                              <div>
                                                  <div class="radio-inline">                                
                                                      <input name="sub_sop[<?php echo $key2;?>][condi]" id="optionsRadios1" value="Y" type="radio"> Ok                                
                                                  </div>
                                                  <div class="radio-inline">                                
                                                      <input name="sub_sop[<?php echo $key2;?>][condi]" id="optionsRadios2" value="N" type="radio"> Not Ok                                
                                                  </div>
                                                  
                                                  <div class="input-group" style="display: inline;padding-left: 15px;">
                                                    <button class="sop_sub_img btn btn-success" data-id="<?php echo $key2;?>"><i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add Images...</span></button>                                                		
                                               	  </div>
                                              </div>
                                              <div style="clear: both;"></div>
                                              <div class="col-md-12" style="padding: 0;">
                                              <!-- The container for the uploaded files -->
                                                <div id="sop_sub_img_preview_<?php echo $key2;?>" class="row" style="margin: 10px;"></div>
                                              </div>
                                         <?php } else { 
                                            $validation_rule .= ',"sub_sop['.$key2.'][reading]":"required"';
                                            $validation_msg .= ',"sub_sop['.$key2.'][reading]":"Please Enter Reading "';
                                            ?>
                                              <input type="hidden" name="sub_sop[<?php echo $key2;?>][requirement_type]" value="R">
                                              <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;"> Reading : </label>
                                              <div class="col-xs-9 col-sm-10">
                                                <div class="input-group">
                                                    <input type="number" name="sub_sop[<?php echo $key2;?>][reading]" class="form-control inline" placeholder="Enter Reading">
                                                    <!--span class="input-group-btn">
                                                        <button class="btn btn-primary" type="button"><i class="fa fa-camera"></i></button>
                                                    </span-->
                                                    <div class="input-group-btn">
                                                		<!--a class='btn btn-primary' href='javascript:;'>
                                                			Browse Image <input type="file" name="sub_sop_doc[<?php echo $key2;?>]" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='$("#sub-sop-file-info-<?php echo $key2+1;?>").html($(this).val());'>
                                                		</a-->
                                                        
                                                        <button class="sop_sub_img btn btn-success" data-id="<?php echo $key2;?>" ><i class="glyphicon glyphicon-plus"></i>
                                                        <span>Add Images...</span></button>
                                                		
                                            	  </div>
                                                </div>
                                                &nbsp;
                                                		<span class='label label-info upl-file-info' id="sub-sop-file-info-<?php echo $key2+1;?>"></span>
                                                <span class="text-danger" style="font-size: 10px;" >Last Reading:  - &ensp; &ensp;</span> &ensp; &ensp; 
                                                <span class="text-danger" style="font-size: 10px;" >Variance: - </span> 
                                              </div>
                                              
                                              <div style="clear: both;"></div>
                                              <div class="col-md-12" style="padding: 0;">
                                              <!-- The container for the uploaded files -->
                                                <div id="sop_sub_img_preview_<?php echo $key2;?>" class="row" style="margin: 10px;"></div>
                                              </div>                         
                                          <?php } ?>
                                          </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12" style="">
                                        <div class="form-group">                          
                                            <label class="col-xs-3 col-sm-2 control-label" style="padding: 5px 0;">Remarks:</label>                            
                                            <div class="col-xs-9 col-sm-10">
                                                <textarea name="sub_sop[<?php echo $key2;?>][remarks]" class="form-control inline" placeholder="Enter Remarks"></textarea>
                                            </div>                              
                                        </div>
                                    </div>
                                </div>
                                
                             </div>
                                
                           <?php  }
                        
                    } ?>
                  
            </div>
                
                    
                <?php                 
                        }
                } ?>
                              
                
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="col-md-12" style="padding-top:20px;">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button> &ensp;
                    <button type="Reset" class="btn btn-default reset_btn">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div>
              <!--div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('index.php/bms_sop/sop_list');?>'">Back</button> &ensp;                    
                  </div>
                </div>
              </div-->
            </form>
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>


var img_total_size = 0;
$('form button.sop_img').click(function(e) {
    e.preventDefault();
    var nb_attachments = $('form input').length;
    var $input = $('<input type="file" name="sop_img[]">');
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
                $('#sop_img_preview').append('<div class="col-xs-6 col-sm-4 col-md-3"><img class="img-responsive" src="'+e.target.result+'" /><br />'+f.name+'</div>');
              //$('#img_preview').attr('src', e.target.result);
            }    
            reader.readAsDataURL(evt.target.files[0]);
        } else {
             $('#sop_img_preview').append('<div class="col-xs-6 col-sm-4 col-md-3"><br />'+f.name+'</div>');
        }
        //console.log(img_total_size +'bytes  ' + (img_total_size/1024) + 'KB  ' + (img_total_size/(1024*1024))+ 'MB');
    });
    $input.hide();
    $input.trigger('click');    
});

$('form button.sop_sub_img').click(function(e) {
    e.preventDefault();
    //var nb_attachments = $('form input').length;
    var ele_id = $(this).attr('data-id');
    var $input = $('<input type="file" name="sop_sub_img['+ele_id+'][]">');
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
                $('#sop_sub_img_preview_'+ele_id).append('<div class="col-xs-6 col-sm-4 col-md-3"><img class="img-responsive" src="'+e.target.result+'" /><br />'+f.name+'</div>');
              //$('#img_preview').attr('src', e.target.result);
            }    
            reader.readAsDataURL(evt.target.files[0]);
        } else {
             $('#sop_sub_img_preview_'+ele_id).append('<div class="col-xs-6 col-sm-4 col-md-3"><br />'+f.name+'</div>');
        }
        //console.log(img_total_size +'bytes  ' + (img_total_size/1024) + 'KB  ' + (img_total_size/(1024*1024))+ 'MB');
    });
    $input.hide();
    $input.trigger('click');
    
});

$(document).ready(function () {
    
    $("#bms_frm" ).validate({
		rules: {
			<?php echo $validation_rule;?>    
		},
		messages: {
			<?php echo $validation_msg;?>
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "text" ) {
				error.insertAfter( element.parent( "div" ) );
			} else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "div" ).parent('div') );
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
           $( "#bms_frm").submit();
        }
	});
    
    $('.reset_btn').click(function () {
        //console.log('reset clicked');
        $('input[type=file]').val('');
        $('#upload-file-info').html('');        
    });
    
});

/*function loadOverSeeingJMB (property_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sop/get_ov_jmb');?>', // Reusing the same function from task creation
            data: {'property_id':property_id},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $('#sop_ov_jmp').html('<option value="">Loading...</option>'); }, //
            success: function(data) {
                var str = '';                 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.MemPosition+'">'+item.MemPosition+'</option>';
                    });
                }                
                $('#sop_ov_jmp').html(str); 
            },
            error: function (e) {
                $('#sop_ov_jmp').html('');
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}*/

$(function () {
    
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    });
    
    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>