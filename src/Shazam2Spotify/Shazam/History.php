<?php

namespace Shazam2Spotify\Shazam;

use Shazam2Spotify\Common\Playlist;
use Symfony\Component\DomCrawler\Crawler;

class History
{
    protected $html, $crawler, $playlist;

    public function __construct($html, Crawler $crawler, Playlist $songManager)
    {
        $this->html = $html;

        $this->crawler = $crawler;
        $this->crawler->add($this->getHtml());

        $this->playlist = $songManager;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function filter() 
    {
        $this->crawler->filter('body > table tr')
            ->reduce(function($node, $i) {
                if($i == 0) return;

                $track = $node->filter('td:nth-child(1)')->text();
                $artist = $node->filter('td:nth-child(2)')->text();
                $this->playlist->add($track, $artist);
            });

        return $this->playlist;
    }

}
