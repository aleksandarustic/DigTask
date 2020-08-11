<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


/**
 * BaseRepository: This is base repository for easier manipulatio with eloquent
 */
class BaseRepository implements EloquentRepositoryInterface
{
    /**      
     * @var Model      
     */
    protected $model;

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id, array $filter): ?Model
    {
        $query = new $this->model;

        $query = $query->when(Arr::exists($filter, 'include'), function ($q) use ($filter) {
            return $q->with(array_map(['Str', 'camel'], $filter['include']));
        });

        $query = $query->when(Arr::exists($filter, 'fields'), function ($q) use ($filter) {
            return $q->select($filter['fields']);
        });

        return $query->findOrFail($id);
    }


    /**
     * Transform data in preparation for validation.
     *
     * @return void
     */
    public function prepareFilters($filters)
    {
        $sanitize = [
            'include' => array_filter(explode(',',$filters['include'] ?? ''), 'strlen'),
            'fields' => array_filter(explode(',', $filters['fields'] ?? ''), 'strlen'),
        ];

        return array_merge($filters,array_intersect_key($sanitize, $filters));
    }

    /**
     * @return Collection
     */
    public function all(array $filter)
    {

        $query = new $this->model;

        $filter = $this->prepareFilters($filter);

        $query = $query->when(Arr::has($filter, 'fields'), function ($q) use ($filter) {
            return $q->select($filter['fields']);
        });

        $query = $query->when(Arr::has($filter, 'include'), function ($q) use ($filter) {
            return $q->with(array_map('camel_case', $filter['include']));
        });

        return Arr::has($filter, 'limit') ? $query->paginate($filter['limit']) : $query->get();
    }
}
