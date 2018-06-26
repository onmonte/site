<?php

namespace Monte;

use Monte\Resources\Api;

class Options extends Api
{

    public static function retrieve($category)
    {
        return self::request('get', '/options/' . $category);
    }

    public static function update($category, $data)
    {
        return self::request('post', '/options/' . $category, [], $data);
    }


}