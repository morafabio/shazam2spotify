<?php

namespace Shazam2Spotify\Common;

class Playlist implements \Countable, \Iterator, \ArrayAccess
{
    private $position;
    protected $storage = array();

    public function add($track, $artist, $uri = null)
    {
        $song = new Song();
        $song->setTrack($track);
        $song->setArtist($artist);
        if($uri) $song->setUri($uri);
        array_push($this->storage, $song);
    }

    public function slice($offset, $length)
    {
        $this->storage = array_slice($this->storage, $offset,  $length);
    }

    public function json()
    {
        $result = array();
        foreach($this->storage as $song)
        {
            array_push($result, $song->toArray());
        }
        return json_encode($result);
    }

    // Countable
    public function count()
    {
        return count($this->storage);
    }

    // Iterator
    public function __construct() {
        $this->position = 0;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->storage[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->storage[$this->position]);
    }

    // ArrayAccess
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->storage[] = $value;
        } else {
            $this->storage[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->storage[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->storage[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->storage[$offset]) ? $this->storage[$offset] : null;
    }

}
