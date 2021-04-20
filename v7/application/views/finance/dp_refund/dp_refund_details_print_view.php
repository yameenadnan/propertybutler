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
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2><?php echo !empty($dp_refund['jmb_mc_name']) ? $dp_refund['jmb_mc_name'] : $dp_refund['property_name'];?></h2>
                                    <?php if(!empty($dp_refund['address_1'])) { ?>
                                    <div><?php echo $dp_refund['address_1'];?></div>
                                    <?php 
                                    }
                                    if(!empty($dp_refund['address_2'])) { ?>
                                    <div><?php echo $dp_refund['address_2'];?></div>
                                    <?php } ?>
                                    <div><?php echo $dp_refund['pin_code']. (!empty($dp_refund['state_name']) ? ', '.$dp_refund['state_name'] : ''). (!empty($dp_refund['country_name']) ? ', '.$dp_refund['country_name'] : '');?></div>
                                    <?php if(!empty($dp_refund['phone_no']) || !empty($dp_refund['phone_no2']) ) { ?> 
                                    <div>Phone: <?php echo !empty($dp_refund['phone_no']) ? $dp_refund['phone_no'] . (!empty($dp_refund['phone_no2']) ? ', '.$dp_refund['phone_no2'] : '')  : (!empty($dp_refund['phone_no2']) ? $dp_refund['phone_no2'] : '');?></div>
                                    <?php } 
                                    
                                    if(!empty($dp_refund['email_addr'])) { ?>
                                    <div>Email: <?php echo $dp_refund['email_addr']; ?></div>                                    
                                    <?php } ?>
                                </div>
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3>OFFICIAL DEPOSIT REFUND RECEIPT </h3>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 50%;padding:5px;"><b>Unit No: </b> <?php echo $dp_refund['unit_no'];?></td>
                                            <td style="width: 50%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($dp_refund['depo_refund_date']));?></td>
                                        </tr>
                                        
                                        <tr>
                                            
                                            <td style="padding:5px;"><b>Name : </b> <?php echo !empty($dp_refund['owner_name']) ? $dp_refund['owner_name'] : ' - ';?></td>
                                            <td style="padding:5px;"><b>Receipt No: </b>  <?php echo $dp_refund['doc_ref_no']; ?> </td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding:5px;"><b>Payment Mode: </b> 
                                            <?php 
                                                $payment_mode = $this->config->item('payment_mode'); 
                                                echo $payment_mode[$dp_refund['payment_mode']];
                                                if($dp_refund['payment_mode'] == 2 && !empty($dp_refund['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_refund['cheq_card_txn_no'].' - ' .$dp_refund['bank'].')';
                                                }
                                                if($dp_refund['payment_mode'] == 3 && !empty($dp_refund['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_refund['cheq_card_txn_no']. ' - ' .$dp_refund['bank']. ')';
                                                }
                                                if($dp_refund['payment_mode'] == 4 && !empty($dp_refund['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_refund['cheq_card_txn_no']. ' - ' .$dp_refund['bank']. ')';
                                                }
                                            ?>  
                                                
                                            </td>
                                            <td style="padding:5px;"> &nbsp;</td>
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                <div class="col-xs-12 " style="padding: 15px 15px 0 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 60%;padding:5px;"><b>Paid Amount : </b> RM <?php echo $dp_refund['amount']; ?></td>
                                            
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Item Name</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Description</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Amount (RM)</th>                                           
                                        </tr>
                                        <?php 
                                             echo "<tr>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".$dp_refund['coa_name']."</td>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".(!empty($dp_refund['description']) ? $dp_refund['description'] : '-')."</td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>".(!empty($dp_refund['amount']) ? number_format($dp_refund['amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                                
                                        ?>
                                        
                                    
                                    </table>
                                    
                                    <?php if($dp_refund['amount'] > 0) {
                                        $tot_arr = explode('.',$dp_refund['amount']);
                                        $whole = convertNumber($tot_arr[0].".0");                                        
                                        $cents = !empty($tot_arr[1]) && $tot_arr[1] != '00' ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> Ringgit Malaysia ". ucwords($whole)." ".ucwords($cents)." Only</div>";
                                    } ?>   
                                    
                                
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Remarks:</b>
                                      <?php echo !empty($dp_refund['remarks']) ? $dp_refund['remarks'] : ' - ';?>
                                        
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Issued By :</b>
                                        <?php echo trim($dp_refund['first_name']).' '.  trim($dp_refund['last_name']) .(!empty($dp_refund['created_date']) && $dp_refund['created_date'] != '0000-00-00' && $dp_refund['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($dp_refund['created_date'])) : ''); ?>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>NOTE:</b>
                                       This Official Deposit Receipt is only valid upon clearance of the transaction.
                                        
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;text-align: center;font-size:12px;"  >                                
                                    This is a computer generated document. No signature is required.
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