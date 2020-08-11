<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transform youtube video data
 */
class Video extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->resource['snippet']['title'],
            'description' => $this->resource['snippet']['description'],
            'thumbnails' => collect($this->resource['snippet']['thumbnails'])->filter(function ($value, $key) {
                return $key == 'default' || $key == 'high';
            }),
        ];
    }
}
