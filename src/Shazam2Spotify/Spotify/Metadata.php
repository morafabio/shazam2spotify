<?php

namespace Shazam2Spotify\Spotify;

use Guzzle\Http\Client;
use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\DefaultCacheStorage;
use Guzzle\Http\Exception\ClientErrorResponseException;

class Metadata
{
    const API_ROOT = 'http://ws.spotify.com';
    protected $client;

    public function __construct(Client $client, $cachePath=null)
    {
        if(!$cachePath) $cachePath = sys_get_temp_dir() . '/SpotifyMetadataAPI-Guzzle/';
        $cachePlugin = new CachePlugin(array(
            'storage' => new DefaultCacheStorage(
                new DoctrineCacheAdapter(new FilesystemCache($cachePath))
            )
        ));
        $client->addSubscriber($cachePlugin);

        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function request($service, $method, $parameters)
    {
        $request = $this->client->get("/$service/1/$method.json");
        foreach($parameters as $name => $value)
        {
            $request->getQuery()->set($name, $value);
        }
        try {
            $response = $request->send();
        }
        catch (ClientErrorResponseException $e)
        {
            throw new \Exception('Spotify Metadata API Client Error: ' . "\n" . $e->getMessage());
        }
        return $response->json();
    }

    public function searchTrack($track, $artist)
    {
        $track = str_replace('"', '', $track);
        $artist = str_replace('"', '', $artist);
        $q = sprintf('track:"%s" artist:"%s"', $track, $artist);
        return $this->request('search', 'track', array('q' => $q));
    }
}
