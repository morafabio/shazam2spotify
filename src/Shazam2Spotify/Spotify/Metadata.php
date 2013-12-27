<?php

namespace Shazam2Spotify\Spotify;

use Guzzle\Http\Client;

class Metadata
{

    const API_ROOT = 'http://ws.spotify.com';
    protected $client;

    public function __construct(Client $client)
    {
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
        return $request->send()->json();
    }

    public function searchTrack($track, $artist)
    {
        $track = str_replace('"', '', $track);
        $artist = str_replace('"', '', $artist);
        $q = sprintf('track:"%s" artist:"%s"', $track, $artist);
        return $this->request('search', 'track', array('q' => $q));
    }
}
