<div class="col-md-12 no-padding" >
    <div class="col-md-3 no-padding">
        <div class="col-md-7">
            <label>Item Name</label>
        </div>
    </div>
    <div class="col-md-9 no-padding" >
        <div class="col-md-6">
          <label>Description</label>
        </div>
        <div class="col-md-2">
          <label>Amount</label>
        </div> 
        <div class="col-md-2">
          <label>Adjust Amt</label>
        </div>    
        <div class="col-md-2">
          <label>Balance</label>
        </div>     
        
     </div>           
</div>

<?php 

if(!empty($bill_items)) {
    //$bill_items = array(array('bill_item_id'=>'','item_cat_id'=>'','item_sub_cat_id'=>'','item_period'=>'','item_descrip'=>'','item_amount'=>'','sub_cat_dd'=>array()));
 
foreach ($bill_items as $Bkey=>$Bval) {
                    
?>
<div class="col-md-12  no-padding item_<?php echo ($Bkey+1);?>" style="padding-top: 10px !important;" >

    <div class="col-md-3 no-padding">
                        <div class="col-md-7">

        <select name="items[coa_id][]" id="cat_dd_<?php echo ($Bkey+1);?>" class="form-control coa_id" data-id="<?php echo ($Bkey+1);?>" required>
            <option value="">Select</option>
            <?php 
            $period = '';
            foreach ($sales_items as $key=>$val) {
                
                if(!empty ($Bval['coa_id']) && $Bval['coa_id'] == $val['coa_id']) {
                    $selected =  'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option value='".$val['coa_id']."' ".$selected.">".$val['coa_name']."</option>";
            } ?> 
        </select>
    </div>

    </div>
        <div class="col-md-9 no-padding">
            <div class="col-md-6">
              <input type="text" name="items[item_descrip][]" id="desc_txt_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['description']) ? $Bval['description'] : '';?>" class="form-control" readonly="true">
                <input type="hidden" name="items[exp_item_id][]" id="exp_item_id_<?php echo ($Bkey+1);?>" value="<?php echo !empty ($Bval['exp_item_id']) ? $Bval['exp_item_id'] : '';?>"  />
            </div>
            <div class="col-md-2">
              <input type="number" name="items[item_amount][]" id="tot_amt_<?php echo ($Bkey+1);?>" class="form-control tot_amt" value="<?php echo !empty ($Bval['amount']) ? $Bval['amount'] : '';?>" readonly="true" >
            </div>
            <div class="col-md-2">
              <input type="number" name="items[adj_amount][]" id="pay_amt_<?php echo ($Bkey+1);?>" class="form-control pay_amt" value="">
            </div>
            <div class="col-md-2">
              <input type="number" name="items[balance_amount][]" id="bal_amt_<?php echo ($Bkey+1);?>" class="form-control bal_amt" value="<?php echo !empty ($Bval['item_balance_amount']) ? $Bval['item_balance_amount'] : '';?>" readonly="true">
              <input type="hidden" name="" id="ori_bal_amt_<?php echo ($Bkey+1);?>" class="form-control" value="<?php echo !empty ($Bval['item_balance_amount']) ? $Bval['item_balance_amount'] : '';?>" readonly="true">
            </div>

        </div>
</div>

<?php
    } 
}
?>