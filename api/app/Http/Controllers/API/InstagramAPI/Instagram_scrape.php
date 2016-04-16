<?php


namespace App\Http\Controllers\API\InstagramAPI;


class Instagram_scrape
{
    private $apiClientID = 'd89b5cfa3796458ebbb2520d70eeb498';
    const API_URL = 'https://api.instagram.com/v1/';


    public function isUserFound($username)
    {
        $results = $this->checkUserExistByUsing_websta($username);
        if ($results) {
            return true;
        }

//        $results = $this->scrape_using_gramfeed($username,$numOfLatestPostCount);
//        dd($results);

        return false;

    }

    public function checkUserExistByUsing_instagramAPI($username)
    {
        $url = 'https://api.instagram.com/v1/users/search?q=guiltytrips&client_id=d89b5cfa3796458ebbb2520d70eeb498';
//        $url = self::API_URL . 'users/search?q=' . $username . '&client_id=' . $this->apiClientID;
        $result = $this->http_post($url);
        $result = json_decode($result, true);

        if (isset($result['data']) && !empty($result['data'])) {
            return true;
        }
    }

//    public function checkUserExistByUsing_instagram($username)
//    {
//        $insta_source = file_get_contents('https://instagram.com/' . $username);
////        $insta_source = $this->http_post('https://instagram.com/' . $username);
//
//        dd($insta_source);
//        if ($insta_source != '' || $insta_source != null) {
//            $shards = explode('window._sharedData = ', $insta_source);
//            dd($shards);
//            if (count($shards) > 1) {
//                $insta_json = explode(';</script>', $shards[1]);
//            }
//        }
//
//    }

