<?php

namespace Monte;

use Monte\Resources\Api;

class Config extends Api
{

    public static function settings()
    {
        return self::request('get', '/config/settings');
    }


    public static function collections()
    {
        return self::request('get', '/config/collections');
    }

    public static function options()
    {
        return self::request('get', '/config/options');
    }


    public static function hourly()
    {
        return self::request('get', '/config/hourly');
    }

    public static function daily()
    {
        return self::request('get', '/config/daily');
    }

    public static function monthly()
    {
        return self::request('get', '/config/monthly');
    }


}