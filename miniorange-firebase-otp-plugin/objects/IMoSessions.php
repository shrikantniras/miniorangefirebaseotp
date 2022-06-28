<?php

namespace MoOTP\Objects;

interface IMoSessions
{
    static function add_session_var($key,$val);
    static function get_session_var($key);
    static function unset_session($key);
    static function check_session();
}