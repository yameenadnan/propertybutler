<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> <?php echo isset($browser_title) && $browser_title != '' ? $browser_title : 'Property Butler |' ;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url();?>dist/css/skins/skin-purple.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_media_query.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bms_custom_styles.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper"> <!-- closed in footer -->

  <!-- Main Header -->
  <header class="main-header">
    <?php

        if ( $_SESSION['bms']['user_type'] == 'staff' )
            $user_id = $_SESSION['bms']['staff_id'];
        elseif ( $_SESSION['bms']['user_type'] == 'jmb' )
            $user_id = $_SESSION['bms']['member_id'];
        elseif ( $_SESSION['bms']['user_type'] == 'developer' )
            $user_id = $_SESSION['bms']['property_dev_id'];

        $this->notify_count = $this->notifications->get_notification_count($_SESSION['bms']['user_type'],$user_id);
    ?>
    <!-- Logo -->
    <a href="<?php echo base_url();?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" ><b>PB</b></span>
      <!-- logo for regular state and mobile devices -->
      <!--span class="logo-lg" style="padding-top:5px;" ><img src="<?php echo base_url();?>assets/images/pb_log4.jpeg" alt="Property Butler" class="img-responsive" style="max-height: 40px;" /></span-->
      <span class="logo-lg" style="padding-top:4px;" ><img src="<?php echo base_url();?>assets/images/2.png" alt="Property Butler" class="img-responsive center-block" /></span>      
      
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top " role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle hidden-xs" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        
        
        <li class="dropdown tasks-menu hidden-xs">
            <?php if($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28))) { ?>
            <a href="<?php echo base_url('index.php/bms_notifications/to_do_list/0/25?status_type=0');?>" class="to_do_cnt_a" title="You have <?php echo $todo_cnt = $this->notifications->get_todo_count ($_SESSION['bms']['staff_id'],'0'); ?> Reminder(s)" >
              PA
              <span class="label label-warning to_do_cnt_cls"><?php echo $todo_cnt;?></span>
            </a>
            <?php } ?>
            
          </li>
        
         <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu hidden-xs">
            
                <?php if($_SESSION['bms']['user_type'] == 'jmb' || ($_SESSION['bms']['user_type'] == 'staff' && !in_array($_SESSION['bms']['designation_id'], array(28)))) { ?>
                <a href="<?php echo base_url('index.php/bms_notifications/notifications_list');?>" class="notification_cnt_a" title="You have <?php echo $this->notify_count; ?> notification(s)">
                  <i class="fa fa-bell-o"></i>
                  <span class="notification_cnt_span label label-<?php echo isset($this->notify_count) && $this->notify_count > 0 ? 'danger' : 'success';?>"><?php echo $this->notify_count; ?></span>
                </a>
                <?php } ?>
            <!--ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!- - inner menu: contains the actual data - ->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                      page and may cause design problems
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> 5 new members joined
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> You changed your username
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul-->
          </li>
         <li class="dropdown user user-menu <?php echo $_SESSION['bms']['user_type'] != 'staff' ? 'hidden-xs' : '';?>">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <i class="fa fa-user"></i><!--img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo isset($_SESSION['bms']['full_name']) ? $_SESSION['bms']['full_name'] : $_SESSION['bms']['first_name'];?></span>
            </a>
            <ul class="dropdown-menu" style="width: 150px;">
                <li class="user-footer ">
                <!--div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div-->
                <div class="pull-right text-right sign-out-sty">
                    <a class="hidden-xs" href="<?php echo base_url('index.php/bms_index/change_password');?>">Change Password</a>
                    <a href="<?php echo base_url('index.php/bms_index/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li-->
        </ul>
      </div>
      
    </nav>
  </header>