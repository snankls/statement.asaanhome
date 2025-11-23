<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- App favicon -->
<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png'); ?>">

<!-- App css -->
<link href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/style.css'); ?>?<?=time()?>" rel="stylesheet" type="text/css" />
<link href="<?php echo site_url('assets/css/toast.min.css'); ?>" rel="stylesheet" type="text/css" />

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

<script src="<?php echo site_url('assets/js/jquery.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/modernizr.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/toast.min.js'); ?>"></script>

</head>
<body>

<!-- Begin page -->
<div class="accountbg" style="background-image: url('<?php echo site_url('assets/images/bg.png'); ?>');">
	<div class="login-overlay"></div>
</div>
	<div class="wrapper-page account-page-full">
