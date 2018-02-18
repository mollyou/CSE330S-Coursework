<?php
session_start();
require 'database.php';
//Thank the user for their submission
?>
<!DOCTYPE html>
    <html>
        <head>
            <title> </title>
        </head>
        <body>
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <h1>Thank you for your submission!</h1><br>
            You will be returned to the homepage shortly.<br><br>
            If you are not automatically redirected, please click the button below.<br>
            <?php
                sleep(5);
                header("Location: submitresult.php");
           ?>
                <form action = "home.php">
                    <input type="submit" value="Return Home">
                </form>  
        </body>
</html>