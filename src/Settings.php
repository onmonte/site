<?php

namespace Monte;

use Monte\Resources\Api;

class Settings extends Api
{

    public static function set($key, $value)
    {
        return self::request('/settings/' . $key . '/set', [], [
            'key' => $key,
            'value' => $value
        ], false, true);
    }

    public static function get($key = 'all')
    {
        $data = self::request('/settings/' . $key . '/get', [], [], true, false);

        if (!empty($data['value'])) {
            return $data['value'];
        }

        return $data;
    }

    public static function delete($key)
    {
        return self::request('/settings/' . $key . '/delete' . $key, [], [], false, true);
    }


}