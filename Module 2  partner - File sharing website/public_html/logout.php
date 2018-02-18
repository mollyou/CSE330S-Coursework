<!DOCTYPE HTML>
<html>
	<head>
	</head>
<body>
<?php
	session_start();
	//must remove session variables and destroy it, return to main page
	session_unset();
	//destroy the session.  leave no survivors
	session_destroy();
	//return the user to the homepage
	header('Location: FileSharingSite.php');
?>

</body>
</html>