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
    <section class="content container-fluid cust-container-fluid d-flex h-100">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
          <div class="box box-primary box-pdf">
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?> >


                  <div class="row" style="margin-top: 15px;">
                      <div class="col-xs-12 col-md-12">
                          <?php
                          foreach ( $asset_schedule_chart as $key => $val ) {
                              $row[$val['asset_id']][$val['asset_name']] = $val['asset_name'];
                              if ($val['service_date_scheduled'] != null || $val['service_date_scheduled'] != '') {
                                  if ( $val['service_date_done'] != '') {
                                      $row[$val['asset_id']][$val['service_date_scheduled']] = 'green';
                                      if ( $val['file_name'] != '' ) {
                                          $row[$val['asset_id']]['attachment'] = "<a style='color:#ffffff;' target='_blank' href='". base_url()."bms_uploads/asset_service_entry_docs/" . date('Y',strtotime($val['created_date'])) . "/" . date('m',strtotime($val['created_date'])) . "/" . $val['file_name'] . "' target='_blank' >";
                                      } else {
                                          $row[$val['asset_id']]['attachment'] = "";
                                      }
                                  } else {
                                      $row[$val['asset_id']][$val['service_date_scheduled']] = 'red';
                                  }
                              }
                          }
                          ?>
                          <style>
                              table {
                                  background: none;
                              }
                          </style>

                          <table class="table table-bordered" style="border: 0px;">
                              <tr>
                                  <td colspan="32" style="border: 0;" align="center">
                                      <div style="margin: 0;">
                                          <h1><?php echo $property_data->property_name; ?></h1>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="16" style="padding-top: 50px; border: 0;">
                                      <h4 style="font-size: 19px;">Asset Service Schedule</h4>
                                  </td>
                                  <td colspan="16" align="right" style="padding-top: 50px; border: 0;">
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
                                  <td colspan="32" style="padding-top: 10px; border: 0; ">&nbsp;</td>
                              </tr>
                              <tr>
                                  <th width="10%" rowspan="2" style="padding: 3px;">Description</th>
                                  <th colspan="<?php echo date ('t',strtotime($report_year . '-' . $report_month . '-01') ) ; ?>" align="center">Dates</th>
                              </tr>
                              <tr>
                                  <?php for ($counter = 1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') + 1 ); $counter++) { ?>
                                      <th valign="middle"><?php echo ($counter < 10)?'0' . $counter:$counter;?></th>
                                  <?php } ?>
                              </tr>





                              <?php
                              $counter = 1;
                              $str = '';
                              $counter_display = 1;
                              if ( !empty ($row) ) {
                                  foreach ( $row as $key=>$val ) {
                                      $str = '<tr>';
                                      reset($val);
                                      $first_key = key($val);
                                      $str .= '<td style="padding: 3px;">' . $first_key . '</td>';
                                      for ($counter=1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') ); $counter++ ) {
                                          if ( !empty($row [$key][$counter]) ) {
                                              $letter = ($row [$key][$counter] == 'green')? 'Y':'N';
                                              $letter = ( trim($row[$key]['attachment']) == '' && $row[$key]['attachment'] == null )? $letter:$row[$key]['attachment'] . $letter . '</a>';
                                              $str .= '<td align="center" style="color:#fff; background-color: ' . $row [$key][$counter] . '">' . $letter . '</th>';
                                          }
                                          else
                                              $str .= '<td>&nbsp;</td>';
                                      }
                                      $counter_display++;
                                      $str .= '</tr>';
                                      echo $str;
                                  }
                              } else {
                                  echo $str = '<tr>';
                                  $str .= '<td align="center" colspan="' . (date ('t',strtotime($report_year . '-' . $report_month . '-01') ) + 1) . '">No data found</th>';
                                  echo $str .= '</tr>';
                              }
                              ?>

                          </table>
                          <table border="0">
                              <tr>
                                  <td style="background-color: red; padding: 0 5px; color:#fff;">N</td>
                                  <td style="padding: 5px;">Scheduled but not serviced</td>
                              </tr>
                              <tr>
                                  <td colspan="2" style="background-color: #ffffff; height: 4px;"></td>
                              </tr>
                              <tr>
                                  <td style="background-color: green; padding: 0 5px; color:#fff;">Y</td>
                                  <td style="padding: 5px;">Serviced</td>
                              </tr>
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