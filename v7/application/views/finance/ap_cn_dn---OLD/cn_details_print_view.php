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
                        <?php //echo "<pre>";print_r($credit_note); echo "</pre>"; ?>
                        <div class="col-md-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            
                            <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2><?php echo !empty($cr_note['jmb_mc_name']) ? $cr_note['jmb_mc_name'] : $cr_note['property_name'];?></h2>
                                    <?php if(!empty($cr_note['address_1'])) { ?>
                                    <div><?php echo $cr_note['address_1'];?></div>
                                    <?php 
                                    }
                                    if(!empty($cr_note['address_2'])) { ?>
                                    <div><?php echo $cr_note['address_2'];?></div>
                                    <?php } ?>
                                    <div><?php echo $cr_note['pin_code']. (!empty($cr_note['state_name']) ? ', '.$cr_note['state_name'] : ''). (!empty($cr_note['country_name']) ? ', '.$cr_note['country_name'] : '');?></div>
                                </div>
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3>DEBIT NOTE </h3>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                       <tr>
                                            <td style="width: 50%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($cr_note['credit_note_date']));?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;"><b>Debit Note No: </b>  <?php echo $cr_note['ap_cr_no']; ?> </td>
                                        </tr>
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >

                                    <table style="width: 100%;" class="table table-bordered table-hover  table-striped">
                                        <tr>
                                            <th align='right' style='padding: 10px; border:1px solid #ddd;'>Item Name</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Description</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Item Amt</th>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        if(!empty($cr_items)) {
                                            foreach($cr_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".$val['coa_name']."</td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['pay_description']) ? $val['pay_description'] : '-')."</td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['pay_net_amount']) ? number_format($val['pay_net_amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                            }
                                            if($cr_note['total_amount'] > 0) {
                                                echo "<tr><td colspan='2' style='padding: 10px; border:1px solid #ddd;' align='right'> <b>Total Amt</b> </td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>". $cr_note['total_amount'] ." </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' align='center'> No Record Found! </td></tr>";
                                        } ?>

                                    </table>


                                    
                                    <?php if($cr_note['total_amount'] > 0) {
                                        $tot_arr = explode('.',$cr_note['total_amount']);
                                        $whole = convertNumber($tot_arr[0].".0");                                        
                                        $cents = !empty($tot_arr[1]) && $tot_arr[1] != '00' ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> Ringgit Malaysia ". ucwords($whole)." ".ucwords($cents)." Only</div>";
                                    } ?>   
                                    
                                
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Remarks:</b>
                                      <?php echo !empty($cr_note['remarks']) ? $cr_note['remarks'] : ' - ';?>
                                        
                                    </div>
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Issued By :</b>
                                        <?php echo trim($cr_note['first_name']).' '.  trim($cr_note['last_name']) .(!empty($cr_note['created_date']) && $cr_note['created_date'] != '0000-00-00' && $cr_note['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($cr_note['created_date'])) : ''); ?>
                                    </div>
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