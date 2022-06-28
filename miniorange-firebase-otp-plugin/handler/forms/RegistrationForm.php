<?php

namespace MoOTP\Handler\Forms;

use MoOTP\Helper\MoPHPSessions;
use MoOTP\Traits\Instance;
use MoOTP\Objects\FormHandler;
use MoOTP\Objects\IFormHandler;
use MoOTP\Helper\MoUtility;

class RegistrationForm extends FormHandler implements IFormHandler
{
    use Instance;

    /**
     * Registration form css
     * @var string
     */
    protected $_registrationFormCss;
    protected $_generateOTPAction = 'miniorange_frb_registration_generate_otp';
    protected $_nonce             = 'frb_form_nonce';
    protected $_default_user_role;

    protected function __construct()
    {
        $this->_isFormEnabled = get_mofr_option('registration_form_enable');
       add_action(  'init', array($this,'handle_mofirebase_form') ,1 );
       parent::__construct();
    }

    function handle_mofirebase_form(){

        add_shortcode('mo_wp_registration_form',array($this,'show_mo_wp_registration_form') );
        
        add_action('wp_enqueue_scripts',array($this, 'miniorange_register_mofirebaselogin_script'));

        add_action("wp_ajax_mo_firebase_reg_send_form", [$this,'_send_otp']);
        add_action("wp_ajax_nopriv_mo_firebase_reg_send_form", [$this,'_send_otp']);

        add_action( 'wp_ajax_mo_firebase_verify_otp',[$this,'mofr_validate_otp_token']);
        add_action( 'wp_ajax_nopriv_mo_firebase_verify_otp', [$this,'mofr_validate_otp_token']);

        add_action( 'wp_ajax_mo_firebase_reg_submit',[$this,'validate_form_submission']);
        add_action( 'wp_ajax_nopriv_mo_firebase_reg_submit', [$this,'validate_form_submission']);
        
        $POST = MoUtility::sanitize_post_data($_POST);

        if (isset($POST['option']) && $POST['option']=="mo_firebase_registration_form_save_option" && isset($POST['nonce_mo_firebase_registration_form_save_option']) && wp_verify_nonce( sanitize_text_field($POST['nonce_mo_firebase_registration_form_save_option']), "mo_nonce_mo_firebase_form_save_option" ) ) {
          $this->handle_mofirebase_form_options();
        }
    }

    function _send_otp(){

        $nonce = sanitize_text_field($_POST['user_nonce_registration_sendotp']);
        if ( ! wp_verify_nonce( $nonce, "mofr_registration_form" ) ){
            wp_send_json( MoUtility::create_json("Invalid Operation. Please Try Again !","error") );
        }
        $token = sanitize_text_field($_POST["recaptcha_token"]);
        $phone = sanitize_text_field($_POST["user_phone"]);

        if (MoUtility::is_blank($phone)) {
            wp_send_json( MoUtility::create_json("Please enter a valid phone number!","error") );
        }

        $this->send_challenge($phone,$token);
        
    }

    public function mofr_validate_otp_token()
    {
        
        $nonce = sanitize_text_field($_POST['user_nonce_registration_verifyotp']);
        if ( ! wp_verify_nonce( $nonce, "mofr_registration_form" ) ){
            wp_send_json( MoUtility::create_json("Invalid Operation. Please Try Again !","error") );
        }

        $pass=false;
        $otpToken = sanitize_text_field($_POST['otp_token']);
        
        if(MoPHPSessions::get_session_var('mo_otptoken'))
        {
            if (MoPHPSessions::get_session_var('mo_registration_phone_verified')!=sanitize_text_field($_POST['user_phone'])) {
                wp_send_json( MoUtility::create_json("Phone on which OTP was sent and phone submitted does not match","error") );
            }

            $url = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=AIzaSyBgQ5fuluF9e8opR395ZTWAz5JNeFQUCdk';

            $fields      = array(
                'sessionInfo'=> MoPHPSessions::get_session_var('sessionInfo'),
                'code'=>$otpToken
            );

            $headers     = ["Content-Type" => "application/json"];
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

            $response    = json_decode($response,TRUE);
            $responseidToken = $response['idToken'];
            $pass        = isset($responseidToken) && !empty($responseidToken);
        }

        if($pass){

            MoPHPSessions::add_session_var('is_regform_validated',true);
            MoPHPSessions::add_session_var('phone_number_mo',$response['phoneNumber']);
            MoPHPSessions::add_session_var('checkoutValidated',true);

            wp_send_json( MoUtility::create_json("otp_validated_successfully","success") );

        }
        else{
            wp_send_json( MoUtility::create_json("Invalid one time passcode. Please enter a valid passcode.","error") );
        }
        return $content;

    }

