<?php 
/**
 * File: metadata.php
 * Description: Defination of this module and its roles and sub-modules
 * 
 * Example of sub module
 * 
 * $module['sub'][] = array(
		'title'=>'Item Category',
		'description'=>'Manage all item category in the commissary',
		'icon'=>'category.png',
		'roles'=>array('Add','View','Edit','Delete')
	);
 */
 
$submenu = array();
if ($this->session->userdata('current_user')->isAdmin) {
    $submenu[] = array('label'=>'Users','url'=>site_url('user'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'User Groups','url'=>site_url('group'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Configuration','url'=>site_url('config'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Audit Logs','url'=>site_url(''),'icon'=>'bullet','subitem'=> array(
            array('label'=>'Activity Logs Per User','url'=>site_url('logs/user_log'),'icon'=>'bullet'),
            array('label'=>'Activity Logs Per Module','url'=>site_url('logs/module_log'),'icon'=>'bullet'),
        )
    );
}

// main module
$module = array();
$module['main']['title'] 		= 'Administrator';
$module['main']['controller'] 	= 'administrator';
$module['main']['description'] 	= 'Manage all User';
$module['main']['icon'] 		= 'la-cog';
$module['main']['roles'] 		= array();


// sub module
$module['sub']['User'] = array(
    'title'=>'Users',
    'module_label'=>'User',
    'controller'=>'user',
    'description'=>'Manage all Users',
    'icon'=>'la-user',
    'roles'=>array()
);


$module['sub']['User Group'] = array(
    'title'=>'User Groups',
    'module_label'=>'User Group',
    'controller'=>'group',
    'description'=>'Manage all User Groups',
    'icon'=>'la-users',
    'roles'=>array()
);

$module['sub']['Configuration'] = array(
    'title'=>'Configurations',
    'module_label'=>'Configuration',
    'controller'=>'config',
    'description'=>'Manage all Configurations',
    'icon'=>'la-cogs',
    'roles'=>array()
);

$module['sub']['Audit Logs'] = array(
    'title'=>'Audit Logs',
    'module_label'=>'Audit Logs',
    'controller'=>'auditlog',
    'description'=>'Manage all Audit Logs',
    'icon'=>'la-server',
    'roles'=>array()
);


$module['main']['menu'] = $submenu;
$this->modules['Administrator']  = $module;
?>