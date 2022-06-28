<?php

use MoOTP\Helper\MoConstants;

$current_user 	= wp_get_current_user();
$email 			= get_mofr_option("admin_email"); //registrationandplans
$phone 			= get_mofr_option("admin_phone");   //registrationandplans
$phone          = $phone ? $phone : '';				//registrationandplans
$support        = MoConstants::FEEDBACK_EMAIL;

include MOFLR_DIR . 'views/support.php';