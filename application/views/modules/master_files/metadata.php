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
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Product')) {
    $submenu[] = array('label'=>'Product','url'=>site_url('product/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Product Category')) {
    $submenu[] = array('label'=>'Product Category','url'=>site_url('product_category/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Supplier')) {
    $submenu[] = array('label'=>'Supplier','url'=>site_url('supplier/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Customer')) {
    $submenu[] = array('label'=>'Customer','url'=>site_url('customer/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Loyalty ')) {
    $submenu[] = array('label'=>'Loyalty','url'=>site_url('loyalty/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Loyalty Ranks ')) {
    $submenu[] = array('label'=>'Loyalty Ranks','url'=>site_url('loyalty_ranks/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Owner')) {
    $submenu[] = array('label'=>'Owner','url'=>site_url('owner/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Bank Accounts')) {
    $submenu[] = array('label'=>'Bank Accounts','url'=>site_url('bank_accounts/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Banks')) {
    $submenu[] = array('label'=>'Banks','url'=>site_url('banks/show'),'icon'=>'bullet','subitem'=>array());
}
 if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Unit Measurement')) {
     $submenu[] = array('label'=>'Unit Measurement','url'=>site_url('unit_measurement/show'),'icon'=>'bullet','subitem'=>array());
 }
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Country')) {
    $submenu[] = array('label'=>'Countries','url'=>site_url('country/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Province')) {
    $submenu[] = array('label'=>'Provinces','url'=>site_url('province/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View City')) {
    $submenu[] = array('label'=>'Cities','url'=>site_url('city/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Barangay')) {
    $submenu[] = array('label'=>'Barangays','url'=>site_url('barangay/show'),'icon'=>'bullet','subitem'=>array());
}

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Deposit')) {
    $submenu[] = array('label'=>'Deposit','url'=>site_url('deposit/show'),'icon'=>'bullet','subitem'=>array());
}


if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Shift')) {
    $submenu[] = array('label'=>'Shift','url'=>site_url('shift/show'),'icon'=>'bullet','subitem'=>array());
}

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Dipstick')) {
    $submenu[] = array('label'=>'Dipstick','url'=>site_url('dipstick/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Pump')) {
    $submenu[] = array('label'=>'Pump','url'=>site_url('pump/show'),'icon'=>'bullet','subitem'=>array());
}


// main module
$module = array();
$module['main']['title'] 		= 'Master Files';
$module['main']['controller'] 	= 'master_files';
$module['main']['description'] 	= 'Manage all Master Files';
$module['main']['icon'] 		= 'la-files-o';
$module['main']['roles'] 		= array();


// sub module
$module['sub']['Product'] = array(
    'title'=>'Product',
    'module_label'=>'Product', // this will display on create/view/edit form
    'controller'=>'product',
    'description'=>'Manage all Product',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Product',
        'View Product',
        'Edit Existing Product',
        'Delete Existing Product',
    )
);

$module['sub']['Deposit'] = array(
    'title'=>'Deposit',
    'module_label'=>'Deposit', // this will display on create/view/edit form
    'controller'=>'Deposit',
    'description'=>'Manage all Deposit',
    'icon'=>'la-dropbox',
    'roles'=>array('Add Deposit','View Deposit','Edit Existing Deposit','Delete Existing Deposit','Approve Deposit')
);

// sub module
$module['sub']['Product Category'] = array(
    'title'=>'Product Category',
    'module_label'=>'Product Category', // this will display on create/view/edit form
    'controller'=>'product_category',
    'description'=>'Manage all Product Category',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Product Category',
        'View Product Category',
        'Edit Existing Product Category',
        'Delete Existing Product Category',
    )
);
// sub module
$module['sub']['Loyalty'] = array(
    'title'=>'Loyalty',
    'module_label'=>'Loyalty', // this will display on create/view/edit form
    'controller'=>'loyalty',
    'description'=>'Manage all Loyalty',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Loyalty',
        'View Loyalty',
        'Edit Existing Loyalty',
        'Delete Existing Loyalty',
    )
);

// sub module
$module['sub']['Loyalty Ranks'] = array(
    'title'=>'Loyalty Ranks',
    'module_label'=>'Loyalty Ranks', // this will display on create/view/edit form
    'controller'=>'loyalty_ranks',
    'description'=>'Manage all Loyalty Ranks',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Loyalty Ranks',
        'View Loyalty Ranks',
        'Edit Existing Loyalty Ranks',
        'Delete Existing Loyalty Ranks',
    )
);
// sub module
$module['sub']['Bank Accounts'] = array(
    'title'=>'Bank Accounts',
    'module_label'=>'Bank Accounts', // this will display on create/view/edit form
    'controller'=>'bank_accounts',
    'description'=>'Manage all Bank Accounts',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Bank Accounts',
        'View Bank Accounts',
        'Edit Existing Bank Accounts',
        'Delete Existing Bank Accounts',
    )
);
// sub module
$module['sub']['Banks'] = array(
    'title'=>'Banks',
    'module_label'=>'Banks', // this will display on create/view/edit form
    'controller'=>'banks',
    'description'=>'Manage all Banks',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Banks',
        'View Banks',
        'Edit Existing Banks',
        'Delete Existing Banks',
    )
);
// sub module
$module['sub']['Customer'] = array(
    'title'=>'Customer',
    'module_label'=>'Customer', // this will display on create/view/edit form
    'controller'=>'customer',
    'description'=>'Manage all Customer',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Customer',
        'View Customer',
        'Edit Existing Customer',
        'Delete Existing Customer',
    )
);
// sub module
$module['sub']['Supplier'] = array(
    'title'=>'Supplier',
    'module_label'=>'Supplier', // this will display on create/view/edit form
    'controller'=>'supplier',
    'description'=>'Manage all Supplier',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Supplier',
        'View Supplier',
        'Edit Existing Supplier',
        'Delete Existing Supplier',
    )
);

