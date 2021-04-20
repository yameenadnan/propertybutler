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
<div class="wrapper" style="background-color: #FFF !important;"> <!-- closed in footer -->

  <!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
    <a class="logo" onclick="return;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" ><b>PB</b></span>
      <!-- logo for regular state and mobile devices -->
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



         <li class="dropdown user user-menu <?php echo $_SESSION['agm']['user_type'] != 'staff' ? '' : '';?>">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <i class="fa fa-user"></i><!--img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo isset($_SESSION['agm']['full_name']) ? $_SESSION['agm']['full_name'] : $_SESSION['agm']['first_name'];?></span>
            </a>
            <ul class="dropdown-menu" style="width: 150px;">
                <li class="user-footer ">
                <!--div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div-->

                    <a href="<?php echo base_url('index.php/bms_agm_egm_vote/ready_vote');?>" class="btn btn-default btn-flat"  style="padding: 5px 5px !important; margin-bottom: 6px; color: #605CA8;">Voting</a>
                    <a href="<?php echo base_url('index.php/bms_agm_egm_vote/vote_history');?>" class="btn btn-default btn-flat"  style="padding: 5px 5px !important; margin-bottom: 6px; color: #605CA8;">History</a>
                    <a href="<?php echo base_url('index.php/bms_agm_egm_vote/logout');?>" class="btn btn-default btn-flat"  style="padding: 5px 5px !important; color: #605CA8;"><b>Sign out</b></a>
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content">

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <!-- general form elements -->

              <div class="box-body" >

              <?php if(isset($_SESSION['flash_msg']) && trim( $_SESSION['flash_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-success agm_msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['flash_msg'].'</div>';
                    unset($_SESSION['flash_msg']);
                }

                if(isset($_SESSION['error_msg']) && trim( $_SESSION['error_msg'] ) != '') {
                    //if($_GET['login_err'] == 'invalid')
                    echo '<div class="alert alert-danger agm_msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>';
                    echo '</strong>'.$_SESSION['error_msg'].'</div>';
                    unset($_SESSION['error_msg']);
                }
            ?>
