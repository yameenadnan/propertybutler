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

                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">

                                <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                    <h2><?php echo !empty($dn_note['jmb_mc_name']) ? $dn_note['jmb_mc_name'] : $dn_note['property_name'];?></h2>
                                    <?php if(!empty($dn_note['address_1'])) { ?>
                                        <div><?php echo $dn_note['address_1'];?></div>
                                        <?php
                                    }
                                    if(!empty($dn_note['address_2'])) { ?>
                                        <div><?php echo $dn_note['address_2'];?></div>
                                    <?php } ?>
                                    <div><?php echo $dn_note['pin_code']. (!empty($dn_note['state_name']) ? ', '.$dn_note['state_name'] : ''). (!empty($dn_note['country_name']) ? ', '.$dn_note['country_name'] : '');?></div>
                                </div>
                                <div class="col-md-12 col-xs-12" style="padding-top: 5px; width: 100%;text-align: center;">
                                    <h3>DEBIT NOTE </h3>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 50%;padding:5px;"><b>Date : </b> <?php echo date('d-m-Y',strtotime($dn_note['debit_note_date']));?></td>
                                        </tr>

                                        <tr>
                                            <td style="padding:5px;"><b>Debit Note No: </b>  <?php echo $dn_note['ap_dn_no']; ?> </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-12 " style="padding: 15px;"  >

                                    <table style="width: 100%;" class="table table-bordered table-hover  table-striped">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Description</th>
                                            <th align='right'>Item Amt</th>
                                        </tr>
                                        <?php
                                        $total = 0;
                                        if(!empty($dn_items)) {
                                            foreach($dn_items as $key=>$val) {
                                                echo "<tr>";
                                                echo "<td>".$val['coa_name']."</td>";
                                                echo "<td>".(!empty($val['description']) ? $val['description'] : '-')."</td>";
                                                echo "<td align='right'>".(!empty($val['net_amount']) ? number_format($val['net_amount'],2) : '-')."</td>";
                                                echo "</tr>";
                                            }
                                            if($dn_note['total_amount'] > 0) {
                                                echo "<tr><td colspan='2' align='right'> <b>Total Amt</b> </td>";
                                                echo "<td align='right'>". $dn_note['total_amount'] ." </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' align='center'> No Record Found! </td></tr>";
                                        } ?>


                                    </table>

                                    <?php if($dn_note['total_amount'] > 0) {
                                        $tot_arr = explode('.',$dn_note['total_amount']);
                                        $whole = convertNumber($tot_arr[0].".0");
                                        $cents = !empty($tot_arr[1]) && $tot_arr[1] != '00' ? ' and '.getDecimalWords("0.".$tot_arr[1]) : '';
                                        echo "<div><b>AMOUNT: </b> Ringgit Malaysia ". ucwords($whole)." ".ucwords($cents)." Only</div>";
                                    } ?>


                                </div>

                                <div class="col-xs-12 " style="padding-top: 15px;"  >

                                    <div class="form-group">
                                        <b>Remarks:</b>
                                        <?php echo !empty($dn_note['remarks']) ? $dn_note['remarks'] : ' - ';?>

                                    </div>
                                </div>


                                <div class="col-xs-12 " style="padding-top: 15px;"  >

                                    <div class="form-group">
                                        <b>Issued By :</b>
                                        <?php echo trim($dn_note['first_name']).' '.  trim($dn_note['last_name']) .(!empty($dn_note['created_date']) && $dn_note['created_date'] != '0000-00-00' && $dn_note['created_date'] != '1970-01-01' ? ' <b>On</b> '. date('d-m-Y', strtotime($dn_note['created_date'])) : ''); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12 text-center" style="padding-top: 15px;">
                            <input class="btn btn-primary download_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_ap_cn_dn/dn_details/').$dn_note['ap_dn_id'].'/download';?>')" value="Download" type="button"> &ensp;
                            <input class="btn btn-primary print_btn" onclick="printDiv('<?php echo base_url('index.php/bms_fin_ap_cn_dn/dn_details/').$dn_note['ap_dn_id'].'/print';?>')" value="Print" type="button"> &ensp;
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
            window.location.href="<?php echo base_url('index.php/bms_fin_ap_cn_dn/dn_list/0/25?property_id='.$dn_note['property_id']);?>";
            return false;
        });


    });

    function printDiv(url) {
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=750,height=600,directories=no,location=no');
    }

</script>