<?php

namespace App\Utill\Api;

use Illuminate\Support\Facades\Cache;

use App\Utill\ApiUtillInterface;

abstract class ApiUtill implements ApiUtillInterface
{
    protected $promises = [];

    public function getFromExternalApiOrCache(String $key,Int $id, $apiCall)
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

    public function storeApiData(String $key, $data)
    {
        return Cache::rememberForever($key, function () use ($data) {
            return method_exists($data, 'wait') ? $data->wait() : $data;
        });
    }
}
