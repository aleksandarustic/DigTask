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
     * Injects countryApiUtill in repository      
     *      
     * @param Job $model      
     */
    public function __construct(\App\Country $model, CountryApiUtill $api_utill)
    {
        parent::__construct($model);

        $this->api_utill = $api_utill;
    }


    /**
     * Get All available country and populate every resource with data from youtube and wikipedia
     *
     * @param  mixed $filters
     * @return void
     */
    public function getAll(array $filters)
    {
        $models = $this->all($filters);

        return $this->api_utill->process($models);
    }
    
    /**
     * Returns single instance of country with populated data from youtube and wikipedia api
     *
     * @param  mixed $id
     * @param  mixed $filters
     * @return void
     */
    public function findCountry(int $id, array $filters)
    {
        $models = $this->find($id, $filters);

        return $this->api_utill->processSingle($models);
    }
}
