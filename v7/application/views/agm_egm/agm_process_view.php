<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<!--link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css"-->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/select2/dist/css/select2.css">
<style>
.pin_div > button { font-size:28px !important }
.result_div > div.page-header > h3 { color:green; }
.result_div > div.page-header { margin-bottom: 5px; }
.result_div>div>div  { font-size:18px !important; }
.result_div { padding:5px;border: 1px solid #999;border-radius: 5px; }
h3 { margin-top: 10px !important; margin-bottom: 5px; }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
            <!--small>Optional description</small-->
        </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->
        <div class="box box-primary">

        <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
            //if($_GET['login_err'] == 'invalid')
            echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
            echo '</strong>'.$_SESSION['flash_msg'].'</div>';
            unset($_SESSION['flash_msg']);
        }

        ?>

            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_agm_egm/set_agm_process');?>" method="post" >

                <div class="box-body">

                    <div class="row" >
                        <div class="col-md-12 col-xs-12 no-padding" style="margin-bottom: 15px;">
                        <div class="col-md-4 col-xs-6">
                        <select class="form-control" id="property_id" name="property_id">
                            <option value="">Select Property</option>
                            <?php
                                foreach ($properties as $key=>$val) {
                                    $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val['property_id']."' ".$selected." data-value='".$val['total_units']."'>".$val['property_name']."</option>";
                                } ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-xs-5">
                          <select class="form-control" id="agm_id" name="agm_id">
                            <option value="">Select AGM/EGM</option>
                            <?php
                                $vote_by = $no_of_committee = '';
                                foreach ($agms as $key=>$val) {
                                    if(isset($agm_id) && trim($agm_id) != '' && trim($agm_id) == $val['agm_id']) {
                                        $vote_by = $val['vote_by'];
                                        $no_of_committee = $val['no_of_committee'];
                                        $selected = 'selected="selected" ';
                                    } else {
                                        $selected = '';
                                    }
                                    //$selected = isset($agm_id) && trim($agm_id) != '' && trim($agm_id) == $val['agm_id'] ? 'selected="selected" ' : '';
                                    echo "<option value='".$val['agm_id']."' ".$selected." >".$val['agm_term']."</option>";
                                } ?>
                        </select>
                    </div>
                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary filter"><i class="fa fa-search"></i></a>
                    </div>
                    <?php if(!empty($agm_id)) { ?>
                    <div class="col-md-3 col-xs-12">
                        <button class="btn btn-primary save_btn" ><i class="fa fa-save"></i>&nbsp; Save </button> &ensp; &ensp;
                        <button class="btn btn-primary print_btn"  type="button"><i class="fa fa-print"></i> &nbsp;Report </button>
                        <!--button class="btn btn-primary bootbox_btn"  type="button"><i class="fa fa-print"></i> &nbsp;Test </button-->
                    </div>
                    <?php } ?>

                    </div>
                    </div>

                <!-- AGM -->
                <?php


               ?>
               <?php if(!empty($agm_id)) { ?>

                <?php

                    $unit_dd = '';
                    foreach($units as $key=>$val) {
                        $owner_name = $val['proxy_required'] == 1 ? $val['proxy_name'] .' (Proxy)' : $val['owner_name'];
                        $unit_dd .= '<option value="'.$val['eli_voter_id'].'">'.$val['unit_no'].'-'.$owner_name.'</option>';
                    }


                    $mc_unit_dd = '';
                    if(!empty($eli_mc_units)) {
                        foreach($eli_mc_units as $key=>$val) {
                            $owner_name = $val['proxy_required'] == 1 ? $val['proxy_name'] .' (Proxy)' : $val['owner_name'];
                            $mc_unit_dd .= '<option value="'.$val['eli_voter_id'].'">'.$val['unit_no'].'-'.$owner_name.'</option>';
                        }
                    }


                    if(!empty($agm_details)) { ?>
                    <input type="hidden" value="" name="start_vote" id="start_vote" />
                    <input type="hidden" value="" name="close_vote" id="close_vote" />
                    <input type="hidden" value="" name="re_vote" id="re_vote" />
                    <div class="col-md-12" style="margin: 15px 0;">
                        <div class="col-md-6 no-padding" >
                            <label>VOTE</label> &ensp;&ensp;&ensp;
                            <label class="radio-inline">
                                <input type="radio" name="vote_by" value="1" <?php echo $vote_by == 1 ? 'checked="checked"' : '';?> /> BY SHOW OF HANDS
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="vote_by" value="2" <?php echo $vote_by == 2 ? 'checked="checked"' : '';?>/> BY POLL
                            </label>
                        </div>

                   </div>
                   <div class="col-md-12 text-right" >
                            Number Of Eligible Voters: <b><?php echo $no_of_ev['cnt'];?> </b> &ensp;| &ensp; Number Of Attendees: <b>
                           <?php
                           if ( $no_of_attendees['cnt'] < floor( ( $no_of_ev['cnt'] / 2 ) + 1 ) ) { ?>
                               <span style="padding: 10px; font-size: 100%; color: #DD4B39;"><?php echo $no_of_attendees['cnt'];?> (QUORUM NOT MET)</span>
                           <?php } else { ?>
                               <span style="padding: 10px; font-size: 100%; color: #00A65A;"><?php echo $no_of_attendees['cnt'];?> (QUORUM MET)</span>
                           <?php } ?></b>
                        </div>
                    <?php

                        foreach ($agm_details as $key => $val) {

                            echo '<input type="hidden" value="'.$val['agm_agenda_id'].'" name="agenda_id['.$val['agm_agenda_id'].']" />';
                            echo '<input type="hidden" value="'.$val['resolu_type'].'" name="resolu_type['. $val['agm_agenda_id'].']" />';
                            ?>
                            <div id="div_<?php echo $val['agm_agenda_id'];?>" class="col-md-12" style="margin: 15px 0 0 0;border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                    <div class="form-group">
                                    <?php $resol_type = '';
                                        switch ($val['resolu_type']) {
                                            case 2: $resol_type = ' [Ordinary Resolution]'; break;
                                            case 3: $resol_type = ' [Special Resolution]'; break;
                                            case 4: $resol_type = ' [Comprehensive Resolution]'; break;
                                            case 5: $resol_type = ' [Unanimous Resolution]'; break;
                                        }
                                    ?>
                                        <label><?php echo ( $val['seq_no'] == 0 )?'Introduction':'Agenda ' . $val['seq_no'] . $resol_type;?></label>
                                        <div style="font-size: 18px !important;" id="agenda_resol_<?php echo $val['agm_agenda_id'];?>">
                                            <?php echo $val['agenda_resol']; ?>&nbsp;&nbsp;&nbsp;
                                            <span style="font-size: 18px !important;"><?php
                                                if ( $val['seq_no'] != 0  ) { ?>
                                                    <a href="javascript:;" title="Update" data-value="<?php echo $val['agm_agenda_id'];?>" class="update_bms_agm_agenda text-success" data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            <?php switch ($val['resolu_type']) {
                                case 1: ?>

                    <div  class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <div class="col-md-12 no-padding nomination_full_div">
                            <div class="col-md-8 no-padding ">
                                <div class="col-md-12 no-padding nomination_div">
                                    <div class="col-md-4"><label>Nominee</label></div>
                                    <div class="col-md-4"><label>Proposer</label></div>
                                    <div class="col-md-4"><label>Seconder</label></div>

                                    <?php if(empty($pc_nominee[$val['agm_agenda_id']])) { ?>
                                    <div class="col-md-12 no-padding">
                                        <div class="col-md-4">
                                        <input type="hidden" name="pc_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                            <select class="form-control select2" name="naminee[<?php echo $val['agm_agenda_id'];?>][]">
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>

                                    </div>
                                    <?php } else {

                                        foreach ($pc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] != 0) { ?>

                                            <div class="col-md-12 no-padding"  style="padding-top:10px !important">
                                            <div class="col-md-4">
                                            <input type="hidden" name="pc_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['pc_id'];?>" />
                                            <select class="form-control select2" name="naminee[<?php echo $val['agm_agenda_id'];?>][]">
                                                <option value="">Select</option>
                                                    <?php foreach ($units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['nominee'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                                </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php foreach ($units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php foreach ($units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                        </div>

                                    </div>
                                            <?php }
                                        }

                                        ?>

                                    <?php } ?>
                                </div>

                                <?php
                                $nomination_closed = 0;
                                if(!empty($pc_nominee[$val['agm_agenda_id']])) {
                                        foreach ($pc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] == 0) {
                                                $nomination_closed = 1;
                                                ?>

                                                <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="pc_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['pc_id'];?>" />
                                                    <div class="col-md-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>

                                                </div>


                             <?php           }
                                        }
                                    } ?>

                                <?php
                                        if(empty($pc_nominee[$val['agm_agenda_id']]) || empty($nomination_closed) ) {  ?>

                                            <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important; display: none;">
                                                <input type="hidden" name="pc_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                                <div class="col-md-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                            </div>

                             <?php
                                    } ?>

                            </div>
                            <?php
                            if (empty($pc_result[$val['agm_agenda_id']])) { ?>
                            <div class="col-md-2">

                                <div class="col-md-12 no-padding" >
                                    <button type="button" class="btn btn-success add_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" style="<?php echo !empty($val['pin']) ? 'display:none;' : '';?>">Add More</button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'display:none;' : 'padding-top:10px !important;';?>">
                                    <button type="button" class="btn btn-warning namination_close_btn">Nomination Close</button>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                    <button type="button" class="btn btn-primary start_vote" disabled data-start-vote="<?php echo $val['agm_agenda_id'];?>">Start Vote</button>
                                </div>
                                <div class="col-md-12 no-padding pin_div" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-success " ><?php echo !empty($val['pin']) ? $val['pin'] : '';?></button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-danger close_voting_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" data-agm-id="<?php echo $agm_id;?>" data-count-down="61" data-resol-type="<?php echo $val['resolu_type'];?>"  >Close Voting</button>
                                </div>

                            </div>
                            <?php } else {
                                ?>
                            <div class="col-md-4 result_div" style="display: <?php echo !empty($pc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>
                                  </div>

                                  <?php if(!empty($pc_result[$val['agm_agenda_id']])) echo $pc_result[$val['agm_agenda_id']]; ?>

                            </div>
                            <?php } ?>
                        </div>

                    </div>

                    <?php

                                    break;
                                case 2:
                                case 3:
                                case 4:
                                case 5:
                                ?>


                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <?php if(empty($vote_resol_result[$val['agm_agenda_id']])) { ?>
                        <div class="col-md-6">
                                <div class="col-md-4 no-padding" style="padding-top: 10px !important;">
                                    <button type="button" class="btn btn-primary start_vote" <?php echo !empty($val['pin']) ? 'disabled' : '';?> data-start-vote="<?php echo $val['agm_agenda_id'];?>">Start Vote</button> &ensp;&ensp;&ensp;
                                </div>
                                <div class="col-md-4 no-padding pin_div" style="padding-top: 10px !important;">
                                    <button type="button" class="btn btn-success" style="<?php echo !empty($val['pin']) ? '' : 'display:none;';?>"><?php echo !empty($val['pin']) ? $val['pin'] : '';?></button>
                                </div>
                                <div class="col-md-4 no-padding" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-danger close_voting_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" data-agm-id="<?php echo $agm_id;?>" data-count-down="61" data-resol-type="<?php echo $val['resolu_type'];?>">Close Voting</button>
                                </div>

                            </div>
                            <?php } else { ?>
                            <div class="col-md-6 result_div" style="display: <?php echo !empty($vote_resol_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;">
                                <div class="page-header">
                                    <h3>Result :</h3>
                                  </div>
                                  <?php if(!empty($vote_resol_result[$val['agm_agenda_id']])) echo $vote_resol_result[$val['agm_agenda_id']]; ?>
                            </div>
                            <?php } ?>
                    </div>


                                <?php

                                    break;
                                case 6:
                                ?>

                                    <div class="col-md-12 col-xs-12" >
                                        <div class="col-md-12 " style="padding: 15px;">
                                            <div class="col-md-7 no-padding ">
                                                <div class="col-md-12 no-padding nomination_div">

                                                    <div class="col-md-5"><label>Proposer</label></div>
                                                    <div class="col-md-5"><label>Seconder</label></div>

                                                    <div class="col-md-12 no-padding">
                                                        <input type="hidden" name="ps_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo !empty($ps_res[$val['agm_agenda_id']]['proposer_seconder_id']) ? $ps_res[$val['agm_agenda_id']]['proposer_seconder_id'] : '';?>" />
                                                        <div class="col-md-5">
                                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]">
                                                                <option value="">Select</option>
                                                                <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = !empty($ps_res[$val['agm_agenda_id']]['proposer']) && $ps_res[$val['agm_agenda_id']]['proposer'] == $val3['eli_voter_id'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]">
                                                                <option value="">Select</option>
                                                                <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = !empty($ps_res[$val['agm_agenda_id']]['seconder']) && $ps_res[$val['agm_agenda_id']]['seconder'] == $val3['eli_voter_id'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php

                                    break;
                                case 7:?>

                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <div class="col-md-12 no-padding nomination_full_div">
                            <div class="col-md-8 no-padding ">
                                <div class="col-md-12 no-padding nomination_div">
                                    <div class="col-md-4"><label>Item</label></div>
                                    <div class="col-md-4"><label>Proposer</label></div>
                                    <div class="col-md-4"><label>Seconder</label></div>

                                    <?php if(empty($no_of_comm[$val['agm_agenda_id']])) { ?>
                                    <div class="col-md-12 no-padding">
                                        <div class="col-md-4">
                                        <input type="hidden" name="propose_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                            <input type="text" class="form-control" name="item[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>

                                    </div>
                                    <?php } else {

                                        foreach ($no_of_comm[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['item'] != '0') { ?>

                                            <div class="col-md-12 no-padding"  style="padding-top:10px !important">
                                                <div class="col-md-4">
                                                    <input type="hidden" name="propose_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['propose_id'];?>" />
                                                    <input type="text" class="form-control" name="item[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['item'];?>" />

                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php foreach ($units as $key3=>$val3) {
                                                                $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                                $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                            } ?>
                                                        </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php foreach ($units as $key3=>$val3) {
                                                                $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                                $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                            } ?>
                                                        </select>
                                                </div>

                                            </div>
                                            <?php }
                                        }

                                        ?>

                                    <?php } ?>
                                </div>

                                <?php
                                $nomination_closed = 0;
                                if(!empty($no_of_comm[$val['agm_agenda_id']])) {
                                        foreach ($no_of_comm[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['item'] == '0') {
                                                $nomination_closed = 1;
                                                ?>

                                                <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="propose_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['propose_id'];?>" />
                                                    <div class="col-md-4">Nomination Close <input type="hidden"  name="item[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>

                                                </div>


                             <?php           }
                                        }
                                    } ?>

                                <?php
                                        if(empty($no_of_comm[$val['agm_agenda_id']]) || empty($nomination_closed) ) {  ?>

                                            <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important; display: none;">
                                                <input type="hidden" name="propose_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                                <div class="col-md-4">Nomination Close <input type="hidden"  name="item[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                            </div>

                             <?php
                                    } ?>

                            </div>
                            <?php if(empty($no_of_comm_result[$val['agm_agenda_id']])) { ?>
                            <div class="col-md-2">

                                <div class="col-md-12 no-padding" >
                                    <button type="button" class="btn btn-success add_more_num_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" style="<?php echo !empty($val['pin']) ? 'display:none;' : '';?>">Add More</button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'display:none;' : 'padding-top:10px !important;';?>">
                                    <button type="button" class="btn btn-warning namination_close_btn">Nomination Close</button>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top: 10px !important;">
                                    <button type="button" class="btn btn-primary start_vote" disabled data-start-vote="<?php echo $val['agm_agenda_id'];?>">Start Vote</button>
                                </div>
                                <div class="col-md-12 no-padding pin_div" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-success" ><?php echo !empty($val['pin']) ? $val['pin'] : '';?></button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-danger close_voting_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" data-agm-id="<?php echo $agm_id;?>" data-count-down="61" data-resol-type="<?php echo $val['resolu_type'];?>">Close Voting</button>
                                </div>

                            </div>
                            <?php } else { ?>
                            <div class="col-md-4 result_div" style="display: <?php echo !empty($no_of_comm_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>
                                  </div>
                                  <?php if(!empty($no_of_comm_result[$val['agm_agenda_id']])) echo $no_of_comm_result[$val['agm_agenda_id']]; ?>

                            </div>
                            <?php } ?>
                        </div>

                    </div>



                                <?php

                                    break;
                                case 8: ?>



                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <div class="col-md-12 no-padding nomination_full_div">
                            <div class="col-md-8 no-padding ">
                                <div class="col-md-12 no-padding nomination_div">
                                    <div class="col-md-4"><label>Nominee</label></div>
                                    <div class="col-md-4"><label>Proposer</label></div>
                                    <div class="col-md-4"><label>Seconder</label></div>

                                    <?php if(empty($mc_nominee[$val['agm_agenda_id']])) { ?>
                                    <div class="col-md-12 no-padding">
                                        <div class="col-md-4">
                                        <input type="hidden" name="mc_nomin_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                            <select class="form-control select2" name="naminee[<?php echo $val['agm_agenda_id'];?>][]">
                                                <option value="">Select</option>
                                                <?php echo $mc_unit_dd;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php echo $unit_dd;?>
                                            </select>
                                        </div>

                                    </div>
                                    <?php } else {

                                        foreach ($mc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] != 0) { ?>

                                            <div class="col-md-12 no-padding"  style="padding-top:10px !important">
                                            <div class="col-md-4">
                                            <input type="hidden" name="mc_nomin_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['mc_nomin_id'];?>" />
                                            <select class="form-control select2" name="naminee[<?php echo $val['agm_agenda_id'];?>][]">
                                                <option value="">Select</option>
                                                    <?php foreach ($eli_mc_units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['nominee'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                                </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php foreach ($units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                <option value="">Select</option>
                                                <?php foreach ($units as $key3=>$val3) {
                                                        $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                    } ?>
                                                </select>
                                        </div>

                                    </div>
                                            <?php }
                                        }

                                        ?>

                                    <?php } ?>
                                </div>

                                <?php
                                $nomination_closed = 0;
                                if(!empty($mc_nominee[$val['agm_agenda_id']])) {
                                        foreach ($mc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] == 0) {
                                                $nomination_closed = 1;
                                                ?>

                                                <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="mc_nomin_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['mc_nomin_id'];?>" />
                                                    <div class="col-md-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['proposer'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                            <option value="">Select</option>
                                                            <?php foreach ($units as $key3=>$val3) {
                                                                        $selected = $val3['eli_voter_id'] == $val2['seconder'] ? 'selected="selected" ': '';
                                                                        $owner_name = $val3['proxy_required'] == 1 ? $val3['proxy_name'] .' - '. $val3['proxy_ic_no'] .' (Proxy)' : $val3['owner_name'];
                                                                        echo '<option value="'.$val3['eli_voter_id'].'" '.$selected.'>'.$val3['unit_no'].'-'.$owner_name.'</option>';
                                                                    } ?>
                                                        </select>
                                                    </div>

                                                </div>


                             <?php           }
                                        }
                                    } ?>

                                <?php
                                        if(empty($mc_nominee[$val['agm_agenda_id']]) || empty($nomination_closed) ) {  ?>

                                            <div class="col-md-12 no-padding nomination_close_div" style="padding-top: 10px !important; display: none;">
                                                <input type="hidden" name="mc_nomin_id[<?php echo $val['agm_agenda_id'];?>][]" value="" />
                                                <div class="col-md-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"  name="proposer[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-control select2" name="seconder[<?php echo $val['agm_agenda_id'];?>][]" >
                                                        <option value="">Select</option>
                                                        <?php echo $unit_dd;?>
                                                    </select>
                                                </div>
                                            </div>

                             <?php
                                    } ?>

                            </div>
                            <?php if(empty($mc_result[$val['agm_agenda_id']])) { ?>
                            <div class="col-md-2">

                                <div class="col-md-12 no-padding" >
                                    <button type="button" class="btn btn-success add_mc_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" style="<?php echo !empty($val['pin']) ? 'display:none;' : '';?>">Add More</button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'display:none;' : 'padding-top:10px !important;';?>">
                                    <button type="button" class="btn btn-warning namination_close_btn">Nomination Close</button>
                                </div>
                                <div class="col-md-12 no-padding" style="padding-top:10px !important;">
                                    <button type="button" class="btn btn-primary start_vote" disabled data-start-vote="<?php echo $val['agm_agenda_id'];?>">Start Vote</button>
                                </div>
                                <div class="col-md-12 no-padding pin_div" style="<?php echo !empty($val['pin']) ? 'padding-top: 10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-success" ><?php echo !empty($val['pin']) ? $val['pin'] : '';?></button>
                                </div>
                                <div class="col-md-12 no-padding" style="<?php echo !empty($val['pin']) ? 'padding-top:10px !important;' : 'display:none;';?>">
                                    <button type="button" class="btn btn-danger close_voting_btn" data-agenda-id="<?php echo $val['agm_agenda_id'];?>" data-agm-id="<?php echo $agm_id;?>" data-count-down="61" data-resol-type="<?php echo $val['resolu_type'];?>">Close Voting</button>
                                </div>

                            </div>
                            <?php } else { ?>
                            <div class="col-md-4 result_div" style="display: <?php echo !empty($mc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>
                                  </div>
                                  <?php if(!empty($mc_result[$val['agm_agenda_id']])) echo $mc_result[$val['agm_agenda_id']]; ?>

                            </div>
                            <?php } ?>
                        </div>

                    </div>
                                <?php

                                    break;
                                default: ?>

                                <?php
                                    break;
                            } ?>
                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <div class="form-group">
                            <label >AGM Minutes</label>
                            <textarea class="form-control" rows="3" name="agenda_min[<?php echo $val['agm_agenda_id'];?>]"><?php echo !empty($val['minutes']) ? str_replace('<br />',"",$val['minutes']) : '';?></textarea>
                        </div>
                    </div>
               </div>


               <?php

                        }
                    }

                ?>

               <?php if(!empty($agm_id)) { ?>
                    <div class="row">
                    <div class="col-md-12 col-xs-12 text-right" style="padding-top: 15px !important;">
                        <button class="btn btn-primary save_btn" ><i class="fa fa-save"></i>&nbsp; Save </button> &ensp; &ensp;
                        <button class="btn btn-primary print_btn"  type="button"><i class="fa fa-print"></i> &nbsp;Report </button>
                    </div>
                </div>
                     <?php } ?>

              </div>


              <!-- /.box-body -->


            <?php } ?>

            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

  <!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Result Details</h4>
      </div>
      <div class="modal-body modal-body2">

        <div class="xol-xs-12 msg">

        </div>
        <div style="clear: both;height:10px"></div>
        <div class="xol-xs-12" style="padding-top: 15px;">

        </div>


      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<!--script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script-->
<script src="<?php echo base_url();?>bower_components/select2/dist/js/select2.full.js"></script>
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script>

// Prevent for input type number and it increase / decrease on mouse wheel scroll
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
// Stop enter key event on focus of input type number
$(document).on( 'keypress', 'input', function (evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.which == 13) && (node.type == "text" || node.type == "number")) {
        return false;
    }
});

