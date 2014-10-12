<?php

define("LOGIN_URI", "https://login.salesforce.com");
define("USER_NAME", "hackathon@clinecollett.com");
define("PASSWORD", "89Spike!");

if ($_SERVER['SERVER_PORT'] == '8088')
{
	//on localhost
	define("CLIENT_ID", "3MVG9xOCXq4ID1uFNYuskejHj1yELlace24MuDa2sLdnTnuJGkiX0CNvdV0ERCNbm3l_I_qtN4eebbTCMDWxr");
	define("CLIENT_SECRET", "5297631002821366505");
	define("REDIRECT_URI", "https://localhost:8088/oauth_callback.php");
	
	define("PG_HOST", "localhost");
	define("PG_USER", "postgres");
	define("PG_PASS", "i8flan");
	define("PG_DB", "harvest");
}else{
	//on Heroku
	define("CLIENT_ID", "3MVG9xOCXq4ID1uFNYuskejHj1zJkdbe7O_pMz9.m72ykKk8em8d0CPfetf7FtDZPT3azrI8oTXP6Bukla1wZ");
	define("CLIENT_SECRET", "7912867588415216020");
	define("REDIRECT_URI", "https://secure-earth-8932.herokuapp.com/oauth_callback.php");
	
	define("PG_HOST", "ec2-54-197-239-171.compute-1.amazonaws.com");
	define("PG_USER", "kqifmckpzedvga");
	define("PG_PASS", "YxhTXs58MU9YXRBZHdszER1NBl");
	define("PG_DB", "d594k3545pn9a");
}

?>
