<?php
session_start();
require 'database.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Post</title>
	</head>
	<body>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />

		<?php


			if (isset($_POST['storyid'])){
				//check for token
				$storyid = $_POST['storyid'];
				if ($_SESSION['token'] !== $_POST['token']) {
					die("Request forgery detected"); }
				$stmt = $mysqli->prepare("select title,story_body from stories where storyid = ?");
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				} 
				$stmt->bind_param('i', $storyid);
				$stmt->execute();
				$stmt->bind_result($title,$story);
				$stmt->fetch();
				$stmt->close();
				if (isset($_POST['edited'])){
					$storybody = $_POST['storybody'];
					$title = $_POST['title'];
					$stmt = $mysqli->prepare("update stories set title=?,story_body= ? where storyid = ?");
					if(!$stmt){
						printf("Query Prep Failed: %s\n", $mysqli->error);
						exit;
					}
					$stmt->bind_param('ssi',$title,$storybody,$storyid);
					$stmt->execute();
					$stmt->close();

					$location = "Location: storydetails.php?storyid=" .  $storyid;
					header($location);

				}

			}

		?>
		<form action="editstory.php" method="POST">
			<fieldset>
			<legend>Edit Submission:</legend>
			 Title:<br>
                  	<input type="text" name="title" value="<?php echo $title;?>"><br>
                  	Description:<br>
			<textarea cols="50" rows="30" name="storybody"><?php echo $story;?></textarea><br>
			<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
			<input type="hidden" name="storyid" value="<?php echo $storyid;?>" />
			<input type="hidden" name="edited" value="true"/>
			<input type="submit" value="Submit!">
			</fieldset>
		</form>

	</body>
</html>
