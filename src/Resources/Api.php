<?php

namespace Monte\Resources;

class Api
{
    public static $apiDeveloperKey;

    public static $apiSiteDomain;

    public static $apiSiteBase;

    const VERSION  = '1.0';

    protected static function setVariables()
    {
        if (empty(Api::$apiSiteDomain)) {
            Api::$apiSiteDomain = $_SERVER['HTTP_HOST'];
        }

        Api::$apiSiteBase = 'https://onmonte.com/api/' . Api::VERSION;
    }

    protected static function request($type = 'get', $route, $clauses = [], $data = [])
    {
        Api::setVariables();

        $params = [
            'fd' => Api::$apiSiteDomain,
            'dk' => Api::$apiSiteDomain,
            'clauses' => Api::$apiDeveloperKey,
            'data' => $data,
        ];

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $type = strtolower(trim($type));

        switch ($type) {
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

        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . Api::$apiDeveloperKey;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        $decodedResult = json_decode($result, true);

        return $decodedResult;
    }
}