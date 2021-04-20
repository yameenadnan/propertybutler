<style>
.transition {
    -webkit-transform: scale(1.6); 
    -moz-transform: scale(1.6);
    -o-transform: scale(1.6);
    transform: scale(1.6);
}

.img_content {
	-webkit-transition: all .4s ease-in-out;
	-moz-transition: all .4s ease-in-out;
	-o-transition: all .4s ease-in-out;
	-ms-transition: all .4s ease-in-out;
}

</style>

<?php     if(!empty($sop)) {  foreach ($sop as $skey=>$sval) {     ?>
                
                
                
                <div class="row" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                    <div class="box-header with-border" style="padding: 15px 0 10px 0; ">
                        <h3 class="box-title" style="font-weight: bold;"><?php echo $sop[$skey]['sop_name'];?></h3>
                    </div>
                  
                  <div class="row">
                    
                    
                    <div class="col-md-4 col-xs-6">
                        <div class="form-group">
                            <label>Start Date :</label>
                            <?php echo isset($sop[$skey]['start_date']) && $sop[$skey]['start_date'] != '0000-00-00' ? date('d-m-Y',strtotime($sop[$skey]['start_date'])) : ' - ';?>          
                            
                          </div>
                    </div> 
                    <div class="col-md-4 col-xs-6">
                        <div class="form-group">
                            <label> Due Date : </label>
            
                            <?php   if(isset($sop[$skey]['no_due_date']) && $sop[$skey]['no_due_date'] == 1)
                                        echo 'No Due Date.';
                                    else 
                                        echo isset($sop[$skey]['due_date']) && $sop[$skey]['due_date'] != '0000-00-00'? date('d-m-Y',strtotime($sop[$skey]['due_date'])) : ' - ';?>
                          </div>
                    </div>  
                    
                    <div class="col-md-4 col-xs-12" style="margin-bottom: 0px;">
                    
                        <div class="form-group">
                            <label> Task Day(s) : </label>
                                
                                <?php   
                                $result_str = '';
                                if(isset($sop[$skey]['mon']) && $sop[$skey]['mon'] == 1)
                                    $result_str .= 'Mon';
                                if(isset($sop[$skey]['tue']) && $sop[$skey]['tue'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : ''). 'Tue';
                                if(isset($sop[$skey]['wed']) && $sop[$skey]['wed'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Wed';
                                if(isset($sop[$skey]['thu']) && $sop[$skey]['thu'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Thu';
                                if(isset($sop[$skey]['fri']) && $sop[$skey]['fri'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Fri';
                                if(isset($sop[$skey]['sat']) && $sop[$skey]['sat'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sat'; 
                                if(isset($sop[$skey]['sun']) && $sop[$skey]['sun'] == 1)
                                    $result_str .= ($result_str != '' ? ', ' : '').'Sun';
                                echo $result_str;           
                            ?>
                         </div> 
                    
                    </div>
                    
                    <div class="col-md-4 col-xs-6">
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Execute Time : </label>
                                <?php echo isset($sop[$skey]['execute_time']) && $sop[$skey]['execute_time'] != '' ? date('h:i:s a',strtotime($sop[$skey]['execute_time'])) : ' - ';?>
                            </div><!-- /.form group -->
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-xs-6">
                        
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                              <label>Due By : </label>
                              <?php echo isset($sop[$skey]['due_by']) && $sop[$skey]['due_by'] != '' ? date('h:i:s a',strtotime($sop[$skey]['due_by'])) : ' - ';?>                              
                            </div><!-- /.form group -->
                        </div>
                        
                    </div>
                    
                </div> <!-- /.row -->
                
                
                <div class="row">  
                    <div class="box-header with-border" style="padding:15px;">
                        <h3 class="box-title" style="font-weight: bold;">History:</h3>
                    </div>                   
                    <?php
                    $startTime = strtotime( $start_date .' 12:00' );
                    $endTime = strtotime( $end_date .' 12:00' );
                    
                    if(!empty($sop_entry[$sop[$skey]['sop_id']])) {
                        for ( $i = $endTime; $i >= $startTime ; $i = $i - 86400 ) {
                            $thisDate = date( 'Y-m-d', $i ); 
                            $thisDay = strtolower(date('D',strtotime($thisDate)));
                            if(isset($sop[$skey][$thisDay]) && $sop[$skey][$thisDay] == 1) {
                                echo "<div class='col-md-12 col-xs-12'  style='padding-top:10px;padding-bottom:10px;'><label>". $thisDate .":</label>";
                                $entry_str = $thisKey = $thisId = '';
                                foreach ($sop_entry[$sop[$skey]['sop_id']] as $key=>$val) {
                                    if(date( 'Y-m-d', strtotime($val['entry_date'])) == $thisDate ) {
                                       
                                        //echo "Entry Found";
                                        $entry_str = "<div class='col-md-12 col-xs-12'>";
                                        $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                        if(!empty($val['requirement_type'])) {
                                            if($val['requirement_type'] == 'C') {
                                                $entry_str .= "<div class='form-group'><label>Condition: </label> ".(!empty($val['requirement_val']) && $val['requirement_val'] == 'Y' ? 'Ok' : 'Not Ok') ."</div>";
                                            } else {
                                                $entry_str .= "<div class='form-group'><label>Reading: </label> ".(!empty($val['requirement_val']) ?  $val['requirement_val'] : ' - ') ."</div>";
                                            }
                                        } else {
                                            $entry_str .= " - ";
                                        }
                                        
                                        $entry_str .= "</div>";
                                        $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                        $entry_str .= "<div class='form-group'><label>Remarks: </label> ".(!empty($val['remarks']) ? $val['remarks'] : ' - ') ."</div>";
                                        $entry_str .= "</div>";
                                        $entry_str .= "</div>";
                                        $thisKey = $key;
                                        $thisId = $val['id'];
                                    }
                                    
                                }
                                if($entry_str != '') {
                                    echo "</div>".$entry_str;
                                    if(!empty($sop_entry_img[$thisId])) {
                                        
                                        echo "<div class='col-md-12' style='margin: 0px;'>";
                                        
                                        echo "<div class='col-md-12'>";
                                        echo "<div class='col-md-3 col-xs-4 no-padding' > ";
                                        echo "<label>Image(s) :</label>";
                                        echo "<ul style='padding-left:20px ;'>";
                                        foreach ($sop_entry_img[$thisId] as $mkey=>$mval) { 
                                            echo "<li><a href='javascript:;' class='imgs_m_a' data-target='img_view_m_".$thisId."' data-value='".$thisId."/".$mval['img_name']."'>image ".($mkey+1)." </a></li>";
                                         } 
                                            
                                        echo "</ul>"; 
                                        echo "</div>";
                                        
                                        echo "<div class='col-md-6 col-xs-8 img_content' style='padding:0 5px 0 25px;'>";   
                                        echo "<img class='img-responsive center-block img_view img_view_m_".$thisId."' style='max-height: 200px; max-width: 150px;cursor: pointer;' src='../../bms_uploads/sop_entry_upload/".$thisId."/".$sop_entry_img[$thisId][0]['img_name']."' />";
                                        echo "</div>";
                                        echo "</div>";
                                
                                        echo "</div>";
                                    }
                                        
                                                                   
                                    
                                } else {
                                    echo " - Entry Not Found!</div>";
                                }
                            }
                            
                        }
                    } else {
                        echo " <div class='col-md-12 col-xs-12 text-center' style='padding-bottom:15px;'> Entry Not Found!</div>";
                    }
                    
                    if(!empty($sop_sub[$sop[$skey]['sop_id']])) {
                        foreach ($sop_sub[$sop[$skey]['sop_id']] as $key=>$val) { ?>
                        <div style='clear: both;'></div>
                        <div class="box-header with-border" style="padding:15px;">
                            <h3 class="box-title" style="font-weight: bold;"><?php echo $val['sub_sop_name'];?></h3>
                        </div>
                        <?php
                            if(!empty($sop_sub_entry[$val['sop_sub_id']])) {
                                for ( $i = $endTime; $i >= $startTime ; $i = $i - 86400 ) {
                                    $thisDate = date( 'Y-m-d', $i ); 
                                    $thisDay = strtolower(date('D',strtotime($thisDate)));
                                    if(isset($sop[$skey][$thisDay]) && $sop[$skey][$thisDay] == 1) {
                                        echo "<div class='col-md-12 col-xs-12'  style='padding-top:10px;padding-bottom:10px;'><label>". $thisDate .":</label>";
                                        $entry_str = $thisKey = $thisId = '';
                                        foreach ($sop_sub_entry[$val['sop_sub_id']] as $key2=>$val2) {
                                            if(date( 'Y-m-d', strtotime($val2['entry_date'])) == $thisDate ) {
                                                
                                                //echo "Entry Found";
                                                $entry_str = "<div class='col-md-12 col-xs-12'>";
                                                $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                                if(!empty($val2['requirement_type'])) {
                                                    if($val2['requirement_type'] == 'C') {
                                                        $entry_str .= "<div class='form-group'><label>Condition: </label> ".(!empty($val2['requirement_val']) && $val2['requirement_val'] == 'Y' ? 'Ok' : 'Not Ok') ."</div>";
                                                    } else {
                                                        $entry_str .= "<div class='form-group'><label>Reading: </label> ".(!empty($val2['requirement_val']) ?  $val2['requirement_val'] : ' - ') ."</div>";
                                                    }
                                                } else {
                                                    $entry_str .= " - ";
                                                }
                                                
                                                $entry_str .= "</div>";
                                                $entry_str .= "<div class='col-md-6 col-xs-6'>";
                                                $entry_str .= "<div class='form-group'><label>Remarks: </label> ".(!empty($val2['remarks']) ? $val2['remarks'] : ' - ') ."</div>";
                                                $entry_str .= "</div>";
                                                $entry_str .= "</div>";
                                                
                                                // for image display purpose
                                                $thisKey = $key2;
                                                $thisId = $val2['id'];
                                            }
                                            
                                        }
                                        if($entry_str != '') {
                                            echo "</div>".$entry_str;
                                            
                                            if($thisId != '' && !empty($sop_sub_entry_img[$thisId])) {
                                        
                                                echo "<div class='col-md-12' style='margin: 0px;'>";
                                                
                                                echo "<div class='col-md-12' >";
                                                echo "<div class='col-md-3 col-xs-4 no-padding' > ";
                                                echo "<label>Image(s) :</label>";
                                                echo "<ul style='padding-left:20px ;'>";
                                                foreach ($sop_sub_entry_img[$thisId] as $mkey=>$mval) { 
                                                    echo "<li><a href='javascript:;' class='imgs_s_a'  data-target='img_view_s_".$thisId."' data-value='".$thisId."/".$mval['img_name']."'>image ".($mkey+1)." </a></li>";
                                                 } 
                                                    
                                                echo "</ul>"; 
                                                echo "</div>";
                                                
                                                echo "<div class='col-md-6 col-xs-8 img_content' style='padding:0 5px 0 25px;'>";   
                                                echo "<img class='img-responsive center-block img_view img_view_s_".$thisId."' style='max-height: 200px; max-width: 150px;cursor: pointer;' src='../../bms_uploads/sop_sub_entry_upload/".$thisId."/".$sop_sub_entry_img[$thisId][0]['img_name']."' />";
                                                echo "</div>";
                                                echo "</div>";
                                        
                                                echo "</div>";
                                            }
                                            
                                        } else {
                                            echo " - Entry Not Found!</div>";
                                        }
                                    }
                                }
                            } else {
                                echo " <div class='col-md-12 col-xs-12 text-center' style='padding-bottom:15px;'> Entry Not Found!</div>";
                            }
                        }
                    }
                    
                     ?>
                
                </div> <!-- /.row -->    
                    
                  
            </div>                    
                <?php 
                }
                        
            } else { ?>
             <div class='col-md-12 col-xs-12 text-center' style='padding-bottom:15px;'> Data Not Found!</div>   
            <?php } ?>
<!-- Modal -->
<div id="fullImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Task Image</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 full_img">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

                                
<script>
$('.imgs_m_a').click(function () {
    //console.log($(this).attr('data-target') + '  ==  '+$(this).attr('data-value'));  
    //$('.'+$(this).attr('data-target')).attr('src','<?php echo base_url();?>assets/images/loading.gif');   
    $('.'+$(this).attr('data-target')).attr('src','<?php echo '../../bms_uploads/sop_entry_upload/';?>'+$(this).attr('data-value'));
});

$('.imgs_s_a').click(function () {
    //console.log($(this).attr('data-target') + '  ==  '+$(this).attr('data-value')); 
    //$('.'+$(this).attr('data-target')).attr('src','<?php echo base_url();?>assets/images/loading.gif');      
    $('.'+$(this).attr('data-target')).attr('src','<?php echo '../../bms_uploads/sop_sub_entry_upload/';?>'+$(this).attr('data-value'));
});

$(document).ready(function(){
    /*$('.img_content').hover(function() {
        $(this).addClass('transition');
    
    }, function() {
        $(this).removeClass('transition');
    });*/
    
    $('.img_view').bind("click",function () {
        //console.log($(this).attr('data-date'));
        $('.full_img').html('<img src="'+$(this).attr('src')+'" class="img-responsive center-block"/>');
        $('#fullImgModal').modal({show:true});       
    });
    
});
</script>            