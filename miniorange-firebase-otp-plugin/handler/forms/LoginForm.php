<?php

namespace MoOTP\Handler\Forms;

use MoOTP\Helper\MoPHPSessions;
use MoOTP\Traits\Instance;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\FormHandler;
use MoOTP\Objects\IFormHandler;
use \WP_Error;
use \WP_User;

class LoginForm extends FormHandler implements IFormHandler
{
    use Instance;

    /**
     * Login form css
     * @var string
     */
    protected $_loginFormCss;

    protected $_nonce             = 'frb_form_nonce';
    protected $_generateOTPAction = 'miniorange_frb_login_generate_otp';

    protected function __construct()
    {
        $this->_isFormEnabled = get_mofr_option('login_form_enable');
        add_action(  'init', array($this,'handle_mofirebase_form') ,1 );
        parent::__construct();
    }

    function handle_mofirebase_form(){

        add_shortcode('mo_wp_login_form',array($this,'show_mo_wp_login_form') );

        add_action('wp_enqueue_scripts',array($this, 'miniorange_login_mofirebaselogin_script'));

        add_action("wp_ajax_mofr_login_with_otp_sendotp", [$this,'_send_otp']);
        add_action("wp_ajax_nopriv_mofr_login_with_otp_sendotp", [$this,'_send_otp']);

        add_action("wp_ajax_mofr_login_with_email_pass", [$this,'mofr_login_with_email_pass']);
        add_action("wp_ajax_nopriv_mofr_login_with_email_pass", [$this,'mofr_login_with_email_pass']);

        add_action( 'wp_ajax_mofr_login_with_otp_verifyotp',[$this,'mofr_validate_otp_token']);
        add_action( 'wp_ajax_nopriv_mofr_login_with_otp_verifyotp', [$this,'mofr_validate_otp_token']);

        $POST = MoUtility::sanitize_post_data($_POST);
        
        if (isset($POST['option']) && $POST['option']=="mo_firebase_login_form_save_option" && isset($POST['nonce_mo_firebase_login_form_save_option']) && wp_verify_nonce($POST['nonce_mo_firebase_login_form_save_option'], "mo_nonce_mo_firebase_form_save_option" ) ) {
            $this->handle_mofirebase_form_options();
        }

    }

    function mofr_login_with_email_pass(){
        
        $nonce = sanitize_text_field($_POST['user_nonce_login_with_emailpass']);

        if ( ! wp_verify_nonce( $nonce, $this->_nonce ) ){
            wp_send_json( MoUtility::create_json("Invalid Operation. Please Try Again !","error") );
        }

        $user_data = sanitize_text_field($_POST['user_data']);
        $user_pass = sanitize_text_field($_POST['user_pass']);;

        $user = wp_authenticate_username_password('', $user_data, $user_pass);
        
        if (is_wp_error($user)) {
            wp_send_json(MoUtility::create_json("Invalid Username or Password !","error"));
        }else{

            wp_set_auth_cookie($user->data->ID);
            wp_send_json(MoUtility::create_json("validation successful","success"));
            exit();
        }
    }

    function _send_otp(){

        $nonce = sanitize_text_field($_POST['user_nonce_login_with_otp']);

        if ( ! wp_verify_nonce( $nonce, $this->_nonce ) ){
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
        $nonce = sanitize_text_field($_POST['user_nonce_verify_login_with_otp']);

        if ( ! wp_verify_nonce( $nonce, $this->_nonce ) ){
            wp_send_json( MoUtility::create_json("Invalid Operation. Please Try Again !","error") );
        }

        $pass=false;
        $otpToken = sanitize_text_field($_POST['otp_token']);
       
        if(MoPHPSessions::get_session_var('mo_otptoken'))
        {
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
                $send_response = esc_attr($response->get_error_message());
                wp_die("Something went wrong: <br/> {$send_response}");
            }
            $response = wp_remote_retrieve_body($response);

            $response    = json_decode($response,TRUE);
            $responseidToken = $response['idToken'];
            $pass        = isset($responseidToken) && !empty($responseidToken);
        }
        
        if($pass){
            
            MoPHPSessions::unset_session('mo_otptoken');
            $user = $this->getUserFromPhoneNumber(sanitize_text_field($_POST['user_phone']));
            wp_set_auth_cookie($user->ID);
            wp_send_json( MoUtility::create_json("otp_validated_successfully","success") );
        }
        else{

            wp_send_json( MoUtility::create_json("Invalid one time passcode. Please enter a valid passcode.","error") );
        }
        return $content;

    }

     /**
     * This functions fetches the user associated with a phone number
     *
     * @param string $username  the user's username
     * @return bool|WP_User
     */
    function getUserFromPhoneNumber($username)
    {
        global $wpdb;
        
        $sql = $wpdb->prepare( "SELECT `user_id` FROM `{$wpdb->prefix}usermeta` WHERE `meta_key` = %s AND `meta_value` = %s",array('mo_phone',$username) );
        
        $results = $wpdb->get_row($sql);
        return !MoUtility::is_blank($results) ? get_userdata($results->user_id) : false;
    }

    public function miniorange_login_mofirebaselogin_script(){

        wp_register_script( 'mofrloginform',MOFLR_URL.'includes/js/mofrloginform.js',array('jquery'));
        wp_localize_script( 'mofrloginform', 'mofrloginform', array(
            'siteURL'       => wp_ajax_fr_url(),
            'nonce'         => wp_create_nonce($this->_nonce),
            'generateURL'   => $this->_generateOTPAction,
            'redirecturl'   => get_page_link(get_mofr_option('login_form_redirecturl')),
        ));
        wp_enqueue_script( 'mofrloginform' );
    }

    public function show_mo_wp_login_form()
    {     
        if(is_user_logged_in()){
            return;
        }
        else{
            $customCSS = get_mofr_option('login_form_css');
            include MOFLR_DIR . 'views/shortcodes/mo-user-login-form-shortcode.php';
        }

    }

    function handle_mofirebase_form_options()
    {
        $this->_isFormEnabled = $this->sanitize_form_post('login_form_enable');
        $this->_loginFormCss  = $this->sanitize_form_post('login_form_css');
        $post_id              = sanitize_text_field($_POST['page_id']);
        $this->_redirecturl   = isset( $post_id) ? $post_id: "";

        update_mofr_option('login_form_enable',$this->_isFormEnabled);
        update_mofr_option('login_form_css',$this->_loginFormCss);
        update_mofr_option('login_form_redirecturl',$this->_redirecturl);
        
    }
}