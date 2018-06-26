<?php

namespace Monte\Resources;

class Cache
{
    public static function get($key)
    {
        return apc_fetch($key);
    }

    public static function store($key, $data, $ttl)
    {
        return apc_store($key, $data, $ttl);
    }

    public static function delete($key)
    {
        return apc_delete($key);
    }
}