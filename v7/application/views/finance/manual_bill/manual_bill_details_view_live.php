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
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2><?php echo !empty($manual_bill['jmb_mc_name']) ? $manual_bill['jmb_mc_name'] : $manual_bill['property_name'];?></h2>
                                    <?php if(!empty($manual_bill['address_1'])) { ?>
                                    <div><?php echo $manual_bill['address_1'];?></div>
                                    <?php 
                                    }
                                    if(!empty($manual_bill['address_2'])) { ?>
                                    <div><?php echo $manual_bill['address_2'];?></div>
                                    <?php } ?>
                                    <div><?php echo $manual_bill['pin_code']. (!empty($manual_bill['state_name']) ? ', '.$manual_bill['state_name'] : ''). (!empty($manual_bill['country_name']) ? ', '.$manual_bill['country_name'] : '');?></div>
                                </div>
                                
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3>Invoice</h3>
                                </div>
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 60%;padding:5px;"><b>Unit No: </b> <?php echo $manual_bill['unit_no'];?></td>
                                            <td style="width: 40%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($manual_bill['bill_date']));?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding:5px;"><b>Name : </b> <?php echo !empty($manual_bill['owner_name']) ? $manual_bill['owner_name'] : ' - ';?></td>
                                            <td style="padding:5px;"><b>Invoice No: </b> <?php echo $manual_bill['bill_no']; ?> </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px;">&nbsp;</td>
                                            <td style="padding:5px;"><b>Due Date : </b> <?php echo date('d-m-Y',strtotime($manual_bill['bill_due_date']));?></td>
                                        </tr>                                   
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <th>Item Name</th>
                                            
                                            <th>Period</th>
                                            <th>Description</th>
                                            <th>Amount</th>                                           
                                        </tr>
                                        <?php 
                                        $total = 0;
                                        if(!empty($manual_bill_items)) {
                                            foreach($manual_bill_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td>".$val['cat_name']."</td>";
                                                
                                                echo "<td>".(!empty($val['item_period']) ? $val['item_period'] : '-')."</td>";
                                                echo "<td>".(!empty($val['item_descrip']) ? $val['item_descrip'] : '-')."</td>";
                                                $amount = $val['item_amount'] - (!empty($val['adj_amount']) ? $val['adj_amount'] : 0);
                                                echo "<td align='right'>".(!empty($amount) ? number_format($amount,2) : '-')."</td>";
                                                echo "</tr>";
                                                $total += !empty($amount) ? $amount : 0;
                                            }
                                            if($total > 0) {
                                                echo "<tr><td colspan='3' align='right'> <b>Total</b> </td>";
                                                echo "<td align='right'>".number_format($total,2)." </td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' align='center'> No Record Found! </td></tr>";
                                        } ?>
                                        
                                    
                                    </table>
                                    
                                    <?php if($total > 0) {
                                        $tot_arr = explode('.',$total);
                                        $whole = convertNumber($tot_arr[0].".0");
                                        $cents = !empty($tot_arr[1]) ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> ". ucwords($whole)." Ringgit".ucwords($cents)." Only</div>";
                                    } ?>  
                                
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Remarks:</b>
                                      <?php echo !empty($manual_bill['remarks']) ? $manual_bill['remarks'] : ' - ';?>
                                        
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