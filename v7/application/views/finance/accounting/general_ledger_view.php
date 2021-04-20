<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
 
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
                  <div class="row">
                  
                    <div class="col-md-3 col-xs-6">
                        <div class="form-group">
                            <label>Property </label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                        echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                    } ?> 
                            </select>
                        </div>
                    </div>
                    
                    
                    
                    
                    
                        <div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>From Date</label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="from_date" id="from_date" type="text"  value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : '';?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                    
                        <div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>To Date</label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="to_date" id="to_date" type="text"  value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] : '';?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                        
                    
                    <div class="col-md-1 col-xs-1" style="padding-top: 25px;">
                        <a href="javascript:;" role="button" class="btn btn-primary filter_btn"><i class="fa fa-download"></i></a>
                    </div>
                    
                    
                               
                    
                </div>
                
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
  <!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('.filter_btn').click(function () {
        if($("#property_id").val() == '') {
            alert('Plase select Property!'); $("#property_id").focus();return false;
        }
        if($("#from_date").val() == '') {
            alert('Plase select From Date!'); $("#from_date").focus();return false;
        } 
        if($("#to_date").val() == '') {
            alert('Plase select To Date!'); $("#to_date").focus();return false;
        }
        window.location.href='<?php echo base_url('index.php/bms_fin_accounting/general_ledger');?>?property_id='+$("#property_id").val()+'&from_date='+$("#from_date").val()+'&to_date='+$("#to_date").val();
        return false;
    });   
    
});



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