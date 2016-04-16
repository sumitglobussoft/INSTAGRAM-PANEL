<?php

namespace App\Http\Controllers\API\InstagramAPI;


class Instagram
{
    //The API base URL.


    private $apiClientID = 'd89b5cfa3796458ebbb2520d70eeb498';
    const API_URL = 'https://api.instagram.com/v1/';

    public function getUserDetailsByUsername($username, $latestPostCount)
    {
        $data['likes_count'] = 0;
        $data['followers_count'] = 0;
        $data['comments_count'] = 0;
        $data['instagramUsersData'][] = array();

        $result = $this->searchUser($username);
        if ($result == 429) {
            return 'Too many request';
        }

        if ($result) {
            $userId = $result['id'];
            $profileDetails = $this->getUserProfileDetails($userId);
            if (!($profileDetails['meta']['code'] == 400)) {
                $data['followers_count'] = (isset($profileDetails['data']['counts']['followed_by'])) ? $profileDetails['data']['counts']['followed_by'] : 0;

                $mediaDetails = $this->getUserMediaDetailsById($userId);
                $mediaDetails = $mediaDetails['data'];

//            echo "<pre>"; print_r($mediaDetails);

                if (isset($mediaDetails)) {
                    foreach ($mediaDetails as $key => $value) {
                        $data['likes_count'] += $value['likes']['count'];
                        $data['comments_count'] += $value['comments']['count'];
                    }
                }

                $instagramUserData = '';
                if (isset($mediaDetails)) {
                    foreach ($mediaDetails as $key => $value) {
                        if ($key > ($latestPostCount - 1)) {
                            break;
                        }
//                  echo "<pre>"; print_r($key);
                        $data['instagramUsersData'][$key]['created_time'] = $value['created_time'];
                        $data['instagramUsersData'][$key]['link'] = $value['link'];
                        $data['instagramUsersData'][$key]['comments_count'] = $value['comments']['count'];
                        $data['instagramUsersData'][$key]['likes_count'] = $value['likes']['count'];
                        $data['instagramUsersData'][$key]['username'] = $value['user']['username'];
                        $data['instagramUsersData'][$key]['profile_picture'] = $value['user']['profile_picture'];
                    }
                    return $data;
                }

                return "There are no any post";

            } else {
                return "user is private";
            }
        } else {
            return "Username does not exist";
        }

    }

    public function searchUser($username)
    {
//        https://api.instagram.com/v1/users/search?q=guiltytrips&client_id=d89b5cfa3796458ebbb2520d70eeb498
        $url = self::API_URL . 'users/search?q=' . $username . '&client_id=' . $this->apiClientID;
        $result = $this->http_post($url);
        $result = json_decode($result, true);
        if ($result['meta']['code'] == 200) {
            $result = $result['data'];
            $userExist = false;
            $userData = array();
            foreach ($result as $user) {
                if ($username == $user['username']) {
                    $userExist = true;
                    $userData = $user;
                    break;
                }
            }
            return ($userExist) ? $userData : null;
        } else if ($result['meta']['code'] == 429) {
            return 429; //to many request
        } else {
            return null;
        }
    }

    public function getUserMediaDetailsById($userID)
    {
//        https://api.instagram.com/v1/users/2061346035/media/recent?client_id=d89b5cfa3796458ebbb2520d70eeb498
        $url = self::API_URL . 'users/' . $userID . '/media/recent?client_id=' . $this->apiClientID;
        $result = $this->http_post($url);
        $result = json_decode($result, true);
        return $result;
    }

    public function getMediaDetailsByShortcode($shortcode)
    {

        $result = $this->getUserMediaDetailsByShortcode($shortcode);
        $result = $result['data'];
        $instagramUserData = '';
        if (isset($result)) {
//            dd($result);
//                  echo "<pre>"; print_r($key);
            $instagramUserData['created_time'] = $result['created_time'];
            $instagramUserData['link'] = $result['link'];
            $instagramUserData['comments_count'] = $result['comments']['count'];
            $instagramUserData['likes_count'] = $result['likes']['count'];
            $instagramUserData['username'] = $result['user']['username'];
            $instagramUserData['profile_picture'] = $result['user']['profile_picture'];

            return $instagramUserData;
        }
        return "Media details not found";
    }

    public function getUserMediaDetailsByShortcode($shortcode)
    {
        //        https://api.instagram.com/v1/media/shortcode/BB19YjwEDHw?client_id=d89b5cfa3796458ebbb2520d70eeb498
        $url = self::API_URL . 'media/shortcode/' . $shortcode . '?client_id=' . $this->apiClientID;
        $result = $this->http_post($url);
        $result = json_decode($result, true);
        return $result;
    }

    public function getUserProfileDetails($id)
    {

        // https://api.instagram.com/v1/users/2061346035?client_id=d89b5cfa3796458ebbb2520d70eeb498
        $url = self::API_URL . 'users/' . $id . '?client_id=' . $this->apiClientID;
        $result = $this->http_post($url);
        $result = json_decode($result, true);
        return $result;
    }

    public function http_post($url)
    {
        // echo "<pre>";print_r($url);die();
        if (empty($url)) {
            return 'Parameter not Passed';
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

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
