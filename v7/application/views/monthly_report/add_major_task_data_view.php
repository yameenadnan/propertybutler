<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); ?>

<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #FFF;">
    <?php if(!isset($act) || $act != 'pdf') { ?><!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>
    </section>
    <?php } ?>
    <!-- Main content -->
<form role="form" id="report_common_info_add" action="<?php echo base_url('index.php/bms_monthly_report/add_major_task_data_submit');?>" method="post" autocomplete="off">
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

              <input type="hidden" id="report_id" name="report_id" value="<?php echo $report_id; ?>">
              <div class="box-body" <?php echo isset($act) && $act == 'pdf' ? 'style="border-top: none;margin-top:-15px;"' : '';?>>
                <?php
                if(isset($act) && $act == 'pdf') {
                    foreach ($properties as $key=>$val) {
                        if(isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ){
                            $selected_property_name = $val['property_name'];
                        }
                    }
                }?>

                  <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="property_id" disabled="disabled" name="property_id">
                            <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
                            <option value="">Select Property</option>
                            <?php } ?>
                            <?php
                                $selected_property_name = '';
                                foreach ($properties as $key=>$val) {
                                    $selected = '';
                                    if( isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ) {
                                        $selected = 'selected="selected" ';
                                        $selected_property_name = $val['property_name'];
                                    }
                                    //$selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['PropertyId'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                } ?>
                              </select>

                          <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                        </div>
                    </div>
                      <style>
                          select {
                              width: 100% !important;
                          }

                          .input-group {
                              width: 100%;
                          }
                      </style>
                    <div class="col-md-3 col-xs-8">
                        <div class="input-group">
                          <select class="form-control" id="report_month" name="report_month" disabled="disabled" style="width: 100%;">
                              <?php echo monthDropdown($report_month);?>
                          </select>
                        </div>
                    </div>

                      <div class="col-md-3 col-xs-8">
                          <div class="input-group">
                              <select class="form-control" id="report_year" disabled="disabled" name="report_year">
                                  <option value="">Year</option>
                                  <?php
                                  for($i = 2018; $i <=date("Y"); $i++) {
                                      $selected = ($report_year == $i) ? 'selected="selected"':'';
                                      echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>' . PHP_EOL;
                                  }
                                  ?>
                              </select>
                          </div>
                      </div>

                </div>
              </div>

              <!-- /.box-body -->

         </div>
          <!-- /.box -->

        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary add_sub_sop_btn pull-right" id="common_info_add_item" value="0" data-value="0" data-id="2" aria-invalid="false">Add Item</button>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12 col-xs-12 block_div">
        <?php
        if ( isset($major_task) && !empty($major_task) ) {
            $counter = 1;
            foreach ( $major_task as $key => $val ) { ?>
                        <div class="row block_div_inner" style="margin: 15px 0 15px 0; padding: 15px;border: 1px solid #999;border-radius: 5px;">
                            <div class="row" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <input type="hidden" id="report_major_task_id" name="major_task[report_major_task_id][]" value="<?php echo ( isset($val['report_major_task_id']) && $val['report_major_task_id'] != '' )?$val['report_major_task_id']:''; ?>">
                                                <input class="form-control pull-right" name="major_task[location][]" maxlength="250" type="text" value="<?php echo ( isset($val['location']) && $val['location'] != '' )?$val['location']:''; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Description of Problem</label>
                                                <textarea name="major_task[description][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"><?php echo ( isset($val['description']) && $val['description'] != '' )?$val['description']:''; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Action/Recommendation</label>
                                                <textarea name="major_task[action][]" id="action" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"><?php echo ( isset($val['action']) && $val['action'] != '' )?$val['action']:''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>






                                    <div class="row">
                                        <div class="col-md-3 col-xs-3">
                                            <label>Proposed Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control pull-right datepicker" name="major_task[date_report][]" type="text" value="<?php echo !empty($val['date_report']) ? date('d-m-Y',strtotime($val['date_report'])) : date("d-m-Y"); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-3">
                                            <label>Due Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control pull-right datepicker" name="major_task[date_line][]" type="text" value="<?php echo !empty($val['date_line']) ? date('d-m-Y',strtotime($val['date_line'])) : date("d-m-Y"); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-3">
                                            <label>Date Approved</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control pull-right datepicker" name="major_task[date_approved][]" type="text" value="<?php echo !empty($val['date_approved']) ? date('d-m-Y',strtotime($val['date_approved'])) : date("d-m-Y"); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-3">
                                            <label>Date Rectified</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control pull-right datepicker" name="major_task[date_rectified][]" type="text" value="<?php echo !empty($val['date_rectified']) ? date('d-m-Y',strtotime($val['date_rectified'])) : date("d-m-Y"); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-12 text-right"><button type="button" class="btn btn-danger delete_sub_btn delete_sub_btn_ajax" value="0" data-id="<?php echo $val['report_major_task_id'];?>" aria-invalid="false">Delete</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>



















           <!--div class="row block_div_inner"  style="margin-top: 10px;">
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="hidden" id="report_major_task_id" name="major_task[report_major_task_id][]" value="<?php echo ( isset($val['report_major_task_id']) && $val['report_major_task_id'] != '' )?$val['report_major_task_id']:''; ?>">
                        <input class="form-control pull-right" name="major_task[location][]" type="text" value="<?php echo ( isset($val['location']) && $val['location'] != '' )?$val['location']:''; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <textarea name="major_task[description][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"><?php echo ( isset($val['description']) && $val['description'] != '' )?$val['description']:''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <textarea name="major_task[action][]" id="action" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"><?php echo ( isset($val['action']) && $val['action'] != '' )?$val['action']:''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control pull-right datepicker" name="major_task[date_report][]" type="text" value="<?php echo !empty($val['date_report']) ? date('d-m-Y',strtotime($val['date_report'])) : date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control pull-right datepicker" name="major_task[date_line][]" type="text" value="<?php echo !empty($val['date_line']) ? date('d-m-Y',strtotime($val['date_line'])) : date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control pull-right datepicker" name="major_task[date_approved][]" type="text" value="<?php echo !empty($val['date_approved']) ? date('d-m-Y',strtotime($val['date_approved'])) : date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control pull-right datepicker" name="major_task[date_rectified][]" type="text" value="<?php echo !empty($val['date_rectified']) ? date('d-m-Y',strtotime($val['date_rectified'])) : date("d-m-Y"); ?>">
                    </div>
                </div>
                <div class="col-md-1">
                   <div class="input-group">
                        <button type="button" class="btn btn-danger btn-sm delete_sub_btn delete_sub_btn_ajax pull-right" data-id="<?php echo $val['report_major_task_id'];?>"><i class="fa fa-close"></i></button>
                   </div>
                </div>
            </div -->
        <?php $counter++;
            }
        } else { ?>





            <div class="row block_div_inner" style="margin: 15px 0 15px 0; padding: 15px;border: 1px solid #999;border-radius: 5px;">
                <div class="row" style="padding-top: 15px;">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input class="form-control pull-right" name="major_task[location][]" maxlength="250" type="text" value="">
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label>Description of Problem</label>
                                    <textarea name="major_task[description][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label>Action/Recommendation</label>
                                    <textarea name="major_task[action][]" id="action" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"></textarea>
                                </div>
                            </div>
                        </div>






                        <div class="row">
                            <div class="col-md-3 col-xs-3">
                                <label>Proposed Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" name="major_task[date_report][]" type="text" value="">
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-3">
                                <label>Due Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" name="major_task[date_line][]" type="text" value="">
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-3">
                                <label>Date Approved</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" name="major_task[date_approved][]" type="text" value="">
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-3">
                                <label>Date Rectified</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" name="major_task[date_rectified][]" type="text" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">

                        </div>
                    </div>
                </div>
            </div>

















        <!--div class="row">
            <div class="col-md-2">
                <div class="input-group">
                    <input class="form-control pull-right" name="major_task[location][]" type="text" value="">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <textarea name="major_task[description][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"></textarea>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <textarea name="major_task[action][]" id="action" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"></textarea>
                </div>
            </div>
            <div class="col-md-1">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right datepicker" name="major_task[date_report][]" type="text" value="<?php echo date("d-m-Y");?>">
                </div>
            </div>
            <div class="col-md-1">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right datepicker" name="major_task[date_line][]" type="text" value="<?php echo date("d-m-Y");?>">
                </div>
            </div>
            <div class="col-md-1">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right datepicker" name="major_task[date_approved][]" type="text" value="<?php echo date("d-m-Y");?>">
                </div>
            </div>
            <div class="col-md-1">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right datepicker" name="major_task[date_rectified][]" type="text" value="<?php echo date("d-m-Y");?>">
                </div>
            </div>
        </div -->


        <?php } ?>
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom: 15px; padding-right: 0px;">
            <button type="button" class="btn  btn-primary add_sub_sop_btn pull-right" id="common_info_add_item_form" value="0" data-value="0" data-id="2" aria-invalid="false">Submit</button>
        </div>
    </section>
    <!-- /.content -->
</form>
  </div>

  <!-- /.content-wrapper -->


  <!-- bootstrap datepicker -->
<?php if(isset($act) && $act == 'pdf') {
        echo '<div class="col-xs-12 col-md-12" style="padding-top:25px;"> Report Generated By <b>'.$_SESSION['bms']['full_name'].' ['.$gen_by_desi[0]['desi_name'].']</b> On <b>'. date('d-m-Y h:i:s a') .'</b></div>';
        echo '</body></html>';
    } else { ?>
<?php $this->load->view('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script>
$(function () {
    var counter = 1;
//Date picker

    $('#common_info_add_item').click( function () {

        var bstr = '<div class="row block_div_inner" style="margin: 15px 0 15px 0; padding: 15px;border: 1px solid #999;border-radius: 5px;">';
        bstr += '<div class="row" style="padding-top: 15px;">';
        bstr += '<div class="col-md-12 col-xs-12">';
        bstr += '<div class="row">';
        bstr += '<div class="col-md-4 col-xs-4">';
        bstr += '<div class="form-group">';
        bstr += '<label>Location</label>';
        bstr += '<input class="form-control pull-right" name="major_task[location][]" maxlength="250" type="text" value="">';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="col-md-4 col-xs-4">';
        bstr += '<div class="form-group">';
        bstr += '<label>Description of Problem</label>';
        bstr += '<textarea name="major_task[description][]" id="description" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"></textarea>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="col-md-4 col-xs-4">';
        bstr += '<div class="form-group">';
        bstr += '<label>Action/Recommendation</label>';
        bstr += '<textarea name="major_task[action][]" id="action" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="2"></textarea>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="row">';
        bstr += '<div class="col-md-3 col-xs-3">';
        bstr += '<label>Proposed Date</label>';
        bstr += '<div class="input-group date">';
        bstr += '<div class="input-group-addon">';
        bstr += '<i class="fa fa-calendar"></i>';
        bstr += '</div>';
        bstr += '<input class="form-control pull-right datepicker" name="major_task[date_report][]" type="text" value="">';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="col-md-3 col-xs-3">';
        bstr += '<label>Due Date</label>';
        bstr += '<div class="input-group date">';
        bstr += '<div class="input-group-addon">';
        bstr += '<i class="fa fa-calendar"></i>';
        bstr += '</div>';
        bstr += '<input class="form-control pull-right datepicker" name="major_task[date_line][]" type="text" value="">';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="col-md-3 col-xs-3">';
        bstr += '<label>Date Approved</label>';
        bstr += '<div class="input-group date">';
        bstr += '<div class="input-group-addon">';
        bstr += '<i class="fa fa-calendar"></i>';
        bstr += '</div>';
        bstr += '<input class="form-control pull-right datepicker" name="major_task[date_approved][]" type="text" value="">';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="col-md-3 col-xs-3">';
        bstr += '<label>Date Rectified</label>';
        bstr += '<div class="input-group date">';
        bstr += '<div class="input-group-addon">';
        bstr += '<i class="fa fa-calendar"></i>';
        bstr += '</div>';
        bstr += '<input class="form-control pull-right datepicker" name="major_task[date_rectified][]" type="text" value="">';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '<div class="row" style="margin-top: 15px;">';
        bstr += '<div class="col-md-12 text-right"><button type="button" class="btn btn-danger delete_sub_btn delete_sub_btn_ajax" value="0" data-value="1" aria-invalid="false">Delete</button></div>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '</div>';
        bstr += '</div>';

        $('.block_div').append(bstr);

        $('.delete_sub_btn').unbind('click');
        $('.delete_sub_btn').bind('click',function (){
            //$('#'+$(this).attr('data-value')).remove();
            $(this).parents('div.block_div_inner').remove();
        });

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            //maxDateNow: true,
            autoclose: true
        });
    });

    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        //maxDateNow: true,
        autoclose: true
    });

    $("#common_info_add_item_form").click ( function () {
        $("#report_common_info_add").submit();
    });

    $('.delete_sub_btn_ajax').unbind('click');
    $('.delete_sub_btn_ajax').bind('click',function (){




    });

    $('.delete_sub_btn_ajax').unbind('click');
    $('.delete_sub_btn_ajax').bind("click",function () {
        var report_major_task_id = $(this).data('id') ;
        var closest_tr = $(this).parents('div.block_div_inner');
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_monthly_report/unset_report_major_task_id');?>/', // Reusing the same function from task creation
            data: {'report_major_task_id': report_major_task_id },
            datatype:"html", // others: xml, json; default is html
            success: function() {
                closest_tr.remove();
            },
            error: function (e) {
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });


});
</script>
<?php }

function monthDropdown($selected=null)
{
    $dd = '';
    $months = array(
        1 => 'january',
        2 => 'february',
        3 => 'march',
        4 => 'april',
        5 => 'may',
        6 => 'june',
        7 => 'july',
        8 => 'august',
        9 => 'september',
        10 => 'october',
        11 => 'november',
        12 => 'december');
    /*** the current month ***/
    $selected = is_null($selected) ? date('n', time()) : $selected;

    for ($i = 1; $i <= 12; $i++)
    {
        $dd .= '<option value="'.$i.'"';
        if ($i == $selected)
        {
            $dd .= ' selected';
        }
        /*** get the month ***/
        $dd .= '>'.$months[$i].'</option>';
    }
    return $dd;
}

?>