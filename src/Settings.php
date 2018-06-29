<?php

namespace Monte;

use Monte\Resources\Api;

class Settings extends Api
{

    public static function set($key, $value)
    {
        return self::request('post', '/settings/' . $key, [], [
            'value' => $value
        ]);
    }

    public static function retrieve($key = 'all')
    {
        $data = self::request('get', '/settings/' . $key);

        if (!empty($data['value'])) {
            return $data['value'];
        }

        return $data;
    }

    public static function delete($key)
    {
        return self::request('delete', '/settings/' . $key);
    }


}