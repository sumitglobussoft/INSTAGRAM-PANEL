<?php
/**
 * Created by PhpStorm.
 * User: GLB-249
 * Date: 2/15/2016
 * Time: 6:56 PM
 */

namespace App\Http\Controllers\API;
use Exception;

class CheapBulkSocial
{
    public $api_url = 'http://cheapbulksocial.com/api.php'; // API URL
    public $api_key = 'f928fa9f4f1c29a728790a1648dfcc9799f36d9b'; // Your API key

    public function order_add($link, $type, $quantity)
    { // Add order
        $post_data = [];
        $post_data['key'] = $this->api_key;
        $post_data['o_type'] = $type; // The order type or service type string
        $post_data['c_url'] = trim(strip_tags($link)); // The order Link Here
        $post_data['o_qty'] = $quantity; // The order amount
        $response = $this->http_post($this->api_url, $post_data);
        return $response;

//        return json_encode(['status_code'=>0,'status_message'=>'Invalid Order Type.'], true);
//        return json_encode(["status_code" => 1, "status_message" => "Order placed successfully. [Order Number: 711467853]",
//            "count_start" => 71, "amount" => 0.02], true);
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
        $url = $this->api_url . '?' . $fields_string;

        $ch = '';
        try {
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600); # timeout after 10 seconds, you can increase it
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
            // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);

            if (($result === FALSE) || (curl_errno($ch) != 0 && empty($result))) {
                throw new Exception();
            }

            curl_close($ch);
            return $result;
        } catch (Exception $e) {
            curl_close($ch);
            throw new \Exception("Error in Curl Execution !");
        }
    }
}