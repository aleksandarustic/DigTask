<?php

use App\Services\Wikipedia\WikipediaServiceInteface;

class CountryTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
        $_SERVER['REMOTE_ADDR'] = 'http://localhost';

        parent::setUp();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllCountriesPaginated()
    {
        $this->get('api/countries?limit=1&page=2', ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            [
                'data' => [
                    '*' => [
                        'id', 'type', 'attributes' => [
                            'name',
                            'region_code',
                            'wikipedia_title',
                            'initial_paragraphs',
                            'videos' => ['*' => ['title', 'description', 'thumbnails' => ['default']]]
                        ]
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page'
                ]
            ]
        );
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllCountries()
    {
        $this->get('api/countries', ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' => [
                '*' => [
                    'id', 'type', 'attributes' => [
                        'name',
                        'region_code',
                        'wikipedia_title',
                        'initial_paragraphs',
                        'videos' => ['*' => ['title', 'description', 'thumbnails' => ['default']]]
                    ]
                ]
            ]]
        );
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSingleCountry()
    {
        $this->get('api/countries/1', ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' => [
                'id',
                'type',
                'attributes' => [
                    'name',
                    'region_code',
                    'wikipedia_title',
                    'initial_paragraphs',
                    'videos' => ['*' => ['title', 'description', 'thumbnails' => ['default']]]
                ]
            ]]
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSingleCountryByRegionCode()
    {
        $this->get('api/countries/code/gb', ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $this->seeJsonStructure(
            ['data' => [
                'id',
                'type',
                'attributes' => [
                    'name',
                    'region_code',
                    'wikipedia_title',
                    'initial_paragraphs',
                    'videos' => ['*' => ['title', 'description', 'thumbnails' => ['default']]]
                ]
            ]]
        );
    }
}
