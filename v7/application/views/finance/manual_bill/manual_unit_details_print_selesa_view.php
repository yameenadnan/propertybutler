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

<style>
@page { size: auto;  margin: 2mm 3mm 2mm 10mm; }
.wrapper {
    width:800px;
    margin:0px auto;
}
</style>
</head>
<body class="hold-transition skin-blue ">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area" style="background-color: #fff;margin-left: 0px;">

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

              <div class="box box-primary" style="border-top: none;">

              <div class="box-body" >
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >

                        <div class="col-md-12 " style="padding: 0;"  >

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >

                                <div class="col-md-12 col-xs-12 text-center" style="padding-top: 5px;">
                                    <span style="font-family: 'erasbd'; font-size: 18px;">
                                        SELESA @ HAPPY GARDEN
                                        <?php /*echo !empty($manual_bill['jmb_mc_name']) ? $manual_bill['jmb_mc_name'] : $manual_bill['property_name'];*/?><!-- <span style="font-family: 'erasbd'; font-size: 13px;">(665318-M)-->
                                        </span></span>
                                    <div style="font-family: 'erasbd'; font-size: 16px;">Management Office</div>
                                    <?php if(!empty($manual_bill['address_1'])) { ?>
                                        <div>
                                            <span style="font-family: 'erasemi';">
                                                S-G-01, Residensi Selesa<br />
                                                No. 1, Jalan 2/127, Taman Gembira,<br />
                                                58200 Kuala Lumpur<br />

                                                <?php /*echo $manual_bill['address_1'];*/?><!--
                                                --><?php /*echo !empty($manual_bill['address_2'])? ', ' . $manual_bill['address_2']:'';*/?>
                                            </span>
                                        </div>
                                    <?php
                                    } ?>
                                    <!--<div><span style="font-family: 'erasemi';"><?php /*echo $manual_bill['pin_code']. (!empty($manual_bill['state_name']) ? ', '.$manual_bill['state_name'] : ''). (!empty($manual_bill['country_name']) ? ', '.$manual_bill['country_name'] : '');*/?></span></div>-->
                                    <div><span style="font-family: 'erasemi';">Phone No: 012-7037556</span><span style="font-family: 'erasemi';">, Email: selesamgt@gmail.com</span></div>
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3><ins><span style="font-family: 'erasbd';">Invoice</span></ins></h3>
                                </div>

                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td rowspan="4" valign="top" width="50%" style="padding:5px;">
                                                <span style="font-family: 'erasemi';">
                                                    <?php echo !empty($manual_bill['owner_name']) ? $manual_bill['owner_name'] : ' - ';?><br />
                                                    <?php echo !empty($manual_bill['owner_address']) ? $manual_bill['owner_address'] : ' - ';?><br />
                                                    <?php echo !empty($manual_bill['owner_postcode']) ? $manual_bill['owner_postcode'] : '';?>
                                                    <?php echo !empty($manual_bill['owner_city']) ? ', ' . $manual_bill['owner_city'] : '';?>
                                                    <?php echo !empty($manual_bill['owner_country']) ? ', ' . $manual_bill['owner_country'] : '';?>
                                                </span>
                                            </td>
                                            <td style="width: 10%;"></td>
                                            <td style="padding:1px; width: 14%;"><span style="font-family: 'erasbd';">Invoice No</span></td>
                                            <td style="padding:1px; width: 26%;" align="left"><span style="font-family: 'erasbd';">: <?php echo $manual_bill['bill_no']; ?> </span></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10%;"></td>
                                            <td style="padding:1px; width: 15%;"><span style="font-family: 'erasemi';">Date</span></td>
                                            <td style="padding:1px; width: 25%;" align="left"><span style="font-family: 'erasemi';">: <?php echo '30-04-2020'; // date('d-m-Y',strtotime($manual_bill['bill_date']));?></span></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10%;"></td>
                                            <td style="padding:1px; width: 15%;"><span style="font-family: 'erasemi';">Unit No:</span></td>
                                            <td style="padding:1px; width: 25%;" align="left"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['unit_no'];?></span></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 10%;"></td>
                                            <td style="padding:1px; width: 15%;"><span style="font-family: 'erasemi';">Share Unit:</span></td>
                                            <td style="padding:1px; width: 25%;" align="left"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['share_unit'];?></span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >

                                <table style="width: 100%;" class="table">
                                        <tr>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Bill date</span></td>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Due Date</span></td>
                                            <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Description</span></td>
                                            <td align='right' style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Amount</span></td>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        if(!empty($manual_bill_items)) {
                                            foreach($manual_bill_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['bill_date']."</span></td>";
                                                echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($val['bill_due_date']) ? $val['bill_due_date'] : '-')."</span></td>";
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
                                                echo "<tr><td colspan='4' style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style=\"font-family: 'erasbd';\">Amount : </span><span style=\"font-family: 'erasemi';\"> ". ucwords($whole)." Ringgit".ucwords($cents)." Only</span></td>";
                                                echo "<tr><td colspan='2' style='padding: 10px;'></td>";
                                                echo "<td align='right' colspan='2' style='padding: 10px; border-bottom:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000;'><span style=\"font-family: 'erasbd';\">Total Amount(RM): " .number_format($total,2)." </span></td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' align='center' style='padding: 10px;'><span style=\"font-family: 'erasbd';\"> No Record Found! </span></td></tr>";
                                        } ?>
                                    </table>
                                </div>

