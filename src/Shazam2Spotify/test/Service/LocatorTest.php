<?php

namespace Shazam2Spotify\test\Service;

use Shazam2Spotify\Service\Locator;
use Shazam2Spotify\Common\Playlist;
use Shazam2Spotify\Spotify\Metadata;
use Shazam2Spotify\test\Spotify\MetadataTest;
use Guzzle\Http\Client;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $locator;

    public function testLocateSongs()
    {
        $track = '15 anni';
        $artist = 'Rumatera';

        $playlist = new Playlist();
        $playlist->add($track, $artist);

        $metadataTest = new MetadataTest();
        $mockClient = $metadataTest->getMockClient(
            'search', 'track',
            array('q' => "track:\"$track\" artist:\"$artist\""),
            unserialize(file_get_contents(__DIR__. '/../Spotify/fixtures/searchTrack.json'))
        );
        $metadata = new Metadata($mockClient);

        $locator = new Locator($metadata);
        $locator->setPlaylist($playlist);
        $playlist = $locator->lookup();

        $this->assertEquals('spotify:track:2WvCf2QjaGIbBDv9ajNA3F', $playlist[0]->getUri());
    }

}
