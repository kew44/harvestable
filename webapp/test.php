<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once "config.php";
require_once "oauth.php";

$oauth = new oauth(CLIENT_ID, CLIENT_SECRET, CALLBACK_URL);
$oauth->auth_with_code();

$query = "SELECT Name FROM Account";
$url = $oauth->instance_url . "/services/data/v29.0/query?q=" . urlencode($query);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth " . $oauth->access_token));
$response = json_decode(curl_exec($curl), true);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ( $status != 200 ) {
    die("<h1>Curl Error</h1><p>URL : " . $url . "</p><p>Status : " . $status . "</p><p>response : error = " . $response['error'] . ", error_description = " . $response['error_description'] . "</p><p>curl_error : " . curl_error($curl) . "</p><p>curl_errno : " . curl_errno($curl) . "</p>");
}
curl_close($curl);
return($response);

//echo $response;

echo "<pre>";
print_r ($response);
echo "</pre>";

?>