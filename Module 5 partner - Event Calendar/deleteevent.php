<?php
session_start();
require 'database.php';
header("Content-Type: application/json"); //JSON DERULO

if(!isset($_SESSION['user_id'])){
	exit;
}
else{
	$eventid = $_POST['eventid'];
	if($_SESSION['token'] !== $_POST['token']){
		die("Request forgery detected");}
	$stmt = $mysqli->prepare("DELETE FROM events WHERE eventid=?");
	if(!$stmt){
		echo json_encode(array("success"=>false));
		exit;
	}
	$stmt->bind_param('i',$eventid);
	$stmt->execute();
	$stmt->close();
	echo json_encode(array("success"=>true));
	exit;


}
?>
