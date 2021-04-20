<!DOCTYPE html>

<html  moznomarginboxes mozdisallowselectionprint>
<head>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
   <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_media_query.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_custom_styles.css">

  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
<style>@page { size: auto;  margin: 2mm 3mm 2mm 5mm; }</style>
</head>
<body class="hold-transition skin-blue ">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area" style="background-color: #fff;margin-left: 0px;">
   
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

              <div class="box box-primary" style="border-top: none;">
            
      <h1>        Minor Task Details    </h1>
      
    
            
              <div class="box-body" style="padding-top: 10px;">
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >
                        
                        <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <div class="form-group">
                                      <label for="property_id">Task ID: </label>
                                        <?php echo str_pad($task_details->task_id, 5, '0', STR_PAD_LEFT);?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12" >
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
                        
                        <div class="col-md-6 col-sm-12 col-xs-12" style="padding:5px 0 0 0;">
                            <div class="col-md-6 col-sm-6 col-xs-6 no-padding right-box1" style="border: 1px solid #999;border-radius: 5px;">
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
                            
                            <div class="col-md-6 col-sm-6 col-xs-6 no-padding right-box2" style="padding-left: 10px !important;" >
                                <div class="col-md-12 col-xs-12" style="border: 1px solid #999;border-radius: 5px;">
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
                                
                                <?php if(!empty ($task_details->task_close_remarks)) { ?>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Close Remarks : </label>
                                        <span id="task_close_span">
                                        
                                          <?php echo isset($task_details->task_close_remarks) ? $task_details->task_close_remarks : ' - ';?>
                                        </span>
                                        <!-- /.input group -->
                                      </div>
                                </div>
                                <?php } ?>
                                
                                </div> 
                                
                                 
                            </div>
                            
                        </div>
                        
                        
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
              </div> <!-- /.box-body -->
              <div style="clear: both;"></div>
              <?php if(!empty($task_images)) {  ?>
              <div class="col-md-12 col-xs-12" style="background-color:#FFF; padding: 0px 10px;" >
              <div class="col-md-12 col-xs-12"  style="border: 1px solid #999;border-radius: 5px;">
              <!-- The container for the uploaded files -->
              
                <div id="img_preview" class="row" style="margin:0 0 10px 0;<?php echo isset($act) && $act == 'pdf' ? 'padding:10px;' : '';?>">
                    <label>Images :</label>
                        <div class="col-md-12" style="padding: 0 0 10px 0;min-height:150px;">                                        
                                
                                <?php foreach ($task_images as $tkey=>$tval) { ?>  
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding: 0;">                                               
                                    <img class="img-responsive center-block img_view img_view_<?php echo $task_id;?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo base_url().'bms_uploads/task_uploads/'.$task_id.'/'.$task_images[$tkey]['img_name'];?>" />
                                </div>
                                <?php } ?>
                        </div>
                    
                </div>
                </div>
              </div>
              <?php } ?>
            
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper --> 
  
</div>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>
<script>

$(document).ready(function () {
    window.print();
});
</script>
</body>
</html>