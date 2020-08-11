<?php

namespace App\Adapters;

class GuzzleAdapter implements RemoteClient
{

    private $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function request($method, $url, $params = [])
    {
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org', [], $params);
    }
}
