<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 25px;
    }

    .select2-container .select2-selection--single {
        height: 34px;
    }
</style>

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
    <section class="content container-fluid cust-container-fluid <?php echo ( isset($act) && $act == 'pdf')? 'text-center':''; ?>">
      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
        <?php if( isset($act) && $act == 'pdf') { ?>
            <span style="font-family: 'erasbd'; font-size: 18px;"><?php echo $PropertyInfo['jmb_mc_name']; ?></span>;
            <?php if ( !empty($PropertyInfo['address_1']) ) { ?>
                <div><span style="font-family: 'erasemi';"><?php echo $PropertyInfo['address_1'];?></span></div>
            <?php } if( !empty($PropertyInfo['address_2']) ) { ?>
                <div><span style="font-family: 'erasemi';"><?php echo $PropertyInfo['address_2'];?></span></div>
            <?php } ?>
            <span style="font-family: 'erasemi';">Tel: <?php echo $PropertyInfo['phone_no']; ?>&nbsp;&nbsp;&nbsp;&nbsp;Email: <?php echo $PropertyInfo['email_addr'] ; ?></span><br /><br />
            <span style="font-family: 'erasbd'; font-size: 18px;">Statement of Accounts</span>
            <div><span style="font-family: 'erasemi';">
            <?php
            echo date("d-F-Y", strtotime($_GET['from'])) . ' to ' . date("d-F-Y", strtotime($_GET['to']));
            echo '</span></div>';
        } ?>
          <div class="<?php if(!isset($act) || $act != 'pdf') { ?> box <?php } ?>box-primary">
              <div class="alert alert-success pre_mail_msg_notification" style="display: none;"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong></strong><div style="width: 100%; text-align: center;"><b>Email sent</b></div></div>
            <?php
            if ( isset($_SESSION['flash_msg']) && $_SESSION['flash_msg'] != '' ) {
                echo '<div class="alert ' . (!empty( $_SESSION['flash_msg_class'] )? $_SESSION['flash_msg_class']: 'alert-success') . ' msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
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
                                          foreach ( $properties as $key=>$val ) {
                                              $selected = !empty($property_id) && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                              echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                          } ?>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-md-2 col-xs-6" style="">
                                  <div class="form-group">
                                      <label>Unit </label>
                                      <select name="receipt[unit_id]" class="form-control select2" id="unit_id">
                                          <option value="">Select Unit</option>
                                          <option value="All" <?php echo (isset($_GET['unit_id']) && $_GET['unit_id'] == 'All') ?  'selected="selected"' : '';?>>All</option>
                                          <?php
                                          if(!empty($units)) {
                                              foreach ($units as $key=>$val) {
                                                  $selected = !empty($_GET['unit_id']) && $_GET['unit_id'] == $val['unit_id'] ?  'selected="selected" ' : '';
                                                  echo "<option value='".$val['unit_id']." 'data-owner='".$val['owner_name']."' ".$selected.">".$val['unit_no']."</option>";
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
                            padding: 4px;
                        }

                    </style>
                <?php } ?>

                  <?php if ( !empty($single_page_soa) && count($single_page_soa) > 0 ) { ?>
                  <div style="text-align: left; width: 100%; font-family: 'erasbd';">
                      Unit #: <?php echo $single_page_soa[0]['unit_no']; ?> - Owner name: <?php echo $single_page_soa[0]['owner_name'] ?>
                  </div>
                  <?php if (isset($act) && $act == 'pdf') { ?>
                      <table id="example2">
                      <?php } else { ?>
                      <table id="example2" class="table">
                          <?php } ?>
                          <thead>
                          <tr class="top-row">
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Date</span></td>
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Doc No</span></td>
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Remarks</span></td>
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000; text-align: right;'><span style="font-family: 'erasbd';">Debit(RM)</span></td>
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000; text-align: right;'><span style="font-family: 'erasbd';">Credit(RM)</span></td>
                              <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000; text-align: right;'><span style="font-family: 'erasbd';">Balance(RM)</span></td>
                          </tr>
                          </thead>
                          <tbody id="content_tbody">
                          <tr>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi';"><?php echo $_GET['from']; ?></span></td>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi';">Balance B/F</span></td>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi';">&nbsp;</span></td>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi'; text-align: right;"></span></td>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi'; text-align: right;"></span></td>
                              <td style='padding: 10px;'><span style="font-family: 'erasemi'; text-align: right;"><?php $bal_tot = ($bf_debit[$single_page_soa[0]['unit_id']]['amount'] - $bf_credit[$single_page_soa[0]['unit_id']]['amount']);
                                  echo number_format($bal_tot, 2) ?>
                              </span></td>
                          </tr>
                          <?php

                          foreach ($single_page_soa as $key => $val) {
                              echo "<tr>";
                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">" . date('d-m-Y', strtotime($val['doc_date'])) . "</span></td>";
                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">" . $val['doc_no'] . "</span></td>";
                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">" . $val['descrip'] . "</span></td>";
                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">";
                              if ($val['item_type'] == 'RINV' || $val['item_type'] == 'DN' || $val['item_type'] == 'DOR') {
                                  $debit_tot += $val['amount'];
                                  $bal_tot += $val['amount'];
                                  echo number_format($val['amount'], 2);
                              } else {
                                  echo "&nbsp;";
                              }
                              echo "</span></td>";
                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">";
                              if ($val['item_type'] == 'DOR' || $val['item_type'] == 'OR' || $val['item_type'] == 'CN') {
                                  $credit_tot += $val['amount'];
                                  $bal_tot -= $val['amount'];
                                  echo number_format($val['amount'], 2);
                              } else {
                                  echo "&nbsp;";
                              }
                              echo "</span></td>";

                              echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">" . (number_format($bal_tot, 2)) . "</span></td>";
                              echo "</tr>";
                          }

                          echo "<tr>";
                          echo "<td colspan='3' style='text-align:right; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasemi'; text-align: right;\">Total </span></td>";
                          echo "<td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasemi'; text-align: right;\">" . number_format($debit_tot, 2) . "</span></td>";
                          echo "<td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasemi'; text-align: right;\">" . number_format($credit_tot, 2) . "</span></td>";
                          echo "<td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasemi'; text-align: right;\"></span></td>";
                          echo "</tr>";

                          echo "<tr>";
                          echo "<td colspan='3' style='text-align:right; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style='font-family: erasemi; text-align: right;'>Balance Due </span></td>";
                          echo "<td style='border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style='font-family: erasemi; text-align: right;'>" . number_format($bal_tot, 2) . "</span></td>";
                          echo "<td style='border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style='font-family: erasemi; text-align: right;'></span></td>";
                          echo "<td style='border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style='font-family: erasemi text-align: right;'></span></td>";
                          echo "</tr>";
                          ?>
                          </tbody>
                      </table>
                      <?php
                      } elseif ( !empty ( $all_soa[0] ) ) { ?>
                          <?php if (isset($act) && $act == 'pdf') { ?>
                                <table id="example2">
                          <?php } else { ?>
                                <table id="example2" class="table table-hover table-striped">
                          <?php } ?>
                          <?php foreach ( $all_soa as $key_all_soa => $val_all_soa ) {
                                $debit_tot = $credit_tot = $bal_tot = 0;
                                ?>
                                <thead>
                                    <tr class="top-row">
                                        <th colspan="6" style="border-left: 0px; border-right: 0px; line-height: 40px;">
                                            <?php
                                            echo "<b>" . ' Unit #: ' . $val_all_soa[0]['unit_no'] . ' - Owner name: ' . $val_all_soa[0]['owner_name'] . "</b>";
                                            ?>
                                        </th>
                                    </tr>
                                </thead>
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
                                      if ($val['item_type'] == 'RINV' || $val['item_type'] == 'DN' || $val['item_type'] == 'DOR') {
                                          $debit_tot += $val['amount'];
                                          $bal_tot += $val['amount'];
                                          echo number_format($val['amount'], 2);
                                      } else {
                                          echo "&nbsp;";
                                      }
                                      echo "</td>";
                                      echo "<td style='text-align: right;'>";
                                      if ($val['item_type'] == 'DOR' || $val['item_type'] == 'OR' || $val['item_type'] == 'CN') {
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
                          <?php } ?>
                          </table>
                          <?php } else { ?>
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

<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>


<script>

$(document).ready(function () {

    $('.select2').select2();

    $('.msg_notification').fadeOut(5000);

    $('.view_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/soa');?>?property_id='+$("#property_id").val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val();
        return false;
    });

    $('.download_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/soa');?>?property_id='+$("#property_id").val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val()+'&act=pdf';
        return false;
    });

    $('.send_mail_btn').click(function () {
        $('.send_mail_btn').attr('disabled','disabled');
        $('.pre_mail_msg_notification').attr('display', 'block');
        $('.pre_mail_msg_notification').html('<div align="center">Sending email can take time</div>');
        $('.pre_mail_msg_notification').show();
        $('.pre_mail_msg_notification').fadeOut(7000);
        window.location.href='<?php echo base_url('index.php/bms_fin_bills/soa');?>?property_id='+$("#property_id").val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val()+'&to='+$("#to_date").val()+'&act=pdf&sendmail=yes';
        return false;
    });

    // On property name change
    $('#property_id').change(function () {
        loadUnit ('');
    });
    loadUnit ('<?php echo !empty($_GET['unit_id']) ? $_GET['unit_id']:''; ?>');
});

function loadUnit (unit_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_jmb_mc/get_unit');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select Unit</option>';
                var selected = unit_id != '' && unit_id == 'All' ? 'selected="selected"' : '';
                str += '<option value="All" '+selected+'>All</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.unit_id+'" '+selected+'>'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);

                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}


$(function () {
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
});


function printDiv(url) {
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');
}

function formatDate(date) {
   var date_arr = date.split('-');
   return  date_arr[2] + "-" + date_arr[1] + "-" + date_arr[0] ;
}
</script>