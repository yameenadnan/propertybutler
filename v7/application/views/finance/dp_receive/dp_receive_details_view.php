<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
                                    <h2><?php echo !empty($dp_receive['jmb_mc_name']) ? $dp_receive['jmb_mc_name'] : $dp_receive['property_name'];?></h2>
                                    <?php if(!empty($dp_receive['address_1'])) { ?>
                                    <div><?php echo $dp_receive['address_1'];?></div>
                                    <?php 
                                    }
                                    if(!empty($dp_receive['address_2'])) { ?>
                                    <div><?php echo $dp_receive['address_2'];?></div>
                                    <?php } ?>
                                    <div><?php echo $dp_receive['pin_code']. (!empty($dp_receive['state_name']) ? ', '.$dp_receive['state_name'] : ''). (!empty($dp_receive['country_name']) ? ', '.$dp_receive['country_name'] : '');?></div>
                                    
                                    <?php if(!empty($dp_receive['phone_no']) || !empty($dp_receive['phone_no2']) ) { ?> 
                                    <div>Phone: <?php echo !empty($dp_receive['phone_no']) ? $dp_receive['phone_no'] . (!empty($dp_receive['phone_no2']) ? ', '.$dp_receive['phone_no2'] : '')  : (!empty($dp_receive['phone_no2']) ? $dp_receive['phone_no2'] : '');?></div>
                                    <?php } 
                                    
                                    if(!empty($dp_receive['email_addr'])) { ?>
                                    <div>Email: <?php echo $dp_receive['email_addr']; ?></div>                                    
                                    <?php } ?>
                                </div>
                                
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3>OFFICIAL DEPOSIT RECEIPT </h3>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                       <tr>
                                            <td style="width: 50%;padding:5px;"><b>Unit No: </b> <?php echo $dp_receive['unit_no'];?></td>
                                            <td style="width: 50%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($dp_receive['deposit_date']));?></td>
                                        </tr>
                                        
                                        <tr>
                                            
                                            <td style="padding:5px;"><b>Name : </b> <?php echo !empty($dp_receive['owner_name']) ? $dp_receive['owner_name'] : ' - ';?></td>
                                            <td style="padding:5px;"><b>Receipt No: </b>  <?php echo $dp_receive['doc_ref_no']; ?> </td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding:5px;"><b>Payment Mode: </b> 
                                            <?php 
                                                $payment_mode = $this->config->item('payment_mode'); 
                                                echo $payment_mode[$dp_receive['payment_mode']];
                                                if($dp_receive['payment_mode'] == 2 && !empty($dp_receive['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_receive['cheq_card_txn_no']. ' - ' .$dp_receive['bank']. ')';
                                                }
                                                
                                                if($dp_receive['payment_mode'] == 3 && !empty($dp_receive['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_receive['cheq_card_txn_no']. ' - ' .$dp_receive['bank']. ')';
                                                }
                                                if($dp_receive['payment_mode'] == 4 && !empty($dp_receive['cheq_card_txn_no'])) {
                                                    echo ' ('.$dp_receive['cheq_card_txn_no']. ' - ' .$dp_receive['bank']. ')';
                                                }
                                            ?>  
                                            </td>
                                            <td style="padding:5px;">&nbsp;</td>
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                <div class="col-xs-12 " style="padding: 15px 15px 0 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 60%;padding:5px;"><b>Paid Amount : </b> RM <?php echo $dp_receive['amount']; ?></td>
                                            
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <th>Item Name</th>
                                            
                                            
                                            <th>Description</th>
                                            <th>Amount (RM)</th>                                           
                                        </tr>
                                        <?php 
                                             echo "<tr>";
                                                echo "<td>".$dp_receive['coa_name']."</td>";
                                                
                                                
                                                echo "<td>".(!empty($dp_receive['description']) ? $dp_receive['description'] : '-')."</td>";
                                                echo "<td align='right'>".(!empty($dp_receive['amount']) ? number_format($dp_receive['amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                                
                                        ?>
                                        
                                    
                                    </table>
                                    
                                    <?php if($dp_receive['amount'] > 0) {
                                        $tot_arr = explode('.',$dp_receive['amount']);
                                        $whole = convertNumber($tot_arr[0].".0");                                        
                                        $cents = !empty($tot_arr[1]) && $tot_arr[1] != '00' ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> Ringgit Malaysia ". ucwords($whole)." ".ucwords($cents)." Only</div>";
                                    } ?>   
                                    
                                </div>
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Remarks:</b>
                                      <?php echo !empty($dp_receive['remarks']) ? $dp_receive['remarks'] : ' - ';?>
                                        
                                    </div>
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding-top: 15px;"  >
                                
                                    <div class="form-group">
                                      <b>Issued By :</b>
                                        <?php echo trim($dp_receive['first_name']).' '.  trim($dp_receive['last_name']) .(!empty($dp_receive['created_date']) && $dp_receive['created_date'] != '0000-00-00' && $dp_receive['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($dp_receive['created_date'])) : ''); ?>
                                    </div>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12 col-xs-12 text-center" style="padding-top: 15px;">
                            <input class="btn btn-primary download_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_dp_receive/dp_receive_details/').$dp_receive['depo_receive_id'].'/download';?>')" value="Download" type="button"> &ensp;
                            <input class="btn btn-primary print_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_dp_receive/dp_receive_details/').$dp_receive['depo_receive_id'].'/print';?>')" value="Print" type="button"> &ensp;
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
        window.location.href="<?php echo base_url('index.php/bms_fin_dp_receive/dp_receive_list/0/25?property_id='.$dp_receive['property_id']);?>";
        return false;  
    });
    
    
});

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
}

</script>