<?php

namespace App\Utill;

use Illuminate\Database\Eloquent\Model;

/**      
 * Interface for apiUtill Service     
 */
interface ApiUtillInterface
{

    public function getFromExternalApiOrCache(String $key, Int $id, $apiCall);

    public function storeApiData(String $key, $data);

    public function process($models);

    public function processSingle(Model $model): Model;
}
