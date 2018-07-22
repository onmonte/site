<?php

namespace Monte;

use Monte\Resources\Api;

class Collection extends Api
{


    public static function create($category, $data = [])
    {
        return self::request('/collection/' . $category . '/create', [], $data, false, true);
    }

    public static function find($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/find', $clausesOrId, [], true, false);
    }

    public static function update($category, $clausesOrId = [], $data = [])
    {
        return self::request('/collection/' . $category . '/update', $clausesOrId, $data, false, true);
    }

    public static function delete($category, $clausesOrId = [])
    {
        return self::request('/collection/' . $category . '/delete', $clausesOrId, [], false, true);
    }

    public static function get($category, $clauses = [], $paginate = 20, $page = 1)
    {
        return self::request('/collection/' . $category . '/get', $clauses, [
            'paginate' => $paginate,
            'page' => $page
        ],true, false);
    }


}