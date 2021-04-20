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
    <form role="form" id="report_common_info_add" action="<?php echo base_url('index.php/bms_monthly_report/add_common_ino_data_submit');?>" method="post" autocomplete="off">
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
        <div class="col-md-12" style="margin-bottom: 15px; padding-right: 0px;">
            <button type="button" class="btn  btn-primary add_sub_sop_btn pull-right" id="common_info_add_item" value="0" data-value="0" data-id="2" aria-invalid="false">Add Item</button>
        </div>
        <table id="common_info_table" class="table table-bordered table-hover">
            <tr>
                <th>Sr #</th>
                <th>Date</th>
                <th>Info Description</th>
                <th>Remarks</th>
            </tr>
            <tbody id="common_info_item_container">
                <?php if ( isset($common_info) && !empty($common_info) ) {
                    $counter = 1;
                    foreach ( $common_info as $key=>$val ) { ?>
                        <tr id="common_item_row_<?php echo $counter;?>">

                            <td><?php echo $counter;?></td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" name="common_info[report_commoon_info_id][]" type="hidden" value="<?php echo !empty($val['report_commoon_info_id']) ? $val['report_commoon_info_id'] : ''; ?>">
                                    <input class="form-control pull-right datepicker" name="common_info[date][]" type="text" value="<?php echo !empty($val['date']) ? date('d-m-Y',strtotime($val['date'])) : date("d-m-Y"); ?>">
                                </div>
                            </td>
                            <td><textarea name="common_info[info_base][]" id="info_base" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"><?php echo ( isset($val['info_base']) && $val['info_base'] != '' )?$val['info_base']:''; ?></textarea></td>
                            <td><input type="text" name="common_info[remarks][]" class="form-control" value="<?php echo ( isset($val['remarks']) && $val['remarks'] != '' )?$val['remarks']:''; ?>"></td>
                            <td><button type="button" class="btn btn-danger btn-sm delete_sub_btn delete_sub_btn_ajax pull-right" data-id="<?php echo $val['report_commoon_info_id'];?>"><i class="fa fa-close"></i></button></td>
                        </tr>
                <?php $counter++;
                    }
                } else { ?>
                    <tr id="common_item_row_1">
                        <td>1</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control pull-right datepicker" name="common_info[date][]" type="text" value="<?php echo date("d-m-Y");?>">
                            </div>
                        </td>
                        <td><textarea name="common_info[info_base][]" id="info_base" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"></textarea></td>
                        <td><input type="text" name="common_info[remarks][]" class="form-control"></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="col-md-12" style="margin-bottom: 15px; padding-right: 0px;">
            <button type="button" class="btn btn-primary add_sub_sop_btn pull-right" id="common_info_add_item_form" value="0" data-value="0" data-id="2" aria-invalid="false">Submit</button>
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
    <script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(function () {
    var counter = 1;
//Date picker

    $('#common_info_add_item').click( function () {

        counter++;

        var id = $(this).attr('id');
        var val = $(this).val();

        var str = '<tr id="common_item_row_' + counter + '">';
        str += '<td>' + counter + '</td>';
        str += '<td>';
        str += '<div class="input-group">';
        str += '<div class="input-group-addon">';
        str += '<i class="fa fa-calendar"></i>';
        str += '</div>';
        str += '<input class="form-control pull-right datepicker" name="common_info[date][]" type="text" value="<?php echo date("d-m-Y");?>">';
        str += '</div>';
        str += '</td>';
        str += '<td><textarea name="common_info[info_base][]" id="info_base" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5"></textarea></td>';
        str += '<td><input type="text" name="common_info[remarks][]" class="form-control"></td>';
        str += '<td><button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-id="'+ counter + '"><i class="fa fa-close"></i></button></td>';
        str += '</tr>';

        $('#common_info_item_container').append( str );

        $('.delete_sub_btn').unbind('click');
        $('.delete_sub_btn').bind("click",function () {
            $(this).closest("tr").remove();
        });

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            //maxDateNow: true,
            autoclose: true
        });
    });

    $('.delete_sub_btn_ajax').unbind('click');
    $('.delete_sub_btn_ajax').bind("click",function () {
        var report_commoon_info_id = $(this).data('id') ;
        var closest_tr = $(this).closest("tr");
        // $(this).closest("tr").remove();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_monthly_report/unset_report_commoon_info_id');?>/', // Reusing the same function from task creation
            data: {'report_commoon_info_id': report_commoon_info_id },
            datatype:"html", // others: xml, json; default is html
            success: function() {
                closest_tr.remove();
            },
            error: function (e) {
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
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





    $( "#report_common_info_add" ).validate({
        rules: {
            "common_info[remarks]": "required"
        },
        messages: {

            "common_info[remarks]": "Please enter remarks"
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else if ( element.prop( "type" ) === "radio" ) {
                error.insertAfter( element.parent( "label" ).parent('div') );
            } else if ( element.hasClass("datepicker") ) {
                error.insertAfter( element.parent( "div" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }
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