<html>
  <head>
    <!-- 
      File: twittertest.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Testing Twitter</title>
  </head>
  <body>

    <form name="form1" method="" action="">
      <input type="text" value="#asianproblems">
      <input type="submit" name="querySubmit" value="Search">
    </form>

    <?php

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key' => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret' => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );

    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?q=#asianproblems';
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);

    $json = json_decode( $twitter->setGetfield($getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest(),true);     

    echo '<table>';

    foreach($json['statuses'] as $status) {
      echo '<tr>';
      echo '<td><img src="' . $status['user']['profile_image_url'] . '"/></td>';
      echo '<td>' . $status['user']['name'] . '</td>';
      echo '<td>' . $status['text'] . '</td>';
      echo '</tr>';
    }

    echo '</table>';
    ?>
  </body>
</html>

