<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>        
      </h1>
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
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
              <div class="box-body" style="padding-top: 15px;">
              
              <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
            ?>
        <div class="row">
                    
                    
                    <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Property Name</label>
                                    <select class="form-control" id="property_id" name="agm_main[property_id]" >                              
                                    <option value="">Select</option>
                                    <?php 
                                        foreach ($properties as $key=>$val) {                                        
                                            $selected = !empty($property_id) && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                            echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                        } ?> 
                                      </select>
                                      
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="form-group">
                                  <label>AGM Type</label>
                                    <select class="form-control" id="agm_type" name="agm_main[agm_type]" >                              
                                    <option value="">All</option>
                                    <?php
                                        foreach ($agm_types as $key=>$val) {                                        
                                            $selected = !empty($agm_type) && trim($agm_type) == $key ? 'selected="selected" ' : '';
                                            echo "<option value='".$key."' ".$selected.">".$val."</option>";
                                        } ?> 
                                      </select>
                                </div>
                            </div>
                    
                    <div class="col-md-1 col-xs-2" style="margin-top: 25px;" >
                        <a href="javascript:;" role="button" class="btn btn-primary list_filter"><i class="fa fa-search"></i></a>
                    </div>
                    <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
                    <div class="col-md-3 col-xs-4" style="margin-top: 25px;">
                        <a role="button" class="btn btn-primary" href="<?php echo base_url('index.php/bms_jmb_mc/add_agm');?>" >Add AGM</a>
                    </div>
                    <?php } ?>                    
                    
                </div>
                
                
              </div>
              <!-- /.box-body -->
              
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>   
                  <th>Property Name</th>               
                  <th>AGM Type</th>
                  <th>JMB / MC Term</th>
                  <th>Last AGM Date</th>
                  <th>AGM Date</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $offset = 0;
                    //$task_status = $this->config->item('task_db_status');
                    if(!empty($agm_list)) {
                        //$prop_doc_download_desi_id = $this->config->item('prop_doc_download_desi_id');
                        //$task_status = $this->config->item('task_db_status');
                        foreach ($agm_list as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td><?php echo $val['property_name'];?></td>
                            <td><?php echo $str = !empty($val['agm_type']) ? $agm_types[$val['agm_type']] : 'AGM';?></td>
                            <td> <?php 
                            //$str = !empty($val['agm_type']) ? $agm_types[$val['agm_type']] : 'AGM';
                            $min_date_y = date('Y', strtotime("-1 Day",strtotime("+12 months", strtotime($val['agm_last_date']))));
                            $str .= ' '.$min_date_y;
                            if(!empty($val['agm_date']) && $val['agm_date'] != '0000-00-00') {
                                $agm_date_y = date('Y', strtotime($val['agm_date']));
                                $str .= $agm_date_y != $min_date_y ? '/'.$agm_date_y : '';
                            }  
                            echo $str;
                            ?></td>
                            <td><?php echo !empty($val['agm_last_date']) && $val['agm_last_date'] != '0000-00-00' ? date('d-m-Y', strtotime($val['agm_last_date'])) : ' - ';?></td>
                            <td><?php echo !empty($val['agm_date']) && $val['agm_date'] != '0000-00-00' ? date('d-m-Y', strtotime($val['agm_date'])) : ' - ';?></td>
                            <td style="text-align: center;">
                                <a class="agm_update_cls" href="javascript:;" data-value="<?php echo $val['agm_id'];?>" title="Update"><i class="fa fa-pencil"></i></a> &ensp;
                                <a href="<?php echo base_url('index.php/bms_jmb_mc/add_agm/'.$val['property_id'].'/'.$val['agm_type'].'/edit/'.$val['agm_id']);?>" title="Edit"><i class="fa fa-edit"></i></a>
                            </td>
                            
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="text-center" colspan="7">No Record Found</td>                            
                        </tr>                    
                    <?php } ?>                
                </tbody>                
              </table>
            </div>
          
          <!--div class="row ciov" style="margin: 0px !important;padding: 10px 0; border-top: 1px solid #DCDCDC;border-bottom: 1px solid #DCDCDC;background-color: #F0F0F0;">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0px;">
                    
                    
                    Show: &nbsp;<select id="records_per_page">
                            <option value="10" selected="selected">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right"  style="padding: 0px;">
                    
                    <div class="paging_right_div" style="padding-right: 5px;">
                        <span class="previous_link"></span> 
                        <span>Page <input class="publi_paging" size="2" pattern="[0-9]*" value="1" type="text"> of <span class="tot_pag_span small_loader"></span></span>
                        <span class="next_link"><a href="javascript:;" > <span class="glyphicon glyphicon-triangle-right" style="color: green;"></span></a></span> <input id="tot_pages" value="" type="hidden">                                           
                    </div>
                </div>
                
            </div--> 
            
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
        <h4 class="modal-title">AGM Update</h4>
      </div>
      <div class="modal-body modal-body2">
        
        <div class="xol-xs-12 msg">
            
        </div>
        <div style="clear: both;height:10px"></div>
        <div class="col-xs-12" style="padding-top: 15px;">
            
        </div>
        
        
      </div>
      
    </div>

  </div>
</div>
  
<?php $this->load->view('footer');?>
 <!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script>

$(document).ready(function () {
    
    $('#property_id').focus();
    $('.msg_notification').fadeOut(5000);
    
    $('.list_filter').click(function () {
        //var search_txt = $('#search_txt').val().replace(/^\s+|\s+$/g,"");
        window.location.href="<?php echo base_url('index.php/bms_jmb_mc/agm_list/');?>"+$('#property_id').val()+'/'+$('#agm_type').val();
        return false;        
    });
    
    $('.agm_update_cls').bind("click",function () {
        $('.modal-content').css('width','750px');
        $('.modal-body2').load('<?php echo base_url('index.php/bms_jmb_mc/agm_details/');?>'+$(this).attr('data-value'),function(result){
    	    $('#myModal2').modal({show:true});
    	});
    });
    
    jQuery('#records_per_page').change(function(e){
        loadOverSeeingTask(0,jQuery('#records_per_page').val());
        return false; 
    });
    
    jQuery('.publi_paging').keypress(function( event ) {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
        if ( event.which == 13 ) {
            //alert(jQuery.isNumeric(jQuery(this).val()) + ' ' + eval(jQuery(this).val()) +'  '+ jQuery('#tot_pages').val());
            event.preventDefault();
            if(jQuery.isNumeric(jQuery(this).val())) {
                if(eval(jQuery(this).val()) > 0 && eval(jQuery(this).val()) <= jQuery('#tot_pages').val()) {
                    loadOverSeeingTask((jQuery(this).val()-1)*jQuery('#records_per_page').val(),jQuery('#records_per_page').val());
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
    
});

</script>