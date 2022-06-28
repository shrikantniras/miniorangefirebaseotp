<?php

use MoOTP\Handler\MoRegistrationHandler;
use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoUtility;

$url = MoConstants::HOSTNAME.'/moas/login'.'?redirectUrl='.MoConstants::HOSTNAME.'/moas/viewlicensekeys';
/** @var MoRegistrationHandler $handler */
$handler 	 = MoRegistrationHandler::instance();

if(get_mofr_option('registration_status') === 'MO_OTP_DELIVERED_SUCCESS'
		|| get_mofr_option('registration_status')  === 'MO_OTP_VALIDATION_FAILURE'
		|| get_mofr_option('registration_status')  === 'MO_OTP_DELIVERED_FAILURE')
{   
	
	$admin_phone = get_mofr_option('admin_phone') ? get_mofr_option('admin_phone') : "";
    $nonce       = $handler->get_nonce_value();
	include MOFLR_DIR . 'views/account/mofrverify.php';
}
elseif (get_mofr_option ( 'verify_customer' ))
{   

	$admin_email = get_mofr_option('admin_email') ? get_mofr_option('admin_email') : "";
    $nonce       = $handler->get_nonce_value();
	include MOFLR_DIR . 'views/account/mofrlogin.php';
}
elseif (! MoUtility::micr())
{   

	$current_user = wp_get_current_user();
	$admin_phone  = get_mofr_option('admin_phone') ? get_mofr_option('admin_phone') : "";
	$nonce        = $handler->get_nonce_value();
    delete_site_option ( 'password_mismatch' );
    update_mofr_option ( 'new_registration', 'true' );
	include MOFLR_DIR . 'views/account/mofrregister.php';
}
elseif (MoUtility::micr() && !MoUtility::mclv())
{   
    $nonce       = $handler->get_nonce_value();
    include MOFLR_DIR . 'views/account/mofrverify-lk.php';
}
else
{   
  
    $admin_email = get_mofr_option('admin_email') ? get_mofr_option('admin_email') : "";
	$customer_id = get_mofr_option('admin_customer_key');
	$api_key     = get_mofr_option('admin_api_key');
	$token 		 = get_mofr_option('customer_token');
	$vl 		 = MoUtility::mclv() && !MoUtility::is_mg();
    $nonce       = $adminHandler->get_nonce_value();
    $regnonce    = $handler->get_nonce_value();
	include MOFLR_DIR . 'views/account/mofrprofile.php';
}