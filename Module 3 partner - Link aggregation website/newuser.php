<?php
session_start();
require 'database.php';
    //This is for a new uesr registering to the site for the first time
?>
<!DOCTYPE html>
    <html>
        <head>
            <title> User Registration</title>
        </head>
        <body>
    
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <h1>Welcome, new user!  Please fill out the form below to get started.</h1><br>
            <!--Get desired username and password from the user-->
            <form action = "newuser.php" method = "POST">
				<fieldset>
				<legend>User Information:</legend>	
                <!--Get desired username from the user-->
                Username:  <br>
				<input type="text" name="user" value="" /> <br> <br>
                <!--Get desired password from the user.  User must enter the same password twice-->
                Password:  <br>
				<input type="password" name="pass1" value="" /><br>
                Please enter your password again:  <br>
				<input type="password" name="pass2" value="" /><br><br>
                <!--Submit button-->
                <input type = "submit" name="reg" value="Register" />
				</fieldset>
            </form>
            
            <?php
            //make sure that the variables (username, password entry 1, and password entry 2) are set
            if(isset($_POST['user']) AND isset($_POST['pass1']) AND isset($_POST['pass2']))
            {
            //filter the input to strings
                $user_input = (string) $_POST['user'];
                $password1 = (string) $_POST['pass1'];
                $password2 = (string) $_POST['pass2'];

            //check to see if the username is available
            //To do this, we're going to count up the rows that have the same username as the one the user entered
            //If the username is not taken, $cnt will equal 0
                //use a prepared statement
                $stmt = $mysqli->prepare("SELECT userid, password FROM users WHERE username=?");
			
			//exit if query prep fails
			if(!$stmt)
			{
			printf("Query Prep Failed. Unable to see if username is taken. %s\n", $mysqli->error);
			exit;
			}
                //bind parameters
                $stmt->bind_param('s', $user_input);
                $stmt->execute(); 
                $stmt->bind_result($retuser,$retpassword);
				$stmt->fetch();

                //check to see if the returned count is zero
                if($retuser == 0 && $retpassword == 0)
                {
                    //if username is available, check to see that the passwords match
                    if(strcmp($password1, $password2) == 0)
                    {
                        //if the passwords match, attempt to add the user to the database
                        //salt the password
                        $pass_secure = crypt($password1);
                        
                        //attempt to add the new user into the database
                        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                        
                        if(!$stmt){
                        printf("Query Prep Failed.  Unable to register user. %s\n", $mysqli->error);
                        exit;
                        }
        
                        $stmt->bind_param('ss',$user_input, $pass_secure);
                        $stmt->execute();
                        $stmt->close();
                        
                        //now redirect the user
                        header("Location: registersuccess.php");
						exit;
                    }
                    else
                    {
                        //if the passwords to not match, tell the user to enter them again
                        echo ('Please make sure that your passwords match.');
                    }
                    
                }
                else
                {
                    //if the desired username is not available, tell the user to pick another one
                    echo ('Username is already taken');
                }
            }
           ?>
        </body>
</html>
