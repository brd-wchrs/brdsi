<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!--(C) Bri Gainley, Alan Estrada, Nick St. Pierre, 2014 -->
<!-- With code from http://www.alessioatzeni.com/blog/signin-dropdown-box-like-twitter-with-jquery/ -->
<!-- for dropdown login menu -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>brdsi - A Twitter Newsfeed</title>
        <meta name="description" content="Twitter trend analysis, delivered right to your screen">
        <meta name="viewport" content="width=device-width">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- build:css styles/vendor.css -->
        <!-- bower:css -->

        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="icon" type="image/png" href="images/favicon.ico">
        
        <!-- Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>  
		      
		  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		    <style type="text/css">
		      #map-canvas { height: 500px; width: 500px; border: orange thick solid; display:inline-block}
		      #tabl, #tabl td { border: black thin solid; padding: 0px; margin: 0px;}
		    </style>
		    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		    <script type="text/javascript"
		            src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=AIzaSyBitVoCXwDIYznuU_fyzu-DukWsuY0ZMJA&sensor=false">
		    </script>
		    <script type="text/javascript">
		
		      // this will all be moved into a separate file, later.
		
		      // map contains the google maps map object, and needs this scope for
		      // ease of access.
		      var map;
		
		      // creates a map and inserts it into the DOM
		      function initBasic(){
		
		        var startLoc=new google.maps.LatLng(-34.397, 150.644);
		
		        var mapOptions = {
		          center: startLoc,
		          zoom: 3
		        };
		
		        map = new google.maps.Map(document.getElementById("map-canvas"),
		                                  mapOptions);
		
		
		        // https://developers.google.com/maps/documentation/javascript/markers
		
		        // To add the marker to the map, use the 'map' property
		        /*var marker = new google.maps.Marker({
		            position:   startLoc,
		            map:        map,
		            title:      "Hello World!"
		        });*/
		
		
		      };
		
		      // print the date for my convenience
		      console.log(new Date());
		      
		      
		      // Use the radius and lat/long variables! They are updated automagically!
		      var activeCircle     =  null,  // this one must be inited to null, but
		          activeRadius     =  null,  // these three don't...
		          activeLatitude   =  null,
		          activeLongitude  =  null;
		      
		      
		      // initializes the drawing library functionality
		      function initDrawingLibrary(){
		
		
		        var drawingManager = new google.maps.drawing.DrawingManager( {
		
		        // options for the drawing manager
		          drawingControlOptions: {
		          position: google.maps.ControlPosition.TOP_CENTER,
		          drawingModes: [
		            //google.maps.drawing.OverlayType.MARKER,
		            google.maps.drawing.OverlayType.CIRCLE
		          ]
		        }});
		      
		
		        // callback function called when a new circle is drawn.
		        function logCircle(c)
		        {
		
		          // remove the previous circle (IF THERE IS ONE)
		          if(activeCircle !== null){
		             activeCircle.setMap(null);
		          }
		
		
		          // we won't have access to this circle object later, 
		          // so we gotta save a handle to it. (so we can remove it next call)
		          activeCircle = c;
		          activeRadius = c.getRadius();
		
		          var activeCenter =  c.getCenter();
		          
		          activeLongitude  =  activeCenter.lng();
		          activeLatitude   =  activeCenter.lat();
		
		          // some lovely debug
		          console.log("logCircle callback.\nradius: " + activeRadius +
		                      "\ncenter: " + activeCenter );
		
		
		          // update our table!
		          $("#map-longitude").text(activeLongitude);
		          $("#map-latitude").text(activeLatitude);
		          $("#map-radius").text(activeRadius);
		
		
		        };
		
		        // add the drawing manager to the map
		        drawingManager.setMap(map);
		
		        // now that the drawing manager is in place, register a callback
		        // circlecomplete fires when the user draws a circle
		        google.maps.event.addListener(drawingManager, 'circlecomplete', logCircle);
		
		
		      };
		
		
		      // calls all initialization functions
		      function initialize() {
		          initBasic();
		          initDrawingLibrary();
		      };
		
		      google.maps.event.addDomListener(window, 'load', initialize);
		
		
		
		    </script>

    </head>
    <body>
    
		<div class="container">
	        <div id="top-navbar">
				<a href="brd.php"><img src="images/brdsiLogo.png"></a>
				<ul>
				  <li><a href="timeline.php">Timeline Analysis</a></li>
				  <li><a href="maps.php">Region Graph</a></li>
				  <li><a href="#">Friend Trends</a></li>
				</ul>
	        </div>
			
			<div id="content">
				<h1>Google Maps Circle Example</h1>
				 <aside>Drawing a Circle gets you a radius and Long/Lat. fk yesss</aside>
				 <div id="map-canvas"><!-- the map will be created here --></div>
				 <div>
				   <table id="tabl">
				     <tr>
				       <td>Longitude</td>
				       <td><p id="map-longitude"></p></td>
				     </tr>
				     <tr>
				       <td>Radius</td>
				       <td><p id="map-radius"></p></td>
				     </tr>
				     <tr>
				       <td>Latitude</td>
				       <td><p id="map-latitude"></p></td>
				     </tr>
				   </table>
				 </div>
			</div>
	        
	        <div id="footer">
	        	<ul>
					<li><a href="about.html">About</a></li>
					<li><a href="contact.html">Contact</a></li>
					<li><a href="help.html">Help</a></li>        	
	        </div>
		</div>

</body>
</html>
