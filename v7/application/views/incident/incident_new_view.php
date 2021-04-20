<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/jquery.fileuploader-theme-thumbnails.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      <!--ol class="breadcrumb">
        <li><a href="<?php echo base_url('index.php/bms_dashboard/index');?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Submenu</li>
      </ol-->
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="incident_new" action="<?php echo base_url('index.php/bms_incident/new_incident_submit');?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12" style="padding: 0;">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                              <label for="property_id">Property Name *</label>
                                <select class="form-control" id="property_id" name="incident[property_id]">
                                <option value="">Select</option>
                                <?php 
                                    foreach ($properties as $key=>$val) {
                                        $selected = isset($_SESSION['bms_default_property']) && $_SESSION['bms_default_property'] == $val['property_id'] ?  'selected="selected" ' : '';
                                        echo "<option value='".$val['property_id']."' data-pname='".$val['property_name']."' ".$selected.">".$val['property_name']."</option>";
                                    } ?> 
                                  </select>
                    
                              <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                            </div>
                        </div>
                    </div>
                    
                    <div style="clear: both;height:1px"></div>
                    <?php if (!empty($incident)){ $val = $incident[0]; }  ?>
                      <input type="hidden" id="incident_id" name="incident_id" value="<?php echo isset($val['incident_id']) && $val['incident_id'] != '' ? $val['incident_id'] : '';?>">
                    <div class="col-md-12" style="padding: 0;">
                        <div class="col-md-6 col-xs-12" style="padding: 0;">

                            <div class="col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label>Incident Date *</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control pull-right datepicker" id="datepicker" name="incident[incident_date]" value="<?php echo isset($val['incident_date']) && $val['incident_date'] != '' && $val['incident_date'] != '0000-00-00' && $val['incident_date'] != '1970-01-01' ? date('d-m-Y', strtotime($val['incident_date'])) : '';?>" type="text">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Incident Time</label>
                                        <div class="input-group">
                                            <input type="text" name="incident[incident_time]" value="<?php echo isset($val['incident_time']) && $val['incident_time'] != '' ? date('h:i A', strtotime($val['incident_time'])) : '';?>" class="form-control timepicker">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div><!-- /.input group -->
                                    </div><!-- /.form group -->
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label>Incident location</label>
                                    <input type="text" name="incident[incident_location]" value="<?php echo isset($val['incident_location']) && $val['incident_location'] != '' ? $val['incident_location'] : '';?>" class="form-control" placeholder="Enter Incident Location" maxlength="200">
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                  <label>Incident detail</label>
                                  <textarea name="incident[details]" class="form-control" rows="2" placeholder="Enter Incident Details"><?php echo isset($val['details']) && $val['details'] != '' ? $val['details'] : '';?></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 ">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label><input name="incident[status]" <?php echo isset($val['status']) && $val['status'] == '1' ? 'checked="checked"' : ''; ?> value="1" type="checkbox">Solved</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea name="incident[remarks]" class="form-control" rows="2" placeholder="Enter Remarkss"><?php echo isset($val['remarks']) && $val['remarks'] != '' ? $val['remarks'] : '';?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12  custom-left">
                                <div class="form-group floating-label">
                                    <label>Upload Image(s)</label>
                                    <input type="file" name="addRequestFile1" id="fileUp1">
                                </div>
                            </div>
                            <?php if ( isset($incident_images) ) {
                            $incident_upload_path = $this->config->item('incident_file_upload');
                            $incident_image = $incident_upload_path['upload_path'];
                            foreach ( $incident_images as $key=>$val ) { ?>
                                <div class="col-md-4">
                                    <div class="row">
                                         <div class="col-md-12 col-xs-12">
                                             <a href="javascript:;" class="expen_det_cls" data-value="<?php echo base_url() . $incident_image . '/' . $val['incident_id'] . '/' . $val['img_name']; ?>"><img src="<?php echo base_url() . $incident_image . '/' . $val['incident_id'] . '/' . $val['img_name']; ?>" style="width: 100px;"></a>
                                         </div>
                                    </div>
                                </div>
                            <?php }
                            } ?>
                            <div class="col-md-12" >
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <p class="help-block"> * Required Fields.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: right;margin:0 -10px;">
                                <div class="col-md-12">
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary submit_btn" name="action" value="save_only">Submit</button> &ensp;
                                        <!--button type="submit" class="btn btn-primary submit_btn" name="action" value="save_print">Submit & Print</button> &ensp;-->
                                        <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 col-xs-12" style="padding: 0;">

                        </div>
                    </div>

              <!-- /.box-body -->
            </div> <!-- .row -->  
          </div> <!-- .box-body -->
          
        </form>
            
            
            
      </div><!-- /.box -->

    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer.php');?>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.fileuploader.min.js"></script>
<script src="<?php echo base_url();?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>
/** Image uload with preview */

$('#fileUp1').fileuploader({
	extensions: ['jpg', 'jpeg', 'png', 'gif','pdf'],
	upload: {
		url: '<?php echo base_url('index.php/bms_incident/incident_image_submit');?>',
		data: {			
			files : 'incident_file'
		},
		type: 'POST',
		enctype: 'multipart/form-data',
		start: true,
		synchron: true,
		beforeSend: null,
		onSuccess: function(data, item) {
			console.log(data);
			var file_data = JSON.parse(data);
			if (typeof file_data !== 'undefined') {
				var html = '<input type="hidden" name="files[]" value="' + file_data['name'] + '" real_name="' + item['name'] + '" footer="footer" />';
				$("#incident_new").append(html);
			}
			//
			setTimeout(function() {
				item.html.find('.progress-holder').hide();
				item.renderImage();
			}, 400);
		},
	},
	onRemove: function(listEl, parentEl, newInputEl, inputEl, data, item){
		var file_name = listEl['name'];
		var real_name = $("input[real_name='" + file_name + "']").val();
		$.ajax({
			url: '<?php echo base_url('index.php/bms_incident/incident_image_remove');?>',
			type: 'POST',
			data: {file: real_name, uploadDir: '../' + 'uploads/Complaint - Tasks Attachement/275/2018/05/2/14/' },
		})
		.done(function() {
			$('#incident_new :input[real_name="' + file_name + '"][footer="footer"]').remove();
		});
	}
	});

$(document).ready(function () {

    $('.timepicker').timepicker({
        showInputs: false
    });
    
    $('.submit_btn').click(function () {
        if($('#property_id').val() != '' && $('#task_name').val() != '' && $('#assign_to').val() != '' && $('#datepicker').val() != '' ) {
            $("#content_area").LoadingOverlay("show"); 
        }   
    });
});

//Date picker
$(function () {
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });
});

$('.expen_det_cls').unbind('click');
$('.expen_det_cls').bind("click",function () {
        $('.modal-body2 img').attr('src', $(this).attr('data-value'));
        $('#myModal2').modal({show:true});
    });
</script>

<!--  MODEL POPUP  -->
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modeltitledisp"><i class="fa fa-file"></i> Incident image </h4>
            </div>
            <div class="modal-body modal-body2" style="text-align: center;">
                <img src="" style="margin: 0 auto;">
            </div>
            <div style="clear: both;height:10px"></div>
            <div class="modal-footer">
                <input type="hidden" value="" id="datavaladded" class="datavaladded">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>