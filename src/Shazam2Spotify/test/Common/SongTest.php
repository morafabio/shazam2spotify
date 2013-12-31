<?php

namespace Shazam2Spotify\test\Common;

use Shazam2Spotify\test\TestCase;
use Shazam2Spotify\Common\Song;

class SongTest extends TestCase
{
    protected $song;

    public function setUp()
    {
        $this->song = new Song();
    }

    public function testArtist()
    {
        $this->assertNull($this->song->getArtist());
        $this->song->setArtist('Pay');
        $this->assertEquals('Pay', $this->song->getArtist());
    }

    public function testTrack()
    {
        $this->assertNull($this->song->getTrack());
        $this->song->setTrack('Zombies');
        $this->assertEquals('Zombies', $this->song->getTrack());
    }

    public function testUri()
    {
        $this->assertNull($this->song->getUri());
        $this->song->setUri('spotify:track:123');
        $this->assertEquals('spotify:track:123', $this->song->getUri());
    }

    public function testToArray()
    {
        $this->song->setTrack('a');
        $this->song->setArtist('b');
        $this->song->setUri('c');
        $this->assertEquals(array('track' => 'a', 'artist' => 'b', 'uri' => 'c'), $this->song->toArray());
    }

}
