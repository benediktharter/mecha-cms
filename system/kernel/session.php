<?php

/**
 * ======================================================================
 *  SESSION
 * ======================================================================
 *
 * -- CODE: -------------------------------------------------------------
 *
 *    // Set session
 *    Session::set('foo', 'bar');
 *
 * ----------------------------------------------------------------------
 *
 *    // Get session
 *    echo Session::get('foo'); // => `bar`
 *
 * ----------------------------------------------------------------------
 *
 *    // Get all sessions
 *    var_dump(Session::get());
 *
 * ----------------------------------------------------------------------
 *
 *    // Remove session
 *    Session::kill('foo');
 *
 * ----------------------------------------------------------------------
 *
 *    // Remove all sessions
 *    Session::kill();
 *
 * ----------------------------------------------------------------------
 *
 *    // Set cookie
 *    Session::set('cookie:foo', 'bar', 3600);
 *
 * ----------------------------------------------------------------------
 *
 *    // Get cookie
 *    echo Session::get('cookie:foo'); // => `bar`
 *
 * ----------------------------------------------------------------------
 *
 *    // Get all cookies
 *    var_dump(Session::get('cookies'));
 *
 * ----------------------------------------------------------------------
 *
 *    // Remove cookie
 *    Session::kill('cookie:foo');
 *
 * ----------------------------------------------------------------------
 *
 *    // Remove all cookies
 *    Session::kill('cookies');
 *
 * ----------------------------------------------------------------------
 *
 */

class Session {

    public static function set($session, $value = "", $expire = 1, $path = '/', $domain = "", $secure = false, $http_only = false) {
        if(strpos($session, 'cookie:') === 0) {
            $name = substr($session, 7);
            $expire = time() + 60 * 60 * 24 * $expire;
            if(strpos($name, '.') !== false) {
                $parts = explode('.', $name);
                $name = array_shift($parts);
                $old = Converter::strEval(isset($_COOKIE[$name]) ? $_COOKIE[$name] : array());
                if(is_object($value)) $value = Mecha::A($value);
                Mecha::SVR($old, implode('.', $parts), $value);
                $value = $old;
            }
            setcookie($name, json_encode($value, true), $expire, $path, $domain, $secure, $http_only);
            setcookie('__' . $name, json_encode(array($expire, $path, $domain, $secure, $http_only), true), $expire, '/', "", false, false);
        } else {
            Mecha::SVR($_SESSION, $session, $value);
        }
    }

    public static function get($session = null, $fallback = "") {
        if(is_null($session)) return $_SESSION;
        if($session == 'cookies') {
            return Converter::strEval($_COOKIE);
        }
        if(strpos($session, 'cookie:') === 0) {
            $name = substr($session, 7);
            $cookie = isset($_COOKIE) ? Converter::strEval($_COOKIE) : $fallback;
            $value = Mecha::GVR($cookie, $name, $fallback);
            return ! is_array($value) && ! is_null(json_decode($value, true)) ? json_decode($value, true) : $value;
        }
        return Mecha::GVR($_SESSION, $session, $fallback);
    }

    public static function kill($session = null) {
        if(is_null($session)) {
            session_destroy();
        } else if($session == 'cookies') {
            $_COOKIE = array();
            if(isset($_SERVER['HTTP_COOKIE'])) {
                $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                foreach($cookies as $cookie) {
                    $parts = explode('=', $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, null, -1);
                    setcookie($name, null, -1, '/');
                }
            }
        } else if(strpos($session, 'cookie:') === 0) {
            $name = substr($session, 7);
            if(strpos($session, '.') !== false) {
                $name_a = explode('.', $name);
                $old = Converter::strEval($_COOKIE[$name_a[0]]);
                Mecha::UVR($old, $name);
                foreach($old as $key => $value) {
                    $_COOKIE[$name_a[0]][$key] = is_array($value) ? json_encode($value, true) : $value;
                }
                $c = Converter::strEval($_COOKIE['__' . $name_a[0]]);
                setcookie($name_a[0], json_encode($old, true), $c[0], $c[1], $c[2], $c[3], $c[4]);
            } else {
                unset($_COOKIE[$name]);
                self::set($session, null, -1);
            }
        } else {
            Mecha::UVR($_SESSION, $session);
        }
    }

}