<?php

namespace App\Http\Controllers\API\InstagramAPI;


class Instagram
{
    //The API base URL.


    private $apiClientID = 'd6371f111591437cab59ea2f7fae0246';
//    private $apiClientID = 'd89b5cfa3796458ebbb2520d70eeb498';
//    private $apiClientID = 'd6371f111591437cab59ea2f7fae0246';
//    private $apiClientID = '8898c0c6ed1441b7850127cac45d390c';
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
                //modified by saurabh // code for load more page

//                for ($count = 0; $count <= $latestPostCount; $count += 20) {
                $mediaDetails = $this->getUserMediaDetailsById($userId);
                $count = 0;
                $i = 0;
                while ((count($mediaDetails) + $count - 3) <= $latestPostCount) {
//                    dd($mediaDetails);
                    if (isset($mediaDetails['pagination']) && !empty($mediaDetails['pagination']))//!= []
                        $nextUrl = $mediaDetails['pagination']['next_url'];
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
                            if ($i > ($latestPostCount - 1)) {
                                break;
                            }
//                  echo "<pre>"; print_r($key);
                            $data['instagramUsersData'][$i]['created_time'] = $value['created_time'];
                            $data['instagramUsersData'][$i]['link'] = $value['link'];
                            $data['instagramUsersData'][$i]['comments_count'] = $value['comments']['count'];
                            $data['instagramUsersData'][$i]['likes_count'] = $value['likes']['count'];
                            $data['instagramUsersData'][$i]['username'] = $value['user']['username'];
                            $data['instagramUsersData'][$i]['profile_picture'] = $value['user']['profile_picture'];
                            $i++;
                        }
                        if ((count($mediaDetails) + $count - 3) <= $latestPostCount) {
//                                dd($mediaDetails);
                            if (isset($nextUrl)) {
                                $mediaDetails = $this->http_post($nextUrl);
                                $mediaDetails = json_decode($mediaDetails, true);
                            }
                            $count += 20;
                        }
                    }
                }
//                dd($key);
                return $data;
//                }


                return "There are no any post";

            } else {
                return "user is private";
            }
        } else {
            return "Username does not exist";
        }

    }

    public function UserDetailsByUsernameWithLastPostCreatedTime($username, $lastPostCreatedTime)
    {
        $data=[];

        $result = $this->searchUser($username);
        if ($result == 429) {
            return 'Too many request';
        }

        if ($result) {
            $userId = $result['id'];
            $profileDetails = $this->getUserProfileDetails($userId);
            if (!($profileDetails['meta']['code'] == 400)) {
                $mediaDetails = $this->getUserMediaDetailsById($userId);

                $mediaDetails = $mediaDetails['data'];

                if (isset($mediaDetails)) {
                    foreach ($mediaDetails as $key => $value) {
                        if ($value['created_time'] > $lastPostCreatedTime) {
                            $data[$key]['created_time'] = $value['created_time'];
                            $data[$key]['link'] = $value['link'];
                            $data[$key]['comments_count'] = $value['comments']['count'];
                            $data[$key]['likes_count'] = $value['likes']['count'];
                            $data[$key]['username'] = $value['user']['username'];
                            $data[$key]['profile_picture'] = $value['user']['profile_picture'];
                        }
                    }
                }
                return $data;
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
        dd($result);
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
//            dd($userExist);
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

    public function getMediaDetailsByShortcode($shortcode, $postImage = false)
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
            $instagramUserData['views_count'] = (isset($result['video_views']) ? $result['video_views'] : 0); //modified by saurabh
            $instagramUserData['username'] = $result['user']['username'];
            $instagramUserData['profile_picture'] = $result['user']['profile_picture'];
            $instagramUserData['type'] = $result['type'];

            if ($postImage) {
                $instagramUserData['post_image_url'] = $result['images']['thumbnail']['url'];
            }

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
