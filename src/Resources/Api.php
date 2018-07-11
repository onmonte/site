<?php

namespace Monte\Resources;

class Api
{
    public static $apiDeveloperKey = 'beta';

    public static $apiSiteDomain;

    public static $apiSiteBase;

    const VERSION = '1.0';

    public static function setSiteDomain($domain)
    {
        Api::$apiSiteDomain = $domain;
    }

    protected static function setVariables()
    {
        if (empty(Api::$apiSiteDomain)) {
            Api::$apiSiteDomain = $_SERVER['HTTP_HOST'];

            if (Api::$apiSiteDomain == 'onmonte.com' && !empty($_GET['fd'])) {
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

    protected static function request($route, $clauses = [], $data = [])
    {
        Api::setVariables();

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/') . '?fd=' . Api::$apiSiteDomain . '&dk=' . Api::$apiDeveloperKey;

        $basePath = strstr(dirname(__FILE__), '/vendor/', true);


        $params = [
            'clauses' => $clauses,
            'data' => $data,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');

        $headers = [];
        $headers[] = "Content-Type: application/json";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        file_put_contents($basePath . '/../monte-library.log', json_encode([
                'date' => date('Y-m-d H:i:s'),
                'server_domain' => $_SERVER['HTTP_HOST'],
                'domain' => Api::$apiSiteDomain,
                'url' => $curlUrl,
                'result' => $result
            ]) . PHP_EOL, FILE_APPEND);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        $decodedResult = json_decode($result, true);

        return $decodedResult;
    }
}