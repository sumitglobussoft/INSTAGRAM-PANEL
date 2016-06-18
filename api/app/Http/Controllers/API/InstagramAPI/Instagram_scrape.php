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
        return false;
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
//        $numOfLatestPostCount = 3;

        $results = $this->scrape_using_websta($username, $numOfLatestPostCount);
        if ($results != null) {
            return $results;
        }

        $results = $this->scrape_using_instagram($username, $numOfLatestPostCount);
        if ($results != null) {
            return $results;
        }

//        $results = $this->scrape_using_gramfeed($username,$numOfLatestPostCount);
//        dd($results);

        return null;

    }

    public function scrape_using_webstaChandrakar($username, $numOfLatestPostCount = 0)
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);
        if ($result != '' || $result != null) {
            $regex = '/<a href="(.*?)" class="mainimg">/';

            preg_match_all($regex, $result, $result_array);

            if (count($result_array) > 1) {
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
        $insta_source = file_get_contents('https://instagram.com/' . $username);
//        $insta_source = $this->http_post('https://instagram.com/' . $username);

        if ($insta_source != '' || $insta_source != null) {
            $shards = explode('window._sharedData = ', $insta_source);
            if (count($shards) > 1) {
                $insta_json = explode(';</script>', $shards[1]);
                $insta_array = json_decode($insta_json[0], TRUE);

                $insta_array = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];

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
            }
            return null;
        }
        return null;
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

        return $htmlContentString;
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

    public function getInsUserLatestPostDetails($username, $createdTime, $numOfLatestPostCount = 0, $postType = 'image')
    {


//        $username = 'liveajayyadav';
//        $username = 'chandrakarramkishan';


        $results = $this->scrape_latest_post_using_websta($username, $createdTime, $numOfLatestPostCount, $postType);
        if ($results != null) {

            if ($results == 'Not found any video post' || $results == 'post empty') {
                return null;
            }

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

// THis code is modified and uploaded @ 30/05/2016 09:22 pm   // need some more modification and optimization
    public function scrape_latest_post_using_webstaNEW($username, $createdTime, $numOfLatestPostCount, $postType = 'image')
    {

        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);

        if ($result != '' || $result != null) {

            $regex = '/<span class="counts_media">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_post_array);
//            dd($result_post_array);
            if (!empty($result_post_array[1])) {
                if (intval($result_post_array[1][0]) != 0) {

                    $regex = '/<li><a href="(.*?)" rel="next"><i class="fa fa-chevron-down"><\/i> Earlier<\/a><\/li>/';
                    preg_match_all($regex, $result, $nextPage);


                    $nextPageUrl = '';
                    $loadNextPage = false;
                    if (!empty($nextPage[1])) {
                        $nextPageUrl = 'http://websta.me/' . $nextPage[1][0];
                    }

                    $instagramUserDetails = array();
                    $index = 0;
                    $breakWhileLoopFlag = false;

//                    $count=0;
                    do {
                        if ($loadNextPage) {
//                            echo ++$count;
                            $result = $this->http_post($nextPageUrl);
                            if ($result == '' || $result == null) {
                                return null;
                            }
                        }

                        if ($postType == 'image') {
                            $regex = '/<a href="(.*?)" class="mainimg">/';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[1])) {
                                $result_array = $result_array[1];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {
                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {

                                            if (intval($websta_result_array[1][0]) > $createdTime) {

                                                $instagramUserDetails[$index]['created_time'] = intval($websta_result_array[1][0]);

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);
                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }
                            }

                        } else if ($postType == 'video') {

                            $regex = '/<a href="(.*?)" class="fancy\-video video\-link (.*?)">(.*?)<a href="(.*?)" class="mainimg">/is';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[4])) {
                                $result_array = $result_array[4];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {

                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {
                                            if (intval($websta_result_array[1][0]) > $createdTime) {
                                                $instagramUserDetails[$index]['created_time'] = $websta_result_array[1][0];

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);

                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }

                            } else {
                                if ($nextPageUrl == '') {
                                    return "Not found any video post";
                                }
                            }
                        }

                        if ($breakWhileLoopFlag || $nextPageUrl == '') {
                            break;
                        }
//                     echo "<pre>"; print_r($instagramUserDetails);
                    } while (($nextPageUrl != '') && ($loadNextPage = true));

                    if (!empty($instagramUserDetails)) {
                        $instagramUserDetails = array_reverse($instagramUserDetails);
                        if ($numOfLatestPostCount == 0) {
                            return $instagramUserDetails;
                        } else {
                            if (count($instagramUserDetails) <= $numOfLatestPostCount) {
                                return $instagramUserDetails;
                            } else {
                                return array_slice($instagramUserDetails, 0, $numOfLatestPostCount);
                            }
                        }
                    }

                } else {
//                    dd("testing");
                    $regex = '/<li><a href="(.*?)" rel="next"><i class="fa fa-chevron-down"><\/i> Earlier<\/a><\/li>/';
                    preg_match_all($regex, $result, $nextPage);

                    $nextPageUrl = '';
                    $loadNextPage = false;
                    if (!empty($nextPage[1])) {
                        $nextPageUrl = 'http://websta.me/' . $nextPage[1][0];
                    }

                    $instagramUserDetails = array();
                    $index = 0;
                    $breakWhileLoopFlag = false;

//                    $count=0;
                    do {
                        if ($loadNextPage) {
//                            echo ++$count;
                            $result = $this->http_post($nextPageUrl);
                            if ($result == '' || $result == null) {
                                return null;
                            }
                        }

                        if ($postType == 'image') {
                            $regex = '/<a href="(.*?)" class="mainimg">/';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[1])) {
                                $result_array = $result_array[1];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {
                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {

                                            if (intval($websta_result_array[1][0]) > $createdTime) {

                                                $instagramUserDetails[$index]['created_time'] = intval($websta_result_array[1][0]);

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);
                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }
                            }

                        } else if ($postType == 'video') {

                            $regex = '/<a href="(.*?)" class="fancy\-video video\-link (.*?)">(.*?)<a href="(.*?)" class="mainimg">/is';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[4])) {
                                $result_array = $result_array[4];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {

                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {
                                            if (intval($websta_result_array[1][0]) > $createdTime) {
                                                $instagramUserDetails[$index]['created_time'] = $websta_result_array[1][0];

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);

                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }

                            } else {
                                if ($nextPageUrl == '') {
                                    return "Not found any video post";
                                }
                            }
                        }

                        if ($breakWhileLoopFlag || $nextPageUrl == '') {
                            break;
                        }
//                     echo "<pre>"; print_r($instagramUserDetails);
                    } while (($nextPageUrl != '') && ($loadNextPage = true));

                    if (!empty($instagramUserDetails)) {
                        $instagramUserDetails = array_reverse($instagramUserDetails);
                        if ($numOfLatestPostCount == 0) {
                            return $instagramUserDetails;
                        } else {
                            if (count($instagramUserDetails) <= $numOfLatestPostCount) {
                                return $instagramUserDetails;
                            } else {
                                return array_slice($instagramUserDetails, 0, $numOfLatestPostCount);
                            }
                        }
                    }

                }

            }
        }
        return null;
    }

    // THis is previous scraping code.
    public function scrape_latest_post_using_websta($username, $createdTime, $numOfLatestPostCount, $postType = 'image')
    {
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);
        if ($result != '' || $result != null) {

            $regex = '/<span class="counts_media">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_post_array);


            if (!empty($result_post_array[1])) {


                if (intval($result_post_array[1][0]) != 0) {

                    $regex = '/<li><a href="(.*?)" rel="next"><i class="fa fa-chevron-down"><\/i> Earlier<\/a><\/li>/';
                    preg_match_all($regex, $result, $nextPage);


                    $nextPageUrl = '';
                    $loadNextPage = false;
                    if (!empty($nextPage[1])) {
                        $nextPageUrl = 'http://websta.me/' . $nextPage[1][0];
                    }

                    $instagramUserDetails = array();
                    $index = 0;
                    $breakWhileLoopFlag = false;

//                    $count=0;
                    do {
                        if ($loadNextPage) {
//                            echo ++$count;
                            $result = $this->http_post($nextPageUrl);
                            if ($result == '' || $result == null) {
                                return null;
                            }
                        }

                        if ($postType == 'image') {
                            $regex = '/<a href="(.*?)" class="mainimg">/';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[1])) {
                                $result_array = $result_array[1];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {
                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {

                                            if (intval($websta_result_array[1][0]) > $createdTime) {

                                                $instagramUserDetails[$index]['created_time'] = intval($websta_result_array[1][0]);

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);
                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }
                            }

                        } else if ($postType == 'video') {

                            $regex = '/<a href="(.*?)" class="fancy\-video video\-link (.*?)">(.*?)<a href="(.*?)" class="mainimg">/is';
                            preg_match_all($regex, $result, $result_array);

                            if (!empty($result_array[4])) {
                                $result_array = $result_array[4];

                                foreach ($result_array as $key => $value) {
                                    $websta_post_uri = 'http://websta.me' . $value;
                                    $result = $this->http_post($websta_post_uri);

                                    if ($result != '' || $result != null) {

                                        //This regex is used to get only instagram post created time.
                                        $regex = '/<span class="time utime" data-utime="(.*?)">(.*?)<\/span>/';
                                        preg_match_all($regex, $result, $websta_result_array);

                                        if (!empty($websta_result_array[1])) {
                                            if (intval($websta_result_array[1][0]) > $createdTime) {
                                                $instagramUserDetails[$index]['created_time'] = $websta_result_array[1][0];

                                                //This regex is used to get only instagram post link.
                                                $regex = '/<li><a href="(.*?)" target="_blank" rel="nofollow"> View Original on Instagram<\/a><\/li>/';
                                                preg_match_all($regex, $result, $websta_result_array);

                                                if (!empty($websta_result_array[1])) {
                                                    $instagramUserDetails[$index]['link'] = $websta_result_array[1][0];
                                                }
                                            } else {
                                                $breakWhileLoopFlag = true;
                                                break;
                                            }
                                        }
                                    } else {
                                        return null;
                                    }
                                    $index++;
                                }

                            } else {
                                if ($nextPageUrl == '') {
                                    return "Not found any video post";
                                }
                            }
                        }

                        if ($breakWhileLoopFlag || $nextPageUrl == '') {
                            break;
                        }
//                     echo "<pre>"; print_r($instagramUserDetails);
                    } while (($nextPageUrl != '') && ($loadNextPage = true));

                    if (!empty($instagramUserDetails)) {
                        $instagramUserDetails = array_reverse($instagramUserDetails);
                        if ($numOfLatestPostCount == 0) {
                            return $instagramUserDetails;
                        } else {
                            if (count($instagramUserDetails) <= $numOfLatestPostCount) {
                                return $instagramUserDetails;
                            } else {
                                return array_slice($instagramUserDetails, 0, $numOfLatestPostCount);
                            }
                        }
                    }

                } else {
                    return "post empty";
                }
            }
        }

        return null;
    }

    public function getInsUserProfileDetails($username)
    {
//        $username = 'liveajayyadav';
//        $username = 'rafiahmadanjum';
//        $username = 'chandrakarramkishan';
//        $username = 'mr.ayush7082';
//        $username = 'mr.ayush70821';
//        $username = 'saurabh_bond';

        $result = $this->scrapDetailsUsingWebsta($username);
        return $result;
    }

    public function scrapDetailsUsingWebsta($username)
    {

        $data = array();
        $url = 'http://websta.me/n/' . $username . '/';
        $result = $this->http_post($url);
        if ($result != '' || $result != null) {
            $regex = '/<h1>This user is private.<\/h1>/';
            preg_match($regex, $result, $profile_type);
            if (!empty($profile_type) && filter_var($profile_type[0], FILTER_SANITIZE_STRING) == "This user is private.") {
                return "This user is private.";
            }

            $regex = '/<p>This user does not exist.<\/p>/';
            preg_match($regex, $result, $profile_exist);

            if (!empty($profile_exist) && filter_var($profile_exist[0], FILTER_SANITIZE_STRING) == "This user does not exist.") {
                return "This user does not exist.";
            }

            $regex = '/<span class="counts_media">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_array);
            if (!empty($result_array) && ($result_array != '' || $result_array != null)) {
                $data['posts'] = $result_array[1][0];
            }

            $regex = '/<span class="counts_followed_by">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_array);
            if (!empty($result_array) && ($result_array != '' || $result_array != null)) {
                $data['followers'] = $result_array[1][0];
            }

            $regex = '/<span class="following">(.*?)<\/span>/';
            preg_match_all($regex, $result, $result_array);
            if (!empty($result_array) && ($result_array != '' || $result_array != null)) {
                $data['following'] = $result_array[1][0];
            }

            return $data;
        } else {
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
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /*---------------Saurabh Kumar------------------------------*/

    public function instagram_scrape($username, $lastPostCreatedTime)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/rimjhim_appy";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $res = $this->extract_unit($result, '"nodes": ', ']}');
//            $res=json_encode($res,true);
            $res = ($res . ']');
//            $res=$res[0];
//            $res = json_decode(json_encode($res, true));
            $res = json_decode($res, true);
            if ($res) {
                $details = array();
//                $lastPostCreatedTime = 0;
                foreach ($res as $key => $result) {
                    if ($result['date'] > $lastPostCreatedTime) {
                        $details[$key]['link'] = ($result['code']); // this is the shortcode that we got from the instagram scraping.
                        $details[$key]['created_time'] = $result['date'];
                    }
                }
                return $details;

            } else {
                return null; //Username is private or doesnt exist
//                return "Username doesnt exist OR may be private";
            }

            /* This is code for scraping  through websta New Code

    //            dd(htmlspecialchars_decode($result));
    //            $regex = '/<div data-react-class="UserStatsBasicAnalytics" data-react-props="(.*?)">/';  //Uncomment this for websta scraping (new)
    //
    //            preg_match_all($regex, $result, $result_array);
    //            dd($result_array);
    //
    //
    //            $result_array = $result_array[1];
    //            $result_array = htmlspecialchars_decode($result_array[0]);
    //            $result_array = json_decode($result_array, true);
    //            dd($result_array);
    //            $lastPostCreatedTime = 1234;
    //            foreach ($result_array['media_list'] as $res) {
    //            if ($res["created_time"] > $lastPostCreatedTime)
    //            print_r($res["link"]);
    //            //                    print_r($res["created_time"]);
    //            }
    //            dd("stop");
            */

        }
    }

