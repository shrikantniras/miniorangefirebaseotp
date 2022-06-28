<?php

use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoMessages;
use MoOTP\Objects\Tabs;
use MoOTP\Helper\MoUtility;

$request_uri    = remove_query_arg(['addon','form','subpage'],sanitize_text_field($_SERVER['REQUEST_URI']));
$profile_url	= add_query_arg( array('page' => $tabDetails->_tabDetails[Tabs::ACCOUNT]->_menuSlug), $request_uri );

$registerMsg    = MoMessages::showMessage(MoMessages::REGISTER_WITH_US,[ "url"=> $profile_url ]);
$activationMsg  = MoMessages::showMessage(MoMessages::ACTIVATE_PLUGIN,[ "url"=> $profile_url ]);
$active_tab 	= sanitize_text_field($_GET['page']);

include MOFLR_DIR . 'views/navbar.php';