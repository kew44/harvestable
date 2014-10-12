<?php
require_once 'inc/functions.php';
require_once 'config.php';
require_once 'inc/db_con.php';
$query = "DELETE from oauth"; 
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
?>
<a href="index.php">Done, go home</a>