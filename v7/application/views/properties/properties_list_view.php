<?php $this->load->view('header');?>
<?php $this->load->view('sidebar'); //echo "<pre>";print_r($properties); ?>
  
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="visible-xs">
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        &ensp;<a role="button" class="btn btn-success" href="<?php echo base_url('index.php/bms_task/new_task');?>" >New Task</a>
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
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-6 col-xs-9">
                        
                          <input type="text" id="property_txt" value="<?php echo isset($_GET['search_txt']) && trim($_GET['search_txt']) != '' ? trim($_GET['search_txt']) : '';?>" class="form-control" placeholder="Enter text to search property">
                            
                
                    </div>
                    
                    
                    <div class="col-md-1 col-xs-3" >
                        <a href="javascript:;" role="button" class="btn btn-primary property_filter"><i class="fa fa-search"></i></a>
                    </div>
                    <?php if(in_array($_SESSION['bms']['designation_id'],$hr_access_desi)) { ?>
                    <div class="col-md-5 col-xs-12">
                        <input class="btn btn-primary add_property_btn" value="Add Property" type="button">
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
                  <th>Total Units</th>
                  <th class="hidden-xs">Property Type</th>
                  <th class="hidden-xs">Email</th>
                  <?php if(in_array($_SESSION['bms']['designation_id'],$hr_access_desi)) { ?>
                  <th>Status</th>
                  <?php } else { ?>
                  
                  <?php } ?>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $offset = 0;
                    //$task_status = $this->config->item('task_db_status');
                    if(!empty($properties)) {
                        //$task_status = $this->config->item('task_db_status');
                        foreach ($properties as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td><?php echo $val['property_name'];?></td>
                            <td><?php echo $val['total_units'];?></td>
                            <td class="hidden-xs"><?php echo $val['type_name'];?></td>
                            <td class="hidden-xs"><?php echo $val['email_addr'];?></td>    
                            <?php if(in_array($_SESSION['bms']['designation_id'],$hr_access_desi) || in_array($_SESSION['bms']['designation_id'],array(27))) { ?>
                            <td><?php echo $val['property_status'] == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';?></td>
                            <td style="text-align: center;"><a href="<?php echo base_url('index.php/bms_property/add_property/'.$val['property_id']);?>" title="Edit"><i class="fa fa-edit"></i></a></td>
                            <?php } else { ?>                        
                            <td style="text-align: center;"><a href="#" data-value="<?php echo $val['property_id'];?>" class="prop_detail_cls" title="View"><i class="fa fa-info-circle"></i></a></td>
                            <?php }  ?>
                        </tr>
                    
                <?php }
                
                    } else { ?>
                        <tr>
                            <td class="hidden-xs text-center" colspan="6">No Record Found</td>
                            <td class="visible-xs text-center" colspan="3">No Record Found</td>
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
  <!-- bootstrap datepicker -->
  
<?php $this->load->view('footer');?>
<script>

$(document).ready(function () {
    
    $('.msg_notification').fadeOut(5000);
    
    $('.property_filter').click(function () {
        window.location.href="<?php echo base_url('index.php/bms_property/properties_list');?>?search_txt="+$('#property_txt').val().replace(/^\s+|\s+$/g,"");
        return false;        
    });
    
    $('.add_property_btn').click(function () {
        window.location.href='<?php echo base_url('index.php/bms_property/add_property');?>';
        return false;  
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

    $('.prop_detail_cls').click(function () {
        $('.modal-body2').load('<?php echo base_url('index.php/bms_property/property_detail_popup/?property_id=');?>'+$(this).attr('data-value'),function(result){
            $('#myModal2').modal({show:true});
        });
    });
    
});

</script>



<!-- Modal content-->
<!-- Modal2 -->
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Details</h4>
            </div>
            <div class="modal-body modal-body2">

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