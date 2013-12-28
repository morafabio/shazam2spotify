<?php

namespace Shazam2Spotify\test\Shazam;

use Shazam2Spotify\Shazam\History;
use Shazam2Spotify\Common\Playlist;
use Symfony\Component\DomCrawler\Crawler;

class HistoryTest extends \PHPUnit_Framework_TestCase
{
    protected $history;

    public function setUp()
    {
        $html = file_get_contents(__DIR__ . '/fixtures/myshazam-history.html');
        $crawler = new Crawler();
        $playlist = new Playlist();

        $this->history = new History($html, $crawler, $playlist);
    }

    public function testGetHtml()
    {
        $this->assertTrue(strlen($this->history->getHtml()) > 100);
    }

    public function testReadTable()
    {
        $playlist = $this->history->filter();
        $this->assertEquals(162, $playlist->count());
    }
}
