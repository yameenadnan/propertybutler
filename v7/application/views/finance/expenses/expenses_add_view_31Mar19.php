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
                        <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($poitem['exp_inv_id']) ? 'Update Invoice ('.$poitem['exp_inv_no'].')' : 'New Expense Invoice';?> </h3>
                    </div>
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_fin_expenses/add_expenses_order');?>"  method="post" enctype="multipart/form-data">
                        <!-- Hidden fields -->
                        <input type="hidden" name="poitem[expInvId]" value="<?php echo !empty($poitem['exp_inv_id']) ? $poitem['exp_inv_id'] : '';?>" />
                        <input type="hidden" name="poitem[exp_inv_no]" value="<?php echo !empty($poitem['exp_inv_no']) ? $poitem['exp_inv_no'] : '';?>" />
                        <input type="hidden" name="poitem[exp_doc_no]" value="<?php echo !empty($poitem['exp_doc_no']) ? $poitem['exp_doc_no'] : '';?>" />
                        <input type="hidden" id="prop_abbr" name="prop_abbr" value="" />
                        <input type="hidden" id="po_id" name="po_id" value="<?php echo $_SESSION['bms_default_property'];?>" />
                        <!-- New Invoice Block Start -->
                        <div class="row" style="padding-top: 15px;padding-bottom:15px;">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-md-2">
                                    <label>Property Name *</label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="property_id" name="property_id" disabled="disabled">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($properties as $key=>$val) {
                                            $selected = !empty($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' data-prop-abbr='".$val['property_abbrev']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Invoice Date *</label>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control pull-right datepicker"
                                               name="po_date" type="text" value="<?php echo !empty($poitem['exp_date']) ? date('d-m-Y',strtotime($poitem['exp_date'])) : date("d-m-Y"); ?>" />
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                                <div class="col-md-2">
                                    <label>Service Provider *</label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control select2" id="service_provider" name="service_provider">
                                        <option value="">Select</option>
                                        <?php
                                        foreach ($service_provider as $key => $val) {
                                            $selected = !empty($poitem['service_provider_id']) && $poitem['service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
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
                                    <label>PO Number</label>
                                </div>

                                <div class="col-md-4">
                                    <select class="form-control select2" id="po_number" name="po_number">
                                        <option value="">Select</option>
                                        <?php
										if(!empty($ponumb)){
                                        foreach ($ponumb as $key => $val) {
                                            $selected = $poitem['po_id'] == $val['pur_order_id'] ? 'selected="selected"' : '';
                                            echo "<option value='" . $val['pur_order_id'] . "'" . $selected . ">" . $val['po_no']. "</option>";
                                        }
										}
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 15px !important;">
                                <div class="col-md-2">
                                    <label>Vendor Invoice No* </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="vendor_inv_no" id="vendor_inv_no" value="<?php echo !empty($poitem['exp_inv_no'])?$poitem['exp_inv_no']:'';?>" required="required" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- End New Invoice Block Start -->

                        <!-- /ITEM CONTAINER EDIT WITH PO SELECTION START -->
                        <div class="col-md-12 no-padding podetails" style="display: none">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Purchase Order summary</strong></h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive-sm">
                                        <div class="podisplayorder"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-sm-5 ml-auto"></div>
                                <div class="col-lg-4 col-sm-5 ml-auto">
                                    <div class="podisplaytotals"></div>
                                </div>
                            </div>
                        </div>
                        <!-- END ITEM CONTAINER EDIT WITH PO SELECTION START -->
                        <?php
							//echo "POID " .$poitem['po_id'];
                            if(!empty($poitem['po_id'])){
                        ?>
                            <div class="po_available">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><strong>Purchase Order summary</strong></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive-sm">
                                            <div class="podisplayorder1">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="center">#</th>
                                                            <th>Description</th>
                                                            <th class="center">Qty</th>
                                                            <th class="right">UOM</th>
                                                            <th class="center">Unit Price</th>
                                                            <th class="center">Price</th>
                                                            <th class="center">Discount</th>
                                                            <th class="right">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            foreach ($pursubitem as $key => $value) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo ++$key;?></td>
                                                            <td><?php echo $value['description'];?></td>
                                                            <td><?php echo $value['qty'];?></td>
                                                            <td><?php echo $value['uom'];?></td>
                                                            <td><?php echo $value['unit_price'];?></td>
                                                            <td><?php echo $value['amount'];?></td>
                                                            <td><?php echo $value['discount_amt'];?></td>
                                                            <td><?php echo $value['net_amount'];?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-sm-4"></div>
                                <div class="col-lg-4 col-sm-4">
                                    <table class="table table-clear">
                                        <tbody>
                                        <tr>
                                            <td class="text-right"> <strong>Total Amount </strong> </td>
                                            <td class="text-right"><?php echo $pursubitem[0]['subtotal']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Discount </strong></td>
                                            <td class="text-right"><?php echo $pursubitem[0]['discounts']; ?>  &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><strong>Tax(<?php echo ($pursubitem[0]['tax_percent']? $pursubitem[0]['tax_percent'] : 0); ?>) (RM)</strong></td>
                                            <td class="text-right"><?php echo $pursubitem[0]['tax_amt']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"> <strong>Grand Total</strong></td>
                                            <td class="text-right"><?php echo $pursubitem[0]['total']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <input type="hidden" name="grandtotal" id="grandtotal" value="<?php echo $pursubitem[0]['total']; ?>">
                                        </tr>

                                        <input type="hidden" name="po_select_pur_order_id" id="pur_order_id" value="<?php echo (!empty($pursubitem[0]['po_no'])?$pursubitem[0]['po_no']:'') ?>">
                                        <input type="hidden" name="po_select_po_no" id="po_id" value="<?php echo $pursubitem[0]['po_id']; ?>">

                                        <tr>
                                            <td class="text-right">  <strong>Paid</strong> </td>
                                            <td class="text-right"><?php echo !empty($pursubitem[0]['invoice_paid_amount'])?$pursubitem[0]['invoice_paid_amount'] : $pursubitem[0]['payment_paid_amount']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"> <strong>Current Total Payable </strong> </td>
                                            <td class="text-right"><input  class="form-control curtot text-right" style="width:100px"  type="number" value="<?php echo $poitem['total'];?>" name="curtot" onchange="calcuamt();" id="curtot">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"> <strong>Balance </strong></td>
											<?php
												if(!empty($pursubitem[0]['invoice_pending_amount'])) {
													$pendamt = $pursubitem[0]['invoice_pending_amount']+$poitem['total'];
												}else {
													$pendamt = $pursubitem[0]['payment_pending_amount']+$poitem['total'];
												}
											?>
                                            <input type="hidden" name="pendingamt" id="pendingamt" value="<?php echo $pendamt; ?>">
                                            <input type="hidden" name="paidamt" id="paidamt" value="<?php echo !empty($pursubitem[0]['invoice_paid_amount'])?$pursubitem[0]['invoice_paid_amount']:$pursubitem[0]['payment_paid_amount']; ?>">
                                            <td class="text-right"><span id="balance" name="balance"><?php echo !empty($pursubitem[0]['invoice_pending_amount'])?$pursubitem[0]['invoice_pending_amount']: $pursubitem[0]['payment_pending_amount']; ?> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                            <input type="hidden" name="balanceamt" id="balanceamt" value="<?php echo $pendamt; ?>">
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PO DISPLAY -->
                        <?php } else {
							?>
                            <!-- WITHOUT PO ORDER DISPLAY -->
                            <div class="col-md-12 no-padding" id="withoutPO">
                                <div class="btndisp">
                                    <div class="col-md-2 no-padding">
                                        <h2 style="color: #afa8a8;">Items</h2>
                                    </div>
                                    <div class="col-md-10" style="margin-top: 25px;">
									<?php
										$btncount = 2;
										if(!empty($po_sub_items)){
											$btncount = count($po_sub_items)+1;
										} 
									?>
                                        <button type="button" class="btn  btn-primary add_sub_exp_btn pull-right" id="exp_0" value="0" data-value="0" data-id="<?php echo $btncount;?>">Add Item</button>
                                    </div>
                                </div>
                                <div class="col-md-12 items_container add_more_item no-padding">
                                    <div class="col-md-12 item_div_1"  style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" required=""  rows="5" placeholder="Enter Description"><?php echo !empty($po_sub_items[0]['description']) && $po_sub_items[0]['description'] != '' ? $po_sub_items[0]['description'] : '';?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>For Asset </label>
                                                        <select class="form-control select2 assetlst" name="items[assetlst][]">
                                                            <option value="">Select</option>
                                                            <?php
                                                            foreach ($property_assets as $key => $val) {
                                                                $selected = !empty( $po_sub_items[0]['asset_id']) && trim( $po_sub_items[0]['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                                echo "<option value='" . $val['asset_id'] . "' " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Account Name </label>
                                                        <select class="form-control select2 cate" name="items[category][]" data-id="1" id="cate_1">
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
										<input type="hidden" name="items[exp_item_id][]"  id="exp_item_id_1" value="<?php echo !empty($po_sub_items[0]['exp_item_id'])?$po_sub_items[0]['exp_item_id']:''; ?>"> 
                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Quantity </label> <input class="form-control amt_quan" name="items[quantity][]" value="<?php echo !empty($po_sub_items[0]['qty'])?$po_sub_items[0]['qty']:'';?>" data-id="1" id="subquantity_1" type="number" min="0" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>UOM</label> <input class="form-control uom" name="items[uom][]"value="<?php echo !empty($po_sub_items[0]['uom'])?$po_sub_items[0]['uom']:'';?>"  data-id="1" id="uom_1" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Unit Price</label> <input class="form-control amt_unit"  data-id="1" id="unitprice_1" name="items[subunitprice][]" value="<?php echo !empty($po_sub_items[0]['unit_price'])?$po_sub_items[0]['unit_price']:'';?>"  type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input readonly class="form-control amt_cal" value="<?php echo !empty($po_sub_items[0]['amount'])?$po_sub_items[0]['amount']:'';?>" name="items[amount][]" data-id="1" id="subamount_1" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Discount Amt</label> <input class="form-control amt_disct" value="<?php echo !empty($po_sub_items[0]['discount_amt'])?$po_sub_items[0]['discount_amt']:'';?>"  name="items[distamount][]"  data-id="1" id="subdistamount_1" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Item Net Amount</label>
                                                    <input readonly class="form-control amt_net" value="<?php echo !empty($po_sub_items[0]['net_amount'])?$po_sub_items[0]['net_amount']:'';?>"  name="items[netamount][]" data-id="1" id="subnetamount_1" type="number" />
                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <?php
                                            $sub_exp_cnt = 0;$total_count =0;
											if(!empty($po_sub_items)){
												$total_count = count($po_sub_items);
											}
                                            if(!empty($poitem['exp_inv_no']) && ($total_count>1)) { // SUB Routine Task
                                                $array_slice = array_slice($po_sub_items, 1);
                                                foreach ($array_slice as $keyslice => $valslice) {
                                                    $dataid_incre = $keyslice + 2;
                                                    ?>
                                                    <div class="col-md-12 item_div_<?php echo ($dataid_incre); ?>" style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                                        <div class="col-md-12 no-padding">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo !empty($valslice['description']) && $valslice['description'] != '' ? $valslice['description'] : ''; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>For Asset </label>
                                                                        <select class="form-control select2 assetlst"  name="items[assetlst][]">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                                foreach ($property_assets as $key => $val) {
                                                                                    $selected = !empty($valslice['asset_id']) && trim($valslice['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                                                    echo "<option value='" . $val['asset_id'] . "' " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 text-right">
                                                                <button type="button"  class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="<?php echo $dataid_incre; ?>" data-id="<?php echo $dataid_incre;?>">
                                                                    <i class="fa fa-close"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Account Name </label>
                                                                        <select class="form-control select2 cate" name="items[category][]" data-id="<?php echo $dataid_incre; ?>" id="cate_<?php echo $dataid_incre; ?>">
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
                                                                    <label>Quantity </label>
                                                                    <input class="form-control amt_quan"
                                                                           name="items[quantity][]"
                                                                           value="<?php echo $valslice['qty']; ?>"
                                                                           data-id="<?php echo $dataid_incre; ?>"
                                                                           id="subquantity_<?php echo $dataid_incre; ?>"
                                                                           type="number" min="0"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>UOM</label> <input class="form-control uom"
                                                                                              name="items[uom][]"
                                                                                              value="<?php echo $valslice['uom']; ?>"
                                                                                              data-id="<?php echo $dataid_incre; ?>"
                                                                                              id="uom_<?php echo $dataid_incre; ?>"
                                                                                              type="text"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Unit Price</label>
                                                                    <input class="form-control amt_unit"
                                                                           data-id="<?php echo $dataid_incre; ?>"
                                                                           id="unitprice_<?php echo $dataid_incre; ?>"
                                                                           name="items[subunitprice][]"
                                                                           value="<?php echo $valslice['unit_price']; ?>"
                                                                           type="number"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Amount</label>
                                                                    <input readonly class="form-control amt_cal"
                                                                           value="<?php echo $valslice['amount']; ?>"
                                                                           name="items[amount][]"
                                                                           data-id="<?php echo $dataid_incre; ?>"
                                                                           id="subamount_<?php echo $dataid_incre; ?>"
                                                                           type="number"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Discount Amt</label>
                                                                    <input class="form-control amt_disct"
                                                                           value="<?php echo $valslice['discount_amt']; ?>"
                                                                           name="items[distamount][]"
                                                                           data-id="<?php echo $dataid_incre; ?>"
                                                                           id="subdistamount_<?php echo $dataid_incre; ?>"
                                                                           type="number"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Item Net Amount</label>
                                                                    <input readonly class="form-control amt_net"
                                                                           value="<?php echo $valslice['net_amount']; ?>"
                                                                           name="items[netamount][]"
                                                                           data-id="<?php echo $dataid_incre; ?>"
                                                                           id="subnetamount_<?php echo $dataid_incre; ?>"
                                                                           type="number"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="items[exp_item_id][]" id="exp_item_id_<?php echo $dataid_incre; ?>" value="<?php echo $valslice['exp_item_id']; ?>">
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 no-padding sub_exp_container_0"></div>
                                <div class="col-md-12 items_container col-xs-6" style="margin-top: 15px;">
                                    <div class="col-md-12 no-padding style="padding-top: 5px !important;text-align: right;"">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Invoice Sub Total</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="form-control resultsubtotal" name="rstsubtot" id="rstsubtot"  value="<?php echo (!empty($poitem['subtotal'])?$poitem['subtotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Discount Amt</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="form-control maindiscount" name="maindiscount" id="maindiscount" value="<?php echo (!empty($poitem['discounts'])?$poitem['discounts']:0);?>"style="text-align: right;" >
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Invoice Net Amount</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="form-control mainntat" name="mainntat" id="mainntat"  value="<?php echo (!empty($poitem['nettotal'])?$poitem['nettotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Tax(%)</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="taxpers form-control" name="taxpers" value="<?php echo (!empty($poitem['tax_percent'])?$poitem['tax_percent']:0);?>" style="text-align: right;" id="taxpers">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Tax Amt</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="taxamt form-control" name="taxamt" id="taxamt" value="<?php echo (!empty($poitem['tax_amt'])?$poitem['tax_amt']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Invoice Total</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="maintotat form-control" name="maintotat" id="maintotat" value="<?php echo (!empty($poitem['total'])?$poitem['total']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                </div>
                            </div>
                  <!-- END WITHOUT PO ORDER DISPLAY -->
                    <?php } ?>
                    <div class="col-md-12 no-padding" style="padding: 10px 0 !important;" id="remarkview">
                            <div class="col-md-2 col-xs-6">
                                <h3> <b>Remarks</b> </h3>
                            </div>
                            <div class="col-md-6 col-xs-12" >
                                <textarea rows="4" name="remarks" class="form-control" cols="50"><?php echo !empty($poitem['remarks'])? $poitem['remarks'] : '';?></textarea>
                            </div>
                            <div class="col-md-8">
                                <div class="box-footer text-right" style="border-top: 0px solid #f4f4f4;">
                                    <button type="submit" class="btn btn-primary submit_btn"
                                            name="action" value="save_only">Submit</button>
                                    &ensp;
                                    <!--button type="submit" class="btn btn-primary submit_btn" name="action" value="save_print">Submit & Print</button> &ensp;-->
                                    <button type="Reset" class="btn btn-default reset_btn">Reset</button>
                                    &ensp;&ensp;
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


    $('.cate').change(function () {
        
        var subcate = "subcate_"+$(this).attr('data-id');
        category_changes($(this).val(),subcate);
    });


    var exp_cnt = 0;
    var sub_exp_cnt = eval("<?php echo ($sub_exp_cnt!='')?$sub_exp_cnt:0;?>");
    $('.add_sub_exp_btn').click(function () {
		
		 
        var id = $(this).attr('id');
        var val = $(this).val();
        var parent_id =$(this).attr('data-value')
        var dataid = $(this).attr('data-id')

        var str = '<div class="col-md-12 item_div_1 sub_exp_'+id+' sub_exp_'+sub_exp_cnt+'"  style="background-color:#ECF0F5;margin: 10px 0 5px 0;padding:10px 0 !important;border: 1px solid #999;border-radius: 5px;">';
        str+= '<div class="col-md-12">';
        str += '</div>';
        str += '<div class="col-md-12 no-padding">';
        str +='<div class="col-md-6">';
        str +='<div class="form-group">';
        str +='<label >Description</label>';
        str +='<textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important;border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo !empty($service_provider['job_scope']) && $service_provider['job_scope'] != '' ? $service_provider['job_scope'] : '';?></textarea>';
        str +='</div>';
        str +='</div>';
        str += '<div class="col-md-4">';
        str  +='<div class="col-md-12">';
        str  +='<div class="form-group" >';
        str  +='<label >For Asset </label>';
        str  +='<select class="form-control select2 assetlst" size="3" name="items[assetlst][]">';
        str  +='<option value="">Select</option>';
       str  +='<?php
				if(count($property_assets)){
                 foreach ($property_assets as $key => $val) {
				  echo "<option value=" . $val['asset_id'] . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                 }
				}
                 ?>';
        str  +='</select>';
        str  +='</div>';
        str  +='</div>';
        str  +='</div>';
        str +='<div class="col-md-2 text-right">';
        str += '<button type="button" class="btn btn-danger btn-sm delete_sub_btn pull-right" data-value="'+(sub_exp_cnt++)+'"  data-id="'+dataid+'" data-subexpid=""><i class="fa fa-close"></i></button>';
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
        str += '<input type="hidden" id="exp_item_id_'+dataid+'" name="items[exp_item_id][]" value=0>';
        str +=  '</div>';
        str +=  '</div>';
        str +=  '</div>';

        str += '</div>';
        str += '</div>';

        str += '</div>';


        $('.sub_exp_container_'+parent_id).append(str);
        $(this).attr('data-id',eval(dataid)+1);

        $('#'+id).val(val);

        $('.cate').unbind('change');
        $('.assetlst').unbind('change');

        $('.amt_quan, .amt_unit, .amt_disct').unbind('keyup');
        $('.amt_quan, .amt_unit, .amt_disct').bind('keyup',function (){
            dataid = $(this).attr('data-id');
            calc_total_amt (dataid);
        });

        $('.delete_sub_btn').unbind("click");
        $('.delete_sub_btn').bind ("click",function () {
            delete_sub_exp ($(this).attr('data-value'),$(this).attr("value"),$(this).attr('data-id') );
        });
        $('.select2').select2();

    });


    function delete_sub_exp (div_cls_id, sub_exp_id, data_id) {
        var poItemId = $('#exp_item_id_'+data_id).val();
        if(poItemId<=0){ poItemId = 0;}
        if(poItemId<=0) {
            $('.sub_exp_'+div_cls_id).remove();
            getcountofmaintot();
            calc_main_discount();
            calc_tax_percent();
        } else {
            if(confirm ("You cannot undo this action. Are you sure want to Delete?")) {
                $.ajax({
                    type:"post",
                    async: true,
                    url: '<?php echo base_url('index.php/bms_fin_expenses/delete_expenses_item');?>/', // Reusing the same function from task creation
                    data: {'sub_exp_id':poItemId},
                    datatype:"html", // others: xml, json; default is html
                    beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                    success: function(data) {
                        $('.item_div_'+data_id).remove();
                        $("#content_area").LoadingOverlay("hide", true);
                        getcountofmaintot();
                        calc_main_discount();
                        calc_tax_percent();
                    },
                    error: function (e) {
                        $("#content_area").LoadingOverlay("hide", true);
                        console.log(e); //alert("Something went wrong. Unable to retrive data!");
                    }
                 });
            }
        }


    }
    $('.delete_sub_btn').bind ("click",function () {
        
        delete_sub_exp ($(this).attr('data-value'),$(this).attr("data-subexpid"),$(this).attr('data-id'));
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

    function getcountofmaintot(){
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

    $(function () {
//Date picker
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            'minDate': new Date()
        });

    });

    // Load block and assign to drop down
    function category_changes(cateid, subid) {
        
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getSubCategoryList');?>',
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


    if(typeof($('#property_id').find('option:selected').data('prop-abbr')) != 'undefined') {
        $('#prop_abbr').val($('#property_id').find('option:selected').data('prop-abbr'));
    }

    $('#service_provider').change(function () {
        showndhide();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getPurchaseOrderNumber');?>',
            data: {'servprov_id':$(this).val()},
            datatype:"json", // others: xml, json; default is html

            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var str = '<option value="">Select</option>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.pur_order_id+'">'+item.po_no+'</option>';
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
            $('.po_available').hide();
            $('#withoutPO').hide();
            $('#remarkview').show();
            getpodetails(ponumber)
        }else {
            $('.add_more_item ').show();
            $('.btndisp').show();
            $('.podetails').hide();
            $('#withoutPO').show();
        }
    }
    function getpodetails(po_id){
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_fin_expenses/getExpensesOrderDetails');?>',
            data: {'po_id':po_id},
            datatype:"json", // others: xml, json; default is html
            // beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                var strap = '<table class="table table-striped table-bordered">';
                strap +='<thead>';
                strap +='<tr>';
                strap +='<th class="center">#</th>';
                strap +='<th>Item</th>';
                strap +='<th>Description</th>';
                strap +='<th class="center">Qty</th>';
                strap +='<th class="right">UOM</th>';
                strap +='<th class="center">Unit Price</th>';
                strap +='<th class="center">Price</th>';
                strap +='<th class="center">Discount</th>';
                strap +='<th class="right">Amount</th>';
                strap +=' </tr>';
                strap +='</thead>';
                strap +='<tbody>';
                if(data.length > 0) {
                    $.each(data,function (i, item) {
                        var subtotal = item.subtotal;
                        strap += '<tr>';
                        strap += '<td>'+(i+1)+'</td>';
                        strap += '<td>'+item.charge_code_category_name+'</td>';
                        strap += '<td>'+item.description+'</td>';
                        strap += '<td>'+item.qty+'</td>';
                        strap += '<td>'+item.uom+'</td>';
                        strap += '<td>'+item.unit_price+'</td>';
                        strap += '<td>'+item.amount+'</td>';

                        strap += '<td>'+item.discount_amt+'</td>';
                        strap += '<td>'+item.net_amount+'</td>';
                        strap+="</tr>";
                    });
                    strap +='</tbody>';
                    strap +='</table>';
                    $('.podisplayorder').html(strap);

                    var strsub = '<table class="table table-clear">';
                    strsub +=' <tbody>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Total Amount</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].subtotal+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Discount</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].discounts+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Tax('+data[0].tax_percent+'%)</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].tax_amt+'</td>';
                    strsub +='</tr>';
                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Grand Total</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right">'+data[0].total+'</td>';
                    strsub +=' <input type="hidden" name="grandtotal" id="grandtotal" value='+ data[0].total+'>';
                    strsub +='</tr>';

                    strsub +='<input type="hidden" name="po_select_pur_order_id" id="pur_order_id" value='+ data[0].pur_order_id+'>';
                    strsub +='<input type="hidden" name="po_select_po_no" id="po_id" value='+ data[0].po_id+'>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Paid</strong>';
                    strsub +=' </td>';
                    strsub +=' <td>'+data[0].invoice_paid_amount+'</td>';
                    strsub +='</tr>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Current Total Payable</strong>';
                    strsub +=' </td>';
                    strsub +=' <td class="right"><input  class="form-control curtot" style="width:100px"  type="number" name="curtot" onchange="calcuamt();" id="curtot"></td>';
                    strsub +='</tr>';

                    strsub +='<tr>';
                    strsub +=' <td class="left">';
                    strsub +=' <strong>Balance</strong>';
                    strsub +=' </td>';
                    strsub +=' <input type="hidden" name="pendingamt" id="pendingamt" value='+ data[0].invoice_pending_amount+'>';
                    strsub +=' <input type="hidden" name="paidamt" id="paidamt" value='+ data[0].invoice_paid_amount+'>';
                    strsub +=' <td class="right"><span id="balance" name="balance">'+data[0].invoice_pending_amount+'</span></td>';
                    strsub +=' <input type="hidden" name="balanceamt" id="balanceamt" value="">';
                    strsub +='</tr>';
                    strsub +='</tbody>';
                    strsub +=' </table>';
                    $('.podisplaytotals').html(strsub);

                }

                // $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }

    function calcuamt(){
        //alert("Calculation done");
        var grandtotal =  $("#grandtotal").val() 
        var paidamt =  $("#paidamt").val();
        var pending =  $("#pendingamt").val(); 
        var curtotpay =  $("#curtot").val();
    
        var balanceamt = ((grandtotal-paidamt) - (curtotpay));
        var getbalamts = Math.round(balanceamt);
        if(getbalamts<0){
            alert("Your payable amount should be lesser than grand total amount.");
            $('#balance').html(pending);
            $("#curtot").val(0.00);
        }  else {
            $('#balance').html(balanceamt.toFixed(2));
            $('#balanceamt').val(balanceamt);
        }
    }

</script>

  