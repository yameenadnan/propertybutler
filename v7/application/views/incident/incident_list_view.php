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
                      <div class="col-md-6 col-xs-6">
                          <div class="form-group">
                              <label for="exampleInputEmail1">Property Name</label>
                              <select class="form-control" id="property_id" name="property_id">
                                  <option value="">All</option>
                                  <?php
                                  foreach ($properties as $key=>$val) {
                                      $selected = !empty($property_id) && trim($property_id) == $val['property_id'] ? 'selected="selected" ' : '';
                                      echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                  } ?>
                              </select>

                              <!--input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"-->
                          </div>
                      </div>
                      <div class="col-md-3 col-xs-4" style="margin-top: 25px;">
                          <a role="button" class="btn btn-primary" href="<?php echo base_url('index.php/bms_incident/new_incident');?>" >Add Incident</a>
                      </div>
                  </div>

              </div>
              <!-- /.box-body -->
              
              <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">S No</th>
                  <th>Incident detail</th>
                  <th>Incident status</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody id="content_tbody">
                <?php 
                    $offset = 0;
                    //$task_status = $this->config->item('task_db_status');
                    if(!empty($incident)) {
                        //$task_status = $this->config->item('task_db_status');
                        foreach ($incident as $key=>$val) { ?>
                        <tr>
                            <td class="hidden-xs"><?php echo ($offset+$key+1);?></td>
                            <td><?php echo $val['details'];?></td>
                            <td><?php echo $val['status'];?></td>
                            <td><?php echo $val['incident_date'];?></td>
                            <td><?php echo $val['incident_time'];?></td>
                            <td style="text-align: center;"><a href="<?php echo base_url('index.php/bms_incident/new_incident/'.$val['incident_id']);?>" title="Edit"><i class="fa fa-edit"></i></a></td>
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
        $('#property_id').change(function () {
            window.location.href="<?php echo base_url('index.php/bms_incident/incident_list');?>?property_id="+$('#property_id').val();
            return false;
        });
    });
</script>