    public function checkUserExistByUsing_websta($username)
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);
        if ($result != '' || $result != null) {
            $regex = '/<p>This user does not exist.<\/p>/';
            preg_match_all($regex, $result, $result_array);
            $result_array = $result_array[0];
            if (empty($result_array)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getInsUserDetailsByUsername($username, $numOfLatestPostCount)
    {
//        $username = 'liveajayyadav';
//        $username = 'chandrakarramkishan';

//        $numOfLatestPostCount = 1;

        $results = $this->scrape_using_websta($username, $numOfLatestPostCount);
        if ($results != null) {
            return $results;
        }

        $results = $this->scrape_using_instagram($username, $numOfLatestPostCount);
        if ($results != null) {
            return $results;
        }

//        dd($results);
//        $results = $this->scrape_using_gramfeed($username,$numOfLatestPostCount);
//        dd($results);

        return null;

    }

    public function scrape_using_websta($username, $numOfLatestPostCount = 0)
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);


        if ($result != '' || $result != null) {
            $regex = '/<a href="(.*?)" class="mainimg">/';

            preg_match_all($regex, $result, $result_array);

            if (count($result_array) > 1 && !empty($result_array[1])) {
                $result_array = $result_array[1];
                $instagramUserDetails = array();
                foreach ($result_array as $key => $value) {
                    if ($key > ($numOfLatestPostCount - 1)) {
                        break;
                    }

                    $websta_post_uri = 'http://websta.me' . $value;
                    $result = $this->http_post($websta_post_uri);
                    if ($result != '' || $result != null) {
                        //This regex is used to get only instagram post link.
                        $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                        preg_match_all($regex, $result, $websta_result_array);
                        if (count($websta_result_array) > 1) {
                            $instagramUserDetails[$key]['link'] = $websta_result_array[1][0];
                        } else {
                            return null;
                        }

                        //This regex is used to get only instagram post created time.
                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                        preg_match_all($regex, $result, $websta_result_array);
                        if (count($websta_result_array) > 1) {
                            $instagramUserDetails[$key]['created_time'] = $websta_result_array[1][0];
                        } else {
                            return null;
                        }

                        //This regex is used to get only instagram post likes comments.
                        $regex = '/<span class="comment_count_(.*?)">(.*?)<\/span>/';
                        preg_match_all($regex, $result, $websta_result_array);
                        if (count($websta_result_array) > 1) {
                            $instagramUserDetails[$key]['comments_count'] = $websta_result_array[2][0];
                        } else {
                            return null;
                        }

                        //This regex is used to get only instagram post likes count.
                        $regex = '/<span class="like_count_(.*?)">(.*?)<\/span>/';
                        preg_match_all($regex, $result, $websta_result_array);
                        if (count($websta_result_array) > 1) {
                            $instagramUserDetails[$key]['likes_count'] = $websta_result_array[2][0];
                        } else {
                            return null;
                        }

                    } else {
                        return null;
                    }

                }
                return (!empty($instagramUserDetails)) ? $instagramUserDetails : null;
            } else {
                return null;
            }
        } else {
            return null;
        }

    }

    public function scrape_using_instagram($username, $numOfLatestPostCount = 0)
    {

        try {
            $insta_source = file_get_contents('https://instagram.com/' . $username);
//        $insta_source = $this->http_post('https://instagram.com/' . $username);

            if ($insta_source != '' || $insta_source != null) {
                $shards = explode('window._sharedData = ', $insta_source);
                if (count($shards) > 1) {
                    $insta_json = explode(';</script>', $shards[1]);

                    $insta_array = json_decode($insta_json[0], TRUE);

                    $insta_array = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];

                    if (!empty($insta_array)) {
                        $instagramUserDetails = array();
                        for ($i = 0; $i < $numOfLatestPostCount; $i++) {
                            if (!$insta_array[$i]['is_video']) {
                                $instagramUserDetails[$i]['link'] = 'https://www.instagram.com/p/' . $insta_array[$i]['code'] . '/';
                                $insta_array[$i]['date'] = explode('.', $insta_array[$i]['date']);
                                $instagramUserDetails[$i]['created_time'] = $insta_array[$i]['date'][0];
                                $instagramUserDetails[$i]['comments_count'] = $insta_array[$i]['comments']['count'];
                                $instagramUserDetails[$i]['likes_count'] = $insta_array[$i]['likes']['count'];
                            } else {//for video views

                            }
                        }
                        return (!empty($instagramUserDetails)) ? $instagramUserDetails : null;
                    } else {
                        return null;
                    }
                }
                return null;
            }
            return null;
        } catch (\Exception $exec) {
            return null;
        }

    }

    public function scrape_using_gramfeed($username, $numOfLatestPostCount = 0)
    {

        $url = 'http://www.gramfeed.com/' . $username . '/';
//        $results = file_get_contents($url);
        $htmlContentString = $this->http_post($url);
        $data = file_get_contents($url);
//        $pattern = "/src=[\"']?([^\"']?.*(png|jpg|gif))[\"']?/i";
        $pattern = '/<div class="box-num">(.*?)<\/div>/';
//        preg_match_all($pattern, $data, $result);
        preg_match($pattern, $data, $result);
        dd($result);
//        $regex = '/<div class="box-num">(.*?)<\/div>/';
        $regex = '/<div class="title">(.*)/';


        if ((preg_match($regex, $htmlContentString, $result))) {
//            print_r( $followers);
            dd($result);
        } else {
            echo "does not match";
        }

        dd("error");
        return $htmlContentString;
    }

    public function  getInsUserLatestPostDetails($username, $createdTime, $numOfLatestPostCount = 0)
    {

//        $username = 'liveajayyadav';
//        $username = 'chandrakarramkishan';


        $results = $this->scrape_latest_post_using_websta($username, $createdTime, $numOfLatestPostCount);
        if ($results != null) {
            return $results;
        }

//        $results = $this->scrape_using_instagram($username);
//        if ($results != null) {
//            return $results;
//        }

//        dd($results);
//        $results = $this->scrape_using_gramfeed($username,$numOfLatestPostCount);
//        dd($results);

        return null;


    }

    public function scrape_latest_post_using_websta($username, $createdTime, $numOfLatestPostCount)
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);

        if ($result != '' || $result != null) {
            $regex = '/<a href="(.*?)" class="mainimg">/';

            preg_match_all($regex, $result, $result_array);

            if (count($result_array) > 1 && !empty($result_array[1])) {
                $result_array = $result_array[1];
                $instagramUserDetails = array();

                foreach ($result_array as $key => $value) {
                    $websta_post_uri = 'http://websta.me' . $value;
                    $result = $this->http_post($websta_post_uri);

                    if ($result != '' || $result != null) {
                        //This regex is used to get only instagram post created time.
                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                        preg_match_all($regex, $result, $websta_result_array);

                        if (!empty($websta_result_array[1]) && count($websta_result_array) > 1) {

                            if ($websta_result_array[1][0] > $createdTime) {

                                $instagramUserDetails[$key]['created_time'] = $websta_result_array[1][0];

                                //This regex is used to get only instagram post link.
                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                preg_match_all($regex, $result, $websta_result_array);

                                if (!empty($websta_result_array[1]) && count($websta_result_array) > 1) {
                                    $instagramUserDetails[$key]['link'] = $websta_result_array[1][0];
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }

                if (!empty($instagramUserDetails)) {
                    if ($numOfLatestPostCount == 0) {
                        return $instagramUserDetails;
                    } else {
                        $instagramUserDetails = array_reverse($instagramUserDetails);
                        if (count($instagramUserDetails) <= $numOfLatestPostCount) {
                            return $instagramUserDetails;
                        } else {
                            return array_slice($instagramUserDetails, 0, $numOfLatestPostCount);
                        }
                    }
                }
            }
        }

        return null;
    }

    public function getProfilePostCountByUsername($username)
    {
        $results = $this->getProfilePostCountUsing_websta($username);
        if ($results != null) {
            return intval($results);
        }

        $results = $this->getProfilePostCountUsing_instagram($username);
        if ($results != null) {
            return $results;
        }

//        $results = $this->scrape_using_gramfeed($username,$numOfLatestPostCount);
//        dd($results);

        return null;

    }

    public function getProfilePostCountUsing_websta($username)
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);
        if ($result != '' || $result != null) {
            $regex = '/<span class="counts_media">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_array);
//            dd($result_array);
            if (!empty($result_array) && ($result_array != '' || $result_array != null)) {
                $postCount = $result_array[1][0];
                return $postCount;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getProfilePostCountUsing_instagram($username)
    {
        {
            $insta_source = file_get_contents('https://instagram.com/' . $username);
//        $insta_source = $this->http_post('https://instagram.com/' . $username);
            if ($insta_source != '' || $insta_source != null) {
                $shards = explode('window._sharedData = ', $insta_source);
                if (count($shards) > 1) {
                    $insta_json = explode(';</script>', $shards[1]);
                    $insta_array = json_decode($insta_json[0], TRUE);
                    $postCount = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['count'];
                    return $postCount;
                }
                return null;
            }
            return null;
        }
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
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  # Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); # timeout after 10 seconds, you can increase it
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}