var countDownInterval;

$(document).ready(function () {

    $('.select2').select2();

    $('.msg_notification').fadeOut(5000);

    /*$('.bootbox_btn').click(function () {
        bootbox.alert("Hello world!", function() {
                console.log("Alert Callback");
            });
    });*/

    $(function(){
      // get hash value
      var hash = window.location.hash;
      // now scroll to element with that id
      if(hash)
        $('html, body').animate({ scrollTop: $(hash).offset().top },10);
    });


    $('.result_details').unbind('click');
    $('.result_details').bind("click",function () {
        $('.modal-body2').load('<?php echo base_url('index.php/bms_agm_egm/result_details/');?>?unit_id='+$(this).attr('data-unit-id')+'&agenda_id='+$(this).attr('data-agenda-id')+'&resol_type='+$(this).attr('data-resol-type')+'&vote_by='+$(this).attr('data-vote-by'),function(result){
    	    $('#myModal2').modal({show:true});
    	});
    });


    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>?property_id="+$('#property_id').val();
        }
    });

    $('.filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
    });

    $('.start_vote').click(function () {
        $('#start_vote').val($(this).attr('data-start-vote'));
        $( "#bms_frm" ).submit();
    });

    $('.re_voting_btn').click(function () {
        if(confirm('Are you sure want to Re-Vote this Resolution?')) {
            $('#re_vote').val($(this).attr('data-agenda-id'));
            $( "#bms_frm" ).submit();
        }
    });


    $('.add_btn').click(function () {

    //console.log($(this).parents('div.nomin_close_div'));
        var id = $(this).attr('data-agenda-id');
        var str = '<div class="col-md-12 no-padding" style="padding-top:10px !important">';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="naminee['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="proposer['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="seconder['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        //str += '<div class="col-md-4">&nbsp;</div>';
        str += '</div>';

        $(this).parent('div').parent('div').siblings().find('div.nomination_div').append(str);
        $('.select2').select2();
        //$(this).parents().siblings().find('div.nomination_div').append(str);
        //.siblings().find(".archive-meta-slide")
        //$(this).parents().siblings().find('div.nomination_div').append(str);
        //$(this).closest('div.nomination_full_div').find('.nomination_div').append(str);
        //.siblings().find(".archive-meta-slide")
        //.parent().parent().children
    });

    $('.add_more_num_btn').click(function () {

    //console.log($(this).parents('div.nomin_close_div'));
        var id = $(this).attr('data-agenda-id');
        var str = '<div class="col-md-12 no-padding" style="padding-top:10px !important">';
        str += '<div class="col-md-4">';
        str += '<input type="text" class="form-control" name="item['+id+'][]" value="" />';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="proposer['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="seconder['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        //str += '<div class="col-md-4">&nbsp;</div>';
        str += '</div>';

        $(this).parent('div').parent('div').siblings().find('div.nomination_div').append(str);
        $('.select2').select2();
        //.siblings().find(".archive-meta-slide")

    });

    $('.add_mc_btn').click(function () {

    //console.log($(this).parents('div.nomin_close_div'));
        var id = $(this).attr('data-agenda-id');
        var str = '<div class="col-md-12 no-padding" style="padding-top:10px !important">';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="naminee['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($mc_unit_dd) ? $mc_unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="proposer['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        str += '<div class="col-md-4">';
        str += '<select class="form-control select2" name="seconder['+id+'][]" >';
        str += '<option value="">Select</option> ';
        str += '<?php echo !empty($unit_dd) ? $unit_dd: '';?>';
        str += '</select>';
        str += '</div>';
        //str += '<div class="col-md-4">&nbsp;</div>';
        str += '</div>';

        $(this).parent('div').parent('div').siblings().find('div.nomination_div').append(str);
        $('.select2').select2();
        //$(this).parents().siblings().find('div.nomination_div').append(str);
        //.siblings().find(".archive-meta-slide")
        //$(this).parents().siblings().find('div.nomination_div').append(str);
        //$(this).closest('div.nomination_full_div').find('.nomination_div').append(str);
        //.siblings().find(".archive-meta-slide")
        //.parent().parent().children
    });


    $('.namination_close_btn').click(function () {
        $(this).parent('div').parent('div').siblings().find('div.nomination_close_div').css('display','block');
        $('.select2').select2();
        $(this).parent('div').siblings().find('button.start_vote').removeAttr('disabled');
    });


    $('.save_btn').click(function () {
       $( "#bms_frm" ).submit();
    });

    $('.close_voting_btn').click(function () {
        //if(confirm('Are you sure want to close voting & view result?')) {
            var agenda_id = $(this).attr('data-agenda-id');
            var agm_id = $(this).attr('data-agm-id');
            var vote_by = 1;
            var resol_type = $(this).attr('data-resol-type');
            var obj = $(this);//$(this).parent('div').parent('div');
            obj.attr('disabled','disabled');
            if(parseInt(obj.attr('data-count-down')) > 0) {
                $.ajax({
                    type:"post",
                    async: true,
                    url: '<?php echo base_url('index.php/bms_agm_egm/get_abstrain_cnt');?>',
                    data: {'agenda_id':agenda_id,'agm_id':agm_id,'vote_by':vote_by,'resol_type':resol_type},
                    datatype:"json", // others: xml, json; default is html

                    beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                    success: function(data) {
                        $("#content_area").LoadingOverlay("hide", true);
                        //console.log(data);
                        if(parseInt(data) > 0) {
                            var msg = "There are <b>"+parseInt(data)+"</b> Pending Vote(s).";
                            bootbox.confirm({
                                message: msg,
                                buttons: {
                                    confirm: {
                                        label: 'View',
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: 'Continue',
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    console.log('This was logged in the callback: ' + result);
                                    countDownInterval = setInterval(function(){ get_count_down(obj); }, 1000)
                                    if(result) {
                                        $('.modal-body2').load('<?php echo base_url('index.php/bms_agm_egm/result_details/');?>?unit_id=a_'+agm_id+'&agenda_id='+agenda_id+'&resol_type='+resol_type+'&vote_by='+vote_by,function(result){
                                    	    $('#myModal2').modal({show:true});
                                    	});
                                    }
                                }
                            });

                        } else {
                            $('#close_vote').val(agenda_id);
                            $( "#bms_frm" ).submit();
                        }
                        //window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val()+'#div_'+agenda_id;
                        //return false;
                        //window.location.reload();
                        //console.log(data);
                        /*obj.siblings('div.result_div').css('display','block');
                        obj.siblings('div.result_div').append(data);
                        obj.html('&nbsp;');
                        $("#content_area").LoadingOverlay("hide", true); */

                    },
                    error: function (e) {
                        $("#content_area").LoadingOverlay("hide", true);
                        console.log(e); //alert("Something went wrong. Unable to retrive data!");
                    }
                });
            } else {
               //alert('close vote');
               $('#close_vote').val(agenda_id);
               $( "#bms_frm" ).submit();
            }
        //}
    });

    $('.print_btn').click(function () {
        var url = '<?php echo base_url('index.php/bms_agm_egm/agm_report');?>?property_id='+$('#property_id').val()+'&agm_id='+$('#agm_id').val();
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=550,directories=no,location=no');
    });

    $(document).on("click", ".update_bms_agm_agenda", function () {
        var agm_agenda_id = $(this).data('value');
        $.ajax({
            type:"post",
            url: '<?php echo base_url('index.php/bms_agm_egm/get_bms_agm_agenda_details');?>',
            data: { 'agm_agenda_id':agm_agenda_id },
            datatype:"html", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                $("#content_area").LoadingOverlay("hide", true);
                $(".modal-body #agenda_resol_popup").val( data );
                $(".modal-body #agm_agenda_id_popup").val( agm_agenda_id );
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });

    $(document).on("click", ".btn_update_bms_agm_agenda", function () {
        var agenda_resol_popup = $('#agenda_resol_popup').val();
        var agm_agenda_id = $('#agm_agenda_id_popup').val();
        $.ajax({
            type:"post",
            url: '<?php echo base_url('index.php/bms_agm_egm/update_bms_agm_agenda_details');?>',
            data: { 'agenda_resol':agenda_resol_popup, 'agm_agenda_id':agm_agenda_id },
            datatype:"html", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {
                $("#content_area").LoadingOverlay("hide", true);
                var edit_link = '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" title="Update" data-value="' + agm_agenda_id + '" class="update_bms_agm_agenda text-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></a>';
                $("#agenda_resol_" + agm_agenda_id).html( data + edit_link );
                $('#myModal').modal('toggle');
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    });
});

//countDownInterval = setInterval(function(){ get_count_down(); }, 1000)
//clearInterval(countDownInterval);
function get_count_down (obj) {
    //console.log(obj.attr('data-count-down'));
    var count_down = parseInt(obj.attr('data-count-down'));

    if (count_down > 0) {
        count_down --;
        obj.attr('data-count-down',count_down);
        obj.attr('disabled','disabled');
        obj.html('Close Vote('+count_down+')');
    } else {
        obj.removeAttr('disabled');
        obj.html('Close Vote');
        clearInterval(countDownInterval);
    }
}

</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Agenda</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 msg">
                    <div class="col-xs-3">Agenda : </div>
                    <div class="col-xs-9">
                        <textarea class="form-control" rows="3" name="agenda_resol_popup" id="agenda_resol_popup"></textarea>
                        <input type="hidden" id="agm_agenda_id_popup" name="agm_agenda_id_popup">
                    </div>
                </div>
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn_update_bms_agm_agenda" data-value="update" id="">Update</button> &ensp;
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>