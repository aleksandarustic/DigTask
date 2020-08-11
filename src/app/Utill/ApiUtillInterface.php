<?php

namespace App\Utill;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ApiUtillInterface
{

    public function getFromExternalApiOrCache(String $key, Int $id, $apiCall);

    public function storeApiData(String $key, $data);

    public function process(Collection $models): Collection;

    public function processSingle(Model $model): Model;

}
