<?php include_once('header.php');?>
<?php include_once('sidebar.php');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!-- &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/sop_list');?>" >SOP List</a-->
        
        <?php
                if($_SESSION['bms']['user_type'] == 'staff') {
        ?>
        <!-- &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/new_sop');?>" >New SOP</a-->
        <?php } // user type staff ?> 
        
        <?php if($_SESSION['bms']['user_type'] == 'staff' && in_array('6',$_SESSION['bms']['access_mod'])) {
        ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_sop/entry_list');?>" >SOP Entry</a>
        <?php } ?>
        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
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
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
              <div class="box-body" style="padding-bottom: 0px;">
                  <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Property Name</label>
                            <select class="form-control" id="property_id" name="property_id">
                                <option value="">Select Property</option>
                                <?php 
                                    foreach ($properties as $key=>$val) { 
                                        $selected = isset($property_id) && trim($property_id) != '' && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';  
                                        echo "<option value='".$val['property_id']."' ".$selected." >".$val['property_name']."</option>";
                                    } ?> 
                            </select>
                        </div>
                    </div>
                                        
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                          <label>Routine Task Title</label>
                          <select id="assign_to_desi_id" name="assign_to_desi_id" class="form-control">
                            <option value="">Select</option>                                 
                          </select>
                        </div>
                    </div> 
                    
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group">
                          <label>Routine Task</label>
                          <select id="sop_id" name="sop_id" class="form-control">
                            <option value="">Select</option>                                 
                          </select>
                        </div>
                    </div>  
                    
                    <div class="col-md-3 col-xs-5">
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
                
                    <div class="col-md-3 col-xs-5">
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
                    <div class="col-md-2 col-xs-2 no-padding" style="margin-top:25px">
                        <a href="javascript:;" role="button" class="btn btn-primary search_filter"><i class="fa fa-search"></i></a>
                    </div>                               
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              <div style="clear: both;height: 0px;"></div>
              
              <div class="box-body" id="report_content">
              
              </div>
            
            
            
              
              
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- bootstrap datepicker -->
  
<?php include_once('footer.php');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    if($('#property_id').val() != '') {
        loadSopTitle($('#property_id').val());
    } 
    $('#property_id').change(function () {
          if($('#property_id').val() != '') {
            loadSopTitle($('#property_id').val());
        }  
    }); 
    
    $('#assign_to_desi_id').change(function () {
          if($('#assign_to_desi_id').val() != '') {
            loadSop($('#property_id').val(),$('#assign_to_desi_id').val());
        }  
    }); 
    
    $('.search_filter').click(function () {
        loadContent();   
    });   
});

function loadSopTitle (property_id) {
    
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_sop/get_sop_title');?>',
        data: {'property_id':property_id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
        success: function(data) {  
            
            var str = '<option value="">Select</option>'; 
            if(data.length > 0) {                    
                $.each(data,function (i, item) {
                    str += '<option value="'+item.assign_to+'">SOP for '+item.desi_name+'</option>';
                });
            }
            $('#assign_to_desi_id').html(str);
            $('#sop_id').html('<option value="">Select</option>');
            $("#content_area").LoadingOverlay("hide", true);
            
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
    
}

function loadSop (property_id) {
    if($('#property_id').val() != '' && $('#assign_to_desi_id').val() != '') {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sop/get_sop_for_desi_id');?>',
            data: {'property_id':$('#property_id').val(),'desi_id':$('#assign_to_desi_id').val()},
            datatype:"json", // others: xml, json; default is html
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {  
                
                var str = '<option value="all">All</option>'; 
                if(data.length > 0) {                    
                    $.each(data,function (i, item) {
                        str += '<option value="'+item.sop_id+'">'+item.sop_name+'</option>';
                    });
                }
                $('#sop_id').html(str);                
                $("#content_area").LoadingOverlay("hide", true);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
}

function loadContent () {
    if($('#property_id').val() != '' && $('#assign_to_desi_id').val() != '' && $('#sop_id').val() != '') {
        $.ajax({
            type:"post",
            async: true,
            url: '<?php echo base_url('index.php/bms_sop/get_sop_report');?>',
            data: {'property_id':$('#property_id').val(),'desi_id':$('#assign_to_desi_id').val(),'sop_id':$('#sop_id').val(),'start_date':$('#start_date').val(),'end_date':$('#end_date').val()},
            beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
            success: function(data) {                
                
                $('#report_content').html(data);                
                $("#content_area").LoadingOverlay("hide", true);
                
            },
            error: function (e) {
                $("#content_area").LoadingOverlay("hide", true);              
                console.log(e); //alert("Something went wrong. Unable to retrive data!");
            }
        });
    }
    
    
}

$(function () {    
    //Date picker
    $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
})

</script>