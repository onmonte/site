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

            if (Api::$apiSiteDomain == '127.0.0.1:8989') {

                /*$c = new Cache();
                $c->eraseAll();*/

                Api::$apiSiteDomain = 'tiye.onmonte.com';
            } elseif (Api::$apiSiteDomain == 'onmonte.com' && !empty($_GET['fd'])) {
                Api::$apiSiteDomain = $_GET['fd'];
            } elseif (Api::$apiSiteDomain == 'onmonte.com') {
                $segments = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

                if (!empty($segments) && $segments[0] == 'admin' && !empty($segments[1])) {
                    Api::$apiSiteDomain = $segments[1];
                }
            }
        }

        Api::$apiSiteBase = 'https://onmonte.com/api/' . Api::VERSION;
    }

    protected static function request($type = 'get', $route, $clauses = [], $data = [])
    {
        Api::setVariables();

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/') . '?fd=' . Api::$apiSiteDomain . '&dk=' . Api::$apiDeveloperKey;

        $uniqueKey = base64_encode($type . urlencode($curlUrl) . sha1(serialize($clauses)) . sha1(serialize($data)));

        $c = new Cache([
            'name' => 'default',
            'path' => strstr(dirname(__FILE__), '/vendor/', true) . '/cache/',
            'extension' => '.cache'
        ]);

        if ($c->isCached($uniqueKey) && $_SERVER['REQUEST_METHOD'] === 'GET') {
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

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $c->store($uniqueKey, $decodedResult, 3600);
        }

        return $decodedResult;
    }
}