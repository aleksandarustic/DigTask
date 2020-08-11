<?php

namespace App\Services\Youtube;

/**
 * Service for manipulation with youtube api
 */
class YoutubeService implements YoutubeServiceInteface
{
    /**
     * User api key (secret)
     *
     * @var mixed
     */
    protected $api_key;
    /**
     * service configuration
     *
     * @var mixed
     */
    protected $config;
    /**
     * Client which is used to make api request
     *
     * @var mixed
     */
    protected $client;
    /**
     * Limiter class used to set rate limit
     *
     * @var mixed
     */
    protected $limiter;

    /**
     * List of available youtube api-s
     *
     * @var array
     */
    public $apis = [
        'videos.list' => 'https://www.googleapis.com/youtube/v3/videos'
    ];

    /**
     * Instanciate youtube service
     *
     * @param  mixed $api_key
     * @param  mixed $config
     * @param  mixed $client
     * @param  mixed $rate_limiter
     * @return void
     */
    public function __construct($api_key, $config = [], $client, $rate_limiter)
    {

        $this->api_key = $api_key;
        $this->config = $config;
        $this->client = $client;
        $this->limiter = $rate_limiter;
    }

    /**
     * Returns url of youtube api for specifid name
     * 
     * @param string $name
     * @return mixed
     */
    public function getApi($name): string
    {
        return $this->apis[$name];
    }

    /**
     * Sets user api key
     * 
     * @param string $key
     * @return YoutubeService
     */
    public function setApiKey($key): YoutubeService
    {
        $this->api_key = $key;

        return $this;
    }

    /**
     * Returns api key
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }


    /**
     * Return unique mahine id
     *
     * @return string
     */
    public function getComputerId(): string
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
    public function getPopularVideos(string $regionCode, int $maxResults = 10, array $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status'], bool $async = true)
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

    /**
     * Creates asnyc or regular api request and parse response
     *
     * @param  string $url
     * @param  array $params
     * @param  mixed $async
     * @return mixed
     */
    public function callApi(string $url, array $params, $async = false)
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
