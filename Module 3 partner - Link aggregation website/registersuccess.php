<?php
session_start();
require 'database.php';

//Tell the user that the resistration was a success, and redirect them to the login page
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Register Success</title>
        </head>
        <body>
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <h1>Thank you for registering</h1><br>
            You will be returned to the login page shortly.<br><br>
            If you are not automatically redirected, please click the button below.<br>
            <?php
            //redirect to login
                sleep(5);
                header("Location: login.php");
           ?>
           <!--option to manually go back to the login page-->
                <form action = "login.php">
                    <input type="submit" value="Login">
                </form>  
        </body>
</html>