<div id="wrap">
   <h4 class="word_text text-primary text-center text-bold"><?php echo $pro_name; ?></h4>
    <div class="row">
        <div class="col-xs-5">
            <?php
			 
            foreach ($service_provider as $key => $val) {
                if($poitem['service_provider_id'] == $val['service_provider_id']){
            ?>

            <h3><?php echo $val['provider_name'];?></h3>
            <?php echo $val['address'];?>,<br/><?php echo $val['city'];?>, <?php echo $val['postcode'];?>, <?php echo $val['state'];?>,<br/><?php echo $val['country_name'];?><br/>Tel: <?php echo $val['office_ph_no'];?><br/>Email:<?php echo $val['person_inc_email'];?><br/>
            <?php
            }
            }
            ?>
        </div>

        <div class="col-xs-6 text-right">
            <h3><?php echo $poitem['po_no'];?></h3>
            Date : <?php echo date('d-m-Y', strtotime($poitem['date']));?><br/>
            Delivery Date : <?php echo date('d-m-Y', strtotime($poitem['delivery_date']));?><br/>
        </div>
    </div>
    <div style="clear: both; height: 15px;"></div>
    <div class="row">
        <div class="col-xs-5">
        <h3 class="inv">Items</h3>
    </div>
    <div class="col-xs-6"></div>
    <p>&nbsp;</p>
    <div style="clear: both; height: 15px;"></div>
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
            <thead>
            <tr>
            <th style="max-width: 30px;">No</th>
            <th>Description</th>
                <th>Account Name</th>
                
                <th class="text-center">Quantity</th>
                <th>UOM</th>
            <th class="col-xs-1 text-center" style="min-width:80px;padding-left:0;padding-right:0;"> Price</th>
            <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Amount</th>
            <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Discount</th>
            <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Tax(%)</th>
            <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Tax Amount</th>       
            <th class="col-xs-2 text-center">Nettotal</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i=1;
                foreach ($po_sub_items as $subkey =>$subval){
            ?>
            <tr>
                <td class="text-left"><?php echo $i;?></td>
                <td><?php echo $subval['description'];?></td>

                <td class="text-left"><?php
                    foreach ($expense_items as $expkey => $expval) {
                        if($expval['coa_id']==$subval['coa_id'])
                        echo $expval['coa_name'];
                    }
                    ?>

                </td>
                <td class="text-right"><?php echo $subval['qty'];?></td>
                <td class="text-right"><?php echo $subval['uom'];?></td>
                <td class="text-right"><?php echo $subval['unit_price'];?></td>
                <td class="text-right"><?php echo $subval['amount'];?></td>
                <td class="text-right"><?php echo $subval['discount_amt'];?></td>
                <td class="text-right"><?php echo $subval['tax_percent'];?></td>
                <td class="text-right"><?php echo $subval['tax_amt'];?></td>
                <td class="text-right"><?php echo $subval['net_amount'];?></td>
            </tr>
                <?php $i++; } ?>
            </tbody>
            <tfoot>
            <tr class="primary">
             <th class="text-right text-primary" colspan="10">
            Grand Total (RM)
            </th>
            <th class="text-right text-primary">
                <?php echo $poitem['total'];?> </th>
            </tr>
            </tfoot>
            </table>
        </div>
        <div style="clear: both;"></div>
        <div class="row">
        <div class="col-xs-12">
        </div>
        <div style="clear: both;"></div>
        <div class="col-xs-4 pull-left">

       <!-- <p style="border-bottom: 1px solid #999;">&nbsp;</p>
        <p>Signature &amp; Stamp</p>-->
        </div>
        <div class="col-xs-12 pull-left">
        <p>&nbsp;</p>
        <p>
         <b>Remarks:</b><br/>
			<?php echo $poitem['remarks'];?></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <!--<p style="border-bottom: 1px solid #999;">&nbsp;</p>
        <p>Signature &amp; Stamp</p>-->
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="col-xs-12" style="margin-top: 15px;">
</div>
</div>
</div>

<script type="text/javascript">
    $('.modeltitledisp').html('<i class="fa fa-file"></i> Purchase Order Details (<?php echo $poitem[po_no];?>)');
	document.getElementById("datavaladded").value=<?php echo $poitem['pur_order_id'];?>;
</script>