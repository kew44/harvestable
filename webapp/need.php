<?php
session_start();
require_once 'inc/functions.php';
require_once 'config.php';
require_once 'inc/db_con.php';

require_once 'oauth.php';

$oauth = new oauth(CLIENT_ID, CLIENT_SECRET, CALLBACK_URL, LOGIN_URL);
//$oauth->auth_with_code();
$oauth->auth_with_password(USERNAME, PASSWORD, 120);


if (!empty($_GET['HarvestId']))
{
$HarvestID = $_GET['HarvestId'];

$response = get_harvest_details($HarvestID, $oauth);

//echo "<pre>";
//print_r ($response);
//echo "</pre>";

foreach($response as $key=>$value){
	foreach($value as $k => $v){
	  if ($k == 'Pounds__c') {
			$pounds = $v;
	  }
	  if ($k == 'Availability__c') {
			$availability = $v;
	  }
	  if ($k == 'Fruit_Type__c') {
			$fruit_type = $v;
	  }
	  if ($k == 'Date_Available__c') {
			$date_available = $v;
	  }
	  if (is_array($v) == 1){
	  foreach($v as $kn => $vn){
		  if ($kn == 'Neighborhood__c') {
			$hood = $vn;
		  }
		 
	   } 
	}
  }
}

}elseif (!empty($_POST['dropoff-from'])){

	//$response = insert_request('a00o0000003oLq0', $instance_url, $access_token);

}


//echo "<pre>";
//print_r ($response);
//echo "</pre>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
			.modal-logo,h1{
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
			.modal-title,.control-label,.thanks{
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
		.jumbotron{
			text-align:center;
		}
		  </style>
    </head>
	
	
	


    <div class="container">

		<div class="row">
		
	<?php if (empty($_POST['dropoff-to'])){ ?>
	
		  <div class="col-sm-6  col-sm-offset-3">
			<!-- Main component for a primary marketing message or call to action -->
			  <div class="jumbotron">
				<h1>HarvesTable</h1>
				<br/>
				<p><?php echo $pounds?> lbs of <?php echo $fruit_type?> are available at <?php echo $availability?> today in your neighborhood.  Want some?</p>
				
			  </div>
			<p>Great! Fill out the amount you would like and dropoff timing below.</p>
			<form class="form-horizontal" name="commentform" method="post" action="need.php">
			<div class="form-group">
				<label class="control-label col-sm-4" for="pounds">Pounds requested</label>
				<div class="col-sm-6">
					<input type="number" class="form-control" id="lbs" name="lbs" placeholder=""/>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-4" for="dropoff">Dropoff window</label>
				<div class="col-sm-6">
				
					<div class="input-group">
					<span class="input-group-addon">from</span>
					<input type="time" class="form-control" id="dropoff-from" name="dropoff-from" placeholder="Time"/>
					<span class="input-group-addon">to</span>
					<input type="time" class="form-control" id="dropoff-to" name="dropoff-to" placeholder="Time"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10" style="text-align:right;">
					<button type="submit" value="Submit" class="btn btn-success" id="send_btn">Submit</button>
				</div>
			</div>
		</form>
			
			
		<?php }else{ ?>
		
		 <div class="col-sm-6  col-sm-offset-3">
			<!-- Main component for a primary marketing message or call to action -->
			  <div class="jumbotron">
				<h1>HarvesTable</h1>
			  </div>
			<p></p>
			<p class="thanks">Thanks! We'll be in touch.</p>

		<?php } ?>
			
		 </div>
</div>

</div> <!-- /container -->

	
	

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

	</body>
</html>
