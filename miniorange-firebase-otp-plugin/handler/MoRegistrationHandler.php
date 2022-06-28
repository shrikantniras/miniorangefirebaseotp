<?php

namespace MoOTP\Handler;
if(! defined( 'ABSPATH' )) exit;
//use MoOTP\Helper\GatewayFunctions;
use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MocURLOTP;
use MoOTP\Helper\MoMessages;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\BaseActionHandler;
use MoOTP\Traits\Instance;
use MoOTP\Helper\AEncryption;

/**
 * This class handles all the Registration related functionality.
 *
 * @todo need to modularize the code further
 */
class MoRegistrationHandler extends BaseActionHandler
{
    use Instance;

	function __construct()
	{    
	    parent::__construct();
	    $this->_nonce = 'mo_reg_actions';
		add_action( 'admin_init',  array( $this, 'handle_customer_registration' ) );
	}
        /** @var string ApplicationName used in API calls */
    protected $applicationName ="wp_email_verification_intranet_firebase";

	/**
	 * This function hooks into the admin_init hook and routes the data
	 * to the correct functions for processsing. Makes sure the user
	 * has enough capabilities to be able to register.
	 */
	function handle_customer_registration()
	{

		if ( !current_user_can( 'manage_options' )) return;
		$POST = MoUtility::sanitize_post_data($_POST);
		
		if(!isset($POST['option'])) return;
		switch($POST['option'])
		{
		    case "mofr_registration_register_customer":
				$this->mofr_register_customer($_POST);
				break;
			case "mofr_registration_connect_verify_customer":
				$this->mofr_verify_customer($_POST);
				break;
			case "mofr_registration_validate_otp":
				$this->mofr_validate_otp($_POST);
				break;
			case "mofr_registration_resend_otp":
				$this->_send_otp_token(get_mofr_option('admin_email'),
                    "",'EMAIL');  	                                                    
				break;
			case "mofr_registration_phone_verification":
				$this->mofr_send_phone_otp_token($_POST);
				break;
			case "mofr_registration_go_back":
				$this->mofr_revert_back_registration();
				break;
			case "mo_registration_mofr_forgot_password":
				$this->mofr_reset_password();
				break;
            case "mofr_go_to_login_page":
            case "mofr_remove_account":
				$this->mofr_removeAccount();
				break;
			case "mofr_registration_verify_license":
				$this->mofr_vlk($_POST);
				break;
		}
	}


