<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
  <style>
      .btn-bs-file{
            position:relative;
      }
        .btn-bs-file input[type="file"]{
            position: absolute;
            top: -9999999;
            filter: alpha(opacity=0);
            opacity: 0;
            width:0;
            height:0;
            outline: none;
            cursor: inherit;
      }
  </style>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ' - '; ?>
        &ensp;<a role="button" class="btn btn-primary pull-right" onclick="printDiv('<?php echo base_url('index.php/bms_defect/print_defect/'.$defect_id);?>');" href="javascript:;" >Print</a>
        <!--small>Optional description</small-->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">

              <div class="box-body">
                  <div class="row printable" id="printable">
                    <div class="col-md-12" >
                        
                        <div class="col-md-6 col-xs-12 " style="padding: 0;"  >
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12" style="padding-top: 10px;">
                                    <div class="form-group">
                                      <label for="property_id">Defect ID: </label>
                                        <?php echo str_pad($defect_details->defect_id, 5, '0', STR_PAD_LEFT);?>
                                    </div>
                                </div>
                                <!--<div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label for="property_id">Property Name: </label>
                                        <?php /*echo $defect_details->property_name;*/?>
                                    </div>
                                </div>-->
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Defecr Title :</label>
                                        <?php echo $defect_details->defect_name;?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Defect Location:</label>
                                        <?php echo isset($defect_details->defect_location) ? $defect_details->defect_location : ' - ';?>
                                    </div>
                                </div>

                                 <!--<div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Created By : </label>
                                                               
                                        <?php /*
                                        if(isset($defect_details->created_by)) {
                                        
                                            if($defect_details->created_by == '0') {
                                                echo 'JMB / MC';
                                            } else if($defect_details->created_by == '-1') {
                                                echo 'Resident';
                                            } else {
                                                echo $defect_details->first_name .' '.$defect_details->last_name;
                                            }
                                        
                                        } else {
                                        echo ' - ';
                                        }*/?>
                                            

                                    </div>
                                </div>-->
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Created Date :</label>
                                        <?php echo isset($defect_details->created_date) && $defect_details->created_date != '' && $defect_details->created_date != '0000-00-00' && $defect_details->created_date != '1970-01-01' ? date('d-m-Y', strtotime($defect_details->created_date)) : ''; ?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Status :</label>
                                        <span id="defect_status_span" <?php echo isset($defect_details->defect_status) && $defect_details->defect_status == 'C' ? 'class="text-success"': '';?>>
                                            <?php echo !empty($defect_details->defect_status) && $defect_details->defect_status == 'O' ? ' Open ' : ' Close '; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Close Remarks :</label>
                                        <span id="defect_close_span">
                                          <?php echo !empty($defect_details->defect_close_remarks) ? $defect_details->defect_close_remarks : ' - ';?>
                                        </span>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6 col-xs-12" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding right-box1" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="col-md-12 col-xs-12"  style="padding-top: 10px;">
                                    <div class="form-group">
                                      <label for="">Block/Street: </label>
                                        <?php echo isset($block_street->block_name) ? $block_street->block_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Unit No: </label>
                                      <?php echo isset($defect_details->unit_no) ? $defect_details->unit_no : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Unit Status: </label>
                                      <?php echo isset($defect_details->unit_status_name) ? $defect_details->unit_status_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Name: </label>
                                        <?php echo isset($defect_details->owner_name) ? $defect_details->owner_name : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Contact: </label>
                                            <?php echo isset($defect_details->contact_1) ? $defect_details->contact_1 : ' - ';?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                      <label>Resident Email: </label>
                                        <?php echo isset($defect_details->email_addr) ? $defect_details->email_addr : ' - ';?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        
                    </div> <!-- /.col-md-12 -->
                </div><!-- /.row -->
              </div> <!-- /.box-body -->
              <div style="clear: both;"></div>
              <div class="col-md-12 col-xs-12" style="border: 1px solid #999;padding: 10px 15px;" >
              <!-- The container for the uploaded files -->
              
                <div id="img_preview" class="row" style="margin: 0px;">
                    <div class="col-md-12" style="padding: 0;min-height:150px;">
                        <div class="col-md-12">
                            <label>Images :</label>
                        </div>
                        <div class="col-md-10 col-xs-8 text-center">
                            <?php
                            if(!empty($defect_images)) {
                                foreach ($defect_images as $key => $val) {
                                    $file_name = explode('.', $val['img_name']);
                                    if (strtolower(end($file_name)) == 'pdf') { ?>
                                        <div class="col-md-3 col-xs-12 text-center">
                                            <img class="img-responsive center-block img_view"
                                                 style="display:none;max-height: 200px; max-width: 150px;cursor: pointer;"
                                                 src="<?php echo '../../../bms_uploads/defect_uploads/' . $defect_id . '/' . $val['img_name']; ?>"/>
                                            <a class="pdf_view"
                                               href="<?php echo base_url() . 'bms_uploads/defect_uploads/' . $defect_id . '/' . $val['img_name']; ?>"
                                               target="_blank" title="click to view / download">
                                                <img class="img-responsive center-block"
                                                     style="max-height: 200px; max-width: 150px;cursor: pointer;"
                                                     src="<?php echo base_url() . 'assets/images/pdf_icon.png'; ?>"/>
                                                <span class="pdf_view_name"><?php echo $val['img_name']; ?></span>
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-3 col-xs-12 text-center">
                                            <img class="img-responsive center-block img_view"
                                                 style="max-height: 200px; max-width: 150px;cursor: pointer;"
                                                 src="<?php echo '../../../bms_uploads/defect_uploads/' . $defect_id . '/' . $val['img_name']; ?>"/>
                                            <a class="pdf_view" style="display:none;"
                                               href="<?php echo base_url() . 'bms_uploads/defect_uploads/' . $defect_id . '/' . $val['img_name']; ?>"
                                               target="_blank" title="click to view / download">
                                                <img class="img-responsive center-block"
                                                     style="max-height: 200px; max-width: 150px;cursor: pointer;"
                                                     src="<?php echo base_url() . 'assets/images/pdf_icon.png'; ?>"/>
                                            </a>
                                        </div>
                                    <?php }
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-2 col-xs-12 align-middle">
                            <span class="align-middle">
                                <a href="javascript:;" class="defect_update_btn btn btn-primary" data-value="close" data-toggle="modal" data-target="#myModal_close" title="Close"  style="line-height: 70px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Close&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </a>
                            </span>
                        </div>
                    </div>
                
                </div>
              </div>
              
              <div class="col-md-12 col-xs-12" style="margin-top: 15px;border: 1px solid;"  >
                 <form name="forum_frm" id="forum_frm">
                    
                    <input type="hidden" name="defect_id" value="<?php echo $defect_id;?>" />
                    <input type="hidden" id="property_id" name="property_id" value="<?php echo $defect_details->property_id;?>" />
                    
                    <div><h5 style="font-weight: bold;"><span style="border-bottom: 1px solid #333;"> Defect Forum:&nbsp; </span></h5></div>
                    <div style="clear: both;height:10px"></div>
                    <div class="col-md-12 col-xs-12 chat_content" style="padding: 0;min-height: 10px;"></div>
                    
                    <div class="col-md-12 col-xs-12" style="padding: 0 0 15px 0;">
                        <div class="col-md-6 col-xs-4" style="padding: 0;">
                            <textarea class="col-md-12 col-xs-12" style="padding: 1px;" name="chat_text" id="chat_text"></textarea>
                        </div>
                        <div class="col-md-6 col-xs-8" style="padding-top: 10px;">
                            <label class="btn-bs-file btn btn-primary">
                                Choose File...
                                <input type="file" id="attach_file" name="attach" onchange='$("#upload-file-info").html($(this).val());' />
                            </label>
                            <span class='label label-info' id="upload-file-info"></span>
                            &ensp;
                    		<input class="btn btn-primary chat_send_btn" type="submit" value="Send" />
                         </div>
                    </div>
                 </form>
              </div>
          </div>
          <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<!-- Modal -->
<div id="myModal_close" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Defect Close Remarks</h4>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 msg">
            <div class="col-xs-3">Remarks : </div>
            <div class="col-xs-9">
                <textarea id="close_remarks" name="close_remarks" class="form-control" placeholder="Enter Defect Close Remarks" /></textarea>
            </div>
        </div>
        <div style="clear: both;height:10px"></div>
      </div>
      <div style="clear: both;height:10px"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary defect_status_update" data-value="close" id="">Save</button> &ensp;
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal2 -->




<!-- Modal -->
<div id="fullImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Defect Image</h4>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 full_img">
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
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<script>



// variable for the set innterval function to keep updating the chat room content
var myInterval;

$(document).ready(function () {
    
    //scroll down the chat content div
    var d = $('.chat_content');
    d.animate({ scrollTop: d.prop('scrollHeight') }, 3000);
    
    //console.log($('.cust-container-fluid').width());
    if($('.cust-container-fluid').width() > 715){
        
         if($('.left-box').height() < (eval($('.right-box1').height()) + eval($('.right-box2').height()) + 5)) {
            //console.log($('.left-box').height()+' = '+)
            $('.left-box').height(eval($('.right-box1').height()) + eval($('.right-box2').height()+5));        
        } 
    } else { 
        $('.right-box1').parent('div').css('padding','10px 0 0 0');        
    }
    

    
    $('.img_view').bind("click",function () {
        //console.log($(this).attr('data-date'));
        $('.full_img').html('<img src="'+$(this).attr('src')+'" class="img-responsive center-block"/>');
        $('#fullImgModal').modal({show:true});       
    });

    $('.defect_status_update').click(function () {
        if($(this).attr('data-value') == 'close') {
            $('#close_remarks').val($('#close_remarks').val().replace(/^\s+|\s+$/g,""));
            if($('#close_remarks').val() == ''){
                alert('Please enter close remarks!');
                $('#close_remarks').focus();
                return false;
            }
        }
        set_defect_status($(this).attr('data-value'));
    }); 
    
    if('<?php echo isset($defect_details->defect_status) && $defect_details->defect_status =='C' ? '1' : '0';?>' == '1') {

         $('.defect_update_btn').removeAttr('data-toggle').removeAttr('data-target').removeClass('text-success').addClass('text-secondary').css('cursor','default');
    } 
    
    $('#logBtn').click(function(){
  
      	$('.modal-body2').load('<?php echo base_url('index.php/bms_defect/get_log_details/'.$defect_id);?>',function(result){
    	    $('#myModal2').modal({show:true});
    	});
    });
    
    $("#forum_frm").on('submit', function(e) {
        
        e.preventDefault();
        
        clearInterval(myInterval); // clear the interval time to update the chat content

        $('#chat_text').val($('#chat_text').val().replace(/^\s+|\s+$/g,""));
        if ($('#chat_text').val() != '' || $('#attach_file').val() != '') {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_defect/set_defect_forum');?>',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function ( ){ $('.chat_send_btn').attr("disabled","disabled"); $('#fupForm').css("opacity",".5");  }, //
                success: function(data) { 
                    
                    // clear the entered data and update the chat content
                    //$('#chat_text').val('');
                    get_chat_content();
                    myInterval = setInterval(function(){ get_chat_content(); }, 5000);
                    
                    if(data) {
                        $('#forum_frm')[0].reset();
                        $('#upload-file-info').html('');
                    } else {
                        alert('Some Error. Unable to save your comment!');
                        console.log('chat not saved!');
                    }
                    
                    $('.chat_send_btn').removeAttr("disabled");
                    $('#forum_frm').css("opacity","");
                },
                error: function (e) {
                    alert('Some Error. Unable to save your comment!');
                    $('.chat_send_btn').removeAttr("disabled");
                    $('#forum_frm').css("opacity","");
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        } else {
            alert('Please enter text OR Choose file!');
            return false;
        }               
    });
    
    // set innterval function to keep updating the chat room content and directly call for first time content load    
    myInterval = setInterval(function(){ get_chat_content(); }, 30000);
    get_chat_content();
});

function printDiv(url) {    
    window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=450,directories=no,location=no');
}

function get_chat_content () {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_defect/get_defect_forum/'.$defect_id.'');?>',
        data: {},
        datatype:"html", // others: xml, json; default is html

        beforeSend:function (){  }, //
        success: function(data) {  
            $('.chat_content').html(data); 
            $('.chat_content').css('max-height','250px');           
            
            if($('.chat_content').html() != '') {
                $('.chat_content').css('overflow-y','scroll');
                //$('.chat_content').scrollTop = $('.chat_content').scrollHeight;
                $('.chat_content').stop().animate({
                  scrollTop: $('.chat_content')[0].scrollHeight
                }, 800);
            }
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function set_defect_status (act_type) {
    // var defect_update = act_type == 'update' ? $('#defect_update').val() : (act_type == 'close' ? 'Closed' : '');
    var defect_update = 'Closed';

    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_defect/set_defect_status');?>',
        data: {'defect_id':'<?php echo $defect_id;?>','property_id':$('#property_id').val(),'defect_update':defect_update,'close_rem':$('#close_remarks').val()},
        datatype:"html", // others: xml, json; default is html

        beforeSend:function (){ $("#content_area").LoadingOverlay("show");   }, //
        success: function(data) {  
            $("#content_area").LoadingOverlay("hide", true);     
            
            if(data) {
                if($('#close_remarks').val() != '') { $('#defect_close_span').html($('#close_remarks').val()) }
                                
                if(defect_update == 'Closed') {
                    $('#defect_status_span').html(defect_update);
                    $('.defect_status_update').unbind('click').removeClass('text-success').addClass('text-secondary').css('cursor','default');
                    $('.defect_update_btn').removeAttr('data-toggle').removeAttr('data-target').removeClass('text-success').addClass('text-secondary').css('cursor','default');
                    $('.defect_status_span').addClass('text-success');
                    $('#myModal_close').modal('toggle');
                } 
                     
                alert('Defect Updated Successfully!');
                /*str += '<div class="alert alert-success log_err_msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                str += '</strong>Task Updated Successfully</div>';*/
            } else {
                alert('Defect Update is not saved!');
            }
            $('#close_remarks').val('');
        },
        error: function (e) {
            $("#content_area").LoadingOverlay("hide", true);     
            $('#defect_update').val('');
            $('#datepicker').val('');
            $('#close_remarks').val('');
            alert('Defect Update Error!');
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

$(function () {
//Date picker
    $('#datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        'minDate': new Date()
    });
    
});
</script>