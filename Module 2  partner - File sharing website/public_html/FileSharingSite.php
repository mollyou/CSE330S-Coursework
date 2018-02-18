<?php
session_start();
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Ultra Secure File Sharing Site (very safe)</title>

        </head>
        <body>
				<img src="../visuals/logo.jpg" alt="very good logo.  paid graphic designer very much for it." />
             <form action = "FileSharingSite.php" method = "GET">
                <label>Please enter username:  <input type="text" name="user" value="" /></label>
                <input type = "submit" name="login" value="login" />
		<input type="submit" name="register" value="register" />
             </form>
             
			<?php
                //user enters their username via above html form
                //set user-inputted username into the variable $user when the submit button is pushed
				$user = "";
				if (isset($_GET['user'])){
				    if (isset($_GET['login'])){
				    $user = $_GET['user'];
                
					//open the userlist
					$userlist = fopen("../private/users.txt", "r");
					
					//go through the userlist line-by-line and compare the inputted username with usernames on the list
					while( !feof($userlist) )
					{
						//if a match is found, bring the user to the main directory.
						if ($user == trim(fgets($userlist)) && $user != "")
						{
							$failed_attempt=false;
							$_SESSION["user"] = $user;
							header("Location: DirectoryMain.php");
							break;
						}
						else
						{
						$failed_attempt=true;
						}
					}
					//close the userlist file
					fclose($userlist);
					
					
					//If failed login, ask the user for a valid username
					if($failed_attempt==true)
					{
						printf("Please enter a valid username");
					}		
				    }
				    else { //else user is trying to register for a new name.
				        $user = $_GET['user'];
					$handle = fopen('../private/users.txt', 'r');
					$valid = false; // init as false
					while (($buffer = fgets($handle)) !== false) {
    						if (strpos($buffer, $user) !== false) {
        						$valid = TRUE;
        						break; // Once you find the string, you should break out the loop.
    						}      
					}
					fclose($handle);

					if($valid==true){
						printf("Sorry, that username is already taken. Please try again.");
					
					}
					else { //we have a new user!
						$userlist = fopen("../private/users.txt", "a");
						fwrite($userlist, "\n". $user);
						fclose($userlist);
						//now we need to give them a directory
						$path = "../private/".$user;
						mkdir($path);
						$_SESSION["user"]=$user;
						header("Location: DirectoryMain.php");
						break;
					}
				    }			
				}
                //close the php tag
                ?>
        </body>
    </html>
