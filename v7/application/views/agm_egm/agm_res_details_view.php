
      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
        <div class="box box-primary">
        
        
            
            <div class="box-body">
                                
                            <?php 
                            
                            if($check_abst[0] == 'a') { ?>
                            
                                <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="col-md-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4"><label>Unit No</label></div>
                                            <div class="col-md-4"><label>Name</label></div>
                                            <?php if($vote_by == 2) { ?>
                                            <div class="col-md-4"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($abstrain)) { 
                                                $total = $vote_by == 2 ? array_sum(array_column($abstrain,'vote_cnt')) : 0;
                                                    foreach ($abstrain as $key=>$val) { ?>                                    
                                                    <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4">
                                                        <?php echo $val['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php echo $val['proxy_required'] == 1 ? $val['proxy_name']. "(Proxy)" : $val['owner_name']; ?>
                                                        </div> 
                                                        <?php if($vote_by == 2) { ?>
                                                        <div class="col-md-4">
                                                        <?php echo $val['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>                                               
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($vote_by == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-8">&nbsp;</div>                                                        
                                                        <div class="col-md-4">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }  
                                              }
                                                ?>                                        
                                    </div>
                            
                                
                                
                                
                                
                                
                           <?php  } else {
                                
                            
                            
                            
                            switch ($resol_type) {
                                case 1: ?>
                                                    
                                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="col-md-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4"><label>Unit No</label></div>
                                            <div class="col-md-4"><label>Name</label></div>
                                            <?php if($vote_by == 2) { ?>
                                            <div class="col-md-4"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($pc_res)) { 
                                                
                                                    $total = $vote_by == 2 ? array_sum(array_column($pc_res,'vote_cnt')) : 0;
                                                    foreach ($pc_res as $key=>$val) { ?>                                    
                                                    <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4">
                                                        <?php echo $val['nom_unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php echo $val['proxy_required'] == 1 ? $val['proxy_name']. "(Proxy)" : $val['nom_owner_name']; ?>
                                                        </div>
                                                        <?php if($vote_by == 2) { ?>
                                                        <div class="col-md-4">
                                                        <?php echo $val['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($vote_by == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-8">&nbsp;</div>                                                        
                                                        <div class="col-md-4">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                    
                                                      
                                              }
                                                ?>                                        
                                    </div>
                                
                                <?php 
                                    
                                    break;
                                case 2:
                                case 3:
                                case 4:
                                case 5:
                                ?>
                                
                                <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="col-md-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4"><label>Unit No</label></div>
                                            <div class="col-md-4"><label>Name</label></div>
                                            <?php if($vote_by == 2) { ?>
                                            <div class="col-md-4"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($vote_res)) { 
                                                    $total = $vote_by == 2 ? array_sum(array_column($vote_res,'vote_cnt')) : 0;
                                                    foreach ($vote_res as $key=>$val) { ?>                                    
                                                    <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4">
                                                        <?php echo $val['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php echo $val['proxy_required'] == 1 ? $val['proxy_name']. "(Proxy)" : $val['owner_name']; ?>
                                                        </div>
                                                        <?php if($vote_by == 2) { ?>
                                                        <div class="col-md-4">
                                                        <?php echo $val['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($vote_by == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-8">&nbsp;</div>                                                        
                                                        <div class="col-md-4">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                      
                                              }
                                                ?>                                        
                                    </div>
                    
                    
                    
                                <?php  
                                    
                                    break;
                                case 6: 
                                    break;
                                    
                                    
                                    
                                case 7:?>
                    
                                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="col-md-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4"><label>Unit No</label></div>
                                            <div class="col-md-4"><label>Name</label></div>
                                            <?php if($vote_by == 2) { ?>
                                            <div class="col-md-4"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($vote_res)) { 
                                                    $total = $vote_by == 2 ? array_sum(array_column($vote_res,'vote_cnt')) : 0;
                                                    foreach ($vote_res as $key=>$val) { ?>                                    
                                                    <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4">
                                                        <?php echo $val['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php echo $val['proxy_required'] == 1 ? $val['proxy_name']. "(Proxy)" :  $val['owner_name']; ?>
                                                        </div>
                                                        <?php if($vote_by == 2) { ?>
                                                        <div class="col-md-4">
                                                        <?php echo $val['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    
                                                    
                                                    if($vote_by == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-8">&nbsp;</div>                                                        
                                                        <div class="col-md-4">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                      
                                              }
                                                ?>                                        
                                    </div>
                                                                    
                                <?php 
                                    
                                    break;
                                case 8: ?>                            
                                
                                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="col-md-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4"><label>Unit No</label></div>
                                            <div class="col-md-4"><label>Name</label></div>
                                            <?php if($vote_by == 2) { ?>
                                            <div class="col-md-4"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($vote_res)) { 
                                                    $total = $vote_by == 2 ? array_sum(array_column($vote_res,'vote_cnt')) : 0;
                                                    foreach ($vote_res as $key=>$val) { ?>                                    
                                                    <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4">
                                                        <?php echo $val['nom_unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                        <?php echo $val['proxy_required'] == 1 ? $val['proxy_name']. "(Proxy)" :  $val['nom_owner_name']; ?>
                                                        </div>
                                                        <?php if($vote_by == 2) { ?>
                                                        <div class="col-md-4">
                                                        <?php echo $val['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }  
                                                    
                                                    
                                                    
                                                    if($vote_by == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-8">&nbsp;</div>                                                        
                                                        <div class="col-md-4">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                    
                                              }
                                                ?>                                        
                                    </div>
                    
                     
                                <?php 
                                    
                                    break;
                                    
                                }
                                
                            }
                                 ?>
                                 
    </div>
                                
</div>                                 