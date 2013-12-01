<?php
/**
 * Flickr_Api PHP API class
 * API Documentation: http://www.flickr.com/services/api/
 * Documentation and usage in README file
 *
 * @author Jonas De Smet - Glamorous
 * @date 02.05.2010
 * @copyright Jonas De Smet - Glamorous
 * @version 0.6.1
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */

class Flickr_Api
{
    const JSON = 'json';
    const XML = 'rest';
    const PHP = 'php_serial';
    const SOAP = 'soap';

    const API_URL = 'http://api.flickr.com/services/rest/';

    const VERSION = '0.6.1';

    /**
     * The available return formats
     *
     * @var array
     */
    private static $_formats = array(Flickr_Api::JSON, Flickr_Api::XML, Flickr_Api::PHP, Flickr_Api::SOAP);

    /**
     * The default parameters-array to include with the API-call
     *
     * @var array
     */
    private static $_defaults = array();

    /**
     * The default format to include with the API-call
     *
     * @var const
     */
    private static $_default_format = Flickr_Api::JSON;


    /**
     * Default constructor
     *
     * @return void
     */
    final private function __construct()
    {
        // This is a static class
    }


    /**
     * Set API-key for all requests
     *
     * @param string $apikey
     * @return void
     */
    public static function setApikey($apikey)
    {
        self::$_defaults['api_key'] = (string) $apikey;
    }

    /**
     * Set default format for all requests
     *
     * @param const Flickr_Api::JSON, Flickr_Api::XML, Flickr_Api::PHP, Flickr_Api::SOAP $format
     * @return void
     */
    public static function setFormat($format)
    {
        if (in_array($format, self::$_formats)) {
            self::$_defaults['format'] = $format;
            self::$_default_format == $format;
        }
        else {
            self::$_defaults['format'] = self::$_default_format;
        }
    }


    /**
     * Makes the call to the API
     *
     * @param array $params	parameters for the request
     * @return mixed
     */
    public static function makeCall($params)
    {
        $params += self::$_defaults;

        if(!isset($params['api_key'])) {
            throw new Exception('API-key must be set');
        }

        if(!isset($params['method'])) {
            throw new Exception("Without a method this class can't call the API");
        }

        if(!isset($params['format'])) {
            $params['format'] = self::$_default_format;
        }

        if($params['format'] == self::JSON) {
            $params['nojsoncallback'] = 1;
        }

        $url = Flickr_Api::API_URL.'?'.http_build_query($params, NULL, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);

        $results = curl_exec($ch);
        $headers = curl_getinfo($ch);

        $error_number = (int) curl_errno($ch);
        $error_message = curl_error($ch);

        curl_close($ch);

        if(!in_array($headers['http_code'], array(0, 200))) {
            throw new Exception('Bad headercode', (int) $headers['http_code']);
        }

        if($error_number > 0){
            throw new Exception($error_message, $error_number);
        }

        return $results;
    }
}