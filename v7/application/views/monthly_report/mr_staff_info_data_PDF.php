<?php $this->load->view('header.php'); ?>
<?php if(!isset($act) || $act != 'pdf') { $this->load->view('sidebar.php'); } ?>

<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background: none;">
    <?php if(!isset($act) || $act != 'pdf') { ?><!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
    </section>
    <?php } ?>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid d-flex">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
          <div class="box box-primary box-pdf">
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?> >

                    <div class="row" style="margin-top: 15px;">
                      <div class="col-xs-12 col-md-12">
                          <table class="table table-bordered" style="border: 0px;">
                              <tr>
                                  <td colspan="3" style="border: 0;" align="center">
                                      <div style="margin: 0;">
                                          <h1><?php echo $property_data->property_name; ?></h1>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="2" style="padding-top: 50px; border: 0;">
                                      <h4 style="font-size: 19px;">Management Team</h4>
                                  </td>
                                  <td colspan="1" align="right" style="padding-top: 50px; border: 0;">
                                      <h4 style="font-size: 19px;"><u>Month:
                                              <?php
                                              $monthNum  = $report_data->report_month;
                                              $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                              $monthName = $dateObj->format('F');
                                              echo $monthName . ' ' . $report_data->report_year;
                                              ?></u></h4>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="3" style="padding-top: 10px; border: 0; ">&nbsp;</td>
                              </tr>
                              <thead>
                              <tr>
                                  <th style="padding: 3px;">#</th>
                                  <th style="padding: 3px;">Name</th>
                                  <th style="padding: 3px;">Designation</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php
                              if ( !empty( $staff_info ) )  {
                                  $counter = 0;
                                  foreach ( $staff_info as $key=>$val ) { ?>
                                      <tr>
                                          <td style="padding: 3px;"><?php echo ++$counter;?></td>
                                          <td style="padding: 3px;"><?php echo $val['first_name'] . ' ' . $val['last_name'];?></td>
                                          <td style="padding: 3px;"><?php echo $val['desi_name'];?></td>
                                      </tr>
                                  <?php }
                              } else { ?>
                                  <tr>
                                      <td style="padding: 3px;" colspan="3" align="center">No record found</td>
                                  </tr>
                              <?php } ?>
                              </tbody>
                          </table>
                      </div>
                    </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Modal -->


  <!-- bootstrap datepicker -->
<?php if (isset($act) && $act == 'pdf') {
        // echo '<div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated By <b>'.$_SESSION['bms']['full_name'].' ['.$gen_by_desi[0]['desi_name'].']</b> On <b>'. date('d-m-Y h:i:s a') .'</b></div>';
        echo '</body></html>';
} ?>