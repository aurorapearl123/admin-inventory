<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="utf-8" />
    <title><?php echo $this->config_model->getConfig('Software') ?></title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?php echo base_url('assets/css/style.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/css/main.min.css') ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/main/favicon.png') ?>" />
    <script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/highcharts/js/highcharts.js'); ?>"></script>
    <style type="text/css">
    .top {
	   border-top: 0.5px solid #cccccc;
    }
    
    .bottom {
	   border-bottom: 0.5px solid #cccccc;
    }
        
    .left {
	   border-left: 0.5px solid #cccccc;
    }
    
    .right {
	   border-right: 0.5px solid #cccccc;
    }
    </style>
  </head>
  <body style="background-color: #ffffff;">
    <table width="100%" style="margin-bottom: 5px;" cellspacing="0">
        <tr style="background-color: #192f5d; color: #ffffff;">
            <td width="50%">
                <img src="<?php echo base_url('assets/img/main/logo-shadow.png') ?>" width="150"  style="margin: 5px 20px 5px 20px;" />
            </td>
            <td align="right">
                <h4 style="margin: 5px 20px 5px 20px;"><?php echo $title ?></h4>
            </td>
        </tr>
    </table>