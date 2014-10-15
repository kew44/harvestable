<?php

define("LOGIN_URL", "https://login.salesforce.com");
define("USERNAME", "hackathon@clinecollett.com");
define("PASSWORD", "89Spike!");
define('CACHE_DIR', 'oauth/cache');


if ($_SERVER['SERVER_PORT'] == '8097')
{
	//on localhost
	define("CLIENT_ID", "3MVG9xOCXq4ID1uFNYuskejHj1wxHz3g8riLc6z6yGbfJPf_uNbPcV7s_aSv.pd129og7rZCc.ADZ3qww7N7H");
	define("CLIENT_SECRET", "4841417032545777056");
	define("CALLBACK_URL", "https://localhost:8097");
	define("APP_URL", "https://localhost:8097");
	
	define("PG_HOST", "localhost");
	define("PG_USER", "postgres");
	define("PG_PASS", "i8flan");
	define("PG_DB", "harvestable");
	
}else{
	//on Heroku
	define("CLIENT_ID", "3MVG9xOCXq4ID1uFNYuskejHj19ODkleSu2HSdFm7C2RvaSo9XwaeV0RaNo4ylxh0CFGUWmmY0Vj5MTt8FAYz");
	define("CLIENT_SECRET", "8858843726296151822");
	define("CALLBACK_URL", "https://shrouded-reef-4254.herokuapp.com/index.php");
	define("APP_URL", "https://shrouded-reef-4254.herokuapp.com");
	
	define("PG_HOST", "ec2-54-197-239-171.compute-1.amazonaws.com");
	define("PG_USER", "kqifmckpzedvga");
	define("PG_PASS", "YxhTXs58MU9YXRBZHdszER1NBl");
	define("PG_DB", "d594k3545pn9a");
}

?>
