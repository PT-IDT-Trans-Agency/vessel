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
$route['default_controller'] = 'Login';
$route['login/post'] = 'Login/post';
$route['logout'] = 'Logout';

$route['dashboard'] = 'Dashboard';
$route['vessel-line-up'] = 'VesselLineUp/index';
$route['vessel-line-up/add'] = 'VesselLineUp/add';
$route['vessel-line-up/edit'] = 'VesselLineUp/edit';
$route['vessel-line-up/delete/(.+)'] = 'VesselLineUp/delete/$1';
$route['vessel-line-up/save'] = 'VesselLineUp/save';
$route['vessel-line-up/print_wj/(.+)'] = 'VesselLineUp/print_wj/$1';
$route['vessellineup/export_excel'] = 'VesselLineUp/export_excel';

$route['cabang'] = 'Cabang';
$route['apicabang/gets_cabang'] = 'ApiCabang/gets_cabang';
$route['apicabang/insert_cabang'] = 'ApiCabang/insert_cabang';
$route['apicabang/delete_cabang'] = 'ApiCabang/delete_cabang';

$route['apiport/gets_master_port'] = 'ApiPort/gets_master_port';
$route['apiport/update_data_port'] = 'ApiPort/update_data_port';
$route['apiport/delete_data_port'] = 'ApiPort/delete_data_port';

$route['vessel'] = 'Vessel';
$route['apivessel/gets_master_vessel'] = 'ApiVessel/gets_master_vessel';
$route['apivessel/update_data_vessel'] = 'ApiVessel/update_data_vessel';
$route['apivessel/delete_data_vessel'] = 'ApiVessel/delete_data_vessel';

$route['shipper'] = 'Shipper';
$route['apishipper/gets_master_shipper'] = 'ApiShipper/gets_master_shipper';
$route['apishipper/update_data_shipper'] = 'ApiShipper/update_data_shipper';
$route['apishipper/delete_data_shipper'] = 'ApiShipper/delete_data_shipper';

$route['agent'] = 'Agent';
$route['buyer'] = 'Buyer';
$route['destination'] = 'Destination';
$route['pbm'] = 'PBM';

$route['category'] = 'Category';
$route['category/delete/(.+)'] = 'Category/delete/$1';
$route['category/add'] = 'Category/add';
$route['apicategory/gets_master_category'] = 'ApiCategory/gets_master_category';
$route['apicategory/get_information_category/(.+)'] = 'ApiCategory/get_information_category/$1';
$route['apicategory/insert_category'] = 'ApiCategory/insert_category';
$route['apicategory/update_data_category'] = 'ApiCategory/update_data_category';
$route['apicategory/is_duplicate_category/(.+)'] = 'ApiCategory/is_duplicate_category/$1';

$route['service'] = 'Service';
$route['service/delete/(.+)'] = 'Service/delete/$1';
$route['service/add'] = 'Service/add';
$route['apiservice/gets_master_service'] = 'ApiService/gets_master_service';
$route['apiservice/get_combo_service_by_category'] = 'ApiService/get_combo_service_by_category';
$route['apiservice/get_information_service/(.+)'] = 'ApiService/get_information_service/$1';
$route['apiservice/insert_service'] = 'ApiService/insert_service';
$route['apiservice/update_data_service'] = 'ApiService/update_data_service';
$route['apiservice/is_duplicate_service/(.+)/(.+)'] = 'ApiService/is_duplicate_service/$1/$2';

$route['principal'] = 'Principal';
$route['principal/delete/(.+)'] = 'Principal/delete/$1';
$route['principal/add'] = 'Principal/add';
$route['principal/gets_master_principal'] = 'Principal/gets_master_principal';
$route['principal/get_information_principal/(.+)'] = 'Principal/get_information_principal/$1';
$route['principal/insert_principal'] = 'Principal/insert_principal';
$route['principal/update_data_principal'] = 'Principal/update_data_principal';
$route['principal/is_duplicate_principal/(.+)'] = 'Principal/is_duplicate_principal/$1';

$route['apiprincipal/gets_master_principal'] = 'ApiPrincipal/gets_master_principal';

$route['user'] = 'User';
$route['apiuser/gets_user'] = 'ApiUser/gets_user';
$route['apiuser/update_data_user'] = 'ApiUser/update_data_user';
$route['apiuser/delete_data_user'] = 'ApiUser/delete_data_user';
$route['apiuser/change_password'] = 'ApiUser/change_password';

$route['apigroup/gets_group'] = 'ApiGroup/gets_group';
$route['apigroup/update_group'] = 'ApiGroup/update_group';
$route['apigroup/delete_group'] = 'ApiGroup/delete_group';
$route['apigroup/gets_group_akses'] = 'ApiGroup/gets_group_akses';
$route['apigroup/get_group_akses'] = 'ApiGroup/get_group_akses';
$route['apigroup/update_konfigurasi_menu'] = 'ApiGroup/update_konfigurasi_menu';


$route['apidestination/gets_master_destination'] = 'ApiDestination/gets_master_destination';
$route['apidestination/update_data_destination'] = 'ApiDestination/update_data_destination';
$route['apidestination/delete_data_destination'] = 'ApiDestination/delete_data_destination';

$route['apiagent/gets_master_agent'] = 'ApiAgent/gets_master_agent';
$route['apiagent/update_data_agent'] = 'ApiAgent/update_data_agent';
$route['apiagent/delete_data_agent'] = 'ApiAgent/delete_data_agent';

$route['apipbm/gets_master_pbm'] = 'ApiPBM/gets_master_pbm';
$route['apipbm/update_data_pbm'] = 'ApiPBM/update_data_pbm';
$route['apipbm/delete_data_pbm'] = 'ApiPBM/delete_data_pbm';

$route['apibuyer/gets_master_buyer'] = 'ApiBuyer/gets_master_buyer';
$route['apibuyer/update_data_buyer'] = 'ApiBuyer/update_data_buyer';
$route['apibuyer/delete_data_buyer'] = 'ApiBuyer/delete_data_buyer';

$route['api/is_duplicate_kode/(.+)'] = 'Api/is_duplicate_kode/$1';
$route['api/is_duplicate_username/(.+)'] = 'Api/is_duplicate_username/$1';
$route['api/update_kegiatan'] = 'Api/update_kegiatan';
$route['apivessellineup/gets_line_up'] = 'ApiVesselLineUp/gets_line_up';
$route['apivessellineup/get_line_up'] = 'ApiVesselLineUp/get_line_up';
$route['apivessellineup/update_data_line_up'] = 'ApiVesselLineUp/update_data_line_up';
$route['apivessellineup/delete'] = 'ApiVesselLineUp/delete';
$route['apivessellineup/get_export_set_column_show'] = 'ApiVesselLineUp/get_export_set_column_show';
$route['apivessellineup/update_export_set_column_show'] = 'ApiVesselLineUp/update_export_set_column_show';

$route['apiepda/update_data_epda'] = 'ApiEPDA/update_data_epda';
$route['apiepda/gets_epda'] = 'ApiEPDA/gets_epda';

$route['apidashboard/get_data_filter_vlu'] = 'ApiDashboard/get_data_filter_vlu';
$route['apidashboard/gets_summary_perbulan_vessel'] = 'ApiDashboard/gets_summary_perbulan_vessel';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
