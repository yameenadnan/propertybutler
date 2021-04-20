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
                  <div class="row">
                  
                    <div class="col-md-3 col-xs-6">
                        <select class="form-control" id="property_id" name="property_id">
                            <option value="">Select Property</option>
                            <?php 
                                foreach ($properties as $key=>$val) { 
                                    $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                } ?> 
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-5">
                        <select class="form-control"  name="facility_id" class="form-control select2" id="facility_id">
                            <option value="">All</option>
                            <?php
                            if ( !empty ($facilities) ) {
                                foreach ( $facilities as $key_fac=>$val_fac ) {
                                    $selected = !empty($facility_id) && trim($facility_id) == $val_fac['facility_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val_fac['facility_id']."' ".$selected . " >".$val_fac['facility_name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-xs-5">
                          <select class="form-control" name="booking_status" class="form-control select2" id="booking_status">
                              <option value="0">Pending for Approval</option>
                              <option value="1" <?php echo !empty($booking_status) && $booking_status == '1' ? "selected='selected'":'';?>>All Bookings</option>
                          </select>
                    </div>

                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary sear_filter"><i class="fa fa-search"></i></a>
                    </div>
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              
              <div class="box-body">
                  <?php
                  $str = '';
                  if(!empty($booking_list['booking_list'])) {
                      $str = '<div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12 text-right">';

                      if($offset > 0 ) {
                          $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="pre">&laquo; Pre</a> &ensp;';
                      }
                      $str .= '<span>Page <input class="my_publi_paging" size="2" pattern="[0-9]*" value="'.(($offset/$rows)+1).'" type="text"> of '.ceil($booking_list['num_rows']/$rows).'</span> &ensp;';
                      if($booking_list['num_rows'] > ($offset+$rows)) {
                          $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="nxt"> Next &raquo; </a>';
                      }
                      $str .= '</div>
                            </div>';

                      echo $str.'<input id="tot_pages" value="'.ceil($booking_list['num_rows']/$rows).'" type="hidden">
                            <input id="offset" name="offset" value="'.$offset.'" type="hidden">
                            <input id="rows" name="rows" value="'.$rows.'" type="hidden">
                            <input id="act_type" name="act_type" value="" type="hidden">';
                  }
                  ?>
        <table id="facility_booking_container" class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
              <th class="col-md-1">S No</th>
              <th class="col-md-2">Facility Name</th>
              <th class="col-md-1">Block</th>
              <th class="col-md-1">Unit No.</th>
              <th class="col-md-1">Apply Date</th>
              <th class="col-md-1">Booking Date</th>
              <th class="col-md-3">Booking Time Slot</th>
              <th class="col-md-2">Status</th>
              <?php if ( !empty ($booking_status) ) { ?>
                <th class="col-md-2">Edit</th>
              <?php } ?>
            </tr>
            </thead>
            <tbody id="content_tbody">
            <?php if ( !empty ($booking_list['booking_list']) ) {
                $counter = 0;
                foreach ( $booking_list['booking_list'] as $key_book => $val_book ) { $counter++; ?>
                    <tr id="facility_booking_id_<?php echo $val_book['facility_booking_id']; ?>">
                        <td class="col-md-1 hidden facility_booking_id"><?php echo $val_book['facility_booking_id']; ?></td>
                        <td class="col-md-1"><?php echo $counter; ?></td>
                        <td class="col-md-2"><?php echo $val_book['facility_name']; ?></td>
                        <td class="col-md-1"><?php echo $val_book['block_name']; ?></td>
                        <td class="col-md-1"><?php echo $val_book['unit_no']; ?></td>
                        <td class="col-md-1"><?php echo date("d-m-Y", strtotime($val_book['created_date'])); ?></td>
                        <td class="col-md-1"><?php echo date("d-m-Y", strtotime($val_book['booking_date'])); ?></td>
                        <td class="col-md-3"><?php echo $val_book['booking_slot']; ?></td>
                        <?php if ( !empty ($booking_status) ) { ?>
                            <td class="col-md-2" style="position: relative;">
                                <?php if ( $val_book['booking_status'] == 1 ) { ?>
                                    <a class="btn btn-success" href="#">Approved</a>
                                <?php } elseif ( $val_book['booking_status'] == 2 ) { ?>
                                    <a class="btn btn-danger" href="#/">Rejected</a>&nbsp;&nbsp;<a data-toggle="modal" data-booking_desc="<?php echo !empty($val_book['booking_desc']) ? $val_book['booking_desc']:''; ?>" class="booking-reject-view" href="#booking_Modal_view"><i style="position: absolute; right: 30px; font-size: 20px; color: #d73925;" class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                                <?php } ?>
                            </td>
                        <?php } else { ?>
                            <td class="col-md-2"><a class="btn btn-success booking-approve" href="#">Approve</a>&nbsp&nbsp&nbsp
                                <a data-toggle="modal" data-id="<?php echo $val_book['facility_booking_id']; ?>" class="btn btn-danger booking-reject" href="#myModal2">Reject</a>
                            </td>
                        <?php } ?>
                        <?php if ( !empty ($booking_status) ) { ?>
                            <td class="col-md-1"><a class="btn btn-primary" href="<?php echo base_url('index.php/bms_property/facility_booking_edit/'.$val_book['facility_booking_id']);?>" title="Edit">Edit</a></td>
                        <?php } ?>
                    </tr>
            <?php }
            } else { ?>
                <tr>
                    <th colspan="8" style="text-align: center;">No record found</th>
                </tr>
            <?php }  ?>
            </tbody>
          </table>
    </div>
          
          <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    Show: &nbsp;<select id="records_per_page">
                            <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>                            
                            <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                            <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                        </select>
                        
                        <!--span style="display: inline-block; padding-left: 15px; font-size: 12px;color:#000;font-weight:bold" class="showing_stat" > Showing 0 to 0 Of 0 Record(s) </span-->
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                    <?php
                    $str = '';
                    if(!empty($booking_list['booking_list'])) {
                        $str = '<div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12 text-right">';

                        if($offset > 0 ) {
                            $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="pre">&laquo; Pre</a> &ensp;';
                        }
                        $str .= '<span>Page <input class="my_publi_paging" size="2" pattern="[0-9]*" value="'.(($offset/$rows)+1).'" type="text"> of '.ceil($booking_list['num_rows']/$rows).'</span> &ensp;';
                        if($booking_list['num_rows'] > ($offset+$rows)) {
                            $str .= '<a href="javascript:;" class="act_btn btn btn-primary" data-value="nxt"> Next &raquo; </a>';
                        }
                        $str .= '</div>
                            </div>';

                        echo $str.'<input id="tot_pages" value="'.ceil($booking_list['num_rows']/$rows).'" type="hidden">
                            <input id="offset" name="offset" value="'.$offset.'" type="hidden">
                            <input id="rows" name="rows" value="'.$rows.'" type="hidden">
                            <input id="act_type" name="act_type" value="" type="hidden">';
                    }
                    ?>
                </div>
                
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
<script>
var facility_cat = $.parseJSON('<?php echo json_encode($this->config->item('facility_cat'))?>');
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);

    $('#property_id').change(function () {
        // loadUnit ('');
        window.location.href = '<?php echo base_url('index.php/bms_property/facility_booking_list');?>?property_id='+$("#property_id").val();
    });

    $('.sear_filter').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_property/facility_booking_list');?>?property_id='+$("#property_id").val()+'&facility_id='+$('#facility_id').val()+'&booking_status='+$('#booking_status').val();
        return false;
        // setViewTable();
    });

    $('.booking-approve').click(function () {
        var facility_booking_id = $(this).closest('tr').find('.facility_booking_id').html();
        bookingApprove(facility_booking_id);
    });

    $(document).on("click", ".booking-reject", function () {
        var facility_booking_id = $(this).data('id');
        $(".modal-body #pop_facility_booking_id").val( facility_booking_id );
        $(".modal-body #booking_desc").val('');
    });

    $(document).on("click", ".booking-reject-view", function () {
        var booking_desc = $(this).data('booking_desc');
        $(".modal-body #popup_booking_desc").html( booking_desc );
    });


    jQuery('.my_publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    window.location.href="<?php echo base_url('index.php/bms_property/facility_booking_list/');?>"+((jQuery(this).val()-1)*jQuery('#rows').val())+"/"+jQuery('#rows').val()+"?property_id="+$('#property_id').val()+"&facility_id="+$('#facility_id').val()+"&booking_status="+$('#booking_status').val();
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }
        }

    });


    $('.act_btn').click(function () {
        if($(this).attr('data-value') == 'pre') {
            window.location.href="<?php echo base_url('index.php/bms_property/facility_booking_list/');?>"+(eval($('#offset').val())-eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&facility_id="+$('#facility_id').val()+"&booking_status="+$('#booking_status').val();
            return false;
        } else if($(this).attr('data-value') == 'nxt') {
            window.location.href="<?php echo base_url('index.php/bms_property/facility_booking_list/');?>"+(eval($('#offset').val())+eval($('#rows').val()))+"/"+$('#rows').val()+"?property_id="+$('#property_id').val()+"&facility_id="+$('#facility_id').val()+"&booking_status="+$('#booking_status').val();
            return false;
        } else {
            $('#act_type').val($(this).attr('data-value'));
            $( "#bms_frm" ).submit();
        }
    });


});


