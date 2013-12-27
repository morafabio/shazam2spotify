<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

use Shazam2Spotify\Spotify\Metadata;
use Shazam2Spotify\Service\Locator;
use Shazam2Spotify\Common\Playlist;

$app = new Silex\Application();
$container = new Pimple();

$app['metadata'] = function () {
    return new Metadata(new Guzzle\Http\Client(Metadata::API_ROOT));
};

$app->get('/locator/', function () use ($app) {
    $playlist = new Playlist();
    $playlist->add('Hey Brother', 'Avicii');
    //$playlist->add('Wake Me Up', 'Aloe Blacc');
    //$playlist->add('Stay The Night (feat Haley Williams of Paramore)', 'Zedd');
    //$playlist->add('Gli Anni D\'Oro', 'Jake La Furia');

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

        return new Response($message);
    });

$app->run();