<?php
/**
 * hexaTrend.php
 * nicholas st.pierre
 *
 * march 23 2014
 *
 * this file uses cities.php to obtain trends from some common cities.
 * It then spits some html out
 *
 *
 */
  // if we run out of requests, use this html
  // include 'sampleHexagons.html'

  include('../cities.php');

  if( ! isset($trendsBoston)  ||
      ! isset($trendsAtlanta) ||
      ! isset($trendsLA))  die("Out of requests!");

  /*echo count($trendsBoston);
  echo count($trendsAtlanta);
  echo count($trendsLA);*/
  $response  = "";
  $response .= '<div class="content">';
  $response .= '<div class="honeycombs">';
  $response .= Honey($trendsBoston,   "Boston",  8) ;
  $response .= Honey($trendsLA,       "LA",      8) ;
  $response .= Honey($trendsAtlanta,  "Atlanta", 8) ;
  $response .= "</div></div>";

  
  echo $response;
  
  function Honey($arr, $cityname, $num){
        $i=1;
        $tweetViewURL= "#trend";      
        $reply="";
        
        foreach($arr as $honeycomb) {
          
         /* $reply= "<div class='comb'>
                <a href='$tweetViewURL/". urlencode($honeycomb)."'>
                  <p>$cityname<br>$honeycomb</p>
                </a>
              </div>";
*/
          
          
          // debug move
          
          $reply .= "<div class='comb'><span>\n  <p><a href='$tweetViewURL/". urlencode($honeycomb)."'>$cityname: $honeycomb</a></p>\n</span></div>\n";
          //$reply .= "<div class='comb'><a href='$tweetViewURL/". urlencode($honeycomb)."'></a><span>$cityname: $honeycomb</span>\n</div>\n";
          //$reply .= "<div class='comb'><span>". htmlentities("$cityname $honeycomb") ."</span>\n</div>\n";

          //method from old page
          /*          echo "<div class='comb'>
                <a href='$tweetViewURL/". urlencode($honeycomb)."'>
                <ul class='hexList'>
                  <li>$cityname</li>
                  <li>________</li>
                  <li>$honeycomb</li>
                </ul>
                </a>
              </div>";    */

          if( $i === $num ) {
            break 1;
          }
          $i++;
        }
        
        return $reply;
  }
?>
