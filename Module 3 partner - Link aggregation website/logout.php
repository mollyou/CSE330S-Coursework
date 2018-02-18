<?php
session_start();
require 'database.php';

    //check to make sure the user is logged in
        //if they are logged in, log them out
        //if they are not logged in, tell them to log in so they can log out
?>
<!DOCTYPE html>
    <html>
        <head>
            <title> </title>
        </head>
        <body>

    <?php
	session_start();
	//must remove session variables and destroy it, return to main page
	session_unset();
	//destroy the session.  leave no survivors
	session_destroy();
	//return the user to the homepage
	header('Location: home.php');
?>
        
        </body>
</html>