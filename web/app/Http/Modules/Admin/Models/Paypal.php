<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Paypal
{
    /**
     * Last error message(s)
     * @var array
     */
    protected $_errors = array();

    /**
     * API Credentials
     * Use the correct credentials for the environment in use (Live / Sandbox)
     * @var array
     */
    protected $_credentials = array(

        'USER' => 'saurabh.kumar_api1.globussoft.com',
        'PWD' => 'X6ZH5X2G2U3NNLD8',
        'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AOpS3AsKcc857KLz99TD-QdguXVE',
    );

    /**
     * API endpoint
     * Live - https://api-3t.paypal.com/nvp
     * Sandbox - https://api-3t.sandbox.paypal.com/nvp
     * @var string
     */
    protected $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * API Version
     * @var string
     */
    protected $_version = '74.0';

    /**
     * Make API request
     *
     * @param string $method string API method to request
     * @param array $params Additional request parameters
     * @return array / boolean Response array / boolean false on failure
     */
    public static $_instance = null;
    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Comment();
        return self::$_instance;
    }


    public function request($method, $params = array())
    {
        $this->_errors = array();
        if (empty($method)) { //Check if API method is not empty
            $this->_errors = array('API method is missing');
            return false;
        }

//Our request parameters
        $requestParams = array(
                'METHOD' => $method,
                'VERSION' => $this->_version
            ) + $this->_credentials;

//Building our NVP string
        $request = http_build_query($requestParams + $params);

//cURL settings
        $curlOptions = array(
            CURLOPT_URL => $this->_endPoint,
            CURLOPT_VERBOSE => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        );
        print_r($curlOptions);die;

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);

//Sending our request - $response will hold the API response
        $response = curl_exec($ch);
        print_r($response);die;

//Checking for cURL errors
        if (!curl_errno($ch)) {
            $this->_errors = curl_error($ch);
            curl_close($ch);
            return 0;
            return false;

//Handle errors
        } else {
            curl_close($ch);
            $responseArray = array();
            parse_str($response, $responseArray); // Break the NVP string to an array
//            return 1;
            return $responseArray;
        }
    }
}