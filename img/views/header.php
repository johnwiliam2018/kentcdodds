<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $page_title ?> - <?php echo SITE_TITLE ?></title>
        <meta name="description" content="Dashboard of <?php echo SITE_TITLE?>" />

        <!-- Bootstrap -->
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="assets/plugins/font-awesome/css/font-awesome.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- iCheck -->
        <link href="assets/plugins/iCheck/skins/flat/green.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- bootstrap-progressbar -->
        <link href="assets/plugins/bootstrap/css/bootstrap-progressbar-3.3.4.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- iCheck -->
        <link href="assets/plugins/iCheck/skins/flat/green.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- bootstrap-wysiwyg -->
        <link href="assets/plugins/google-code-prettify/prettify.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- Select2 -->
        <link href="assets/plugins/select2/css/select2.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- Switchery -->
        <link href="assets/plugins/switchery/switchery.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- starrr -->
        <link href="assets/plugins/starrr/starrr.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <!-- Datatables -->
        <link href="assets/plugins/datatables/css/dataTables.bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <link href="assets/plugins/datatables/css/buttons.bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <link href="assets/plugins/datatables/css/fixedHeader.bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <link href="assets/plugins/datatables/css/responsive.bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">
        <link href="assets/plugins/datatables/css/scroller.bootstrap.min.css?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="assets/css/custom.css?v=<?php echo ASSETS_CUSTOM_VERSION; ?>" rel="stylesheet">

        <!-- jQuery -->
        <script src="assets/plugins/jquery/jquery.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
        <!-- Bootstrap -->
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
        <!-- FastClick -->
        <script src="assets/plugins/fastclick.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
        <!-- NProgress -->
        <script src="assets/plugins/nprogress.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
        <!-- Switchery -->
        <script src="assets/plugins/switchery/switchery.min.js?v=<?php echo ASSETS_ORIGINAL_VERSION; ?>"></script>
    </head>

    <?php
    $logined_user = $wpdb -> get_row("SELECT * FROM " . TABLE_ADMINS . " WHERE `ID`='" . $logined_user_id . "'");
    ?>

	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title" style="border: 0;">
							<a href="index.php" class="site_title"> 
								<h1> <?php echo SITE_TITLE?> </h1> 
							</a>
						</div>

						<div class="clearfix"></div>

						<!-- sidebar menu -->
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
							<div class="menu_section">
								<ul class="nav side-menu">
									<li class="<?php if ($page_slug == 'home') echo "active" ?>">
										<a href="index.php"><i class="fa fa-tachometer"></i> Upload Files </a>
									</li>
									<li class="<?php if ($page_slug == 'images') echo "active" ?>">
										<a href="imageGallery.php"><i class="fa fa-image"></i> Images </a>
									</li>
									
									<li class="<?php if ($page_slug == 'system') echo "active" ?>">
										<a href="system-config.php"><i class="fa fa-cogs"></i> System Configuration </a>
									</li>
									<li class="<?php if ($page_slug == 'test') echo "active" ?>">
										<a href="test.php"><i class="fa fa-cloud"></i> Test APIs </a>
									</li>
									<li class="<?php if (isset($page_slug) && $page_slug == 'users') echo "active" ?>">
										<a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu" style="<?php if (isset($page_slug) && $page_slug == 'users_') echo 'display:block;' ?>">
											<li class="<?php if (isset($page_sub_slug) && $page_sub_slug == 'usersall') echo "active" ?>">
												<a href="usersall.php">All Users</a>
											</li>
											<li class="<?php if (isset($page_sub_slug) && $page_sub_slug == 'usersnew') echo "active" ?>">
												<a href="usersnew.php">New User</a>
											</li>
										</ul>
									</li>
								</ul>
							</div>

						</div>
						<!-- /sidebar menu -->
					</div>
				</div>

				<!-- top navigation -->
				<div class="top_nav">

					<div class="nav_menu">
						<nav class="" role="navigation">
							<div class="nav toggle">
								<a id="menu_toggle"><i class="fa fa-bars"></i></a>
							</div>
							<ul class="nav navbar-nav navbar-right">
								<li class="">
									<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-user"></i> <?php echo $logined_user->username?> <span class=" fa fa-angle-down"></span> </a>
									<ul class="dropdown-menu dropdown-usermenu pull-right">
										<!--li>
											<a href="your-profile.php"><i class="fa fa-edit pull-right"></i> Edit Profile</a>
										</li-->
										<li>
											<a href="change-password.php"><i class="fa fa-key pull-right"></i> Change Password</a>
										</li>
										<li>
											<a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
										</li>
									</ul>
								</li>
							</ul>
						</nav>
					</div>

				</div>
				<!-- /top navigation -->
      
