<?php

namespace MoOTP;

use MoOTP\Traits\Instance;
use MoOTP\Helper\MenuItems;
use MoOTP\Handler\MoOTPActionHandlerHandler;
use MoOTP\Handler\LoginRegistrationForm;
use MoOTP\Handler\MoRegistrationHandler;
use MoOTP\Helper\MoDisplayMessages;
use MoOTP\Helper\MoMessages;

if(! defined( 'ABSPATH' )) exit;

final class MoOTPInitializer
{

	use Instance;

	private function __construct()
	{
		$this->initialize_values();
		$this->initialize_hooks();
		$this->initialize_helpers();
		$this->initialize_handlers();

	}

	private function initialize_hooks()
	{
		add_action( 'admin_menu'             , array( $this, 'miniorange_customer_validation_menu'));
		add_action( 'admin_enqueue_scripts'	 , array( $this, 'mo_registration_plugin_settings_style'));
		add_action( 'admin_enqueue_scripts'	 , array( $this, 'mo_registration_plugin_settings_script'));
		add_action( 'mo_registration_show_message'	, array( $this, 'mo_show_otp_message'    		 			 ),1   , 2);

	}

	private function initialize_values(){
		if (!get_mofr_option("mofr_firebase_auth_key")) {
			update_mofr_option("mofr_firebase_auth_key",10);
		}
	}

	private function initialize_handlers()
	{
		MoOTPActionHandlerHandler::instance();
        LoginRegistrationForm::instance();		
		MoRegistrationHandler::instance();
		
	}

	 /**
     * Initialize all the helper classes
     */
	private function initialize_helpers()
	{
		MoMessages::instance();    
	}

	function miniorange_customer_validation_menu()
	{
	    MenuItems::instance();
	}

	function mo_registration_plugin_settings_style()
	{
		wp_enqueue_style( 'mofr_customer_validation_admin_settings_style'	 , MOFLR_CSS_URL);
		wp_enqueue_style( 'mofr_customer_validation_inttelinput_style', MOFLR_INTTELINPUT_CSS);
		wp_enqueue_style( 'mofr_customer_validation_firebase_boot_style', MOFLR_BOOT_CSS);
	}

	function mo_registration_plugin_settings_script()
	{
		$countryVal = [];
		wp_enqueue_script( 'mofr_customer_validation_admin_settings_script', MOFLR_JS_URL , array('jquery'));
	}
    
    /**
	 * This function runs when mo_registration_show_message hook
	 * is initiated. The hook runs to show a plugin generated
	 * message to the user in the admin dashboard.
	 *
	 * @param $content refers to the message content
	 * @param $type refers to the type of message
	 */
	function mo_show_otp_message($content,$type)
	{
		new MoDisplayMessages($content,$type);
	}

	function  mo_customer_validation_options()
	{
		include MOFLR_DIR . 'controllers/main-controller.php';
	}

}
