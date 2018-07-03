<?php

namespace Monte;

use Monte\Resources\Api;

class Collection extends Api
{

    public static function createRow($category, $data = [])
    {
        return self::request('/collection/' . $category . '/create', [], $data);
    }

    public static function retrieveRow($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/retrieve', $clausesOrId);
    }

    public static function updateRow($category, $clausesOrId = [], $data = [])
    {
        return self::request('/collection/' . $category . '/update', $clausesOrId, $data);
    }

    public static function deleteRow($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/delete', $clausesOrId);
    }

    public static function listRows($category, $clauses = [])
    {
        return self::request('/collection/' . $category . '/list', $clauses);
    }


}