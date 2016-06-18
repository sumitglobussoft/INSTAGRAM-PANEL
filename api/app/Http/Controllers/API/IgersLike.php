<?php
namespace App\Http\Controllers\API;
use Exception;
/**
 * Class igerslike_market_consumer
 * This class should be used to consume the Market API for igerslike.com
 * Please make sure to edit and fix any bugs in your own responsability
 * All sales made are live and are final , If you wish to turn your test mode ON please contact us support@igerslike.com
 * Method : POST
 * Libcurl is required to run this file.
 */

class IgersLike
{
    public $api_url = 'https://www.igerslike.com/api/market/api.php'; // API URL
    public $api_key = '9d6f65be87883e23b078532d3ada6a91fc3ba3bcf93fedd552f5a3c1f04707f4'; // Your API key

    /** Adding a regulra order to igerslike
     * @param $link
     * @param $type
     * @param $amount
     * @param $options
     * @return mixed
     */
    public function order_add($link, $type, $amount, $comments = '')
    {
        $post_data = [];
        $post_data['action'] = 'order_add';
        $post_data['key'] = $this->api_key;
        $post_data['link'] = trim(strip_tags($link));
        $post_data['type'] = trim(strtolower($type));
        $post_data['amount'] = (int)trim(strtolower($amount));

        if (isset($comments) && $comments != '') {
            $post_data['comments_data'] = $comments; // Here should arrived the RAW $_POST from the text box of COMMENTS, new lines with \\r\\n
        }

        $response = $this->http_post($this->api_url, $post_data);
        return $response;

//        return json_encode(["status" => "ok", "message" => "Order Added", "order" => 2614165], true);
//        return json_encode(["status"=>"fail","message"=>"Error Message : Error in add order"],true);
    }

    /**
     * Get the status of an order ID an array is returned with all the elements
     * @param $order_id
     * @return mixed
     */
    public function order_status($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'order_status';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(strip_tags($order_id)); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * Get the status of an order ID an array is returned with all the elements
     * @param $order_id
     * @return mixed
     */
    public function order_status_multiple($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'order_status_multiple';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function order_stop($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'order_stop';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $username
     * @param $amount_likes
     * @param $amount_pics
     * @param $add_comments
     * @param $amount_comments
     * @param $service_type
     * @param $options
     * @return mixed
     */
    public function autolikes_add($username, $amount_likes, $amount_pics, $add_comments, $amount_comments, $service_type, $options = [])
    {

        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'auto_likes_add';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['username'] = (string)strtolower(trim(mysql_real_escape_string(strip_tags($username)))); // The order Link Here
        $post_data['amount_likes'] = (int)strtolower(trim(mysql_real_escape_string(strip_tags($amount_likes)))); // The order Link Here
        $post_data['amount_pics'] = (int)strtolower(trim(mysql_real_escape_string(strip_tags($amount_pics)))); // The order Link Here
        $post_data['add_comments'] = (int)strtolower(trim(mysql_real_escape_string(strip_tags($add_comments)))); // The order Link Here
        $post_data['amount_comments'] = (int)trim(strtolower(mysql_real_escape_string(strip_tags($amount_comments)))); // The order amount
        /** Advanced Order Fields */
        $post_data['service_type'] = (string)strtolower(trim(mysql_real_escape_string(strip_tags($service_type)))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function autolikes_stop($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'auto_likes_stop';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function autolikes_status($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'auto_likes_status';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function autolikes_restart($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'auto_likes_restart';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;

    }

    /**
     * @param $service_type
     * @param $link
     * @param $amount_total
     * @param $amount_per_run
     * @param $delay
     * @return mixed
     */
    public function automatic_add($service_type, $link, $amount_total, $amount_per_run, $delay, $options = [])
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'automatic_add';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['type'] = (string)strtolower(trim(mysql_real_escape_string(strip_tags($service_type)))); // The order Link Here
        $post_data['link'] = (string)trim(mysql_real_escape_string(strip_tags($link))); // The order Link Here
        $post_data['amount_total'] = (int)strtolower(trim(mysql_real_escape_string(strip_tags($amount_total)))); // The order Link Here
        $post_data['amount_per_run'] = (int)strtolower(trim(mysql_real_escape_string(strip_tags($amount_per_run)))); // The order Link Here
        $post_data['delay'] = (int)trim(strtolower(mysql_real_escape_string(strip_tags($delay)))); // The order amount
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function automatic_stop($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'automatic_stop';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function automatic_status($order_id)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'automatic_status';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $post_data['order_id'] = (int)trim(mysql_real_escape_string(strip_tags($order_id))); // The order Link Here
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @return mixed
     */
    public function check_balance()
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'info_get_balance';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @return mixed
     */
    public function check_pending()
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'info_get_pending';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @return mixed
     */
    public function check_processing()
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'info_get_processing';
        $post_data['key'] = $this->api_key;
        /** Basic Order Fields */
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $filter
     * @return mixed
     */
    public function check_latest_orders($filter = '')
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['action'] = 'info_get_latest';
        $post_data['key'] = $this->api_key;
        $post_data['service'] = $filter; // Specify any service filter : ex: ig_likes , ig_likes_fast etc
        /** Basic Order Fields */
        $response = $this->http_post($this->api_url, $post_data);
        return $response;
    }

    /**
     * @param $url
     * @param $post_paramas
     * @return mixed
     */
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
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);

            if (($result === FALSE) || (curl_errno($ch) != 0 && empty($result))) {
                throw new Exception();
            }

            curl_close($ch);
            return $result;
        } catch (Exception $e) {
            curl_close($ch);
            throw new Exception("Error in Curl Execution !");
        }
    }
}

