<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .report-container { padding-top: 15px; }
  .report-container > div { padding: 10px 0px; }
  .report-container > div > span { padding-bottom: 3px; border-bottom: 1px dashed #999; }
  </style>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>      
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

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
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_staff_eval/award_submit');?>" method="post" >
            
              <!--input type="hidden" name="eval[awarded_id]" value="<?php echo $_SESSION['bms']['staff_id'];?>" /-->
                
              <div class="box-body">
                <?php 
                //echo $eval_status; exit;
                if(!in_array($eval_status, array(0,6))) {
                    echo '<div class="alert alert-warning" style="margin-top:5px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$eval_message[$eval_status].'</div>';
                    //unset($_SESSION['flash_msg']);
                } else {
                
                ?>
                  <div class="row">
                  
                    <div class="col-md-5 col-xs-12">
                        <div class="form-group">
                            <select class="form-control" id="award_cat" name="award_cat">                            
                            <option value="">Select Category</option>
                            
                            <?php 
                                $staff_award_cat = $this->config->item('staff_award_category'); 
                                foreach ($staff_award_cat as $key=>$val) {
                                    $selected = '';
                                    if(!empty($_GET['award_cat']) && trim($_GET['award_cat']) == $key ){
                                        $selected = 'selected="selected" ';                                        
                                    }
                                    //$selected = isset($_GET['property_id']) && trim($_GET['property_id']) != '' && trim($_GET['property_id']) == $val['PropertyId'] ? 'selected="selected" ' : '';  
                                    echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                } ?> 
                              </select>
                          </div>
                    </div>
                    
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <select class="form-control" id="award_year" name="award_year">                            
                                <option value="">Select Year</option>
                                <?php $selected = '';
                                    if(!empty($_GET['award_year']) && trim($_GET['award_year']) == '2018' ){
                                        $selected = 'selected="selected" ';                                        
                                    }?>
                                <option value="2018" <?php echo $selected;?>>2018</option>
                            </select>                
                          
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-xs-4">
                        <div class="form-group">
                            <select class="form-control" id="award_month" name="award_month">                            
                                <option value="">Select Month</option>
                                <!--option value="7">July</option-->
                                <?php $selected = '';
                                    if(!empty($_GET['award_month']) && trim($_GET['award_month']) == '8' ){
                                        $selected = 'selected="selected" ';                                        
                                    }?>
                                <option value="8" <?php echo $selected;?>>August</option>
                                <!--option value="9" <?php echo $selected;?>>September</option-->
                            </select>                
                          
                        </div>
                    </div>
                    
                    <div class="col-md-1 col-xs-3">
                        <a href="javascript:;" role="button" class="btn btn-primary filter_btn"><i class="fa fa-search"></i></a>
                    </div>
                    
                </div>
                
                
                
                <div class="row award-container" style="margin: 0;display: none;">
                    <table id="example2" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                          <th class="hidden-xs">S No</th>                          
                          <th>Staff Name</th>   
                          <th>Property Name</th>               
                          <th>JMB / MC Percentage</th>
                          <th>AM Percentage</th>
                          <th>HR Percentage</th>
                          <th>Total Score</th>
                          <th id="award_status" style="text-align: center;">Award</th>
                        </tr>
                        </thead>
                        <tbody id="content_tbody">
                                       
                        </tbody>                
                    </table>
                             
                </div>
              <div class="row submit_div" style="text-align: right;display: none;"> 
                <div class="col-md-12">
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary eval_submit_btn">Submit</button> &ensp;&ensp;
                    <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                    
                  </div>
                </div>
              </div>  
                
              </div><!-- /.box-body -->
              
              <?php } ?>
              
            </form>
          
           
            
         </div>
          <!-- /.box -->     

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
        <h4 class="modal-title">Staff Evaluation Results</h4>
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
 
  <!-- bootstrap datepicker -->
 
<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(10000);
    
    //$('.award-container').css('display','none');
    if('<?php echo !empty($_GET['auto']) && $_GET['auto'] == 1 ? '1' : '0';?>' == '1') {
        get_award_list (false);
    }
    $('.filter_btn').click(function (){
        get_award_list (true);
    });
    
    function get_award_list (flag) {
        if($('#award_cat').val() != '' && $('#award_cat').val() != '' && $('#award_cat').val() != '') {
            $.ajax({
                type:"post",
                async: true,
                url: '<?php echo base_url('index.php/bms_staff_eval/award_staff');?>',
                data: {'award_cat':$('#award_cat').val(),'award_year':$('#award_year').val(),'award_month':$('#award_month').val()},
                datatype:"json", // others: xml, json; default is html
    
                beforeSend:function (){ $("#content_area").LoadingOverlay("show");  }, //
                success: function(data) { 
                    //console.log(data);
                    $("#content_area").LoadingOverlay("hide", true);   
                    if(data.message == 'invalid_input') {
                        alert('invalid input');
                    } else if (data.message == 'staff_awarded'){
                        var str = '';
                        $.each(data.awarded_staff,function (i, item) { 
                            showing_to = (eval(i)+1);
                            str += '<tr>';
                            str += '<td class="hidden-xs">'+showing_to+'</td>';
                            str += '<td><a href="javascript:;" class="eval_det_cls" data-value="'+item.staff_id+'" data-prop="'+item.property_id+'" data-ayear="'+item.award_year+'" data-amonth="'+item.award_month+'" title="View Evaluation Details">'+item.first_name+(item.last_name != 'undefined' ? ' '+item.last_name : '' )+'</a></td>';
                            str += '<td>'+item.property_name+'</td>';
                            str += '<td>'+item.jmb_percentage+'</td>';
                            str += '<td>'+item.am_percentage+'</td>';
                            str += '<td>'+item.hr_percentage+'</td>';   
                            str += '<td>'+item.total_percentage+'</td>';                    
                            str += '<td style="text-align: center;">'+(item.awarded == '1' ? 'Awarded' : ' - ')+'</td>';
                            str += '</tr>';
                            
                        });
                        $('#content_tbody').html(str);
                        $('.award-container').css('display','block');
                    }  else if (data.message == 'award_staff_list'){
                        var str = '';
                        $.each(data.award_staff,function (i, item) { 
                            showing_to = (eval(i)+1);
                            str += '<tr>';
                            str += '<td class="hidden-xs">'+showing_to+'</td>';
                            str += '<td><a href="javascript:;" class="eval_det_cls" data-value="'+item.staff_id+'" data-prop="'+item.property_id+'" data-ayear="'+item.award_year+'" data-amonth="'+item.award_month+'" title="View Evaluation Details">'+item.first_name+(item.last_name != 'undefined' ? ' '+item.last_name : '' )+'</a></td>';
                            str += '<td>'+item.property_name+'</td>';
                            str += '<td>'+item.jmb_prtg+'</td>';
                            str += '<td>'+item.am_prtg+'</td>';
                            str += '<td>'+item.hr_prtg+'</td>';   
                            str += '<td>'+item.tot_prtg+'</td>';                    
                            str += '<td style="text-align: center;"><input type="radio" name="award_staff" value="'+item.staff_id+'-'+item.property_id+'"</td>';
                            str += '</tr>';
                            
                        });
                        $('#content_tbody').html(str);
                        $('.award-container, .submit_div').css('display','block');
                    } else {
                         var str = '';
                         str += '<tr>';
                         str += '<td colspan="8" class="text-center">No record found</td>';                        
                         str += '</tr>';
                        
                        $('#content_tbody').html(str);
                        $('.award-container').css('display','block');
                    }
                    
                    $('.eval_det_cls').unbind('click');
                    $('.eval_det_cls').bind("click",function () {
                        $('.modal-content').css('width','750px');
                        $('.modal-body2').load('<?php echo base_url('index.php/bms_staff_eval/get_staff_eval_det/');?>'+$(this).attr('data-value')+'/'+$(this).attr('data-prop')+'/'+$(this).attr('data-ayear')+'/'+$(this).attr('data-amonth'),function(result){
                    	    $('#myModal2').modal({show:true});
                    	});
                    });
                    
                    // This is to update the url
                    if(flag) {
                        if (typeof (history.pushState) != "undefined") {
                            var update_url = '<?php echo base_url('index.php/bms_staff_eval/award');?>?award_cat='+$('#award_cat').val()+'&award_year='+$('#award_year').val()+'&award_month='+$('#award_month').val()+'&auto=1';
                            var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler' ;?>', Url: update_url };
                            history.pushState(obj, obj.Title, obj.Url);
                        } else {
                            console.log("Browser does not support HTML5.");
                        }
                    }
                    
                },
                error: function (e) {
                    $("#content_area").LoadingOverlay("hide", true);              
                    console.log(e); //alert("Something went wrong. Unable to retrive data!");
                }
            });
        }
    }
    
   
});

</script>