// sub module
$module['sub']['Pump'] = array(
    'title'=>'Pump',
    'module_label'=>'Pump', // this will display on create/view/edit form
    'controller'=>'pump',
    'description'=>'Manage all Pump',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Pump',
        'View Pump',
        'Edit Pump',
        'Delete Pump',
    )
);

$module['sub']['Dipstick'] = array(
    'title'=>'Dipstick',
    'module_label'=>'Dipstick', // this will display on create/view/edit form
    'controller'=>'Dipstick',
    'description'=>'Manage all Dipstick',
    'icon'=>'la-dropbox',
    'roles'=>array('Add Dipstick','View Dipstick','Edit Existing Dipstick','Delete Existing Dipstick','Approve Dipstick')
);

// // sub module
// $module['sub']['Loyal'] = array(
//     'title'=>'Loyal',
//     'module_label'=>'Loyal', // this will display on create/view/edit form
//     'controller'=>'loyal',
//     'description'=>'Manage all Loyal',
//     'icon'=>'la-user',
//     'roles'=>array(   
//         'Add Loyal',
//         'View Loyal',
//         'Edit Existing Loyal',
//         'Delete Existing Loyal',
//     )
// );

// // sub module
// $module['sub']['Rank'] = array(
//     'title'=>'Rank',
//     'module_label'=>'Rank', // this will display on create/view/edit form
//     'controller'=>'rank',
//     'description'=>'Manage all Rank',
//     'icon'=>'la-user',
//     'roles'=>array(   
//         'Add Rank',
//         'View Rank',
//         'Edit Existing Rank',
//         'Delete Existing Rank',
//     )
// );
// sub module
$module['sub']['Owner'] = array(
    'title'=>'Owner',
    'module_label'=>'Owner', // this will display on create/view/edit form
    'controller'=>'owner',
    'description'=>'Manage all Owner',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Owner',
        'View Owner',
        'Edit Existing Owner',
        'Delete Existing Owner',
    )
);
// // sub module
// $module['sub']['Rank'] = array(
//     'title'=>'Rank',
//     'module_label'=>'Rank', // this will display on create/view/edit form
//     'controller'=>'rank',
//     'description'=>'Manage all Rank',
//     'icon'=>'la-user',
//     'roles'=>array(   
//         'Add Rank',
//         'View Rank',
//         'Edit Existing Rank',
//         'Delete Existing Rank',
//     )
// );



// sub module
$module['sub']['Shift'] = array(
    'title'=>'Shift',
    'module_label'=>'Shift', // this will display on create/view/edit form
    'controller'=>'shift',
    'description'=>'Manage all Shift',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Shift',
        'View Shift',
        'Edit Existing Shift',
        'Delete Existing Shift',
    )
);


$module['main']['menu'] = $submenu;
$this->modules['Master Files']  = $module;
?>