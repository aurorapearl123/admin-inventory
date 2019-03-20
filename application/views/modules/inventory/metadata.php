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

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Stockcard')) {
    $submenu[] = array('label'=>'Stock Cards','url'=>site_url('stockcard/show'),'icon'=>'bullet','subitem'=>array());
}

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Customer Ledger')) {
    $submenu[] = array('label'=>'Customer ledger','url'=>site_url('customer_ledger/show'),'icon'=>'bullet','subitem'=>array());
}



if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Supplier Ledger')) {
    $submenu[] = array('label'=>'Supplier ledger','url'=>site_url('supplier_ledger/show'),'icon'=>'bullet','subitem'=>array());
}


// main module
$module = array();
$module['main']['title'] 		= 'Inventory';
$module['main']['controller'] 	= 'inventory';
$module['main']['description'] 	= 'Manage all Inventory';
$module['main']['icon'] 		= 'la-archive';
$module['main']['roles'] 		= array();




// sub module
$module['sub']['Stockcard'] = array(
    'title'=>'Stockcard',
    'module_label'=>'Stockcard', // this will display on create/view/edit form
    'controller'=>'stockcard',
    'description'=>'Manage all Inventory Stockcard',
    'icon'=>'la-archive',
    'roles'=>array(
        'Add Stockcard',
        'View Stockcard',
        'Edit Stockcard',
        'Delete Stockcard',
    )
);


// sub module
$module['sub']['Customer Ledger'] = array(
    'title'=>'Customer Ledger',
    'module_label'=>'Customer Ledger', // this will display on create/view/edit form
    'controller'=>'Customer Ledger',
    'description'=>'Manage all Inventory Customer Ledger',
    'icon'=>'la-sort-numeric-asc',
    'roles'=>array(
        'Add Customer Ledger',
        'View Customer Ledger',
        'Edit Customer Ledger',
        'Delete Customer Ledger',
        'Confirm Customer Ledger',
        'Cancel Customer Ledger',
    )
);


// sub module
$module['sub']['Supplier Ledger'] = array(
    'title'=>'Supplier Ledger',
    'module_label'=>'Supplier Ledger', // this will display on create/view/edit form
    'controller'=>'Supplier Ledger',
    'description'=>'Manage all Inventory Supplier Ledger',
    'icon'=>'la-sort-numeric-asc',
    'roles'=>array(
        'Add Supplier Ledger',
        'View Supplier Ledgerr',
        'Edit Supplier Ledger',
        'Delete Supplier Ledger',
        'Confirm Supplier Ledger',
        'Cancel Supplier Ledger',
    )
);

$module['main']['menu'] = $submenu;
$this->modules['Inventory']  = $module;
?>