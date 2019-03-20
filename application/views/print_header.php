<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link REL="SHORTCUT ICON" HREF="<?php echo base_url(); ?>images/fav.ico">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->config_model->getConfig('Software') ?></title>
<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet" type="text/css" media="all"  />
<script src="<?php echo base_url(); ?>javascript/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>javascript/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>javascript/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>

<?php
$theme = $this->config_model->getConfig('Theme');
?>

<link title="color:sugar" type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/colors.<?php echo strtolower($theme) ?>.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/map.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/rating.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/fonts.normal.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/print.css" media="all" />
<link type="text/css" href="<?php echo base_url(); ?>css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
</head>

<body>
<!--start of header-->