<?php
/* config section */
$db_connect = 'mysql:dbname=newsprint;host=127.0.0.1';
$db_user = 'root';
$db_pass = '';

/* end of config */

try {
	$db = new PDO($db_connect,$db_user,$db_pass);
}
catch (PDOException $e)
{
	echo 'Connection failed: '.$e->getMessage();
	die();
}
?>