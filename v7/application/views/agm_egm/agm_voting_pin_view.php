<?php include_once('agm_voter_header.php');?>
<div class="row">
                  <?php //if(!empty($eligible_voter)) { ?>  
                    <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_agm_egm_vote/start_voting');?>" method="post" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="text-danger" style="font-size: 18px;">Please wait for our announcement for the voting PIN Number!</p>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                        <div class="col-md-3 col-sm-4 col-xs-10 ">  
                            <div class="form-group"  style="font-size: 18px;">
                                <label >Enter Voting PIN Number</label>
                                <input type="text" name="agenda_pin" id="agenda_pin" class="form-control"  value=""  style="font-size: 35px;height:60px;" maxlength="10">
                            </div>
                       </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                       <div class="col-md-2 col-sm-4 col-xs-12"  >
                            <button type="submit" class="btn btn-primary service_schedule_add_btn"  style="font-size: 18px;">Submit</button>   
                        </div>
                    </div>                   
                    </form>
                    
                    <?php // } else { ?>
                           <!--     You are not eligible to vote. Please check with Administrators!<br /><br />
                                Click <a href="<?php echo base_url('index.php/bms_agm_egm_vote/ready_vote');?>" onclick="window.location.reload();"> here </a> go back.<br /><br /><br />
                                Click <a href="<?php echo base_url('index.php/bms_agm_egm_vote/logout');?>"> here </a> go logout.
                           -->
                            <?php// } ?>
                    
                </div>
                
                
<?php include_once('agm_voter_footer.php');?>              
<script>
$(document).ready(function () {    
    jQuery('#agenda_pin').focus();
});
</script>
</body>
</html>