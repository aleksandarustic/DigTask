<?php

namespace App\Services\Youtube;

interface YoutubeServiceInteface
{
    public function getPopularVideos(string $regionCode, int $maxResults = 10, array $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status'], bool $async = true);
    public function callApi(string $url, array $params, $async = false);
}