// THis is modified with followers count
    public function instagramScrape($username, $lastPostCreatedTime, $planType)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/saurabh_bond";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_private":');
//            dd($pos);
            if ($pos) {
                $isPrivate = $this->extract_unit($result, '"is_private": ', ',');
//                dd($isPrivate);
                if ($isPrivate == "false") { // as I am taking this false and true as a string not as a boolean SO using double quotes here , dont get confused
                    $res = $this->extract_unit($result, '"ProfilePage":', ']}');
//            $res=json_encode($res,true);
                    $res = ($res . ']}}}]');
//            $res=$res[0];
//            $res = json_decode(json_encode($res, true));
                    $res = json_decode($res, true);
//                    dd($res);
                    if ($res) {
                        $details = array();
//                        $details['followers_count'] = $res[0]["user"]["followed_by"]['count'];
                        $followerCounts = $res[0]["user"]["followed_by"]['count'];
//                        dd($details);
                        $mediaDetails = $res[0]["user"]["media"]["nodes"];
//                        dd($mediaDetails);
                        if ($mediaDetails != null) {
                            foreach ($mediaDetails as $key => $result) {
                                if ($planType == "video") {
                                    if ($result["is_video"] == true) {
                                        if ($result['date'] > $lastPostCreatedTime) {
                                            $details[$key]['link'] = ($result['code']); // this is the shortcode that we got from the instagram scraping.
                                            $details[$key]['created_time'] = $result['date'];
                                            $details[$key]['followers_count'] = $followerCounts;
                                            $details[$key]['likes_count'] = $result["likes"]["count"];
                                            $details[$key]['comments_count'] = $result["comments"]["count"];
                                            $details[$key]['views_count'] = ($result["is_video"]) ? $result["video_views"] : 0;
                                        }
                                    }

                                } elseif ($planType == "image") {
                                    if ($result['date'] > $lastPostCreatedTime) {
                                        $details[$key]['link'] = ($result['code']); // this is the shortcode that we got from the instagram scraping.
                                        $details[$key]['created_time'] = $result['date'];
                                        $details[$key]['followers_count'] = $followerCounts;
                                        $details[$key]['likes_count'] = $result["likes"]["count"];
                                        $details[$key]['comments_count'] = $result["comments"]["count"];
                                        $details[$key]['views_count'] = ($result["is_video"]) ? $result["video_views"] : 0;
                                    }
                                }
                            }
//                            dd($details);
                            return $details;
                        } else {
                            return null;
                            dd("There is no any post for this profile");
                        }

                    } else {
                        return null; //Username is private or doesnt exist
//                return "Username doesnt exist OR may be private";
                    }
                } else {
                    return null;
                    dd("username is private");
                }
            } else {
                return null; //Username doesnt exist
                dd("username doesnt exist");
            }

        }

        /* This is code for scraping  through websta New Code

    //            dd(htmlspecialchars_decode($result));
    //            $regex = '/<div data-react-class="UserStatsBasicAnalytics" data-react-props="(.*?)">/';  //Uncomment this for websta scraping (new)
    //
    //            preg_match_all($regex, $result, $result_array);
    //            dd($result_array);
    //
    //
    //            $result_array = $result_array[1];
    //            $result_array = htmlspecialchars_decode($result_array[0]);
    //            $result_array = json_decode($result_array, true);
    //            dd($result_array);
    //            $lastPostCreatedTime = 1234;
    //            foreach ($result_array['media_list'] as $res) {
    //            if ($res["created_time"] > $lastPostCreatedTime)
    //            print_r($res["link"]);
    //            //                    print_r($res["created_time"]);
    //            }
    //            dd("stop");
        */
    }

    public function isUsernameExists($username)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/saurabh_bond";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_private":');
