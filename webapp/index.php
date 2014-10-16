<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


require_once 'config.php';
require_once 'oauth.php';

$oauth = new oauth(CLIENT_ID, CLIENT_SECRET, CALLBACK_URL, LOGIN_URL);
//$oauth->auth_with_code();
$oauth->auth_with_password(USERNAME, PASSWORD, 120);

require_once 'inc/db_con.php';
require_once 'inc/functions.php';
//require_once 'authenticate.php';
?>

<?php
if(isset($_POST['first_name'])) {

    $first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$organization = $_POST['organization'];
	$phone = $_POST['phone'];
	$twitter = $_POST['twitter'];
	$neighborhood = $_POST['neighborhood'];
	
	if (($_POST['first_name'] != '') && ($_POST['last_name'] != '') && ($_POST['organization'] != ''))
	{
	create_lead($first_name, $last_name, $organization, $phone, $twitter, $neighborhood, $oauth);
	}
?>
 
<?php
}
?>
<html>
    <head>
        <title>HarvesTable</title>
			 <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi"/>
			<link type="text/css" rel="stylesheet" href="css/shCoreEclipse.css"/>

			<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	   
	   
	       <link href='https://fonts.googleapis.com/css?family=Lobster|Lato|Roboto' rel='stylesheet' type='text/css'>
		   
		   <!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

			<!-- Optional theme -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		   
			<link rel="stylesheet" href="css/leaflet.css" />
			<script src="js/leaflet.js"></script>	
		  <style>
		  
		  html, body { margin: 0; height: 100%; }
			#map img, #map_canvas a { -webkit-touch-callout: none; -webkit-tap-highlight-color: rgba(0,0,0,0); -webkit-text-size-adjust: none; -webkit-user-select: none; -moz-user-select: none; }
			#map{width:100%;position:absolute;top:50px;bottom:0px;left:0;right:0;}
			
			.popover   {
			background-color: #e74c3c;
			color: #ecf0f1;
			width: 120px;
			}
			.popover.right .arrow:after {
			border-right-color: #e74c3c;
			}
			
			.navbar-brand{
				font-family: 'Lobster', cursive;
				font-size: 180% !important;
				color: #FF9933 !important;
			}
			.modal-logo{
				font-family: 'Lobster', cursive;
				font-size: 200% !important;
				color: #FF9933 !important;
				text-align: center;
			}
			.modal-intro{
				font-family: 'Roboto', sans-serif;
				font-size: 120% !important;
				text-align: center;
			}
			.modal-body p{
				font-family: 'Roboto', sans-serif;
				font-size: 110% !important;
			}
			.sign-up-glyph {
			
			}
			.navbar-nav,control-label{
			   font-family: 'Roboto', sans-serif;
			}
			.modal-title,.control-label{
				font-family: 'Roboto', sans-serif;
			}
			.modal-header p{
				font-family: 'Roboto', sans-serif;
			}
			.overlay {
			width: 300px;
			position:absolute; 
			padding: 6px 8px;
			font: 14px/16px Arial, Helvetica, sans-serif;
			color: #223;
			background-color:rgba(255,255,255,0.8);
			box-shadow: 0 0 15px rgba(0,0,0,0.2);
			border-radius: 5px;
		}
		.title {
		width: 350px;
			top:60px;
			right:8px;
			font-family: 'Roboto', sans-serif;
		}
			.green {
				color:green;
			}
			
			.red{
				color:red;
			}
		  </style>
		 
    </head>
    <body>

	
	
	 <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	 
      <div class="container">
	 
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">HarvesTable</a>
        </div>
		
		
		
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#signUp" role="button" class="btn btn-custom" data-toggle="modal">Sign up&nbsp;&nbsp;<span class="glyphicon glyphicon-circle-arrow-right sign-up-glyph" style="display:none;"></span></a></li>
			<li><a href="#learnMore" role="button" class="btn btn-custom" data-toggle="modal">Learn more</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
	
	
	<!-- Modal -->
    <div id="learnMore" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="learnMoreLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h1 class="modal-logo">HarvesTable</h1>
			<p class="modal-intro">Connecting just harvested fresh fruit with those that most need it.</p>
        </div>
        <div class="modal-body">
		<p>This site connects freshly harvested backyard fruit, that would otherwise go to waste, with organizations within the neighborhood that are feeding the hungry. If you're an organization like that (food bank, shelter, school lunch program, etc) and would like to be notified when we are harvesting in your area, please <a href="#signUp" role="button" data-toggle="modal">sign up</a>. </p>
		<p>You will get a text or a direct message from Twitter with a link to request the fruit that we are harvesting at that moment. No long forms to fill out, e-mailing or phone tag; just more healthy local fruit for those that you serve.</p>

        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->
	
	<!-- Modal -->
    <div id="signUp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Subscribe to neighborhood harvest notifications</h4>
			<br/>
			<p>Are we harvesting in your area right now? And you're an organization that could distrubute fresh fruit to those that need it most?  Sign up and we will tweet or text you when fruit is available in your neighborhood.</p>
        </div>
        <div class="modal-body">
		<form class="form-horizontal" name="commentform" method="post" action="index.php">
			<div class="form-group">
				<label class="control-label col-md-4" for="first_name">First Name</label>
				<div class="col-md-6">
					<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4" for="last_name">Last Name</label>
				<div class="col-md-6">
					<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4" for="last_name">Organization</label>
				<div class="col-md-6">
					<input type="text" class="form-control" id="organization" name="organization" placeholder=""/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4" for="phone">SMS Number</label>
				<div class="col-md-6">
					<input type="text" class="form-control" id="phone" name="phone" placeholder="For text alerts"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-4" for="phone">Twitter Handle</label>
				<div class="col-md-6">
				
				<div class="input-group">
					<span class="input-group-addon">@</span>
					<input type="text" class="form-control" id="twitter" name="twitter" placeholder="For Twitter DM alerts"/>
					</div>
					<br/>
					<a href="https://twitter.com/urbanfreshfruit" class="twitter-follow-button" data-show-count="false">Follow @urbanfreshfruit</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
			</div>
			<!--
			<div class="form-group">
				<label class="control-label col-sm-4" for="dropoff">Location</label>
				<div class="col-sm-6">
					<span id="location_status" class=""></span>
				</div>
			</div>
			-->
			<!--<script src="js/geoPosition.js" type="text/javascript" charset="utf-8"></script>
			<script type="text/javascript">
			if(geoPosition.init()){
				//geoPosition.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
			}
			else{
				document.getElementById('location_status').style.display = 'block';
				document.getElementById('location_status').innerHTML = 'Your location not available';
				var d = document.getElementById("location_status");
				d.className = "red";
			}
			function success_callback(p)
			{
				var latitude = parseFloat( p.coords.latitude ).toFixed(2);
				var longitude = parseFloat( p.coords.longitude ).toFixed(2);
				document.getElementById('lat').value = latitude;
				document.getElementById('long').value = longitude;
				
				//document.getElementById('latitude').innerHTML = '<span class="information">Latitude:</span>' + latitude;
				//document.getElementById('longitude').innerHTML = '<span class="information">Longitude:</span>' + longitude;	
				document.getElementById('location_status').style.display = 'block';
				document.getElementById('location_status').innerHTML = 'Success! We have your location.';
				
				
				var d = document.getElementById("location_status");
				d.className = "green";
			}
			function error_callback(p)
			{
				document.getElementById('location_status').style.display = 'block';
				document.getElementById('location_status').innerHTML = p.message;	
				var d = document.getElementById("location_status");
				d.className = "red";
			}	
			</script>	
			-->
			<div class="form-group">
				<label class="control-label col-md-4" for="phone">Neighborhood</label>
				<div class="col-md-6">
				 <select class="form-control" name="neighborhood">
				  <option value="Ballard">Ballard</option>
				   <option value="South Seattle">South Seattle</option>
				  <option value="West Seattle">West Seattle</option>
				</select> 	
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-10">
					<button type="submit" value="Submit" class="btn btn-custom pull-right btn-success" id="send_btn">Send</button>
				</div>
			</div>
			<input type="hidden" id="lat" name="lat"/>
			<input type="hidden" id="long" name="long"/>
		</form>
        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->
	  
      <div class="starter-template">
	  
       <div id="map"></div>
	   <div class="overlay title">You are viewing real time harvest data. Are you an organization feeding the hungry? <a href="#signUp" role="button" data-toggle="modal">Sign up</a> to get some of the fruit coming from your neighborhood!</div>
      </div>

    </div><!-- /.container -->
	
	
	<script type="text/javascript" src="js/shCore.js"></script>
	<script type="text/javascript" src="js/shBrushJScript.js"></script>
	
	<script type="text/javascript" src="js/webgl-heatmap.js"></script>
	<script type="text/javascript" src="js/webgl-heatmap-leaflet.js"></script>
	<script src="js/kml.js"></script> 
	<script type="text/javascript">
	
	var baseURL = 'http://{s}.tiles.mapbox.com/v3/i8flan.jo1h0k21/{z}/{x}/{y}.png';
    
	var base = L.tileLayer(baseURL, { 
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="http://mapbox.com">Mapbox</a>, Heatmap by Florian Bosch (@pyalot), leaflet heatmap plugin by Ursudio',
		maxZoom: 18
		});
    
	
	var map = L.map('map', {layers: [base]}).setView([47.5990333, -122.3320708], 12);

	map.scrollWheelZoom.disable();
    
	L.control.scale().addTo(map);
	
	//custom size for this example, and autoresize because map style has a percentage width
	var heatmap = new L.TileLayer.WebGLHeatMap({size: 1000}); 
	
	//load in the points from Salesforce
	var dataPoints =[<?php show_harvests($oauth);?>[47.5446267,-122.3852719]];
	
	for (var i = 0, len = dataPoints.length; i < len; i++) {
		var point = dataPoints[i];
		heatmap.addDataPoint(point[0],
			 point[1],
			 50);
	}


	
	map.addLayer(heatmap);
	
	SyntaxHighlighter.all();
	
	</script>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
$('#send_btn').popover({content: 'Thank You'},'click');	
</script>
	</body>
</html>
