<?php include_once('agm_voter_header.php');?>
                <div class="row">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    
                        <div class="col-md-12 col-sm-12 col-xs-12 ">  
                            <div class="form-group"  style="font-size: 18px;">
                            <?php if(empty($avail_vote)) { ?>
                                There is no AGM/EGM today. Please click <a href="javascript:;" onclick="window.location.reload();"> here </a> after sometimes...
                            <?php } else { ?>
                                <button type="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('index.php/bms_agm_egm_vote/voting');?>'"  style="font-size: 18px;">Start Voting</button>
                            <?php } ?>
                            </div>
                       </div>
                       
                       <div class="col-md-2 col-sm-4 col-xs-12" style="padding-top: 25px !important;">
                              
                        </div>
                    </div>                   
                    
                </div>
                
<?php include_once('agm_voter_footer.php');?>
<script>
$(document).ready(function () {
});
</script>
</body>
</html>