<?php

namespace Shazam2Spotify\test\Spotify;

use Shazam2Spotify\Spotify\Metadata;
use Guzzle\Http\Client;

class MetadataTest extends \PHPUnit_Framework_TestCase
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
        $mockClient = $this->getMockClient('search', 'track', array('q' => "track:\"$track\" artist:\"$artist\""), $data);
        $metadata = new Metadata($mockClient);

        $this->assertEquals($data, $metadata->searchTrack($track, $artist));
    }

    public function testRequest()
    {
       $service = 'myservice';
       $method = 'mymethod';
       $parameters = array('x', 'val1', 'y', 'val2');

       $mockClient = $this->getMockClient($service, $method, $parameters);
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

    public function getMockClient($service, $method, $parameters, $responseJson = null)
    {
       $url = "/$service/1/$method.json";
       $parameterCount = count($parameters);

       $mockQuery = $this->getMockBuilder('Guzzle\Http\QueryString')->disableOriginalConstructor()->getMock();
       $mockQuery->expects($this->exactly($parameterCount))->method('set');
       $i = 0;
       foreach($parameters as $name => $value)
       {
            $mockQuery->expects($this->at($i))->method('set')->with($name, $value);
            $i++;
       }

       $mockResponse = $this->getMockBuilder('Guzzle\Http\Message\Response')->disableOriginalConstructor()->getMock();
       $mockResponse->expects($this->once())->method('json')->will($this->returnValue($responseJson));

       $mockRequest = $this->getMockBuilder('Guzzle\Http\Message\Request')->disableOriginalConstructor()->getMock();
       $mockRequest->expects($this->exactly($parameterCount))->method('getQuery')->will($this->returnValue($mockQuery));
       $mockRequest->expects($this->once())->method('send')->will($this->returnValue($mockResponse));

       $mockClient = $this->getMock('Guzzle\Http\Client', array('get'));
       $mockClient->expects($this->once())->method('get')->with($url)->will($this->returnValue($mockRequest));

       return $mockClient;
    }
}
