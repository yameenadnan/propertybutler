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
                                  <td colspan="6" style="border: 0;" align="center">
                                      <div style="margin: 0;">
                                          <h1><?php echo $property_data->property_name; ?></h1>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td colspan="3" style="padding-top: 50px; border: 0;">
                                      <h4 style="font-size: 19px;">Service Providers Attendance</h4>
                                  </td>
                                  <td colspan="3" align="right" style="padding-top: 50px; border: 0;">
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
                                  <td colspan="6" style="padding-top: 10px; border: 0; ">&nbsp;</td>
                              </tr>
                              <thead>
                              <tr>
                                  <th style="padding: 3px;">#</th>
                                  <th style="padding: 3px;">Contractor</th>
                                  <th style="padding: 3px;">Days</th>
                                  <th style="padding: 3px;">Head count</th>
                                  <th style="padding: 3px;">Attended</th>
                                  <th style="padding: 3px;">Percentage</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php
                              if ( !empty($service_provider_attendance) && $service_provider_attendance[0]['provider_name'] != null )  {
                                  $counter = 0;
                                  foreach ($service_provider_attendance as $key => $val) { ?>
                                      <tr>
                                          <td style="padding: 3px;"><?php echo ++$counter;?></td>
                                          <td style="padding: 3px;"><?php echo $val['provider_name'];?></td>
                                          <td style="padding: 3px;"><?php echo $val['total_days'];?></td>
                                          <td style="padding: 3px;"><?php echo $val['total_head_count'];?></td>
                                          <td style="padding: 3px;"><?php echo $val['head_count_obtain'];?></td>
                                          <td style="padding: 3px;">
                                              <?php
                                              if ( $val['head_count_obtain'] * 100 > 0 )
                                                echo round(($val['head_count_obtain'] * 100) / $val['total_head_count'], 2);
                                              else
                                                echo '-';
                                              ?>
                                          </td>
                                      </tr>
                                  <?php }
                              } else { ?>
                                  <tr>
                                      <td colspan="6" align="center" style="padding: 3px;">No record found</td>
                                  </tr>
                              <?php } ?>
                              </tbody>
                          </table>
                      </div>

                        <?php
                        foreach ($service_provider_attendance_detail as $key => $val) {
                            $row[$val['service_provider_id']][$val['provider_name']] = $val['provider_name'];
                            $row[$val['service_provider_id']]['total_head_count_per_day'] = $val['total_head_count_per_day'];
                            if ($val['attendance_day'] != null || $val['attendance_day'] != '') {
                                if ( $val['head_count'] != '') {
                                    $row[$val['service_provider_id']][$val['attendance_day']] = $val['head_count'];
                                } else {
                                    $row[$val['service_provider_id']][$val['attendance_day']] = '';
                                }
                            }
                        }

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

                        ?>

                        <div class="row" style="margin-top: 40px;">
                            <div class="col-sm-12">
                                <div style="width: 100%;"><h4>Service Providers Attendance Detail</h4></div>
                                <table class="table table-bordered">

                                    <thead>
                                    <tr>
                                        <th width="10%" rowspan="2">Service Provider</th>
                                        <th width="10%" rowspan="2">Contract Head count</th>
                                        <th colspan="31" align="center">Dates</th>
                                    </tr>
                                    <tr>
                                        <?php for ($counter = 1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') ) ; $counter++) {
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
                                            }
                                            ?>
                                            <th valign="middle" <?php echo $sunday_style;?>><?php echo ($counter < 10)?'0' . $counter:$counter;?></th>
                                        <?php } ?>
                                    </tr>
                                    <?php

                                    $counter = 1;
                                    $counter_display = 1;
                                    $str = '';
                                    if ( !empty($row) ) {
                                        foreach ( $row as $key=>$val ) {
                                            $str = '<tr>';
                                            reset($val);
                                            $first_key = key($val);
                                            $str .= '<td style="padding-top: 3px; padding-bottom: 3px;">' . $first_key . '</td><td>' . $row [$key]['total_head_count_per_day'] . '</td>';
                                            $holiday_and_sunday = 0;
                                            for ($counter=1; $counter <= date ('t',strtotime($report_year . '-' . $report_month . '-01') ); $counter++ ) {

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

                                                if ( $row [$key][$counter] > -1 )
                                                    $str .= '<td align="center" ' . $sunday_style . '>' . $row [$key][$counter] . '</td>';
                                                else
                                                    $str .= '<td ' . $sunday_style . '></td>';
                                            }
                                            $counter_display++;
                                            $str .= '</tr>';
                                            echo $str;
                                        }
                                    } else {
                                        $str = '<tr>';
                                        $str .= '<td align="center" colspan="' . (date ('t',strtotime($report_year . '-' . $report_month . '-01') ) + 2) . '">No data found</th>';
                                        echo $str .= '</tr>';
                                    } ?>
                                    </thead>
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