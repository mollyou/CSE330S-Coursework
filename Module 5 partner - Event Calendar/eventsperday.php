<?php
session_start();
require 'database.php';
header("Content-Type: application/json");

//echo (json_encode(array(
//	"success" => false));
//exit;

if (!isset($_SESSION['user_id']))
{
	echo json_encode(array(
		"success" => false
	));
	exit;
}
else
{
	$year = (string) $_POST['year'];
	$month = (string) $_POST['month'];
	$day = (string) $_POST['day'];
	if ($_POST['day'] < 10){
		$day = "0" . $_POST['day'];}
	$fulldate = $year . "-" . $month . "-" . $day . "%";

	$stmt = $mysqli->prepare("SELECT title, eventdatetime, tagname FROM events WHERE userid = ? AND eventdatetime LIKE ?"); //get title, eventdatetime, tags
			
	//exit if query prep fails
	if(!$stmt)
	{
		echo json_encode(array(
			"success" => false));
		exit;
	}
        //bind parameters
        $stmt->bind_param('is', $_SESSION['user_id'], $fulldate);
        $stmt->execute(); 
        $stmt->bind_result($title,$datetime,$tag);
	$i = 0;
	$events = array();
	while($stmt->fetch()){
		$event = array("title" => htmlentities($title),
					"time" => substr($datetime, 11, 8),
					"tag" => htmlentities($tag));
		array_push($events, $event);
		/*
		$events[$i] = array();
		$events[$i]['title'] = htmlentities($title);
		$events[$i]['time'] = substr($datetime, 11, 8);
		$events[$i]['tag'] = htmlentities($tag);
		$i++;
		*/
		$i++;
	}
	echo json_encode(array(
		"success" => true,
		"eventnum" => $i,
		"events" => $events
		));
	$stmt->close();
	exit;

}




?>
