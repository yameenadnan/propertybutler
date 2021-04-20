<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style>
  .notification-type-div { display: inline;padding: 10px;width: 25px; }
  .notification-type-div > a:hover, .notification-type-div > a:active { background-color: transparent !important; }
  .notification-type-div > a > span { position: relative;top: -15px;left:-5px;text-align: center;font-size: 9px;padding: 4px 6px;line-height: .9; }
  .notifi_caption { display: inline; font-size: 16px; padding-right: 5px; }
  </style>  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="hidden-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
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
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
              <div class="box-body">
                  <div class="row">
                  
                    <div class="col-md-4 col-xs-6"> 
                        <select class="form-control" id="status_type" name="status_type">
                            <option value="all" <?php echo !empty($_GET['status_type']) && $_GET['status_type'] == 'all' ? 'selected="selected"' : '';?>>All</option>
                            <option value="1" <?php echo !empty($_GET['status_type']) && $_GET['status_type'] == '1' ? 'selected="selected"' : '';?> >Completed</option>
                            <option value="0" <?php echo isset($_GET['status_type']) && $_GET['status_type'] == '0' ? 'selected="selected"' : '';?>>Uncompleted</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 col-xs-5">                        
                          <input type="text" id="search_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter Description to Search">
                    </div>
                    
                    
                    <div class="col-md-1 col-xs-1" >
                        <a href="javascript:;" role="button" class="btn btn-primary filter_btn"><i class="fa fa-search"></i></a>
                    </div>
                    
                    <div class="col-md-3 col-xs-12">
                        <input class="btn btn-primary" onclick="window.location.href='<?php echo base_url('index.php/bms_notifications/add_todo');?>'" value="New Reminder" type="button">
                    </div>
                                        
                    
                </div>
                
              </div>
              <!-- /.box-body -->
              
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Description</th>
                  <th>Expected Complete Date</th>
                  <th>Actual Complete Date</th>                  
                  <th>Action</th>  
                </tr>
                </thead>
                <tbody id="content_tbody">
                      
                </tbody>                
              </table>
            </div>
          
          <div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    
                    Show: &nbsp;<select id="records_per_page">
                            <option value="25" <?php echo isset($per_page) &&  $per_page == 25 ? 'selected="selected"' : '';?>>25 per page</option>                            
                            <option value="50" <?php echo isset($per_page) &&  $per_page == 50 ? 'selected="selected"' : '';?>>50 per page</option>
                            <option value="100" <?php echo isset($per_page) &&  $per_page == 100 ? 'selected="selected"' : '';?>>100 per page</option>
                        </select>
                        
                        <!--span style="display: inline-block; padding-left: 15px; font-size: 12px;color:#000;font-weight:bold" class="showing_stat" > Showing 0 to 0 Of 0 Record(s) </span-->
                    
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                    
                    <div class="paging_right_div" style="padding-right: 5px;">
                        <span class="previous_link"></span> 
                        <span>Page <input class="publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span small_loader"></span></span>
                        <span class="next_link"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages" value="" type="hidden">                                           
                    </div>
                </div>
                
            </div>
            
         </div><!-- /.box -->  
             

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 
  
  <!-- bootstrap datepicker -->
  
<?php $this->load->view('footer');?>
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {    
    $('.msg_notification').fadeOut(5000);
    
    $('.filter_btn').click(function () {
        loadContent(0,jQuery('#records_per_page').val(),true);   
    });
        
    jQuery('#records_per_page').change(function(e){
        loadContent(0,jQuery('#records_per_page').val(),true);
        return false; 
    });
    
    jQuery('.publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    loadContent((jQuery(this).val()-1)*jQuery('#records_per_page').val(),jQuery('#records_per_page').val(),true);
                    return false;
                } else {
                    var max_limit = eval(jQuery('#tot_pages').val());
                    alert('Please enter the page number between 1 and '+max_limit);
                    jQuery(this).focus();
                    return false;
                }                
            } else {
                alert('Please enter a valid page number');
                jQuery(this).val('');jQuery(this).focus();
                return false;
            }              
        }        
    });
    
    loadContent('<?php echo $offset;?>','<?php echo $per_page;?>',false);
    
});

function set_todo_done (id) {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_notifications/set_todo_done');?>',
        data: {'todo_id':id},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_tbody").LoadingOverlay("hide", true);
            $('.done_cls_'+id).html(' Completed ');
            $('.act_comp_date_'+id).html(data);
            var cnt = parseInt($('.to_do_cnt_cls').html()) -1;
            $('.to_do_cnt_cls').html(cnt);
            $('.to_do_cnt_a').attr('title','You have '+cnt+' PA');
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}

