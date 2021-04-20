<?php include_once('agm_voter_header.php');?>
<style>
    .font_size > div > div { font-size: 24px; }
    .chk_box {
        transform:scale(2);
    }

</style>
<div class="row" style="font-size: 18px;">
    <script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>

        <input type="hidden" name="agm_attendance_id" value="<?php echo $_SESSION['agm']['agm_attendance_id'];?>" />
        <input type="hidden" name="resolu_type" value="<?php echo $resolu_type;?>" />
        <input type="hidden" name="agenda_id" value="<?php echo $agm_agenda_id;?>" />

        <div class="col-md-12" style="padding: 10px 0; overflow-wrap: break-word;"><label><?php echo $agenda_resol; ?></label></div>
        <div class="col-md-12 no-padding font_size">
            <?php
            switch ($resolu_type) {
                case 1:
                    if(!empty($agenda)) {
                        foreach ( $agenda as $key=>$val ) {
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-md-4 col-xs-8"><label>You casted vote for </label></div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                            <div class="col-md-4 col-xs-8"><?php echo $val['unit_no']. ' - '. ($val['proxy_required'] == 1 ? $val['proxy_name'] .' (Proxy)' : $val['owner_name'] );?></div>
                        </div>
                            <?php
                        }
                    } else { ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-md-4 col-xs-8"><label>You are Abstain for this resolution</label></div>
                        </div>
                    <?php }
                    break;
                case 2:
                case 3:
                case 4:
                case 5;
                    if(!empty($agenda)) {
                        foreach ( $agenda as $key=>$val ) {
                            ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-xs-8"><label>You casted your vote
                                        <br/><?php echo ($val['vote_for'] == '1') ? 'For' : 'Against'; ?></label>
                                </div>
                                <div class="col-xs-4"><label></label></div>
                            </div>
                            <?php
                        }
                    } else { ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-xs-8"><label>You are Abstain for this resolution</div>
                            <div class="col-xs-4"><label></label></div>
                        </div>
                    <?php }
                    break;
                case 7:
                    if(!empty($agenda)) {
                        foreach ( $agenda as $key=>$val ) {
                            ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="col-xs-4"><label><?php echo $val['item']; ?></label></div>
                            </div>
                            <?php
                        }
                    } else { ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-xs-8"><label>You are Abstain for this resolution</div>
                            <div class="col-xs-4"><label></label></div>
                        </div>
                    <?php }
                    break;
                case 8:
                    if(!empty($agenda)) {
                        foreach ($agenda as $key=>$val) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                <div class="col-md-4 col-xs-8"><?php echo $val['unit_no']. ' - '. ($val['proxy_required'] == 1 ? $val['proxy_name'] . ' (Proxy)' : $val['owner_name'] );?></div>
                            </div>

                            <?php
                        }
                    }
                    break;
            }
            ?>
        </div>
        <br /><br />
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="col-xs-8"><label><a class="btn btn-primary" href="<?php echo base_url('index.php/bms_agm_egm_vote/vote_history');?>" style="padding: 8px 15px; margin-top: 25px; font-size: 18px;">Back</a></label></div>
        </div>
</div>
<?php include_once('agm_voter_footer.php');?>
</body>
</html>

















<?php include_once('agm_voter_header.php');?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="form-group"  style="font-size: 18px;">
                    <div class="box-body">
                        <?php // echo $owner_name; ?>
                    </div>
                </div>
           </div>
        </div>
    </div>
<?php include_once('agm_voter_footer.php');?>
</body>
</html>