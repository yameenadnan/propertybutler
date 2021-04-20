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

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<style>
@page { size: auto;  margin: 1mm 2mm 1mm 2mm; }

    .wrapper {
        width:800px;
        margin:0px auto;
    }

    .box-body {
        padding: 0px !important;
    }

    .content {
    min-height: 150px;
    padding: 0px !important;
}
</style>
</head>
<body class="hold-transition skin-blue">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area" style="background-color: #fff;margin-left: 0px;">

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

              <div class="box box-primary" style="border-top: none;">

              <div class="box-body">
                  <div class="row printable" id="printable">
                    <div class="col-md-12">

                        <div class="col-md-12 " style="padding: 0;">

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">

                                <div class="col-md-12 col-xs-12 text-center">
                                    <span style="font-family: 'erasbd'; font-size: 18px;"><?php echo !empty($manual_bill['jmb_mc_name']) ? $manual_bill['jmb_mc_name'] : $manual_bill['property_name'];?></span>
                                    <?php if(!empty($manual_bill['address_1'])) { ?>
                                        <div><span style="font-family: 'erasemi';"><?php echo $manual_bill['address_1'];?></span></div>
                                    <?php
                                    }
                                    if(!empty($manual_bill['address_2'])) { ?>
                                        <div><span style="font-family: 'erasemi';"><?php echo $manual_bill['address_2'];?></span></div>
                                    <?php } ?>
                                    <div><span style="font-family: 'erasemi';"><?php echo $manual_bill['pin_code']. (!empty($manual_bill['state_name']) ? ', '.$manual_bill['state_name'] : ''). (!empty($manual_bill['country_name']) ? ', '.$manual_bill['country_name'] : '');?></span></div>
                                    <div><span style="font-family: 'erasemi';"><?php echo !empty($manual_bill['phone_no']) ? 'Phone No: ' . $manual_bill['phone_no']:'Phone No: -'; ?></span><span style="font-family: 'erasemi';"><?php echo !empty($manual_bill['email_addr'])? ', Email: ' . $manual_bill['email_addr']:'' ; ?></span></div>
                                </div>

                                <div class="col-md-12 col-xs-12" style="padding-top: 0px; width: 100%;text-align: center;">
                                    <h3><ins><span style="font-family: 'erasbd';">Invoice</span></ins></h3>
                                </div>

                                <div class="col-xs-12 " style="padding: 10px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td rowspan="4" valign="top" width="45%" style="padding:3px;">
                                                <span style="font-family: 'erasemi';">
                                                    <?php echo !empty($manual_bill['owner_name']) ? $manual_bill['owner_name'] : ' - ';?><br />
                                                    <?php echo !empty($manual_bill['owner_address']) ? $manual_bill['owner_address'] : ' - ';?>
                                                </span>
                                            </td>
                                            <td style="width: 13%;"></td>
                                            <td valign="top" style="padding:5px; width: 42%;">
                                                <table style="width: 100%;">
                                                    <tr>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">Invoice No</span></td>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['bill_no'];?> </span></td>
                                                    </tr>
                                                    <?php if ( !empty($invoices_per_unit) && $invoices_per_unit == 1 ) {
                                                    } else { ?>
                                                    <tr>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">Invoice Date</span></td>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">: <?php echo date('d-m-Y',strtotime(date('y-m-d')));?></span></td>
                                                    </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">Unit No:</span></td>
                                                        <td style="padding: 2px;"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['unit_no'];?></span></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!--<tr>
                                            <td style="width: 15%;"></td>
                                            <td style="padding:5px; width: 15%;"><span style="font-family: 'erasemi';">Due Date</span></td>
                                            <td style="padding:5px; width: 30%;" align="left"><span style="font-family: 'erasemi';">: <?php /*echo date('d-m-Y',strtotime($manual_bill['bill_due_date']));*/?></span></td>
                                        </tr>-->
                                    </table>
                                </div>
                                <div class="col-xs-12 " style="padding: 8px;">

                                <table style="width: 100%;" class="table">
                                        <tr>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Bill Date</span></td>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Bill Due Date</span></td>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Item Name</span></td>
                                            <?php if ( !empty($invoices_per_unit) && $invoices_per_unit == 1 ) { ?>
                                                <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Invoice #</span></td>
                                            <?php } else { ?>
                                                <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Period</span></td>
                                            <?php } ?>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Description</span></td>
                                            <td align='right' style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Amount</span></td>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        if (!empty($manual_bill_items)) {
                                            foreach($manual_bill_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['bill_date']."</span></td>";
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['bill_due_date']."</span></td>";
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['cat_name']."</span></td>";
                                                if ( !empty($invoices_per_unit) && $invoices_per_unit == 1 ) {
                                                    echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($val['bill_no']) ? $val['bill_no'] : '-')."</span></td>";
                                                } else {
                                                    echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($val['item_period']) ? $val['item_period'] : '-')."</span></td>";
                                                }
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($val['item_descrip']) ? $val['item_descrip'] : '-')."</span></td>";
                                                $amount = $val['item_amount'] - (!empty($val['adj_amount']) ? $val['adj_amount'] : 0);
                                                echo "<td align='right' style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($amount) ? number_format($amount,2) : '-')."</span></td>";
                                                echo "</tr>";
                                                $total += !empty($amount) ? $amount : 0;
                                            }
                                            if($total > 0) {
                                                $tot_arr = explode('.',$total);
                                                $whole = convertNumber($tot_arr[0].".0");
                                                $cents = !empty($tot_arr[1]) ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                                echo "<tr><td colspan='6' style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasbd';\">Amount : </span><span style=\"font-family: 'erasemi';\"> ". ucwords($whole)." Ringgit".ucwords($cents)." Only</span></td>";

                                                echo "<tr><td colspan='4' style='padding: 10px;'></td>";
                                                echo "<td align='right' colspan='2' style='padding: 10px; border-bottom:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000;'><span style=\"font-family: 'erasbd';\">Total Amount(RM): " .number_format($total,2)." </span></td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' align='center' style='padding: 10px;'><span style=\"font-family: 'erasbd';\"> No Record Found! </span></td></tr>";
                                        } ?>
                                    </table>
                                </div>
                                <?php
                                if ( !empty ( $manual_bill_items[0]['meter_reading_id'] ) ) { ?>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <span style="font-family: 'erasbd';">Remarks:</span>
                                            <span style="font-family: 'erasemi';">
                                                <?php echo !empty ( $manual_bill_items[0]['previous_reading'] )? 'Previous reading = ' . $manual_bill_items[0]['previous_reading']:''; ?>
                                                <?php echo !empty ( $manual_bill_items[0]['reading'] )? ', Current reading = ' . $manual_bill_items[0]['reading']:''; ?>
                                                <?php echo ( !empty( $manual_bill_items[0]['reading']) && !empty( $manual_bill_items[0]['reading']) )? ', Consumption = ' . ( $manual_bill_items[0]['reading'] - $manual_bill_items[0]['previous_reading'] ):''; ?>
                                                <?php echo ( !empty( $manual_bill_items[0]['water_min_charg']) )? ', Minimum charges = ' . $manual_bill_items[0]['water_min_charg'] :''; ?>
                                                <?php

                                                if ( !empty($manual_bill_items[0]['water_charge_range']) && $manual_bill_items[0]['water_charge_range'] > 0 && !empty($manual_bill_items[0]['water_charge_per_unit_rate_2']) && $manual_bill_items[0]['water_charge_per_unit_rate_2'] > 0 ) {
                                                    $rate = ', Range = ' . $manual_bill_items[0]['water_charge_range'] . ', Charge per m3(Rate 1) = ' . $manual_bill_items[0]['water_charge_per_unit_rate_1'] . ', Charge per m3(Rate 2) = ' . $manual_bill_items[0]['water_charge_per_unit_rate_2'];
                                                } else {
                                                    $rate = ', Charge per m3(Rate) = ' . $manual_bill_items[0]['water_charge_per_unit_rate_1'];
                                                }
                                                echo $rate;
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                      <span style="font-family: 'erasbd';">Remarks:</span>
                                      <span style="font-family: 'erasemi';"><?php echo !empty($manual_bill['remarks']) ? $manual_bill['remarks'] : ' - ';?></span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasbd';">
                                            ** IF THERE ARE ANY ERRORS, KINDLY CONTACT US WITHIN <?php echo (!empty($manual_bill['bill_due_days']))?$manual_bill['bill_due_days']:' - ';?> DAYS. WE APOLOGISE FOR ANY INCONVENIENCE CAUSED. THANK YOU.
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            1. All cheques must be crossed and made payable to "<?php echo !empty($manual_bill['account_title']) ? $manual_bill['account_title'] : '-';?>". Kindly indicate your name, unit no. and contact number on the reverse side of the cheque.
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            2. Name of Building Maintenance Fund : <?php echo !empty($manual_bill['account_title']) ? $manual_bill['account_title'] : '-';?><br />
                                            Bank : <?php echo !empty($manual_bill['bank_name']) ? $manual_bill['bank_name'] : '-';?> A/C No.: <?php echo !empty($manual_bill['account_no']) ? $manual_bill['account_no'] : '-';?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">3. Kindly remit the amount payable before the due date to avoid interest charges.</span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            4. This billing statement serves as a reminder in case of any legal proceedings to recover the total outstanding due.
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">

                                    <div class="form-group">
                                        <span style="font-family: 'erasbd';">Issued By :</span>
                                        <span style="font-family: 'erasemi';"><?php echo trim($manual_bill['first_name']).' '.  trim($manual_bill['last_name']) .(!empty($manual_bill['created_date']) && $manual_bill['created_date'] != '0000-00-00' && $manual_bill['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($manual_bill['created_date'])) : ''); ?></span>
                                    </div>
                                </div>

                                <div class="col-xs-12 " style="padding-top: 10px;text-align: center;font-size:12px;">
                                    <span style="font-family: 'erasemi';">This is a computer generated document. No signature is required.</span>
                                </div>

                            </div>
                        </div>
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
              </div> <!-- /.box-body -->



          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>

<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>
<?php if($act_type == 'print') { ?>
<script>
$(document).ready(function () {
    window.print();
});
</script>
<?php } else if($act_type == 'download') { ?>
<script>
$(document).ready(function () {
    window.close();
});
</script>
<?php } ?>
</body>
</html>