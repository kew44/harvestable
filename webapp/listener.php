<?php
session_start();
require_once('lib/twitteroauth.php');
require_once('lib/twilio/Services/Twilio.php');
require_once('config.php');
require_once 'inc/db_con.php';
require_once('inc/functions.php');
require_once 'authenticate.php';

// Set twitter keys
$consumerKey = '4jcLU3bRF9IkZTz8wb5vl9d0l';
$consumerSecret = 'lNFhDYrCnClI66Wfe4g5yy0DHl7OFlCLWNtKJEjIJCBtsXyqBP';
$accessToken = '2852450720-7VNz8CIUtbmlimDbskcNMirM6UlfWRHOgmNGQnx';
$accessTokenSecret = 'LR1LqRBLYaCcpJHLQ4rTe5lT2YrSDdDjSQzCbKvh92fAf';

$body = file_get_contents('php://input');

$response = get_harvest_details($body, $instance_url, $access_token);

echo "<pre>";
print_r ($response);
echo "</pre>";

foreach($response as $key=>$value){
	foreach($value as $k => $v){
	  if ($k == 'Pounds__c') {
			$pounds = $v;
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

$response = get_leads_to_alert($hood, $instance_url, $access_token);

echo "<pre>";
print_r ($response);
echo "</pre>";

$response = $response['records'];

$sms_body = $pounds . " lbs of " . $fruit_type . " available in " . $hood . " today.  Want some?";
$tweet_body = $sms_body;
$at_mentions = "";
$short_url = "";

foreach($response as $key=>$value){
	foreach($value as $k => $v){
		if ($k == 'Id') {
			$id = $v;
			$url_to_shorten = "https://secure-earth-8932.herokuapp.com/need.php?HarvestId=" . $body;
			$short_url = shortenUrl($url_to_shorten);
		}elseif ($k == 'MobilePhone') {
			//echo "Send to:" . $v . "<br>";
			$send_sms_body = $sms_body . " " . $short_url;
			echo $id;
			echo $short_url;
			echo $send_sms_body;
			sendSMS($v, $send_sms_body);
		}elseif ($k == 'Twitter__c') {
		    // Create object
			//$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
			// Send a direct message
			//$options = array("screen_name" => $v, "text" => $tweet_body);
			//$tweet->post('direct_messages/new', $options);
			echo "\n DM sent to: " . $v . "\n";
			//$at_mentions = $at_mentions . " @" . $v;
			$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
			$tweet_body = $tweet_body . $short_url;
			$response = $tweet->post('direct_messages/new', array('text' => $tweet_body, 'screen_name' => $v));
			
			var_dump($response);
		}
		
		
  }
  
  
}

?>