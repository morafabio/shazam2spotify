<?php

namespace Shazam2Spotify\test\Spotify;

use Shazam2Spotify\test\TestCase;
use Shazam2Spotify\Spotify\Metadata;
use Guzzle\Http\Client;

class MetadataTest extends TestCase
{
    protected $metadata;

    public function setUp()
    {
        $client = new Client(Metadata::API_ROOT);
        $this->metadata = new Metadata($client);
    }

    public function testApiRoot()
    {
        $this->assertEquals('http://ws.spotify.com', Metadata::API_ROOT);
        $this->assertEquals('http://ws.spotify.com', $this->metadata->getClient()->getBaseUrl());
    }

    public function testSearchTrack()
    {
        $track = '15 Anni';
        $artist = 'Rumatera';
        $data = unserialize(file_get_contents(__DIR__. '/fixtures/searchTrack.json'));
        $mockClient = $this->getMockGuzzleClient('search', 'track', array('q' => "track:\"$track\" artist:\"$artist\""), $data);
        $metadata = new Metadata($mockClient);

        $this->assertEquals($data, $metadata->searchTrack($track, $artist));
    }

    public function testRequest()
    {
       $service = 'myservice';
       $method = 'mymethod';
       $parameters = array('x', 'val1', 'y', 'val2');

       $mockClient = $this->getMockGuzzleClient($service, $method, $parameters);
       $metadata = new Metadata($mockClient);

       $metadata->request($service, $method, $parameters);
    }

    public function testExceptionRequest()
    {
        $this->markTestSkipped('Live API access (test slow).');

        $this->setExpectedException('Exception');
        $service = 'search';
        $method = 'track';
        $parameters = array('x', 'val1');

        $this->metadata->request($service, $method, $parameters);
    }

}
