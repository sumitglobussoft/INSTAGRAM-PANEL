<?php namespace App\Http\Controllers\API;

class SocialNator
{
    private $secretkey = "1c5d7a92d458721b040a8f4b386263cc";
    private $api_url = "http://socialnator.com/api/market/api.php";

    public function add_order($instagramProfileURL, $amount, $methodName)
    {
        $post_data = [];
        /** Auth and Action */
        $post_data['instagramprofileurl'] = $instagramProfileURL;
        $post_data['orderamount'] = $amount;
        $post_data['secretkey'] = $this->secretkey;
        $post_data['methodname'] = $methodName;

//        $post_data['instagramprofileurl'] = 'https://www.instagram.com/p/8aG__xK1Um/';
//        $post_data['orderamount'] = 20;
//        $post_data['secretkey'] = $this->secretkey;
//        $post_data['methodname'] = "add_likes";
        $response = $this->http_post($this->api_url, $post_data);

        return $response;
        /*

        This is the add order response pattern .  and this is for deleted post also but it will not get added to the system
        "{\"code\":200,\"Message\":\"Your requested information has been processed.\"}"


        always getting this msg only
        "{\"code\":200,\"Message\":\"Your requested information has been processed.\"}"
        */
    }

    private function http_post($url, $post_paramas)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (is_array($post_paramas)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_paramas);
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


}