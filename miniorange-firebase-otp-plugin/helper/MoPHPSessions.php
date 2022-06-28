<?php

namespace MoOTP\Helper;
use MoOTP\Objects\IMoSessions;

if(! defined( 'ABSPATH' )) exit;

/** TODO: Need to move each session type to different files */
class MoPHPSessions implements IMoSessions
{
    /**
     * Sets session values.
     *
     * @param string $key
     * @param mixed $val
     */
    static function add_session_var($key,$val)
    {
        
        switch(MOFLR_SESSION_TYPE)
        {
            case 'COOKIE':
                setcookie($key, maybe_serialize($val));
                break;
            case 'SESSION':
                self::check_session();
                $_SESSION[$key] = maybe_serialize($val);
                break;
            case 'CACHE':
                if(!wp_cache_add($key,maybe_serialize($val))){
                    wp_cache_replace($key,maybe_serialize($val));
                }
                break;
            case 'TRANSIENT':
                /** TODO: Need to Simplify the Transient Code */
                $issettransient_key = sanitize_text_field($_COOKIE["transient_key"]);
                if(!isset($issettransient_key)) {
                    if(!wp_cache_get("transient_key")){
                        $transient_key = MoUtility::rand();
                        if(ob_get_contents()) ob_clean();
                        setcookie('transient_key',$transient_key,time()+12 * HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
                        wp_cache_add('transient_key',$transient_key);
                    }else{
                        $transient_key = wp_cache_get("transient_key");
                    }
                }else{
                    $transient_key = sanitize_text_field($_COOKIE["transient_key"]);
                }
                set_site_transient( $transient_key.$key, $val, 12 * HOUR_IN_SECONDS);
                break;

        }
    }

    /**
     * Return the value stored in session.
     *
     * @param string    $key    - key against the value is stored
     * @return mixed
     */
    static function get_session_var($key)
    {
        switch(MOFLR_SESSION_TYPE)
        {
            case 'COOKIE':
                return maybe_unserialize($_COOKIE[$key]);
            case 'SESSION':
                self::check_session();
                return maybe_unserialize(MoUtility::sanitize_check($key,$_SESSION));
            case 'CACHE':
                return maybe_unserialize(wp_cache_get($key));
            case 'TRANSIENT':
                $transientkey = sanitize_text_field($_COOKIE["transient_key"]);
                $transient_key = isset($transientkey)
                    ? sanitize_text_field($transientkey) : wp_cache_get("transient_key");
                return get_site_transient( $transient_key.$key );
        }
    }

    /**
     * Unsets the session values as per the type set for.
     *
     * @param string $key       -   key to unset.
     */
    static function unset_session($key)
    {
        switch(MOFLR_SESSION_TYPE)
        {
            case 'COOKIE':
                unset( $_COOKIE[$key] );
                setcookie( $key, '', time() - ( 15 * 60 ) );
                break;
            case 'SESSION':
                self::check_session();
                unset($_SESSION[$key]);
                break;
            case 'CACHE':
                wp_cache_delete( $key );
                break;
            case 'TRANSIENT':
                $issettransientkey = sanitize_text_field($_COOKIE["transient_key"]);
                $transient_key = isset($issettransientkey) ? sanitize_text_field($_COOKIE["transient_key"]) : wp_cache_get("transient_key");

                if(!MoUtility::is_blank($transient_key)) {
                    delete_site_transient($transient_key . $key);
                }
                break;
        }
    }

    /**
     * Checks if session started or not. Initiates session of not already initialized.
     */
    static function check_session()
    {
        if(MOFLR_SESSION_TYPE == 'SESSION')
        {
            if (session_id() == '' || !isset($_SESSION)) {
                session_start();
            }
        }
    }
}