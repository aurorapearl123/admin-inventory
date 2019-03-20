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
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Dashboard')) {
    $submenu[] = array('label'=>'Dashboard','url'=>site_url('dashboard/show'),'icon'=>'bullet','subitem'=>array());
}

// main module
$module = array();
$module['main']['title'] 		= 'Dashboard';
$module['main']['controller'] 	= 'dashboard';
$module['main']['description'] 	= 'Manage all Dashboard';
$module['main']['icon'] 		= 'la-desktop';
$module['main']['roles'] 		= array();


// sub module
$module['sub']['Dashboard'] = array(
    'title'=>'Dashboard',
    'module_label'=>'Dashboard', // this will display on create/view/edit form
    'controller'=>'dashboard',
    'description'=>'Manage all Dashboard',
    'icon'=>'la-desktop',
    'roles'=>array('View Dashboard',
    )
);

$module['main']['menu'] = $submenu;
$this->modules['Dashboard']  = $module;
?>