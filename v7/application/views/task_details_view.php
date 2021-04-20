<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
  
  <style>
  .btn-bs-file{
        position:relative;
    }
    .btn-bs-file input[type="file"]{
        position: absolute;
        top: -9999999;
        filter: alpha(opacity=0);
        opacity: 0;
        width:0;
        height:0;
        outline: none;
        cursor: inherit;
    }
  </style>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ' - '; ?>
        &ensp;<a role="button" class="btn btn-primary pull-right" onclick="printDiv('<?php echo base_url('index.php/bms_task/print_task/'.$task_id);?>');" href="javascript:;" >Print</a>
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
            
              <div class="box-body">
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >
                        
                        <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                    <div class="form-group">
                                      <label for="property_id">Task ID: </label>
                                        <?php echo str_pad($task_details->task_id, 5, '0', STR_PAD_LEFT);?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label for="property_id">Property Name: </label>
                                        <?php echo $task_details->property_name;?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Task Name : </label>
                                        <?php echo $task_details->task_name;?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Task Location: </label>
                                        <?php echo isset($task_details->task_location) ? $task_details->task_location : ' - ';?>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-xs-12" style="padding-bottom: 15px;">
                                    <div class="form-group">
                                      <label>Task Details: </label>
                                        <?php echo isset($task_details->task_details) ? $task_details->task_details : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Task Catagory: </label>
                                        <?php 
                                            $task_cat = $this->config->item('task_cat');
                                            echo isset($task_details->task_category) && $task_details->task_category != 0 ? $task_cat[$task_details->task_category] : ' - ';?>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Source Of Assignment: </label>
                                        <?php 
                                            $source_assign = $this->config->item('source_assign');
                                            echo isset($task_details->task_source) && $task_details->task_source != 0 ? $source_assign[$task_details->task_source] : ' - ';?>
                                    </div>
                                </div>
                                 <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Created By : </label>
                                                               
                                        <?php 
                                        if(isset($task_details->created_by)) {
                                        
                                            if($task_details->created_by == '0'){
                                                echo 'JMB / MC';
                                            } else if($task_details->created_by == '-1'){
                                                echo 'Resident';
                                            } else {
                                                echo $task_details->first_name .' '.$task_details->last_name;
                                            }
                                        
                                        } else {
                                        echo ' - ';
                                        }?>
                                            
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                      <label>Created Date :</label>
                                        <?php echo isset($task_details->created_date) && $task_details->created_date != '' && $task_details->created_date != '0000-00-00' && $task_details->created_date != '1970-01-01' ? date('d-m-Y', strtotime($task_details->created_date)) : ''; ?>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6 col-xs-12" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box1" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                    <div class="form-group">
                                      <label for="">Block/Street: </label>
                                        <?php echo isset($block_street->block_name) ? $block_street->block_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Unit No: </label>
                                      <?php echo isset($task_details->unit_no) ? $task_details->unit_no : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Unit Status: </label>
                                      <?php echo isset($task_details->unit_status_name) ? $task_details->unit_status_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Name: </label>
                                        <?php echo isset($task_details->owner_name) ? $task_details->owner_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Contact: </label>
                                            <?php echo isset($task_details->contact_1) ? $task_details->contact_1 : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Email: </label>
                                        <?php echo isset($task_details->email_addr) ? $task_details->email_addr : ' - ';?>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box2" style="margin-top:5px;border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                    <div class="form-group">
                                      <label>Assign To :</label>
                                        <?php echo isset($task_details->desi_name) ? $task_details->desi_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Due Date : </label>
                                            <span id="due_date_span">                            
                                              <?php echo isset($task_details->due_date) ? date('d-m-Y',strtotime($task_details->due_date)) : ' - ';?>
                                            </span>
                                        <!-- /.input group -->
                                      </div>
                                </div> 
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Task Status :</label>
                                        <span id="task_status_span" <?php echo isset($task_details->task_status) && $task_details->task_status == 'C' ? 'class="text-success"': '';?>>
                                            <?php 
                                                $task_status = $this->config->item('task_db_status');
                                                echo isset($task_details->task_status) ? $task_status[$task_details->task_status] : ' - ';
                                                
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Task Update : </label>
                                        <span id="task_update_span">
                                        
                                          <?php 
                                          
                                          if(isset($task_details->task_update)) {
                                            $task_update = $this->config->item('task_update');                                            
                                            echo isset($task_details->task_update) ? $task_update[$task_details->task_update] : ' - ';  
                                          }   else echo ' - ';
                                          ?>
                                        </span>
                                        <!-- /.input group -->
                                      </div>
                                </div> 
                                
                                <div class="col-md-12 col-xs-12 task_close_div" style="display: <?php echo !empty ($task_details->task_close_remarks) ? 'block' : 'none';?>">
                                    <div class="form-group">
                                        <label>Close Remarks : </label>
                                        <span id="task_close_span">                                        
                                          <?php echo isset($task_details->task_close_remarks) ? $task_details->task_close_remarks : ' - ';?>
                                        </span>
                                        <!-- /.input group -->
                                      </div>
                                </div>
                               
                                
                                 
                            </div>
                            
                        </div>
                        
                        
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
              </div> <!-- /.box-body -->
              <div style="clear: both;"></div>
              <?php if(!empty($task_images)) {  ?>
              <div class="col-md-12 col-xs-12" style="border: 1px solid #999;padding: 10px 15px;" >
              <!-- The container for the uploaded files -->
              
                <div id="img_preview" class="row" style="margin: 0px;">
                    <div class="col-md-12" style="padding: 0;min-height:150px;">
                        <div class="col-md-3 col-xs-4" style="padding: 0;"> 
                        <label>Images :</label>
                            <ul style="padding-left:20px ;">
                            <?php foreach ($task_images as $key=>$val) { ?>
                                <li><a href="javascript:;" class="imgs_a" data-value="<?php echo $val['img_name'];?>">image <?php echo $key+1;?> </a></li>
                            <?php } ?>
                                
                            </ul> 
                        </div>
                        
                        <div class="col-md-6 col-xs-8 text-center">  
                            <?php 
                            
                            $file_name = explode('.',$task_images[0]['img_name']);
                            if(strtolower(end($file_name)) == 'pdf') {
                            
                            ?> 
                                <img class="img-responsive center-block img_view" style="display:none;max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../../bms_uploads/task_uploads/'.$task_id.'/'.$task_images[0]['img_name'];?>" /> 
                                <a class="pdf_view" href="<?php echo base_url().'bms_uploads/task_uploads/'.$task_id.'/'.$task_images[0]['img_name'];?>" target="_blank" title="click to view / download">
                                    <img class="img-responsive center-block" style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo base_url().'assets/images/pdf_icon.png';?>" />
                                    <span class="pdf_view_name"><?php echo $task_images[0]['img_name'];?></span>
                                </a>
                            <?php } else { ?>
                                <img class="img-responsive center-block img_view" style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo '../../../bms_uploads/task_uploads/'.$task_id.'/'.$task_images[0]['img_name'];?>" /> 
                                <a class="pdf_view" style="display:none;" href="<?php echo base_url().'bms_uploads/task_uploads/'.$task_id.'/'.$task_images[0]['img_name'];?>" target="_blank" title="click to view / download">
                                    <img class="img-responsive center-block" style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo base_url().'assets/images/pdf_icon.png';?>" />
                                </a>
                            <?php } ?>
                            
                        </div>
                    </div>
                
                </div>
              </div>
              <?php } ?>
              
              <?php if(isset($task_details->assign_to) && $task_details->assign_to == $_SESSION['bms']['designation_id']) { ?>
              <div class="col-md-12 col-xs-12" style="margin-top: 15px;" >
                <div class="col-xs-3 text-center">
                    <a href="javascript:;" title="Update" class="task_update_btn text-success" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-pencil"></i><br /> Update                         
                    </a>
                    
                </div>
                <div class="col-xs-3 text-center">
                    <a href="javascript:;" style="color:#00A65A;" title="Quotation">
                      <i class="fa fa-external-link"></i><br /> Quotation
                    </a>
                </div>
                <div class="col-xs-3 text-center">
                    <a href="javascript:;" id="logBtn" style="color:#00A65A;" title="Log">
                        <i class="fa fa-list-ol"></i><br /> Log
                    </a>
                </div>
                <div class="col-xs-3 text-center">
                    <a href="javascript:;" class="task_update_btn text-success" data-value="close" data-toggle="modal" data-target="#myModal_close" title="Close">
                      <i class="fa fa-window-close"></i><br /> Close
                    </a>
                </div>
              </div>  
              <?php } ?>
              
              <div class="col-md-12 col-xs-12" style="margin-top: 15px;border: 1px solid;"  >
                 <form name="forum_frm" id="forum_frm">             
                    
                    <input type="hidden" name="task_id" value="<?php echo $task_id;?>" />
                    <input type="hidden" id="property_id" name="property_id" value="<?php echo $task_details->property_id;?>" />
                    
                    <div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"> Task Forum:&nbsp; </span></h5></div>
                    <div style="clear: both;height:10px"></div>
                    <div class="col-md-12 col-xs-12 chat_content" style="padding: 0;min-height: 10px;"></div>
                    
                    <div class="col-md-12 col-xs-12" style="padding: 0 0 15px 0;">
                        <div class="col-md-6 col-xs-4" style="padding: 0;">
                            <textarea class="col-md-12 col-xs-12" style="padding: 1px;" name="chat_text" id="chat_text"></textarea>
                        </div>
                        <div class="col-md-6 col-xs-8" style="padding-top: 10px;">
                    		<!--a class='btn btn-primary' href='javascript:;'>
                    			Choose File...
                    			<input type="file"  name="attach" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                    		</a-->
                            <label class="btn-bs-file btn btn-primary">
                                Choose File...
                                <input type="file" id="attach_file" name="attach" onchange='$("#upload-file-info").html($(this).val());' />
                            </label>
                            <span class='label label-info' id="upload-file-info"></span>
                            &ensp;
                    		<input class="btn btn-primary chat_send_btn" type="submit" value="Send" />
                         </div>
                         <!--div class="col-md-3 col-xs-3" style="padding-top: 10px;">                	  
                            <input class="btn btn-primary chat_send_btn" type="submit" value="Send" />
                        </div-->
                    </div>
                 </form>
              
              </div>
                
            
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
              <!-- Modal -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Status Update</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 msg">
            <div class="col-xs-5">Task Update : </div>
            <div class="col-xs-6">
                <select class="form-control" id="task_update">
                    <option value="">Select</option>     
                    <?php 
                        $task_update = $this->config->item('task_update');
                        
                        foreach ($task_update as $key=>$val) {                            
                            echo "<option value='".$key."' >".$val."</option>";
                        }
                    ?>              
                </select>
            </div>
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            <div class="col-xs-5">Due Date : </div>
            <div class="col-xs-6">
                <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input class="form-control pull-right" name="due_date" id="datepicker" type="text">
                    </div>
            </div>
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary task_status_update" data-value="update" id="">Update</button> &ensp;
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="myModal_close" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Close Remarks</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 msg">
            <div class="col-xs-3">Remarks : </div>
            <div class="col-xs-9">
                <textarea id="close_remarks" name="close_remarks" class="form-control" placeholder="Enter Task Close Remarks" /></textarea>
            </div>
        </div>
        <div style="clear: both;height:10px"></div>
        
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary task_status_update" data-value="close" id="">Save</button> &ensp;
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Log Details</h4>
      </div>
      <div class="modal-body modal-body2">
        
        <div class="col-xs-12 msg">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<!-- Modal -->
