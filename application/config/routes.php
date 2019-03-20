<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//api
$route['api/login'] = "api/ApiLogin/login";
//Sales
$route['api/sales/(:any)'] = "api/Sales/sales";
//Monhtly sales
$route['api/monthly-sales/(:any)'] = "api/Sales/monhtlysales";
//sales report
$route['api/report-sales'] = "api/Sales/resportsales";
//Deposits
$route['api/deposits/(:any)'] = "api/Deposit/deposits";
//Inventory
$route['api/inventory'] = "api/Inventory/inventory";
//Inventory date
$route['api/inventorydate/(:any)'] = "api/Inventory/inventorydate";
//Inventory date by details
$route['api/inventorydetails/(:any)'] = "api/Inventory/inventorydetails";
//Owner
$route['api/owner'] = "api/Owner/owner";
//Edit Owner 
$route['api/owner-update'] = "api/Owner/ownerupdate";
//Patient
$route['api/patient'] = "api/Patient/add";

$route['api/patient-edit'] = "api/Patient/add_edit";
$route['api/patient-delete/(:any)'] = "api/Patient/remove";
$route['api/patients/(:any)'] = "api/Patient/list";
$route['api/patient-all'] = "api/Patient/all";
//update image
$route['api/upload-image'] = "api/Patient/upload_image";



