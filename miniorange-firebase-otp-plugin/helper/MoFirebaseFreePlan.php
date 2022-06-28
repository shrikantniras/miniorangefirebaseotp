<?php

namespace MoOTP\Helper;

if(! defined( 'ABSPATH' )) exit;

use MoOTP\Helper\MoPHPSessions;
use MoOTP\Traits\Instance;


final class MoFirebaseFreePlan
{
    use Instance;
    
    protected $applicationName;
    
    private function __construct()
    {
        
    }

    function mo_firebase_send_otp($phone_number,$recaptchaToken,$headers= ["Content-Type" => "application/json"]){

        $mofr_firebase_auth_key = get_mofr_option("mofr_firebase_auth_key");
        if (get_mofr_option("mofr_firebase_auth_key")==0) {
            wp_send_json( MoUtility::create_json("You can exhausted free transcations limit for your account. Please contact otpsupport@xecurify.com","error") );
        }

        update_mofr_option("mofr_firebase_auth_key", $mofr_firebase_auth_key-1);

        $url         = MOFLR_FIREBASE_API_HOST.'identitytoolkit/v3/relyingparty/sendVerificationCode?key='.get_mofr_option('gateway_apiKey');

        $fields      = array(
            'phoneNumber'    =>$phone_number,
            'recaptchaToken' =>$recaptchaToken,
        );
        $json        = json_encode($fields);
        
        $args = [
            'method'        => "POST",
            'body'          => $json,
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
        $response = wp_remote_retrieve_body($response);
        
        $response = json_decode($response,TRUE);
        $responsesessionInfo = $response['sessionInfo'];
        $pass     = isset($responsesessionInfo) && !empty($responsesessionInfo);

        if($pass){
            MoPHPSessions::add_session_var('mo_otptoken',true);
            MoPHPSessions::add_session_var('mo_registration_phone_verified',$phone_number);
            MoPHPSessions::add_session_var('sessionInfo',$response['sessionInfo']);
            wp_send_json( MoUtility::create_json("A OTP (One Time Passcode) has been sent to ".$phone_number.". Please enter the OTP in the field below to verify your phone.","success") );
        }
        else{
            MoPHPSessions::add_session_var('mo_otptoken',false);
            wp_send_json( MoUtility::create_json("There was an error in sending the OTP to the given Phone. Number. Please Try Again or contact site Admin.","error") );
        }

    }

    public function mclv()
    {
        return TRUE;
    }

     public function get_application_name()
    {
        return $this->applicationName;
    }



}