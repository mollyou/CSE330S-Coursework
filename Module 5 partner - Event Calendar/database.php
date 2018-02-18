<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'caladmin', 'calamityjane21', 'module5');
 
if($mysqli->connect_errno) {
	//printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>
