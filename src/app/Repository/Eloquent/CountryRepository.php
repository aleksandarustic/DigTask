<?php

namespace App\Repository\Eloquent;

use App\Repository\CountryRepositoryInterface;
use App\Utill\Api\CountryApiUtill;
use Illuminate\Support\Arr;

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

    /**
     * Returns single instance of country with populated data from youtube and wikipedia api
     *
     * @param  mixed $id
     * @param  mixed $filters
     * @return void
     */
    public function findCountryByRegionCode(string $region_code, array $filter)
    {

        $query = new $this->model;

        $filter = $this->prepareFilters($filter);

        $query = $query->when(Arr::exists($filter, 'include'), function ($q) use ($filter) {
            return $q->with(array_map(['Str', 'camel'], $filter['include']));
        });

        $query = $query->when(Arr::exists($filter, 'fields'), function ($q) use ($filter) {
            return $q->select($filter['fields']);
        });

        return $this->api_utill->processSingle($query->where('region_code', $region_code)->firstOrFail());
    }
}
