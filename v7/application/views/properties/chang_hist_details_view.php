
<div class="box-body">
<div class="col-md-12 col-xs-12" style="padding-top: 10px;">

    <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>   
                  <th><?php echo $charg_cat[$cat_id];?></th>               
                  <th>Till Date</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $offset = 0;
                    //$task_status = $this->config->item('task_db_status');
                    if(!empty($property_chang_hist_det)) {
                        
                        foreach ($property_chang_hist_det as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td><?php echo $cat_id == 1 ? $calcul_base[$val['charg_val']] : $val['charg_val'];?></td>
                            <td><?php echo !empty($val['updated_date']) ? date('d-m-Y',strtotime($val['updated_date'])) : ' - ';?></td>
                                                       
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="text-center" colspan="3">No Record Found</td>                            
                        </tr>                    
                    <?php } ?>                
                </tbody>                
              </table>
</div>
</div>

