<?php
$this->load->view('header');
$this->load->view('sidebar'); // echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet"  href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- SELECT 2 -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="hidden-xs">
            <?php echo !empty($page_header) && $page_header != '' ? $page_header : ''; ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <!-- general form elements -->
        <div class="box box-primary">
            <?php
 
                if (!empty($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
                    unset($_SESSION['flash_msg']);
                }
             ?>
            <div class="box-body">
                <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
                    <div class="row" style="background-color: #d2cece; height: 50px;" >
                        <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($poitem['ap_dn_id']) ? 'Update Credit Note ('.$poitem['ap_dn_id'].')' : 'New Debit Note';?> </h3>
                    </div>
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_ap_cn_dn/add_cr_submit');?>"  method="post">
                        <!-- Hidden fields -->
                        <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                        <!-- New Invoice Block Start -->
                        <div class="row" style="padding-top: 15px;padding-bottom:15px;">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-2" style="margin-top: 5px;">
                                    <label>Property Name *</label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="property_id" name="ap_cr[property_id]" readonly="readonly">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($properties as $key=>$val) {
                                            $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-2" style="margin-top: 5px;">
                                    <label>Debit Note Date *</label>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="ap_cr[credit_note_date]" value="<?php echo !empty($ap_cr['credit_note_date']) ? date('d-m-Y',strtotime($ap_cr['credit_note_date'])) : date("d-m-Y"); ?>" class="form-control datepicker">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                                <div class="col-md-2">
                                    <label>Service Provider *</label>
                                </div>
                                <div class="col-md-4">
                                    <select required class="form-control select2" id="service_provider" name="ap_cr[service_provider_id]">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($service_provider as $key => $val) {
                                            $selected = !empty($ap_cr['service_provider_id']) && ap_cr['service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='" . $val['service_provider_id'] . "'
											  data-spaddress='" . $val['address'] . "'
											  data-sppostcode='" . $val['postcode'] . "'
											  data-spcity='" . $val['city'] . "'
											  data-spstate='" . $val['state'] . "'
											  data-spcountry='" . $val['country_name'] . "'
											  data-spphoneno='" . $val['office_ph_no'] . "'
											  data-sppinc='" . $val['person_incharge'] . "'
											  data-sppincmobile='" . $val['person_inc_mobile'] . "'
											  data-sppincemail='" . $val['person_inc_email'] . "'
											" . $selected . ">" . $val['provider_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>PV Number</label>
                                </div>

                                <div class="col-md-4">
                                    <select class="form-control select2" id="pay_id" name="ap_cr[pay_id]">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- End New Invoice Block Start -->


                            <div class="row" style="padding-top: 15px;padding-bottom:15px;">





                                <!--end default open-->

                                <div class="col-md-12 no-padding" style="padding: 10px 0 !important;">
                                    <div class="col-md-2 col-xs-6">
                                        <h3>
                                            <b>Remarks</b>
                                        </h3>
                                    </div>
                                    <div class="col-md-6 col-xs-12" >
                                        <textarea rows="4" name="ap_cr[remarks]" class="form-control" cols="50"></textarea>
                                    </div>
                                </div>

                                <div style="color:red;padding: 15px 15px !important;"> (*) indicates mandatory fields.</div>

                                <div class="col-md-12 no-padding">
                                    <div class="col-md-2 col-xs-6">
                                    </div>
                                    <div class="col-md-10 col-xs-12" >
                                        <div class="col-md-6">
                                            <input type="submit" value="Save"  class="btn btn-primary" style="float: right;">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="reset"  value="Reset" class="btn btn-primary reset_btn" >
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </form>



                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php 
	 $proasset = $property_assets;
	
	$this->load->view('footer'); 
	
	
	//require_once("expenses_add_script.php");
?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<!-- SELECT 2 -->
<script src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

<script>
    $(document).ready(function (){

        $('.msg_notification').fadeOut(5000);

        // On property name change
        $('#property_id').change(function () {
            //console.log($('#property_id').val());
            property_change_eve ();//$('#property_id').trigger("change");
        });

        $(document).on("wheel", "input[type=number]", function (e) {
            $(this).blur();
        });

        $(document).on( 'keypress', 'input', function (evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
                return false;
            }
        });

        $('.select2').select2();

        $( "#bms_frm" ).validate({
            rules: {
                "ap_cr[credit_note_date]": "required",
                "ap_cr[service_provider_id]": "required",
                "ap_cr[pay_id]": "required"
            },
            messages: {
                "ap_cr[credit_note_date]": "Please select Date",
                "ap_cr[service_provider_id]": "Please select Service Provider",
                "ap_cr[pay_id]": "Please select Payment Voucher Number"
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

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            'minDate': new Date()
        });

        if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
            $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));
        }

        $('#service_provider').change(function () {
            // showndhide();
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_fin_ap_cn_dn/getPVNumber');?>',
                data: {'pay_service_provider_id':$(this).val()},
                datatype:"json", // others: xml, json; default is html

                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) {
                    var str = '<option value="">Select</option>';
                    if(data.length > 0) {
                        $.each(data,function (i, item) {
                            str += '<option value="'+item.pay_id+'">'+item.pay_no+ '&nbsp;&nbsp;&nbsp;&nbsp;(' + item.pay_total + ')</option>';
                        });
                    }
                    $('#pay_id').html(str);
                    $("#content_area").LoadingOverlay("hide", true);
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        });

    });

    function property_change_eve () {
        if($('#property_id').val() != '') {
            $('#property_name').val($(this).find('option:selected').data('pname'));
        } else {
            $('#property_name').val('');
        }

        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_task/get_blocks');?>',
            data: {'property_id':$('#property_id').val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.block_id+'">'+item.block_name+'</option>';
                    });
                }
                $('#block_id').html(str);
                $('#unit_id').html('<option value="">Select</option>'); // reset unit dropdown if it is loaded already
                set_owner ();//unset_resident_info(); // unset the resident onfo if loaded already
                $('#assign_to').html('<option value="">Loading...</option>'); // unset the assign to dropdown incase selected already
                $("#content_area").LoadingOverlay("hide", true);
                //loadBank ($('#property_id').val());
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

</script>