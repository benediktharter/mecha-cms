<?php


/**
 * Cache Manager
 * -------------
 */

Route::accept(array($config->manager->slug . '/cache', $config->manager->slug . '/cache/(:num)'), function($offset = 1) use($config, $speak) {
    if(Guardian::get('status') != 'pilot') {
        Shield::abort();
    }
    $offset = (int) $offset;
    $takes = Get::files(CACHE, '*', 'DESC', 'update');
    if($_files = Mecha::eat($takes)->chunk($offset, $config->per_page * 2)->vomit()) {
        $files = array();
        foreach($_files as $_file) $files[] = $_file;
    } else {
        $files = false;
    }
    Config::set(array(
        'page_title' => $speak->caches . $config->title_separator . $config->manager->title,
        'offset' => $offset,
        'files' => $files,
        'pagination' => Navigator::extract($takes, $offset, $config->per_page * 2, $config->manager->slug . '/cache'),
        'cargo' => DECK . DS . 'workers' . DS . 'cache.php'
    ));
    Shield::attach('manager', false);
});


/**
 * Cache Repair
 * ------------
 */

Route::accept($config->manager->slug . '/cache/repair/(file|files):(:all)', function($path = "", $name = "") use($config, $speak) {
    if(Guardian::get('status') != 'pilot') {
        Shield::abort();
    }
    $name = File::path($name);
    if( ! $file = File::exist(CACHE . DS . $name)) {
        Shield::abort(); // File not found!
    }
    $G = array('data' => array('path' => $file, 'content' => File::open($file)->read()));
    Config::set(array(
        'page_title' => $speak->editing . ': ' . basename($name) . $config->title_separator . $config->manager->title,
        'cargo' => DECK . DS . 'workers' . DS . 'repair.cache.php'
    ));
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        $P = array('data' => $request);
        File::open($file)->write($request['content'])->save(0600);
        Notify::success(Config::speak('notify_file_updated', array('<code>' . basename($name) . '</code>')));
        Weapon::fire('on_cache_update', array($G, $P));
        Weapon::fire('on_cache_repair', array($G, $P));
        Guardian::kick($config->manager->slug . '/cache/repair/file:' . File::url($name));
    }
    Shield::define(array(
        'the_name' => $name,
        'the_content' => File::open($file)->read()
    ))->attach('manager', false);
});


/**
 * Cache Killer
 * ------------
 */

Route::accept($config->manager->slug . '/cache/kill/(file|files):(:all)', function($path = "", $name = "") use($config, $speak) {
    if(Guardian::get('status') != 'pilot') {
        Shield::abort();
    }
    $name = File::path($name);
    if(strpos($name, ';') !== false) {
        $deletes = explode(';', $name);
    } else {
        if( ! File::exist(CACHE . DS . $name)) {
            Shield::abort(); // File not found!
        } else {
            $deletes = array($name);
        }
    }
    Config::set(array(
        'page_title' => $speak->deleting . ': ' . (count($deletes) === 1 ? basename($name) : $speak->caches) . $config->title_separator . $config->manager->title,
        'cargo' => DECK . DS . 'workers' . DS . 'kill.cache.php'
    ));
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        $info_path = array();
        foreach($deletes as $file_to_delete) {
            $_path = CACHE . DS . $file_to_delete;
            $info_path[] = $_path;
            File::open($_path)->delete();
        }
        $P = array('data' => array('files' => $info_path));
        Notify::success(Config::speak('notify_file_deleted', array('<code>' . implode('</code>, <code>', $deletes) . '</code>')));
        Weapon::fire('on_cache_update', array($P, $P));
        Weapon::fire('on_cache_destruct', array($P, $P));
        Guardian::kick($config->manager->slug . '/cache');
    } else {
        Notify::warning(count($deletes) === 1 ? Config::speak('notify_confirm_delete_', array('<code>' . File::path($name) . '</code>')) : $speak->notify_confirm_delete);
    }
    Shield::define('the_name', $deletes)->attach('manager', false);
});


/**
 * Multiple Cache Killer
 * ---------------------
 */

Route::accept($config->manager->slug . '/cache/kill', function($path = "") use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        if( ! isset($request['selected'])) {
            Notify::error($speak->notify_error_no_files_selected);
            Guardian::kick($config->manager->slug . '/cache');
        }
        $files = array();
        foreach($request['selected'] as $file) {
            $files[] = str_replace('%2F', '/', Text::parse($file, '->encoded_url'));
        }
        Guardian::kick($config->manager->slug . '/cache/kill/files:' . implode(';', $files));
    }
});