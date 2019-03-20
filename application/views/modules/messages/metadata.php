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
 * 
 * Sub module name must be equal to controller name, space separated.
 */

$submenu = array();
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Dashboard')) {
    $submenu[] = array('label'=>'Message Board','url'=>site_url('message_board/show'),'icon'=>'bullet','subitem'=>array());
    $submenu[] = array('label'=>'Message Private','url'=>site_url('message_private/show'),'icon'=>'bullet','subitem'=>array());

}

// main module
$module = array();
$module['main']['title'] 		= 'Messages';
$module['main']['controller'] 	= 'messages';
$module['main']['description'] 	= 'Manage all Messages';
$module['main']['icon'] 		= 'la-envelope';
$module['main']['roles'] 		= array();

 
// sub module
$module['sub']['Message Board'] = array(
    'title'=>'Message Board',
    'module_label'=>'Message Board', // this will display on create/view/edit form
    'controller'=>'Message Board',
    'description'=>'Manage all Message Board',
    'icon'=>'la-envelope',
    'roles'=>array(
        'View Message Board',
    )
);
$module['sub']['Message Private'] = array(
    'title'=>'Message Privates',
    'module_label'=>'Message Private', // this will display on create/view/edit form
    'controller'=>'Message Private',
    'description'=>'Manage all Message Private',
    'icon'=>'la-envelope',
    'roles'=>array(
        'View Message Private',
    )
);


$module['main']['menu'] = $submenu;
$this->modules['Messages']  = $module;
?>