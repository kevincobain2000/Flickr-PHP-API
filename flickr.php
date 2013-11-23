<?php
require_once('FlickrApi.php');
/**
 * Wrapping API Methods
 * @author https://github.com/kevincobain2000
 */
class Flickr
{
    private $flickrApi;

    public function __construct($api_key)
    {
        $this->flickrApi = Flickr_Api::setApikey($api_key);
    }
}