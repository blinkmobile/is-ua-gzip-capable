<?php

namespace blinkmobile;

class IsUaGzipCapable
{
    private static function testAcceptEncoding($acceptEncoding = null)
    {
        return is_string($acceptEncoding) && stripos($acceptEncoding, 'gzip') !== false;
    }

    private static function testUserAgentString($userAgent = null)
    {
        if (empty($userAgent) || !is_string($userAgent)) {
            return true;
        }
        if (stripos($userAgent, 'MSIE') !== false ||  stripos($userAgent, 'Trident') !== false) {
            if (stripos($userAgent, 'rv:11.0') !== false) {
                // https://connect.microsoft.com/IE/feedbackdetail/view/950689
                return false;
            }
            // http://httpd.apache.org/docs/2.0/en/mod/mod_deflate.html
            return true;
        }

        if (stripos($userAgent, 'Mozilla/4') === 0) {
            // http://httpd.apache.org/docs/2.0/en/mod/mod_deflate.html
            // technically we should still GZIP text/html, but this is easier
            return false;
        }
        return true;
    }

    public static function testRequest($_server = null)
    {
        if ($_server == null || !is_array($_server)) {
            if (!empty($_SERVER)) {
                $_server = $_SERVER;
            } else {
                $_server = array(
                    'HTTP_ACCEPT_ENCODING' => '',
                    'HTTP_USER_AGENT' => ''
                );
            }
        }
        if (!self::testAcceptEncoding($_server['HTTP_ACCEPT_ENCODING'])) {
            // never GZIP if the user agent doesn't accept it
            return false;
        }
        return self::testUserAgentString($_server['HTTP_USER_AGENT']);
    }
}
