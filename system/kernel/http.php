<?php

class HTTP {

    public static $messages = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // RFC4918
        208 => 'Already Reported', // RFC5842
        226 => 'IM Used', // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect', // RFC-reschke-http-status-308-07
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC2324
        422 => 'Unprocessable Entity', // RFC4918
        423 => 'Locked', // RFC4918
        424 => 'Failed Dependency', // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal', // RFC2817
        426 => 'Upgrade Required', // RFC2817
        428 => 'Precondition Required', // RFC6585
        429 => 'Too Many Requests', // RFC6585
        431 => 'Request Header Fields Too Large', // RFC6585
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)', // RFC2295
        507 => 'Insufficient Storage', // RFC4918
        508 => 'Loop Detected', // RFC5842
        510 => 'Not Extended', // RFC2774
        511 => 'Network Authentication Required', // RFC6585
    );

    private static $o = array();

    /**
     * ============================================================
     *  SET HTTP RESPONSE STATUS
     * ============================================================
     *
     * -- CODE: ---------------------------------------------------
     *
     *    HTTP::status(404); // 404 Not Found
     *
     * ------------------------------------------------------------
     *
     */

    public static function status($code = 200, $value = null) {
        if(is_int($code) && isset(self::$messages[$code])) {
            if(strpos(PHP_SAPI, 'cgi') !== false) {
                header('Status: ' . $code . ' ' . self::$messages[$code]);
            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' ' . self::$messages[$code]);
            }
        } else 
        return new static;
    }

    /**
     * ============================================================
     *  SET HTTP RESPONSE HEADER
     * ============================================================
     *
     * -- CODE: ---------------------------------------------------
     *
     *    HTTP::header('Content-Type', 'text/plain');
     *
     * ------------------------------------------------------------
     *
     */

    public static function header($key, $value = null) {
        if( ! is_array($key)) {
            if(is_int($key)) {
                self::status($key);
            } else {
                if( ! is_null($value)) {
                    header($key . ': ' . $value);
                } else {
                    header($key);
                }
            }
        } else {
            foreach($key as $k => $v) {
                header($k . ': ' . $v);
            }
        }
    }

    /**
     * ============================================================
     *  SET CARGO MIME TYPE
     * ============================================================
     *
     * -- CODE: ---------------------------------------------------
     *
     *    HTTP::mime('text/plain', 'UTF-8');
     *
     * ------------------------------------------------------------
     *
     */

    public static function mime($mime, $charset = null) {
        header('Content-Type: ' . $mime . ( ! is_null($charset) ? '; charset=' . $charset : ""));
        return new static;
    }

    // Add new method with `HTTP::plug('foo')`
    public static function plug($kin, $action) {
        self::$o[$kin] = $action;
    }

    // Call the added method with `HTTP::foo()`
    public static function __callStatic($kin, $arguments = array()) {
        if( ! isset(self::$o[$kin])) {
            Guardian::abort('Method <code>HTTP::' . $kin . '()</code> does not exist.');
        }
        return call_user_func_array(self::$o[$kin], $arguments);
    }

}