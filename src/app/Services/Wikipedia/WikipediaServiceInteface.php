<?php 

namespace App\Services\Wikipedia;

interface WikipediaServiceInteface {
    public function getInitialParagraphs($titles, $action = 'query', $format = 'json', $async = true);
    public function callApi($url, $params, $async = false);
}
