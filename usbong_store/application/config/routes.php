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
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'b';//'b';//'home'; //'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;//FALSE;
//$route['w/(:any)'] = 'w/index/$1'; //added by Mike, 20170623
$route['w/(:any)/(:any)'] = 'w/index/$1/$2'; //added by Mike, 20170623
$route['request/(:any)/(:any)'] = 'request/index/$1/$2'; //added by Mike, 20171217
$route['sell/(:any)/(:any)'] = 'sell/index/$1/$2'; //added by Mike, 20180407

// routing for auto-email controller
// routing rules are needed cause auto-email controllers are located in controllers/auto-email instead of controllers/
$route['auto-email']                               = 'auto-email/administer/index';
$route['auto-email/administer/(:num)']             = 'auto-email/administer/index/$1';
$route['auto-email/queue/(:num)/(:num)']           = 'auto-email/administer/queue/$1/$2';
$route['auto-email/resume']                        = 'auto-email/administer/resume';
$route['auto-email/create/template/(:num)']        = 'auto-email/administer/create/template/$1';
$route['auto-email/create/data']                   = 'auto-email/administer/create/data';
$route['auto-email/create/products/(:num)/(:any)'] = 'auto-email/administer/create/products/$1/$2';
$route['auto-email/create/products/(:num)']        = 'auto-email/administer/create/products/$1';
$route['auto-email/create/save']                   = 'auto-email/administer/create/save';
$route['auto-email/edit/data/(:num)']              = 'auto-email/administer/edit/data/$1';
$route['auto-email/edit/products/(:num)/(:any)']   = 'auto-email/administer/edit/products/$1/$2';
$route['auto-email/edit/products/(:num)']          = 'auto-email/administer/edit/products/$1';
$route['auto-email/edit/save/(:num)']              = 'auto-email/administer/edit/save/$1';

$route['auto-email/preview/(:num)'] = 'auto-email/administer/preview/$1';
$route['auto-email/unsubscribe/(:any)'] = 'auto-email/administer/unsubscribe/$1';

// routing for manage controller
$route['manage/merchants/add'] = 'manage/merchant_add';
$route['manage/merchants/edit/(:num)'] = 'manage/merchant_edit/$1';