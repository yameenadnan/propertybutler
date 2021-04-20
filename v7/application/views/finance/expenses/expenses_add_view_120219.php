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
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">
        <!-- general form elements -->
        <div class="box box-primary">
            <?php
 
                if (isset($_SESSION['flash_msg']) && trim($_SESSION['flash_msg']) != '') {
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>' . $_SESSION['flash_msg'] . '</div>';
                    unset($_SESSION['flash_msg']);
                }
				
				$poitem = array();
            ?>
            <div class="box-body">
                <div class="col-md-12 col-sm-12 col-xs-12" style="border: 1px solid #999;border-radius: 2px;">
                    <div class="row" style="background-color: #d2cece; height: 50px;" >
                        <h3 style="margin-top: 10px;margin-left: 5px;"><?php echo !empty($poitem['exp_inv_id']) ? 'Update Invoice ('.$poitem['exp_inv_no'].')' : 'New Invoice';?> </h3>
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
                                            $selected = isset($property_id) && $property_id == $val['property_id'] ?  'selected="selected" ' : '';
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
                                            $selected = isset($poitem['service_provider_id']) && $poitem['service_provider_id'] == $val['service_provider_id'] ?  'selected="selected" ' : '';
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
                                    <select class="form-control select2" id="po_number"
                                            name="po_number">
                                        <option value="">Select</option>
                                        <?php
										if(isset($invnum)){
                                        foreach ($invnum as $key => $val) {
                                            $selected = $poitem['po_id'] == $val['exp_inv_id'] ? 'selected="selected"' : '';
                                            echo "<option value='" . $val['exp_inv_id'] . "'
                                                              " . $selected . ">" . $val['exp_inv_no']. "</option>";
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
                                    <input type="text" name="vendor_inv_no" id="vendor_inv_no" value="<?php echo isset($poitem['exp_inv_no'])?$poitem['exp_inv_no']:'';?>" required="required" class="form-control">
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
                            if(isset($poitem['po_id'])){
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

                                        <input type="hidden" name="po_select_pur_order_id" id="pur_order_id" value="<?php echo $pursubitem[0]['po_no']; ?>">
                                        <input type="hidden" name="po_select_po_no" id="po_id" value="<?php echo $pursubitem[0]['po_id']; ?>">

                                        <tr>
                                            <td class="text-right">  <strong>Paid</strong> </td>
                                            <td class="text-right"><?php echo $pursubitem[0]['invoice_paid_amount']; ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"> <strong>Current Total Payable </strong> </td>
                                            <td class="text-right"><input  class="form-control curtot" style="width:100px"  type="number" value="<?php echo $poitem['total'];?>" name="curtot" onchange="calcuamt();" id="curtot">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"> <strong>Balance </strong></td>
                                            <input type="hidden" name="pendingamt" id="pendingamt" value="<?php echo ($pursubitem[0]['invoice_pending_amount']+$poitem['total']); ?>">
                                            <input type="hidden" name="paidamt" id="paidamt" value="<?php echo $pursubitem[0]['invoice_paid_amount']; ?>">
                                            <td class="right"><span id="balance" name="balance"><?php echo $pursubitem[0]['invoice_pending_amount']; ?> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                                            <input type="hidden" name="balanceamt" id="balanceamt" value="<?php echo ($pursubitem[0]['invoice_pending_amount']+$poitem['total']); ?>">
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END PO DISPLAY -->
                        <?php } else {
								$po_sub_items = array();
							?>
                            <!-- WITHOUT PO ORDER DISPLAY -->
                            <div class="col-md-12 no-padding" id="withoutPO">
                                <div class="btndisp">
                                    <div class="col-md-2 no-padding">
                                        <h2 style="color: #afa8a8;">Items</h2>
                                    </div>
                                    <div class="col-md-10" style="margin-top: 25px;">
                                        <button type="button" class="btn  btn-primary add_sub_exp_btn pull-right" id="exp_0" value="0" data-value="0" data-id="<?php echo (count($po_sub_items)? (count($po_sub_items)+1):2);?>">Add Item</button>
                                    </div>
                                </div>
                                <div class="col-md-12 items_container add_more_item no-padding">
                                    <div class="col-md-12 item_div_1"  style="background-color: #ECF0F5; margin: 10px 0 5px 0; padding: 10px 0 !important; border: 1px solid #999; border-radius: 5px;">
                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" required=""  rows="5" placeholder="Enter Description"><?php echo isset($po_sub_items[0]['description']) && $po_sub_items[0]['description'] != '' ? $po_sub_items[0]['description'] : '';?></textarea>
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
                                                                $selected = isset( $po_sub_items[0]['asset_id']) && trim( $po_sub_items[0]['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
                                                                echo "<option value='" . $val['asset_id'] . "' " . $selected . ">" . $val['asset_name'] . "-" . $val['asset_location'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Category </label>
                                                        <select class="form-control select2 cate" name="items[category][]" data-id="1" id="cate_1">
                                                            <option value="">Select</option>
                                                            <?php
                                                            $group_name = "";
                                                            foreach ($expense_items as $key => $val) :
                                                                $selected = isset( $po_sub_items[0]['cat_id']) && trim( $po_sub_items[0]['cat_id']) == $val['charge_code_category_id'] ? 'selected="selected" ' : '';
                                                                if ($val['charge_code_group_name'] != $group_name) {
                                                                    if ($endLabel) {
                                                                        echo '</optgroup>';
                                                                    }
                                                                    echo '<optgroup label="' . $val['charge_code_group_name'] . '">';
                                                                    $group_name = $val['charge_code_group_name'];
                                                                    $endLabel = true;
                                                                }
                                                                echo "<option value='" . $val['charge_code_category_id'] . "' " . $selected . ">" . $val['charge_code_category_name'] . "</option>";
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Sub Category</label>
                                                        <select  class="form-control select2 subcate" name="items[subcategory][]" id="subcate_1" data-id="1">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($poitem['exp_inv_no']!=''){
                                                                foreach ($po_sub_items[0]['sub_cat_dd'] as $key => $val) {
                                                                    $selected = isset($po_sub_items[0]['sub_cat_id']) && trim($po_sub_items[0]['sub_cat_id']) == $val['charge_code_sub_category_id'] ? 'selected="selected" ' : '';
                                                                    echo "<option value='" . $val['charge_code_sub_category_id'] . "'
                                                                      " . $selected . ">" . $val['charge_code_sub_category_name']. "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										<input type="hidden" name="items[exp_item_id][]"  id="exp_item_id_1" value="<?php echo isset($po_sub_items[0]['exp_item_id'])?$po_sub_items[0]['exp_item_id']:''; ?>"> 
                                        <div class="col-md-12 no-padding">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Quantity </label> <input class="form-control amt_quan" name="items[quantity][]" value="<?php echo isset($po_sub_items[0]['qty'])?$po_sub_items[0]['qty']:'';?>" data-id="1" id="subquantity_1" type="number" min="0" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>UOM</label> <input class="form-control uom" name="items[uom][]"value="<?php echo isset($po_sub_items[0]['uom'])?$po_sub_items[0]['uom']:'';?>"  data-id="1" id="uom_1" type="text" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Unit Price</label> <input class="form-control amt_unit"  data-id="1" id="unitprice_1" name="items[subunitprice][]" value="<?php echo isset($po_sub_items[0]['unit_price'])?$po_sub_items[0]['unit_price']:'';?>"  type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input readonly class="form-control amt_cal" value="<?php echo isset($po_sub_items[0]['amount'])?$po_sub_items[0]['amount']:'';?>" name="items[amount][]" data-id="1" id="subamount_1" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Discount Amt</label> <input class="form-control amt_disct" value="<?php echo isset($po_sub_items[0]['discount_amt'])?$po_sub_items[0]['discount_amt']:'';?>"  name="items[distamount][]"  data-id="1" id="subdistamount_1" type="number" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Item Net Amount</label>
                                                    <input readonly class="form-control amt_net" value="<?php echo isset($po_sub_items[0]['net_amount'])?$po_sub_items[0]['net_amount']:'';?>"  name="items[netamount][]" data-id="1" id="subnetamount_1" type="number" />
                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    <div class="col-md-12 col-xs-12 no-padding">
                                        <?php
                                            $sub_exp_cnt = 0;
                                            $total_count = count($po_sub_items);
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
                                                                    <textarea name="items[description][]" class="form-control" style="-webkit-border-radius: 4px !important; border-radius: 4px !important;" rows="5" placeholder="Enter Description"><?php echo isset($valslice['description']) && $valslice['description'] != '' ? $valslice['description'] : ''; ?></textarea>
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
                                                                                    $selected = isset($valslice['asset_id']) && trim($valslice['asset_id']) == $val['asset_id'] ? 'selected="selected" ' : '';
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
                                                            <div class="col-md-6">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Category </label>
                                                                        <select class="form-control select2 cate" name="items[category][]" data-id="<?php echo $dataid_incre; ?>" id="cate_<?php echo $dataid_incre; ?>">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            $group_name = "";
                                                                            foreach ($expense_items as $key => $val) :
                                                                                $selected = isset($valslice['cat_id']) && trim($valslice['cat_id']) == $val['charge_code_category_id'] ? 'selected="selected" ' : '';
                                                                                if ($val['charge_code_group_name'] != $group_name) {
                                                                                    if ($endLabel) {
                                                                                        echo '</optgroup>';
                                                                                    }
                                                                                    // echo label...
                                                                                    echo '<optgroup label="' . $val['charge_code_group_name'] . '">';
                                                                                    $group_name = $val['charge_code_group_name'];
                                                                                    $endLabel = true;
                                                                                }
                                                                                // $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ? 'selected="selected" ' : '';
                                                                                echo "<option value='" . $val['charge_code_category_id'] . "'
                                                                      " . $selected . ">" . $val['charge_code_category_name'] . "</option>";
                                                                            endforeach;
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Sub Category</label>
                                                                        <select class="form-control select2 subcate" name="items[subcategory][]" id="subcate_<?php echo $dataid_incre; ?>" data-id="<?php echo $dataid_incre; ?>">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            if ($poitem['exp_inv_no'] != '') {
                                                                                foreach ($valslice['sub_cat_dd'] as $key => $val) {
                                                                                    $selected = isset($valslice['sub_cat_id']) && trim($valslice['sub_cat_id']) == $val['charge_code_sub_category_id'] ? 'selected="selected" ' : '';
                                                                                    echo "<option value='" . $val['charge_code_sub_category_id'] . "' " . $selected . ">" . $val['charge_code_sub_category_name'] . "</option>";
                                                                                }
                                                                            }
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
                                                        <input type="hidden1" name="items[exp_item_id][]" id="exp_item_id_<?php echo $dataid_incre; ?>" value="<?php echo $valslice['exp_item_id']; ?>">
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
                                        <input type="number" class="form-control resultsubtotal" name="rstsubtot" id="rstsubtot"  value="<?php echo (isset($poitem['subtotal'])?$poitem['subtotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Discount Amt</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="form-control maindiscount" name="maindiscount" id="maindiscount" value="<?php echo (isset($poitem['discounts'])?$poitem['discounts']:0);?>"style="text-align: right;" >
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Invoice Net Amount</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="form-control mainntat" name="mainntat" id="mainntat"  value="<?php echo (isset($poitem['nettotal'])?$poitem['nettotal']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Tax(%)</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="taxpers form-control" name="taxpers" value="<?php echo (isset($poitem['tax_percent'])?$poitem['tax_percent']:0);?>" style="text-align: right;" id="taxpers">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Tax Amt</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="taxamt form-control" name="taxamt" id="taxamt" value="<?php echo (isset($poitem['tax_amt'])?$poitem['tax_amt']:0);?>" style="text-align: right;" readonly="true">
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 5px !important;text-align: right;">
                                    <div class="col-md-8 col-xs-6">&nbsp;</div>
                                    <div class="col-md-2 col-xs-12" style="padding-top: 5px !important;text-align: right;">
                                        <label>Invoice Total</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12 ">
                                        <input type="number" class="maintotat form-control" name="maintotat" id="maintotat" value="<?php echo (isset($poitem['total'])?$poitem['total']:0);?>" style="text-align: right;" readonly="true">
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
	$this->load->view('footer'); 
	require_once("expenses_add_script.php");
?>
  