    public function validate_form_submission(){

        $nonce = sanitize_text_field($_POST['user_nonce_registration_submit']);
        if ( ! wp_verify_nonce( $nonce, "mofr_registration_form" ) ){
            wp_send_json( MoUtility::create_json("Invalid Operation. Please Try Again !","error") );
        }

        if (!is_email(sanitize_email($_POST["user_email"]))) {
            wp_send_json( MoUtility::create_json("Enter a valid email address","error") );
        }

        if (get_user_by("email",sanitize_email($_POST["user_email"]))) {
            wp_send_json( MoUtility::create_json("Email Address already used !","error") );
        }

        if (get_user_by("login",sanitize_user($_POST["user_name"]))) {
            wp_send_json( MoUtility::create_json("Username already used !","error") );
        }
        if (!sanitize_text_field($_POST["user_phone"])) {
            wp_send_json( MoUtility::create_json("Enter a valid phone number !","error") );
        }
        if (!sanitize_text_field($_POST["user_pass"])) {
            wp_send_json( MoUtility::create_json("Enter a valid password !","error") );
        }
        if ($this->_isFormEnabled) {
            
            if(!MoPHPSessions::get_session_var('mo_otptoken')){
                wp_send_json( MoUtility::create_json("Please verify yourself !","error") );
            }
            if(!MoPHPSessions::get_session_var('is_regform_validated')){
                wp_send_json( MoUtility::create_json("Please verify yourself !","error") );
            }
            if (MoPHPSessions::get_session_var('mo_registration_phone_verified')!=sanitize_text_field($_POST['user_phone'])) {
                wp_send_json( MoUtility::create_json("Phone on which OTP was sent and phone submitted does not match","error") );
            }
        }

        $userdata = array('user_login' =>sanitize_user($_POST["user_name"]) , 'user_pass'=>sanitize_text_field($_POST["user_pass"]),'user_email'=>sanitize_email($_POST["user_email"]),'role'=>get_mofr_option('default_user_role'));

        $user_id = wp_insert_user( $userdata );

        update_user_meta($user_id, 'mo_phone', sanitize_text_field($_POST['user_phone']));

        MoPHPSessions::unset_session('mo_otptoken');
        wp_set_auth_cookie($user_id);
        
        wp_send_json( MoUtility::create_json("form_validated_successfully","success") );

    }

    public function miniorange_register_mofirebaselogin_script(){

        wp_register_script( 'mofrregistrationform',MOFLR_URL.'includes/js/mofrregistrationform.js',array('jquery'));
        wp_localize_script( 'mofrregistrationform', 'mofrregistrationform', array(
            'siteURL'     => wp_ajax_fr_url(),
            'nonce'         => wp_create_nonce("mofr_registration_form"),
            'generateURL'   => $this->_generateOTPAction,
            'redirecturl'   => $this->_redirecturl,            
        ));
        wp_enqueue_script( 'mofrregistrationform' );

        wp_register_script('firebase-app','https://www.gstatic.com/firebasejs/7.2.1/firebase-app.js',null, null, true );

        wp_register_script('firebase-auth','https://www.gstatic.com/firebasejs/7.2.1/firebase-auth.js',null, null, true );

        wp_enqueue_script('firebase-app');
        wp_enqueue_script('firebase-auth');
    }

    public function show_mo_wp_registration_form()
    {     
        if(is_user_logged_in()){
            return;
        }
        else{

            $customCSS = get_mofr_option('registration_form_css');
            include MOFLR_DIR . 'views/shortcodes/mo-user-registration-form-shortcode.php';
        }
    }

    function handle_mofirebase_form_options()
    {
        $this->_isFormEnabled        = $this->sanitize_form_post('registration_form_enable');
        $this->_registrationFormCss  = $this->sanitize_form_post('registration_form_css');
        $post_id                     = sanitize_text_field($_POST['page_id']);
        $this->_redirecturl          = isset( $post_id) ? $post_id :"";
        
        $this->_default_user_role    = $this->sanitize_form_post('default_user_role');
        
        update_mofr_option('registration_form_enable',$this->_isFormEnabled);
        update_mofr_option('registration_form_css',$this->_registrationFormCss);
        update_mofr_option('reg_form_redirecturl',$this->_redirecturl);
        update_mofr_option('default_user_role',$this->_default_user_role);

    }

    
}