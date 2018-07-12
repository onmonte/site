<?php

namespace Monte;

use Monte\Resources\Api;

class Collection extends Api
{

    public static $name;

    public static $title;

    public static $description;

    public static $paginate;

    public static $sortable;

    public static $fields;

    public static function create($data = [])
    {
        return self::request('/collection/' . self::$name . '/create', [], $data, false, true);
    }

    public static function find($clausesOrId = [])
    {
        return self::request('/collection/' . self::$name . '/find', $clausesOrId, [], true, false);
    }

    public static function update($clausesOrId = [], $data = [])
    {
        return self::request('/collection/' . self::$name . '/update', $clausesOrId, $data, false, true);
    }

    public static function delete($clausesOrId = [])
    {
        return self::request('/collection/' . self::$name . '/delete', $clausesOrId, [], false, true);
    }

    public static function get($clauses = [])
    {
        return self::request('/collection/' . self::$name . '/get', $clauses, [],true, false);
    }


}