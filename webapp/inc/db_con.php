<?php 
require_once 'config.php';

$host = PG_HOST;
$db = PG_DB;
$user = PG_USER;
$pass = PG_PASS;

$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
    or die ("Could not connect to server\n"); 

?>