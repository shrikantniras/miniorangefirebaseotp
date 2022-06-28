<?php

namespace MoOTP\Objects;

use MoOTP\Helper\MoUtility;
use MoOTP\Traits\Instance;

final class TabDetails
{
    use Instance;

    
    public $_tabDetails;

    
    public $_parentSlug;

    
    private function __construct()
    {
        $registered = MoUtility::micr();
        $this->_parentSlug = 'registrationform';
        $request_uri = remove_query_arg('addon',sanitize_text_field($_SERVER['REQUEST_URI']));

        $this->_tabDetails = [
            
            Tabs::LOGINREGISTRATIONFORMS => new PluginPageDetails(
                'OTP Verification - Login/Registration Form',
                "registrationform",
                esc_attr(mofr_('Login/Registration Form')),
                esc_attr(mofr_('Login/Registration Form')),
                $request_uri,
                'LoginRegistrationForm.php',
                'tabID',
                "background:#D8D8D8"
            ),
            Tabs::GATEWAY_CONFIG => new PluginPageDetails(
                'OTP Verification - Gateway Config',
                'mofrgatewayconfig',
                esc_attr(mofr_('Gateway Configuration')),
                esc_attr(mofr_('Gateway Configuration')),
                $request_uri,
                'mofrgatewayconfig.php',
                'emailSmsTemplate',
                "background:#D8D8D8"
            ),

            Tabs::ACCOUNT => new PluginPageDetails(
                "OTP Verification - Accounts",
                "mofrotpaccount",
                !$registered ? 'Account Setup' : 'User Profile',
                !$registered ? "Account Setup" : "Profile",
                $request_uri,
                'mofraccount.php',
                'account',
                '',
                "background:#D8D8D8"
            ),
            
        ];
    }
}