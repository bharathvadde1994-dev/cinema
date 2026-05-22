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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['auth/login'] = 'auth/login';
$route['auth/signup'] = 'auth/signup';
$route['auth/signup/details'] = 'auth/signup_details';
$route['auth/google'] = 'auth/google';
$route['auth/google/callback'] = 'auth/google_callback';
$route['auth/logout'] = 'auth/logout';
$route['cinemas'] = 'cinemas/index';
$route['booking'] = 'booking/index';
$route['booking/add_to_cart'] = 'booking/add_to_cart';
$route['booking/cart'] = 'booking/cart';
$route['booking/checkout'] = 'booking/checkout';
$route['booking/place_order'] = 'booking/place_order';
$route['booking/edit/(:any)'] = 'booking/edit/$1';
$route['booking/remove/(:any)'] = 'booking/remove/$1';
$route['booking/details/(:any)'] = 'booking/details/$1';
$route['booking/upload/(:any)'] = 'booking/upload/$1';
$route['profile'] = 'profile/index';
$route['profile/update'] = 'profile/update';
$route['profile/addresses'] = 'profile/addresses';
$route['profile/payment-methods'] = 'profile/payment_methods';
$route['profile/orders'] = 'profile/orders';
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';
$route['admin/forgot-password'] = 'admin/forgot_password';
$route['admin/forgot-password/sent'] = 'admin/forgot_password_sent';
$route['admin/logout'] = 'admin/logout';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/bookings'] = 'admin/bookings';
$route['admin/bookings/delete/(:num)'] = 'admin/delete_booking/$1';
$route['admin/bookings/assets-reviewed/(:num)'] = 'admin/mark_assets_reviewed/$1';
$route['admin/bookings/request-reupload/(:num)'] = 'admin/request_reupload/$1';
$route['admin/bookings/document/(:num)/(:any)'] = 'admin/document/$1/$2';
$route['admin/bookings/(:num)'] = 'admin/booking/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
