<?php
session_start();
require 'database.php';

//This is the login screen
//From here, the user can enter in their username and password
        //For registed users, get username and password
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Login </title>
        </head>
        <body>
            
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <h1>Welcome back!  Please Log in below.</h1><br>

        <form action = "login.php" method = "POST">
            <fieldset>
                <legend>Login Info</legend>
                Username: <br>
                <input type="text" name="user" value="" /><br><br>
                Password: <br>
                <input type="password" name="pass_guess" value="" /><br><br>
                <input type = "submit" name="login" value="login" />
            </fieldset>
        </form>
<?php
    //check to make sure variables are set
    if(isset($_POST['user']) AND isset($_POST['pass_guess']))
    {
        //filter inputs
        $username = (string) $_POST['user'];
        $pwd_guess = (string) $_POST['pass_guess'];
           
        //prep query
        $stmt = $mysqli->prepare("SELECT COUNT(*), userid, username, password FROM users WHERE username=?");
            
           // Bind the parameter
           $stmt->bind_param('s', $username);
           $stmt->execute();
            
           // Bind the results
           $stmt->bind_result($cnt, $user_id, $user_name, $pwd_hash);
           $stmt->fetch();
           
           // Compare the submitted password to the actual password hash
           if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
               // Login succeeded!
               $_SESSION['user_id'] = $user_id;
               $_SESSION['user_name'] = $user_name;
               //Generate CSRF token
               $_SESSION['token'] = substr(md5(rand()), 0, 10);
               // Redirect to your target page
               printf("yay.  You will be redirected in 10 sec.");
               
               header("Location: home.php");
           }else{
               // Login failed; redirect back to the login screen
               printf("please check your username and password again");
           }
    }
    
           ?>
        </body>
</html>