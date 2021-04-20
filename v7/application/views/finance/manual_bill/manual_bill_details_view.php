<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="page-break-after: always;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
              <div class="box-body">
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >
                        
                        
                        <div class="col-md-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                <div class="col-md-12 col-xs-12 text-center" style="padding-top: 5px;">
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

                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3><ins><span style="font-family: 'erasbd';">Invoice</span></ins></h3>
                                </div>
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td rowspan="4" valign="top" width="40%" style="padding:5px;">
                                                <span style="font-family: 'erasemi';">
                                                    <?php echo !empty($manual_bill['owner_name']) ? $manual_bill['owner_name'] : ' - ';?><br />
                                                    <?php echo !empty($manual_bill['owner_address']) ? $manual_bill['owner_address'] : ' - ';?>
                                                </span>
                                            </td>
                                            <td style="width: 25%;"></td>
                                            <td style="padding:5px; width: 14%;"><span style="font-family: 'erasemi';">Invoice No</span></td>
                                            <td style="padding:5px; width: 30%;" align="left"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['bill_no']; ?> </span></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;"></td>
                                            <td style="padding:5px; width: 14%;"><span style="font-family: 'erasemi';">Invoice Date</span></td>
                                            <td style="padding:5px; width: 30%;" align="left"><span style="font-family: 'erasemi';">: <?php echo date('d-m-Y',strtotime(date('y-m-d')));?></span></td>
                                        </tr>
                                        <!--<tr>
                                            <td style="width: 15%;"></td>
                                            <td style="padding:5px; width: 15%;"><span style="font-family: 'erasemi';">Due Date</span></td>
                                            <td style="padding:5px; width: 30%;" align="left"><span style="font-family: 'erasemi';">: <?php /*echo date('d-m-Y',strtotime($manual_bill['bill_due_date']));*/?></span></td>
                                        </tr>-->
                                        <tr>
                                            <td style="width: 25%;"></td>
                                            <td style="padding:5px; width: 14%;"><span style="font-family: 'erasemi';">Unit No:</span></td>
                                            <td style="padding:5px; width: 30%;" align="left"><span style="font-family: 'erasemi';">: <?php echo $manual_bill['unit_no'];?></span></td>
                                        </tr>                                   
                                    </table>
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover table-striped">
                                    <tr>
                                        <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Bill Date</span></td>
                                        <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Bill Due Date</span></td>
                                        <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Item Name</span></td>
                                        <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Period</span></td>
                                        <td style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Description</span></td>
                                        <td align='right' style='padding: 10px; border-top:1px solid #000000; border-bottom:1px solid #000000;'><span style="font-family: 'erasbd';">Amount</span></td>
                                    </tr>
                                    <?php
                                    $total = 0;
                                    if(!empty($manual_bill_items)) {
                                        foreach($manual_bill_items as $key=>$val) {
                                            echo "<tr>";
                                            echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['bill_date']."</span></td>";
                                            echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['bill_due_date']."</span></td>";
                                            echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".$val['cat_name']."</span></td>";
                                            echo "<td style='padding: 10px;'><span style=\"font-family: 'erasemi';\">".(!empty($val['item_period']) ? $val['item_period'] : '-')."</span></td>";
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
                                    
                                    <?php if($total > 0) {
                                        $tot_arr = explode('.',$total);
                                        $whole = convertNumber($tot_arr[0].".0");
                                        $cents = !empty($tot_arr[1]) ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> ". ucwords($whole)." Ringgit".ucwords($cents)." Only</div>";
                                    } ?>
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
                                
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Issued By :</b>
                                        <?php echo trim($manual_bill['first_name']).' '.  trim($manual_bill['last_name']) .(!empty($manual_bill['created_date']) && $manual_bill['created_date'] != '0000-00-00' && $manual_bill['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($manual_bill['created_date'])) : ''); ?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;text-align: center;font-size:12px;"  >                                
                                    This is a computer generated document. No signature is required.
                                </div>
                                
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12 col-xs-12 text-center" style="padding-top: 15px;">
                            <input class="btn btn-primary download_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_bills/manual_bill_details/').$manual_bill['bill_id'].'/download';?>')" value="Download" type="button"> &ensp;
                            <input class="btn btn-primary print_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_bills/manual_bill_details/').$manual_bill['bill_id'].'/print';?>')" value="Print" type="button"> &ensp;
                            <input class="btn btn-primary list_btn" value="Go to List" type="button"> &ensp;
                        </div>
                        
                        
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
                
              </div>
              <!-- /.box-body -->
              
              
          
          
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>
  <!-- loadingoverlay JS -->
  <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);  
        
    $('.list_btn').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_fin_bills/manual_bill_list/0/25?property_id='.$manual_bill['property_id']);?>";
        return false;  
    });
    
    
});

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
}

</script>