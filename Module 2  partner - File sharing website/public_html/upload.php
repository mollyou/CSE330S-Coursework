<!DOCTYPE HTML>
<html>
	<head>
	<title>File Upload</title>	
	</head>
	
<body>
<?php
	session_start();
	//Get user directory from session variable
	$target_dir = $_SESSION["userdir"];
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	//tentatively give user permission to upload the file.  
	$uploadOk = 1;
	// Check if file already exists.  If so, stop user from uploading file
	if (file_exists($target_file)) {
		echo "The file already exists. ";
		$uploadOk = 0;
	}
	// Check file size.  If so, stop user from uploading file
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "Sorry, your file is too large. ";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded. ";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {	
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. <br>";
		} else {
		echo "Sorry, there was an error uploading your file. <br>";
		}
	}
?>

	<!--Button to return the user home-->
<form action="DirectoryMain.php" class="form-inline">
	<input type="submit" value="Go Back" />
</form>

</body></html>
