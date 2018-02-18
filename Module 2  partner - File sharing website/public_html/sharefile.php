<!DOCTYPE HTML>
<html>
	<head>
	<title>File Share</title>	
	</head>
	
<body>
<?php
    session_start();
    
	//set target directory to copy file (share) to
    $target_directory = "../private/" . $_SESSION['userShare'] . "/" . $_SESSION['fileShare'];
    //$origin_directory = $_SESSION['userdir'];
    //$origin_file = $_SESSION['fileShare'];
	//set origin of the file to be shared
    $origin = $_SESSION['userdir'] . $_SESSION['fileShare'];
    
    //echo $origin;
    //echo "\n";
    //echo $target_directory;
    
	//attempt to copy file to target user's directory
	//if success, tell the user
    if(copy($origin, $target_directory))
    {
        printf("%s shared successfully <br />",
			   htmlentities($_SESSION['fileShare']));
    }
	//if the copy was a failure, tell the user
    else
    {
        printf("Error.  %s not shared <br />",
			   htmlentities($_SESSION['fileShare']));
    }
?>

	<!--Give the user a button to return back to DirectoryMain.php-->
<form action="DirectoryMain.php" class="form-inline">
	<input type="submit" value="Go Back" />
</form>

</body>
</html>