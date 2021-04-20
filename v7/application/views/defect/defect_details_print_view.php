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
                <h1>Defect Details</h1>
              <div class="box-body" style="padding-top: 10px;">
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >
                        
                        <div class="col-md-6 col-xs-12 col-sm-12 col-xs-12" style="padding: 0;"  >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <div class="form-group">
                                      <label for="property_id">Defect ID:</label>
                                        <?php echo str_pad($defect_details->defect_id, 5, '0', STR_PAD_LEFT);?>
                                    </div>
                                </div>
                                <!--<div class="col-md-12 col-xs-12" >
                                    <div class="form-group">
                                      <label for="property_id">Property Name: </label>
                                        <?php /*echo $defect_details->property_name;*/?>
                                    </div>
                                </div>-->
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Defect Title :</label>
                                        <?php echo $defect_details->defect_name;?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Defect Location:</label>
                                        <?php echo isset($defect_details->defect_location) ? $defect_details->defect_location : ' - ';?>
                                    </div>
                                </div>

                                 <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Created By :</label>
                                                               
                                        <?php 
                                        if(isset($defect_details->created_by)) {
                                        
                                            if($defect_details->created_by == '0'){
                                                echo 'JMB / MC';
                                            } else if($defect_details->created_by == '-1'){
                                                echo 'Resident';
                                            } else {
                                                echo $defect_details->first_name .' '.$defect_details->last_name;
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
                                        <?php echo isset($defect_details->created_date) && $defect_details->created_date != '' && $defect_details->created_date != '0000-00-00' && $defect_details->created_date != '1970-01-01' ? date('d-m-Y', strtotime($defect_details->created_date)) : ''; ?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Status :</label>
                                        <span id="task_status_span" <?php echo isset($defect_details->defect_status) && $defect_details->defect_status == 'C' ? 'class="text-success"': '';?>>
                                            <?php
                                            echo !empty($defect_details->defect_status) && $defect_details->defect_status == 'O'? ' Open ' : ' Close ';
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <?php if(!empty ($defect_details->defect_close_remarks)) { ?>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Close Remarks :</label>
                                            <span id="task_close_span"><?php echo isset($defect_details->defect_close_remarks) ? $defect_details->defect_close_remarks : ' - ';?></span>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 col-xs-12" style="padding:5px 0 0 0;">
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
                                      <?php echo isset($defect_details->unit_no) ? $defect_details->unit_no : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Unit Status: </label>
                                      <?php echo isset($defect_details->unit_status_name) ? $defect_details->unit_status_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Name: </label>
                                        <?php echo isset($defect_details->owner_name) ? $defect_details->owner_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Contact: </label>
                                            <?php echo isset($defect_details->contact_1) ? $defect_details->contact_1 : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Email: </label>
                                        <?php echo isset($defect_details->email_addr) ? $defect_details->email_addr : ' - ';?>
                                    </div>
                                </div>
                                
                            </div>
                            </div>
                            
                        </div>
                        
                        
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
              </div> <!-- /.box-body -->
              <div style="clear: both;"></div>
              <?php if(!empty($defect_images)) {  ?>
              <div class="col-md-12 col-xs-12" style="background-color:#FFF; padding: 0px 10px;" >
              <div class="col-md-12 col-xs-12"  style="border: 1px solid #999;border-radius: 5px;">
              <!-- The container for the uploaded files -->
              
                <div id="img_preview" class="row" style="margin:0 0 10px 0;<?php echo isset($act) && $act == 'pdf' ? 'padding:10px;' : '';?>">
                    <label>Images :</label>
                        <div class="col-md-12" style="padding: 0 0 10px 0;min-height:150px;">                                        
                                
                                <?php foreach ($defect_images as $tkey=>$tval) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding: 0;">                                               
                                    <img class="img-responsive center-block img_view img_view_<?php echo $defect_id;?>"  style="max-height: 200px; max-width: 150px;cursor: pointer;" src="<?php echo base_url().'bms_uploads/defect_uploads/'.$defect_id.'/'.$defect_images[$tkey]['img_name'];?>" />
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