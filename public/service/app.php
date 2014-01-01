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

    // TODO: put this responsibility somewhere else (such as a decorator?)
    if($playlist->count() < 1) throw new Exception('No songs found or invalid file.', 415);
    $maxResults = 100;
    if($playlist->count() > $maxResults) {
        $playlist->slice(0, $maxResults);
    }

    // TODO: extract to DIC
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
        case 500:
            $message = 'Internal Server Error. Please contact the system administrator.';
            break;
        default:
            $message = $e->getMessage();
    }
    $response = array('error' => $message);
    return new Response(json_encode($response), $code, ['Content-Type' => 'application/json']);
});

$app->run();
