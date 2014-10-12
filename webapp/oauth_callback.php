<?php
require_once 'config.php';
require_once 'inc/db_con.php';

session_start();

$token_url = LOGIN_URI . "/services/oauth2/token";

//start new
$loginurl = "https://login.salesforce.com/services/oauth2/token";

$params = "grant_type=password"
. "&client_id=" . CLIENT_ID
. "&client_secret=" . CLIENT_SECRET
. "&username=" . USER_NAME
. "&password=" . PASSWORD;

$curl = curl_init($loginurl);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$json_response = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ( $status != 200 ) {
    die("Error: call to URL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
}

curl_close($curl);

echo $json_response;
//end new

$response = json_decode($json_response, true);

$access_token = $response['access_token'];
$instance_url = $response['instance_url'];

if (!isset($access_token) || $access_token == "") {
    die("Error - access token missing from response!");
}

if (!isset($instance_url) || $instance_url == "") {
    die("Error - instance URL missing from response!");
}

$_SESSION['access_token'] = $access_token;
$_SESSION['instance_url'] = $instance_url;

//save the access_token and instance_url for this environment
$app_instance = REDIRECT_URI;
$query = "SELECT * FROM oauth WHERE app_instance = '$app_instance' LIMIT 1"; 
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");

if (pg_num_rows($rs) == 1) {
	//environment exists, update the values
	while ($row = pg_fetch_row($rs)) {
	  $query = "UPDATE oauth SET access_token = '$access_token', instance_url = '$instance_url' WHERE app_instance = '$app_instance'"; 
	  $rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
	}
}else{
	//environment doesn't exist, insert values
	$query = "INSERT INTO oauth (access_token, instance_url, app_instance) VALUES ('$access_token','$instance_url','$app_instance')";
	$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
}

pg_close($con); 
header( 'Location: index.php' ) ;
?>
