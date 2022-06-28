<?php

use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\PluginPageDetails;
use MoOTP\Objects\Tabs;

$mo_firebase_login_form_enable     = get_mofr_option('login_form_enable')?"checked":"";
$mo_firebase_login_FormCSS     	   = get_mofr_option('login_form_css');
$mo_firebase_loginredirectPageurl  = MoUtility::is_blank(get_mofr_option('login_form_redirecturl'))? "": get_mofr_option('login_form_redirecturl');


include MOFLR_DIR . 'views/settings.php';