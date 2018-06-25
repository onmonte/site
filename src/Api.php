<?php

namespace Monte;

class Api
{
    public static $apiDeveloperKey;

    public static $apiSiteDomain;

    public static $apiSiteBase;

    const VERSION = '1.0';

    public static function setVariables()
    {
        if (empty(Api::$apiSiteDomain)) {
            Api::$apiSiteDomain = $_SERVER['HTTP_HOST'];
        }

        Api::$apiSiteBase = 'https://' . Api::$apiSiteDomain . '/api/' . Api::VERSION;
    }

    protected static function request($type = 'get', $route, $clauses = [], $data = [])
    {
        Api::setVariables();

        $params = [
            'fd' => $_SERVER['HTTP_HOST'],
            'clauses' => $clauses,
            'data' => $data,
        ];

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        switch (strtolower(trim($type))) {
            case 'get':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;

            case 'post':
                curl_setopt($ch, CURLOPT_POST, 1);
                break;

            case 'put':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;

            case 'delete':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return json_decode($result, true);
    }
}