<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Property Butler | Purchase Orders</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bms_media_query.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bms_custom_styles.css">
	<style type="text/css">
	@media print {
    .make-grid(sm);

    .visible-xs {
        .responsive-invisibility();
    }

    .hidden-xs {
        .responsive-visibility();
    }

    .hidden-xs.hidden-print {
        .responsive-invisibility();
    }

    .hidden-sm {
        .responsive-invisibility();
    }

    .visible-sm {
        .responsive-visibility();
    }
}	
	</style>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link id="avast_os_ext_custom_font" href="chrome-extension://eofcbnmajmjmplflapaojjnihcjkigck/common/ui/fonts/fonts.css" rel="stylesheet" type="text/css"><style> @-webkit-keyframes loadingoverlay_animation__rotate_right { to { -webkit-transform : rotate(360deg); transform : rotate(360deg); } } @keyframes loadingoverlay_animation__rotate_right { to { -webkit-transform : rotate(360deg); transform : rotate(360deg); } } @-webkit-keyframes loadingoverlay_animation__rotate_left { to { -webkit-transform : rotate(-360deg); transform : rotate(-360deg); } } @keyframes loadingoverlay_animation__rotate_left { to { -webkit-transform : rotate(-360deg); transform : rotate(-360deg); } } @-webkit-keyframes loadingoverlay_animation__fadein { 0% { opacity   : 0; -webkit-transform : scale(0.1, 0.1); transform : scale(0.1, 0.1); } 50% { opacity   : 1; } 100% { opacity   : 0; -webkit-transform : scale(1, 1); transform : scale(1, 1); } } @keyframes loadingoverlay_animation__fadein { 0% { opacity   : 0; -webkit-transform : scale(0.1, 0.1); transform : scale(0.1, 0.1); } 50% { opacity   : 1; } 100% { opacity   : 0; -webkit-transform : scale(1, 1); transform : scale(1, 1); } } @-webkit-keyframes loadingoverlay_animation__pulse { 0% { -webkit-transform : scale(0, 0); transform : scale(0, 0); } 50% { -webkit-transform : scale(1, 1); transform : scale(1, 1); } 100% { -webkit-transform : scale(0, 0); transform : scale(0, 0); } } @keyframes loadingoverlay_animation__pulse { 0% { -webkit-transform : scale(0, 0); transform : scale(0, 0); } 50% { -webkit-transform : scale(1, 1); transform : scale(1, 1); } 100% { -webkit-transform : scale(0, 0); transform : scale(0, 0); } } </style>
</head>
<div id="wrap">
   <h4 class="word_text text-primary text-center text-bold"><?php echo $pro_name; ?></h4>
    <div class="row">
        <div class="col-xs-6">
            <?php
			
            foreach ($service_provider as $key => $val) {
                if($poitem['service_provider_id'] == $val['service_provider_id']){
            ?>

            <h3><?php echo $val['provider_name'];?></h3>
            <?php echo $val['address'];?>,<br/><?php echo $val['city'];?>, <?php echo $val['postcode'];?>, <?php echo $val['state'];?>,<br/><?php echo $val['country_name'];?><br/>Tel : <?php echo $val['office_ph_no'];?><br/>Email : <?php echo $val['person_inc_email'];?><br/>
            <?php
            }
            }
            ?>
        </div>

        <div class="col-xs-6 text-right">
            <h3><?php echo $poitem['exp_doc_no'];?></h3>
			Invoice No : <?php echo $poitem['exp_inv_no'];?> <br/>
			Date : <?php echo date('d-m-Y', strtotime($poitem['exp_date']));?><br/>
             <br/>
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
                <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Tax (%)</th>
                <th class="col-xs-1 text-center" style="min-width:100px;padding-left:0;padding-right:0;">Tax Amount</th>
                <th class="col-xs-2 text-center">Nettotal</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i=1;$editstat = "";

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
                <td class="text-right"><?php echo $subval['taxpercent'];?></td>
                <td class="text-right"><?php echo $subval['taxamt'];?></td>
                <td class="text-right"><?php echo $subval['net_amount'];?></td>
            </tr>
                <?php $i++;
					$subtot = $subval['subtotal'];
					$totat = $subval['total'];
					$editstat = "editrec";
				} ?>
            </tbody>
            <tfoot>
            
			 <tr class="primary">
				<th class="text-right text-primary" colspan="10">
					Grand Total (RM)
				</th>
				<th class="text-right text-primary">
					<?php echo $totat;?> 
				</th>
            </tr>
			
			<?php if($editstat) { ?>
            <tr class="primary">
				<th class="text-right text-primary" colspan="10">
					Invoice Amount (RM)
				</th>
				<th class="text-right text-primary">
					<?php echo $poitem['total'];?> 
				</th>
            </tr>
			<?php } ?>
			
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
		</br></br></br></br>
        <table width="800px">
            <tr class="noBorder">
                <td align="center">
                    -------------------------------
                </td>
                <td align="center">
                    --------------------------------
                </td>
                <td align="center">
                    ---------------------------------
                </td>
            </tr>
            <tr class="noBorder">
                <td align="center">PREPARED BY</td>
                <td align="center">APPROVED BY </td>
                <td align="center">SIGNATURE </td>
            </tr>

        </table>
        
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="col-xs-12" style="margin-top: 15px;">
</div>
</div>
</div>

<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		window.print();
	 });
</script>
