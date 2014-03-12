<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Newsfeed</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <!-- build:css(.tmp) styles/main.css -->
        <!--link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/sidebarAndMain.css">
        < !--<link rel="stylesheet" href="styles/tiles.css"> -- >
        <link rel="stylesheet" href="styles/grid.css"-->
        <link rel="stylesheet" href="styles/a9881864.main.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet" type="text/css">
        <!-- endbuild -->
        <script src="bower_components/modernizr/modernizr.js"></script>

    </head>
    <body>
        <!--[if lt IE 10]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#">
            <img src="images/tinyBrdsiLogo.png" alt="brdsi logo">
          </a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">Regions</a></li>
            <li><a href="#">Friends</a></li>
            <li><a href="#">Mood Graphs</a></li>
            <li><a href="#">Login</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- For login form -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
        <form class="form-horizontal" role="form" style="padding-bottom: 15px;">
            <div class="input-group margin-bottom-sm">
              <span class="input-group-addon"><i class="fa fa-user fa-fw" style="color:#526e82;"></i></span>
              <input class="form-control" type="text" placeholder="Username"s>
            </div>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-key fa-fw" style="color:#526e82;"></i></span>
              <input class="form-control" type="password" placeholder="Password">
              <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
            </div>
        </form>
    <!-- End login form -->

    <!-- Begin sidebar navs -->
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Overview</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">This will be a search bar</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">And these will be tags</a></li>
          </ul>
        </div>
    <!-- End sidebar navs -->

    <?php
    
    
      /* This is Alan's code! */
    
      require_once('TwitterAPIExchange.php');

    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
      'oauth_access_token' => "2326287732-4iSDtAQB7nhc7EsvxFNUgOBg5LJpdqYvbQZEP0C",
      'oauth_access_token_secret' => "pBJ4aPRnfkEBBAqOUK9k8GDf7LHJBD2xPV4w0tmphYUhf",
      'consumer_key' => "y7ojWz9W1EAqeFyK5BsHfA",
      'consumer_secret' => "atcvh1uWEdNNZ2hnlUrcrFuE1yTqlnRHDpToVxik"
    );
    
    
    // todo: add location choosing.
    
    
    /** Now we create the URL that requests the trends of the WOEid **/
    $url = 'https://api.twitter.com/1.1/trends/place.json';
    //$woeid = '2512636'; // alpha: use 1 for global search.
    $woeid = '2367105'; // alpha: use 1 for global search.
    $getfield = "?id=$woeid";
    
    // make a twitter object from the api
    $twitter = new TwitterAPIExchange($settings);
    
    $requestMethod = 'GET';

    $trendsJSON = json_decode( $twitter->setGetfield($getfield)
                               ->buildOauth($url, $requestMethod)
                               ->performRequest(),true);
    
    $trends = $trendsJSON[0]["trends"];
        

    
    // NOW WE HAVE THE TRENDS!
    
    // Each trend is an array, containing a name, url, promoted_content, query, and events.
    // right now, we only care about name (and query, time permitting.)
    
    /*
     some development chickenscratch  
    echo "<div>";
    echo "<h1>DEVSPACE</h1>";
    
    foreach( $trends as $sub) 
    {
      print_r($sub); 
      echo 'OK<br>';
    }
    echo "</div>";
    */
    
    
    
    ?>
    
    <!-- Begin main center content -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Twitter Trends</h1>

          <div class="grid grid-pad">
            
            <?php
            
              //$panelColors=array("pink", "yellow", "pink", "navy", "pink", "yellow", "navy", "yellow", "pink", "yellow");
              $panelColors=array("pink", "navy", "pink", "navy", "pink", "navy",  "pink", "navy", "pink", "navy");
              $panelColorIndex=0;
            
              
              foreach($trends as $trend)
              {
                
                // quick hack to get the colors in the php. uses an array. 
                // access to the array will not go out of bounds
                $color=$panelColors[($panelColorIndex % count($panelColors))];
                //$color="pink";
                
                $classes="col-1-5 ".$color;
                $trendName=$trend['name'];
                
                $link="/ndev/viewer.php?q=".urlencode($trend['query']);
                
                $panel = "<div class='$classes'>\n<p><a href='$link'>$trendName</a></p>\n</div>";
            
                echo $panel;

                $panelColorIndex++;
              }
              
              
            ?>
            <!-- 
             This is how the page used to be:
            <div class="col-1-5 pink" id="trend-1">
              <p>#test</p>
            </div>
            <div class="col-1-5 yellow" id="trend-2">
              <p>#test</p>
            </div>
            <div class="col-1-5 pink" id="trend-3">
              <p>#test</p>
            </div>
            <div class="col-1-5 navy" id="trend-4">
              <p>#test</p>
            </div>
            <div class="col-1-5 pink" id="trend-5">
              <p>#test</p>
            </div>            
            <div class="col-1-5 yellow" id="trend-6">
              <p>#test</p>
            </div>
            <div class="col-1-5 navy" id="trend-7">
              <p>#test</p>
            </div>
            <div class="col-1-5 yellow" id="trend-8">
              <p>#test</p>
            </div>
            <div class="col-1-5 pink" id="trend-9">
              <p>#test</p>
            </div>
            <div class="col-1-5 yellow" id="trend-10">
              <p>#test</p>
            </div-->
          </div>
        <!-- End grid of hover tweets -->
        <!-- build:js scripts/vendor.js -->
        <!-- bower:js -->
        <script src="bower_components/jquery/jquery.js"></script>
        <!-- endbower -->
        <!-- endbuild -->

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>

        <!-- build:js scripts/plugins.js -->
        <script src="bower_components/sass-bootstrap/js/affix.js"></script>
        <script src="bower_components/sass-bootstrap/js/alert.js"></script>
        <script src="bower_components/sass-bootstrap/js/dropdown.js"></script>
        <script src="bower_components/sass-bootstrap/js/tooltip.js"></script>
        <script src="bower_components/sass-bootstrap/js/modal.js"></script>
        <script src="bower_components/sass-bootstrap/js/transition.js"></script>
        <script src="bower_components/sass-bootstrap/js/button.js"></script>
        <script src="bower_components/sass-bootstrap/js/popover.js"></script>
        <script src="bower_components/sass-bootstrap/js/carousel.js"></script>
        <script src="bower_components/sass-bootstrap/js/scrollspy.js"></script>
        <script src="bower_components/sass-bootstrap/js/collapse.js"></script>
        <script src="bower_components/sass-bootstrap/js/tab.js"></script>
        <!-- endbuild -->

        <!-- build:js({app,.tmp}) scripts/main.js -->
        <script src="scripts/main.js"></script>
        <!-- endbuild -->
        
    </body>
</html>
