<?php

use MoOTP\SplClassLoader;
use MoOTP\Helper\MoUtility;

if(! defined( 'ABSPATH' )) exit;

define('MOFLR_DIR', plugin_dir_path(__FILE__));
define('MOFLR_URL', plugin_dir_url(__FILE__));
define('MOFLR_VERSION', '1.0.0');
define('MOFLR_SSL_VERIFY',false);
define('MOFLR_SESSION_TYPE','SESSION');
define('MOFLR_FIREBASE_API_HOST','https://www.googleapis.com/');
define('MOFLR_HOST','https://login.xecurify.com');
define('MOFLR_LICENSE_TYPE','MoFirebaseFreePlan');

define('MOFLR_ICON', MOFLR_URL . '/includes/images/miniorange_icon.png');
define('MOFLR_LOADER_URL', MOFLR_URL . 'includes/images/loader.gif');
define('MOFLR_MAIL_LOGO', MOFLR_URL.'includes/images/mo_support_icon.png');
define('MOFLR_LOGO_URL', MOFLR_URL . 'includes/images/logo.png');

define('MOFLR_CSS_URL', MOFLR_URL . 'includes/css/mofr_customer_validation_style.min.css?version='.MOFLR_VERSION);
define('MOFLR_FORM_CSS',MOFLR_URL . 'includes/css/mo_forms_css.min.css?version='.MOFLR_VERSION);
define('MOFLR_INTTELINPUT_CSS', MOFLR_URL.'includes/css/intlTelInput.min.css?version='.MOFLR_VERSION);
define('MOFLR_BOOT_CSS', MOFLR_URL . 'includes/css/mo_fr_otp_boot.min.css?version='.MOFLR_VERSION);
define('MOFLR_JS_URL', MOFLR_URL . 'includes/js/settings.min.js?version='.MOFLR_VERSION);
define('MOFLR_INTTELINPUT_JS', MOFLR_URL.'includes/js/intlTelInput.min.js?version='.MOFLR_VERSION);
define('MOFLR_DROPDOWN_JS', MOFLR_URL.'includes/js/dropdown.min.js?version='.MOFLR_VERSION);

include "SplClassLoader.php";

$idpClassLoader = new SplClassLoader('MoOTP', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
$idpClassLoader->register();
mofirebase_initialize_forms();

function mofirebase_initialize_forms()
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(MOFLR_DIR . 'handler/forms',RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach($iterator as $it){
        $filename = $it->getFilename();
        $className = "MoOTP\\Handler\\Forms\\" . str_replace('.php','',$filename);
        
        $formHandler = $className::instance();
    }
}

function get_mofrom_option($string,$prefix=null)
{
    $string = ($prefix==null ? "mo_form_" : $prefix) . $string;
    return get_option($string,'');
}


function update_moform_option($optionName,$value,$prefix=null)
{
    $optionName = ($prefix===null ? "mo_form_" : $prefix) . $optionName;
    update_option($optionName,$value,'');
}

function mofr_($string)
{
    $textDomain = "miniorange-firebase-otp";
    $string = preg_replace('/\s+/S', " ", $string);
    return is_scalar($string)
            ? (MoUtility::_is_polylang_installed() && MOV_USE_POLYLANG ? pll__($string) : __($string, $textDomain))
            : $string;
}

function wp_ajax_fr_url(){ return admin_url('admin-ajax.php'); }

function get_mofr_option($string,$prefix=null)
{
    $string = ($prefix===null ? "mo_firebase_loginreg_" : $prefix) . $string;
    return apply_filters('get_mofr_option',get_site_option($string));
}


function update_mofr_option($string,$value,$prefix=null)
{
    $string = ($prefix===null ? "mo_firebase_loginreg_" : $prefix) . $string;
    update_site_option($string,apply_filters('update_mofr_option',$value,$string));
}

function delete_mofr_option($string,$prefix=null)
{
    $string = ($prefix===null ? "mo_firebase_loginreg_" : $prefix) . $string;
    delete_site_option($string);
}