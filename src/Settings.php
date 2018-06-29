<?php

namespace Monte;

use Monte\Resources\Api;

class Settings extends Api
{

    public static function set($key, $value)
    {
        return self::request('post', '/settings/' . $key, $value);
    }

    public static function retrieve($key = 'all')
    {
        $data = self::request('get', '/settings/' . $key);

        return !empty($data) && !empty($data['value']) ? $data['value'] : false;
    }

    public static function delete($key)
    {
        return self::request('delete', '/settings/' . $key);
    }


}