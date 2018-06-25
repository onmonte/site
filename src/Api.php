<?php

namespace Monte;

class Api
{
    public static $apiKey;

    public static $apiDomain;

    public static $apiBase;

    const VERSION = '1.0';

    public function __construct()
    {
        $this::$apiDomain = $_SERVER['HTTP_HOST'];

        $this::$apiBase = 'https://' . $this::$apiDomain . '/api/' . Api::VERSION;
    }

    protected static function request($type = 'get', $route, $clauses = [], $data = [])
    {
        $params = [
            'fd' => $_SERVER['HTTP_HOST'],
            'clauses' => $clauses,
            'data' => $data,
        ];

        $curlUrl = strpos($route, Api::$apiBase) !== false ? $route : Api::$apiBase . '/' . trim($route, '/');

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


        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: Bearer " . Api::$apiKey;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return json_decode($result, true);
    }
}