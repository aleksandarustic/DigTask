<?php

namespace App\Services\Wikipedia;

use InvalidArgumentException;

class WikipediaService implements WikipediaServiceInteface
{
    protected $client;
    protected $lang;
    protected $limiter;

    /**
     * Language Supported
     * @var array
     */
    private $language_supported = ['de', 'en', 'es', 'fr', 'it', 'nl', 'pl', 'ru', 'ceb', 'sv', 'vi', 'war'];

    public function __construct(string $lang = 'en', $client, $rate_limiter)
    {
        $this->setLang($lang);

        $this->client = $client;
        $this->limiter = $rate_limiter;
    }

    protected function baseUri()
    {
        return "https://{$this->lang}.wikipedia.org/w/api.php";
    }

    /**
     * @param $key
     * @return Youtube
     */
    public function setLang($lang)
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
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     *  Get the list of supported languages
     * @return array
     */
    public function getSupportedLanguages()
    {
        return $this->language_supported;
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
    public function getInitialParagraphs($titles, $action = 'query', $format = 'json', $async = true)
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

    /**
     *  Get list of articles or items
     * @param  array $data
     * @param  string $field
     * @param  integer $i
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
