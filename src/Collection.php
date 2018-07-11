<?php

namespace Monte;

use Monte\Resources\Api;

class Collection extends Api
{

    public static function createRow($category, $data = [])
    {
        return self::request('/collection/' . $category . '/create', [], $data, false, true);
    }

    public static function retrieveRow($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/retrieve', $clausesOrId, [], true, false);
    }

    public static function updateRow($category, $clausesOrId = [], $data = [])
    {
        return self::request('/collection/' . $category . '/update', $clausesOrId, $data, false, true);
    }

    public static function deleteRow($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/delete', $clausesOrId, [], false, true);
    }

    public static function listRows($category, $clauses = [])
    {
        return self::request('/collection/' . $category . '/list', $clauses, [],true, false);
    }


}