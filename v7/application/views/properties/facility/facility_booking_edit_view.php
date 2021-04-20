<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>

  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
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
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_property/update_booking_status');?>" method="post">
                    <input type="hidden" id="facility_booking_id" name="facility_booking_id" value="<?php echo $facility_booking_id;?>"/>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12 no-padding">               
                        <div class="form-group">
                          <label >Property Name *</label>
                            <input type="text" readonly="readonly" class="form-control" value="<?php echo !empty($facility_booking_detail['property_name']) ? $facility_booking_detail['property_name']:''; ?>">
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label >Facility Name</label>
                                    <input type="text" readonly="readonly" name="facility_name" class="form-control" value="<?php echo !empty($facility_booking_detail['facility_name']) ? $facility_booking_detail['facility_name']:''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label >Block</label>
                                    <input type="text" readonly="readonly" name="block" class="form-control" value="<?php echo !empty($facility_booking_detail['block_name']) ? $facility_booking_detail['block_name']:''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label >Unit No</label>
                                    <input type="text" readonly="readonly" name="unit" class="form-control" value="<?php echo !empty($facility_booking_detail['unit_no']) ? $facility_booking_detail['unit_no']:''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Apply Date</label>
                                    <input type="text" readonly="readonly" name="facility_name" class="form-control" value="<?php echo !empty($facility_booking_detail['created_date']) ? date('d-m-Y', strtotime($facility_booking_detail['created_date'])):''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Booking Date</label>
                                    <input type="text" readonly="readonly" name="booking_date" class="form-control" value="<?php echo !empty($facility_booking_detail['booking_date']) ? date('d-m-Y', strtotime($facility_booking_detail['booking_date'])) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Booking Slot</label>
                                    <input type="text" readonly="readonly" name="booking_slot" class="form-control" value="<?php echo !empty($facility_booking_detail['booking_slot']) ? $facility_booking_detail['booking_slot']:''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="booking_status" name="booking_status">
                                        <option value="1" <?php echo !empty($facility_booking_detail['booking_status']) && $facility_booking_detail['booking_status'] == "1" ? "selected='selected'":''; ?>>Approve</option>
                                        <option value="2" <?php echo !empty($facility_booking_detail['booking_status']) && $facility_booking_detail['booking_status'] == "2" ? "selected='selected'":''; ?>>Reject</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Reject Reason</label>
                                    <textarea type="text" name="booking_desc" id="booking_desc" class="form-control"><?php echo !empty($facility_booking_detail['booking_desc']) ? $facility_booking_detail['booking_desc']:''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                        <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                        <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
                      </div>
                    </div>

                    </form>
                    
                </div> <!-- /.row -->
                
            </div> <!-- /.box-body -->
            
         </div>
          <!-- /.box -->     

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>
$(document).ready(function () {
    $('.msg_notification').fadeOut(5000);

    $('#booking_status').change ( function () {
        if ( $(this).val() == 1 )
            $('#booking_desc').val('');
    });
});

$(document).on('click', 'form button[type=submit]', function(e) {
    if ( $('#booking_status').val() == 2 && $('#booking_desc').val() == '') {
        alert ('Please Enter Reject Remarks');
        e.preventDefault();
    }
});

</script>