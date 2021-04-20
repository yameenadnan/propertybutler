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
            <form role="form" id="sop_new" action="<?php echo base_url('index.php/bms_sop/new_sop_submit');?>" method="post" >
                  
                <div class="box-body">                    
                  
                    <div class="row">
                    <?php 
                     
                    if($_SESSION['bms']['user_type'] == 'staff' && in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi'))) { ?> 
                        
                    
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Property Name</label>
                                <select name="property_id" id="property_id" class="form-control">                             
                                <option value="all">All</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {
                                        $selected = '';
                                        if(isset($_GET['property_id'])) {
                                            $selected = isset($_GET['property_id']) && $_GET['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';
                                        } else if(isset($_SESSION['bms_default_property'])) {
                                            $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                        }
                                        //$selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['property_id'] ? 'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                            </div>
                        </div>
                                            
                        <div class="col-md-3 col-xs-6">
                            <div class="form-group">
                              <label>Staff Name</label>
                              <select id="staff_id" name="staff_id" class="form-control">
                                <option value="">Select</option>                                 
                              </select>
                            </div>
                        </div> 
                    <?php } else { ?>
                        <input type="hidden" name="property_id" id="property_id" value="0" />
                        <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $_SESSION['bms']['staff_id'];?>" />
                    <?php } ?>    
                        <div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>Start Date </label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="start_date" id="start_date" type="text"  value="<?php echo date('d-m-Y');?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                    
                        <div class="col-md-2 col-xs-4">
                            <div class="form-group">
                                <label>End Date </label>
                
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input class="form-control pull-right datepicker" name="end_date" id="end_date" type="text"  value="<?php echo date('d-m-Y');?>" />
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="margin-top:25px">
                            <a href="javascript:;" role="button" class="btn btn-primary attend_filter"><i class="fa fa-search"></i></a>
                        </div>
                        <div class="col-md-1 col-xs-2 export_excel" style="margin-top:25px;display:none;">
                            <a href="javascript:;" role="button" class="btn btn-primary download_excel"><i class="fa fa-download"></i></a>
                        </div>
                    
                                                                                   
                    </div>  
                    
                    <div class="row report_content"> </div>
                                     
                </div>
              <!-- /.box-body -->
            
            </form>
        </div> <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Modal -->
<div id="attenModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Attendance Photo</h4>
      </div>
      <div class="modal-body">
        
        <div class="col-xs-12 atten_msg">
            
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

<?php $this->load->view('footer');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    // On property name change
    $('#property_id').change(function () {
        loadStaffs ();        
    });
    
    if('<?php echo in_array($_SESSION['bms']['designation_id'],$this->config->item('attend_rep_view_all_access_desi')) ? '1' : '0';?>' =='1') {
        loadStaffs ();  
    }
    
    $('.attend_filter').click(function () {
        $('#start_date').val($('#start_date').val().replace(/^\s+|\s+$/g,""));
        if($('#start_date').val() == '') {
            alert('Please choose Start Date'); $('#start_date').focus(); 
            return false;
        }
        $('#end_date').val($('#end_date').val().replace(/^\s+|\s+$/g,""));
        if($('#end_date').val() == '') {
            alert('Please choose End Date'); $('#end_date').focus();
            return false;
        }
        
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_attendance/get_staff_attendance');?>', // Reusing the same function from task creation
            data: {'start_date':$('#start_date').val(),'end_date':$('#end_date').val(),'property_id':$('#property_id').val(),'staff_id':$('#staff_id').val(),'act_type':'html'},
            //datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show"); }, //
            success: function(data) {
                //console.log(data);
                $('.report_content').html(data);
                //$('#sop_ov_desi').html(str2); 
                //loadOverSeeingJMB (property_id);             
                $("#content_area").LoadingOverlay("hide", true);
                $('.atten_img').unbind('click');
                $('.atten_img').bind("click",function () {
                    //console.log($(this).attr('data-date'));
                    var str = '<img src="../../bms_uploads/atten_captures/'+$(this).attr('data-date')+'/'+$(this).attr('data-value')+'" class="img-responsive center-block"/>';
                    str += '<br />&ensp;&ensp;&ensp;&ensp;<b>'+$(this).attr('data-att-type') +' : </b>'+ $(this).attr('data-att-time');
                    //data-att-type
                    $('.atten_msg').html(str);
                    $('#attenModal').modal({show:true});       
                });
                
                $('.export_excel').css('display','block');
                
                
            },
            error: function (e) {  
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        }); 
              
    });
    
    $('.download_excel').click(function (){
        $(".download_excel").append('<form id="download_excel_frm" action="<?php echo base_url('index.php/bms_attendance/get_staff_attendance');?>" target="_blank" method="POST">');
   
        $(".download_excel form").append('<input type="hidden" name="start_date" value="'+$('#start_date').val()+'"/>');
        $(".download_excel form").append('<input type="hidden" name="end_date" value="'+$('#end_date').val()+'"/>');
        $(".download_excel form").append('<input type="hidden" name="property_id" value="'+$('#property_id').val()+'"/>');
        $(".download_excel form").append('<input type="hidden" name="staff_id" value="'+$('#staff_id').val()+'"/>');
        $(".download_excel form").append('<input type="hidden" name="act_type" value="download_excel"/>');
        $("#download_excel_frm").submit();
   
   
    });
    
    /*$('.atten_img').click(function(){
        console.log($(this).attr('data-date'));
        $('.atten_msg').html('<img src="../../bms_uploads/atten_captures/'+$(this).attr('data-date')+'/'+$(this).attr('data-value')+'"');
        $('#attenModal').modal({show:true});
      	
    });*/
    
});

function loadStaffs() {
    var property_id = $('#property_id').val();
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_attendance/get_staff_name_by_property');?>', // Reusing the same function from task creation
            data: {'property_id':property_id},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show"); }, //
            success: function(data) {
                var str = '<option value="all">All</option>'; 
                
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.staff_id+'">'+item.first_name+' '+item.last_name+'</option>';
                    });
                }
                $('#staff_id').html(str);   
                //$('#sop_ov_desi').html(str2); 
                //loadOverSeeingJMB (property_id);             
                $("#content_area").LoadingOverlay("hide", true);
            },
            error: function (e) {  
                $("#content_area").LoadingOverlay("hide", true);
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
}

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
})
</script>