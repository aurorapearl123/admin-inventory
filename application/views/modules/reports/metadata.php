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

//Daily Sales Report
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Daily Sales Report')) {
    $submenu[] = array('label'=>'Daily Sales Report','url'=>site_url('reports/daily_sales'),'icon'=>'bullet','subitem'=>array());
}

//Credit Breakdown Report
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Credit Breakdown Report')) {
    $submenu[] = array('label'=>'Credit Breakdown Report','url'=>site_url('reports/credit_breakdown_report'),'icon'=>'bullet','subitem'=>array());
}

//Sales Breakdown Report
if ($this->userrole_model->has_access($this->session->userdata('current_user')->userID,'View Sales Breakdown Report')) {
    $submenu[] = array('label'=>'Sales Breakdown Report','url'=>site_url('reports/sales_breakdown_report'),'icon'=>'bullet','subitem'=>array());
}



// main module
$module = array();
$module['main']['title']        = 'Reports';
$module['main']['controller']   = 'reports';
$module['main']['description']  = 'Manage all Reports';
$module['main']['icon']         = 'la-newspaper-o';
$module['main']['roles']        = array();

// sub module
$module['sub']['Daily Sales Report'] = array(
    'title'=>'Daily Sales Report',
    'module_label'=>'Daily Sales Report', // this will display on create/view/edit form
    'controller'=>'reports',
    'description'=>'Manage all Daily Sales Report',
    'icon'=>'la-user',
    'roles'=>array(   
        'Add Daily Sales Report',
        'View Daily Sales Report',
        'Edit Daily Sales Report',
        'Delete Daily Sales Report',
		'View Credit Breakdown Report',
		'View Sales Breakdown Report',
    )
);


$module['main']['menu'] = $submenu;
$this->modules['Reports']  = $module;
?>