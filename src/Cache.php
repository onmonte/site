<?php

namespace Monte;

class Cache
{
    /**
     * $cacheFolder is the path to the cache folder.
     * All cache files and folders will be created inside this folder
     *
     * You may set this value from the outside to change the path:
     * cache::$cacheFolder - 'some/other/cache/folder';
     * */
    public static $cacheFolder = 'cache/';

    /**
     * $lastAction is defaulted at NULL.
     * Every time the caching mechanism is used, this value is updated
     * to the last action taken by the cache:: class
     * These values may be either of the 2 class constants:
     * USE_CACHED_FILE or CACHE_FILE_NOW
     * */
    public static $lastAction = null;

    const USE_CACHED_FILE = 1;

    const CACHE_FILE_NOW = 2;

    /**
     * This function is accessed statically
     * cache::make( $name, $expires, $function );
     *
     * $name
     * =====
     * $name refers to the filename of the cache you're currently
     * making, it may be something like user_12_messages or
     * all_members or any string that may be used as a filename.
     * You may set this to a path, ie: users/12/messages
     *
     * $expires
     * ========
     * Number of minutes that the store will keep the cache alive, once
     * this time is exceeded, the mechanism will re-cache your code.
     *
     * $function
     * =========
     * This argument is a closure function containing whatever it is
     * you want to cache The caching mechanism will cache both
     * the function return and any echos and prints to the buffer,
     * so it may be used to cache both data and html.
     *
     * Example
     * =======
     * $q = cache::make('my_cache', 5, function(){
     *     print 'This is cached';
     *     return 'So it this';
     * }); // will immediately print 'This is cached'.
     *
     * print $q; // will print 'So is this'.
     *
     * */
    static function make($name, $expires, $function)
    {

        $cache_path = self::$cacheFolder . $name . '.json';

        $action = self::USE_CACHED_FILE;


        if (!file_exists(dirname($cache_path))) {
            mkdir(dirname($cache_path), 0777, true);
        }

        if (!file_exists($cache_path)) {
            $action = self::CACHE_FILE_NOW;
        } elseif ((time() - filemtime($cache_path)) > $expires * 60 && $expires != 0) {
            $action = self::CACHE_FILE_NOW;
        }

        switch ($action) {
            case self::USE_CACHED_FILE:
                $cache = json_decode(file_get_contents($cache_path));
                break;
            case self::CACHE_FILE_NOW:
                ob_start();
                $cache = array($function(), ob_get_clean());

                $fp = fopen($cache_path, "c");
                if (flock($fp, LOCK_EX)) {
                    // File locked and ready
                    fwrite($fp, json_encode($cache));
                    flock($fp, LOCK_UN);
                }
                fclose($fp);

                break;
        }

        self::$lastAction = $action;

        return $cache[0];
    }

    /**
     * This function is used to prematurely destroy an existing cache.
     * cache::destroy($name);
     *
     * $name
     * =====
     * The name (or full path) of the cache to be destroyed.
     * The name mey contain the "*" wildcard.
     *
     * Example
     * =======
     * cache::destroy('side_navigation');
     *
     * or
     *
     * cache::destroy('user_*_notifications');
     *
     * or even
     *
     * cache::destroy('user/*');
     * */
    static function destroy($name = '*')
    {
        if (strpos($name, '*') == -1) {
            @unlink(Cache::$cacheFolder . '/' . $name);
        } else {
            foreach (glob(Cache::$cacheFolder . '/' . $name) as $filename) {
                @unlink($filename);
            }
        }
    }
}