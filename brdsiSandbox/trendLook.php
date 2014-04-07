<?php
    
    // code to Get the tweets
    
    /* This is from Alan's code! */
    
    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token'        => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key'        => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret'     => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );
    
    
    // todo: add location choosing.
    
    
    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    
    // make a twitter object from the api
    $twitter = new TwitterAPIExchange($settings);
    
    
    $latitude   = "42.35";
    $longitude  = "-71.06";

    $radius     = "500";

    
    $searchStr  =  $_GET['q'];
    
    $searchStr  =  str_replace(" ", "", $searchStr);
    $searchStr  =  str_replace("+", "", $searchStr);
    
    if(empty($searchStr)){print "<input type='hidden' value='ERROR: NO q in URI' >";}
    
    $getfield = "?q=$searchStr&geocode=$latitude,$longitude,$radius".'mi&count=15' ;
    

    $tweetsJSON = json_decode( $twitter->setGetfield($getfield)
                                 ->buildOauth($url, 'GET')
                                 ->performRequest(),true);
    
    

    // more alan code
      echo '<table><tr>';

      foreach($tweetsJSON['statuses'] as $status) {
        echo '<tr><td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
        echo '<td>' . $status['user']['name'] . '</td>';
        echo '<td>' . $status['text'] . '</td></tr>';
      }
      echo '</table>';

?>