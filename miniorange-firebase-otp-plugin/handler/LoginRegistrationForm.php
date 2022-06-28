<?php

namespace MoOTP\Handler;

use MoOTP\Helper\MoPHPSessions;
use MoOTP\Traits\Instance;
use MoOTP\Helper\MoUtility;
use MoOTP\Objects\FormHandler;
use MoOTP\Objects\IFormHandler;

class LoginRegistrationForm extends FormHandler implements IFormHandler
{
    use Instance;

    /**
     * Login form css
     * @var string
     */
    protected $_loginregFormCss;

    protected function __construct()
    {
       add_action(  'init', array($this,'handle_mofirebase_form') ,1 );
    }

    function handle_mofirebase_form(){

        add_shortcode('mo_wp_loginreg_form',array($this,'show_mo_wp_loginreg_form') );
        
        $POST = MoUtility::sanitize_post_data($_POST);

        if (isset($POST['option']) && $POST['option']=="mo_firebase_loginreg_form_save_option" && isset($POST['nonce_mo_firebase_loginreg_form_save_option']) && wp_verify_nonce( $POST['nonce_mo_firebase_loginreg_form_save_option'], "mo_nonce_mo_firebase_form_save_option" ) ) {
            $this->handle_mofirebase_form_options();
        }

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
        $results = $wpdb->get_row("SELECT `user_id` FROM `{$wpdb->prefix}usermeta`"
                                    ."WHERE `meta_key` = 'mo_phone' AND `meta_value` =  '$username'");
        return !MoUtility::is_blank($results) ? get_userdata($results->user_id) : false;
    }
    
    public function show_mo_wp_loginreg_form()
    {     
        if(is_user_logged_in()){
            return;
        }
        else{
            $customloginregCSS = get_mofr_option('loginreg_form_css') ? get_mofr_option('loginreg_form_css') : file_get_contents(MOFLR_DIR . 'includes/css/mofr_loginreg_form.css');
            $isRegistrationOtpEnabled      = get_mofr_option('registration_form_enable');
            $isLoginOtpEnabled             = get_mofr_option('login_form_enable');
            include MOFLR_DIR . 'views/shortcodes/mo-user-loginreg-form-shortcode.php';
        }
    }

    function handle_mofirebase_form_options()
    {
        $this->_loginregFormCss  = $this->sanitize_form_post('loginreg_form_css')?$this->sanitize_form_post('loginreg_form_css') : file_get_contents(MOFLR_DIR . 'includes/css/mofr_loginreg_form.css');
        update_mofr_option('loginreg_form_css',$this->_loginregFormCss);
        
    }
}