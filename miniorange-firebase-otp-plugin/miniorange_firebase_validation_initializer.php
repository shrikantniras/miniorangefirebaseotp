<?php

/**
 * Plugin Name: Miniorange OTP Verification with Firebase
 * Plugin URI: http://miniorange.com/miniorange-firebase-otp-plugin
 * Description: Login and registration forms. OTP Verification using firebase gateway. 24/7 support.
 * Version: 1.0.0
 * Author: miniOrange
 * Author URI: http://miniorange.com
 * Text Domain: miniorange-otp-verification
 * Domain Path: /lang
 * WC requires at least: 2.0.0
 * WC tested up to: 5.6.0
 * License: MIT/Expat
 */

use MoOTP\MoOTPInitializer;

if(! defined( 'ABSPATH' )) exit;
define('MOFLR_PLUGIN_NAME', plugin_basename(__FILE__));
$dirName = substr(MOFLR_PLUGIN_NAME,0,strpos(MOFLR_PLUGIN_NAME,"/"));
define("MOFLR_NAME",$dirName);
include '_moautoload.php';
require 'MoOTPInitializer.php';
MoOTPInitializer::instance(); //initialize the main class
