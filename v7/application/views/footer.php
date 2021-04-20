<!-- Main Footer -->
  <footer class="main-footer hidden-xs" style="padding: 10px 15px;">
    <!-- To the right -->
    <!--div class="pull-right hidden-xs">
      Anything you want
    </div-->
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date('Y');?> <a href="http://itechms.my/" target="_blank">iTech Management Solutions Sdn.Bhd.</a></strong> All rights reserved.
  </footer>
  
  <footer class="main-footer visible-xs" style="padding: 10px 15px; position: fixed; height:<?php echo $_SESSION['bms']['user_type'] == 'staff' ? '70' : '55';?>px; bottom: 0;width: 100%;">
    <!-- To the right -->
    <!--div class="pull-right hidden-xs">
      Anything you want
    </div-->
    <!-- Default to the left -->
    <div class="row">
        <div class="col-xs-12" style="padding: 0;">
            <?php if($_SESSION['bms']['user_type'] == 'staff') { ?>
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="javascript:;" title="Chat">
                    <i class="fa fa-comment"></i><br /> Chat
                </a>
            </div>
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_task/task_list');?>" title="Minor Tasks">
                    <i class="fa fa-tasks"></i><br /> Minor Task                         
                </a>
                
            </div>
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_sop/entry_list');?>" title="Routine Task">
                  <i class="fa fa-th-list"></i><br /> Routine Task
                </a>
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="javascript:;" title="Major Task">
                  <i class="fa fa-cubes"></i><br /> Major Task
                </a>
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo in_array($_SESSION['bms']['designation_id'],$this->config->item('daily_report_access_desi')) ? base_url('index.php/bms_daily_report/index') : 'javascript:;';?>" title="Report">
                    <i class="fa fa-folder"></i><br /> Report                         
                </a>                
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_notifications/notifications_list');?>" title="Alert">
                  <i class="fa fa-bell-o"></i><span class="notification_cnt_span label label-<?php echo isset($this->notify_count) && $this->notify_count > 0 ? 'danger' : 'success';?>" style="position: absolute;top: -4px;right: 10px;text-align: center;font-size: 9px;padding: 3px 4px;line-height: .9;"><?php echo $this->notify_count; ?></span><br /> Alert
                </a>
            </div>
            
            <!--div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_index/logout');?>" title="Sign Out">
                  <i class="fa fa-sign-out"></i><br /> Logout
                </a>
            </div-->
            <?php } else { ?>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="javascript:;" title="Chat">
                    <i class="fa fa-comment"></i><br /> Chat
                </a>
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_task/task_list');?>" title="Minor Tasks">
                    <i class="fa fa-tasks"></i><br /> Task                         
                </a>                
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_notifications/notifications_list');?>" title="Alert">
                  <i class="fa fa-bell-o"></i><span class="notification_cnt_span label label-<?php echo isset($this->notify_count) && $this->notify_count > 0 ? 'danger' : 'success';?>" style="position: absolute;top: -4px;right: 10px;text-align: center;font-size: 9px;padding: 3px 4px;line-height: .9;">
                  <?php echo $this->notify_count; ?></span><br /> Alert
                </a>
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_property/docs_list_jmb');?>" title="Property Documents">
                  <i class="fa fa-cubes"></i><br /> Docs
                </a>
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="javascript:;" title="Report">
                    <i class="fa fa-folder"></i><br /> Report                         
                </a>                
            </div>
            
            <div class="col-xs-2 text-center" style="padding: 0;">
                <a href="<?php echo base_url('index.php/bms_index/logout');?>" title="Sign Out">
                  <i class="fa fa-sign-out"></i><br /> Logout
                </a>
            </div>
            
            <?php } ?>
            
        </div>
    </div>
  </footer>

  <!-- Control Sidebar -->
  <!--aside class="control-sidebar control-sidebar-dark">
    <!- - Create the tabs - ->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!- - Tab panes - ->
    <div class="tab-content">
      <!- - Home tab content - ->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!- - /.control-sidebar-menu - ->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!- - /.control-sidebar-menu - ->

      </div>
      <!- - /.tab-pane - ->
      <!- - Stats tab content - ->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!- - /.tab-pane - ->
      <!- - Settings tab content - ->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!- - /.form-group - ->
        </form>
      </div>
      <!- - /.tab-pane - ->
    </div>
  </aside-->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>dist/js/adminlte.min.js"></script>
<script>
/*window.addEventListener("load",function() {
    console.log('event listener for load');*/
    setTimeout(function(){
        // This hides the address bar:
        window.scrollTo(0, 1);
    }, 0);
/*});*/
var notifyInterval;
$(document).ready(function (){
    //notification_cnt_span
    if('<?php echo $_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) ? 1 : 0  ?>' == '1') {
        notifyInterval = setInterval(function(){ get_notify_count(); }, 31000)
    }
});

function get_notify_count () {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_notifications/get_notify_count');?>',
        data: {},
        datatype:"html", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {  
            if(jQuery.isNumeric(data)) {
                $('.notification_cnt_span').html(data);
                $('.notification_cnt_a').attr('title','You have '+data+' notification(s)');
                if(eval(data) > 0) {
                    $('.notification_cnt_span').addClass('label-danger').removeClass('label-success');
                } else {
                    $('.notification_cnt_span').addClass('label-success').removeClass('label-danger');
                }
            }
            /*$('.chat_content').html(data); 
            $('.chat_content').css('max-height','250px');           
            
            if($('.chat_content').html() != '') {
                $('.chat_content').css('overflow-y','scroll');
                //$('.chat_content').scrollTop = $('.chat_content').scrollHeight;
                $('.chat_content').stop().animate({
                  scrollTop: $('.chat_content')[0].scrollHeight
                }, 800);
            }*/
            //jQuery.isNumeric(data)
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>