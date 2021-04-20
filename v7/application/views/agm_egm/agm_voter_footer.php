</div>
              <!-- /.box-body --> 

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  
  <footer class="main-footer" style="padding: 10px 15px; margin-left: 0px !important;">    
    <div class="hidden-xs"><strong>Copyright &copy; <?php echo date('Y');?> <a href="http://itechms.my/" target="_blank">iTech Management Solutions Sdn.Bhd.</a></strong> All rights reserved.</div>
    <div class="visible-xs"><strong>Copyright &copy; <?php echo date('Y');?> <a href="http://itechms.my/" target="_blank">iTech </a></strong> &ensp; All rights reserved.</div>
  </footer>
  
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

var notifyInterval;
$(document).ready(function () {    
    $('.agm_msg').fadeOut(7000);
    notifyInterval = setInterval(function(){ ksa(); }, 1200000);
});

function ksa () {
    $.ajax({
        type:"post",
        async: true,
        url: '<?php echo base_url('index.php/bms_agm_egm_vote/ksa');?>',
        data: {},
        datatype:"html", // others: xml, json; default is html
        beforeSend:function (){  }, //
        success: function(data) {    
            
        },
        error: function (e) {
            console.log(e); //alert("Something went wrong. Unable to retrive data!");
        }
    });
}
</script>