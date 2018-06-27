<?php

namespace Monte\Resources;

class Cache
{
    public static function get($key)
    {
        return $_COOKIE($key);
    }

    public static function store($key, $data, $ttl)
    {
        return setcookie($key, $data, time() + $ttl);
    }

    public static function delete($key)
    {
        return \setcookie($key, null, 0);
    }
}