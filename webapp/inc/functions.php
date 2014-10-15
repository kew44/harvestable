<?php

function show_harvests($oauth) {

	$query = "SELECT Account__r.Location__latitude__s,Account__r.Location__longitude__s FROM Harvest__c WHERE Harvest__c.CreatedDate < NEXT_N_DAYS:14";
	$url = $oauth->instance_url . "/services/data/v24.0/query?q=" . urlencode($query);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth " . $oauth->access_token));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$response = json_decode(curl_exec($curl), true);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
    die("<h1>Curl Error</h1><p>URL : " . $url . "</p><p>Status : " . $status . "</p><p>response : error = " . $response['error'] . ", error_description = " . $response['error_description'] . "</p><p>curl_error : " . curl_error($curl) . "</p><p>curl_errno : " . curl_errno($curl) . "</p>");
	}

    //$json_response = curl_exec($curl);
    curl_close($curl);
    //$response = json_decode($json_response, true);
    //$total_size = $response['totalSize'];
	
	$response = $response['records'];
	
	
	foreach($response as $key=>$value){
		foreach($value as $k => $v){
		  if (is_array($v) == 1){
		  foreach($v as $kn => $vn){
			  if ($kn == 'Location__Latitude__s') {
				if ($vn != ''){
				$lat = $vn;
				echo "[" . $lat . ",";
				}
			  }
			  if ($kn == 'Location__Longitude__s') {
			    if ($vn != ''){
				$long = $vn;
				echo $long . "],";
				}
			  }
		   } 
		}
	  }
	}
}

function create_lead($first_name, $last_name, $organization, $phone, $twitter, $neighborhood, $oauth) {
    $url = $oauth->instance_url . "/services/data/v24.0/sobjects/Lead/";
    $content = json_encode(array("FirstName" => $first_name,"LastName" => $last_name,"MobilePhone" => $phone,"Twitter__c" => $twitter,"Notify_Neighborhood__c" => $neighborhood,"Company" => $organization,"LeadSource" => "Harvest Notify"));

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth " . $oauth->access_token));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$response = json_decode(curl_exec($curl), true);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
    die("<h1>Curl Error</h1><p>URL : " . $url . "</p><p>Status : " . $status . "</p><p>response : error = " . $response['error'] . ", error_description = " . $response['error_description'] . "</p><p>curl_error : " . curl_error($curl) . "</p><p>curl_errno : " . curl_errno($curl) . "</p>");
	}
    
    //echo "HTTP status $status creating lead<br/><br/>";

    curl_close($curl);

    //$response = json_decode($json_response, true);
	//$response = $response['records'];
    $id = $response["id"];

    //echo "New record id $id<br/><br/>";

    return $id;
}

function sendSMS($to_sms_num, $message_text)
{	
$account_sid = 'AC3e3f22c35645d99153c32392db9bdabe';
$auth_token = '8b97116e9407ff7946ab3118e0925124';
$from_sms_num = '2064960618';

$client = new Services_Twilio($account_sid, $auth_token);
$message = $client->account->messages->sendMessage(
  $from_sms_num,
  $to_sms_num,
  $message_text
);
}


function create_contact($first_name, $last_name, $organization, $phone, $twitter, $neighborhood, $instance_url, $access_token) {
    $url = "$instance_url/services/data/v20.0/sobjects/Contact/";
	
    $content = json_encode(array("FirstName" => $first_name,"LastName" => $last_name,"MobilePhone" => $phone,"Twitter__c" => $twitter,"Notify_Neighborhood__c" => $neighborhood,"Organization__c" => $organization));

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 201 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    
    echo "HTTP status $status creating contact<br/><br/>";

    curl_close($curl);

    $response = json_decode($json_response, true);

    $id = $response["id"];

    echo "New record id $id<br/><br/>";

    return $id;
}

function get_leads_to_alert($neighborhood, $instance_url, $access_token) {
    $query = "SELECT Id, MobilePhone, Notify_Neighborhood__c,twitter__c FROM Lead WHERE Notify_Neighborhood__c = '$neighborhood'";
	
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    //$total_size = $response['totalSize'];

	return $response;

}

function get_harvest_details($Id, $instance_url, $access_token) {
    $query = "SELECT Id, Date_Available__c, Fruit_Type__c, Pounds__c, Account__r.Neighborhood__c,Availability__c FROM Harvest__c WHERE Id = '$Id'";
	
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    //$total_size = $response['totalSize'];

	$response = $response['records'];
	
	return $response;

}

function shortenUrl($longurl)
{
	// This is the URL you want to shorten
	$longurl = htmlspecialchars($longurl);

	// Get API key from : http://code.google.com/apis/console/
	$apiKey = 'AIzaSyBWcV2XS6JS6Z1Ref9pc9zU1Pa_P5u3Lf0';

	$postData = array('longUrl' => $longurl, 'key' => $apiKey);
	$jsonData = json_encode($postData);

	$curlObj = curl_init();

	curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($curlObj, CURLOPT_POST, 1);
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

	$response = curl_exec($curlObj);

	// Change the response json string to object
	$json = json_decode($response);

	curl_close($curlObj);
	
	return $json->id;
	 
}

function insert_request($harvest_id, $instance_url, $access_token) {
    $query = "INSERT INTO Request__C (Harvest__c, Organization__c) VALUES ('$harvest_id','This One')";
	
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    //$total_size = $response['totalSize'];

	return $response;

}
?>