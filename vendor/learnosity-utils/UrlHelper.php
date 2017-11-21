<?php

class UrlHelper
{

    public static function get_url_parameter($key, $default = '')
    {
        if (!isset($_GET[$key]) || empty($_GET[$key])) {
            return $default;
        }
        return strip_tags((string) wp_unslash($_GET[$key]));
    }
}