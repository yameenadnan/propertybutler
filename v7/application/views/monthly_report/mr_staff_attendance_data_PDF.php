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

                  <div class="row" style="margin-top: 35px;">
                      <div class="col-xs-12 col-md-12">
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
                                      <h4 style="font-size: 19px;">Staff Attendance</h4>
                                  </td>
                                  <td colspan="16" align="right" style="padding-top: 50px; border: 0;">
                                      <h4 style="font-size: 19px;"><u>Month:
                                          <?php
                                          $monthNum  = $report_data->report_month;
                                          $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                          $monthName = $dateObj->format('F');
                                          echo $monthName . ' ' . $report_data->report_year;
                                          ?></u>
                                      </h4>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="32" style="padding-top: 10px; border: 0; ">&nbsp;</td>
                              </tr>
                              <thead>
                              <tr>
                                  <th width="10%" rowspan="2" style="padding-top: 3px; padding-bottom: 3px;">Staff name</th>
                                  <th colspan="31" align="center">Dates</th>
                              </tr>
                              <tr>
                                  <?php
                                  $holidays_array = array ();
                                  $CI =& get_instance();
                                  $CI->load->model('Bms_monthly_report_model');
                                  $holidays_result = $CI->bms_monthly_report_model->get_holiday_dates ($property_data->state_id, $report_data->report_year, $report_data->report_month);

                                  $last_date_of_last_month = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $report_year . '-' . $report_month . '-01' ) ) ));

                                  // Check last date of last month for sunday and public holiday
                                  $last_day_last_month_holiday_and_sunday = 0;
                                  if ( !empty ($holidays_result) ) {
                                      if ( $holidays_result[0]['day'] == $last_date_of_last_month ) {
                                          if ( date ('D',strtotime($last_date_of_last_month) ) == 'Sun' ) {
                                              $last_day_last_month_holiday_and_sunday = 1;
                                          }
                                          array_shift($holidays_result);
                                      }
                                  }

                                  if ( !empty ( $holidays_result ) ) {
                                      foreach ( $holidays_result as $key => $val ) {
                                          array_push($holidays_array,date('j', strtotime($val['day'])));
                                      }
                                  }
                                  $holiday_and_sunday = 0;
                                  for ($counter = 1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') ); $counter++) {
                                      $sunday_style = '';

                                      if ( $holiday_and_sunday == 1 ) {
                                          $sunday_style = 'style="background-color:orange;"';
                                          $holiday_and_sunday = 0;
                                      }
                                      if ( $holiday_and_sunday == 1 ) {
                                          $sunday_style = 'style="background-color:orange;"';
                                          $holiday_and_sunday = 0;
                                      }
                                      if ( $counter == 1 && $last_day_last_month_holiday_and_sunday == 1) {
                                          $sunday_style = 'style="background-color:orange;"';
                                      } elseif ( date ('D',strtotime($report_year . '-' . $report_month . '-' . $counter ) ) == 'Sun' && in_array($counter, $holidays_array)  ) {
                                          $sunday_style = 'style="background-color:red;"';
                                          $holiday_and_sunday = 1;
                                      } elseif ( date ('D',strtotime($report_year . '-' . $report_month . '-' . $counter) ) == 'Sun' ) {
                                          $sunday_style = 'style="background-color:red;"';
                                      } elseif (in_array($counter, $holidays_array) ) {
                                          $sunday_style = 'style="background-color:orange;"';
                                      }?>
                                      <th valign="middle" <?php echo $sunday_style;?>><?php echo ($counter < 10)?'0' . $counter:$counter;?></th>
                                  <?php } ?>
                              </tr>
                              </thead>
                              <?php
                              $counter = 1;
                              $counter_display = 1;

                              /*$timestamp = strtotime('2009-10-22')
                              $day = date('D', $timestamp);*/

                              $str = '';
                              if ( !empty ( $staff_attendannce_detal ) ) {
                                  foreach ( $staff_attendannce_detal as $key=>$val ) {
                                      $str = '<tr>';
                                      reset($val);
                                      $first_key = key($val);
                                      $str .= '<td style="padding-top: 3px; padding-bottom: 3px;">' . $staff_attendannce_detal [$key]['name'] . '</td>';
                                      $holiday_and_sunday = 0;
                                      for ($counter=1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') )  ; $counter++ ) {
                                          $sunday_style = '';
                                          if ( $holiday_and_sunday == 1 ) {
                                              $sunday_style = 'style="background-color:orange;"';
                                              $holiday_and_sunday = 0;
                                          }
                                          if ( $counter == 1 && $last_day_last_month_holiday_and_sunday == 1) {
                                              $sunday_style = 'style="background-color:orange;"';
                                          } elseif ( date ('D',strtotime($report_year . '-' . $report_month . '-' . $counter) ) == 'Sun' && in_array($counter, $holidays_array)  ) {
                                              $sunday_style = 'style="background-color:red;"';
                                              $holiday_and_sunday = 1;
                                          } elseif ( date ('D',strtotime($report_year . '-' . $report_month . '-' . $counter) ) == 'Sun' ) {
                                              $sunday_style = 'style="background-color:red;"';
                                          } elseif (in_array($counter, $holidays_array) ) {
                                              $sunday_style = 'style="background-color:orange;"';
                                          }

                                          if ( !empty($staff_attendannce_detal [$key][$counter]) )
                                              $str .= '<td align="center" ' . $sunday_style . '>1</td>';
                                          else
                                              $str .= '<td align="center" ' . $sunday_style . '>0</td>';

                                      }
                                      $counter_display++;
                                      $str .= '</tr>';
                                      echo $str;
                                  }
                              } else {
                                  $str = '<tr>';
                                  $str .= '<td align="center" colspan="' . (date ('t',strtotime($report_year . '-' . $report_month . '-01') ) + 1) . '">No data found</th>';
                                  echo $str .= '</tr>';
                              }
                              ?>
                          </table>
                          <table border="0">
                              <tr>
                                  <td style="background-color: red; padding: 0 5px;">&nbsp;</td>
                                  <td style="padding: 5px;">Sunday</td>
                              </tr>
                              <tr>
                                  <td colspan="2" style="background-color: #ffffff; height: 4px;"></td>
                              </tr>
                              <tr>
                                  <td style="background-color: orange; padding: 0 5px;">&nbsp;</td>
                                  <td style="padding: 5px;">Public holiday</td>
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