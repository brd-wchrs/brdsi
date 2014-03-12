<html>
  <head>
    <!-- 
      File: timeline.php
      Author: Alan Estrada
    
      Comments because HEINES MAKES ME FEEL BAD 
      
      Literally for just messing around with the basic twitter API.
      
      Using someone else's class TwitterAPIExchange.php for OAuth.
      -->

    <title>Parsing Timelines</title>
  </head>
  <body>

    <form name="form1" method="POST" action="timeline.php">
      <table>
	<tr>
	  <td>Screen Name:</td>
	  <td><input type="text" name="screenname"></td>
	</tr>
	<tr>
      </table>
    </form>

    <?php

    require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-DY4QCFFLLL8cZkNKEhbdthu84567o4AjhJjQFGE",
      'oauth_access_token_secret' => "naCMorFD9wCUYF3PC6rh47vuikDmlfRYuprKJsNBofKQN",
      'consumer_key' => "pIMhuLu8PLs7IZYYFbZMbQ",
      'consumer_secret' => "1h3uAUlnwwkQfSK0mKpLwMApDd4pEmxffBaSjTmO9g"
    );

    /** So yeah, this part grabs the stuff from the textboxes */
    $query = $_POST['screenname'] ;

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name=' . $query . '&count=200&contributor_details=true'; 

    /** Default code, copied from source. **/
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);
    
    $json = json_decode( $twitter->setGetfield($getfield)
                         ->buildOauth($url, $requestMethod)
                         ->performRequest(),true);     


    /** Variables to contain data **/
    $tags = array() ;             // contains the hashtags used
    $usersMentioned = array() ;   // contains the users mentioned
    $dates = array() ;            // contains the days in which there were tweets
    $daysOfWeek = array() ;       // contains the number of tweets per day of the week
    $favoritedTweets = array() ;  // contains the number of favorites per tweets


    $beginDate = '' ;             // contains the date of the earliest tweet received
    $endDate = '' ;               // contains the date of the latest tweet received
    $retweets = 0 ;               // contains how many retweets
    $replies = 0 ;                // contains how many replies
    $userMentions = 0 ;           // contains how many times a user was mentioned
    $hashtags = 0 ;               // contains how many times a hashtag was used

    $totalTweets = count($json) ; // number of tweets received

    /** Iterate through the json of tweets to analyze data **/
    $count = 0 ;
    foreach( $json as $tweet ) {
           
      /** Finds the date of the first and last tweets received **/
      if ( $tweet === reset($json) ) {
        $beginDate = $tweet["created_at"] ;
      }

      if ( $tweet === end($json) ) {
        $endDate = $tweet["created_at"] ;
      }
    
      /** Finds the the five tweets with the most favorites **/
      if ( count($favoritedTweets) < 5 ) {
         $favoritedTweets[] = $count ;
      }
      elseif ( array_key_exists( "favorite_count" ) 
               && !is_null( $tweet["favorite_count"] )) {
	foreach ( $favoritedTweets as &$tweetNum ) {
	  if ( $tweet["favorite_count"] > $json[$tweetNum]["favorite_count"] ) {
            $tweetNum = $count ;
            break ;
          }
        }
      }
				     
      /** Populates topTags with the hashtags and how many times used **/
      foreach ( $tweet["entities"]["hashtags"] as $hashtag ) {
        $tags[$hashtag["text"]]++ ;
        $hashtags++ ;
       }
				     
      /** Fills usersMentioned with the users mentioned and how many times **/
      foreach ( $tweet["entities"]["user_mentions"] as $user ) {
        $usersMentioned[$user["screen_name"]]++ ;
        $userMentions++ ;
      }

      /** Finds the day of the week the tweet was posted **/
      $day = date('D',strtotime($tweet["created_at"])) ;
      $daysOfWeek[$day]++;

      /** Increments each date tweeted **/
      $dates[$tweet["created_at"]]++;

      /** Check if this is a retweet **/
      if ( array_key_exists( "retweeted_status", $tweet ) ) {
        $retweets++ ;
      }

      /** Check if this is a reply **/
      if ( !is_null( $tweet["in_reply_to_user_id"] )) {
        $replies++ ;
      }

      $count++ ;
    }

    /****** This is where we start analyzing the stats ******/

    /** Sort the arrays to get top ten values of each **/
    arsort($tags) ;
    arsort($usersMentioned);

    /** Counts percentage of total tweets are retweets **/
    $retweets = ($retweets / $totalTweets) * 100 ;
    $retweets = number_format( $retweets, 1 ) ;

    /** Counts percentage of total tweets are replies **/
    $replies = ($replies / $totalTweets) * 100 ;
    $replies = number_format( $replies, 1 ) ;

    /** Format the dates **/
    $f_beginDate = date('F j, Y, g:ia', strtotime($beginDate)) ;
    $f_endDate = date('F j, Y, g:ia', strtotime($endDate)) ;

    /** Find the average number of tweets per day **/
    $date1 = date('Y-m-d', strtotime($beginDate));
    $date2 = date('Y-m-d', strtotime($endDate));

    $diff = abs(strtotime($date2) - strtotime($date1)) ;
    $totalDays = $diff / (60 * 60 * 24) ;

    $tweetsPerDay = number_format($totalTweets / $totalDays, 1) ;

    /** Find the percentage of tweets posted per day of the week **/
    foreach ( $daysOfWeek as &$day ) {
      $day = number_format( ($day / $totalTweets ) * 100, 1) ; 
    }

    /****** This is where we stop analyzing the stats ******/

    /****** This is where we begin to display our analysis ******/

    //echo "Judging from " . $query . "'s tweets from " . $f_beginDate . " to " . $f_endDate . "..." ;
    
    echo "In the past " . number_format($totalDays, 0) . " days ..." ;

    echo "<br><br>" ;
    echo "Initial statistics: <br>" ;
    echo $query . "makes " . $tweetsPerDay . " tweets per day.<br>" ;
    echo $retweets . "% of " . $query . "'s tweets are retweets.<br>" ;
    echo $replies . "% of " . $query . "'s tweets are replies.<br>" ;
    echo $query . " used " . $hashtags . " hashtags and mentioned " .
         $userMentions . " users.<br>";
    echo "<br>" ;

    /** Display the top ten (or less) hashtags used **/
    echo '<table>';
    echo '<tr><td>Top Hashtags</td><td>Usage</td></tr>';
    $count = 0;
    foreach( $tags as $tag => $value ) {      
      $count++;
      echo '<tr><td>' . $count . '. ' . $tag . '</td>' .
	   '<td>' . $value . '</td></tr>'; 
      if ( $count >= 10) {
        break;
      }
    }    
    echo '</table>';

    echo '<br>' ;

    /** Display the top ten (or less) users mentioned **/
    echo '<table>';
    echo '<tr><td>Users Mentioned:</td><td>Mentions:</td></tr>';
    $count = 0;
    foreach( $usersMentioned as $user => $value ) {
      $count++;
      echo '<tr><td>' . $count . '. ' . $user . '</td>' .
           '<td>' . $value . '</td></tr>';
      if ( $count >= 10) {
        break;
      }
    }
    echo '</table>';

    echo '<br>' ;

    /** Display breakdown of percentage of tweets per weekday **/
    echo '<table>';

    echo '<tr><td>Day of the Week</td>' . 
	 '<td> Percentage of Tweets Posted</td></tr>';

    echo '<tr><td>Sunday</td><td>' . $daysOfWeek['Sun'] .
	 '%</td></tr>' ;

    echo '<tr><td>Monday</td><td>' . $daysOfWeek['Mon'] .
	 '%</td></tr>' ;

    echo '<tr><td>Tuesday</td><td>' . $daysOfWeek['Tue'] .
	 '%</td></tr>' ;

    echo '<tr><td>Wednesday</td><td>' . $daysOfWeek['Wed'] .
	 '%</td></tr>' ;

    echo '<tr><td>Thursday</td><td>' . $daysOfWeek['Thu'] .
	 '%</td></tr>' ;

    echo '<tr><td>Friday</td><td>' . $daysOfWeek['Fri'] .
	 '%</td></tr>' ;

    echo '<tr><td>Saturday</td><td>' . $daysOfWeek['Sat'] .
	 '%</td></tr>' ;

    echo '</table>';

    echo '<br>' ;

    echo '<table>';
    echo '<tr><td>Highest Favorited Tweets</td>' . 
	 '<td>Date</td><td>Number of Favorites</td></tr>' ;

    foreach ( $favoritedTweets as $tweetNum ) {
      echo '<tr><td>' . $json[$tweetNum]["text"] . '</td>' ;
      $date = $json[$tweetNum]["created_at"] ;
      echo '<td>' . date('F j, Y, g:ia', strtotime($date)) . '</td>' ;
      echo '<td>' . $json[$tweetNum]["favorite_count"] . '</td></tr>' ;
      
    }
    echo '</table>' ;

    /****** This is where we stop displaying our analysis ******/

    ?>
  </body>
</html>

