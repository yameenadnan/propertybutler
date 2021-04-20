
    <form role="form" id="bms_owner_frm" action="<?php echo base_url('index.php/bms_unit_setup/set_parking');?>" method="post" >
      
      <div class="col-md-12 col-xs-12 no-padding" style="">
                 
                    <div class="box-header with-border">
                      <h3 class="box-title"><b>Parking Details</b></h3>
                    </div>
                    <div class="box-body">
                    
                    <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_id;?>"/>
                    
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                          
                          <th>Parking No.</th>               
                          <th>Parking Type</th>
                          <th>Amount</th>
                          <th>Effective Date</th>
                          <th>Charge Code</th>                          
                          <th>Add / Remove</th>
                          
                        </tr>
                        </thead>
                        <tbody class="parking_tbody">
                        
                        <tr id="0">
                            <td>  
                                <input type="text" name="parking[parking_no][]" class="form-control" value="<?php echo isset($parking[0]['parking_no']) && $parking[0]['parking_no'] != '' ? $parking[0]['parking_no'] : '';?>" placeholder="Enter Parking No." maxlength="50">
                                <input type="hidden" name="parking[parking_id][]" value="<?php echo !empty($parking[0]['parking_id']) ? $parking[0]['parking_id'] : '';?>" />
                            </td>
                            <td>
                                <select class="form-control" name="parking[parking_type][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($parking_type as $key=>$val) {                                        
                                        $selected = !empty($parking[0]['parking_type']) && $parking[0]['parking_type'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td><input type="text" name="parking[amount][]" class="form-control" value="<?php echo isset($parking[0]['amount']) && $parking[0]['amount'] != '' ? $parking[0]['amount'] : '';?>" placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="parking[effect_date][]" value="<?php echo !empty($parking[0]['effect_date']) && $parking[0]['effect_date'] != '0000-00-00' ? date('d-m-Y',strtotime($parking[0]['effect_date'])) : '';?>" type="text">
                                </div>  
                            
                            </td>  
                            
                            <td>  
                                <select class="form-control" name="parking[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($parking[0]['charge_code_id']) && $parking[0]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            
                            <td class="text-center"><a href="javascript:;" class="btn btn-success btn-circle add_more_btn" data-value="<?php echo !empty($parkings) ? count($parkings)+1 : 1;?>" ><i class="fa fa-plus"></i></a></td>
                            
                        </tr>
                        
                        <?php if(!empty($parking) && count($parking) > 1) {
                            for ($i =1; $i < count($parking); $i++) { ?>
                                                                
                            <tr id="<?php echo $i;?>">
                            <td>  
                                <input type="text" name="parking[parking_no][]" class="form-control" value="<?php echo isset($parking[$i]['parking_no']) && $parking[$i]['parking_no'] != '' ? $parking[$i]['parking_no'] : '';?>" placeholder="Enter Parking No." maxlength="50">
                                <input type="hidden" name="parking[parking_id][]" value="<?php echo !empty($parking[$i]['parking_id']) ? $parking[$i]['parking_id'] : '';?>" />
                            </td>
                            
                            <td>  
                                <select class="form-control" name="parking[parking_type][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($parking_type as $key=>$val) {                                        
                                        $selected = !empty($parking[$i]['parking_type']) && $parking[$i]['parking_type'] == $key ? 'selected="selected" ' : '';
                                        echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                    } ?> 
                                </select>
                            </td>
                            <td><input type="text" name="parking[amount][]" class="form-control" value="<?php echo isset($parking[$i]['amount']) && $parking[$i]['amount'] != '' ? $parking[$i]['amount'] : '';?>" placeholder="Enter Amount" maxlength="100"></td>
                            <td>
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="parking[effect_date][]" value="<?php echo !empty($parking[$i]['effect_date']) && $parking[$i]['effect_date'] != '0000-00-00' ? date('d-m-Y',strtotime($parking[$i]['effect_date'])) : '';?>" type="text">
                                </div>  
                            
                            </td>  
                            <td>  
                                <select class="form-control" name="parking[charge_code_id][]" >                              
                                <option value="">Select</option>
                                <?php 
                                    foreach ($charge_codes as $key=>$val) {                                        
                                        $selected = !empty($parking[$i]['charge_code_id']) && $parking[$i]['charge_code_id'] == $val['charge_code_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['charge_code_id']."' ".$selected.">".$val['charge_code']."</option>";
                                    } ?> 
                                </select>
                            </td>
                            
                            <td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" data-value="<?php echo $i;?>" ><i class="fa fa-minus"></i></a></td>
                            
                        </tr>
                        
                            
                            <?php
                            }
                            
                        }?>
                       </table>    
                       <div class="col-md-12 no-padding text-right">
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary owner_save_btn">Save</button> &ensp;&ensp;                        
                      </div>
                    </div>   
                               
                    </div>
                    
                    
                </div><!-- /.box-info -->  
             
            </form>

    
  <script>
  
  var charge_codes = $.parseJSON('<?php echo !empty($charge_codes) ? json_encode($charge_codes) : json_encode(array());?>');
  var parking_type = $.parseJSON('<?php echo !empty($parking_type) ? json_encode($parking_type) : json_encode(array());?>');
  $(document).ready(function (){
    
    $('.add_more_btn').click(function () {        
        add_parking($(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
  
    
    $('.del_more_btn').bind('click',function (){
       $('#'+$(this).attr('data-value')).remove(); 
    });
    
    function add_parking (row_id) {
        var str = '<tr id="'+row_id+'">';
        str += '<td>';
        str += '<input type="text" name="parking[parking_no][]" class="form-control" value="" placeholder="Enter Parking No." maxlength="50">'
        str += '<input type="hidden" name="parking[parking_id][]" value="" />';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="parking[parking_type][]">';
        str += '<option value="">Select</option>';
        $.each(parking_type,function (i, item) { 
            str += '<option value="'+i+'">'+item+'</option>';
        });  
        str += '</select>';
        str += '</td>';
        str += '<td><input name="parking[amount][]" class="form-control" value="" placeholder="Enter Amount" maxlength="100" type="text"></td>';
        str += '<td>';
        str += '<div class="input-group date">';
        str += '<div class="input-group-addon">';
        str += '<i class="fa fa-calendar"></i>';
        str += '</div>';
        str += '<input class="form-control pull-right datepicker" name="parking[effect_date][]" value="" type="text">';
        str += '</div>';
        str += '</td>';
        str += '<td>';
        str += '<select class="form-control" name="parking[charge_code_id][]">';
        str += '<option value="">Select</option>';
        $.each(charge_codes,function (i, item) { 
            str += '<option value="'+item.charge_code_id+'">'+item.charge_code+'</option>';
        }); 
        str += '</select>';
        str += '</td>';
        
    
        str += '<td class="text-center"><a href="javascript:;" class="btn btn-danger btn-circle del_more_btn" data-value="'+row_id+'" ><i class="fa fa-minus"></i></a></td>';
    
        str += '</tr>';
        
        $('.parking_tbody').append(str);
        
        $('.datepicker').unbind('datepicker');  
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
        
        $('.del_more_btn').unbind('click');
        $('.del_more_btn').bind('click',function (){
           $('#'+$(this).attr('data-value')).remove(); 
        });
    }
    
  });
  
  //Date picker
  $(function () {
    $('.datepicker').unbind('datepicker');  
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
  });
  </script>