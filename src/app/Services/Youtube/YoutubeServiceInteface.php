<?php 

namespace App\Services\Youtube;

interface YoutubeServiceInteface {
    public function getPopularVideos($regionCode, $maxResults = 10, $part = ['id', 'snippet', 'contentDetails', 'player', 'statistics', 'status'], $async = true);
    public function callApi($url, $params, $async = false);
}
