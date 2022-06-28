<?php

use MoOTP\Handler\MoOTPActionHandlerHandler;
 use MoOTP\Helper\MoUtility;   //registrationandplans
use MoOTP\Objects\PluginPageDetails;
use MoOTP\Objects\TabDetails;
 use MoOTP\Helper\MoConstants; 

$registered 	= MoUtility::micr();  //registrationandplans
$activated      = MoUtility::mclv();    //registrationandplans
$plan       	= MoUtility::micv();    //registrationandplans
$disabled       = $registered && $activated ? "" : "disabled"; //registrationandplans
$current_user 	= wp_get_current_user();  //registrationandplans
$email 			= get_mofr_option("admin_email");  //registrationandplans
$phone 			= get_mofr_option("admin_phone");   //registrationandplans

$controller 	= MOFLR_DIR . 'controllers/';
$adminHandler 	= MoOTPActionHandlerHandler::instance();

$tabDetails = TabDetails::instance();

include $controller . 'navbar.php';

echo "<div class='mofr-opt-content'>
        <div id='mofrblock' class='mofr_customer_validation-modal-backdrop dashboard'>".
            "<img src='".esc_attr(MOFLR_LOADER_URL)."'>".
        "</div>";

$getPage = sanitize_text_field($_GET[ 'page' ]);
if(isset( $getPage ))
{
    
    foreach ($tabDetails->_tabDetails as $tabs) {
        if($tabs->_menuSlug == sanitize_text_field($_GET['page'])) {
            include $controller . $tabs->_view;
        }
    }

    do_action('mo_otp_verification_add_on_controller');
    include $controller . 'support.php';
}

echo "</div>";

echo'   <div class="mo_otp_footer"> 
  <div class="mofr-otp-mail-button">
  <img src="'.esc_attr(MOFLR_MAIL_LOGO).'" class="mofr_show_support_form" id="helpButton"></div>
  <button type="button" class="mofr-otp-help-button-text">Hello there!<br>Need Help? Drop us an Email</button>
  </div>';
        