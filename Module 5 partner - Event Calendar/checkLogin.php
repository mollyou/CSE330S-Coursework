<?php
session_start();
require 'database.php';

header("Content-Type: application/json");

//if user is logged in, send true. if not, send false

if(isset($_SESSION['user_id'])){
	echo json_encode(array("login" => true, "token" => $_SESSION['token']));
	exit;
}
else {
	echo json_encode(array("login" => false));
}
exit;








?>
