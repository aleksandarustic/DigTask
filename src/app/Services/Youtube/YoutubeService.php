<?php

namespace App\Services\Youtube;

class YoutubeService implements YoutubeServiceInteface
{
    protected $api_key;
    protected $config;
    protected $client;
    protected $limiter;

    public $apis = [
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos'
    ];

    public function __construct($api_key, $config = [], $client, $rate_limiter)
    {

        $this->api_key = $api_key;
        $this->config = $config;
        $this->client = $client;
        $this->limiter = $rate_limiter;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getApi($name)
    {
        return $this->apis[$name];
    }

    /**
     * @param $key
     * @return Youtube
     */
    public function setApiKey($key)
    {
        $this->api_key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }


    public function getComputerId()
    {
        return $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Gets popular videos for a specific region (ISO 3166-1 alpha-2)
     *
     * @param $regionCode
     * @param integer $maxResults
     * @param array $part
     */
    public function getPopularVideos($regionCode, $maxResults = 10, $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status'], $async = true)
    {
        $url = $this->getApi('videos.list');

        $params = [
            'key' => $this->api_key,
            'chart' => 'mostPopular',
            'part' => implode(',', $part),
            'regionCode' => $regionCode,
            'maxResults' => $maxResults,
        ];

        $apiData = $this->callApi($url, $params,  $async);

        return $apiData;
    }


    public function callApi($url, $params, $async = false)
    {
        try {

            $headers = [
                'Accept' => 'application/json',
            ];

            $key = $this->getComputerId() . '|' . $url;

            if ($this->limiter->tooManyAttempts($key, 60)) {
                throw new \Exception('Too many requests', 429);
            }

            if ($async) {
                $response = $this->client->requestAsync('GET', $url, ['headers' => $headers, 'query' =>  $params])->then(function ($res) {
                    return json_decode($res->getBody(), true);
                });
            } else {
                $response = json_decode($this->client->request('GET', $url, ['headers' => $headers, 'query' => $params])->getBody(), true);
            }

            $this->limiter->hit($key, 1 * 60);

        } catch (\Exception $e) {

            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $response;
    }

}
