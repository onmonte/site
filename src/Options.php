<?php

namespace Monte;

use Monte\Resources\Api;

class Options extends Api
{

    public static function retrieve($category)
    {
        return self::request('/options/' . $category . '/retrieve', [], []);
    }

    public static function update($category, $data)
    {
        return self::request('/options/' . $category . '/update', [], $data);
    }


}