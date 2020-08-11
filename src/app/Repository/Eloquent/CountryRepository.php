<?php

namespace App\Repository\Eloquent;

use App\Repository\CountryRepositoryInterface;
use App\Utill\Api\CountryApiUtill;

/**
 * CountryRepository: Contain main logic and operation for manipulation with Countries
 */
class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{

    /**      
     * BaseRepository constructor.      
     *      
     * @param Job $model      
     */
    public function __construct(\App\Country $model, CountryApiUtill $api_utill)
    {
        parent::__construct($model);

        $this->api_utill = $api_utill;
    }


    public function getAll(array $filters)
    {
        $models = $this->all($filters);

        return $this->api_utill->process($models);
    }

    public function findCountry(int $id, array $filters)
    {
        $models = $this->find($id, $filters);

        return $this->api_utill->processSingle($models);
    }
}
