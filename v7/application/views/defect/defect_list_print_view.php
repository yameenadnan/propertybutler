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

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary" style="border-top: none;"
            <!-- /.box-header -->
              <!-- /.box-body -->
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>S No</th>
                  <th>Block</th>
                  <th>Unit No.</th>
                  <th>Defect ID</th>
                  <th>Defect Title</th>
                  <th>Defect Location</th>
                  <th>Created date</th>
                  <th>Status</th>
                </tr>
                </thead>
                <tbody id="defect_tbody">
                <?php if (!empty ($defects['records'])) {
                    $counter = 0;
                    foreach ( $defects['records'] as $key=>$val ) { ?>
                    <tr>
                        <td><?php echo ++$counter;?></td>
                        <td><?php echo $val['block_name'];?></td>
                        <td><?php echo $val['unit_no'];?></td>
                        <td><?php echo str_pad($val['defect_id'], 5, '0', STR_PAD_LEFT);?></td>
                        <td><?php echo $val['defect_name'];?></td>
                        <td><?php echo $val['defect_location'];?></td>
                        <td><?php echo date('d-m-Y', strtotime($val['created_date']));?></td>
                        <td><?php echo !empty($val['defect_status']) && $val['defect_status'] == 'O' ? ' Open ':' Close ';?></td>
                    </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="8">No record found</td>
                    </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
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