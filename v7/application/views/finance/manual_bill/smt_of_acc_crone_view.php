<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" <?php echo ( isset($act) || $act == 'pdf') ? 'style="background-color: #FFF;"':''; ?>>
    <!-- Content Header (Page header) -->
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
    <section class="content container-fluid cust-container-fluid">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
        <?php if( isset($act) && $act == 'pdf') {
            echo "<h2>". $PropertyInfo['jmb_mc_name'] . "</h2>";
            echo '<div style="margin: 0 auto; text-align: center;">' . $PropertyInfo['address_1'] . ' ' . $PropertyInfo['address_2'] . "<br>";
            echo 'Tel: ' . $PropertyInfo['phone_no'];
            echo '&nbsp;&nbsp;&nbsp;&nbsp;Email: ' . $PropertyInfo['email_addr'] . "<br /><br />
            <h3>Statement of Accounts</h3>";
            echo date("d-F-Y", strtotime($_GET['from'])) . ' to ' . date("d-F-Y", strtotime($_GET['to']));
            echo '<h3>Unit #: ' . $UnitDetail->unit_no . '</h3>';
            echo "</h3></div>";
        } ?>
          <div class="<?php if(!isset($act) || $act != 'pdf') { ?> box <?php } ?>box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }

            if ( !isset($act) || $act != 'pdf') { ?>
                  <div class="box-body">
                      <div class="row">
                          <div class="col-md-12 no-padding">
                              <div class="col-md-3 col-xs-6">
                                  <div class="form-group">
                                      <label>Property </label>
                                      <select class="form-control" id="property_id" name="property_id">
                                          <option value="">Select Property</option>
                                          <?php
                                          foreach ($properties as $key=>$val) {
                                              $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                              echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                          } ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-2 col-xs-6" style="">
                                  <div class="form-group">
                                      <label>Unit </label>
                                      <select name="receipt[unit_id]" class="form-control" id="unit_id">
                                          <option value="">Select Unit</option>
                                          <option value="All" <?php echo !empty($_GET['unit_id']) && $_GET['unit_id'] == 'All' ?  'selected="selected" ' : '';?>>All</option>
                                          <?php
                                          if(!empty($units)) {
                                              foreach ($units as $key=>$val) {
                                                  $selected = !empty($_GET['unit_id']) && $_GET['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
                                                  echo "<option value='".$val['unit_id']."'
                                        data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
                                              }
                                          }
                                          ?>
                                      </select>
                                  </div>
                              </div>

                              <div class="col-md-2 col-xs-4">
                                  <div class="form-group">
                                      <label>From </label>

                                      <div class="input-group date">
                                          <div class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker" name="from_date" id="from_date" type="text"  value="<?php echo !empty($_GET['from']) ? $_GET['from'] : '01-'. date('m-Y');?>" />
                                      </div>
                                      <!-- /.input group -->
                                  </div>
                              </div>

                              <div class="col-md-2 col-xs-4">
                                  <div class="form-group">
                                      <label>To </label>

                                      <div class="input-group date">
                                          <div class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                          </div>
                                          <input class="form-control pull-right datepicker" name="to_date" id="to_date" type="text"  value="<?php echo !empty($_GET['to']) ? $_GET['to'] : date('d-m-Y');?>" />
                                      </div>
                                      <!-- /.input group -->
                                  </div>
                              </div>
                              <div class="col-md-3 col-xs-12" style="margin-top: 25px;">
                                  <input class="btn btn-primary view_btn" value="View" type="button">
                                  <?php if (!empty($single_page_soa) || !empty( $all_soa[0] ) ) { ?>
                                      <input class="btn btn-primary download_btn" value="Download" type="button" style="ma">
                                      <input class="btn btn-primary send_mail_btn" value="Send mail" type="button">
                                  <?php } ?>
                              </div>
                          </div>
                      </div>
                  </div>
              <?php } ?>

              <!-- /.box-body -->
              <?php $debit_tot = $credit_tot = $bal_tot = 0;?>
              <div class="box-body">
                <?php if ( !empty($_GET['property_id']) && !empty($_GET['unit_id']) && !empty($_GET['from']) ) {
                if (isset($act) && $act == 'pdf') { ?>
                    <style>
                        table {
                            border-collapse: collapse;
                            width: 100%;
                        }

                        td, th {
                            padding: 7px;
                            border: 1px solid;
                        }

                        .top-row {
                            background-color: lightgrey;
                        }
                    </style>
                <?php } ?>

                  <?php if ( !empty($single_page_soa) && count($single_page_soa) > 0 ) {
                  echo "<br /><b>" . ' Unit #: ' . $single_page_soa[0]['unit_no'] . ' - Owner name: ' . $single_page_soa[0]['owner_name'] . "</b>";
                  ?>
                  <?php if (isset($act) && $act == 'pdf') { ?>
                  <table id="example2">
                      <?php } else { ?>
                      <table id="example2" class="table table-bordered table-hover table-striped">
                          <?php } ?>
                          <thead>
                          <tr class="top-row">
                              <th>Date</th>
                              <th>Doc No</th>
                              <th>Remarks</th>
                              <th style="text-align: right;">Debit(RM)</th>
                              <th style="text-align: right;">Credit(RM)</th>
                              <th style="text-align: right;">Balance(RM)</th>
                          </tr>
                          </thead>
                          <tbody id="content_tbody">
                          <tr>
                              <td><?php echo $_GET['from']; ?></td>
                              <td>Balance B/F</td>
                              <td>&nbsp;</td>
                              <td style="text-align: right;"></td>
                              <td style="text-align: right;"></td>
                              <td style="text-align: right;"><?php $bal_tot = ($bf_debit[$single_page_soa[0]['unit_id']]['amount'] - $bf_credit[$single_page_soa[0]['unit_id']]['amount']);
                                  echo number_format($bal_tot, 2) ?></td>
                          </tr>
                          <?php

                          foreach ($single_page_soa as $key => $val) {
                              echo "<tr>";
                              echo "<td>" . date('d-m-Y', strtotime($val['doc_date'])) . "</td>";
                              echo "<td >" . $val['doc_no'] . "</td>";
                              echo "<td>" . $val['descrip'] . "</td>";

                              echo "<td style='text-align: right;'>";
                              if ($val['item_type'] == 'RINV' || $val['item_type'] == 'DN' || ($val['item_type'] == 'OR' && empty($val['invoice_id']))) {
                                  $debit_tot += $val['amount'];
                                  $bal_tot += $val['amount'];
                                  echo number_format($val['amount'], 2);
                              } else {
                                  echo "&nbsp;";
                              }
                              echo "</td>";
                              echo "<td style='text-align: right;'>";
                              if ($val['item_type'] == 'OR' || $val['item_type'] == 'CN') {
                                  $credit_tot += $val['amount'];
                                  $bal_tot -= $val['amount'];
                                  echo number_format($val['amount'], 2);
                              } else {
                                  echo "&nbsp;";
                              }
                              echo "</td>";

                              echo "<td style='text-align: right;'>" . (number_format($bal_tot, 2)) . "</td>";
                              echo "</tr>";
                          }

                          echo "<tr>";
                          echo "<td colspan='3' style='text-align:right'>Total &ensp; </td>";
                          echo "<td style='text-align: right;'><b>" . number_format($debit_tot, 2) . "</b></td>";
                          echo "<td style='text-align: right;'><b>" . number_format($credit_tot, 2) . "</b></td>";
                          echo "<td style='text-align: right;'>&ensp;</td>";
                          echo "</tr>";

                          echo "<tr>";
                          echo "<td colspan='3' style='text-align:right'>Balance Due &ensp; </td>";
                          echo "<td style='text-align: right;'><b>" . number_format($bal_tot, 2) . "</b></td>";
                          echo "<td style='text-align: right;'>&ensp;</td>";
                          echo "<td style='text-align: right;'>&ensp;</td>";
                          echo "</tr>";

                          ?>
                          </tbody>
                      </table>
                      <?php
                      } elseif ( !empty ( $all_soa[0] ) ) {
                          foreach ( $all_soa as $key_all_soa => $val_all_soa ) {
                          $debit_tot = $credit_tot = $bal_tot = 0;
                          echo "<br /><b>" . ' Unit #: ' . $val_all_soa[0]['unit_no'] . ' - Owner name: ' . $val_all_soa[0]['owner_name'] . "</b>";
                          ?>
                              <?php if (isset($act) && $act == 'pdf') { ?>
                              <table id="example2">
                              <?php } else { ?>
                              <table id="example2" class="table table-bordered table-hover table-striped">
                                  <?php } ?>
                                  <thead>
                                  <tr class="top-row">
                                      <th>Date</th>
                                      <th>Doc No</th>
                                      <th>Remarks</th>
                                      <th style="text-align: right;">Debit(RM)</th>
                                      <th style="text-align: right;">Credit(RM)</th>
                                      <th style="text-align: right;">Balance(RM)</th>
                                  </tr>
                                  </thead>
                                  <tbody id="content_tbody">
                                  <tr>
                                      <td><?php echo $_GET['from']; ?></td>
                                      <td>Balance B/F</td>
                                      <td>&nbsp;</td>
                                      <td style="text-align: right;"></td>
                                      <td style="text-align: right;"></td>
                                      <td style="text-align: right;"><?php $bal_tot = ($bf_debit[$val_all_soa[0]['unit_id']]['amount'] - $bf_credit[$val_all_soa[0]['unit_id']]['amount']);
                                          echo number_format($bal_tot, 2) ?></td>
                                  </tr>
                                  <?php
                                  if (!empty($val_all_soa)) {
                                      foreach ($val_all_soa as $key => $val) {
                                          echo "<tr>";
                                          echo "<td>" . date('d-m-Y', strtotime($val['doc_date'])) . "</td>";
                                          echo "<td >" . $val['doc_no'] . "</td>";
                                          echo "<td>" . $val['descrip'] . "</td>";

                                          echo "<td style='text-align: right;'>";
                                          if ($val['item_type'] == 'RINV' || $val['item_type'] == 'DN' || ($val['item_type'] == 'OR' && empty($val['invoice_id']))) {
                                              $debit_tot += $val['amount'];
                                              $bal_tot += $val['amount'];
                                              echo number_format($val['amount'], 2);
                                          } else {
                                              echo "&nbsp;";
                                          }
                                          echo "</td>";
                                          echo "<td style='text-align: right;'>";
                                          if ($val['item_type'] == 'OR' || $val['item_type'] == 'CN') {
                                              $credit_tot += $val['amount'];
                                              $bal_tot -= $val['amount'];
                                              echo number_format($val['amount'], 2);
                                          } else {
                                              echo "&nbsp;";
                                          }
                                          echo "</td>";

                                          echo "<td style='text-align: right;'>" . (number_format($bal_tot, 2)) . "</td>";
                                          echo "</tr>";
                                      }
                                  }
                                  echo "<tr>";
                                  echo "<td colspan='3' style='text-align:right'>Total &ensp; </td>";
                                  echo "<td style='text-align: right;'><b>" . number_format($debit_tot, 2) . "</b></td>";
                                  echo "<td style='text-align: right;'><b>" . number_format($credit_tot, 2) . "</b></td>";
                                  echo "<td style='text-align: right;'>&ensp;</td>";
                                  echo "</tr>";

                                  echo "<tr>";
                                  echo "<td colspan='3' style='text-align:right'>Balance Due &ensp; </td>";
                                  echo "<td style='text-align: right;'><b>" . number_format($bal_tot, 2) . "</b></td>";
                                  echo "<td style='text-align: right;'>&ensp;</td>";
                                  echo "<td style='text-align: right;'>&ensp;</td>";
                                  echo "</tr>";

                                  ?>
                                  </tbody>
                              </table>
                              <?php
                              }
                          } else { ?>
                                  <?php if (isset($act) && $act == 'pdf') { ?>
                                  <table id="example2">
                                  <?php } else { ?>
                                  <table id="example2" class="table table-bordered table-hover table-striped">
                                      <?php } ?>
                                      <thead>
                                      <tr class="top-row">
                                          <th>Date</th>
                                          <th>Doc No</th>
                                          <th>Remarks</th>
                                          <th style="text-align: right;">Debit(RM)</th>
                                          <th style="text-align: right;">Credit(RM)</th>
                                          <th style="text-align: right;">Balance(RM)</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr class="top-row">
                                          <td colspan="6" align="center"><b>No record found</b></td>
                                      </tr>
                                      </tbody>
                                  </table>
                          <?php }
                      }
                      ?>
                  </div>
              </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php
if ( !isset($act) || $act != 'pdf') {
    $this->load->view('footer');
}
?>
  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
  <!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>