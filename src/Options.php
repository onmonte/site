<?php

namespace Monte;

use Monte\Resources\Api;

class Options extends Api
{

    public static $name;

    public static $title;

    public static $description;

    public static $fields;


    public static function get()
    {
        return self::request('/options/' . self::$name . '/retrieve', [], [], true, false);
    }

    public static function update($data)
    {
        return self::request('/options/' . self::$name . '/update', [], $data, false, true);
    }


}