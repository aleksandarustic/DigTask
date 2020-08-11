<?php

namespace App\Utill\Api;

use App\Services\Wikipedia\WikipediaServiceInteface;
use App\Services\Youtube\YoutubeServiceInteface;
use App\Utill\CountryUtillInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Excapsulate external api functionalities for country model
 */
class CountryApiUtill extends ApiUtill implements CountryUtillInterface
{
    /**
     * Instaciate class and inject services 
     *
     * @param  YoutubeServiceInteface $youtubeService
     * @param  WikipediaServiceInteface $wikipediaService
     * @return void
     */
    public function __construct(YoutubeServiceInteface $youtubeService, WikipediaServiceInteface $wikipediaService)
    {
        $this->yt_service = $youtubeService;
        $this->wk_service = $wikipediaService;
    }

    /**
     * Call youtube api and return response
     *
     * @param  Model $model
     * @return mixed
     */
    public function getYouTubeData(Model $model)
    {
        return $this->yt_service->getPopularVideos($model->region_code, 10, ['snippet'], true);
    }

    public function getWikiData(Model $model)
    {
        return $this->wk_service->getInitialParagraphs($model->wikipedia_title, 'query', 'json', true);
    }


    /**
     * Get required data from api or cache and populate model with that data
     *
     * @param  Model $model
     * @return Model
     */
    public function getDataFromApiOrCache(Model $model): Model
    {
        if (isset($model->region_code)) {
            $model->videos = $this->getFromExternalApiOrCache('videos', $model->id, function () use ($model) {
                return $this->getYouTubeData($model);
            });
        }
        if (isset($model->wikipedia_title)) {
            $model->initial_paragraphs = $this->getFromExternalApiOrCache('initial_paragraphs', $model->id, function () use ($model) {
                return $this->getWikiData($model);
            });
        }
        return $model;
    }


    /**
     * Gets external data from api or cache and return populated model with external data for single Model
     *
     * @param  mixed $model
     * @return Model
     */
    public function processSingle(Model $model): Model
    {

        $model = $this->getDataFromApiOrCache($model);

        if (count($this->promises)) {
            foreach ($this->promises as $key => $promise) {
                $keys = explode('-', $key);
                $model[$keys[0]] =  $this->storeApiData($key, $promise);
            }
        }


        return $model;
    }


    /**
     * Gets external data from api or cache and return populated model with external data for collection of models
     *
     * @param  mixed $models
     * @return mixed
     */
    public function process($models)
    {

        $models->each(function ($model) {
            $model = $this->getDataFromApiOrCache($model);
        });

        if (count($this->promises)) {
            foreach ($this->promises as $key => $promise) {
                $keys = explode('-', $key);
                $models->firstWhere('id', $keys[1])[$keys[0]] =  $this->storeApiData($key, $promise);
            }
        }

        return $models;
    }
}
