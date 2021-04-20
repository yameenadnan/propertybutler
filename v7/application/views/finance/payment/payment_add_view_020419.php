     <?php
$this->load->view('header');
$this->load->view('sidebar'); // echo "<pre>";print_r($properties); ?>

<!-- bootstrap datepicker -->
<link rel="stylesheet"
    href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- SELECT 2 -->
<link rel="stylesheet"
    href="<?php echo base_url();?>bower_components\select2\dist\css\select2.css">

<style type="text/css">
    .table > thead:first-child > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table-striped thead tr.primary:nth-child(odd) th {
    background-color: #428BCA;
    color: white;
    border-color: #357EBD;
    border-top: 1px solid #357EBD;
    text-align: center;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
      </h1>

    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <?php
        $last_itm_cnt = 0;
        ?>
      <!-- general form elements -->
        <div class="box box-primary">
			<?php
				if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
					echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
					echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
					unset($_SESSION['flash_msg']);
				}
			?>
        <div class="box-body">
			<div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
				<div class="row" style="background-color: #d2cece; height: 50px;" >
				<h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($pv_item['pay_no']) ? 'Update Payment ('.$pv_item['pay_no'].')' : 'New Payment';?> </h3>
				</div>
                <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_payment/add_payment_order');?>" method="post" enctype="multipart/form-data">
                    <div class="row" style="padding-top: 15px;padding-bottom:15px;">
                        <input type="hidden" id="po_id" name="po_id"  value="<?php echo $_SESSION['bms_default_property'];?>" />
						<div class="col-md-2">
							<label>Property Name *</label>
						</div>
						<div class="col-md-4">
							<select class="form-control" id="property_id" name="property_id" disabled="disabled">
								<option value="">Select</option>
								 <?php
								foreach ($properties as $key=>$val) {
									$selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
									echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
								} ?>
							</select>
						  <!-- Hidden fields -->
							<input type="hidden" name="pv_item[pay_id]" value="<?php echo !empty($pv_item['pay_id']) ? $pv_item['pay_id'] : '';?>" />
							<input type="hidden" name="pv_item[pay_no]" value="<?php echo !empty($pv_item['pay_no']) ? $pv_item['pay_no'] : '';?>" />
							<input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
						</div>
						<div class="col-md-2">
							<label>Payment Date *</label>
						</div>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control pull-right datepicker"
									name="pv_date" type="text" value="<?php echo !empty($pv_item['pay_date']) ? date('d-m-Y',strtotime($pv_item['pay_date'])) : date("d-m-Y"); ?>" />
							</div>
						</div>
                        <!------------------------------------------------------------>
						<div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                            <div class="col-md-2">
                                <label>Service Provider *</label>
                            </div>
                            <div class="col-md-4">
                                <?php
                                $select_value = "";
                                if(isset($pv_item)){
                                    if($pv_item['pay_service_provider_id']==0){
                                        $select_value = "selected";
                                    }
                                }
                                ?>
                                 <select class="form-control select2" id="service_provider"
                                    name="service_provider" >
                                    <option value="">Select</option>
                                    <option value="new" <?php echo $select_value; ?> >New User</option>
                                      <?php
                                      foreach ($service_provider as $key => $val) {
                                          $selected = isset($pv_item['pay_service_provider_id']) && $pv_item['pay_service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
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
                                <label>Invoice Number</label>
                            </div>

                            <div class="col-md-4">

                                <select class="form-control select2 show-tick selectpicker"  data-live-search="true"  id="po_number"
                                    name="pv_number[]" data-live-search="true" multiple>
                                    <?php
                                    foreach ($invnum as $key => $val) {
                                        $selected = $pv_item['pay_inv_id'] == $val['exp_inv_id'] ? 'selected="selected"' : '';
                                        echo "<option value='" . $val['exp_inv_id'] . "'
                                                              " . $selected . ">" . $val['exp_inv_no']. "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
							<!------------------------------------- ---------------------------------->
                             <div class="row" id="prov_name" <?php if($pv_item['pay_service_provider_name']!='') { ?> style="display: block;" <?php } else { ?>style="display: none;" <?php } ?>>
                              <div class="col-md-12" style="margin-top:15px;">
                                <div class="col-md-2 col-xs-4 text-left" style="padding-top: 5px;">
                                  <label> Provider Name </label>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                   <input type="text" name="provider_name" required="" value="<?php echo $pv_item['pay_service_provider_name'];?>" class="form-control">
                                </div>
                                  <div class="col-md-2 col-xs-4 text-left" style="padding-top: 5px;">
                                      <label> Provider Address </label>
                                  </div>
                                  <div class="col-md-4 col-xs-4">
                                      <textarea  id="provider_address" name="provider_address" name="" class="form-control"><?php echo $pv_item['pay_service_provider_address'];?></textarea>
                                  </div>
                              </div>
                            </div>
						</div>
						<!------------------------------------------------------------------------------>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 15px !important;">
                            <div class="col-md-2">
                                <label>Bank*</label>
                            </div>

                            <div class="col-md-4">

                                  <select class="form-control select2" id="bank_id" name="paymt[bank_id]">
                                    <option value="">Select</option>
                                    <?php
                                    if(!empty($banks)) {
                                        foreach ($banks as $key=>$val) {
                                            $selected = $pv_item['bank_id'] == $val['bank_id'] ? 'selected="selected"' : '';
											echo "<option value='".$val['bank_id']."' ".$selected.">".$val['bank_name']."</option>";
                                        }
                                    }
                                    ?>
                                  </select>
                            </div>

							<div class="col-md-2 col-xs-4">
							   <label>Payment mode *</label>
							</div>
							<div class="col-md-4">
								  <select class="form-control select2" id="payment_mode" name="paymt[payment_mode]">
								<?php $payment_mode = $this->config->item ('payment_mode');
									foreach ($payment_mode as $key=>$val) {
										if($key!=3 && $key!=5){
										$checked = $val == 'CASH' ? 'checked="checked"' : '';
										$selected = isset($pv_item['pay_mod']) && $pv_item['pay_mod'] == $key ?  'selected="selected" ' : '';
										echo "<option value='".$key."' ".$selected.">".$val."</option>";
										}
									}
								?>
								</select>
							</div>
						</div>
						<!------------------------------------------------------------------------------>
						<div class="row pay_mode_details pay_mode_2" style="display: none;">
                          <div class="col-md-12" style="margin-top:15px;">
                            <div class="col-md-1 col-xs-4 text-right" style="padding-top: 5px;">
                              <label> Cheque Bank </label>
                            </div>
                            <div class="col-md-3 col-xs-2">
                               <input type="text" name="pm_details[cheq_bank]" value="<?php echo $pv_item['pay_chq_bank_name'];?>" class="form-control">
                            </div>

                            <div class="col-md-1 col-xs-4 text-right" >
                                <label> Cheque No </label>
                            </div>
                            <div class="col-md-3 col-xs-4">
                               <input type="text" name="pm_details[cheq_no]" value="<?php echo $pv_item['pay_cheq_no'];?>" class="form-control">
                            </div>
                            <div class="col-md-2 col-xs-4 text-right" style="padding-top: 5px;">
                                <label> Cheque Date </label>
                            </div>
                            <div class="col-md-2 col-xs-4">
                               <input type="text" name="pm_details[cheq_date]" value="<?php echo !empty($pv_item['pay_cheq_date']) ? date('d-m-Y',strtotime($pv_item['pay_cheq_date'])) : ""; ?>" class="form-control datepicker">
                            </div>

                          </div>
						</div>
                      <!---  END -------->

						<div class="row pay_mode_details pay_mode_4" style="display: none;">
						  <div class="col-md-12" style="margin-top:15px;">
							<div class="col-md-2 col-xs-4 text-right" style="padding-top: 5px;">
							  <label> Online Bank
							</div>
							<div class="col-md-3 col-xs-4">
							   <input type="text" name="pm_details[online_bank]" value="<?php echo $pv_item['pay_online_bank'];?>" class="form-control">
							</div>

							<div class="col-md-1 col-xs-4 text-right" >
							  <label> Txn No</label>
							</div>
							<div class="col-md-3 col-xs-4">
							   <input type="text" name="pm_details[online_txn_no]" value="<?php echo $pv_item['pay_online_txn_no'];?>" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;">
							</div>
						  </div>

						  <div class="col-md-12" style="margin-top:15px;">
							<div class="col-md-2 col-xs-4 text-right" style="padding-top: 5px;">
							   <label>Online Type</label>
							</div>
							<div class="col-md-3 col-xs-4">

							   <select  name="pm_details[online_type]" class="form-control" >
									<option value="">Select</option>
									<?php $online_type = $this->config->item ('online_type');
										foreach ($online_type as $key=>$val) {
											 $selected = $pv_item['pay_online_type'] == $key ? 'selected="selected"' : '';
											//echo '<option value="'.$key.'">'.$val.'</option>';
											echo "<option value='".$key."' ".$selected.">".$val."</option>";
										}
									?>
							  </select>
							</div>

							<div class="col-md-1 col-xs-4 text-right" style="padding-top: 5px;">
							   <label> Date</label>
							</div>
							<div class="col-md-3 col-xs-4">
							   <input type="text" name="pm_details[online_date]" value="<?php echo !empty($pv_item['pay_online_date']) ? date('d-m-Y',strtotime($pv_item['pay_online_date'])) : ""; ?>" class="form-control datepicker">
							</div>

						  </div>
					  </div>
					</div>
                    <!-- /.row -->


                    <!-- /.PO ORDER DETEAILS -->
                    <div class="col-md-12 no-padding podetails" style="display: none">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Order Summary</strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <div class="podisplayorder"></div>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- END PO ORDER DETAILS -->
                    <?php
                        if($pv_item['pay_inv_id']){
                    ?>
                    <!-- PO ORDER DISPLAY -->
                    <div class="po_available">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Order summary</strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <div class="podisplayorder1">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Invoice No</th>
                                                <th class="center">Document No</th>
                                                <th class="right">Subtotal</th>
                                                <th class="center">Discounts</th>
                                                <th class="center">Tax Amt</th>
                                                <th class="center">Total PO Amt</th>
                                                <th class="right">Invoice Amt</th>
                                                <th class="right">Paid Amt</th>
                                                <th class="right">Payable Amt</th>
                                                <th class="right">Balance Amt</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            
                                            foreach ($expsubitem as $key => $value) {  
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$key;?></td>
                                                    <td><?php echo $value['exp_inv_no'];?></td>
                                                    <td><?php echo $value['exp_doc_no'];?></td>
                                                    <td><?php echo $value['subtotal'];?></td>
                                                    <td><?php echo $value['discounts'];?></td>
                                                    <td><?php echo $value['tax_amt'];?></td>
                                                    <td>
                                                    <?php if(!empty($value['po_id'])) { ?>
                                                        <input type="hidden" name="items[inv_amt][]" id="invoc_amt_<?php echo ++$key;?>" value="<?php echo $value['pay_total'];?>"><?php echo $value['total'];?>
                                                    <?php } else { ?>
                                                        <input type="hidden" name="items[inv_amt][]" id="invoc_amt_<?php echo ++$key;?>" value="0.00">
                                                        0.00  
                                                    <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $payable = ($value['payment_pending_amount']==0.00)?$value['total']: $value['payment_pending_amount'];
                                                            $invtotaamt =  $value['expstot']; //($value['payment_pending_amount']!=0.00)?($value['payment_paid_amount']+$payable):$value['total'];
                                                        ?>
                                                    <input type="number" name="items[pay_amount][]" readonly id="pay_amt_<?php echo ++$key;?>" data-id='<?php echo ++$key;?>' class="form-control pay_amt"  style="width:100px" value="<?php echo $invtotaamt; ?>"></td>
                                                    <td>
                                                        <input type="hidden" name="items[exp_inv_no][]" id="expinv_<?php echo ++$key;?>" data-id='<?php echo ++$key;?>' class="form-control"  value="<?php echo ($value['exp_inv_id']); ?>" >
                                                        <input type="number" name="items[bal_amount][]" id="bal_amt_<?php echo ++$key;?>" data-id='<?php echo ++$key;?>' class="form-control bal_amt"  value="<?php echo ($value['payment_pending_amount']==0.00)?$value['expstot']: $value['payment_pending_amount'];?>"  style="width:100px"></td>
                                                    </td>
                                                    <td><?php echo $value['payment_paid_amount'];?></td>
                                                    <td><?php echo $value['payment_pending_amount'];?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            <!-- END PO DISPLAY -->
                    <?php } else { ?>
                     <!-- WITHOUT PO ORDER DISPLAY -->
                    <div class="col-md-12 no-padding">
                        <div class="btndisp">

                        <div class="col-md-2 no-padding">
                            <h2 style="color: #afa8a8;">Items</h2>
                        </div>
                        <div class="col-md-10" style="margin-top: 25px;">
                            <button type="button" class="btn  btn-primary add_sub_sop_btn pull-right"
                                id="sop_<?php echo count($pv_sub_items);?>" value="0" data-value="0"
                                data-id="<?php echo (count($pv_sub_items)? (count($pv_sub_items)+1):2);?>">Add Item</button>
                        </div>
                        </div>

                        <div class="col-md-12 items_container add_more_item no-padding">
                            <div class="col-md-12 item_div_1"
                                style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="items[description][]" class="form-control"
                                                style="-webkit-border-radius: 4px !important; border-radius: 4px !important;"
                                                rows="5" placeholder="Enter Description"><?php echo isset($pv_sub_items[0]['pay_description']) && $pv_sub_items[0]['pay_description'] != '' ? $pv_sub_items[0]['pay_description'] : '';?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>For Asset </label> <select
                                                    class="form-control select2 assetlst" name="items[assetlst][]">
                                                    <option value="">Select</option>
                                                <?php
                                                foreach ($property_assets as $key => $val) {
                                                    $selected = isset( $pv_sub_items[0]['pay_asset_id']) && trim( $pv_sub_items[0]['pay_asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                    echo "<option value='" . $val['asset_id'] . "'
                                                              " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                }
                                                ?>

                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Account Name </label> <select
                                                    class="form-control select2 cate" name="items[category][]"
                                                    data-id="1" id="cate_1">
                                                    <option value="">Select</option>
                                                    <?php
                                                        $group_name = "";
                                                        foreach ($expense_items as $key => $val) :
                                                        $selected = isset( $po_sub_items[0]['cat_id']) && trim( $po_sub_items[0]['cat_id']) == $val['coa_id'] ? 'selected="selected" ' : '';
                                                            if ($val['coa_type_name'] != $group_name) {
                                                                if ($endLabel) {
                                                                    echo '</optgroup>';
                                                                }
                                                                // echo label...
                                                                echo '<optgroup label="' . $val['coa_type_name'] . '">';
                                                                $group_name = $val['coa_type_name'];
                                                                $endLabel = true;
                                                            }
                                                            // $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                            echo "<option value='" . $val['coa_id'] . "'                                                    
                                                                    " . $selected . ">" . $val['coa_name'] . "</option>";
                                                        endforeach;
                                                    ?>
                                                    </select>
                                            </div>
                                        </div>                                 
                                    </div>
                                </div>

                                <div class="col-md-12 no-padding">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Quantity </label> <input class="form-control amt_quan"
                                                 name="items[quantity][]" value="<?php echo $pv_sub_items[0]['pay_qty'];?>" data-id="1" id="subquantity_1"
                                                type="number" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>UOM</label> <input class="form-control uom"
                                                name="items[uom][]"value="<?php echo $pv_sub_items[0]['pay_uom'];?>"  data-id="1" id="uom_1" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Unit Price</label> <input
                                                class="form-control amt_unit"  data-id="1"
                                                id="unitprice_1" name="items[subunitprice][]" value="<?php echo $pv_sub_items[0]['pay_unit_price'];?>"  type="number" />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Amount</label> <input readonly
                                                class="form-control amt_cal" value="<?php echo $pv_sub_items[0]['pay_amount'];?>" name="items[amount][]"
                                                data-id="1" id="subamount_1" type="number" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Discount Amt</label> <input
                                                class="form-control amt_disct" value="<?php echo !empty($pv_sub_items[0]['pay_discount_amt']) ? $pv_sub_items[0]['pay_discount_amt'] : 0;?>"  name="items[distamount][]"
                                                data-id="1" id="subdistamount_1" type="number" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Item Net Amount</label> <input readonly
                                                class="form-control amt_net" value="<?php echo $pv_sub_items[0]['pay_net_amount'];?>"  name="items[netamount][]"
                                                data-id="1" id="subnetamount_1" type="number" />
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="items[pay_item_id][]" value=<?php echo $pv_sub_items[0]['pay_item_id']; ?>>
                            </div>

                        <div class="col-md-12 col-xs-12 no-padding">
                        <?php
                            $sub_sop_cnt = 0;
                            if (! empty(pv_item['pay_no'])) { // SUB Routine Task
                                $total_count = count($pv_sub_items);
                                if($total_count>1){
                                    $array_slice = array_slice($pv_sub_items,1);
                                    foreach ($array_slice as $keyslice => $valslice) {
                                        $dataid_incre = $keyslice+2;
                                    ?>
                                        <div class="col-md-12 item_div_1"
                                        style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="items[description][]" class="form-control"
                                                        style="-webkit-border-radius: 4px !important; border-radius: 4px !important;"
                                                        rows="5" placeholder="Enter Description"><?php echo isset($valslice['pay_description']) && $valslice['pay_description'] != '' ? $valslice['pay_description'] : '';?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>For Asset </label> <select
                                                            class="form-control select2 assetlst" name="items[assetlst][]">
                                                            <option value="">Select</option>
                                                        <?php
                                                        foreach ($property_assets as $key => $val) {
                                                            $selected = isset( $valslice['pay_asset_id']) && trim( $valslice['pay_asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                            echo "<option value='" . $val['asset_id'] . "'
                                                                      " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                        }
                                                        ?>

                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-right">
                                            <button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="'+(sub_sop_cnt++)+'"  data-id="'+dataid+'" data-subsopid=""><i class="fa fa-close"></i></button>
                                             </div>

                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Account Name </label> <select
                                                            class="form-control select2 cate" name="items[category][]"
                                                            data-id="<?php echo $dataid_incre;?>" id="cate_<?php echo $dataid_incre;?>">
                                                            <option value="">Select</option>
                                                            <?php
                                                                $group_name = "";
                                                                foreach ($expense_items as $key => $val) :
                                                                $selected = isset( $po_sub_items[0]['cat_id']) && trim( $po_sub_items[0]['cat_id']) == $val['coa_id'] ? 'selected="selected" ' : '';
                                                                    if ($val['coa_type_name'] != $group_name) {
                                                                        if ($endLabel) {
                                                                            echo '</optgroup>';
                                                                        }
                                                                        // echo label...
                                                                        echo '<optgroup label="' . $val['coa_type_name'] . '">';
                                                                        $group_name = $val['coa_type_name'];
                                                                        $endLabel = true;
                                                                    }
                                                                    // $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                                    echo "<option value='" . $val['coa_id'] . "'                                                    
                                                                            " . $selected . ">" . $val['coa_name'] . "</option>";
                                                                endforeach;
                                                            ?>
                                                    </select>
                                                    </div>
                                                </div>
                                
                                            </div>

                                        </div>

                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Quantity </label> <input class="form-control amt_quan"
                                                         name="items[quantity][]" value="<?php echo $valslice['pay_qty'];?>" data-id="<?php echo $dataid_incre;?>" id="subquantity_<?php echo $dataid_incre;?>"
                                                        type="number" min="0" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>UOM</label> <input class="form-control uom"
                                                        name="items[uom][]"value="<?php echo $valslice['pay_uom'];?>"  data-id="<?php echo $dataid_incre;?>" id="uom_<?php echo $dataid_incre;?>" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Unit Price</label> <input
                                                        class="form-control amt_unit"  data-id="<?php echo $dataid_incre;?>"
                                                        id="unitprice_<?php echo $dataid_incre;?>" name="items[subunitprice][]" value="<?php echo $valslice['pay_unit_price'];?>"  type="number" />
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label> <input readonly
                                                        class="form-control amt_cal" value="<?php echo $valslice['pay_amount'];?>" name="items[amount][]"
                                                        data-id="<?php echo $dataid_incre;?>" id="subamount_<?php echo $dataid_incre;?>" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Discount Amt</label> <input
                                                        class="form-control amt_disct" value="<?php echo !empty($valslice['pay_discount_amt']) ? $valslice['pay_discount_amt'] : 0; ?>"  name="items[distamount][]"
                                                        data-id="<?php echo $dataid_incre;?>" id="subdistamount_<?php echo $dataid_incre;?>" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Item Net Amount</label> <input readonly
                                                        class="form-control amt_net" value="<?php echo $valslice['pay_net_amount'];?>"  name="items[netamount][]"
                                                        data-id="<?php echo $dataid_incre;?>" id="subnetamount_<?php echo $dataid_incre;?>" type="number" />
                                                </div>
                                            </div>
                                        </div>
                                            <input type="hidden" name="items[pay_item_id][]" value=<?php echo $valslice['pay_item_id']; ?>>

                                    </div>

                            </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                            </div>


                            <!---- SUB ITEM ADDED -->
                            <div class="col-md-12 col-xs-12 no-padding sub_sop_container_0">
                            </div>
                            <!-- END SUBITEM -->

                            <!-----------------------------  Payment totals -->
                            <div class="col-md-12 items_container col-xs-6 dispsubtitemval" style="margin-top: 15px;">
                                <div class="col-md-12 no-padding">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Payment Sub Total</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                      <input type="text" class="form-control resultsubtotal" name="rstsubtot" id="rstsubtot"  value="<?php echo (($pv_item['pay_subtotal']!='')?$pv_item['pay_subtotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                      <label>Discount Amt</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                      <input type="text" class="form-control maindiscount" name="maindiscount" id="maindiscount" value="<?php echo (($pv_item['pay_discounts']!='')?$pv_item['pay_discounts']:0);?>"style="text-align: right;" >
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                      <label>Payment Net Amount</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                      <input type="text" class="form-control mainntat" name="mainntat" id="mainntat"  value="<?php echo (($pv_item['pay_nettotal']!='')?$pv_item['pay_nettotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                      <label>Tax(%)</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                      <input type="text" class="taxpers form-control" name="taxpers" value="<?php echo (($pv_item['pay_tax_percent']!='')?$pv_item['pay_tax_percent']:0);?>" style="text-align: right;" id="taxpers">
                                    </div>
                                </div>

                              <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                <div class="col-md-8 col-xs-6">&nbsp;</div>
                                <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                  <label>Tax Amt</label>
                                </div>
                                <div class="col-md-2 col-xs-12 ">
                                  <input type="text" class="taxamt form-control" name="taxamt" id="taxamt" value="<?php echo (($pv_item['pay_tax_amt']!='')?$pv_item['pay_tax_amt']:0);?>" style="text-align: right;" readonly="true">
                                </div>
                              </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                      <label>Payment Total</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                      <input type="text" class="maintotat form-control" name="maintotat" id="maintotat" value="<?php echo (($pv_item['pay_total']!='')?$pv_item['pay_total']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                            </div>
                            <!--------------------- END PAYMENT -------------->

                        <?php } ?>
                        </div>
                    <!-- END WITHOUT PV ORDER DISPLAY -->

                    <!------------------------->
                    <div class="col-md-12 no-padding" style="padding: 10px 0 !important;">
                        <div class="col-md-2 col-xs-6">
                            <h3>
                                <b>Remarks</b>
                            </h3>
                        </div>

                        <div class="col-md-6 col-xs-12" >
                            <textarea rows="4" name="remarks" class="form-control" cols="50"><?php echo !empty($pv_item['remarks'])? $pv_item['remarks'] : '';?></textarea>
                        </div>
                    </div>
                    <!------------------------->
                    <div class="row" style="text-align: right; margin: 0 -10px;">
                        <div class="col-md-8">
                            <div class="box-footer" style="border-top: 0px solid #f4f4f4;">
                                <button type="submit" class="btn btn-primary submit_btn"
                                        name="action" value="save_only">Submit</button>&ensp;
                                <button type="Reset" class="btn btn-default reset_btn">Reset</button>
                                &ensp;&ensp;
                                
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php $this->load->view('footer'); ?>
<!-- bootstrap datepicker -->

     <script
             src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
     <script
             src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>

     <!-- overlay loader for ajax call -->
     <script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
     <script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
     <!-- SELECT 2 -->
     <script
             src="<?php echo base_url();?>bower_components\select2\dist\js\select2.full.js"></script>

     <script>
         $(document).ready(function (){
             $('.select2').select2();

             $( "#bms_frm" ).validate({
                 rules: {
                     "po[po_date]": "required",
                     "po[service_provider]": "required"
                 },
                 messages: {
                     "po[po_date]": "Please select Date",
                     "po[service_provider]": "Please select Service Provider"
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

         var sop_cnt = eval("<?php echo $last_itm_cnt;?>");
         var sub_sop_cnt = eval("<?php echo $sub_sop_cnt;?>");
         $('.add_sub_sop_btn').click(function () {
             debugger;
             var id = $(this).attr('id');
             var val = $(this).val();
             var parent_id =$(this).attr('data-value')
             var dataid = $(this).attr('data-id')

             var str = '<div class="col-md-12 item_div_1 sub_sop_'+id+' sub_sop_'+sub_sop_cnt+'"  style="background-color:#ECF0F5;margin: 10px 0 5px 0;padding:10px 0 !important;border: 1px solid #999;border-radius: 5px;">';
             str+= '<div class="col-md-12">';
             str += '</div>';
             str += '<div class="col-md-12 no-padding">';
             str +='<div class="col-md-6">';
             str +='<div class="form-group">';
             str +='<label >Description</label>';
             str +='<textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important;border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo isset($service_provider['job_scope']) && $service_provider['job_scope'] != '' ? $service_provider['job_scope'] : '';?></textarea>';
             str +='</div>';
             str +='</div>';
             str += '<div class="col-md-4">';
             str  +='<div class="col-md-12">';
             str  +='<div class="form-group" >';
             str  +='<label >For Asset </label>';
             str  +='<select class="form-control select2 assetlst" size="3" name="items[assetlst][]">';
             str  +='<option value="">Select</option>';
             str  +='<?php
                 foreach ($property_assets as $key => $val) {
                     echo "<option value=" . $val[asset_id] . ">" . $val[asset_name] . "-" . $val[asset_location] . "</option>";
                 }
                 ?>';
             str  +='</select>';
             str  +='</div>';
             str  +='</div>';
             str  +='</div>';
             str +='<div class="col-md-2 text-right">';
             str += '<button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="'+(sub_sop_cnt++)+'"  data-id="'+dataid+'" data-subsopid=""><i class="fa fa-close"></i></button>';
             str +='</div>';

             str += '<div class="col-md-4">';
             str += '<div class="col-md-12">';
             str += '<div class="form-group">';
             str += '<label >Account Name </label>';
             str += '<select class="form-control select2 cate" name="items[category][]" id="cate_'+dataid+'"  data-id="'+dataid+'">';
             str += '<option value="">Select</option>';
             str += '<?php
                 $group_name = "";
                 foreach ($expense_items as $key => $val) :
                     if ($val['coa_type_name'] != $group_name) {
                         if ($endLabel) {
                             echo "</optgroup>";
                         }
                         // echo label...
                         echo "<optgroup label=" . $val['coa_type_name'] . ">";
                         $group_name = $val['coa_type_name'];
                         $endLabel = true;
                     }
                     echo "<option value=" . $val['coa_id'] . ">" . $val['coa_name'] . "</option>";
                 endforeach
                 ;
                 ?>';
             str += '</select>';
             str += '</div>';
             str += '</div>';
              
             str += '</div>';
             str += '<div>';
             str += '<div class="col-md-12 no-padding">';
             str += '<div class="col-md-2">';
             str += '<div class="form-group">';
             str += '<label >Quantity </label>';
             str += '<input class="form-control amt_quan" value="" name="items[quantity][]"  data-id="'+dataid+'" id="subquantity_'+dataid+'"  type="number" min="0" />';
             str += '</div>';
             str += '</div>';
             str +=  '<div class="col-md-2">';
             str +=  '<div class="form-group">';
             str +=  '<label >UOM</label>';
             str +=  '<input class="form-control uom" value="" name="items[uom][]" data-id="'+dataid+'" id="uom_'+dataid+'"  type="text" />';
             str +=  '</div>';
             str +=  '</div>';
             str +=  '<div class="col-md-2">';
             str +=  '<div class="form-group">';
             str +=  '<label >Unit Price</label>';
             str +=  '<input class="form-control amt_unit" value="" data-id="'+dataid+'" id="unitprice_'+dataid+'" name="items[subunitprice][]"  type="number" />';
             str +=  '</div>';
             str +=  '</div>';
             str +=  '<div class="col-md-2">';
             str +=  '<div class="form-group">';
             str +=  '<label >Amount</label>';
             str +=  '<input readonly class="form-control amt_cal" value="0" name="items[amount][]" data-id="'+dataid+'" id="subamount_'+dataid+'" type="number" />';
             str +=  '</div>';
             str +=  '</div>';
             str +=  '<div class="col-md-2">';
             str +=  '<div class="form-group">';
             str +=  '<label >Discount Amt</label>';
             str +=  ' <input class="form-control amt_disct" value="0" name="items[distamount][]" data-id="'+dataid+'" id="subdistamount_'+dataid+'" type="number"  />';
             str +=  '</div></div>';
             str +=  '<div class="col-md-2">';
             str +=  '<div class="form-group">';
             str +=  '<label >Item Net Amount</label>';
             str +=  ' <input readonly class="form-control amt_net" value="0" name="items[netamount][]" data-id="'+dataid+'" id="subnetamount_'+dataid+'"  type="number"  />';
             str += '<input type="hidden" name="items[pay_item_id][]" value=0>';
             str +=  '</div>';
             str +=  '</div>';
             str +=  '</div>';

             str += '</div>';
             str += '</div>';

             str += '</div>';


             $('.sub_sop_container_'+parent_id).append(str);
             $(this).attr('data-id',eval(dataid)+1);

             $('#'+id).val(val);

              
             $('.assetlst').unbind('change');
             

             $('.amt_quan, .amt_unit, .amt_disct').unbind('keyup');
             $('.amt_quan, .amt_unit, .amt_disct').bind('keyup',function (){
                 dataid = $(this).attr('data-id');
                 calc_total_amt (dataid);
             });

             $('.delete_sub_btn').unbind("click");
             $('.delete_sub_btn').bind ("click",function () {
                 delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"),$(this).attr('data-id') );
             });
             $('.select2').select2();

         });


         function delete_sub_sop (div_cls_id, sub_sop_id, data_id) {
             debugger;
             if(sub_sop_id == "") {
                 $('.sub_sop_'+div_cls_id).remove();
             } else {
                 if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
                     $.ajax({
                         type:"post",
                         async: true,
                         url: '<?php echo base_url('index.php/bms_fin_payment/delete_purchase');?>/'+sub_sop_id, // Reusing the same function from task creation
                         data: {'sub_sop_id':sub_sop_id},
                         //datatype:"json", // others: xml, json; default is html
                         beforeSend:function (){ $('.sub_sop_'+div_cls_id).LoadingOverlay("show"); }, //
                         success: function(data) {
                             $('.sub_sop_'+div_cls_id).LoadingOverlay("hide", true);
                             if(data == 1) {
                                 $('.sub_sop_'+div_cls_id).remove();
                             }
                         },
                         error: function (e) {
                             $('.sub_sop_'+div_cls_id).LoadingOverlay("hide", true);
                             console.log(e); //alert("Something went wrong. Unable to retrive data!");
                         }
                     });
                 }
             }

             var amt_net = 0;
             $('.amt_net').each(function () {
                 if($(this).val() != '') {
                     amt_net += eval($(this).val());
                 }
             });


             var amt_net = parseFloat(amt_net).toFixed(2)
             $('.resultsubtotal').html(amt_net);
             $('#rstsubtot').val(amt_net);

             var getdist = $('#maindiscount').val();
             if(getdist>0){
                 amt_net = parseFloat(amt_net) - parseFloat(getdist);
             }
             var amt_net = parseFloat(amt_net).toFixed(2)
             $('.maintotalamount').html(amt_net);
             $('#maintotat').val(amt_net)
             var gettax = $('#taxpers').val();
             amt_nettotal = $('.mainnetamt').html()
             if(gettax>0){
                 var gettaxval = parseFloat($('#taxpers').val());
                 var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
                 $('.taxamt').val(calctax);
                 var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
                 var netat = parseFloat(disnetamt).toFixed(2)
                 $('.maintotalamount').html(netat);
                 $('#maintotat').val(netat);
             }else {
                 var netat = parseFloat(disnetamt).toFixed(2)
                 $('.maintotalamount').html(netat);
                 $('#maintotat').val(netat)
             }
             calc_main_discount();
             calc_tax_percent();
         }
         $('.delete_sub_btn').bind ("click",function () {
             debugger;
             delete_sub_sop ($(this).attr('data-value'),$(this).attr("data-subsopid"),$(this).attr('data-id'));
         });

         $('.amt_quan, .amt_unit, .amt_disct').keyup(function(){
             dataid = $(this).attr('data-id');
             calc_total_amt (dataid);
         });


         function calc_main_discount(){
             var mainsub = $("#rstsubtot").val();
             var getval = parseFloat($('#maindiscount').val());
             var disamt = parseFloat(mainsub) -getval ;
             var disamt = parseFloat(disamt).toFixed(2)
             $('#mainntat').val(disamt);
             var disamt = parseFloat(disamt).toFixed(2)
             $('#maintotat').val(disamt)
         }


         function calc_tax_percent() {
             var mainnet = $('#mainntat').val();
             var gettaxval = parseFloat($('#taxpers').val());
             var calctax = (mainnet*gettaxval/100).toFixed(2);
             $('.taxamt').val(calctax);
             var disnetamt = parseFloat(mainnet) - parseFloat(calctax) ;
             var disnetamt = parseFloat(disnetamt).toFixed(2);
             $('#maintotat').val(disnetamt);
         }

         $('.maindiscount').keyup(function(){
             calc_main_discount();
             calc_tax_percent();
         });

         $('.taxpers').keyup(function(){
             calc_tax_percent();
         });


         function calc_total_amt (dataid) {
             debugger;
             var quant = 0;
             var unit = 0;
             var amount = 0;
             $('.amt_quan').each(function () {
                 if($(this).val() != '') {
                     quant = eval($('#subquantity_'+dataid).val());
                 }
             });
             $('.amt_unit').each(function () {
                 if($(this).val() != '') {
                     unit = eval($('#unitprice_'+dataid).val());
                 }
             });
             var subtotal = 0;
             amount = (quant * unit);

             subtotal = parseFloat(amount).toFixed(2);
             $('#subamount_'+dataid).val(amount);
             var amt_disct = 0;
             var subnetamt = 0;
             $('.amt_disct').each(function () {
                 if($(this).val() != '') {
                     amt_disct = eval($('#subdistamount_'+dataid).val());
                 }
             });
             subnetamt = (amount - amt_disct);
             $('#subnetamount_'+dataid).val(subnetamt);

             var amt_net = 0;
             $('.amt_net').each(function () {
                 if($(this).val() != '') {
                     amt_net += eval($(this).val());
                 }
             });

             var amt_net = parseFloat(amt_net).toFixed(2)
             $('.resultsubtotal').html(amt_net);
             $('#rstsubtot').val(amt_net);
             var getdist = $('#maindiscount').val();
             if(getdist>0){
                 amt_net = parseFloat(amt_net) - parseFloat(getdist);
             }
             var amt_net = parseFloat(amt_net).toFixed(2)
             $('.mainnetamt').html(amt_net);
             $('#mainntat').val(amt_net);
             var gettax = $('#taxpers').val();
             amt_nettotal = $('.mainnetamt').html()
             if(gettax>0){
                 var gettaxval = parseFloat($('#taxpers').val());
                 var calctax = (amt_nettotal*gettaxval/100).toFixed(2);
                 $('.taxamt').val(calctax);
                 var disnetamt = parseFloat(amt_nettotal) - parseFloat(calctax) ;
                 var disnetamt = parseFloat(disnetamt).toFixed(2)
                 $('.maintotalamount').html(disnetamt);
                 $('#maintotat').val(disnetamt)
             }else {
                 var amt_net = parseFloat(amt_net).toFixed(2)
                 $('.maintotalamount').html(amt_net);
                 $('#maintotat').val(amt_net)
             }
             calc_main_discount();
             calc_tax_percent();

         }

         function set_pay_mode_details () {
             $('.pay_mode_details').css('display','none');
             $('.pay_mode_'+$('#payment_mode').val()).slideDown();
             //$('.'+$("input[name='receipt[payment_mode]']:checked").attr('id')).css('display','block');
             //console.log($("input[name='receipt[pay_mode]']:checked").val());
         }

         $(function () {
             $('#payment_mode').change(function () {
                 set_pay_mode_details ();
             });
             set_pay_mode_details ();
//Date picker
             $('.datepicker').datepicker({
                 format: 'dd-mm-yyyy',
                 autoclose: true,
                 'minDate': new Date()
             });

         });

         // Load block and assign to drop down
         function category_changes(cateid, subid) {
             debugger;
             $.ajax({
                 type:"post",
                 async: true,
                 url: '<?php echo base_url('index.php/bms_fin_payment/getSubCategoryList');?>',
                 data: {'category_id':cateid},
                 datatype:"json", // others: xml, json; default is html

                 beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                 success: function(data) {
                     var str = '<option value="">Select</option>';
                     if(data.length > 0) {
                         $.each(data,function (i, item) {
                             str += '<option value="'+item.charge_code_sub_category_id+'">'+item.charge_code_sub_category_name+'</option>';
                         });
                     }
                     $('#'+subid).html(str);
                     $("#content_area").LoadingOverlay("hide", true);

                 },
                 error: function (e) {
                     $("#content_area").LoadingOverlay("hide", true);
                     console.log(e); //alert("Something went wrong. Unable to retrive data!");
                 }
             });
         }

         loadBank ($('#property_id').val());

         if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
             $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));
         }

         $('#service_provider').change(function () {
             showndhide();
             var showvalue = $(this).val();
             if(showvalue=="new"){
                 $('#prov_name').show();
             }else {
                 $('#prov_name').hide();

                 $.ajax({
                     type: "post",
                     async: true,
                     url: '<?php echo base_url('index.php/bms_fin_payment/getINVorderNumber');?>',
                     data: {'servprov_id': showvalue},
                     datatype: "json", // others: xml, json; default is html

                     beforeSend: function () {
                         $("#content_area").LoadingOverlay("show");
                     }, //
                     success: function (data) {
                         var str = '<option value="">Select</option>';
                         if (data.length > 0) {
                             $.each(data, function (i, item) {
                                 str += '<option value="' + item.exp_inv_id + '">' + item.exp_inv_no + '</option>';
                             });
                         }
                         $('#po_number').html(str);
                         $("#content_area").LoadingOverlay("hide", true);
                     },
                     error: function (e) {
                         $("#content_area").LoadingOverlay("hide", true);
                         console.log(e); //alert("Something went wrong. Unable to retrive data!");
                     }
                 });
             }
         });

         $("#po_number").change(function(){
             var ponumber = $(this).val();

             showndhide(ponumber);
         });

         function showndhide(ponumber=''){
             if(ponumber!=''){
                 $('.add_more_item ').hide();
                 $('.btndisp').hide();
                 $('.podetails').show();
                 $('.po_available').hide();
                 $('.dispsubtitemval').hide();
                 getpodetails(ponumber)
             }else {
                 $('.add_more_item ').show();
                 $('.btndisp').show();
                 $('.podetails').hide();
                 $('.dispsubtitemval').show();
             }
         }
         function getpodetails(po_id){
             debugger;
             poid = po_id.toString();
			  $(".podisplayorder").LoadingOverlay("hide", true);
             $.ajax({
                 type:"post",
                 async: true,
                 url: '<?php echo base_url('index.php/bms_fin_payment/getExpInvoiceDetails');?>',
                 data: {'po_id':poid},
                 datatype:"json", // others: xml, json; default is html
                 // beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                 success: function(data) {
                     var strap = '<table class="table table-striped table-bordered">';
                     strap +='<thead>';
                     strap +='<tr>';
                     strap +='<th class="center">#</th>';
                     strap +='<th>Invoice No</th>';
                     strap +='<th>Document No</th>';
                     strap +='<th class="center">Subtotal</th>';
                     strap +='<th class="right">Discounts</th>';
                     strap +='<th class="center">Tax Amt</th>';
                     strap +='<th class="center">Total PO Amt</th>';
                     strap +='<th class="center">Invoice Total</th>';
                     strap +='<th class="center">Paid Amt</th>';
                     strap +='<th class="center">Payable Amt</th>';
                     strap +='<th class="right">Balance Amt</th>';
                     strap +=' </tr>';
                     strap +='</thead>';
                     strap +='<tbody>';
                     if(data.length > 0) {
                         var subtotal = 0; var discounts = 0; var tax_amt = 0; var total = 0;
                         $.each(data,function (i, item) {
                             if(item.po_id != item.po_id) {
                                 subtotal += parseFloat(item.subtotal);
                                 discounts += parseFloat(item.discounts);
                                 tax_amt += parseFloat(item.tax_amt);
                                 total += parseFloat(item.total);
                             }
                             strap += '<tr>';
                             strap += '<td>'+(i+1)+'</td>';
                             strap += '<td>'+item.exp_inv_no+'</td>';
                             strap += '<td>'+item.exp_doc_no+'</td>';
                             strap += '<td>'+item.subtotal+'</td>';
                             strap += '<td>'+item.discounts+'</td>';
                             strap += '<td>'+item.tax_amt+'</td>';

                             if(item.po_id!=0) {
                                 strap += '<td><input type="hidden" name="items[inv_amt][]" id="invoc_amt_'+(i+1)+'" value="'+item.expstot+'">'+item.total+'</td>';
                             }else {
                                 strap += '<td><input type="hidden" name="items[inv_amt][]" id="invoc_amt_'+(i+1)+'" value="'+item.total+'">0.00</td>';
                             }
                             strap += '<td>' + item.expstot + '</td>';
                             strap += '<td><input type="hidden" name="items[inv_paid][]" id="invoc_paid_'+(i+1)+'" value="'+item.payment_paid_amount+'">'+item.payment_paid_amount+'</td>';
                             strap += '<td><input type="number" name="items[pay_amount][]" id="pay_amt_'+(i+1)+'" data-id='+(i+1)+' class="form-control pay_amt"  style="width:100px" value="" ></td>';
                             strap += '<input type="hidden" name="items[exp_inv_no][]" id="expinv_'+(i+1)+'" data-id='+(i+1)+' class="form-control"  value="'+item.exp_inv_id+'" >';
                             strap += '<td><input type="number" name="items[bal_amount][]" id="bal_amt_'+(i+1)+'" data-id='+(i+1)+' class="form-control bal_amt"  value="'+((item.payment_pending_amount==0.00)?item.expstot:item.payment_pending_amount)+'" readonly="true"  style="width:100px"></td>';
                             strap +='</tr>';

                         });
                             strap +='</tbody>';
                             /*strap +='<tfoot>';
                             strap +='<tr id="tfoot" class="tfoot active">';
                             strap +='<th colspan="3" class="text-right">Total</th>';
                             strap += '<th class="text-left">'+subtotal.toFixed(2)+'</th>';
                             strap +='<th colspan="1">'+discounts.toFixed(2)+'</th>';
                             strap +='<th colspan="1">'+tax_amt.toFixed(2)+'</th>';
                             strap +='<th colspan="1">'+total.toFixed(2)+'</th>';
                             strap += '<th class="text-left">-</th>';
                             strap += '<th class="text-left">-</th>';
                             strap += '<th class="text-left">-</th>';
                             strap += '<th class="text-left">-</th>';
                             strap += '</tr></tfoot>';*/

                         $('.podisplayorder').html(strap);

                        /* strap +='<input type="hidden1" name="po_select_pur_order_id" id="pur_order_id" value='+ data[0].exp_inv_id+'>';

                         strap +=' <input type="hidden1" name="pendingamt" id="pendingamt" value='+ data[0].payment_pending_amount+'>';
                         strap +=' <input type="hidden1" name="paidamt" id="paidamt" value='+ data[0].payment_paid_amount+'>';
                         strap +=' <td class="text-right"><span id="balance" name="balance"  style="padding-right:50px;">'+data[0].payment_pending_amount+'</span></td>';
                         strap +=' <input type="hidden1" name="balanceamt" id="balanceamt" value="">';
*/

                         strap +='</table>';
                   }

                     $('.pay_amt').unbind('keyup');
                     $('.pay_amt').bind('keyup',function (){
                         dataid = $(this).attr('data-id');
                         calc_pay_total_amt (dataid);
                     });


                     // $("#content_area").LoadingOverlay("hide", true);
                 },
                 error: function (e) {
                     $("#content_area").LoadingOverlay("hide", true);
                     console.log(e); //alert("Something went wrong. Unable to retrive data!");
                 }
            });
         }
		
		 $('.pay_amt').unbind('keyup');
         $('.pay_amt').keyup(function(){
             dataid = $(this).attr('data-id');
             calc_total_amt (dataid);
         });

         function calc_pay_total_amt(id) {
             $('.pay_amt').each(function () {
                 var ino_val = $('#invoc_amt_'+id).val();
                 var pay_amt = $('#pay_amt_'+id).val();
                 if( ino_val!= '' && pay_amt!='') {
                     var bal_amt = $('#bal_amt_'+id).val();
                     if(bal_amt<0){
                         alert("Please check payable and balance amount");
                         $('#bal_amt_' + id).val(0.00);
                         $('#pay_amt_' + id).val(0);

                     }else {
                         var payamot = 0;
                         if($('#pay_amt_' + id).val()!='') {
                             payamot = $('#pay_amt_' + id).val();
                         } 
                         var totalamt = parseFloat($('#invoc_paid_' + id).val())+parseFloat(payamot);

                         var paidcal = parseFloat($('#invoc_amt_' + id).val())-totalamt;
                         $('#bal_amt_' + id).val(paidcal);
                     }
                 }
             });
         }

         function loadBank (property_id) {
             $.ajax({
                 type:"post",
                 async: true,
                 url: '<?php echo base_url('index.php/bms_fin_payment/getBanks');?>',
                 data: {'property_id':$('#property_id').val()},
                 datatype:"json", // others: xml, json; default is html
                 success: function(data) {
                   var str = '<option value="">Select</option>';
                     if(data.length > 0) {
                         $.each(data,function (i, item) {
                             str += '<option value="'+item.bank_id+'">'+item.bank_name+'</option>';
                         });
                     }
                     $('#bank_id').html(str);

                 },
                 error: function (e) {
                     console.log(e); //alert("Something went wrong. Unable to retrive data!");
                 }
             });
         }

         function calcuamt(){
             //alert("Calculation done");
             var grandtotal =  $("#grandtotal").val();
             var paidamt =  $("#paidamt").val();
             var pending =  $("#pendingamt").val();
             var curtotpay =  $("#curtot").val();

             var balanceamt = ((grandtotal-paidamt) - curtotpay);
             if(balanceamt<0){
                 alert("Your payable amount should be lesser than grand total amount.");
                 $('#balance').html(0.00);
                 $('#curtot').val(0);
                 $('#balanceamt').val(0);
             }  else {
                 $('#balance').html(balanceamt.toFixed(2));
                 $('#balanceamt').val(balanceamt);

             }
         }

     </script>