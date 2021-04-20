<?php $this->load->view('header');
$this->load->view('sidebar'); ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link href="<?php echo base_url(); ?>assets/css/magic-check.css" rel="stylesheet">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="visible-xs">
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        </h1>
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
            <!-- /.box-header -->
            <div class="box-body" style="padding-top: 15px;">
                <?php if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
                    unset($_SESSION['flash_msg']);
                }
                ?>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form name="bms_frm" id="bms_frm" method="post" action="<?php echo base_url('index.php/bms_fin_accounting/create_bank_trans_old_submit'); ?>">
                        <div class="row" style="padding-top: 15px;padding-bottom:15px;">
                            <div class="col-md-12 col-sm-12 col-xs-12"  style="padding: 10px 0 !important;" >
                                <div class="col-md-2 col-xs-3" style="margin-top: 5px;"> <label>Property Name</label></div>
                                <div class="col-md-3 col-xs-5">
                                    <div class="input-group">
                                        <input type="hidden" name="old_bank[property_id]" value="<?php echo $property_id;?>" />
                                        <input type="hidden" name="bank_trans_old_id" value="<?php echo !empty($bank_trans_old_id)? $bank_trans_old_id: '';?>" />
                                        <select class="form-control" id="property_id" disabled="disabled">
                                            <option value="">Select</option>
                                            <?php
                                            $prop_abbr = '';
                                            foreach ($properties as $key=>$val) {
                                                $selected = '';
                                                if(isset($property_id) && $property_id == $val['property_id']){
                                                    $selected = 'selected="selected" ';
                                                    $prop_abbr = $val['property_abbrev'];
                                                }

                                                echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                            } ?>
                                        </select>
                                        <!-- Hidden fields -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12"  style="padding: 10px 0 !important;" >
                                <div class="col-md-2 col-sm-12 col-xs-3" style="margin-top: 5px;"><label>Date<label></div>
                                <div class="col-md-3 col-xs-5">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control pull-right datepicker" name="old_bank[trans_date]" id="trans_date" type="text"  value="<?php echo !empty($old_bank['trans_date']) ? date('d-m-Y',strtotime($old_bank['trans_date'])) : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-3" style="margin-top: 5px;"> <label>Pay mode</label></div>
                                <div class="col-md-2 col-xs-5">
                                    <div class="input-group">
                                        <select class="form-control" id="pay_mode" name="old_bank[pay_mode]">
                                            <option value="">Select</option>
                                            <?php
                                            $payment_mode = $this->config->item('payment_mode');
                                            foreach ( $payment_mode as $key=>$val ) {
                                                $selected = '';
                                                if( !empty( $old_bank['pay_mode'] ) && $old_bank['pay_mode'] == $key ) {
                                                    $selected = 'selected="selected" ';
                                                }
                                                echo "<option value='" . $key . "' ".$selected.">" . $val . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12"  style="padding: 10px 0 !important;" >
                                <div class="col-md-2 col-sm-12 col-xs-3" style="margin-top: 5px;"><label>Ref. 1<label></div>
                                <div class="col-md-3 col-xs-5">
                                    <div class="input-group">
                                        <input class="form-control pull-right" name="old_bank[reference1]" id="reference1" type="text"  value="<?php echo !empty($old_bank['reference1']) ? $old_bank['reference1']: ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-3" style="margin-top: 5px;"><label>Ref. 2<label></div>
                                <div class="col-md-2 col-xs-5">
                                    <div class="input-group">
                                        <input class="form-control pull-right" name="old_bank[reference2]" id="reference2" type="text"  value="<?php echo !empty($old_bank['reference2']) ? $old_bank['reference2'] : ''; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12"  style="padding: 10px 0 !important;" >
                                <div class="col-md-2 col-sm-12 col-xs-3" style="margin-top: 5px;"> <label>Credit</label></div>
                                <div class="col-md-3 col-xs-5">
                                    <div class="input-group">
                                        <input class="form-control pull-right empty_check" name="old_bank[credit]" id="credit" type="text" value="<?php echo !empty($old_bank['credit']) ? $old_bank['credit']: ''; ?>" />
                                    </div>
                                </div>

                                <div class="col-md-2 col-xs-3" style="margin-top: 5px;"><label>Debit<label></div>
                                <div class="col-md-2 col-xs-5">
                                    <div class="input-group">
                                        <input class="form-control pull-right empty_check" name="old_bank[debit]" id="debit" type="text"  value="<?php echo !empty($old_bank['debit']) ? $old_bank['debit']: ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-1 col-xs-3" style="margin-top: 5px;"><label><label></div>
                                <div class="col-md-3 col-xs-5">
                                    <div class="input-group"></div>
                                </div>
                            </div>

                            <!--end default open-->

                            <div style="color:red;padding: 15px 15px !important;"> &nbsp;</div>

                            <div class="col-md-12 no-padding">
                                <div class="col-md-2 col-xs-6">
                                </div>
                                <div class="col-md-10 col-xs-12" >
                                    <div class="col-md-6">
                                        <input type="submit" value="Save"  class="btn btn-primary" style="float: right;">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="reset"  value="Reset" class="btn btn-primary" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- bootstrap datepicker -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo base_url(); ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url(); ?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script>
    $(document).ready(function () {

        $('.msg_notification').fadeOut(5000);

        $( "#bms_frm" ).validate({
            rules: {
                "old_bank[reference1]": "required",
                "old_bank[trans_date]": "required"
            },
            messages: {
                "old_bank[reference1]": "Please input name",
                "old_bank[trans_date]": "Please select Date"
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

        $('#bms_frm').submit(function(e) {
            var isNotEmpty = 0;
            $( ".empty_check" ).each(function( index ) {
                if ( $( this ).val().trim() != '' ) {
                    isNotEmpty = 1;
                }
            });

            if ( isNotEmpty == 0 ) {
                alert ('Please enter debit or credit value');
                e.preventDefault();
            }
        });
    });

    $(function () {
        //Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
</script>