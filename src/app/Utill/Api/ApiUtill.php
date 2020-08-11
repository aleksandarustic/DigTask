<?php

namespace App\Utill\Api;

use Illuminate\Support\Facades\Cache;

use App\Utill\ApiUtillInterface;

/**
 * Base ApiUtill functionality
 */
abstract class ApiUtill implements ApiUtillInterface
{
    /**
     * list of async promises where response is not returned
     *
     * @var array
     */
    protected $promises = [];

    /**
     * Return data from cache or from api request
     *
     * @param  string $key
     * @param  int $id
     * @param  mixed $apiCall
     * @return mixed
     */
    public function getFromExternalApiOrCache(String $key, Int $id, $apiCall)
    {
        if (!Cache::has($key . '-' . $id)) {

            $result = $apiCall();

            if (method_exists($result, 'wait')) {
                $this->promises[$key . '-' . $id] = $result;
            } else {
                Cache::put($key . '-' . $id, $result);
            }
        }

        return Cache::get($key . '-' . $id);
    }

    /**
     * Return data from cache if exist or return data from api and store it to cache
     *
     * @param  mixed $key
     * @param  mixed $data
     * @return void
     */
    public function storeApiData(String $key, $data)
    {
        return Cache::rememberForever($key, function () use ($data) {
            return method_exists($data, 'wait') ? $data->wait() : $data;
        });
    }
}
