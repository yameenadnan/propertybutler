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
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2> <?php echo $credit_note['property_name'];?></h2>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 60%;padding:5px;"><b>Open Credit No:  </b> <?php echo $credit_note['credit_note_no']; ?></td>
                                            <td style="width: 40%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($credit_note['credit_note_date']));?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding:5px;"><b>Unit No: </b> <?php echo $credit_note['unit_no'];?></td>
                                            <td style="padding:5px;"><b>Name : </b> <?php echo !empty($credit_note['owner_name']) ? $credit_note['owner_name'] : ' - ';?></td>
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover">
                                        <tr>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Category</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Sub Category</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Period</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Description</th>
                                            <th style='padding: 10px; border:1px solid #ddd;'>Amount</th>                                           
                                        </tr>
                                        <?php 
                                        $total = 0;
                                        if(!empty($credit_note_items)) {
                                            foreach($credit_note_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".$val['cat_name']."</td>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['sub_cat_name']) ? $val['sub_cat_name'] : '-')."</td>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['item_period']) ? $val['item_period'] : '-')."</td>";
                                                echo "<td style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['item_descrip']) ? $val['item_descrip'] : '-')."</td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>".(!empty($val['item_amount']) ? number_format($val['item_amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                                $total += !empty($val['item_amount']) ? $val['item_amount'] : 0;
                                            }
                                            if($total > 0) {
                                                echo "<tr><td colspan='4' align='right' style='padding: 10px; border:1px solid #ddd;'> <b>Total</b> </td>";
                                                echo "<td align='right' style='padding: 10px; border:1px solid #ddd;'>".number_format($total,2)." </td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' align='center' style='padding: 10px; border:1px solid #ddd;'> No Record Found! </td></tr>";
                                        } ?>
                                        
                                    
                                    </table>
                                
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Remarks:</b>
                                      <?php echo !empty($credit_note['remarks']) ? $credit_note['remarks'] : ' - ';?>
                                        
                                    </div>
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Created By :</b>
                                        <?php echo trim($credit_note['first_name']).' '.  trim($credit_note['last_name']) .(!empty($credit_note['created_date']) && $credit_note['created_date'] != '0000-00-00' && $credit_note['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($credit_note['created_date'])) : ''); ?>
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