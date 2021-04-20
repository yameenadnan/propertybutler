<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler |' ;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_media_query.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_custom_styles.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<style>
.pin_div > button { font-size:28px !important }
.result_div > div.page-header > h3 { color:green; } 
.result_div>div>div  { font-size:18px !important; } 
.result_div { margin-top:5px;padding:5px;border: 1px solid #666;border-radius: 5px; }
.page-header { margin-bottom: 0px !important; }
h3 { margin-top: 10px !important; margin-bottom: 5px; }

</style>
<body class="hold-transition skin-blue ">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area" style="background-color: #fff;margin-left: 0px;">
   
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <div class="box box-primary" style="border-top: none;">    
        <div class="col-md-12 col-xs-12" style="padding-bottom: 10px;padding-left:10px !important">
            Number Of Eligible Voters: <b><?php echo $no_of_ev['cnt'];?> </b> &ensp;| &ensp; Number Of Attendees: <b><?php echo $no_of_attendees['cnt'];?></b>           
        </div>
        <div class="box-body">
            
                    <div class="col-md-12 col-xs-12 no-padding" >
                        <div class="col-md-12 col-xs-12 no-padding" style="margin-bottom: 15px;">
                            
                        </div>
                    </div>
                
                <!-- AGM -->                
                
               
               <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  
                  <th>Unit(s)</th>
                  <th>User Name</th>
                  <th>Mobile No</th>
                  <th>Image</th>
                  
                </tr>
                </thead>
                <tbody id="content_tbody">
                       <?php 
                    //$offset = 0;
                    
                    if(!empty($units)) {
                        
                        foreach ($units as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($key+1);?></td>                                
                            <td style="width: 350px;"><?php echo !empty($val['unit_nos']) ? str_replace(',','<br />',$val['unit_nos']) : '-';?></td>                                
                            <td ><?php echo !empty($val['user_name']) ? $val['user_name'] : '-';?></td> 
                            <td ><?php echo !empty($val['mobile_no']) ? $val['mobile_no'] : '-';?></td> 
                            <td ><?php echo !empty($val['image_name']) && !empty($val['created_date']) ? '<img class="img-responsive center-block img_view" style="max-height: 100px; max-width: 100px;cursor: pointer;" src="'.base_url().'bms_uploads/agm_attendance/'.date('Y',strtotime($val['created_date'])).'/'.date('m',strtotime($val['created_date'])).'/'.$val['created_date'].'/'.$val['agm_id'].'/'.$val['image_name'].'" />' : '';?></td>
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="hidden-xs text-center" colspan="9">No Record Found</td>
                            <td class="visible-xs text-center" colspan="9">No Record Found</td>
                        </tr>                    
                    <?php } ?>                       
                </tbody>                
              </table>
               
            
            
            </div><!-- /.box-body -->
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>

<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

$(document).ready(function () {
    window.print();
});
</script>
</body>
</html>