<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

use Shazam2Spotify\Spotify\Metadata;
use Shazam2Spotify\Service\Locator;
use Shazam2Spotify\Common\Playlist;
use Shazam2Spotify\Shazam\History;
use Symfony\Component\DomCrawler\Crawler;

$app = new Silex\Application();
$container = new Pimple();

$app['metadata'] = function () {
    return new Metadata(new Guzzle\Http\Client(Metadata::API_ROOT));
};

$app->post('/locator/', function () use ($app) {

    if(!isset($_FILES["uploadedFile"])) throw new Exception('Can\'t read the uploaded file.', 417);
    $html = file_get_contents($_FILES["uploadedFile"]["tmp_name"]);

    $history = new History($html, new Crawler(), new Playlist());
    $playlist = $history->filter();

    if($playlist->count() < 1) throw new Exception('No songs found or invalid file.', 415);
    //if($playlist->count() > 10) throw new Exception('Too many songs (maximum is 10).', 416);

    $locator = new Locator($app['metadata']);
    $locator->setPlaylist($playlist);
    $playlist = $locator->lookup();

    return new Response($playlist->json(), 200, ['Content-Type' => 'application/json']);
});

$app->error(function (\Exception $e, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = $e->getMessage();
    }
    $response = array('error' => $message);
    return new Response(json_encode($response), $code, ['Content-Type' => 'application/json']);
});

$app->post('/locator/test.successful', function () use ($app) {
    $playlist = unserialize(file_get_contents(__DIR__ . '/../../src/Shazam2Spotify/test/functional/fixtures/locator-success.serialized'));
    return new Response($playlist->json(), 200, ['Content-Type' => 'application/json']);
});

$app->run();