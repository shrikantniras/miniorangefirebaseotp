<?php

use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\PluginPageDetails;
use MoOTP\Objects\Tabs;

$mo_create_new_page 			   = admin_url().'post-new.php?post_type=page';
$mo_firebase_loginreg_FormCSS      = get_mofr_option('loginreg_form_css')? get_mofr_option('loginreg_form_css') : file_get_contents(MOFLR_DIR . 'includes/css/mofr_loginreg_form.css');

$mo_firebase_login_form_enable     = get_mofr_option('login_form_enable')?"checked":"";
$mo_firebase_loginredirectPageurl  = MoUtility::is_blank(get_mofr_option('login_form_redirecturl'))? "": get_mofr_option('login_form_redirecturl');

$mo_firebase_registration_form_enable = get_mofr_option('registration_form_enable')?"checked":"";
$mo_firebase_regredirectPageurl       = MoUtility::is_blank(get_mofr_option('reg_form_redirecturl'))? "": get_mofr_option('reg_form_redirecturl');
$mo_firebase__default_user_role       = get_mofr_option("default_user_role");

$mo_nonce_mo_firebase_form_save_option = wp_create_nonce( 'mo_nonce_mo_firebase_form_save_option' );

include MOFLR_DIR . 'views/LoginRegistrationForm.php';