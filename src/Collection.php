<?php

namespace Monte;

use Monte\Resources\Api;

class Collection extends Api
{

    public static function createRow($collection, $data = [])
    {
        return self::request('post', '/collection/' . $collection, [], $data);
    }

    public static function retrieveRow($collection, $clausesOrId = [])
    {
        return self::request('get', '/collection/' . $collection . '/retrieve', $clausesOrId);
    }

    public static function updateRow($collection, $clausesOrId = [], $data = [])
    {
        return self::request('post', '/collection/' . $collection . '/update', $clausesOrId, $data);
    }

    public static function deleteRow($collection, $clausesOrId = [])
    {
        return self::request('delete', '/collection/' . $collection . '/delete', $clausesOrId);
    }

    public static function listRows($collection, $clauses = [])
    {
        return self::request('get', '/collection/' . $collection, $clauses);
    }


}