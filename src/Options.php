<?php

namespace Monte;

use Monte\Resources\Api;

class Options extends Api
{

    public static function get($category)
    {
        return self::request('/options/' . $category . '/retrieve', [], [], true, false);
    }

    public static function update($category, $data)
    {
        return self::request('/options/' . $category . '/update', [], $data, false, true);
    }


}