	/**
	 * Process the registration form and register the user. Checks if the password
	 * and confirm password match the correct format and email and password fields
	 * are not empty or null. First checks if a customer exists in the system Based
	 * on that decides if a new user needs to be created or fetch user info instead.
	 *
	 * @param $post - the posted data
	 */
	function mofr_register_customer($post)
	{
	    $this->is_valid_request();
		$email 			 = sanitize_email( $_POST['email'] );
		$company 		 = sanitize_text_field($_POST['company']);
		$first_name 	 = sanitize_text_field($_POST['fname']);
		$last_name 		 = sanitize_text_field($_POST['lname']);
		$password 		 = sanitize_text_field($_POST['password'] );
		$confirmPassword = sanitize_text_field($_POST['confirmPassword'] );

		if( strlen( $password ) < 6 || strlen( $confirmPassword ) < 6)
		{
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::PASS_LENGTH),'ERROR');
			return;
		}

		if( $password != $confirmPassword )
		{
			delete_mofr_option('verify_customer');
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::PASS_MISMATCH),'ERROR');
			return;
		}

		if( MoUtility::is_blank( $email ) || MoUtility::is_blank( $password )
				|| MoUtility::is_blank( $confirmPassword ) )
		{
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::REQUIRED_FIELDS),'ERROR');
			return;
		}

		update_mofr_option( 'company_name'		, $company);
		update_mofr_option( 'first_name'		, $first_name);
		update_mofr_option( 'last_name'		    , $last_name);
		update_mofr_option( 'admin_email'		, $email );
		update_mofr_option( 'admin_password'	, $password );

		$content  = json_decode(MocURLOTP::mofr_check_customer($email), true);
		switch ($content['status'])
		{
			case 'CUSTOMER_NOT_FOUND':
				// $this->_send_otp_token($email,"",'EMAIL');
				$this->handle_without_ckey_cid_regisgtration($email,$company,$password,"",$first_name, $last_name);
				
				break;
			default:
				$this->mofr_get_current_customer($email,$password);
				break;
		}

	}


	/**
	 * This function is called to send the OTP token to the user.
	 *
	 * @param $email - the email provided by the user
	 * @param $phone - the phone number provided by the user
	 * @param $auth_type - email or sms verification
	 */
	function _send_otp_token($email,$phone,$auth_type)
	{
        $this->is_valid_request();
		$content  = json_decode(MocURLOTP::mofr_send_otp_token($auth_type,$email,$phone), true);
		if(strcasecmp($content['status'], 'SUCCESS') == 0)
		{
			update_mofr_option('transactionId',$content['txId']);
			update_mofr_option('registration_status','MO_OTP_DELIVERED_SUCCESS');
			if($auth_type=='EMAIL')
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::OTP_SENT,array('method'=>$email)),'SUCCESS');
			else
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::OTP_SENT,array('method'=>$phone)),'SUCCESS');
		}
		else
		{
			update_mofr_option('registration_status','MO_OTP_DELIVERED_FAILURE');
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ERR_OTP),'ERROR');
		}
	}


    /**
     * Function to fetch customer details from the server and save in
     * the local WordPress Database.
     *
     * @param string    $email      email of the user
     * @param string    $password   password of the user
     */
	private function mofr_get_current_customer($email,$password)
	{   
		$content     = MocURLOTP::mofr_get_customer_key($email,$password);
		$customerKey = json_decode($content, true);
		if(json_last_error() == JSON_ERROR_NONE)
		{
			update_mofr_option('admin_email', $email );
			if(is_null($customerKey['phone']))
			    update_mofr_option( 'admin_phone', "" );
			else
				update_mofr_option( 'admin_phone', $customerKey['phone'] );
			$this->save_success_customer_config(
			    $customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['appSecret']
            );
			MoUtility::mofr_handle_mo_check_ln(false,$customerKey['id'], $customerKey['apiKey']);
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REG_SUCCESS),'SUCCESS');
		}
		else
		{
            update_mofr_option('admin_email', $email );
			update_mofr_option('verify_customer', 'true');
			delete_mofr_option('new_registration');
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ACCOUNT_EXISTS),'ERROR');
		}
	}

	//Save all required fields on customer registration/retrieval complete.
	function save_success_customer_config($id, $apiKey, $token, $appSecret)
	{
		update_mofr_option( 'admin_customer_key'  , $id       );
		update_mofr_option( 'admin_api_key'       , $apiKey   );
		update_mofr_option( 'customer_token'      , $token    );
		update_mofr_option( 'plugin_activation_date'      , date("Y-m-d h:i:sa"));
		delete_mofr_option( 'verify_customer'                 );
		delete_mofr_option( 'new_registration'                );
		delete_mofr_option( 'admin_password'                  );
	}

    /**
     * Validate OTP posted by the user
     * @param array $post   the $_POST array
     */
	function mofr_validate_otp($post)
	{
        $this->is_valid_request();
		$otp_token 		 = sanitize_text_field( $post['otp_token'] );
		$email 			 = get_mofr_option( 'admin_email');
		$company 		 = get_mofr_option( 'company_name');
		$password 		 = get_mofr_option( 'admin_password');

		if( MoUtility::is_blank( $otp_token ) )
		{
			update_mofr_option('registration_status','MO_OTP_VALIDATION_FAILURE');
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REQUIRED_OTP),'ERROR');
			return;
		}

		/** @todo Need to change how transactionId is being fetched from database */
		$content = json_decode(MocURLOTP::mofr_validate_otp_token(get_mofr_option('transactionId'), $otp_token ),true);
		if(strcasecmp($content['status'], 'SUCCESS') == 0)
		{
			$customerKey = json_decode(
			    MocURLOTP::mofr_create_customer($email, $company, $password, $phone = '', $first_name = '', $last_name = ''),
                true
            );
            if(strcasecmp($customerKey['status'], 'INVALID_EMAIL_QUICK_EMAIL')==0){
            	do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ENTERPRIZE_EMAIL),'ERROR');
            }
			if(strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0)
			{
				$this->mofr_get_current_customer($email,$password);
			}
			elseif(strcasecmp($customerKey['status'], 'EMAIL_BLOCKED') == 0 && $customerKey['message']=='error.enterprise.email'){
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ENTERPRIZE_EMAIL),'ERROR');	
			}
			elseif(strcasecmp($customerKey['status'], 'FAILED') == 0){
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REGISTRATION_ERROR),'ERROR');	
			}
			elseif(strcasecmp($customerKey['status'], 'SUCCESS') == 0)
			{
				$this->save_success_customer_config($customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['appSecret']);
				update_mofr_option('registration_status','MO_CUSTOMER_VALIDATION_REGISTRATION_COMPLETE');
				
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REG_COMPLETE),'SUCCESS');
				header('Location: admin.php?page=mofrotpaccount');
			}
		}
		else
		{
			update_mofr_option('registration_status','MO_OTP_VALIDATION_FAILURE');
			do_action('mo_registration_show_message', MoUtility::_get_invalid_otp_method() ,'ERROR');
		}
	}

	function handle_without_ckey_cid_regisgtration($email,$company,$password,$phone,$first_name, $last_name){

		$customerKey = json_decode(
			    MocURLOTP::mofr_create_customer($email, $company, $password, $phone, $first_name, $last_name),
                true
            );
            if(strcasecmp($customerKey['status'], 'INVALID_EMAIL_QUICK_EMAIL')==0){
            	do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ENTERPRIZE_EMAIL),'ERROR');
            }
			if(strcasecmp($customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0)
			{
				$this->mofr_get_current_customer($email,$password);
			}
			elseif(strcasecmp($customerKey['status'], 'EMAIL_BLOCKED') == 0 && $customerKey['message']=='error.enterprise.email'){
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::ENTERPRIZE_EMAIL),'ERROR');	
			}
			elseif(strcasecmp($customerKey['status'], 'FAILED') == 0){
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REGISTRATION_ERROR),'ERROR');	
			}
			elseif(strcasecmp($customerKey['status'], 'SUCCESS') == 0)
			{
				$this->save_success_customer_config($customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['appSecret']);
				update_mofr_option('registration_status','MO_CUSTOMER_VALIDATION_REGISTRATION_COMPLETE');
				
				do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REG_COMPLETE),'SUCCESS');
				header('Location: admin.php?page=mofrotpaccount');
			}
	}


	//Function to send otp token to phone
	function mofr_send_phone_otp_token($post)
	{
        $this->is_valid_request();
		$phone = sanitize_text_field($_POST['phone_number']);
		$phone = str_replace(' ', '', $phone);
		$pattern = "/[\+][0-9]{1,3}[0-9]{10}/";
		if(preg_match($pattern, $phone, $matches, PREG_OFFSET_CAPTURE))
		{
			update_mofr_option('admin_phone',$phone);
			$this->_send_otp_token("",$phone,'SMS');
		}
		else
		{
			update_mofr_option('registration_status','MO_OTP_DELIVERED_FAILURE');
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::INVALID_SMS_OTP),'ERROR');
		}
	}


    /**
     * Function to verify customer details. Checks if email and
     * password has been submitted and then fetches customer info.
     *
     * @param $post
     */
	function mofr_verify_customer($post)
	{
        $this->is_valid_request();
		$email 	  = sanitize_email( $post['email'] );
		$password = stripslashes($post['password']);

		if( MoUtility::is_blank( $email ) || MoUtility::is_blank( $password ) )
		{
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REQUIRED_FIELDS),'ERROR');
			return;
		}
		$this->mofr_get_current_customer($email,$password);
	}


    /**
     * Reset Administrator's miniOrange password.
     * This calls the server to send a forgot password email.
     */
	function mofr_reset_password()
	{
        $this->is_valid_request();
		$email 	  = get_mofr_option('admin_email');
		if(!$email)
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::MOFR_FORGOT_PASSWORD_MESSAGE),"SUCCESS");
		else{
		$mofr_forgot_password_response = json_decode(MocURLOTP::mofr_forgot_password($email));
		if($mofr_forgot_password_response->status == 'SUCCESS')
			do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::RESET_PASS),'SUCCESS');
		else
			do_action('mo_registration_show_message',MoMessages::showMessage(MoMessages::UNKNOWN_ERROR),'ERROR');
		}
		
	}


    /**
     * In case of an error delete all option values to revert back
     * all the data as was at the beginning of the registration process.
     */
	function mofr_revert_back_registration()
	{
        $this->is_valid_request();
		update_mofr_option('registration_status','');
		delete_mofr_option('new_registration');
		delete_mofr_option('verify_customer' ) ;
		delete_mofr_option('admin_email');
		delete_mofr_option('sms_otp_count');
		delete_mofr_option('email_otp_count');
		delete_mofr_option('plugin_activation_date');
	}


    /**
     * This function runs when the user wants to remove his account. Used to delete
     * a few values so that the user has to login again when he wishes to.
     */
    function mofr_removeAccount()
    {
        $this->is_valid_request();
         $this->mius();

        wp_clear_scheduled_hook('hourlySync');
        delete_mofr_option('transactionId');
        delete_mofr_option('admin_password');
        delete_mofr_option('registration_status');
        delete_mofr_option('admin_phone');
        delete_mofr_option('new_registration');
        delete_mofr_option('admin_customer_key');
        delete_mofr_option('admin_api_key');
        delete_mofr_option('customer_token');
        delete_mofr_option('verify_customer');
        delete_mofr_option('message');
        delete_mofr_option('check_ln');
        delete_mofr_option('site_email_ckl');
        delete_mofr_option('email_verification_lk');
        update_mofr_option("verify_customer",true);
        delete_mofr_option('plugin_activation_date');
    }

    /**
     * Function checks if there is an existing license on the site.
     * If so then update the status of the key on the server so
     * that it can be reused again.
     */
    function flush_cache()
    {
        /** @var GatewayFunctions $gateway */
        $gateway = GatewayFunctions::instance();
        $gateway->flush_cache();
    }

    /**
     * This function is used to verify the license key entered
     * by the user while activating the plugin.
     *
     * @param $post - all the data sent in form post to validate the license key
     */
     public function mofr_vlk($post)
    {
        if( MoUtility::is_blank( $post['email_lk'] ) )
        {
            do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::REQUIRED_FIELDS),MoConstants::ERROR);
            return;
        }
        $code = trim(sanitize_text_field($_POST['email_lk']));
        $result = json_decode($this->ccl(), true);
        switch ($result['status'])
        {
            case 'SUCCESS':
                $this->mofr_vlk_success($code);	break;
            default:
                $this->mofr_vlk_fail();			break;
        }
    }
     private function mofr_vlk_success($code)
    {
        $content = json_decode($this->vml($code),true);
        if(strcasecmp($content['status'], 'SUCCESS') == 0)
        {
            $key = get_mofr_option('customer_token');
            update_mofr_option('email_verification_lk'	, $code );
            update_mofr_option('site_email_ckl'		 	, "true" );
            do_action('mo_registration_show_message'	, MoMessages::showMessage(MoMessages::VERIFIED_LK),'SUCCESS');
        }
        elseif(strcasecmp($content['status'], 'FAILED') == 0)
        {
            if(strcasecmp($content['message'], 'Code has Expired') == 0)
                do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::LK_IN_USE), 'ERROR');
            else
                do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::INVALID_LK), 'ERROR');
        }
        else
            do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::UNKNOWN_ERROR),'ERROR');
    }
    private function mofr_vlk_fail()
    {
        $key = get_mofr_option('customer_token');
        update_mofr_option('site_email_ckl', "false");
        do_action('mo_registration_show_message', MoMessages::showMessage(MoMessages::NEED_UPGRADE_MSG),'ERROR');
    }
     private function ccl()
    {
        $url = MoConstants::HOSTNAME . '/moas/rest/customer/license';
        $customerKey = get_mofr_option ( 'admin_customer_key' );
        $apiKey 	 = get_mofr_option ( 'admin_api_key' );

        //*check for otp over sms/email
        $fields = array(
            'customerId' => $customerKey,
            'applicationName' => $this->applicationName,
        );

        $json 		 = json_encode($fields);
        $authHeader  = MocURLOTP::mofr_create_authheader($customerKey,$apiKey);
        $response    = MocURLOTP::mofr_call_api($url, $json, $authHeader);
        return $response;
    }
     private function mius()
    {
        $url = MoConstants::HOSTNAME . '/moas/api/backupcode/updatestatus';
        $customerKey = get_mofr_option ( 'admin_customer_key' );
        $apiKey 	 = get_mofr_option ( 'admin_api_key' );
        $key = get_mofr_option('customer_token');
        $code = get_mofr_option('email_verification_lk');
        $fields = array (
            'code' => $code,
            'customerKey' => $customerKey
        );
        $json 		 = json_encode($fields);
        $authHeader  = MocURLOTP::mofr_create_authheader($customerKey,$apiKey);
        $response    = MocURLOTP::mofr_call_api($url, $json, $authHeader);
        return $response;
    }
        private function vml($code)
    {
        $url = MoConstants::HOSTNAME . '/moas/api/backupcode/verify';
        $customerKey = get_mofr_option ( 'admin_customer_key' );
        $apiKey 	 = get_mofr_option ( 'admin_api_key' );

        $fields = array (
            'code' => $code ,
            'customerKey' => $customerKey,
            'additionalFields' => array(
                'field1' => site_url()
            )
        );

        $json 		 = json_encode($fields);
        $authHeader  = MocURLOTP::mofr_create_authheader($customerKey,$apiKey);
        $response    = MocURLOTP::mofr_call_api($url, $json, $authHeader);
        return $response;

    }



}