function bookingApprove (facility_booking_id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_property/set_booking_status_approved');?>',
        data: {'facility_booking_id':facility_booking_id},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody").LoadingOverlay("hide", true);
            if ( data == 1 ) {
                $("#facility_booking_id_" + facility_booking_id).remove();
            }

        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}


function bookingReject (facility_booking_id, booking_desc) {
    if ( booking_desc == '' ) {
        alert ('Please Enter Reject Remarks');
        $("#booking_desc").focus();
        return;
    }

    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_property/set_booking_status_rejected');?>',
        data: {'facility_booking_id':facility_booking_id, 'booking_desc':booking_desc},
        datatype:"json", // others: xml, json; default is html

        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {
            $("#content_tbody").LoadingOverlay("hide", true);
            if ( data == 1 ) {
                $("#facility_booking_id_" + facility_booking_id).remove();
                $('#myModal2').modal('toggle');
            }
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
</script>

<!--  MODEL POPUP  -->
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"> Reject remarks</h4>
            </div>
            <div class="modal-body modal-body2">
                <div class="xol-xs-12"> </div>
                <div style="clear: both;height:10px">
                    <input type="hidden" id="pop_facility_booking_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="Remarks">Remarks:</label>
                                <textarea class="form-control" rows="4" name="booking_desc" id="booking_desc"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="xol-xs-12" style="padding-top: 15px;"></div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" href="javascript:;" onclick="bookingReject( $('#pop_facility_booking_id').val(), $('#booking_desc').val() );">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!--  MODEL POPUP  -->
<div id="booking_Modal_view" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"> Reject remarks</h4>
            </div>
            <div class="modal-body modal-body2">
                <div class="xol-xs-12"> </div>
                <div style="clear: both;height:10px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="Remarks">Remarks:</label>
                                <div id="popup_booking_desc"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="xol-xs-12" style="padding-top: 15px;"></div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>