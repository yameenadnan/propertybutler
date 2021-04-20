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
                    <?php if(!empty($agm_id)) { ?>
                    <div class="col-md-3 col-xs-12">
                        <input class="btn btn-primary" value="Save" type="submit">
                    </div>
                     <?php } ?>                    
                    
                    </div>
                    </div>
                
                <!-- AGM -->                
                <?php 
                
                  
               ?>  
               
               <div class="col-md-12 div_1" style="margin: 15px 0;">
                    <label>VOTE</label> &ensp;&ensp;&ensp; 
                    <label class="radio-inline">
                        <input type="radio" name="vote_by" value=""> BY SHOW OF HANDS
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="vote_by" value=""> BY POLL
                    </label>
               </div>
               
               <div class="col-md-12 div_2" style="margin: 15px 0;display: none;">
                    
               </div>                              
                
               
              </div>
              <!-- /.box-body -->
              <div class="row" style="text-align: right;"> 
                <div class="box-footer">
                    <div class="col-md-12">
                    <div class="col-md-6 text-left">
                        <button type="button" class="btn btn-primary pre_btn">Previous</button>                        
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-primary next_btn">Next</button>                        
                    </div>
                  </div>
                </div>
              </div>
            
            
            
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
            window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>?property_id="+$('#property_id').val();
        }           
    });
    
    $('.filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_process');?>?property_id="+$('#property_id').val()+"&agm_id="+$('#agm_id').val();
    });
    
    $('#reset_btn').click(function (){
       window.location.reload(); return false; 
    });
    
    $('.add_agm_btn').click(function () {        
        add_agm();
        //$(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.add_reminder_btn').click(function () {
        //console.log($(this).attr('data-parent') + ' = '+$(this).attr('data-value') + ' = '+$(this).val());
        add_reminder($(this).attr('data-parent'),$(this).attr('data-value'));
        $(this).attr('data-value',eval($(this).attr('data-value'))+1);
    });
    
    $('.del_agm_btn').bind('click',function (){
           $('.agm_div_'+$(this).attr('data-value')).remove(); 
        });
    
    $('.del_reminder_btn').bind('click',function (){
       $('.reminder_div_'+$(this).attr('data-parent')+'_'+$(this).attr('data-value')).remove(); 
    });
        
    var cnt = 2;
    function add_agm () {
        
        var str = '<div class="row agenda_div" style="margin: 0 0 15px 0; padding: 5px;border: 1px solid #999;border-radius: 5px;">';
        str += '<div class="row add_reminder_bottom" style="padding-top: 15px;">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="col-md-12 col-xs-12">';
        str += '<div class="form-group">';
        str += '<label>Title '+(cnt++)+' </label>';
        str += '<input type="text" name="agm[agm_descrip][1]" class="form-control" value="" placeholder="Enter Title" maxlength="250">';
        str += '<input type="hidden" name="agm[agm_master_id][1]" value="">                                ';
        str += '</div>';
        str += '</div>';
        str += '</div> ';
        str += '<div class="col-md-12 col-xs-12 reminder_div_1_0">';
        str += '<div class="col-md-8 col-xs-8">';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="property[calcul_base]" value="1"><b>Input+Vote</b>   ';
        str += '</label>&ensp;&ensp;';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="property[calcul_base]" value="2"><b>Direct Vote</b>    ';
        str += '</label>&ensp;&ensp;';
        str += '<label class="radio-inline">';
        str += '<input type="radio" name="property[calcul_base]" value="3"><b>Proposer&amp;Seconder</b>   ';
        str += '</label>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;';
        str += '<label style="font-weight:bold;"><input name="agm_reminder[1][email_jmb][0]" value="1" type="checkbox"> Special Resolution</label>';
        str += '</div>';
        str += '<div class="col-md-4 col-xs-4 text-right">';
        str +='<button type="button" class="btn btn-danger del_agm_btn" value="0" aria-invalid="false">Delete Agenda</button>';
        str += '</div>';
        str += '</div>';
        str += '</div> <!-- /.row -->';
        str += '</div>';
        
        
        
        $('.add_agm_before_div').before(str);
        
        
        
        $('.del_agm_btn').unbind('click');
        $('.del_agm_btn').bind('click',function (){
            console.log($(this).parents('div.agenda_div').html());
           $(this).parents('div.agenda_div').remove(); 
        });    
        
    }
    
    
});

$('#agm_type').change(function () {
    window.location.href="<?php echo base_url('index.php/bms_agm_egm/agm_master');?>?agm_type="+$('#agm_type').val();
    return false;
});
</script>