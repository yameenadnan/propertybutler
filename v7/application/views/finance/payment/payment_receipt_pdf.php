    <style>
            * { margin: 0; padding: 0; }
            body {
                font: 14px/1.4 Helvetica, Arial, sans-serif;
            }
            #page-wrap { width: 800px; margin: 0 auto; }

            textarea { border: 0; font: 14px Helvetica, Arial, sans-serif; overflow: hidden; resize: none; }
            table { border-collapse: collapse; }
            table td, table th { border: 1px solid black; padding: 5px; }
            tr.noBorder td {
                border: 0;
            }

            td.Border td {
                border: 1px;
            }

            #header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }

            #address { width: 250px; height: 150px; float: left; }
            #customer { overflow: hidden; }

            #logo { text-align: right; float: right; position: relative; margin-top: 25px; border: 1px solid #fff; max-width: 540px; overflow: hidden; }
            #customer-title { font-size: 20px; font-weight: bold; float: left; }

            #meta { margin-top: 1px; width: 100%; float: left; }
            #meta td { text-align: left;  }
            #meta td.meta-head { text-align: left; background: #6c757d; }
            #meta td textarea { width: 100%; height: 20px; text-align: right; }

            #signing { margin-top: 0px; width: 100%; float: left; }
            #signing td { text-align: center;  }
            #signing td.signing-head { text-align: center; background: #eee; }
            #signing td textarea { width: 100%; height: 20px; text-align: center; }

            #items { clear: both; width: 100%; margin: 30px 0 0 0; border: 1px solid black; }
            #items th { background: #6c757d; }
            #items textarea { width: 80px; height: 50px; }
            #items tr.item-row td {  vertical-align: top; }
            #items td.description { width: 300px; }
            #items td.item-name { width: 175px; }
            #items td.description textarea, #items td.item-name textarea { width: 100%; }
            #items td.total-line { border-right: 0; text-align: right; }
            #items td.total-value { border-left: 0; padding: 10px; }
            #items td.total-value textarea { height: 20px; background: none; }
            #items td.balance { background: #6c757d; }
            #items td.blank { border: 0; }

            #terms { text-align: center; margin: 20px 0 0 0; }
            #terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
            #terms textarea { width: 100%; text-align: center;}



            .delete-wpr { position: relative; }
            .delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }

            /* Extra CSS for Print Button*/
            .button {
                display: -webkit-box;
                display: -webkit-flex;
                display: -ms-flexbox;
                display: flex;
                overflow: hidden;
                margin-top: 20px;
                padding: 12px 12px;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                -webkit-transition: all 60ms ease-in-out;
                transition: all 60ms ease-in-out;
                text-align: center;
                white-space: nowrap;
                text-decoration: none !important;

                color: #fff;
                border: 0 none;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                line-height: 1.3;
                -webkit-appearance: none;
                -moz-appearance: none;

                -webkit-box-pack: center;
                -webkit-justify-content: center;
                -ms-flex-pack: center;
                justify-content: center;
                -webkit-box-align: center;
                -webkit-align-items: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-flex: 0;
                -webkit-flex: 0 0 160px;
                -ms-flex: 0 0 160px;
                flex: 0 0 160px;
            }
            .button:hover {
                -webkit-transition: all 60ms ease;
                transition: all 60ms ease;
                opacity: .85;
            }
            .button:active {
                -webkit-transition: all 60ms ease;
                transition: all 60ms ease;
                opacity: .75;
            }
            .button:focus {
                outline: 1px dotted #959595;
                outline-offset: -4px;
            }

            .button.-regular {
                color: #202129;
                background-color: #edeeee;
            }
            .button.-regular:hover {
                color: #202129;
                background-color: #e1e2e2;
                opacity: 1;
            }
            .button.-regular:active {
                background-color: #d5d6d6;
                opacity: 1;
            }

            .button.-dark {
                color: #FFFFFF;
                background: #333030;
            }
            .button.-dark:focus {
                outline: 1px dotted white;
                outline-offset: -4px;
            }

            @media print
            {
                .no-print, .no-print *
                {
                    display: none !important;
                }
            }
            h4 {
                border-bottom: 1px solid black;
            }

        </style>

    <div id="page-wrap">
        <table width="100%">
            <tr>
                <td style="border: 0;  text-align: left" width="50%">
                    <b> <?php echo $pro_name;?></b><br />
                    <?php echo $pro_address;?>
                     </td>
                <td style="border: 0;  text-align: center" width="35%">
                    <b> PAYMENT VOUCHER </b>
                </td>
                <td style="border: 0;  text-align: center" width="15%">
                    </br><img src='https://barcode.tec-it.com/barcode.ashx?data=<?php echo $pv_item['pay_no']; ?>&code=MobileQRCode&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=72&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0&modulewidth=50' alt='<?php echo $pv_item['pay_no']; ?>'/>
                    <?php echo $pv_item['pay_no']; ?>
                </td>
                </tr>
        </table>
        <hr>
        </br>

    <div id="customer">
        <table id="meta">
            <tr>
                <td rowspan="5" style="border: 1px solid white; border-right: 1px solid black; text-align: left" width="50%">
                    <strong><?php echo "PAY TO" ?></strong> <br/>
                    <table>
                        <tr class="noBorder">
                            <td>
                            <b><?php echo ($provid_name!='')?$provid_name :$pv_item['pay_service_provider_name']; ?></b><br/> <br/> 	
                            <?php
                                if($pv_item['address']){
                            ?>
                                <?php echo $pv_item['address']; ?><br/>
                                <?php echo $pv_item['city']." | ".$pv_item['state'] ?> <br/>
                                <?php echo $pv_item['postcode']; ?> <br/>
                                <?php echo $pv_item['person_inc_email']; ?>
                            <?php } else {
                                    echo $pv_item['pay_service_provider_address'];
                                } ?>
                            </td>
                        </tr>
                    </table>

                </td>
                <td class="meta-head" style="width: 17%;"><p style="color:white; font-size: 12px;">Bank</p></td>
                <td style="font-size: 12px;">
                    <?php echo ($pv_item['bank_name']!="")?$pv_item['bank_name']:'MAY BANK'; ?>
                </td>
            </tr>
            <tr>
                <td class="meta-head"><p style="color:white; font-size: 12px;">Pay Mode</p></td>
                <?php
                    $payment_mode = $this->config->item('payment_mode');
                    $bankdetails = $payment_mode[$pv_item['pay_mod']];
                ?>
                <td style="font-size: 12px;"><?php echo $bankdetails;?></td>

            </tr>
            <?php 
                if($pv_item['pay_mod']==2) {  ?>
                <tr>
                    <td class="meta-head"><p style="color:white; font-size: 12px;">Pay details</p></td>
                    <td style="font-size: 12px;"><?php echo $pv_item['pay_chq_bank_name']." / ". $pv_item['pay_cheq_no'] ;?> </td>
                    <!--<td style="font-size: 12px;"><?php /*echo $pv_item['pay_chq_bank_name']." / ". $pv_item['pay_cheq_no'] ." / " .(date('d-m-Y', strtotime($pv_item['pay_cheq_date'])));*/?> </td>-->
                </tr>
                <?php
                } else if($pv_item['pay_mod']==4) {
                ?>
                <tr>
                    <td class="meta-head"><p style="color:white; font-size: 12px;">Pay details</p></td>
                    <td style="font-size: 12px;"><?php echo $pv_item['pay_online_bank']." / ". $pv_item['pay_online_txn_no'] ;?> </td>
                    <!--<td style="font-size: 12px;"><?php /*echo $pv_item['pay_online_bank']." / ". $pv_item['pay_online_txn_no'] ." / " .(date('d-m-Y', strtotime($pv_item['pay_online_date'])));*/?> </td>-->
                </tr>
                <?php } ?>
                <tr>
                    <td class="meta-head"><p style="color:white; font-size: 12px;">Payment Date</p></td>
                    <td style="font-size: 12px;"><?php echo date('d-m-Y', strtotime($pv_item['pay_date']))?></td>
                </tr>
                <?php if ( !empty( $pv_item['pay_inv_id'] ) ) { ?>
                    <tr>
                        <td class="meta-head"><p style="color:white; font-size: 12px;">Invoice No</p></td>
                        <td style="font-size: 12px;"><b><?php echo $pv_item['exp_inv_no']; ?></b></td>
                    </tr>
                <?php } ?>
        </table>
    </div>
        <!-- Display for direct payment receipt -->

    <!-- START DISPLAY FOR WITH PO AND INVOICE -->
    <table id="items">
        <tr>
            <th align="left"><p style="color:white;"><?php echo 'Item name'; ?></p></th>
            <th align="center"><p style="color:white;"><?php echo 'Item description';?></p></th>
            <th align="center"><p style="color:white;"><?php echo 'Amount Paid'; ?></p></th>
            <!--<th align="center"><p style="color:white;"><?php /*echo 'Settled Amt'; */?></p></th>
            <th align="center"><p style="color:white;"><?php /*echo 'Paid Amt'; */?></p></th>
            <th align="center"><p style="color:white;"><?php /*echo 'Balance Amt'; */?></p></th>-->
        </tr>
        <?php
        if ( !empty ($expsubitem) ) {
            foreach ( $expsubitem as $key => $val ) { ?>
                <tr class="item-row">
                    <td><?php echo $val['coa_name']; ?></th>
                    <td align="center"><?php echo $val['pay_description']; ?></th>
                    <td align="center"><?php echo $val['pay_net_amount']; ?></th>
                    <!--<td align="center"><?php /*echo $val['net_amount'] - ($val['item_payable_amt'] + $val['item_balance_amount']); */?></th>
                    <td align="center"><?php /*echo $val['item_payable_amt']; */?></th>
                    <td align="center"><?php /*echo $val['item_balance_amount']; */?></th>-->
                </tr>
            <?php }
        } ?>
