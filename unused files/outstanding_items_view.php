<?php if($key_cnt == 0) { ?><div class="col-md-12 no-padding" >
    <div class="col-md-3 no-padding">
        <div class="col-md-7">
            <label>Item Name</label>
        </div>
        
        <div class="col-md-5">                    
          <label>Period</label>
        </div>
        
    </div>
    <div class="col-md-9 no-padding" >
        <div class="col-md-5">
          <label>Description</label>
        </div>
        <div class="col-md-2">
          <label>Amount</label>
        </div> 
        <div class="col-md-2">
          <label>Applied Amt</label>
        </div>    
        <div class="col-md-2">
          <label>Balance</label>
        </div>     
        <div class="col-md-1">&nbsp;</div>  
     </div>           
</div>

<?php 
}
if(empty($bill_items) && $key_cnt == 0) {
    $bill_items = array(array('bill_item_id'=>'','item_cat_id'=>'','item_sub_cat_id'=>'','item_period'=>'','item_descrip'=>'','item_amount'=>'','sub_cat_dd'=>array()));
} 
foreach ($bill_items as $Bkey=>$Bval) {
                    
?>
<div class="col-md-12  no-padding item_<?php echo ($Bkey+$key_cnt+1);?>" style="padding-top: 10px !important;" >
    
    
    <div class="col-md-3 no-padding">
                        <div class="col-md-7">
                        
    
        <input type="hidden" name="items[bill_item_id][]" id="bill_item_id_<?php echo ($Bkey+$key_cnt+1);?>" value="<?php echo !empty ($Bval['bill_item_id']) ? $Bval['bill_item_id'] : '';?>"  />
    
        <select name="items[item_cat_id][]" id="cat_dd_<?php echo ($Bkey+$key_cnt+1);?>" class="form-control cat_dd" data-id="<?php echo ($Bkey+$key_cnt+1);?>" required>
            <option value="">Select</option>
            <?php 
            $period = '';
            foreach ($sales_items as $key=>$val) {
                
                if(!empty ($Bval['item_cat_id']) && $Bval['item_cat_id'] == $val['coa_id']) {
                    $selected =  'selected="selected"';
                    if(!empty($val['period'])) {
                        $period = $val['period'];
                    }
                } else {
                    $selected = '';
                }
                echo "<option value='".$val['coa_id']."' data-period='".$val['period']."' ".$selected.">".$val['coa_name']."</option>";
            } ?> 
          </select> 
          
    </div>
    
    <div class="col-md-5">                    
      <select class="form-control period_dd" name="items[item_period][]" id="period_dd_<?php echo ($Bkey+$key_cnt+1);?>" data-id="<?php echo ($Bkey+$key_cnt+1);?>">
      <?php echo get_period_dd($period,(!empty ($Bval['item_period']) ? $Bval['item_period'] : '')); ?>
      </select>
      
    </div>
    </div>
                    <div class="col-md-9 no-padding" >
                        <div class="col-md-5">
                          <input type="text" name="items[item_descrip][]" id="desc_txt_id_<?php echo ($Bkey+$key_cnt+1);?>" value="<?php echo !empty ($Bval['item_descrip']) ? $Bval['item_descrip'] : '';?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                          <input type="number" name="items[item_amount][]" id="tot_amt_<?php echo ($Bkey+$key_cnt+1);?>" class="form-control tot_amt" value="<?php echo !empty ($Bval['bal_amount']) ? $Bval['bal_amount'] : (!empty ($Bval['item_amount']) ? $Bval['item_amount'] : '');?>" readonly="true">
                          <input type="hidden" name="items[item_amount_orgi][]" value="<?php echo !empty ($Bval['item_amount']) ? $Bval['item_amount'] : '';?>" />                                                    
                        </div> 
                        <div class="col-md-2">
                          <input type="number" name="items[paid_amount][]" id="pay_amt_<?php echo ($Bkey+$key_cnt+1);?>" class="form-control pay_amt" value="<?php echo !empty ($Bval['paid_amount']) ? $Bval['paid_amount'] : '';?>">
                        </div> 
                        <div class="col-md-2">
                          <input type="number" name="items[bal_amount][]" id="bal_amt_<?php echo ($Bkey+$key_cnt+1);?>" class="form-control bal_amt" value="<?php echo !empty ($Bval['bal_amount']) ? $Bval['bal_amount'] : '';?>" readonly="true">
                          <input type="hidden" name="" id="ori_bal_amt_<?php echo ($Bkey+$key_cnt+1);?>" class="form-control" value="<?php echo !empty ($Bval['bal_amount']) ? $Bval['bal_amount'] : '';?>" readonly="true">
                        </div> 
                     
                        <div class="col-md-1 text-center"><button type="button" class="btn btn-danger btn-remove" data-id="<?php echo ($Bkey+$key_cnt+1);?>"><i class="fa fa-close"></i></button></div>
                    </div>                       
</div>

<?php } ?>

        