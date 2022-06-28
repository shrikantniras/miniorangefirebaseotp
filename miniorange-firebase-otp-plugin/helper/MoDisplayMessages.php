<?php

namespace MoOTP\Helper;

if(! defined( 'ABSPATH' )) exit;

/**
 * This function is used to DisplayMessages in WordPress. You
 * can decide the HTML code to show your message based on the
 * type of the message you want to show.
 */
class MoDisplayMessages
{
    private $_message;
    private $_type;

    function __construct( $message,$type )
    {
        $this->_message = $message;
        $this->_type = $type;
        
        add_action( 'admin_notices', array( $this, 'render' ) );
    }

    function render()
    {
        switch ($this->_type)
        {
            case 'CUSTOM_MESSAGE':
                echo  esc_attr(mofr_($this->_message));
                break;
            case 'NOTICE':
                echo '<div style="margin-top:1%;"'.
                    'class="is-dismissible notice notice-warning mofr-admin-notif">'.
                    '<p>'.esc_attr(mofr_($this->_message)).'</p>'.
                    '</div>';
                break;
            case 'ERROR':
                echo '<div style="margin-top:1%;"'.
                    'class="notice notice-error is-dismissible mofr-admin-notif">'.
                    '<p>'.esc_attr(mofr_($this->_message)).'</p>'.
                    '</div>';
                break;
            case 'SUCCESS':
                echo '<div  style="margin-top:1%;"'.
                    'class="notice notice-success is-dismissible mofr-admin-notif">'.
                    '<p>'.esc_attr(mofr_($this->_message)).'</p>'.
                    '</div>';
                break;
        }
    }

    function show_message_div_addons(){
        switch ($this->_type) {
            case 'MO_ADDON_MESSAGE_CUSTOM_MESSAGE_SUCCESS':
                echo '<div  style="margin-top:1%;"'.
                    'class="notice notice-success is-dismissible mofr-admin-notif">'.
                    '<p>'.esc_attr(mofr_($this->_message)).'</p>'.
                    '</div>';
                # code...
                break;
            case 'MO_ADDON_MESSAGE_CUSTOM_MESSAGE_ERROR':
                echo '<div style="margin-top:1%;"'.
                    'class="notice notice-error is-dismissible mofr-admin-notif">'.
                    '<p>'.esc_attr(mofr_($this->_message)).'</p>'.
                    '</div>';
                break;
        }
    }
}