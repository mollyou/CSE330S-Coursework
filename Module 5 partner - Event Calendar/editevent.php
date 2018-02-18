
<?php
session_start();
require 'database.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user_id']));
{
	//lol
	exit;
}
else
{
	$eventid = $_POST['eventid'];
	if ($_SESSION['token'] !== $_POST['token']) {
		die("Request forgery detected"); }
	$event_title = $_POST['title'];
	$event_datetime = $_POST['datetime'];
	$event_tag = null;
	if (isset($_POST['tag'])) {
		$event_tag = $_POST['tag'];
	}
	$stmt = $mysqli->prepare("update events set title=?,eventdatetime=?,tagname=? where eventid=?");
	if(!$stmt){
		echo json_encode(array("success"=>false));
		exit;
	}
	$stmt->bind_param('sssi',$event_title,$event_datetime,$event_tag,$eventid);
	$stmt->execute();
	$stmt->close();
	echo json_encode(array("success"=>true));
	exit;	


}




?>
