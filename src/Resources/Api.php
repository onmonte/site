<?php

namespace Monte\Resources;

class Api
{
    public static $apiDeveloperKey;

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

    protected static function request($route, $clauses = [], $data = [], $cache = false, $erase = false)
    {
        Api::setVariables();

        $curlUrl = strpos($route, Api::$apiSiteBase) !== false ? $route : Api::$apiSiteBase . '/' . trim($route, '/') . '?fd=' . Api::$apiSiteDomain . '&dk=' . Api::$apiDeveloperKey;

        $uniqueKey = base64_encode(urlencode($curlUrl) . serialize($clauses) . serialize($data));

        $basePath = strstr(dirname(__FILE__), '/vendor/', true);

        $siteConfigFile = $basePath . '/config.json';

        $sitePath = $basePath . '/../sites/' . Api::$apiSiteDomain;

        $fromMainConfigFile = $sitePath . '/config.json';

        $cacheBasePath = $basePath;

        if (file_exists($siteConfigFile)) {
            $configFile = $siteConfigFile;
        } else if (file_exists($fromMainConfigFile)) {
            $configFile = $fromMainConfigFile;

            $cacheBasePath = $sitePath;
        }

        if (!empty($configFile)) {
            $config = file_get_contents($configFile);

            $configSettings = json_decode($config, true);

            if (empty($configSettings['cache_path'])) {
                $configSettings['cache_path'] = '/cache';
            }

            $cachePath = $cacheBasePath . '/' . trim($configSettings['cache_path'], '/') . '/';
        } else {
            $cachePath = $cacheBasePath . '/cache/';
        }

        $c = new Cache([
            'name' => 'default',
            'path' => $cachePath,
            'extension' => '.cache'
        ]);

        $params = [
            'clauses' => $clauses,
            'data' => $data,
        ];

        if ($c->isCached($uniqueKey) && $cache) {
            $data = $c->retrieve($uniqueKey);

            if ($erase) {
                $c->eraseAll();
            }

            return $data;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $curlUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);*/
        /*curl_setopt($ch, CURLOPT_VERBOSE, true);*/
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        /*curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);*/

        /*$headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . Api::$apiDeveloperKey;*/

        /*curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);*/

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        $decodedResult = json_decode($result, true);

        if ($erase) {
            $c->eraseAll();
        }

        if ($cache) {
            $c->store($uniqueKey, $decodedResult, 3600);
        }

        return $decodedResult;
    }
}