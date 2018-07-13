<?php

namespace Monte;

use Monte\Resources\Api;

class Config extends Api
{

    public static function collections()
    {
        return self::request('/config/collections', [], [], true, false);
    }

    public static function options()
    {
        return self::request('/config/options', [], [], true, false);
    }


    public static function hourly()
    {
        return self::request('/config/hourly', [], [], false, true);
    }

    public static function daily()
    {
        return self::request('/config/daily', [], [], false, true);
    }

    public static function monthly()
    {
        return self::request('/config/monthly', [], [], false, true);
    }


}