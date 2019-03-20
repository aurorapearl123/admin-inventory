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
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Purchase Order')) {
    $submenu[] = array('label'=>'Purchase Order','url'=>site_url('purchase_order/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Sales Order')) {
    $submenu[] = array('label'=>'Sales Order','url'=>site_url('sales_order/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Receiving Report')) {
    $submenu[] = array('label'=>'Receiving Report','url'=>site_url('receiving_report/show'),'icon'=>'bullet','subitem'=>array());
}

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Delivery Report')) {
    $submenu[] = array('label'=>'Delivery Report','url'=>site_url('delivery_report/show'),'icon'=>'bullet','subitem'=>array());
}

if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Account Payable')) {
    $submenu[] = array('label'=>'Account Payables','url'=>site_url('account_payable/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Account Receivable')) {
    $submenu[] = array('label'=>'Account Receivables','url'=>site_url('account_receivable/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Payment')) {
    $submenu[] = array('label'=>'Payments','url'=>site_url('payment/show'),'icon'=>'bullet','subitem'=>array());
}
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Collection')) {
    $submenu[] = array('label'=>'Collections','url'=>site_url('collection/show'),'icon'=>'bullet','subitem'=>array());
}

// main module
$module = array();
$module['main']['title'] 		= 'Transactions';
$module['main']['controller'] 	= 'transactions';
$module['main']['description'] 	= 'Manage all Transactions';
$module['main']['icon'] 		= 'la-cart-plus';
$module['main']['roles'] 		= array();







// sub module
$module['sub']['Purchase Order'] = array(
    'title'=>'Purchase Order',
    'module_label'=>'Purchase Order', // this will display on create/view/edit form
    'controller'=>'purchase_order',
    'description'=>'Manage all Purchase Order',
    'icon'=>'la-cart-arrow-down',
    'roles'=>array(
        'Add Purchase Order',
        'View Purchase Order',
        'Edit Purchase Order',
        'Delete Purchase Order',
        'Confirm Purchase Order',
        'Cancel Purchase Order',
    )
);
// sub module
$module['sub']['Sales Order'] = array(
    'title'=>'Sales Order',
    'module_label'=>'Sales Order', // this will display on create/view/edit form
    'controller'=>'sales_order',
    'description'=>'Manage all Sales Order',
    'icon'=>'la-cart-arrow-down',
    'roles'=>array(
        'Add Sales Order',
        'View Sales Order',
        'Edit Sales Order',
        'Delete Sales Order',
        'Confirm Sales Order',
        'Cancel Sales Order',
    )
);

// sub module
$module['sub']['Receiving Report'] = array(
    'title'=>'Receiving Report',
    'module_label'=>'Receiving Report', // this will display on create/view/edit form
    'controller'=>'rr',
    'description'=>'Manage all Receiving Report',
    'icon'=>'la-check-square',
    'roles'=>array(
        'Add Receiving Report',
        'View Receiving Report',
        'Edit Receiving Report',
        'Delete Receiving Report',
        'Confirm Receiving Report',
        'Inspect Receiving Report',
        'Cancel Receiving Report',
    )
);

// sub module
$module['sub']['Delivery Report'] = array(
    'title'=>'Delivery Report',
    'module_label'=>'Delivery Report', // this will display on create/view/edit form
    'controller'=>'rr',
    'description'=>'Manage all Delivery Report',
    'icon'=>'la-check-square',
    'roles'=>array(
        'Add Delivery Report',
        'View Delivery Report',
        'Edit Delivery Report',
        'Delete Delivery Report',
        'Confirm Delivery Report',
        'Inspect Delivery Report',
        'Cancel Delivery Report',
    )
);


// sub module
$module['sub']['Transactions'] = array(
    'title'=>'Transactions',
    'module_label'=>'Transactions', // this will display on create/view/edit form
    'controller'=>'transactions',
    'description'=>'Manage all Transactions',
    'icon'=>'la-text-height',
    'roles'=>array('View Transactions',
    )
);



//----------------------------------------------------------------------------------
// Account Payable Module
$module['sub']['Account Payable'] = array(
    'title'=>'Account Payables',
    'module_label'=>'Account Payable',
    'controller'=>'Account Payable',
    'description'=>'Manage all Account Payable',
    'icon'=>'la-hospital-o',
    'roles'=>array(
		'View Account Payable',
        'Add Account Payable',
        'Edit Account Payable',
        'Delete Account Payable',
    )
);

// Account Receivable Module
$module['sub']['Account Receivable'] = array(
    'title'=>'Account Receivables',
    'module_label'=>'Account Receivable', 
    'controller'=>'Account Receivable',
    'description'=>'Manage all Account Receivable',
    'icon'=>'la-hospital-o',
    'roles'=>array(
		'View Account Receivable',
        'Add Account Receivable',
        'Edit Account Receivable',
        'Delete Account Receivable',
    )
);

// Payment Module
$module['sub']['Payment'] = array(
    'title'=>'Payments',
    'module_label'=>'Payment', 
    'controller'=>'payment',
    'description'=>'Manage all Payment',
    'icon'=>'la-hospital-o',
    'roles'=>array(
		'View Payment',
        'Add Payment',
        'Edit Payment',
        'Delete Payment',
    )
);

// Collection Module
$module['sub']['Collection'] = array(
    'title'=>'Collections',
    'module_label'=>'Collection', 
    'controller'=>'collection',
    'description'=>'Manage all Collection',
    'icon'=>'la-hospital-o',
    'roles'=>array(
		'View Collection',
        'Add Collection',
        'Edit Collection',
        'Delete Colletion',
    )
);
//----------------------------------------------------------------------------------

// sub module
$module['sub']['Stock withdrawal slip'] = array(
    'title'=>'Stock withdrawal slip',
    'module_label'=>'Stock withdrawal slip', // this will display on create/view/edit form
    'controller'=>'Stock_withdrawal_slip',
    'description'=>'Manage all Stock withdrawal slip',
    'icon'=>'la-sort-numeric-desc',
    // 'roles'=>array('View Stock withdrawal slip',
    // )
    'roles'=>array(
		'View Stock withdrawal Slip',
        'Add Stock withdrawal Slip',
        'Edit Existing Stock withdrawal Slip',
        'Delete Stock withdrawal Slip',
    )
);

$module['main']['menu'] = $submenu;
$this->modules['Transactions']  = $module;
?>