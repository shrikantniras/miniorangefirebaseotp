<?php

namespace MoOTP\Objects;

// use OTP\Helper\FormList;
// use OTP\Helper\FormSessionVars;
use MoOTP\Helper\MoConstants;
use MoOTP\Helper\MoPHPSessions;
use MoOTP\Helper\MoFirebaseFreePlan;
use MoOTP\Helper\MoFirebasePremiumPlan;
// use OTP\Helper\MoMessages;
use MoOTP\Helper\MoUtility;
use MoOTP\Traits\Instance;
// use OTP\Helper\SessionUtils;

/**
 * Interface class that needs to be extended by each form class.
 * It defines some of the common actions and functions for each form
 * class.
 */
class FormHandler
{  
    use Instance;
    /**
     * The phone HTML tag
     * @var string
     */
    protected $_typePhoneTag;

    /**
     * The email HTML tag
     * @var string
     */
    protected $_typeEmailTag;

    /**
     * The both HTML tag
     * @var string
     */
    protected $_typeBothTag;

    /**
     * The form key
     * @var string
     */
    protected $_redirecturl;

    /**
     * The form key
     * @var string
     */
    protected $_formKey;

    /**
     * The name of the form
     * @var string
     */
    protected $_formName;

    /**
     * Email or sms verification ( type of otp enabled by admin )
     * @var string
     */
    protected $_otpType;

    /**
     * The form javascript selector used by the script
     * file to append country code dropdown
     * @var string|array
     */
    protected $_phoneFormId;

    /**
     * Is form enabled or not
     * @var string
     */
    protected $_isFormEnabled;

    /**
     * Restrict duplicate phone number entries
     * @var string
     */
    protected $_restrictDuplicates;

    /**
     * Option to by pass otp verification for logged in users
     * @var string
     */
    protected $_byPassLogin;

    /**
     * Is the form in question a login or social form
     * @var string
     */
    protected $_isLoginOrSocialForm;

    /**
     * Is the form an ajax form or not
     * @var string
     */
    protected $_isAjaxForm;

    /**
     * The key value of the phone field
     * @var string
     */
    protected $_phoneKey;

    /**
     * The key value of the email field
     * @var string
     */
    protected $_emailKey;

    /**
     * Text of the button
     * @var string
     */
    protected $_buttonText;

    /**
     * The form details - formid, phonekey / emailkey etc
     * @var array
     */
    protected $_formDetails;

    /**
     * Option set by the admin to disable auto activation of users after successful verification
     * @var string
     */
    protected $_disableAutoActivate;

    /**
     * The session variable associated with Form
     * @var string
     */
    protected $_formSessionVar;

    /**
     * The session variable associated with Wordpress Form
     * @var string
     */
    protected $_formSessionVar2;

    /**
     * The nonce key for all forms
     * @var string
     */
    protected $_nonce = 'form_nonce';

    /**
     * The session Id which stores the transaction ids
     * @var string
     */
    // protected $_txSessionId  = FormSessionVars::TX_SESSION_ID;

    /**
     * The form options for all forms
     * @var string
     */
    protected $_formOption   = "mo_customer_validation_settings";

    /**
     * The generateOTPAction Key
     * @var string
     */
    protected $_generateOTPAction;

    /**
     * The generateOTPAction Key
     * @var string
     */
    protected $_validateOTPAction;

    /**
     * Nonce key against with the nonce value is passed
     * @var string
     */
    protected $_nonceKey = 'security';

    /**
     * Value that indicates if the form in question is an AddOn Form
     * @var bool
     */
    protected $_isAddOnForm = FALSE;

    /**
     * The form documents array
     * @var array
     */
    protected $_formDocuments = [];

    private $gateway;

    /**
     * @var array
     * Plugin Type to Class Map
     */
    private $pluginTypeToClass = [
        "MoFirebaseFreePlan"          => "MoOTP\Helper\MoFirebaseFreePlan",
        "MoFirebasePremiumPlan"       => "MoOTP\Helper\MoFirebasePremiumPlan",
    ];

    const VALIDATED = "VALIDATED";
    const VERIFICATION_FAILED = "verification_failed";
    const VALIDATION_CHECKED = "validationChecked";

    public function __construct()
    {
        $pluginType    = $this->pluginTypeToClass[MOFLR_LICENSE_TYPE];
        $this->gateway = $pluginType::instance();
        
        // add_action('wp_enqueue_scripts',array($this, 'miniorangeauth_mofirebase_basic_script'));
       wp_register_script( 'mofirebaseauth',MOFLR_URL.'includes/js/mofirebaseauth.js',array('jquery'));
        wp_localize_script( 'mofirebaseauth', 'mofirebaseauth', array(
            'firebasegatewaydetails'  => unserialize(get_mofr_option("firebase_gateway_details")),
            'imgURL'        => MOFLR_LOADER_URL,
        ));
        wp_enqueue_script( 'mofirebaseauth' );  
        
    }


    public function miniorange_login_mofirebaselogin_script(){

    }



    /**
     * Check the POST output buffer and return the value if a value exists
     * otherwise return a null or '0'
     * <br/>Appends {@code mo_firebase_} to the key.
     *
     * @param $param - the key to check the post buffer for
     * @param $prefix - prefix to the key if any
     * @return bool|String|array
     */
    public function sanitize_form_post($param,$prefix=null)
    {
        $param = ($prefix===null ? "mo_firebase_" : "") . $param;
        return MoUtility::sanitize_check($param,$_POST);
    }


    /**
     * This function is called from every form handler class to start the OTP
     * Verification process. Keeps certain variables in session and start the
     * OTP Verification process. Calls the mo_generate_otp hook to start
     * the OTP Verification process.
     *
     * @param string    $user_login    username submitted by the user
     * @param string    $user_email    email submitted by the user
     * @param string    $errors        error variable ( currently not being used )
     * @param string    $phone_number  phone number submitted by the user
     * @param string    $otp_type      email or sms verification
     * @param string    $password      password submitted by the user
     * @param string    $extra_data    an array containing all the extra data submitted by the user
     * @param bool      $from_both     denotes if user has a choice between email and phone verification
     */
    public function send_challenge($phone_number,$recaptcha_token)
    {
        $this->gateway->mo_firebase_send_otp($phone_number,$recaptcha_token);
    }

    
    // /** @return bool */
    // public function isAddOnForm(){ return $this->_isAddOnForm; }
     public function mclv()
    {
        return $this->gateway->mclv();
    }
    /**Will be implemented for future plans
     * */
      public function get_application_name()
    {
        return $this->gateway->get_application_name();
    }

    /**
     * Checks if the request made is a valid ajax request or not.
     * Only checks the none value for now.
     */
    protected function validate_ajax_request()
    {
        if(!check_ajax_referer($this->_nonce,$this->_nonceKey)) {
            wp_send_json(
                MoUtility::createJson(
                    MoMessages::showMessage(BaseMessages::INVALID_OP),
                    MoConstants::ERROR_JSON_TYPE
                )
            );
            exit;
        }
    }

}