<?php
/**
 * nick stpierre
 * 3.30.2014
 * gotta go fast
 * 
 */


/***************** INIT VARS **********************/

// this seems to do nothing but it /should/ make php spew errors
error_reporting(E_ALL);

// name of the file that will contain the cached html
$cachedFilename = "cachedHexas.html";


/****************************************************/



/*********** DEFINE HELPER FUNCTIONS ****************/


/**
 * 
 * @param type $arr
 * @param type $cityname
 * @param type $num
 * @return string
 */
function Honey($arr, $cityname, $num){
    $i=1;
    $tweetViewURL= "#trend";      
    $reply="";

    foreach($arr as $honeycomb) {

      $reply .= "<div class='comb'><span>\n  <p><a href='$tweetViewURL/". 
                urlencode($honeycomb)."'>$cityname: $honeycomb</a></p>\n</span></div>\n";

      if( $i === $num ) {
        break 1;
      }
      $i++;
    }

    return $reply;
}

/**
 * function to getAndStoreHexaHTML
 * returns FALSE on failure, TRUE otherwise
 */
function getAndStoreHexaHTML()
{
  
  $cachedFilename = "cachedHexas.html";

  // this script sets $trendsBoston, $trendsAtlanta, $trendsLA
  include('../cities.php');

  // check if the script had any trouble or if we're out of requests
  
  if( ! isset($trendsBoston)  ||
      ! isset($trendsAtlanta) ||
      ! isset($trendsLA)) 
    {
    //echo "trends arrays are empty, we're dead";
    return FALSE;
    
    } else {
      /*echo count($trendsBoston);
      echo count($trendsAtlanta);
      echo count($trendsLA);*/
      $response  = "";
      $response .= '<div class="content">'."\n";
      $response .= '<div class="honeycombs">'."\n";
      $response .= Honey($trendsBoston,   "Boston",  8) ;
      $response .= Honey($trendsLA,       "LA",      8) ;
      $response .= Honey($trendsAtlanta,  "Atlanta", 8) ;
      $response .= "</div>"."\n"."</div>";
  }

  // get the absolute path to the file
  $fullpath = getcwd() . "/$cachedFilename";
  
  // open it
  $handle =  fopen($fullpath, "w");
  
  // error check opening the file
  if(! $handle)
  {
    //echo "ERROR can't open file";
    return FALSE;
  }
  
  // error check writing to the file
  if( fwrite($handle, $response) === FALSE)
  {
    //echo "ERROR file write error";
    return FALSE;
  }
  
    // close the file and honestly, forget error checking.
    fclose($handle);

    // true means OK
    return TRUE;
}

/******************************************************/


/******************** SCRIPT BODY *********************/


// first check to see if our cached file exists
if(! file_exists($cachedFilename))
{
  //echo "file dont exists<br>";
  
  // if not exists
  if( getAndStoreHexaHTML() )
    echo file_get_contents($cachedFilename);
  else
    echo "ERROR Couldn't get an initial cache";
  
}
else
{ 
 
  //echo "file exists<br>";

  // get file modified time
  $modtime = filemtime($cachedFilename);
  
  
  // error check this
  if( $modtime === FALSE )
  {
    die("ERROR filemtime failed");
  }

  // time() gives us epoch seconds, and so does modtime()
  $fileAge = time() - $modtime;
  
  //echo "debug: now is " . time() . ", modtime is $modtime, and their difference is $fileAge <br>";

  // If the file is old, refresh it:
  // 900 seconds = 15 minutes
  if( $fileAge > 900 )
  {
    if(! getAndStoreHexaHTML())
    {
      //echo "we have a cached version but the refresh failed...<br>";
    }
    else{
      //echo "update success<br>";
    }
  }
  else
  {
    echo "\n<!-- the cached file is $fileAge seconds old -->\n";
    //echo "using cached<br>";
    
  }
  
  
  
  echo file_get_contents($cachedFilename);
  
} // end else {?>