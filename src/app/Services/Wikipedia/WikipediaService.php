<?php

namespace App\Services\Wikipedia;

use InvalidArgumentException;

/**
 * Service for manipulation with wikipedia api
 */
class WikipediaService implements WikipediaServiceInteface
{
    /**
     * Client which is used to make api request
     *
     * @var mixed
     */
    protected $client;

    /**
     * Language of wikipedia which will be used 
     *
     * @var string
     */
    protected $lang;

    /**
     * Limiter class used to set rate limit
     *
     * @var mixed
     */
    protected $limiter;

    /**
     * Language Supported
     * @var array
     */
    private $language_supported = ['de', 'en', 'es', 'fr', 'it', 'nl', 'pl', 'ru', 'ceb', 'sv', 'vi', 'war'];

    /**
     * Instanciate wikipedia service
     *
     * @param  string $lang
     * @param  mixed $client
     * @param  mixed $rate_limiter
     */
    public function __construct(string $lang = 'en', $client, $rate_limiter)
    {
        $this->setLang($lang);

        $this->client = $client;
        $this->limiter = $rate_limiter;
    }

    /**
     * Wikipedia base url
     *
     * @return string
     */
    protected function baseUri(): string
    {
        return "https://{$this->lang}.wikipedia.org/w/api.php";
    }

    /**
     * @param  string
     * @return WikipediaService
     */
    public function setLang($lang): WikipediaService
    {
        if (!in_array($lang, $this->getSupportedLanguages())) {
            throw new InvalidArgumentException(
                '$lang should have valid language'
            );
        }

        $this->lang = $lang;

        return $this;
    }

    /**
     * Return language
     * @return string 
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     *  Get the list of supported languages
     * @return array
     */
    public function getSupportedLanguages(): array
    {
        return $this->language_supported;
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
     * Gets country initial summary from wikipedia api.
     *
     * @param  string $titles
     * @param  string $action
     * @param  string $format
     * @param  bool $async
     * @return mixed
     */
    public function getInitialParagraphs(string $titles, string $action = 'query', string $format = 'json', bool $async = true)
    {
        $params = [
            'titles' => $titles,
            'action' => $action,
            'exintro' => 1,
            'explaintext' => 1,
            'prop' => 'extracts',
            'redirects' => 1,
            'format' => $format
        ];

        $apiData =  $this->callApi($this->baseUri(), $params, $async);

        if ($async) {
            return $apiData->then(function ($res) {

                $parsed = $this->get_list_or_item($res['query']['pages'], 'extract');

                return reset($parsed);
            });
        }

        $parsed = $this->get_list_or_item($apiData['query']['pages'], 'extract');

        return reset($parsed);
    }


    /**
     * Creates asnyc or regular api request and parse response
     *
     * @param  string $url
     * @param  array $params
     * @param  mixed $async
     * @return mixed
     */
    public function callApi(string $url, array $params,bool $async = false)
    {
        try {
            $headers = [
                'Accept' => 'application/json',
            ];

            $key = $this->getComputerId() . '|' . $url;

            if ($this->limiter->tooManyAttempts($key, 80)) {
                throw new \Exception('Too many requests', 429);
            }

            if ($async) {
                $response = $this->client->requestAsync('GET', $url, ['headers' => $headers, 'query' =>  $params])->then(function ($res) {
                    return json_decode($res->getBody(), true);
                });
            } else {
                $response = json_decode($this->client->request('GET', $url, ['headers' => $headers, 'query' => $params])->getBody(), true);
            }

            $this->limiter->hit($key, 30);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     *  Get list or item
     * @param  array $data
     * @param  string $field
     * @param  string $default
     * @return mixed
     */
    private function get_list_or_item($data, $field, $default = '')
    {
        $result = [];
        foreach ($data as $page_id => $item) {
            $result[$page_id] = isset($item[$field]) ? $item[$field] : $default;
        }
        return $result;
    }
}