<!--        <tr class="item-row">
            <td class="right"><?php /*echo $pv_item['pay_no']; */?></td>
            <td align="right"><?php /*echo date('d-m-Y', strtotime($pv_item['pay_date'])); */?></td>
            <td align="right"><?php /*echo $pv_item['pay_total']; */?></td>
        </tr>-->
            <?php
       // }
        ?>
        <tr>
            <td align="right" colspan="2" class="total-value balance"><p style=""> <?php echo "Total Paid Amount";?></p></td>
            <td align="right" class="total-value balance"><div class="due"> <p style="color:white;"><b> <?php echo sprintf('%0.2f', $pv_item['pay_total']);?></b></p></div></td>
        </tr>
    </table>
    <!-- END DISPLAY FOR WITH PO AND INVOICE  -->

        <!--    end related transactions -->
    <div id="terms">
        <h5><?php echo $lang['inv-shipping18'] ?></h5>
        <table id="related_transactions" style="width: 100%">
            <tr class="noBorder">
                <td>
                    <p align="justify"><b>Remarks:</b></p>
                    <p align="left"><?php echo $pv_item['remarks'];?></p>
                </td>
            </tr>
        </table>
        <br/><br/><br/>
        <table id="signing">
            <tr class="noBorder">
                <td align="center">
                    -----------------------------------------------------------
                </td>
                <td align="center">
                    -----------------------------------------------------------
                </td>
                <td align="center">
                    -----------------------------------------------------------
                </td>
            </tr>
            <tr class="noBorder">
                <td align="center">PREPARED BY</td>
                <td align="center">CHECKED BY</td>
                <td align="center">APPROVED BY</td>
            </tr>
            <tr class="noBorder">
                <td align="center" colspan="3" style="height: 35px;"></td>
            </tr>
            <tr class="noBorder">
                <td align="center">
                    -----------------------------------------------------------
                </td>
                <td align="center">
                    -----------------------------------------------------------
                </td>
                <td align="center">
                    -----------------------------------------------------------
                </td>
            </tr>
            <tr class="noBorder">
                <td align="center">SIGNATURE</td>
                <td align="center">SIGNATURE</td>
                <td align="center">SIGNATURE</td>
            </tr>
        </table>
    </div>
    </div>
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>
<script>
$(document).ready(function () {
    window.close();
});
</script>
