<?php

namespace Monte\Resources;

class Api
{
    public static $apiDeveloperKey;

    public static $apiSiteDomain;

    public static $apiSiteBase;

    const VERSION  = '1.0';

    public static function setSiteDomain($domain)
    {
        Api::$apiSiteDomain = $domain;
    }

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

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/') . '?fd=' . Api::$apiSiteDomain . '&dk=' . Api::$apiDeveloperKey;

        $uniqueKey = base64_encode($type . urlencode($curlUrl) . sha1(serialize($clauses)) . sha1(serialize($data)));

        $c = new Cache();

        if ($c->isCached($uniqueKey)) {
            return $c->retrieve($uniqueKey);
        }

        if (!empty($clauses) || !empty($data)) {
            $params = [
                'clauses' => $clauses,
                'data' => $data,
            ];
        }

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
                $c->eraseAll();

                curl_setopt($ch, CURLOPT_POST, 1);
                break;

            case 'put':
                $c->eraseAll();

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;

            case 'delete':
                $c->eraseAll();

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

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

        $c->store($uniqueKey, $decodedResult, 3600);

        return $decodedResult;
    }
}