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
                    <div class="col-md-12 no-padding">
                        <div class="col-md-4 col-xs-6">
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
                        <div class="col-md-4 col-xs-6" style="">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" id="search_txt" value="" class="form-control"  >
                            </div>
                        </div>            
                        <div class="col-md-4 col-xs-6" style="">
                            <div class="form-group">
                                    <label>Journal Type</label>
                              <select name="receipt[unit_id]" class="form-control" id="unit_id">
                                <option value="">Select</option> 
                                <option value="1" selected="selected">General</option>                          
                              </select>
                          </div>
                        </div>
                        
                        <div class="col-md-3 col-xs-6" style="">
                            <div class="form-group">
                                <label>Journal Voucher No</label>
                                <input type="text" id="search_txt2" value="" class="form-control"  >
                            </div>
                        </div> 
                        
                    
                        <div class="col-md-3 col-xs-4">
                            <div class="form-group">
                                <label>Date </label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="from_date" id="from_date" type="text"  value="<?php echo !empty($_GET['from']) ? $_GET['from'] : date('d-m-Y');?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                        <div class="col-md-3 col-xs-6" style="">
                            <div class="form-group">
                                <label>2nd Voucher No</label>
                                <input type="text" id="search_txt2" value="" class="form-control"  >
                            </div>
                        </div> 
                    
                        <!--div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>To </label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="to_date" id="to_date" type="text"  value="<?php echo !empty($_GET['to']) ? $_GET['to'] : date('d-m-Y');?>" />
                                </div>
                                <!- - /.input group - ->
                              </div>
                        </div-->
                        <div class="col-md-2 col-xs-12" style="margin-top: 25px;">
                            <input class="btn btn-primary view_btn" value="View" type="button">
                        </div>
                
                </div>  
                             
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              <?php $debit_tot = $credit_tot = $bal_tot = 0;?>
              <div class="box-body">
                
                <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>Acc. No.</th>  
                  <th>Account Desc.</th>
                  <th>Description</th>  
                  <th>Ref.2</th>
                  <th >Date</th>                  
                  <th >DR</th>
                  <th>CR</th>
                  
                </tr>
                </thead>
                <tbody id="content_tbody">
                
                    <tr>
                        <td colspan="7" style="text-align: center;">No record found!</td>
                        
                    </tr>
                    
                </tbody>                
              </table>
              
              
              </div>
          
          
            
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
    
    $('.view_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_fin_finance/journal_entry');?>?property_id='+$("#property_id").val()+'&unit_id='+$("#unit_id").val()+'&from='+$("#from_date").val();
        return false;  
    });
    
    // On property name change
    $('#property_id').change(function () {
        //loadUnit ('');
    }); 
});

function loadUnit (unit_id) {
    $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_jmb_mc/get_unit');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                /*if(typeof(data.error_msg) != "undefined" &&  data.error_msg == 'invalid access') {
                    window.location.href= '<?php echo base_url();?>';
                    return false;
                }*/
                var str = '<option value="">Select Unit</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        var selected = unit_id != '' && unit_id == item.unit_id ? 'selected="selected"' : '';
                        str += '<option value="'+item.unit_id+'" '+selected+'>'+item.unit_no+'</option>';
                    });
                }
                $('#unit_id').html(str);   
                        
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}


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