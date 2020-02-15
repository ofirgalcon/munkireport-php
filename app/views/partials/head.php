<!doctype html>
<html class="no-js" lang="en">

<head>
	<meta name=apple-mobile-web-app-capable content=yes>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo conf('sitename'); ?></title>
	<link rel="stylesheet" href="/assets/themes/Default/bootstrap.min.css" id="bootstrap-stylesheet">
	<link rel="stylesheet" href="<?php echo conf('subdirectory'); ?>assets/nvd3/nv.d3.min.css" />
	<link rel="stylesheet" href="<?php echo conf('subdirectory'); ?>assets/themes/<?php echo sess_get('theme', 'Default')?>/nvd3.override.css" id="nvd3-override-stylesheet" />
	<link rel="stylesheet" href="<?php echo conf('subdirectory'); ?>assets/css/style.css" />
	<link rel="stylesheet" media="screen" href="//cdn.datatables.net/plug-ins/1.10.20/integration/font-awesome/dataTables.fontAwesome.css" />
	<link href="<?php echo conf('subdirectory'); ?>assets/css/font-awesome.min.css" rel="stylesheet">
  <!--favicons-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/manifest.json">
	<link rel="mask-icon" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/safari-pinned-tab.svg" color="#5d5858">
	<link rel="shortcut icon" href="<?php echo conf('subdirectory'); ?>assets/images/favicons/favicon.ico">
	<meta name="msapplication-config" content="<?php echo conf('subdirectory'); ?>assets/images/favicons/browserconfig.xml">
	<meta name="theme-color" content="#5d5858">
  <!--end of favicons-->
	<?php if(conf('custom_css')): ?>
	<link rel="stylesheet" href="<?php echo conf('custom_css'); ?>" />
	<?php endif; ?>

	<?php if(isset($stylesheets)):?>
	<?php foreach($stylesheets as $stylesheet):?>
	<link rel="stylesheet" href="<?php echo conf('subdirectory'); ?>assets/css/<?php echo $stylesheet; ?>" />
	<?php endforeach?>
	<?php endif?>

	<style>
	main > .container { margin-top: 1rem; }	
	</style>

	<script>
		var baseUrl = "<?php echo conf('subdirectory'); ?>",
			appUrl = "<?php echo rtrim(url(), '/'); ?>",
			default_theme = "<?php echo conf('default_theme'); ?>",
			businessUnitsEnabled = <?php echo conf('enable_business_units') ? 'true' : 'false'; ?>;
			isAdmin = <?php echo $_SESSION['role'] == 'admin' ? 'true' : 'false'; ?>;
			isManager = <?php echo $_SESSION['role'] == 'manager' ? 'true' : 'false'; ?>;
	</script>

<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<?php
	if (isset($scripts))
		foreach($scripts as $script): ?>
	<script src="<?php echo conf('subdirectory'); ?>assets/js/<?php echo $script; ?>" type="text/javascript"></script>
<?php endforeach; ?>

</head>

<body>

<?php if( isset($_SESSION['user'])){require('nav.php');}?>

<main role="main">
	