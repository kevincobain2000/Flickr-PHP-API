<?php

require_once('Flickr.php');

class FlickrTest extends PHPUnit_Framework_TestCase
{
    const API_KEY = 'YOUR_API_KEY';
    private $module;

    public function setUp()
    {
        $this->module = new Flickr(self::API_KEY);
    }

    public function testMe()
    {
        // var_dump(get_class($this->module));
        // $this->assertTrue(true);
    }
}