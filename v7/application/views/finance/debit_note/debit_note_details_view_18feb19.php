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
                        <?php //echo "<pre>";print_r($credit_note); echo "</pre>"; ?>
                        <div class="col-md-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2>Open Credit No: <?php echo $credit_note['credit_note_no']; ?></h2>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 60%;padding:5px;"><b>Property Name: </b> <?php echo $credit_note['property_name'];?></td>
                                            <td style="width: 40%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($credit_note['credit_note_date']));?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="padding:5px;"><b>Unit No: </b> <?php echo $credit_note['unit_no'];?></td>
                                            <td style="padding:5px;"><b>Name : </b> <?php echo !empty($credit_note['owner_name']) ? $credit_note['owner_name'] : ' - ';?></td>
                                        </tr>
                                    
                                    </table>
                                
                                    
                                </div>
                                
                                
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                
                                <table style="width: 100%;" class="table table-bordered table-hover  table-striped">
                                        <tr>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Period</th>
                                            <th>Description</th>
                                            <th>Amount</th>                                           
                                        </tr>
                                        <?php 
                                        $total = 0;
                                        if(!empty($credit_note_items)) {
                                            foreach($credit_note_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td>".$val['cat_name']."</td>";
                                                echo "<td>".(!empty($val['sub_cat_name']) ? $val['sub_cat_name'] : '-')."</td>";
                                                echo "<td>".(!empty($val['item_period']) ? $val['item_period'] : '-')."</td>";
                                                echo "<td>".(!empty($val['item_descrip']) ? $val['item_descrip'] : '-')."</td>";
                                                echo "<td align='right'>".(!empty($val['item_amount']) ? number_format($val['item_amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                                $total += !empty($val['item_amount']) ? $val['item_amount'] : 0;
                                            }
                                            if($total > 0) {
                                                echo "<tr><td colspan='4' align='right'> <b>Total</b> </td>";
                                                echo "<td align='right'>".number_format($total,2)." </td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' align='center'> No Record Found! </td></tr>";
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
                        
                        <div class="col-md-12 col-xs-12 text-center" style="padding-top: 15px;">
                            <input class="btn btn-primary download_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_credit_note/credit_note_details/').$credit_note['credit_note_id'].'/download';?>')" value="Download" type="button"> &ensp;
                            <input class="btn btn-primary print_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_credit_note/credit_note_details/').$credit_note['credit_note_id'].'/print';?>')" value="Print" type="button"> &ensp;
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
        window.location.href="<?php echo base_url('index.php/bms_fin_credit_note/credit_note_list/0/25?property_id='.$credit_note['property_id']);?>";
        return false;  
    });
    
    
});

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
}

</script>