//            dd($pos);
            if ($pos) {
                $isPrivate = $this->extract_unit($result, '"is_private": ', ',');
//                dd($isPrivate);
                if ($isPrivate == "false") { // as I am taking this false and true as a string not as a boolean SO using double quotes here , dont get confused
                    $res = $this->extract_unit($result, '"ProfilePage":', ']}');
                    $res = ($res . ']}}}]');
                    $res = json_decode($res, true);
//                    dd($res);
                    if ($res) {
                        $details = array();
                        $followerCounts = $res[0]["user"]["followed_by"]['count'];
                        $postCount = $res[0]["user"]["media"]["count"];
                        if ($postCount == 0)
                            return "There is no any post for this profile";
                        else
                            return $postCount;
                    }
                } else {
                    return "Account is private.";
                }
            } else {
                return "This user does not exist.";
            }
        } else {
            return null; //some error in scraping
        }

    }

    public function instagramScrapeByEndIndex($username, $endIndex)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/saurabh_bond";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_private":');
//            dd($pos);
            if ($pos) {
                $isPrivate = $this->extract_unit($result, '"is_private": ', ',');
//                dd($isPrivate);
                if ($isPrivate == "false") { // as I am taking this false and true as a string not as a boolean SO using double quotes here , dont get confused
                    $res = $this->extract_unit($result, '"ProfilePage":', ']}');
//            $res=json_encode($res,true);
                    $res = ($res . ']}}}]');
//            $res=$res[0];
//            $res = json_decode(json_encode($res, true));
                    $res = json_decode($res, true);
//                    dd($res);
                    if ($res) {
                        $details = array();
//                        $details['followers_count'] = $res[0]["user"]["followed_by"]['count'];
                        $followerCounts = $res[0]["user"]["followed_by"]['count'];
//                        dd($details);
                        $mediaDetails = $res[0]["user"]["media"]["nodes"];
//                        dd($mediaDetails);
                        if ($mediaDetails != null) {
                            $count = 1;
                            foreach ($mediaDetails as $key => $result) {

                                $details[$key]['link'] = ($result['code']); // this is the shortcode that we got from the instagram scraping.
                                $details[$key]['created_time'] = $result['date'];
                                $details[$key]['followers_count'] = $followerCounts;
                                $details[$key]['likes_count'] = $result["likes"]["count"];
                                $details[$key]['comments_count'] = $result["comments"]["count"];
                                $details[$key]['views_count'] = ($result["is_video"]) ? $result["video_views"] : 0;
                                if ($count == $endIndex) {
                                    break;
                                }
                                ++$count;

                            }
//                            dd($details);
                            return $details;
                        } else {
                            return null;
                            dd("There is no any post for this profile");
                        }

                    } else {
                        return null; //Username is private or doesnt exist
//                return "Username doesnt exist OR may be private";
                    }
                } else {
                    return null;
                    dd("username is private");
                }
            } else {
                return null; //Username doesnt exist
                dd("username doesnt exist");
            }

        }

        /* This is code for scraping  through websta New Code

    //            dd(htmlspecialchars_decode($result));
    //            $regex = '/<div data-react-class="UserStatsBasicAnalytics" data-react-props="(.*?)">/';  //Uncomment this for websta scraping (new)
    //
    //            preg_match_all($regex, $result, $result_array);
    //            dd($result_array);
    //
    //
    //            $result_array = $result_array[1];
    //            $result_array = htmlspecialchars_decode($result_array[0]);
    //            $result_array = json_decode($result_array, true);
    //            dd($result_array);
    //            $lastPostCreatedTime = 1234;
    //            foreach ($result_array['media_list'] as $res) {
    //            if ($res["created_time"] > $lastPostCreatedTime)
    //            print_r($res["link"]);
    //            //                    print_r($res["created_time"]);
    //            }
    //            dd("stop");
        */
    }

    public function instagramScrapeOfDirectLink($link)
    {
//        $url = "https://www.instagram.com/p/BE8Z1Iwq1Ss/?taken-by=saurabh_bond";
        $result = $this->curlHit($link);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_video":');
//            dd($pos);
            if ($pos) {
                $videoPost = $this->extract_unit($result, '"is_video": ', ',');
//                dd($videoPost);
                $details = [];
                $details['likes_count'] = $this->extract_unit($result, '"likes": {"count": ', ',');
                $details['comments_count'] = $this->extract_unit($result, '"comments": {"count": ', ',');
                $details['views_count'] = ($videoPost == "true") ? $this->extract_unit($result, '"video_views": ', ',') : 0;
                $details['image_url'] = $this->extract_unit($result, '"display_src": "', '"');
                return $details;

            } else {
                return "Account is private OR doesn't exist.";
//                dd("username doesnt exist");

            }
        }
    }

    public function isVideoPost($link)
    {
//        $url = "https://www.instagram.com/p/BGYCSJHi5w2/?taken-by=bestvines";
        $result = $this->curlHit($link);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_video":');
//            dd($pos);
            if ($pos) {
                $videoPost = $this->extract_unit($result, '"is_video": ', ',');
                return ($videoPost == "true") ? true : false;
            } else {
                return "Account is private OR doesn't exist.";
//                dd("username doesnt exist");
            }
        }
    }

    public function getProfilePicUrl($username)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/saurabh_bond";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_private":');
