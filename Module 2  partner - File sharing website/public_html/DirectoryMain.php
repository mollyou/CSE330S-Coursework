<?php
session_start();
$user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html>
<!--WELCOME USER-->
	<head>
		<title>Super Duper Secure We Promise</title>
		<link rel="stylesheet" type="text/css" href="../visuals/stylesheet.css">
	</head>
	
	<body>
		<!--Welcome the user back with a cute image-->
		<br />
		<img src="../visuals/welcome.jpg" alt="I can't believe our graphic designer threw in this one for free!" />
		<br />

		<?php
			//print out the username in a welcome message
			printf("<p> Welcome back, %s </p>\n",
			htmlentities($user));
//END WELCOME USER
			?>

<!--FUNCTION:  File Upload-->
		<!--button for the user to upload their file-->
		<form action="upload.php" method="post" enctype="multipart/form-data">
			Select file to upload:
			<input type="file" name="fileToUpload" id="fileToUpload" />
			<input type="submit" value="Upload" name="submit" />
		</form>
		<br />
<!--END FUNCTION:  File Upload-->
<?php
//FUNCTION:  File Deletion
			//store the path to the user's directory in a session variable, then pass it to the $dir variable
			$_SESSION["userdir"] = "../private/" . $user . "/";
			$dir = $_SESSION["userdir"];
			// Open the user's directory, and read its contents
			if (is_dir($dir))
			{
			  if ($dh = opendir($dir))
			  {
				echo "Your Files:  ";
				//print the User's files in a table
				?>
				<br>
					<table>
						<!--Table heading-->
						<tr>
							<th><b>File Name</b></th>
							<th><b>Open?</b></th>
							<th><b>Delete?</b></th>
						</tr>
						<!--Give each file a radio button that, when pushed, marks the file for deletion-->
						<form action="DirectoryMain.php" method = "POST">
				<?php
					//print each file in a table row, and put the checkbox in the next column
					//loop goes through each file in the user's directory
					while (($file = readdir($dh)) !== false)
					{
						//for each file, print the file name in the first col of the row
						//put a radio button in the second col of the row
					  if (($file != "..") && ($file != "."))
					  {
						echo sprintf("<tr><td>%s</td>
									<td><input type=\"radio\" name=\"fileToOpen\" value=\"$file\" /></td>
									 <td><input type=\"radio\" name=\"fileToDelete\" value=\"$file\" /> </td></tr>",
									 htmlentities($file));
						}
					}
				?>
					<tr><td><b>None</b>(don't delete any files)</td><td></td><td><input type = "radio" name="fileToDelete" value ="nothingtodelete"></td></tr>
					<!--End table-->
					</table>
				<?php
					//close the directory
					closedir($dh);				
				}
			}
			?>
				<!--Button to delete files selected-->
				<input type="submit" name="openButton" value="Open the Selected File" />
				<input type="submit" name="deleteButton" value="Delete the Selected Files" />
				</form>
				<br />
				<!--Test code to see if the array was filled with diles from the checklist-->
				<?php
				if(isset($_POST['fileToDelete']))
				{
					if($_POST['fileToDelete']==="nothingtodelete")
					{
						echo "Please select a file before clicking the above button.  You can't delete nothing";
					}
					//If the user tries to delete the None header, get snarky with them.
					else
					{
						$_SESSION["fileD"]=$_POST['fileToDelete'];
						header("Location: delete.php");	
					}
				}
				else if(isset($_POST['fileToOpen']))
				{
  					$file = $_SESSION['userdir'] . $_POST['fileToOpen'];
  					$filename = $_POST['fileToOpen'];
  					if (file_exists($file)) {
					    header('Content-Description: File Transfer');
					    header('Content-Type: application/octet-stream');
					    header('Content-Disposition: attachment; filename="'.basename($file).'"');
					    header('Expires: 0');
					    header('Cache-Control: must-revalidate');
					    header('Pragma: public');
					    header('Content-Length: ' . filesize($file));
					    readfile($file);
					    exit;
					}
				}
				?>
			<br />
		<!--Button for the user to delete their file via entering filename
		<form action="delete.php" class="form-inline">
			Select file to delete:
			<input type="text" name="fileToDelete" id="filetoDelete" />
			<input type="submit" value="Delete" name="submit" />
		</form>
		-->
<!--END FUNCTION:  File Deletion-->

<!--FUNCTION:  Share a File-->
		<?php
			//Go through the user's directory and populate a dropdown menu with the names of their files
			if (is_dir($dir))
			{
			  if ($dh = opendir($dir))
			  {
				?>
				
				<form action="DirectoryMain.php" method = "POST">
					<!--Label for the dropdown-->
				Share File:
					<!--The dropdown menu-->
				<select name = "dropdown">
					<!--Default option-->
				<option id ="dropdown" value="noshare">Select...</option>
					<!--Put each file in its own row in the dropdown-->
				<?php
					//print each file in a table row, and put the checkbox in the next column
					//loop goes through each file in the user's directory
					while (($file = readdir($dh)) !== false)
					{
						//for each file, print the file name in the dropdown
					  if (($file != "..") && ($file != "."))
					  {
						echo sprintf("<option value=\"$file\">%s</option>",
									 htmlentities($file));
						}
					}
					//close the directory
					closedir($dh);				
				}
			}
		?>
			</select><br />
			<!--Have user enter in the username of the person they want to share the file with-->
			<label>User to Share with: <input type="text" name = "userToShare"/></label>
			<!--Button for the person to submit-->
			<input type="submit" value="Share with selected user" />
		</form>
		
				<?php
				//check to make sure file and user to share were submitted correctly
				if(isset($_POST['userToShare']) && isset($_POST['dropdown']))
				{
						//IF the user fails to select a file to share, get snarky
						if($_POST['dropdown']==="noshare")
						{
							echo "Please choose a file to share.";
						}
						//else, check to make sure they entered a valid username
						else
						{
							$userShare = "";
							$userShare = $_POST['userToShare'];
							$failed_attempt=false;
                
							//open the userlist
							$userlist = fopen("../private/users.txt", "r");
					
							//go through the userlist line-by-line and compare the inputted username with usernames on the list
							while( !feof($userlist) )
							{
								//if a match is found, store user to share and file in a Session variable.
								//Direct user to the share.php page
								if ($userShare == trim(fgets($userlist)) && $userShare != "")
								{
									$failed_attempt=false;
									$_SESSION['userShare'] = $userShare;
									$_SESSION['fileShare'] = $_POST['dropdown'];
									header("Location: sharefile.php");
									break;
								}
								//if the username is invalid, say so
								else
								{
								$failed_attempt=true;
								}
							}
							//close the userlist file
							fclose($userlist);
					
							//If failed login, ask the user for a valid username
							if($failed_attempt == true)
							{
							printf("Please enter a valid username");
							}					
						}	
					}
				?>
<!--END FUNCTION:  File Share a File-->

<!--FUNCTION:  Logout -->
		<!--Button for the user to logout-->
		<br />
		<br />
		<form action="logout.php" class="form-inline">
			<input type="submit" value="Logout" />
		</form>
<!--END FUNCTION:  Logout -->
	</body>
</html>