function loadContent (offset,rows,flag) {
    var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_notifications/get_todo_content');?>',
        data: {'offset':offset,'rows':rows,'staff_id':'<?php echo $_SESSION['bms']['staff_id'];?>','status_type':$('#status_type').val(),'search_txt':search_txt},
        datatype:"json", // others: xml, json; default is html
        beforeSend:function (){ $("#content_tbody").LoadingOverlay("show");  }, //
        success: function(data) {  
            $("#content_tbody").LoadingOverlay("hide", true);
            var str = ''; var showing_to = 0;
            var numFound = data.numFound;
            //console.log(data.numFound);
            if(numFound > 0) {                    
                $.each(data.records,function (i, item) { 
                    showing_to = (eval(offset)+eval(i)+1);
                    str += '<tr>';
                    str += '<td class="hidden-xs">'+showing_to+'</td>';
                    str += '<td>'+item.description+'</td>';
                    str += '<td>'+formatDate(item.complete_date)+'</td>';
                    str += '<td class="act_comp_date_'+item.to_do_id+'">'+(item.actual_complete_date != null ? formatDate(item.actual_complete_date) : ' - ')+'</td>';
                    if(item.status != '0') {
                        str += '<td> Completed </td>';
                    } else {
                        str += '<td class="done_cls_'+item.to_do_id+'"><a href="<?php echo base_url('index.php/bms_notifications/add_todo/');?>'+item.to_do_id+'" role="button" class="btn btn-primary">Edit</a>&ensp;';
                        str += '<a href="javascript:;" data-value="'+item.to_do_id+'" role="button" class="btn btn-primary done_btn">Done</a></td>';
                    }
                    
                    str += '</tr>';
                });
                var page = (eval(offset) / eval(rows)) + 1;
                jQuery('.publi_paging').val(page);
                var total_pages = Math.ceil(numFound / rows);
                total_pages = total_pages == 0 ? 1 : total_pages;
                jQuery('#tot_pages').val(total_pages);
                jQuery('.tot_pag_span').html(total_pages.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                jQuery('#tot_rec_span').html('<span id="tot_rec">'+numFound.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+ '</span> RESULTS'); //x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                
                var previous_link = ""; 
                var next_link = "";
                if(offset > 0 ){
                    previous_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)-eval(rows))+","+rows+",true);'><span class='glyphicon glyphicon-triangle-left' style='color: green;'></span></a> ";
                } 
                
                if(eval(numFound) > (eval(offset)+eval(rows))){
                    if((eval(offset)+eval(rows)) < numFound){
                        next_link = "<a href='javascript:;' onclick='loadContent("+(eval(offset)+eval(rows))+","+rows+",true);'> <span class='glyphicon glyphicon-triangle-right' style='color: green;'></span></a>";
                    } else {
                       // do nothing      
                    }                    
                }
                jQuery('.previous_link').html(previous_link);
                jQuery('.next_link').html(next_link);                
                $("#content_tbody").html(str);
            } else {
                str = '<tr><td class="hidden-xs text-center" colspan="7">No Record Found</td>';
                str += '<td class="visible-xs text-center" colspan="4">No Record Found</td></tr>';
                $("#content_tbody").html(str);
                jQuery('.tot_pag_span').html('1');
                jQuery('.next_link').html('');
            }
            
            $('.done_btn').unbind('click');
            $('.done_btn').bind("click",function () {
                set_todo_done($(this).attr('data-value'));
            });
            
            
            //$('.showing_stat').html('Showing '+(eval(offset)+(numFound > 0 ? 1 : 0))+' - '+showing_to+' Of '+numFound+' Record(s). Total Unit(s) : '+$('#property_id').find("option:selected").attr('data-value'));
            //$(this).attr('title',$('#property_id').find("option:selected").attr('data-value'));
            
            // This is to update the url
            if(flag) {
                if (typeof (history.pushState) != "undefined") {
                    var update_url = '<?php echo base_url('index.php/bms_notifications/to_do_list');?>/'+offset+'/'+rows+'?status_type='+$('#status_type').val()+'&search_txt='+search_txt;
                    var obj = { Title: '<?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler' ;?>', Url: update_url };
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    console.log("Browser does not support HTML5.");
                }
            }
        },
        error: function (e) {
            $("#content_tbody").LoadingOverlay("hide", true);              
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}



function formatDate(in_date) {
  
  var in_date_arr2 = in_date.split('-');
  
  return in_date_arr2[2] + "-" + in_date_arr2[1] + "-" + in_date_arr2[0];
  
  /*var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  var mont = date.getMonth()+1;
  return  date.getDate() + "-" + mont + "-" + date.getFullYear() + "  " + strTime;*/
}

$(function () {
//Date picker
    $('#datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true   
    });
    
});
</script>