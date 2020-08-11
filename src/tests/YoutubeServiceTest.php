<?php

use App\Services\Youtube\YoutubeServiceInteface;

class YoutubeServiceTest extends TestCase
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

        $this->yt_service = $this->app->make(YoutubeServiceInteface::class);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAsyncCall()
    {

        $response = $this->yt_service->getPopularVideos('gb', 10, ['snippet'], true);

        $this->seeJsonStructure([
            'kind',
            'etag',
            'items' => [
                '*' => [
                    'etag',
                    'kind',
                    'id',
                    'snippet' =>  [
                        'title',
                        'description',
                        'thumbnails' => ['*' => ['url', 'width', 'height']]
                    ]
                ]
            ]
        ], $response->wait());
    }



    public function testNormalCall()
    {

        $response = $this->yt_service->getPopularVideos('gb', 10, ['snippet'], false);

        $this->seeJsonStructure([
            'kind',
            'etag',
            'items' => [
                '*' => [
                    'etag',
                    'kind',
                    'id',
                    'snippet' =>  [
                        'title',
                        'description',
                        'thumbnails' => ['*' => ['url', 'width', 'height']]
                    ]
                ]
            ]
        ], $response);
    }
}
