
<!DOCTYPE HTML>
<html>
	<head>
			<title>File Deletion</title>
	</head>
	
<body><?php
    session_start();
	
	//get the file name from the session variable initialized in DirectoryMAin.php
	$deletionFileName=$_SESSION['fileD'];
			//Test code
				//if(isset($deletionFileName))
				//{
				//	echo $deletionFileName;
				//}
	
	//get directory of file to be deleted
	$target_dir = $_SESSION["userdir"];
	//clear target_file so we don't delete the wrong file
	$target_file = "";
	//get target_file to have the target directory and the name of the file
	$target_file = $target_dir . $deletionFileName;

	//$target_file = $target_dir . $_FILES["fileToDelete"]["name"];
	//$uploadOk = 1;
	// Check if file already exists
	if (is_file($target_file) && file_exists($target_file))
	{
		if(unlink($target_file))
		{
			echo "File deleted successfully. <br>";
		}
		
	}
	else {
		echo "Error <br>";
	}
?>


<form action="DirectoryMain.php" class="form-inline">
	<input type="submit" value="Go Back" />

</form>

</body>
</html>