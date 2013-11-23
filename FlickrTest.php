<?php

require_once('Flickr.php');

class FlickrTest extends PHPUnit_Framework_TestCase
{
    //    const API_KEY = 'YOUR_API_KEY';

    private $flickr;

    public function setUp()
    {
        $this->flickr = new Flickr(self::API_KEY);
    }

    public function testMe()
    {
        $urls = $this->flickr->getPhotos("Justin");
        $this->assertTrue(!empty($urls));
    }
}