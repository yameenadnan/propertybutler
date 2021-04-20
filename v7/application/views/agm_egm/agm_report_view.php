<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler |' ;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_media_query.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_custom_styles.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<style>
.pin_div > button { font-size:28px !important }
.result_div > div.page-header > h3 { color:green; } 
.result_div>div>div  { font-size:18px !important; } 
.result_div { margin-top:5px;padding:5px;border: 1px solid #666;border-radius: 5px; }
.page-header { margin-bottom: 0px !important; }
h3 { margin-top: 10px !important; margin-bottom: 5px; }

</style>
<body class="hold-transition skin-blue ">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area" style="background-color: #fff;margin-left: 0px;">
   
    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <div class="box box-primary" style="border-top: none;">    
        <div class="col-md-12 col-xs-12" style="padding-bottom: 10px;padding-left:10px !important"><h1>Minutes Of Meeting of  
        <?php
        
        if(!empty($agm['agm_number'])) {
            
            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            if (($agm['agm_number'] %100) >= 11 && ($agm['agm_number']%100) <= 13)
                echo $agm['agm_number']. 'th';
            else
                echo $agm['agm_number']. $ends[$agm['agm_number'] % 10];
        }      
        echo ['agm_type'] == 2 ? ' Extraordinary ' : ' Annual ';?>  General Meeting  </h1></div>
        <div class="box-body">
            
                    <div class="col-md-12 col-xs-12 no-padding" >
                        <div class="col-md-12 col-xs-12 no-padding" style="margin-bottom: 15px;">
                            <div class="col-md-6 col-xs-6 no-padding">
                                <div class="col-md-12 col-xs-12 no-padding"><label>Date :</label> <?php echo !empty($agm['agm_date']) && $agm['agm_date'] != '0000:00:00' ? date('d-m-Y',strtotime($agm['agm_date'])) : '';?></div>                        
                                <div class="col-md-12 col-xs-12 no-padding"><label>Time :</label> 10:00 AM</div>
                                <div class="col-md-12 col-xs-12 no-padding"><label>Venue :</label> Multi Purpose Hall, Clubhouse, Bukit Gita Bayu</div>
                            </div>
                            
                            <div class="col-md-6 col-xs-6">
                                <div class="col-md-12 col-xs-12 no-padding"><label>Property Name :</label> <?php echo $agm['property_name'];?></div>                        
                                <div class="col-md-12 col-xs-12 no-padding"><label>Term :</label> <?php echo $agm['agm_term'];?> </div>
                            </div>
                        </div>
                    </div>
                
                <!-- AGM -->                
                
               <?php if(!empty($agm_id)) { ?>
               
                <?php                
                
                    if(!empty($agm_details)) { ?>
                    
                    <div class="col-md-12 no-padding" style="margin: 15px 0;">
                        <div class="col-md-5 col-sm-5 col-xs-5 no-padding" >
                            <label>VOTE</label> &ensp;&ensp;&ensp; 
                            <?php echo $agm['vote_by'] == 2 ? 'BY POLL' : 'BY SHOW OF HANDS';?>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-7 no-padding text-right" >
                            Number Of Eligible Voters: <b><?php echo $no_of_ev['cnt'];?> </b> &ensp;| &ensp; Number Of Attendees: <b><?php echo $no_of_attendees['cnt'];?></b> 
                        </div>
                   </div>
                    
                    <?php
                        
                        foreach ($agm_details as $key => $val) { 
                            
                            echo '<input type="hidden" value="'.$val['agm_agenda_id'].'" name="agenda_id['.$val['agm_agenda_id'].']" />';
                            echo '<input type="hidden" value="'.$val['resolu_type'].'" name="resolu_type['. $val['agm_agenda_id'].']" />';
                            ?>
                            <div id="div_<?php echo $val['agm_agenda_id'];?>" class="col-md-12 col-xs-12" style="margin: 15px 0 0 0;border: 1px solid #999;border-radius: 5px;">
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
                                        <label >Agenda <?php echo $val['seq_no'] . $resol_type ;?></label>
                                        <div style="font-size: 18px !important;"><?php echo $val['agenda_resol'];?></div>                                 
                                    </div>
                                </div>
                                
                            <?php switch ($val['resolu_type']) {
                                case 1: ?>
                                                    
                    <div  class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom: 15px;">
                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                            <div class="col-md-12 col-xs-12 no-padding " _style="padding:5px 0;border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12 no-padding nomination_div">
                                    <div class="col-md-4 col-xs-4"><label>Nominee</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Proposer</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Seconder</label></div>
                                    
                                    <?php if(empty($pc_nominee[$val['agm_agenda_id']])) { ?>
                                    
                                    <?php } else { 
                                        //echo "<pre>";print_r($pc_nominee);echo "</pre>";
                                        foreach ($pc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] != 0) { ?>
                                                
                                            <div class="col-md-12 col-xs-12 no-padding"  style="padding-top:10px !important">
                                            <div class="col-md-4 col-xs-4">
                                                <?php echo $val2['nominee_name']; ?>
                                            
                                                </div>
                                        <div class="col-md-4 col-xs-4">
                                            <?php echo $val2['proposer']; ?>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <?php echo $val2['seconder']; ?>
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
                                            
                                                <div  class="col-md-12 col-xs-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="pc_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['pc_id'];?>" />
                                                    <div class="col-md-4 col-xs-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['proposer']; ?>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['seconder']; ?>
                                                    </div>
                                                    
                                                </div>
                                            
                                            
                             <?php           }
                                        }
                                    } ?>
                                
                            </div>
                            <?php if(empty($pc_result[$val['agm_agenda_id']])) { ?>
                            
                            <?php } else { ?>
                            <div class="col-md-12 col-xs-12 result_div" _style="padding:5px 0;margin-top:10px;border: 1px solid #999;border-radius: 5px;display: <?php echo !empty($pc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>      
                                  </div>
                                  <?php if(!empty($pc_result[$val['agm_agenda_id']])) echo $pc_result[$val['agm_agenda_id']]; ?> 
                            
                            </div>
                            
                            
                            
                            
                            <!--div class="col-md-12 col-xs-12" _style="padding:5px 0;margin-top:10px;border: 1px solid #999;border-radius: 5px;display: <?php echo !empty($pc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result Details:</h3>      
                                  </div>
                                  <?php 
                                  //echo "<pre>";print_r($val);echo "</pre>"; exit;
                                   /*foreach ($pc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                    if($val2['nominee'] != 0 && !empty($val['agm_agenda_id'])) { 
                                        $pc_res = $this->bms_agm_egm_model->get_pc_result_details ($val2['nominee'],$val['agm_agenda_id'],$agm['vote_by']);
                                        ?>
                                        <?php if(!empty($pc_res)) { ?>
                                        <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;">Vote For : <b><?php echo $val2['nominee_name']; ?></b></div>      
                                  </div>
                                        <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            
                                                <?php
                                                    $total = $agm['vote_by'] == 2 ? array_sum(array_column($pc_res,'vote_cnt')) : 0;
                                                    foreach ($pc_res as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['nom_unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['nom_owner_name']; ?>
                                                        </div>
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                   ?>                                        
                                            </div>                                      
                                        
                                        <?php  
                                                      
                                              }
                                                
                                    }                                    
                                    
                                  } 
                                  if(!empty($val['agm_agenda_id'])) { 
                                    
                                    $abstrain = $this->bms_agm_egm_model->get_pc_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                  ?>
                                  <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;"><b>Abstrain</b></div>      
                                  </div>
                                  <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($abstrain)) { 
                                                $total = $agm['vote_by'] == 2 ? array_sum(array_column($abstrain,'vote_cnt')) : 0;
                                                    foreach ($abstrain as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div> 
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>                                               
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }  
                                              }
                                                ?>                                        
                                    </div>
                                  
                                  <?php 
                                  
                                  
                                  
                                  }*/
                                  ?> 
                            
                            </div-->
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
                        
                            <?php } else { ?>
                            <div class="col-md-12 col-xs-12 result_div" style="display: <?php echo !empty($vote_resol_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;">
                                <div class="page-header">
                                    <h3>Result :</h3>      
                                  </div>
                                  <?php if(!empty($vote_resol_result[$val['agm_agenda_id']])) echo $vote_resol_result[$val['agm_agenda_id']]; ?>
                            </div>
                            
                            
                            <!--div class="col-md-12 col-xs-12 "  >
                                <div class="page-header">
                                    <h3>Result Details:</h3>      
                                  </div>
                                  <?php 
                                  //echo "<pre>";print_r($val);echo "</pre>"; exit;
                                  /*for ($l = 1;$l <= 2;$l++) {
                                    if(!empty($val['agm_agenda_id'])) { 
                                        $pc_res = $this->bms_agm_egm_model->get_vote_resol_result_details ($l,$val['agm_agenda_id'],$agm['vote_by']);
                                        ?>
                                        <?php if(!empty($pc_res)) { ?>
                                        <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;">Vote For : <b><?php echo $l == 1 ? 'FOR' : 'AGAINST';?></b></div>      
                                  </div>
                                        <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            
                                                <?php
                                                    $total = $agm['vote_by'] == 2 ? array_sum(array_column($pc_res,'vote_cnt')) : 0;
                                                    foreach ($pc_res as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div>
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                    
                                                  ?>                                        
                                    </div>
                                        
                                        
                                        <?php     
                                              }
                                                
                                    }                                    
                                    
                                  }
                                  if(!empty($val['agm_agenda_id'])) { 
                                    
                                    $abstrain = $this->bms_agm_egm_model->get_vote_resol_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                  ?>
                                  <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;"><b>Abstrain</b></div>      
                                  </div>
                                  <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($abstrain)) { 
                                                $total = $agm['vote_by'] == 2 ? array_sum(array_column($abstrain,'vote_cnt')) : 0;
                                                    foreach ($abstrain as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div> 
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>                                               
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }  
                                              }
                                                ?>                                        
                                    </div>
                                  
                                  <?php 
                                  
                                  
                                  
                                  }*/
                                  ?> 
                            
                            </div-->
                            
                            
                            
                            <?php } ?>
                    </div>
                    
                    
                                <?php  
                                    
                                    break;
                                case 6:
                                ?>                               
                                
                                    <div class="col-md-12 col-xs-12" >
                                        <div class="col-md-12 col-xs-12 " style="padding: 15px;">
                                            <div class="col-md-8 col-xs-12 no-padding ">
                                                <div class="col-md-12 col-xs-12 no-padding nomination_div">
                                                    
                                                    <div class="col-md-5 col-xs-5"><label>Proposer</label></div>
                                                    <div class="col-md-5 col-xs-5"><label>Seconder</label></div>
                                                    
                                                    <div class="col-md-12 col-xs-12 no-padding">
                                                        
                                                        <div class="col-md-5 col-xs-6">
                                                            <?php echo $val2['proposer']; ?>
                                                        </div>
                                                        <div class="col-md-5 col-xs-6">
                                                            <?php echo $val2['seconder']; ?>
                                                        </div>                                       
                                                    </div>                                                    
                                                </div>                            
                                            </div>                            
                                        </div>
                                    </div>
                   
                                <?php         
                                    
                                    break;
                                case 7:?>
                    
                    <div class="col-md-12 col-xs-12" style="padding-top: 10px; padding-bottom:15px;">
                        <div class="col-md-12 no-padding nomination_full_div">
                            <div class="col-md-12 col-xs-12 no-padding ">
                                <div class="col-md-12 col-xs-12 no-padding nomination_div">
                                    <div class="col-md-4 col-xs-4"><label>Number</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Proposer</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Seconder</label></div>
                                    
                                    <?php if(empty($no_of_comm[$val['agm_agenda_id']])) { ?>
                                    
                                    <?php } else { 
                                        
                                        foreach ($no_of_comm[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['item'] != '0') { ?>
                                                
                                            <div class="col-md-12 col-xs-12 no-padding"  style="padding-top:10px !important">
                                                <div class="col-md-4 col-xs-4">
                                                    <?php echo $val2['item'];?>
                                                
                                                </div>
                                                <div class="col-md-4 col-xs-4">
                                                    <?php echo $val2['proposer']; ?>
                                                </div>
                                                <div class="col-md-4 col-xs-4">
                                                    <?php echo $val2['seconder']; ?>
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
                                            
                                                <div class="col-md-12 col-xs-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="propose_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['propose_id'];?>" />
                                                    <div class="col-md-4 col-xs-4">Nomination Close <input type="hidden"  name="number[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['proposer']; ?>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['seconder']; ?>
                                                    </div>
                                                    
                                                </div>
                                            
                                            
                             <?php           }
                                        }
                                    } ?>
                                
                            
                            </div>
                            <?php if(empty($no_of_comm_result[$val['agm_agenda_id']])) { ?>
                            
                            <?php } else { ?>
                            <div class="col-md-12 col-xs-12  result_div" style="padding-top:10px;display: <?php echo !empty($no_of_comm_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>      
                                  </div>
                                  <?php if(!empty($no_of_comm_result[$val['agm_agenda_id']])) echo $no_of_comm_result[$val['agm_agenda_id']]; ?> 
                            
                            </div>
                            
                            
                            
                                                        
                            <!--div class="col-md-12 col-xs-12 " style="display: <?php echo !empty($no_of_comm_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result Details:</h3>      
                                  </div>
                                  <?php 
                                  //echo "<pre>";print_r($val);echo "</pre>"; exit;
                                  /*foreach ($no_of_comm[$val['agm_agenda_id']] as $key2=>$val2) {
                                    if($val2['item'] != '0' && !empty($val['agm_agenda_id'])) { 
                                        //$pc_res = $this->bms_agm_egm_model->get_pc_result_details ($val2['nominee'],$val['agm_agenda_id'],$agm['vote_by']);
                                        $pc_res = $this->bms_agm_egm_model->get_no_of_comm_result_details ($val2['item'],$val['agm_agenda_id'],$agm['vote_by']);
                                        ?>
                                        <?php if(!empty($pc_res)) { ?>
                                        <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;">Vote For : <b><?php echo $val2['item']; ?></b></div>      
                                  </div>
                                        <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            
                                                <?php
                                                    $total = $agm['vote_by'] == 2 ? array_sum(array_column($pc_res,'vote_cnt')) : 0;
                                                    foreach ($pc_res as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div>
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }
                                                    ?>                                        
                                    </div>
                                        
                                        
                                        <?php 
                                                      
                                              }
                                                
                                    }                                    
                                    
                                  }
                                  if(!empty($val['agm_agenda_id'])) { 
                                    
                                    //$abstrain = $this->bms_agm_egm_model->get_pc_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                    $abstrain = $this->bms_agm_egm_model->get_no_of_comm_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                  ?>
                                  <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;"><b>Abstrain</b></div>      
                                  </div>
                                  <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($abstrain)) { 
                                                $total = $agm['vote_by'] == 2 ? array_sum(array_column($abstrain,'vote_cnt')) : 0;
                                                    foreach ($abstrain as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div> 
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>                                               
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }  
                                              }
                                                ?>                                        
                                    </div>
                                  
                                  <?php                                 
                                  
                                  
                                  } */
                                  ?> 
                            
                            </div-->
                            
                            
                            
                            
                                                                                    
                            <?php } ?>
                        </div>
                        
                    </div>    
                                <?php 
                                    
                                    break;
                                case 8: ?>                            
                                
                   
                    
                    <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                            <div class="col-md-12 col-xs-12 no-padding ">
                                <div class="col-md-12 col-xs-12 no-padding nomination_div">
                                    <div class="col-md-4 col-xs-4"><label>Nominee</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Proposer</label></div>
                                    <div class="col-md-4 col-xs-4"><label>Seconder</label></div>
                                    
                                    <?php if(empty($mc_nominee[$val['agm_agenda_id']])) { ?>
                                   
                                    <?php } else { 
                                        $namination = array('Poh It Pen','Lawrence Stephen GrantLapre','Kawaljit Maan',' Foong Yoke Ping','Ng Kar Im'); 
                                        foreach ($mc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                            if($val2['nominee'] != 0) { ?>
                                                
                                            <div class="col-md-12 col-xs-12 no-padding"  style="padding-top:10px !important">
                                            <div class="col-md-4 col-xs-4">
                                            <?php echo $namination[$key2]; ?>
                                                </div>
                                        <div class="col-md-4 col-xs-4">
                                            <?php echo $val2['proposer']; ?>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <?php echo $val2['seconder']; ?>
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
                                            
                                                <div class="col-md-12 col-xs-12 no-padding nomination_close_div" style="padding-top: 10px !important;">
                                                    <input type="hidden" name="mc_nomin_id[<?php echo $val['agm_agenda_id'];?>][]" value="<?php echo $val2['mc_nomin_id'];?>" />
                                                    <div class="col-md-4 col-xs-4">Nomination Close <input type="hidden"  name="naminee[<?php echo $val['agm_agenda_id'];?>][]" value="0" /></div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['proposer']; ?>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4">
                                                        <?php echo $val2['seconder']; ?>
                                                    </div>
                                                    
                                                </div>
                                            
                                            
                             <?php           }
                                        }
                                    } ?>
                                
                            </div>
                            <?php if(empty($mc_result[$val['agm_agenda_id']])) { ?>
                            
                            <?php } else { ?>
                            <div class="col-md-12 col-xs-12 result_div" style="padding-top:10px;display: <?php echo !empty($mc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result :</h3>      
                                  </div>
                                  <?php if(!empty($mc_result[$val['agm_agenda_id']])) echo $mc_result[$val['agm_agenda_id']]; ?> 
                            
                            </div>
                            
                            
                            
                            
                            <!--div class="col-md-12 col-xs-12 " style="display: <?php echo !empty($mc_result[$val['agm_agenda_id']]) ? 'block' : 'none';?>;" >
                                <div class="page-header">
                                    <h3>Result Details:</h3>      
                                  </div>
                                  <?php 
                                  //echo "<pre>";print_r($val);echo "</pre>"; exit;
                                  /*foreach ($mc_nominee[$val['agm_agenda_id']] as $key2=>$val2) {
                                    if($val2['nominee'] != 0 && !empty($val['agm_agenda_id'])) { 
                                        //$pc_res = $this->bms_agm_egm_model->get_pc_result_details ($val2['nominee'],$val['agm_agenda_id'],$agm['vote_by']);
                                        $pc_res = $this->bms_agm_egm_model->get_mc_result_details ($val2['nominee'],$val['agm_agenda_id'],$agm['vote_by']);
                                        ?>
                                        <?php if(!empty($pc_res)) { ?>
                                        <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;">Vote For : <b><?php echo $val2['nominee_name']; ?></b></div>      
                                  </div>
                                        <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            
                                                <?php
                                                    $total = $agm['vote_by'] == 2 ? array_sum(array_column($pc_res,'vote_cnt')) : 0;
                                                    foreach ($pc_res as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['nom_unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['nom_owner_name']; ?>
                                                        </div>
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>
                                                
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php } ?>                                        
                                    </div>
                                        
                                        
                                        <?php                                                    
                                                      
                                              }
                                                
                                    }                                    
                                    
                                  }
                                  if(!empty($val['agm_agenda_id'])) { 
                                    
                                    //$abstrain = $this->bms_agm_egm_model->get_pc_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                    $abstrain = $this->bms_agm_egm_model->get_mc_abstains_details ($agm['agm_id'],$val['agm_agenda_id'],$agm['vote_by']);
                                  ?>
                                  <div class="page-header col-xs-12" style="padding-top: 10px;">
                                    <div style="font-size:16px;"><b>Abstrain</b></div>      
                                  </div>
                                  <div class="col-md-12 col-xs-12" style="padding-top: 5px;">
                                        <div class="col-md-12 col-xs-12 no-padding nomination_full_div">
                                            
                                            <div class="col-md-4 col-xs-4"><label>Unit No</label></div>
                                            <div class="col-md-6 col-xs-6"><label>Name</label></div>
                                            <?php if($agm['vote_by'] == 2) { ?>
                                            <div class="col-md-2 col-xs-2"><label>Share Unit</label></div>
                                            <?php } ?>
                                            
                                        </div>
                                            <?php if(!empty($abstrain)) { 
                                                $total = $agm['vote_by'] == 2 ? array_sum(array_column($abstrain,'vote_cnt')) : 0;
                                                    foreach ($abstrain as $key3=>$val3) { ?>                                    
                                                    <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                        <div class="col-md-4 col-xs-4">
                                                        <?php echo $val3['unit_no']; ?>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                        <?php echo $val3['owner_name']; ?>
                                                        </div> 
                                                        <?php if($agm['vote_by'] == 2) { ?>
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $val3['vote_cnt']; ?>
                                                        </div>
                                                        <?php } ?>                                               
                                                    </div>
                                            <?php
                                                    }
                                                    
                                                    if($agm['vote_by'] == 2 && !empty($total)) { ?>
                                                    <div class="col-md-12 col-xs-12 no-padding" style="border-top:1px solid #999;padding-top: 10px !important;">
                                                        <div class="col-md-10 col-xs-10">&nbsp;</div>                                                        
                                                        <div class="col-md-2 col-xs-2">
                                                        <?php echo $total; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php }  
                                              }
                                                ?>                                        
                                    </div>
                                  
                                  <?php 
                                  } */
                                  ?> 
                            
                            </div-->
                            
                            
                            
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
                            <label >AGM Minutes</label><br />
                            <?php echo !empty($val['minutes']) ? $val['minutes'] : ' - ';?>                                
                        </div>
                    </div>
               </div>       
                            
               <?php    
                        }
                        ?>
                    <div class="col-md-12 col-xs-12 no-padding" style="margin: 25px 0 10px 0;">
                        <div class="col-md-3 col-xs-3 no-padding" >
                            <label>Minuted By: </label> 
                            
                        </div>
                        <div class="col-md-3 col-xs-3" >
                             _______________________________________
                        </div>
                   </div>
                   <div class="col-md-12 col-xs-12 no-padding" style="margin: 10px 0 25px 0 ;">
                        <div class="col-md-3 col-xs-3 no-padding" >
                            <label>Confirmed By: <br />  
                            (Presiding Chairman)</label>
                        </div>
                        <div class="col-md-3 col-xs-3 " >
                             _______________________________________
                        </div>
                   </div>
                                
                    <?php
                        
                    }
                
                ?>  
            
            <?php } ?>
            </div><!-- /.box-body -->
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>

<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

$(document).ready(function () {
    window.print();
});
</script>
</body>
</html>