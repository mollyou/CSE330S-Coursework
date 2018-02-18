
            <?php
	session_start();
	require 'database.php';
	header("Content-Type: application/json"); //sending json response
            //make sure that the variables (username, password entry 1, and password entry 2) are set
            //filter the input to strings
                $user_input = (string) $_POST['user'];
                $password = (string) $_POST['password'];

            //check to see if the username is available
            //To do this, we're going to count up the rows that have the same username as the one the user entered
            //If the username is not taken, $cnt will equal 0
                //use a prepared statement
                $stmt = $mysqli->prepare("SELECT userid, password FROM users WHERE username=?");
			
			//exit if query prep fails
			if(!$stmt)
			{
				echo json_encode(array(
					"success" => false,
					message => "Query Prep Failed"
				));
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
                        //if the passwords match, attempt to add the user to the database
                        //salt the password
                        $pass_secure = crypt($password);
                        
                        //attempt to add the new user into the database
                        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                        
                        if(!$stmt){
                        	echo json_encode(array(
					"success" => false,
					message => "Query Prep Failed"
				));
				exit;
                        }
        
                        $stmt->bind_param('ss',$user_input, $pass_secure);
                        $stmt->execute();
                        $stmt->close();
                       echo json_encode(array(
					"success" => true
				));
                        //now redirect the user
			exit;
                    
                }
                else
                {
                    //if the desired username is not available, tell the user to pick another one
			echo json_encode(array(
				"success" => false,
				message => "Username is Not Available"
			));
			exit;
		}           
?>
