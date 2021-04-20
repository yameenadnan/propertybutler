<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
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
            <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success msg_notification"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }
                
            ?>
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_human_resource/notice_board_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                  
                    
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-9 col-xs-12">
                                <div class="form-group">
                                  <label>Message </label>
                                  <?php 
                                  $message = '';
                                  if(!empty($notice_board['message'])) {
                                    $message = $notice_board['message'];
                                    $breaks = array("<br />","<br>","<br/>");  
                                    $message = str_ireplace($breaks, "", $message); 
                                                                      
                                  } 
                                    //$text = str_ireplace($breaks, "\r\n", $text);  
                                ?>                                                                                                           
                                  <textarea name="message" class="form-control" rows="10" placeholder="Enter Notice Board Message"><?php echo $message; ?></textarea>
                                </div>
                            </div>
                    
                    </div>
                    <input type="hidden" name="notice_id" value="<?php echo !empty($notice_board['notice_id']) ? $notice_board['notice_id'] : ''; ?>" />
                   
                    
                  </div><!-- . row -->
                  
                 
                
              
          </div><!-- /.box-body -->
          
          <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
              </div>
            </div>
          </div>
        </form>
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<?php $this->load->view('footer');?>
<script>

$(document).ready(function () {
  $('.msg_notification').fadeOut(10000);  
});
</script>