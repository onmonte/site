<?php

namespace Monte;

use Monte\Resources\Api;

class Config extends Api
{

    public static function collections()
    {
        return self::request('get', '/config/collections');
    }

    public static function options()
    {
        return self::request('get', '/config/options');
    }


}