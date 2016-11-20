<?php

class navigation
{
    function __construct()
    {

    }

    static function is_in_page($pagename){
        $args = func_get_args();
        if (basename($_SERVER['PHP_SELF']) == $args || basename($_SERVER['PHP_SELF']) == $pagename){
            return true;
        }
        return false;
    }

    static function go_to_url($url) {
        header("Location: $url");
    }
}