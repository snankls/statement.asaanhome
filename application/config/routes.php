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
$route['default_controller'] = 'frontend';//'migrate/first_run';
$route['404_override'] = 'Error404';
$route['translate_uri_dashes'] = FALSE;

/*
-------------------------------------------------------------------------
	Frontend
-------------------------------------------------------------------------
*/

$route['index'] = 'frontend/index';


//Login
$route['login'] = 'frontend/login';
$route['process-login'] = 'frontend/process_login';
$route['logout'] = 'frontend/logout';

//Dashboard
$route['dashboard'] = 'frontend/index';

//Projects
$route['project'] = 'project/project';
$route['project/add'] = 'project/project_setup';
$route['project/edit/(:any)'] = 'project/project_setup/$1';
$route['project/view/(:any)'] = "project/project_view/$1";

//Inventory
$route['inventory'] = 'inventory/inventory';
$route['inventory/add'] = 'inventory/inventory_setup';
$route['inventory/edit/(:any)'] = 'inventory/inventory_setup/$1';
$route['inventory/view/(:any)'] = "inventory/inventory_view/$1";

//Booking
$route['booking'] = 'booking/booking';
$route['booking/add'] = 'booking/booking_setup';
$route['booking/edit/(:any)'] = 'booking/booking_setup/$1';
$route['booking/view/(:any)'] = "booking/booking_view/$1";

//Collection
$route['collection'] = 'collection/collection';

//Voucher
$route['voucher'] = 'voucher/voucher';
$route['voucher/add'] = 'voucher/voucher_setup';
$route['voucher/edit/(:any)'] = 'voucher/voucher_setup/$1';
$route['voucher/view/(:any)'] = "voucher/voucher_view/$1";

//Chart of Accounts
$route['chart-of-accounts/level-1'] = 'chart_of_accounts/chart_of_accounts_level_1';
$route['chart-of-accounts/level-2'] = 'chart_of_accounts/chart_of_accounts_level_2';
$route['chart-of-accounts/level-3'] = 'chart_of_accounts/chart_of_accounts_level_3';
$route['chart-of-accounts/level-4'] = 'chart_of_accounts/chart_of_accounts_level_4';

//Reports
$route['reports/chart-of-accounts'] = 'reports/chart_of_accounts';
$route['reports/finance-ledger'] = 'reports/finance_ledger';
$route['reports/activity-report'] = 'reports/leads_activity_report';
$route['reports/kpi-report'] = 'reports/leads_kpi_report';

//Team
$route['teams'] = 'teams/teams';

//Leads
$route['leads'] = 'leads/leads';
$route['leads/add'] = 'leads/leads_setup';
$route['leads/edit/(:any)'] = 'leads/leads_setup/$1';
$route['leads/view/(:any)'] = "leads/leads_view/$1";
$route['leads/todo-list'] = 'leads/todo';
$route['leads/receipt'] = "leads/leads_receipt";

//Import
$route['leads/import'] = 'import/leads_import';

//Attendance
$route['attendance'] = 'attendance/attendance';
$route['attendance/individual'] = 'attendance/attendance_individual';
$route['attendance/group'] = 'attendance/attendance_group';
$route['branches'] = 'attendance/branches';

//Leave Application
$route['leave-application'] = 'attendance/leave_application';
$route['leave-application/add'] = 'attendance/leave_application_setup';
$route['leave-application/edit/(:any)'] = 'attendance/leave_application_setup/$1';

//Users
$route['user'] = 'user/user';
$route['user/add'] = 'user/user_setup';
$route['user/edit/(:any)'] = 'user/user_setup/$1';
$route['user/copy/(:any)'] = 'user/user_setup/$1/1';
$route['user/view/(:any)'] = "user/user_view/$1";
$route['user/change-password'] = 'user/change_password';
$route['forget-password'] = 'user/forget_password';
$route['recover'] = 'user/recover_password';

//Log History
$route['log-history'] = 'log_history/log_history';

//Clients
$route['client'] = 'client/client';
