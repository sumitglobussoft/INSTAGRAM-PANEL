<?php
namespace Curl;
use stdClass;
use Input;

class CurlRequestHandler {

    private static $_instance = null;

    //Prevent any oustide instantiation of this class
    private function __construct() {

    }

    private function __clone() {

    }

//Prevent any copy of this object
    public static function getInstance() {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new self();
        return self::$_instance;
    }

    public function serveRequest() {

        if (func_num_args() > 0) {
            $url = func_get_arg(0);

            $data = func_get_arg(1);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
        } else {
            $message = 'Parameter not Passed';
            $code = 500;
            throw new Exception($message, $code);
        }
    }

    public function curlUsingPost($url, $data) {
//        echo '<pre>';print_r($url);die;
        $response = new stdClass();
        if (empty($url) || empty($data)) {
            $response->code = 198;
            $response->message = 'Parameter not Passed';
            return $response;
        }
        $fields_string = '';
        foreach ($data as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //execute post
        $result = curl_exec($ch);
//        echo "<pre>"; print_r($result);die;
        $result = json_decode($result, true);
        curl_close($ch);

        if ($result) {
            @$response->message = $result['message'];
            @$response->code = $result['code'];
            @$response->data = $result['data'];
            return @$response;
        } else {
            $response->code = 196;
            $response->message = 'Some error Occured, Request not complete';
            return $response;
        }
    }

    public function curlUsingGet($url) {
        $response = new stdClass();
       // echo "<pre>";print_r($url);die();
        if (empty($url)) {
            $response->code = 198;
            $response->message = 'Parameter not Passed';
            return $response;
        }

        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //execute post
        $result = curl_exec($ch);
        return $result;
        $result = json_decode($result, true);
        //close connection
        curl_close($ch);
        if ($result) {
            @$response->message = $result['message'];
            @$response->code = $result['code'];
            @$response->data = $result['data'];
            return @$response;
        } else {
            $response->code = 196;
            $response->message = 'Some error Occured, Request not complete';
            return $response;
        }
    }



}

?>