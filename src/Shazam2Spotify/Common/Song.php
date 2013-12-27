<?php

namespace Shazam2Spotify\Common;

class Song
{
    protected $artist, $track, $uri;

    public function toArray()
    {
        return array(
            'track' => $this->getTrack(),
            'artist' => $this->getArtist(),
            'uri' => $this->getUri()
        );
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function setTrack($track)
    {
        $this->track = $track;
    }
    
    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}
