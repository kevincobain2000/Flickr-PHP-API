<?php
require_once('FlickrApi.php');
/**
 * Wrapping API Methods
 * @author https://github.com/kevincobain2000
 */
class Flickr
{
    private $text;
    private $urls;

    public function __construct($api_key)
    {
        Flickr_Api::setFormat(Flickr_Api::PHP);
        Flickr_Api::setApikey($api_key);
    }

    /**
     * Get Photo ids from this class method
     * Loops over ids & calls method to set array of urls
     *
     * @param $text string text to search
     * @param $size string e.g. Large, Small, Medium, Original
     */
    public function getPhotos($text, $size = 'Large')
    {
        $this->setText($text);

        $photo_ids = $this->getPhotosIdsFromText();
        foreach ($photo_ids as $key => $value) {
            $this->farmUrlsFromPhotoId($value, $size);
        }
        return $this->urls;
    }

    private function setText($value)
    {
        $this->text = $value;
    }

    /**
     * @param  $text string member e.g. Justin Timberlake
     * @return $ret  array  photo ids from Flickr
     */
    private function getPhotosIdsFromText()
    {
        $ret = array();
        $params = array(
                        'method' => 'flickr.photos.search',
                        'text' => $this->text
                        );

        $call = Flickr_Api::makeCall($params);
        $photos = unserialize($call);

        if ($photos['stat'] && isset($photos['photos'])) {
            foreach ($photos['photos'] as $key => $value) {
                if ($key === 'photo') {
                    foreach ($value as $k => $v) {
                        $ret[] = $v['id'];
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Sets the instance array $this->urls with http://farm.url.jpg
     *
     * @param $photo_id string Flickr Photo id e.g. 11111233
     * @param $size     string Flickr Photo Size @see getPhotos
     */
    private function farmUrlsFromPhotoId($photo_id, $size)
    {
        $params = array(
                        'method'   => 'flickr.photos.getSizes',
                        'photo_id' => $photo_id
                        );
        $call  = Flickr_Api::makeCall($params);
        $sizes = unserialize($call);

        if ($sizes['stat'] && isset($sizes['sizes'])) {
            foreach ($sizes['sizes'] as $key => $value) {
                if ($key === 'size') {
                    foreach ($value as $k => $v) {
                        if ($v['label'] === $size) {
                            $this->urls[] = $v['source'];
                        }
                    }
                }
            }
        }
    }

}