<!--                                <div class="col-xs-12">
                                    <div class="form-group">
                                      <span style="font-family: 'erasbd';">Remarks:</span>
                                      <span style="font-family: 'erasemi';"><?php /*echo 'SC-LCP & SF-LCP / SC-CA & SF-CA'; */?></span>
                                    </div>
                                </div>
-->
                                <!--<br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />-->
                                <div class="col-xs-12" style="text-align: left;">
                                    <div class="form-group">
                                        <span style="font-family: 'erasbd'; font-size: 13px;">
                                            Important Note:
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12" style="text-align: left;">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            <p style="font-size: 13px; font-family: 'erasemi'">Purchasers are requested to fully cooperate by settling all outstanding bills before the payment due date.  Prompt payments are important to ensure the effective management of Residensi Selesa, as well as to sustain the appreciation value of our properties.</p>
                                            <p style="font-size: 13px; font-family: 'erasemi'">Acting in accordance with Chapter 3 Section 25(1) [Proprietor to pay charges and contribution to sinking funds] of the Strata Management Act 757(2015), late payment interests of 10% per annum (daily rest) shall be imposed and debited to your account on all outstanding sums of the above billed items if the outstanding amount is not settled within the stipulated due date.</p>
                                            <ol  style="font-size: 13px; font-family: 'erasemi'">
                                                <li>Service charge rate for limited common property is RM2.39 per share unit and service charge rate for common property is at RM0.27 per share unit.</li>
                                                <li>Sinking fund contribution for limited common property and common property is charged at 10% of your service charges per share unit.</li>
                                                <li>We DO NOT accept any CASH payments. Payments are to be made by cheque, bank-in or online transfer, and proof of payment is to be forwarded to <b>selesamgt@gmail.com</b></li>
                                                <li>All cheque payments should be crossed and made payable to SUNTRACK DEVELOPMENT SDN BHD SELESA SC or banked in to UOB A/C No.: 225-303-236-9 <br>
                                                with proof of payment (cheque deposit slip) forwarded to us via <b>selesamgt@gmail.com</b>.</li>
                                                <li>Management office operation hours are from Mondays to Fridays (9:00am – 5:00pm) and Saturdays (9:00am to 1:00pm).  The Management office is closed on Sundays and Public Holidays.</li>
                                                <li>Please remit your payments for this outstanding bill within 14 days from the date of invoice or a late payment charge of 10% per annum will be imposed.</li>
                                                <li>Please notify us in writing, in the event:</li>
                                                <li>
                                                    <ol style="font-size: 13px; font-family: 'erasemi'">
                                                        <li>There is a change of ownership to this property.  Please extend a copy of the Sale & Purchase Agreement (SPA) and Deed of Mutual Covenant (DMC) to us.</li>
                                                        <li>There is a change in your correspondence address and contact details.</li>
                                                    </ol>
                                                </li>
                                            </ol>
                                        </span>
                                    </div>
                                </div>







<!--                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasbd';">
                                            ** IF THERE ARE ANY ERRORS, KINDLY CONTACT US WITHIN <?php /*echo (!empty($manual_bill['bill_due_days']))?$manual_bill['bill_due_days']:' - ';*/?> DAYS. WE APOLOGISE FOR ANY INCONVENIENCE CAUSED. THANK YOU.
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            1. All cheques must be crossed and made payable to "<?php /*echo !empty($manual_bill['account_title']) ? $manual_bill['account_title'] : '-';*/?>". Kindly indicate your name, unit no. and contact number on the reverse side of the cheque.
                                        </span>
                                    </div>
                                </div>

                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <span style="font-family: 'erasemi';">
                                            2. Name of Building Maintenance Fund Bank Account: <?php /*echo !empty($manual_bill['account_title']) ? $manual_bill['account_title'] : '-';*/?><br />
                                            Bank : <?php /*echo !empty($manual_bill['bank_name']) ? $manual_bill['bank_name'] : '-';*/?> A/C No.: <?php /*echo !empty($manual_bill['account_no']) ? $manual_bill['account_no'] : '-';*/?>
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
                                        <span  style="font-size: 13px; font-family: 'erasemi'">Issued By :</span>
                                        <span  style="font-size: 13px; font-family: 'erasemi'"><?php echo trim($manual_bill['first_name']).' '.  trim($manual_bill['last_name']) .(!empty($manual_bill['created_date']) && $manual_bill['created_date'] != '0000-00-00' && $manual_bill['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($manual_bill['created_date'])) : ''); ?></span>
                                    </div>
                                </div> -->


                                <div class="col-xs-12 " style="padding-top: 15px;text-align: center; font-size:10px;"  >
                                    <span style="font-family: 'erasemi'; font-size:13px;">This is a computer generated document. No signature is required.</span>
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