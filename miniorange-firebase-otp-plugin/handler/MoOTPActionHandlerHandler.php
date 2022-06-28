<?php

namespace MoOTP\Handler;
if(! defined( 'ABSPATH' )) exit;

use MoOTP\Objects\TabDetails;
use MoOTP\Objects\Tabs;
use MoOTP\Traits\Instance;
use MoOTP\Helper\MoMessages;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\BaseActionHandler;
use MoOTP\Helper\MocURLOTP;

class MoOTPActionHandlerHandler extends BaseActionHandler
{
    use Instance;
	function __construct()
	{
	     parent::__construct();
		$this->_nonce = 'mo_admin_actions';

		add_action( 'admin_init',  array( $this, 'mofr_handle_admin_actions' ) );
	}


	/**
	 * This function hooks into the admin_init wordpress hook. This function
	 * checks the form being posted and routes the data to the correct function
	 * for processing. The 'option' value in the form post is checked to make
	 * the diversion.
	 */
	function mofr_handle_admin_actions()
	{
		$POST = MoUtility::sanitize_post_data($_POST);
		
		if(!isset($POST['option'])) return;
		switch(sanitize_text_field($_POST['option']))
		{
			case "mo_firebase_gateway_deatils_save_option":
				$this->_mo_save_firebase_gateway_details($_POST);
				break;
			case "mofr_check_mo_ln":
                $this->_mofr_check_l();
                break;
            case "mofr_validation_contact_us_query_option":
				$this->_mofr_validation_support_query($_POST);
				break;
		}
	}

	/**
     * This function processes the support form data before sending it to the server.
     *
     * @param array $postData
    */
	function _mofr_validation_support_query($postData)
	{
	    $email = MoUtility::sanitize_check('query_email',$postData);
	    $query = MoUtility::sanitize_check('query',$postData);
	    $phone = MoUtility::sanitize_check('query_phone',$postData);

		if(!$email || !$query)
		{
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::SUPPORT_FORM_VALUES),'ERROR');
			return;
		}

		$submitted  = MocURLOTP::mofr_submit_contact_us( $email, $phone, $query );

		if(json_last_error() == JSON_ERROR_NONE && $submitted)
		{
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::SUPPORT_FORM_SENT),'SUCCESS');
			return;
		}

		do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::SUPPORT_FORM_ERROR),'ERROR');
	}

	function _mo_save_firebase_gateway_details($data){

		update_mofr_option('gateway_apiKey' ,MoUtility::sanitize_check('mo_firebase_gateway_apiKey',$data));
		update_mofr_option('gateway_authdomain' ,MoUtility::sanitize_check('mo_firebase_gateway_authdomain',$data));
		update_mofr_option('gateway_databaseurl' ,MoUtility::sanitize_check('mo_firebase_gateway_databaseurl',$data));
		update_mofr_option('gateway_projectid' ,MoUtility::sanitize_check('mo_firebase_gateway_projectid',$data));
		update_mofr_option('gateway_storagebucket' ,MoUtility::sanitize_check('mo_firebase_gateway_storagebucket' ,$data));
		update_mofr_option('gateway_messagingsenderid' ,MoUtility::sanitize_check('mo_firebase_gateway_messagingsenderid',$data));
		update_mofr_option('gateway_appid' ,MoUtility::sanitize_check('mo_firebase_gateway_appid',$data));

		$firebase_gateway_details = array(
			'gateway_apiKey'     => MoUtility::sanitize_check('mo_firebase_gateway_apiKey',$data),
			'gateway_authdomain' => MoUtility::sanitize_check('mo_firebase_gateway_authdomain',$data),
			'gateway_databaseurl' => MoUtility::sanitize_check('mo_firebase_gateway_databaseurl',$data),
			'gateway_projectid'    => MoUtility::sanitize_check('mo_firebase_gateway_projectid',$data), 
		    'gateway_storagebucket' => MoUtility::sanitize_check('mo_firebase_gateway_storagebucket',$data), 
		    'gateway_messagingsenderid' => MoUtility::sanitize_check('mo_firebase_gateway_messagingsenderid',$data), 
		    'gateway_appid' => MoUtility::sanitize_check('mo_firebase_gateway_appid',$data) 
		);

		update_mofr_option("firebase_gateway_details",serialize($firebase_gateway_details));
		
	}
    
    function _mofr_check_l()
    {
        $this->is_valid_request();
       MoUtility::mofr_handle_mo_check_ln(true,
            get_mofr_option('admin_customer_key'),
            get_mofr_option('admin_api_key')
        );
    }
    
}