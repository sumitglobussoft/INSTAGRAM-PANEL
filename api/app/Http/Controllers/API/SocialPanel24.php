<?php
/**
 * Created by PhpStorm.
 * User: GLB-249
 * Date: 2/15/2016
 * Time: 6:56 PM
 */

namespace App\Http\Controllers\API;

use Exception;

class SocialPanel24
{
    //    public $api_url = 'http://socialpanel24.com/api.php'; // API URL //Earlier API version of socialPanel
    public $api_url = 'http://socialpanel24.com/api/v2'; // API URL  //changed API URL.
    public $api_key = 'f105da781c0526858828aa2ec0b8d5f6'; // Your API key

    public function order_add($link, $type, $quantity)
    { // Add order
        $post_data = [];
        $post_data['key'] = $this->api_key;
        $post_data['action'] = 'add';
        $post_data['service'] = $type; // The order type or service type string // 'type' for older API.
        $post_data['link'] = trim(strip_tags($link)); // The order Link Here
        $post_data['quantity'] = $quantity; // The order amount
//        dd($post_data);
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
        /*
        new error response when using 'type' instead of 'service' OR by giving wrong serviceID(type) => {"error":"Incorrect service ID"}
         return json_encode(['error' => 'bad_type'],true); //old error response
         return json_encode(['id' => 12133],true);        //old success response
         return json_encode(['order' => 12133],true);     //new success response
        */

//        return json_encode(['error' => 'bad_type'],true);
//        return json_encode(['id' => 12133],true);
    }

    public function order_status($order_id)
    { // Get status, remains
        $post_data = [];
        $post_data['key'] = $this->api_key;
        $post_data['action'] = 'status';
        $post_data['id'] = (int)trim(strip_tags($order_id)); // The order Link Here

        try {
            $response = $this->http_post($this->api_url, $post_data);
            return $response;
        } catch (Exception $e) {
            throw new Exception("Error in curl execution.");
        }

        /*
         *New Response for check order_status=>  {"charge":"0.15","start_count":"1205","status":"In progress","remains":"100"}
         * */
    }

    private function http_post($url, $post_paramas)
    {
//        ini_set('max_execution_time', 1000);
        if (empty($url) || empty($post_paramas)) {
            return 'Parameter not Passed';
        }
        $fields_string = '';
        foreach ($post_paramas as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        $fields_string = rtrim($fields_string, '&');
        $ch = '';
        try {
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POST, count($post_paramas));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600); # timeout after 10 seconds, you can increase it

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
            // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);

            if (($result === FALSE)) {     //}) || (curl_errno($ch) != 0 && empty($result))) {
                throw new \Exception();
            }

            curl_close($ch);
            return $result;

        } catch (Exception $e) {
            curl_close($ch);
            throw new Exception("Error in Curl Execution !");
        }

    }
}