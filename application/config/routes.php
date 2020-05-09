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
$route['users/create-account'] = "api/users/createUseraccount";
$route['users/login'] = "api/users/userLogin";
$route['users/forgot-password'] = "api/users/forget_password";
$route['users/update-profile'] = "api/users/profile_update";
$route['users/update-profile-img'] = "api/users/upload_profile_img";
$route['users/change-password'] = "api/users/change_password";
$route['get-storage-location'] = "api/sitelink_api/index";
$route['get-unit-by-location-code'] = "api/sitelink_api/get_units_by_location_code";
$route['add-tenant'] = "api/sitelink_api/addTenant";
$route['need-help'] = "api/users/needHelp";
$route['contact'] = "api/users/contactUs";
$route['about-us'] = "api/users/aboutUs";
$route['content/about-us'] = "admin/users/content_controller/aboutUs";
$route['content/terms-condition'] = "admin/users/content_controller/termsCondtn";
$route['terms-condition'] = "api/users/termsCondtn";
$route['search'] = "api/sitelink_api/storageSerachByCity";
$route['cities'] = "api/sitelink_api/GetAllCity";
$route['users/autologin'] = "api/users/auto_login";
$route['logout'] = "api/users/userLogout";

$route['user/payment'] = "api/sitelink_api/payPaymentToSiteLink";

$route['addrating'] = "api/sitelink_api/storeStorageRating";
$route['user/reservation-history'] = "api/sitelink_api/reservationHistoryList";
$route['user/reservation-detail'] = "api/sitelink_api/getReservationDetail";
$route['user/add-app-issue'] = "api/users/addAppIssue";
$route['offered-feature'] = "api/users/offerFeaturedList";
$route['cancel-reservation'] = "api/sitelink_api/cancelReservation";
$route['user/storage-rating'] = "api/users/getStoreRatingByUser";
$route['user/notification'] = "api/users/userNotificationStatusChange";
$route["notf"] = "api/sitelink_api/notification_test";
$route["user/send-email"] = "api/users/sendUserQueryToAdminEmail";




#--------------------------------- ADMIN URL -------------------------------------------------------------
$route['login'] = "admin/login/login";
$route['login/submit'] = "admin/login/login/login_submited";
$route['forgot-password'] = "admin/login/login/forgotPassword";
$route['forgot-password/submited'] = "admin/login/login/forgotPasswordSubmited";
$route['updated-password/(:any)'] = "admin/common/index/$1";
$route['users'] = "admin/users/user_controller";
$route['subadmin/dashboard'] = "admin/common/subadmindashboard";
$route['change-password'] = "admin/common/change_password";
$route['change-password/submited'] = "admin/common/changepasswordsubmited";
$route['queries'] = "admin/users/query_controller/userqueries";
$route['subadmin-list'] = "admin/subadmin/subadmin_controller/index";
$route['sub-admin/(:num)'] = "admin/subadmin/subadmin_controller/loadsubadminview/$1";
$route['subadmin/submited'] = "admin/subadmin/subadmin_controller/subadminsubmited";
$route['about'] = "admin/users/content_controller/aboutUs";
$route["term-condition-list"] = "admin/users/content_controller/termsCondtn";
$route["contact-us"] = "admin/users/content_controller/contactUs";



/*
 *  15/oct/2018
 */
$route["offer-feature-list"] = "admin/offer_featured/offer_controller/index";
$route["payment-list"] = "admin/users/payment_controller/index";
$route["user-detail/(:num)"] = "admin/users/user_controller/userDetail/$1";
$route["rating"] = "admin/rating/rating_controller/index";
$route["rating-delete/(:num)"] = "admin/rating/rating_controller/delete/$1";
$route["analytics"] = "admin/analytics/analytics_controller";





$route['default_controller'] = 'welcome';



$route['404_override'] = 'custom404';
$route['translate_uri_dashes'] = FALSE;


