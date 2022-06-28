<?php

use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\PluginPageDetails;
use MoOTP\Objects\Tabs;

$mo_firebase_registration_form_enable = get_mofr_option('registration_form_enable')?"checked":"";
$mo_firebase_registration_FormCSS     = get_mofr_option('registration_form_css');
$mo_firebase_regredirectPageurl       = MoUtility::is_blank(get_mofr_option('reg_form_redirecturl'))? "": get_mofr_option('reg_form_redirecturl');
$mo_firebase__default_user_role       = get_mofr_option("default_user_role");

include MOFLR_DIR . 'views/registrationform.php';