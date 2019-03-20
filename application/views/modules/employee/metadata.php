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
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Employee')) {
    $submenu[] = array('label'=>'Employee','url'=>site_url('employee/show'),'icon'=>'bullet','subitem'=>array());
}

// main module
$module = array();
$module['main']['title'] 		= 'Employee';
$module['main']['controller'] 	= 'employee';
$module['main']['description'] 	= 'Manage all Employee';
$module['main']['icon'] 		= 'la-users';
$module['main']['roles'] 		= array();


// sub module
$module['sub']['Employee'] = array(
    'title'=>'Employee',
    'module_label'=>'Employee', // this will display on create/view/edit form
    'controller'=>'employee',
    'description'=>'Manage all Employee',
    'icon'=>'la-users',
    'roles'=>array('View Employee',
    )
);

$module['main']['menu'] = $submenu;
$this->modules['Employee']  = $module;
?>