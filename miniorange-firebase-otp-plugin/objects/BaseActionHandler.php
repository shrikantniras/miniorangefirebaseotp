<?php

namespace MoOTP\Objects;

 use MoOTP\Helper\MoConstants;
 use MoOTP\Helper\MoMessages;
 use MoOTP\Helper\MoUtility;

class BaseActionHandler
{
    
    protected $_nonce;

    protected function __construct() {}


    
    protected function is_valid_request()
    {
        if (!current_user_can('manage_options') || !check_admin_referer($this->_nonce)) {
            wp_die(MoMessages::showMessage(esc_attr(MoMessages::INVALID_OP)));
        }
        return true;
    }


    
    protected function is_valid_ajax_request($key)
    {
        if(!check_ajax_referer($this->_nonce,$key)){
            wp_send_json(MoUtility::create_json(
                MoMessages::showMessage(BaseMessages::INVALID_OP),
                MoConstants::ERROR_JSON_TYPE
            ));
        }
    }


    
    public function get_nonce_value(){ return $this->_nonce; }
}