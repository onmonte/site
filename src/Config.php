<?php

namespace Monte;

use Monte\Resources\Api;

class Config extends Api
{

    public static function settings()
    {
        return self::request( '/config/settings', [], []);
    }


    public static function collections()
    {
        return self::request( '/config/collections', [], []);
    }

    public static function options()
    {
        return self::request( '/config/options', [], []);
    }


    public static function hourly()
    {
        return self::request( '/config/hourly');
    }

    public static function daily()
    {
        return self::request( '/config/daily');
    }

    public static function monthly()
    {
        return self::request( '/config/monthly');
    }


}