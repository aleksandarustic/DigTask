<?php

namespace App\Services\Wikipedia;

interface WikipediaServiceInteface
{
    public function getInitialParagraphs(string $titles, string $action = 'query', string $format = 'json', bool $async = true);
    public function callApi(string $url, array $params, bool $async = false);
}
