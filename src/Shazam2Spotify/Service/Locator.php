<?php

namespace Shazam2Spotify\Service;

use Shazam2Spotify\Common\Playlist;
use Shazam2Spotify\Spotify\Metadata;
use Guzzle\Http\Client;

class Locator
{
    protected $spotify;
    protected $playlist;

    public function __construct(Metadata $spotify)
    {
        $this->spotify = $spotify;
    }

    public function setPlaylist(Playlist $songManager)
    {
        $this->playlist = $songManager;
    }

    public function lookup()
    {
        foreach($this->playlist as $song)
        {
            $json = $this->spotify->searchTrack($song->getTrack(), $song->getArtist());
            if(!$json['info']['num_results']) continue;
            $song->setUri($json['tracks'][0]['href']);
        }
        return $this->playlist;
    }
}
