<?php
session_start();
require 'database.php';
header("Content-Type: application/json"); //sending json response

if (!isset($_SESSION['user_id']))
{
	//echo json_encode(array(
	//	"success" => false,
	//	message => "User is Not Logged In"
	//));
	exit;
}
else
{
	$stmt = $mysqli->prepare("SELECT title,eventdatetime,tagname FROM events WHERE userid=?"); //get title, eventdatetime, tags
			
	//exit if query prep fails
	if(!$stmt)
	{
		exit;
	}
        //bind parameters
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute(); 
        $stmt->bind_result($title,$datetime,$tag);
	$i = 0;
	$events = array();
	$eventsperday = array();
	while($stmt->fetch()){
		$date = substr($datetime, 0, 10);
		$events[$i] = array();
		$events[$i]['title'] = htmlentities($title);
		$events[$i]['date'] = $date;
		$events[$i]['time'] = substr($datetime, 11, 8);
		$events[$i]['tag'] = htmlentities($tag);
		if(!isset($eventsperday[$date])){
			$eventsperday[$date] = 0;}
		$eventsperday[$date]++;
		$i++;
	}
	echo json_encode(array(
		"eventsperday" => $eventsperday,
		"events" => $events
		));
	$stmt->close();
	exit;

}
?>