<div id="fullImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Image</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 full_img">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

  
  
<?php include_once('footer.php');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>



// variable for the set innterval function to keep updating the chat room content
var myInterval;

$(document).ready(function () {
    
    //scroll down the chat content div
    var d = $('.chat_content');
    d.animate({ scrollTop: d.prop('scrollHeight') }, 3000);
    
    //console.log($('.cust-container-fluid').width());
    if($('.cust-container-fluid').width() > 715){
        
         if($('.left-box').height() < (eval($('.right-box1').height()) + eval($('.right-box2').height()) + 5)) {
            //console.log($('.left-box').height()+' = '+)
            $('.left-box').height(eval($('.right-box1').height()) + eval($('.right-box2').height()+5));        
        } 
    } else { 
        $('.right-box1').parent('div').css('padding','10px 0 0 0');        
    }
    
    $('.imgs_a').click(function () {
        var file_name = $(this).attr('data-value');
        var file_name_arr = file_name.split('.');
        if(file_name_arr.pop().toLowerCase() == 'pdf') {
            //console.log(file_name);
            $('.pdf_view').attr('href','<?php echo base_url().'bms_uploads/task_uploads/'.$task_id.'/';?>'+$(this).attr('data-value'));
            $('.pdf_view_name').html(file_name);
            $('.pdf_view').css('display','block');
            $('.img_view').css('display','none');
        } else {            
            $('.img_view').attr('src','<?php echo '../../../bms_uploads/task_uploads/'.$task_id.'/';?>'+$(this).attr('data-value'));
            $('.pdf_view').css('display','none');
            $('.img_view').css('display','block');
        }
        
    });
    
    $('.img_view').bind("click",function () {
        //console.log($(this).attr('data-date'));
        $('.full_img').html('<img src="'+$(this).attr('src')+'" class="img-responsive center-block"/>');
        $('#fullImgModal').modal({show:true});       
    });

    $('.task_status_update').click(function () {
        if($(this).attr('data-value') == 'close') {
            $('#close_remarks').val($('#close_remarks').val().replace(/^\s+|\s+$/g,""));
            if($('#close_remarks').val() == ''){
                alert('Please enter close remarks!');
                $('#close_remarks').focus();
                return false;
            }
        }
        if($(this).attr('data-value') == 'update') {
            $('#task_update').val($('#task_update').val().replace(/^\s+|\s+$/g,""));
            if($('#task_update').val() == '' && $('#datepicker').val() == '') {
                alert('Please select Task Status / Due Date!');
                $('#task_update').focus();
                return false;
            }
        }
        set_task_status($(this).attr('data-value'));
        
    }); 
    
    if('<?php echo isset($task_details->task_status) && $task_details->task_status =='C' ? '1' : '0';?>' == '1') {
        
         $('.task_status_update').unbind('click').removeClass('text-success').addClass('text-secondary').css('cursor','default');
         $('.task_update_btn').removeAttr('data-toggle').removeAttr('data-target').removeClass('text-success').addClass('text-secondary').css('cursor','default');   
         //$('.task_update_btn');
    } 
    
    $('#logBtn').click(function(){
  
      	$('.modal-body2').load('<?php echo base_url('index.php/bms_task/get_log_details/'.$task_id);?>',function(result){
    	    $('#myModal2').modal({show:true});
    	});
    });
    
    $("#forum_frm").on('submit', function(e){
        
        e.preventDefault();
        
        clearInterval(myInterval); // clear the interval time to update the chat content
        
        $('#chat_text').val($('#chat_text').val().replace(/^\s+|\s+$/g,""));
        if($('#chat_text').val() != '' || $('#attach_file').val() != '') {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_task/set_task_forum');?>',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function ( ){ $('.chat_send_btn').attr("disabled","disabled"); $('#fupForm').css("opacity",".5");  }, //
                success: function(data) { 
                    
                    // clear the entered data and update the chat content
                    //$('#chat_text').val('');
                    get_chat_content();
                    myInterval = setInterval(function(){ get_chat_content(); }, 5000);
                    
                    if(data) {
                        $('#forum_frm')[0].reset();
                        $('#upload-file-info').html('');
                    } else {
                        alert('Some Error. Unable to save your comment!');
                        console.log('chat not saved!');
                    }
                    
                    $('.chat_send_btn').removeAttr("disabled");
                    $('#forum_frm').css("opacity","");
                },
                error: function (e) {
                    alert('Some Error. Unable to save your comment!');
                    $('.chat_send_btn').removeAttr("disabled");
                    $('#forum_frm').css("opacity","");
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        } else {
            alert('Please enter text OR Choose file!');
            return false;
        }               
    });
    
    // set innterval function to keep updating the chat room content and directly call for first time content load    
    myInterval = setInterval(function(){ get_chat_content(); }, 30000);
    get_chat_content();
             
});

function printDiv(url) {    
    /*var divToPrint = document.getElementById('printable');
    var popupWin = window.open('', '_blank', 'width=300,height=300');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();*/
    
window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no');
}

function get_chat_content () {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_task/get_task_forum/'.$task_id.'');?>',
        data: {},
        datatype:"html", // others: xml, json; default is html

        beforeSend:function (){  }, //
        success: function(data) {  
            $('.chat_content').html(data); 
            $('.chat_content').css('max-height','250px');           
            
            if($('.chat_content').html() != '') {
                $('.chat_content').css('overflow-y','scroll');
                //$('.chat_content').scrollTop = $('.chat_content').scrollHeight;
                $('.chat_content').stop().animate({
                  scrollTop: $('.chat_content')[0].scrollHeight
                }, 800);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function set_task_status (act_type) {
    var task_update = act_type == 'update' ? $('#task_update').val() : (act_type == 'close' ? 'Closed' : '');
    
    //console.log(task_update);
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_task/set_task_status');?>',
        data: {'task_id':'<?php echo $task_id;?>','property_id':$('#property_id').val(),'task_update':task_update,'due_date':$('#datepicker').val(),'close_rem':$('#close_remarks').val()},
        datatype:"html", // others: xml, json; default is html

        beforeSend:function (){ $("#content_area").LoadingOverlay("show");   }, //
        success: function(data) {  
            $("#content_area").LoadingOverlay("hide", true);     
            
            if(data) {
                if($('#task_update').val() != '') { $('#task_update_span').html($('#task_update').val()); }
                if($('#datepicker').val() != '') { $('#due_date_span').html($('#datepicker').val()); }
                if($('#close_remarks').val() != '') { $('#task_close_span').html($('#close_remarks').val()); $('#task_close_div').css('display','block') }                
                                
                if(task_update == 'Closed') { 
                    $('#task_status_span').html(task_update);
                    $('.task_status_update').unbind('click').removeClass('text-success').addClass('text-secondary').css('cursor','default');
                    $('.task_update_btn').removeAttr('data-toggle').removeAttr('data-target').removeClass('text-success').addClass('text-secondary').css('cursor','default');   
                    $('.task_status_span').addClass('text-success');
                    $('#myModal_close').modal('toggle');
                } 
                     
                alert('Task Updated Successfully!');
                /*str += '<div class="alert alert-success log_err_msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                str += '</strong>Task Updated Successfully</div>';*/
            } else {
                alert('Task Update is not saved!');
            }
            $('#task_update').val('');
            $('#datepicker').val('');
            $('#close_remarks').val('');
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);     
            $('#task_update').val('');
            $('#datepicker').val('');
            $('#close_remarks').val('');
            alert('Task Update Error!');
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
</script>