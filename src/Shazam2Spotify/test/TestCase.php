<?php

namespace Shazam2Spotify\test;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getMockGuzzleClient($service, $method, $parameters, $responseJson = null)
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