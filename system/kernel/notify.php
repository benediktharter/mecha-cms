<?php

/**
 * ==============================================
 *  NOTIFICATION MESSAGES
 * ==============================================
 *
 * -- CODE: -------------------------------------
 *
 *    // Set
 *    Notify::error('Hi, there was an error!');
 *    Notify::info('PS: Hi again!');
 *
 * ----------------------------------------------
 *
 *    // Get
 *    echo Notify::read();
 *
 * ----------------------------------------------
 *
 *    // Clear
 *    echo Notify::clear();
 *
 * ----------------------------------------------
 *
 */

class Notify {

    private static $o = array();

    public static $message = 'message';
    public static $errors = 0;

    public static $config = array(
        'icons' => array(
            'default' => '<i class="fa fa-fw fa-microphone"></i> ',
            'success' => '<i class="fa fa-fw fa-check"></i> ',
            'info' => '<i class="fa fa-fw fa-info-circle"></i> ',
            'warning' => '<i class="fa fa-fw fa-exclamation-triangle"></i> ',
            'error' => '<i class="fa fa-fw fa-times"></i> '
        ),
        'classes' => array(
            'messages' => 'messages',
            'message' => 'message message-%s cl cf'
        )
    );

    public static function add($type = 'default', $text = "", $icon = null, $tag = 'p') {
        $icon = is_null($icon) ? self::$config['icons'][$type] : $icon;
        Session::set(self::$message, Session::get(self::$message) . TAB . '<' . $tag . ' class="' . sprintf(self::$config['classes']['message'], $type) . '">' . $icon . $text . '</' . $tag . '>' . NL);
    }

    public static function success($text = "", $icon = null, $tag = 'p') {
        self::add('success', $text, $icon, $tag);
        Guardian::forget();
    }

    public static function info($text = "", $icon = null, $tag = 'p') {
        self::add('info', $text, $icon, $tag);
    }

    public static function warning($text = "", $icon = null, $tag = 'p') {
        self::add('warning', $text, $icon, $tag);
        Guardian::memorize();
        self::$errors++;
    }

    public static function error($text = "", $icon = null, $tag = 'p') {
        self::add('error', $text, $icon, $tag);
        Guardian::memorize();
        self::$errors++;
    }

    public static function errors() {
        return self::$errors > 0 ? self::$errors : false;
    }

    public static function read() {
        $results = Session::get(self::$message) !== "" ? O_BEGIN . '<div class="' . self::$config['classes']['messages'] . '">' . NL . Session::get(self::$message) . '</div>' . O_END : "";
        self::clear();
        return $results;
    }

    public static function clear() {
        Session::kill(self::$message);
    }

    public static function send($from, $to, $subject, $message, $FP = 'common:') {

        if(trim($to) === "" || ! Guardian::check($to, '->email')) return false;

        $header  = "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $header .= "From: " . $from . "\r\n";
        $header .= "Reply-To: " . $from . "\r\n";
        $header .= "Return-Path: " . $from . "\r\n";
        $header .= "X-Mailer: PHP/" . phpversion();

        $header = Filter::apply($FP . 'notification.email.header', $header);
        $message = Filter::apply($FP . 'notification.email.message', $message);

        return mail($to, $subject, $message, $header);

    }

    // Add new method with `Notify::plug('foo')`
    public static function plug($kin, $action) {
        self::$o[$kin] = $action;
    }

    // Call the added method with `Notify::foo()`
    public static function __callStatic($kin, $arguments = array()) {
        if( ! isset(self::$o[$kin])) {
            Guardian::abort('Method <code>Notify::' . $kin . '()</code> does not exist.');
        }
        return call_user_func_array(self::$o[$kin], $arguments);
    }

}