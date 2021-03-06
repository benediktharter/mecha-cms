<?php


/**
 * Shortcode Manager
 * -----------------
 */

Route::accept($config->manager->slug . '/shortcode', function() use($config, $speak) {
    if(Guardian::get('status') != 'pilot') {
        Shield::abort();
    }
    $d = DECK . DS . 'workers' . DS . 'repair.state.shortcode.php';
    $shortcodes = file_exists($d) ? include $d : array();
    if($file = Get::state_shortcode()) {
        foreach($file as $key => $value) {
            unset($shortcodes[$key]);
        }
        $shortcodes = array_merge($shortcodes, $file);
    }
    $G = array('data' => $shortcodes);
    Config::set(array(
        'page_title' => $speak->shortcodes . $config->title_separator . $config->manager->title,
        'files' => $shortcodes,
        'cargo' => DECK . DS . 'workers' . DS . 'shortcode.php'
    ));
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        $data = array();
        for($i = 0, $keys = $request['keys'], $count = count($keys); $i < $count; ++$i) {
            if(trim($keys[$i]) !== "") {
                $data[$keys[$i]] = $request['values'][$i];
            }
        }
        $P = array('data' => $data);
        File::serialize($data)->saveTo(STATE . DS . 'shortcode.txt', 0600);
        Notify::success(Config::speak('notify_success_updated', array($speak->shortcode)));
        Weapon::fire('on_shortcode_update', array($G, $P));
        Guardian::kick($config->url_current);
    }
    Shield::attach('manager', false);
});