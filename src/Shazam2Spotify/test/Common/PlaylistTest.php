<?php

namespace Shazam2Spotify\test\Common;

use Shazam2Spotify\Common\Playlist;
use Shazam2Spotify\Common\Song;

class PlaylistTest extends \PHPUnit_Framework_TestCase
{
    protected $playlist;

    public function setUp()
    {
        $this->playlist = new Playlist();
    }

    public function testAddSongFromTrackArtistUri()
    {
        $track = '15 anni';
        $artist = 'Rumatera';
        $uri = 'spotify:track:2WvCf2QjaGIbBDv9ajNA3F';

        $song = new Song();
        $song->setTrack($track);
        $song->setArtist($artist);
        $this->playlist->add($track, $artist);
        $this->playlist->add($track, $artist, $uri);

        $this->assertEquals(2, $this->playlist->count());
        $this->assertEquals($track, $this->playlist[0]->getTrack());
        $this->assertEquals($artist, $this->playlist[0]->getArtist());
        $this->assertEquals($uri, $this->playlist[1]->getUri());
    }

    public function testJson()
    {
        $this->playlist->add('Hey Brother', 'Avicii', 'spotify:track:3zKST4nk4QJE77oLjUZ0Ng');
        $this->playlist->add('Wake Me Up', 'Aloe Blacc', 'spotify:track:6SEBRJ6qYR3mnKJivWH1mW');
        $this->playlist->add('Stay The Night (feat Haley Williams of Paramore)', 'Zedd');
        $this->playlist->add('Gli Anni D\'Oro', 'Jake La Furia', 'spotify:track:24MbH5gEx6zbLSILbWTtWk');

        $json = $this->playlist->json();

        $this->assertEquals(array('[', ']'), array($json[0], $json[strlen($json) - 1]));
        $json = json_decode($json);
        $this->assertEquals(4, count($json));
        $this->assertEquals(array('track', 'artist', 'uri'), array_keys((array) $json[0]));

    }
}
