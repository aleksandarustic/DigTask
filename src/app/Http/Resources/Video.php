<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'description' =>$this->resource['snippet']['description'],
            'thumbnails' => $this->resource['snippet']['thumbnails'],
        ];
    }
}