//            dd($pos);
            if ($pos) {
                $isPrivate = $this->extract_unit($result, '"is_private": ', ',');
//                dd($isPrivate);
                if ($isPrivate == "false") { // as I am taking this false and true as a string not as a boolean SO using double quotes here , dont get confused
                    $res = $this->extract_unit($result, '"ProfilePage":', ']}');
//            $res=json_encode($res,true);
                    $res = ($res . ']}}}]');
//            $res=$res[0];
//            $res = json_decode(json_encode($res, true));
                    $res = json_decode($res, true);
//                    dd($res);
                    if ($res) {
                        $details = array();
//                        $details['followers_count'] = $res[0]["user"]["followed_by"]['count'];
                        $details['followers_count'] = $res[0]["user"]["followed_by"]['count'];
                        $details['image_url'] = $res[0]["user"]["profile_pic_url"];
                        return $details;

                    } else {
                        return null; //Username is private or doesnt exist
//                return "Username doesnt exist OR may be private";
                    }
                } else {
                    return null;
                    dd("username is private");
                }
            } else {
                return null; //Username doesnt exist
                dd("username doesnt exist");
            }
        }
    }

    public function getDetailsByStartAndLastSpreadIndex($username, $startIndex, $endIndex)
    {
        $url = 'https://www.instagram.com/' . $username . '/';
//        $url = "https://www.instagram.com/saurabh_bond";
        $result = $this->curlHit($url);
//        dd($result);
        if ($result != '' || $result != null) {

            $pos = stripos($result, '"is_private":');
//            dd($pos);
            if ($pos) {
                $isPrivate = $this->extract_unit($result, '"is_private": ', ',');
//                dd($isPrivate);
                if ($isPrivate == "false") { // as I am taking this false and true as a string not as a boolean SO using double quotes here , dont get confused
                    $res = $this->extract_unit($result, '"ProfilePage":', ']}');
//            $res=json_encode($res,true);
                    $res = ($res . ']}}}]');
//            $res=$res[0];
//            $res = json_decode(json_encode($res, true));
                    $res = json_decode($res, true);
//                    dd($res);
                    if ($res) {
                        $details = array();
//                        $details['followers_count'] = $res[0]["user"]["followed_by"]['count'];
                        $followerCounts = $res[0]["user"]["followed_by"]['count'];
                        $totalLikesCount = 0;
//                        dd($details);
                        $mediaDetails = $res[0]["user"]["media"]["nodes"];
//                        dd($mediaDetails);
                        if ($mediaDetails != null) {

                            foreach ($mediaDetails as $key => $result) {
                                if (($key == $startIndex - 1) && ($key < $endIndex)) {
//                                    $details[$key]['link'] = ($result['code']); // this is the shortcode that we got from the instagram scraping.
//                                    $details[$key]['created_time'] = $result['date'];
                                    $details[$key]['followers_count'] = $followerCounts;
                                    $details[$key]['likes_count'] = $result["likes"]["count"];
                                    $totalLikesCount += $result["likes"]["count"];
//                                    $details[$key]['comments_count'] = $result["comments"]["count"];
//                                    $details[$key]['views_count'] = ($result["is_video"]) ? $result["video_views"] : 0;
//                                    $endIndex--;
                                    $startIndex++;
                                }
                            }
                            $mediaCounts = [];
                            $mediaCounts = ['likes_count' => $totalLikesCount, 'followers_count' => $followerCounts];
                            return $mediaCounts;
                        } else {
                            return null;
                            dd("There is no any post for this profile");
                        }

                    } else {
                        return null; //Username is private or doesnt exist
//                return "Username doesnt exist OR may be private";
                    }
                } else {
                    return null;
                    dd("username is private");
                }
            } else {
                return null; //Username doesnt exist
                dd("username doesnt exist");
            }

        }
    }

    public function extract_unit($string, $start, $end)
    {
        $pos = stripos($string, $start);

        $str = substr($string, $pos);

        $str_two = substr($str, strlen($start));

        $second_pos = stripos($str_two, $end);

        $str_three = substr($str_two, 0, $second_pos);

        $unit = trim($str_three); // remove whitespaces

        return $unit;
    }

    public function curlHit($url)
    {
//        dd($url);

//        $proxies = array(); // Declaring an array to store the proxy list
//
        // Adding list of proxies to the $proxies array // This is the local Proxy given by the SS team
//        $proxies[] = '103.217.90.66';
//        $proxies[] = '103.217.90.96';
//        $proxies[] = '103.217.90.97';
//        $proxies[] = '103.217.90.98';
//        $proxies[] = '103.217.90.99';
//        $proxies[] = '103.217.90.100';
//        $proxies[] = '103.217.90.101';
//        $proxies[] = '103.217.90.102';
//
        // Choose a random proxy
//        if (isset($proxies)) {
//            $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
//        }
//        $url="https://www.instagram.com/accounts/login/?force_classic_login=&next=/oauth/authorize/%3Fclient_id%3D9d836570317f4c18bca0db6d2ac38e29%26redirect_uri%3Dhttp%3A//websta.me/callback%26response_type%3Dcode%26scope%3Drelationships%2Blikes%2Bcomments%2Bbasic%2Bfollower_list%2Bpublic_content&username;
//        $url="/accounts/login/?force_classic_login=&next=/oauth/authorize/%3Fclient_id%3D9d836570317f4c18bca0db6d2ac38e29%26redirect_uri%3Dhttp%3A//websta.me/callback%26response_type%3Dcode%26scope%3Drelationships%2Blikes%2Bcomments%2Bbasic%2Bfollower_list%2Bpublic_content";
//        $url="https://www.facebook.com/";
//        $data = array(
//            'email' => 'saurabh.kumar@globussoft.com',
//            'pass' => 'bond_007'
//        );
//        $url = "https://www.instagram.com/saurabh_bond/";
        $ch = curl_init();  // Initialise a cURL handle


        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
//        if (isset($proxy)) {
//            curl_setopt($ch, CURLOPT_PROXY, $proxy);
//        }
        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookies);
//        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookies);
//        curl_setopt($ch, CURLOPT_HEADER, 0);

//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);


//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}