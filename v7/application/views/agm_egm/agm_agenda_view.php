<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
    
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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_agm_egm/set_agm_agenda');?>" method="post" >
                  
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
                                foreach ($agms as $key=>$val) { 
                                    $selected = isset($agm_id) && trim($agm_id) != '' && trim($agm_id) == $val['agm_id'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$val['agm_id']."' ".$selected." >".$val['agm_term']."</option>";
                                } ?> 
                        </select>
                    </div>
                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary filter"><i class="fa fa-search"></i></a>
                    </div>
                    </div>
                    </div>
                
                <!-- AGM -->                
                <?php 
                $seq_no = 0;
                
                if(!empty($agm_id)) { ?>
                
                <div class="row agenda_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                            <div class="row add_reminder_bottom" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-inline">
                                            <label>Introduction&ensp;<input type="hidden" name="agm[seq_no][0]" value="0" /></label>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="agm[agenda_resol][0]" rows="4" id="resol_0"><?php echo !empty($agendas[0]['agenda_resol']) ? str_replace('<br />',"",$agendas[0]['agenda_resol']) : '';?></textarea>
                                            <input type="hidden" name="agm[agm_agenda_id][0]" id="agm_agenda_id_0" value="<?php echo !empty($agendas[0]['agm_agenda_id']) ? $agendas[0]['agm_agenda_id'] : '';?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.row -->
                        </div> <!-- /.row -->
                    
                    <div class="row agenda_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                            <div class="row add_reminder_bottom" style="padding-top: 15px;">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-inline">
                                            <label >Agenda&ensp;<input type="input" name="agm[seq_no][<?php echo ++$seq_no;?>]" value="<?php echo !empty($agendas[$seq_no]['seq_no']) ? $agendas[$seq_no]['seq_no'] : '';?>" class="form-control" placeholder="Enter Sequence No" /></label>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="agm[agenda_resol][<?php echo $seq_no;?>]" rows="4" id="resol_1"><?php echo !empty($agendas[$seq_no]['agenda_resol']) ? str_replace('<br />',"",$agendas[$seq_no]['agenda_resol']) : '';?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 reminder_div_<?php echo $seq_no;?>_0">
                                    <div class="col-md-11 col-xs-10">
                                        <div class="col-md-12 col-xs-12 no-padding">

                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="ordi_resol_<?php echo $seq_no;?>" value="2" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 2 ? 'checked="checked"' : '';?>><b>Ordinary Resol</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="spl_resol_<?php echo $seq_no;?>" value="3" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 3 ? 'checked="checked"' : '';?>><b>Special Resol</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="comp_resol_<?php echo $seq_no;?>" value="4" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 4 ? 'checked="checked"' : '';?>><b>Comprehensive Resol</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="unani_resol_<?php echo $seq_no;?>" value="5" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 5 ? 'checked="checked"' : '';?>><b>Unanimous Resol</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="prop_second_<?php echo $seq_no;?>" value="6" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 6 ? 'checked="checked"' : '';?>><b>Proposer&amp;Seconder</b>
                                            </label>
                                        </div>
                                        <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="pres_chair_<?php echo $seq_no;?>" value="1" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 1 ? 'checked="checked"' : '';?>><b>Presiding Chairman</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="no_of_mc_<?php echo $seq_no;?>" value="7" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 7 ? 'checked="checked"' : '';?>><b>Determine Resol</b>
                                            </label>
                                            &ensp;&ensp;
                                            <label class="radio-inline">
                                                <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="elect_mc_<?php echo $seq_no;?>" value="8" <?php echo !empty($agendas[$seq_no]['resolu_type']) && $agendas[$seq_no]['resolu_type'] == 8 ? 'checked="checked"' : '';?>><b>To Elect Committee</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" >
                                        &nbsp;
                                    </div>
                                </div>
                            </div> <!-- /.row -->
                        </div> <!-- /.row -->
                    
                    
                    

                        

                        
                    <?php 
                        $cnt = count ($agendas);
                        if($cnt > 2) {
                        for( $i=2;$i < $cnt; $i++ ) { ?>
                            
                                <div class="row agenda_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">
                                    <div class="row add_reminder_bottom" style="padding-top: 15px;">
                                        <div class="col-md-12 col-xs-12">

                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-inline">
                                                    <label >Agenda&ensp;<input type="input" name="agm[seq_no][<?php echo ++$seq_no;?>]" value="<?php echo !empty($agendas[$i]['seq_no']) ? $agendas[$i]['seq_no'] : '';?>" class="form-control" placeholder="Enter Sequence No" /></label>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="agm[agenda_resol][<?php echo $seq_no;?>]" rows="4" id="resol_1"><?php echo !empty($agendas[$i]['agenda_resol']) ? str_replace('<br />',"",$agendas[$i]['agenda_resol']) : '';?></textarea>
                                                    <input type="hidden" name="agm[agm_agenda_id][<?php echo $seq_no;?>]" id="agm_agenda_id_1" value="<?php echo !empty($agendas[$i]['agm_agenda_id']) ? $agendas[$i]['agm_agenda_id'] : '';?>"/>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-12 col-xs-12 reminder_div_<?php echo $seq_no;?>_0">
                                            <div class="col-md-11 col-xs-10">
                                                <div class="col-md-12 col-xs-12 no-padding">

                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="ordi_resol_<?php echo $seq_no;?>" value="2" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 2 ? 'checked="checked"' : '';?>><b>Ordinary Resol</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="spl_resol_<?php echo $seq_no;?>" value="3" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 3 ? 'checked="checked"' : '';?>><b>Special Resol</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="comp_resol_<?php echo $seq_no;?>" value="4" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 4 ? 'checked="checked"' : '';?>><b>Comprehensive Resol</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="unani_resol_<?php echo $seq_no;?>" value="5" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 5 ? 'checked="checked"' : '';?>><b>Unanimous Resol</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="prop_second_<?php echo $seq_no;?>" value="6" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 6 ? 'checked="checked"' : '';?>><b>Proposer&amp;Seconder</b>
                                                    </label>

                                                </div>
                                                <div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="pres_chair_<?php echo $seq_no;?>" value="1" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 1 ? 'checked="checked"' : '';?>><b>Presiding Chairman</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="no_of_mc_<?php echo $seq_no;?>" value="7" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 7 ? 'checked="checked"' : '';?>><b>Determine Resol</b>
                                                    </label>
                                                    &ensp;&ensp;
                                                    <label class="radio-inline">
                                                        <input type="radio" name="agm[resolu_type][<?php echo $seq_no;?>]" id="elect_mc_<?php echo $seq_no;?>" value="8" <?php echo !empty($agendas[$i]['resolu_type']) && $agendas[$i]['resolu_type'] == 8 ? 'checked="checked"' : '';?>><b>To Elect Committee</b>
                                                    </label>

                                                </div>
                                            </div>



                                            <div class="col-md-1 col-xs-2 text-right"><button type="button" class="btn btn-danger del_btn">Delete</button></div>
                                        </div>


                                    </div> <!-- /.row -->

                                </div> <!-- /.row -->
                            <?php 
                            }
                        }
                    ?>
               
                <div class="row add_agm_before_div">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-success add_agm_btn" id="add_agm_btn" value="0" data-value="<?php echo $seq_no;?>" aria-invalid="false">Add More</button>
                    </div>
               
                </div>
                
                
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-right" style="padding-top: 15px !important;">
                        <input class="btn btn-primary" value="Save" type="submit">
                    </div>
                </div>
                    
                
              </div>
              <!-- /.box-body -->
              <!--div class="row" style="text-align: right;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button> &ensp;
                    <button type="Reset" id="reset_btn"  class="btn btn-default">Reset</button> &ensp;&ensp;
                  </div>
                </div>
              </div-->
            <?php } ?>  
            
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

var designations = $.parseJSON('<?php echo !empty($designations) ? json_encode($designations) : json_encode(array());?>');
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);   
    
    
    $('#property_id').change(function () {
        if($('#property_id').val() != '') {
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_agenda');?>?property_id="+$('#property_id').val();
        }           
    });
    
    $('.filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_agenda');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
    });
    
    $('#reset_btn').click(function (){
       window.location.reload(); return false; 
    }); 
    
    $('.add_agm_btn').click(function () {        
        add_agenda();
        //$(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });   
    
    $('.del_btn').bind('click',function (){
        $(this).parents('div.agenda_div').remove();
        arrange_sequence ();
    });
         
    var seq_no = <?php echo ++$seq_no;?>;
    function add_agenda () {
        
        var str = '<div class="row agenda_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">';
        str += '<div class="row add_reminder_bottom" style="padding-top: 15px;">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="form-inline">';
        str += '<label >Agenda&ensp;<input type="input" name="agm[seq_no]['+seq_no+']" value="" class="form-control" placeholder="Enter Sequence No" /></label>';
        str += '</div>';
        str += '<div class="form-group">';        
        //str += '<div class="form-group">';
        //str += '<label>Resolution <span class="seq_no_cls" id="seq_no_'+seq_no+'">'+seq_no+'<input type="hidden" name="agm[seq_no]['+seq_no+']" /></span></label>';        
        str +='<textarea class="form-control" name="agm[agenda_resol]['+seq_no+']" rows="4" id="agenda_resol_'+seq_no+'"></textarea>';
        str += '<input type="hidden" name="agm[agm_agenda_id]['+seq_no+']" id="agm_agenda_id_'+seq_no+'" value="">';
        str += '</div>';
        str += '</div>';
        str += '</div>';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="col-md-11 col-xs-10">';
        str += '<div class="col-md-12 col-xs-12 no-padding">';
        
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="ordi_resol_'+seq_no+'" value="2"><b>Ordinary Resol</b>';
        str += '</label>&ensp;&ensp;';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="spl_resol_'+seq_no+'" value="3" ><b>Special Resol</b>';
        str += '</label>&ensp;&ensp;';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="comp_resol_'+seq_no+'" value="4"><b>Comprehensive Resol</b>'; 
        str += '</label>&ensp;&ensp;';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="unani_resol_'+seq_no+'" value="5" ><b>Unanimous Resol</b>';
        str += '</label>&ensp;&ensp;';        
        
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="prop_second_'+seq_no+'" value="6"><b>Proposer&amp;Seconder</b>';
        str += '</label>&ensp;&ensp;&ensp;';
        str += '</div>';
        
        str += '<div class="col-md-12 col-xs-12 no-padding" style="padding-top: 10px !important;">';                                      
        
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="pres_chair_'+seq_no+'" value="1"><b>Presiding Chairman</b>';
        str += '</label>&ensp;&ensp;&ensp;';
        
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="no_of_mc_'+seq_no+'" value="7"><b>Determine Resol</b>';
        str += '</label>&ensp;&ensp; ';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="agm[resolu_type]['+seq_no+']" id="elect_mc_'+seq_no+'" value="8"><b>To Elect Committee</b>';
        str += '</label>';
        str +='</div>';
        str += '</div>';
        
        str += '<div class="col-md-1 col-xs-2 text-right">';
        str +='<button type="button" class="btn btn-danger del_btn">Delete</button>';
        str += '</div>';
        str += '</div>';
        str += '</div> <!-- /.row -->';
        str += '</div>';
        
        seq_no++;
        
        $('.add_agm_before_div').before(str);
        
        arrange_sequence ();
        
        $('.del_btn').unbind('click');
        $('.del_btn').bind('click',function (){
            $(this).parents('div.agenda_div').remove();
            arrange_sequence ();
        });    
        
    }
});

function arrange_sequence () {
    /*$('.seq_no_cls').each(function (i) {
        console.log($(this).attr('id'));
        var id_full = $(this).attr('id');
        var id_no = id_full.split('_').pop();
        //console.log(id_no);
        $(this).html((i+1)+'<input type="hidden" name="agm[seq_no]['+(i+1)+']" value="'+(i+1)+'" />');
        //$('.seq_no_'+id_no).attr('name',';
        $('#agenda_resol_'+id_no).attr('name','agm[agenda_resol]['+(i+1)+']');
        $('#agm_agenda_id_'+id_no).attr('name','agm[agm_agenda_id]['+(i+1)+']');
        $('#pres_chair_'+id_no).attr('name','agm[resolu_type]['+(i+1)+']');
        $('#dir_vote_'+id_no).attr('name','agm[resolu_type]['+(i+1)+']');
        $('#prop_second_'+id_no).attr('name','agm[resolu_type]['+(i+1)+']');
        //$('#spec_resol_'+id_no).attr('name','agm[special_resol]['+(i+1)+']');   
                            
    });*/
}

</script>