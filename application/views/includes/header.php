<!DOCTYPE html>
<html>
<head>
<?php $random_token = time(); ?>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<!-- App favicon -->
<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png'); ?>">

<!-- App css -->
<link href="<?php echo site_url('assets/css/fontawesome-all.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/metismenu.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/datepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/dataTables.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/dataTables.colvis.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/mCustomScrollbar.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/timepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/multi-select.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo site_url('assets/css/select.dataTables.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/toast.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/summernote.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/tagsinput.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url("assets/plugins/select2/css/select2.min.css"); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo site_url('assets/css/fancybox.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/tooltipster.bundle.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/daterangepicker.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/style.css'); ?>?<?=time()?>" rel="stylesheet" type="text/css" />

<!-- jQuery -->
<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>';
var site_url = '<?php echo site_url(); ?>';

function loader_big() {
	return '<?=content_loader('loader-big')?>';
}

function loader_small() {
	return '<?=content_loader()?>'
}

function loader_tiny() {
	return '<i class="fa fa-spinner fa-spin loader_tiny"></i>';
}
</script>

<!-- jQuery  -->
<script src="<?php echo site_url('assets/js/modernizr.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/jquery.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/dataTables.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/dataTables.colvis.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/dataTables.rowgroup.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap-filestyle.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/timepicker.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/jquery-ui.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/multi-select.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/appear.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/toast.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/toastr.init.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/summernote.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/tagsinput.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/masking.js'); ?>"></script>
<script src="<?php echo site_url("assets/plugins/select2/js/select2.min.js"); ?>"></script>
<script src="<?php echo site_url("assets/js/formToObject.min.js?t=$random_token"); ?>"></script>
<script src="<?php echo site_url('assets/js/common_functions.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/datatables_customized.js'); ?>?<?=time()?>"></script>

</head>
<body<?php if(!empty($body_class)) echo ' class="'.$body_class.'"'; ?>>
<!--oncontextmenu="return false;"-->
<!-- Begin page -->
<div id="wrapper">
	<!--Header-->
    <header class="main-header">
    	<div class="container-fluid">
        	<div class="topbar">
                <!-- Mobile Navigation Toggler -->
                <div class="mobile-nav-toggler"><span class="icon"><button class="button-menu-mobile waves-effect waves-light"><i class="fa fa-bars"></i></button></span></div>
                
                <nav class="navbar-custom">
                    <ul class="list-unstyled topbar-right-menu float-left mb-0">
                        <li class="dropdown notification-list">
                            <div class="topbar-left">
                                <a href="<?php echo site_url('dashboard'); ?>" class="text-success logo">
                                    <span><img src="<?php echo site_url('assets/images/logo.png'); ?>" alt="Logo" height="20"></span>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-unstyled topbar-right-menu float-right mb-0">
                        <li class="dropdown notification-list" style="margin-top:8px;">
                            <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
								<h6 class="text-overflow m-0">Welcome <?php echo $this->session->userdata('fullname'); ?> (<?php echo $this->session->userdata('role'); ?>) <span class="ml-1"><i class="fe-chevron-down"></i></span></h6>
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                                <!-- item-->
                                <a href="<?php echo site_url('user/view/'.$this->session->userdata('user_id')); ?>" class="dropdown-item notify-item"><i class="fe-user"></i> <span>Profile</span></a>
                                <a href="<?php echo site_url('user/change-password'); ?>" class="dropdown-item notify-item"><i class="fe-sliders"></i> <span>Settings</span></a>
                                <a href="<?php echo site_url('logout'); ?>" class="dropdown-item notify-item"><i class="fe-log-out"></i> <span>Logout</span></a>
                            </div>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="slimscroll-menu" id="remove-scroll">
            <!--- Sidemenu -->
            <div id="sidebar-menu">
            	<?php echo $navigation; ?>
            </div>
            <!-- Sidebar -->
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -left -->
    </div>
    <!-- Left Sidebar End -->

    <div class="content-page">
        <!-- Top Bar Start -->
        <div class="topbar">
            <nav class="navbar-custom">
                <ul class="page-heading list-inline menu-left mb-0">
                    <li>
                        <div class="page-title-box">
                            <h4 class="page-title"><?php echo $title; ?></h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
                            </ol>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Top Bar End -->
