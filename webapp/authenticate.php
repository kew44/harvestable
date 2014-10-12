<?php
if ((!isset($access_token) || $access_token == "") && (!isset($instance_url) || $instance_url == "")) {
	//no access to tokens, check the db to see if we have them already for this environment
	$app_instance = REDIRECT_URI;
	$query = "SELECT * FROM oauth WHERE app_instance = '$app_instance' LIMIT 1"; 
	$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
	
	//they're in the db, grab them and set the session vars and the local vars
	if (pg_num_rows($rs) == 1) {
		while ($row = pg_fetch_row($rs)) {
			
		  $_SESSION['access_token'] = $row[0];
		  $_SESSION['instance_url'] = $row[1];
		  
		  $access_token = $_SESSION['access_token'];
		  $instance_url = $_SESSION['instance_url'];
		  
		  //echo "$row[0] $row[1]\n";
		}
	}else{
		//not in the db, we need to authorize this instance
		header('Location: oauth.php');
	}
	pg_close($con); 
}else{
	//the vars exist.  Set the session vars.  
	$_SESSION['access_token'] = $access_token;
	$_SESSION['instance_url'] = $instance_url;

}

?>