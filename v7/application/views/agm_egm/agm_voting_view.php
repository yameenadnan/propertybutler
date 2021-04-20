<?php include_once('agm_voter_header.php');?>
<style>
.font_size > div > div { font-size: 24px; }
.chk_box {
  transform:scale(2);
}

</style>
                <div class="row" style="font-size: 18px;">
                    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_agm_egm_vote/set_voting');?>" method="post" >
                    
                    <input type="hidden" name="agm_attendance_id" value="<?php echo $_SESSION['agm']['agm_attendance_id'];?>" />
                    <input type="hidden" name="resolu_type" value="<?php echo $agenda['resolu_type'];?>" />
                    <input type="hidden" name="agenda_id" value="<?php echo $agenda['agm_agenda_id'];?>" />
                    <input type="hidden" name="agenda_pin" id="agenda_pin" value="<?php echo $pin;?>" />

                    <div class="col-md-12" style="padding: 10px 0; overflow-wrap: break-word;"><label><?php echo $agenda['agenda_resol'];?></label></div>
                    <div class="col-md-12 no-padding font_size"> 
                     <?php 
                        switch ($agenda['resolu_type']) {
                            case 1:
                                 if(!empty($nominee)) { ?>
                                 
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                        <div class="col-md-4 col-xs-8"><label>Nominee</label></div>
                                        
                                        <div class="col-xs-4"><label>Vote</label></div>
                                 </div> 
                                 
                                 <?php 
                                    
                                    foreach ($nominee as $key=>$val) { ?>
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                        <div class="col-md-4 col-xs-8"><?php echo $val['nom_unit_no']. ' - '. ($val['proxy_required'] == 1 ? $val['proxy_name'] .' (Proxy)' : $val['nom_owner_name'] );?></div>                                        
                                        <div class="col-xs-4"><input type="radio" class="chk_box" name="vote_pc" value="<?php echo $val['pc_id'];?>" /></div>
                                    </div>
                                        
                                 <?php
                                    } 
                                    
                                 }
                                break;
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                            
                            ?>
                                 
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                        <div class="col-xs-8"><label>Vote</label></div>                                        
                                        <div class="col-xs-4"><label></label></div>
                                 </div> 
                                 
                                 
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                        <div class="col-md-2 col-xs-8">For</div>                                        
                                        <div class="col-xs-4"><input type="radio" class="chk_box" name="vote_for" value="1" /></div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                        <div class="col-md-2 col-xs-8">Against</div>
                                        
                                        <div class="col-xs-4"><input type="radio" class="chk_box" name="vote_for" value="2" /></div>
                                    </div>
                                        
                                    <?php
                                break; 
                                
                                
                            case 7:
                            
                                if(!empty($items)) { ?>
                                 
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                                        <div class="col-md-2 col-xs-8"><label>Item</label></div>                                        
                                        <div class="col-xs-4"><label>Vote</label></div>
                                 </div> 
                                 
                                 <?php 
                                    
                                    foreach ($items as $key=>$val) { ?>
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                        <div class=" col-md-2 col-xs-8"><?php echo $val['item'];?></div>                                        
                                        <div class="col-xs-4"><input type="radio" class="chk_box" name="vote_no_of_comm" value="<?php echo $val['propose_id'];?>" /></div>
                                    </div>
                                        
                                 <?php
                                 
                                    }
                                    
                                 }
                                 break;
                            case 8: 
                                if(!empty($nominee)) { ?>
                                 
                                 <div class="col-md-12 col-sm-12 col-xs-12 no-padding" >
                                    
                                        <div class="col-md-4 col-xs-8"><label>Nominee</label></div>                                        
                                        <div class="col-xs-4"><label>Vote</label></div>
                                 </div> 
                                 
                                 <?php 
                                    
                                    foreach ($nominee as $key=>$val) { ?>
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                        <div class="col-md-4 col-xs-8"><?php echo $val['nom_unit_no']. ' - '. ($val['proxy_required'] == 1 ? $val['proxy_name'] . ' (Proxy)' : $val['nom_owner_name'] );?></div>                                        
                                        <div class="col-xs-4"><input type="checkbox" class="mc_chk chk_box" name="vote_mc[]" value="<?php echo $val['mc_nomin_id'];?>" /></div>
                                    </div>
                                        
                                 <?php
                                 
                                    }                                    
                                 }
                                break;
                     }
                     ?>
                     </div>       
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12 no-padding text-right">
                       <div class="col-md-2 col-sm-4 col-xs-12" style=" padding-top: 25px !important;">
                            <button type="button" class="btn btn-primary save_btn" style="font-size: 18px;">Submit</button>  &ensp; &ensp; 
                            <button type="reset" id="reset_page" class="btn btn-default reset_btn" style="font-size: 18px;">Reset</button>
                        </div>
                    </div>                   
                    </form>
                <?php include_once('agm_voter_footer.php');?>              
<script>
$(document).ready(function () {  
    $('.chk_box:first').focus(); 
    $('.save_btn').click(function () {
        console.log($('.chk_box:checked').length);
        if($('.chk_box:checked').length) {
            if(confirm('You cannot undo this action. Are you sure you want to save?')){
                $( "#bms_frm" ).submit();
            }
        } else {
            alert('Please select atleast one!');  
            $('.chk_box:first').focus();      
        }       
    });

    $('#reset_page').click ( function () {
        $('#bms_frm').attr('action', '<?php echo base_url('index.php/bms_agm_egm_vote/start_voting');?>');
        $('#bms_frm').submit ();
    });
});
</script>
</body>
</html>