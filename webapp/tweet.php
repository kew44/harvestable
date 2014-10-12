<?php

// Include twitteroauth
require_once('lib/twitteroauth.php');

// Set keys
$consumerKey = '4jcLU3bRF9IkZTz8wb5vl9d0l';
$consumerSecret = 'lNFhDYrCnClI66Wfe4g5yy0DHl7OFlCLWNtKJEjIJCBtsXyqBP';
$accessToken = '2852450720-Ec7YyjY1jJEptE3N7p7aBy4XDNHtlkDq4P16mcb';
$accessTokenSecret = 'W4ghx0TcJQIfuvueYYu0ev09Ui2IUhy3o9lRRKX62jPzn';

// Create object
$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

// Set status message
$tweetMessage = 'This is another test tweet from php';

// Check for 140 characters
if(strlen($tweetMessage) <= 140)
{
    // Post the status message
    $tweet->post('statuses/update', array('status' => $tweetMessage));
}

echo $tweetMessage;

?>