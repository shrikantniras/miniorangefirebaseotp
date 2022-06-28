<?php

namespace MoOTP\Helper;

//use MoOTP\Objects\NotificationSettings;

if(! defined( 'ABSPATH' )) exit;

/**
 * This class denotes all the cURL related functions to make API calls
 * to the miniOrange server. You can read about cURL here : {@link https://curl.haxx.se/}
 * and read how it is implemented in PHP here: {@link http://php.net/manual/en/book.curl.php}
 *
 * cURL is required by the plugin for OTP Verification to work. Without
 * cURL the plugin is as good as useless.
 */
class MocURLOTP
{

    public static function mofr_create_customer($email, $company, $password, $phone = '', $first_name = '', $last_name = '')
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/rest/customer/add';
    
        $fields = array (
            'companyName' 	 => $company,
            'areaOfInterest' => MoConstants::AREA_OF_INTEREST,
            'firstname' 	 => $first_name,
            'lastname' 		 => $last_name,
            'email' 		 => $email,
            'phone' 		 => $phone,
            'password' 		 => $password
        );
        $json = json_encode($fields);
        $currentTimestampInMillis = self::mofr_get_timestamp();
        if(MoUtility::is_blank($currentTimestampInMillis))
        {
            $currentTimestampInMillis = round(microtime(true) * 1000);
            $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        }
         $authHeader = [
            "Content-Type"  => "application/json",
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $authHeader
        ];
        $response = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_get_customer_key($email, $password)
    {
        $url 		 = MoConstants::HOSTNAME. "/moas/rest/customer/key";
        
        $fields = array (
            'email' 	=> $email,
            'password'  => $password
        );
        $json = json_encode($fields);

        $currentTimestampInMillis = self::mofr_get_timestamp();
        if(MoUtility::is_blank($currentTimestampInMillis))
        {
            $currentTimestampInMillis = round(microtime(true) * 1000);
            $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        }
         $authHeader = [
            "Content-Type"  => "application/json",
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $authHeader
        ];
        $response = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_check_customer($email)
    {
        $url 		 = MoConstants::HOSTNAME . "/moas/rest/customer/check-if-exists";
        
        $fields = array(
            'email' 	=> $email,
        );
        $json     = json_encode($fields);
        $currentTimestampInMillis = self::mofr_get_timestamp();
        if(MoUtility::is_blank($currentTimestampInMillis))
        {
            $currentTimestampInMillis = round(microtime(true) * 1000);
            $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        }
         $authHeader = [
            "Content-Type"  => "application/json",
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $authHeader
        ];

        $response = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_send_otp_token($auth_type,$email='',$phone='')
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/api/auth/challenge';
        $customerKey = get_mofr_option('admin_customer_key');
        $apiKey 	 = get_mofr_option('admin_api_key');

        $fields  	 = array(
            'customerKey' 	  => $customerKey,
            'email' 	  	  => $email,
            'phone' 	  	  => $phone,
            'authType' 	  	  => $auth_type,
            'transactionName' => MoConstants::AREA_OF_INTEREST
        );
        $json 		 = json_encode($fields);
        $authHeader  = self::mofr_create_authheader($customerKey,$apiKey);
        $response 	 = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_validate_otp_token($transactionId,$otpToken)
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/api/auth/validate';
        $customerKey = get_mofr_option('admin_customer_key');
        $apiKey 	 = get_mofr_option('admin_api_key');
        $fields 	 = array(
            'txId'  => $transactionId,
            'token' => $otpToken,
        );
        $json 		 = json_encode($fields);
        $authHeader  = self::mofr_create_authheader($customerKey,$apiKey);
        $response    = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_submit_contact_us(  $q_email, $q_phone, $query  )
    {
        $current_user 	= wp_get_current_user();
        $url    	  	= MoConstants::HOSTNAME . "/moas/rest/customer/contact-us";
        $query  		= '['.MoConstants::AREA_OF_INTEREST.' '.'('.MoConstants::PLUGIN_TYPE.')'.']: ' . $query;
        $customerKey 	= get_mofr_option('admin_customer_key');
        $apiKey 	 	= get_mofr_option('admin_api_key');
        $fields = array(
            'firstName'	=> $current_user->user_firstname,
            'lastName'	=> $current_user->user_lastname,
            'company' 	=> sanitize_text_field($_SERVER['SERVER_NAME']),
            'email' 	=> $q_email,
            'ccEmail'   => MoConstants::FEEDBACK_EMAIL,
            'phone'		=> $q_phone,
            'query'		=> $query
        );
        $field_string   = json_encode( $fields );
        $authHeader     = self::mofr_create_authheader($customerKey,$apiKey);
        $response 	    = self::mofr_call_api($url, $field_string, $authHeader);
        return true;
    }

    public static function mofr_forgot_password($email)
    {
        $url 		 = MoConstants::HOSTNAME . '/moas/rest/customer/password-reset';
        $customerKey = get_mofr_option('admin_customer_key');
        $apiKey 	 = get_mofr_option('admin_api_key');

        $fields 	 = array(
            'email' => $email
        );

        $json 		 = json_encode($fields);
        $authHeader  = self::mofr_create_authheader($customerKey,$apiKey);
        $response    = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }


    public static function mofr_check_customer_ln($customerKey,$apiKey,$appName)
    {
        $url = MoConstants::HOSTNAME . '/moas/rest/customer/license';
        $fields = array(
            'customerId' => $customerKey,
            'applicationName' => $appName,
            'licenseType' => !MoUtility::micr() ? 'DEMO' : 'PREMIUM',
        );

        $json 		 = json_encode($fields);
        $authHeader  = self::mofr_create_authheader($customerKey,$apiKey);
        $response    = self::mofr_call_api($url, $json, $authHeader);
        return $response;
    }

    public static function mofr_create_authheader($customerKey, $apiKey)
    {
        $currentTimestampInMillis = self::mofr_get_timestamp();
        if(MoUtility::is_blank($currentTimestampInMillis))
        {
            $currentTimestampInMillis = round(microtime(true) * 1000);
            $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        }
        $stringToHash = $customerKey . $currentTimestampInMillis . $apiKey;
        $authHeader = hash("sha512", $stringToHash);

        $header = [
            "Content-Type"  => "application/json",
            "Customer-Key"  => $customerKey,
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $authHeader
        ];
        return $header;
    }

    public static function mofr_get_timestamp()
    {
        $url = MoConstants::HOSTNAME . '/moas/rest/mobile/get-timestamp';
        return self::mofr_call_api($url,null,null);
    }


    /**
     *  Uses WordPress HTTP API to make cURL calls to miniOrange server
     *  <br/>Arguments that you can pass
     * <ol>
     *  <li>'timeout'     => 5,</li>
     *  <li>'redirection' => 5,</li>
     *  <li>'httpversion' => '1.0',</li>
     *  <li>'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),</li>
     *  <li>'blocking'    => true,</li>
     *  <li>'headers'     => array(),</li>
     *  <li>'cookies'     => array(),</li>
     *  <li>'body'        => null,</li>
     *  <li>'compress'    => false,</li>
     *  <li>'decompress'  => true,</li>
     *  <li>'sslverify'   => true,</li>
     *  <li>'stream'      => false,</li>
     *  <li>'filename'    => null</li>
     * </ol>
     *
     * @param string $url URL to post to
     * @param string $json_string json encoded post data
     * @param array $headers headers to be passed in the call
     * @param string $method GET or POST or PUT HTTP Method
     * @return string
     */
    public static function mofr_call_api($url, $json_string, $headers = ["Content-Type" => "application/json"],$method='POST')
    {
        $args = [
            'method'        => $method,
            'body'          => $json_string,
            'timeout'       => '10000',
            'redirection'   => '10',
            'httpversion'   => '1.0',
            'blocking'      => true,
            'headers'       => $headers,
            'sslverify'     => MOFLR_SSL_VERIFY,
        ];
        $response = wp_remote_post( $url, $args );
        if ( is_wp_error( $response ) ) {
            wp_die("Something went wrong: <br/> {$response->get_error_message()}");
        }
        return wp_remote_retrieve_body($response);
    }

}