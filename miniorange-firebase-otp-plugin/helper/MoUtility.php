<?php

namespace MoOTP\Helper;

// use OTP\Objects\NotificationSettings;
use MoOTP\Objects\TabDetails;
use MoOTP\Objects\Tabs;
use MoOTP\Helper\MocURLOTP;
use MoOTP\Objects\FormHandler;
use \ReflectionClass;
use ReflectionException;
use \stdClass;

if (!defined('ABSPATH')) exit;


class MoUtility
{
    public static function is_blank($value)
    {
        return !isset($value) || empty($value);
    }
    
    public static function create_json($message, $type)
    {
        return array('message' => $message, 'result' => $type);
    }
    
    public static function micr()
    {
        $email = get_mofr_option('admin_email');
        $customerKey = get_mofr_option('admin_customer_key');
        if (!$email || !$customerKey || !is_numeric(trim($customerKey)))
            return 0;
        else
            return 1;
    }
    
    public static function micv()
    {
        $email = get_mofr_option('admin_email');
        $customerKey = get_mofr_option('admin_customer_key');
        $check_ln = get_mofr_option('check_ln');
        if (!$email || !$customerKey || !is_numeric(trim($customerKey)))
            return 0;
        else
            return  $check_ln ? $check_ln : 0;
    }

    public static function sanitize_post_data($rawArray){
        
        $sanitizedArray = array();
        foreach($rawArray as $key => $value) {
            $sanitizedArray[$key] = sanitize_text_field( $value );
        }
        return $sanitizedArray;

    }

    
    public static function mofr_handle_mo_check_ln($showMessage, $customerKey, $apiKey)
    {
        $msg = MoMessages::FREE_PLAN_MSG;
        $plan = array();
        $content = (array)json_decode(MocURLOTP::mofr_check_customer_ln($customerKey, $apiKey,'wp_email_verification_intranet_firebase', true));
       
        if (strcasecmp($content['status'], 'SUCCESS') == 0) {
            
             $emailRemaining = isset($content['emailRemaining']) ? $content['emailRemaining'] : 0;
             $smsRemaining = isset($content['smsRemaining']) ? $content['smsRemaining'] : 0;

            if (MoUtility::sanitize_check("licensePlan",$content)) {
                if(strcmp(MOFLR_LICENSE_TYPE, "MoFirebaseFreePlan")===0){
                    $msg = MoMessages::UPGRADE_MSG;
                    $plan = array('plan' => $content['licensePlan'],
                                    'sms'=> $smsRemaining,
                                    'email' => $emailRemaining );

                }
                else{
                    $msg = MoMessages::UPGRADE_MSG;
                    $plan = array('plan' => $content['licensePlan']);
                }
                update_mofr_option('check_ln', $content['licensePlan']);
            }
    
        } else {
            $content = json_decode(MocURLOTP::mofr_check_customer_ln($customerKey, $apiKey,'wp_email_verification_intranet'), true);
            if (MoUtility::sanitize_check("licensePlan",$content)) {
                $msg = MoMessages::INSTALL_PREMIUM_PLUGIN;
            }
         }
        if ($showMessage) {
            do_action('mo_registration_show_message', MoMessages::showMessage($msg, $plan), 'SUCCESS');
        }
    }

    public static function _get_invalid_otp_method()
    {
        return get_mofr_option("invalid_message","mo_otp_") ? mofr_(get_mofr_option("invalid_message","mo_otp_"))
            : MoMessages::showMessage(MoMessages::INVALID_OTP);
    }

    
    public static function _is_polylang_installed()
    {
        return function_exists('pll__') && function_exists('pll_register_string');
    }


    
    public static function sanitize_check($key, $buffer)
    {
        if(!is_array($buffer)) return $buffer;
        $value = !array_key_exists($key,$buffer) || self::is_blank($buffer[$key]) ? false : $buffer[$key];
        return is_array($value) ? $value : sanitize_text_field($value);
    }

    public static function mclv()
    {
        /** @var FormHandlerFunctions */
        $pluginType = FormHandler::instance();
        return $pluginType->mclv();
    }
    
    
    public static function is_mg()
    {  
        return false;
    }
}
