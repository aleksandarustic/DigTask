<?php

use App\Services\Wikipedia\WikipediaServiceInteface;

class WikipediaServiceTest extends TestCase
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

        $this->wk_service = $this->app->make(WikipediaServiceInteface::class);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAsyncCall()
    {

        $response = $this->wk_service->getInitialParagraphs('france', 'query', 'json', true);

        $this->assertNotEmpty($response->wait());
    }



    public function testNormalCall()
    {

        $response = $this->wk_service->getInitialParagraphs('france', 'query', 'json', false);

        $this->assertNotEmpty($response);
    }
}
