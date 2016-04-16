<?php
/**
 * Created by PhpStorm.
 * User: GLB-249
 * Date: 2/15/2016
 * Time: 6:56 PM
 */

namespace App\Http\Controllers\API;


class SocialPanel24
{
    public $api_url = 'http://socialpanel24.com/api.php'; // API URL
    public $api_key = 'f105da781c0526858828aa2ec0b8d5f6'; // Your API key

    public function order_add($link, $type, $quantity)
    { // Add order
        $post_data = [];
        $post_data['key'] = $this->api_key;
        $post_data['action'] = 'add';
        $post_data['link'] = trim(strip_tags($link)); // The order Link Here
        $post_data['type'] = $type; // The order type or service type string
        $post_data['quantity'] = $quantity; // The order amount
//        dd($post_data);
//        $response = $this->http_post($this->api_url, $post_data);
//        return $response;
//        return json_encode(['error' => 'bad_type'],true);
        return json_encode(['id' => 12133],true);
    }

    public function order_status($order_id)
    { // Get status, remains
        $post_data = [];
        $post_data['key'] = $this->api_key;
        $post_data['action'] = 'status';
        $post_data['id'] = (int)trim(strip_tags($order_id)); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    private function http_post($url, $post_paramas)
    {
        if (empty($url) || empty($post_paramas)) {
            return 'Parameter not Passed';
        }
        $fields_string = '';
        foreach ($post_paramas as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        $fields_string = rtrim($fields_string, '&');
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POST, count($post_paramas));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //execute post
        $result = curl_exec($ch);
        if (curl_errno($ch) != 0 && empty($result)) {
            $result = false;
        }
        curl_close($ch);
        return $result;
    }
}