<?php
session_start();
require 'database.php';
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>News</title>
        </head>
        
        
        <body>
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <h1>Very cool Website</h1><br>
            <?php
//BLOCK:  DETERMINE IF THE USER IS LOGGED IN, AND DISPLAY OPTIONS AS APPROPRIATE
            //if the user is nog logged in, display a login and a register button
            if (!isset($_SESSION['user_id']))
            {
                ?>
                <form action = "login.php">
                    <input type="submit" value="Login">
                </form>
                <form action = "newuser.php">
                    <input type="submit" value="Register">
                </form>
                <?php
            }
            else
            {
                //Welcome the user
                $user=$_SESSION['user_name'];
                printf("<p>Hello, %s!</p> \n",
                       htmlentities($user)
                       );
                ?>
                
                <!--Allow the user to submit a new story-->
                <form action = "submitstory.php">
                    <input type="submit" value="Submit a Story">
                </form>
                <!--Allow the user to logout-->
                <form action = "logout.php">
                    <input type="submit" value="Logout">
                </form><br><br>
        <?php
             }
        ?>
        <h1>LATEST STORIES</h1>
            
<?php
//END DETERMINING IF USER IS LOGGED IN
//DISPLAY STORIES
		//preprare statement to delect author, timestamp, title, and id of the story from the appropriate tables
        $stmt = $mysqli->prepare("SELECT users.username, stories.posted, stories.title, stories.storyid
                                 FROM stories
                                 JOIN users
                                    ON (stories.authorid=users.userid)
                                 ORDER BY posted DESC");
		
		//make sure that the prepare is successful
        if(!$stmt)
        {
        	printf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
        }
		
		//execute and bind results
        $stmt->execute();
        $stmt->bind_result($auth, $timestamp, $title, $story_id);

		//print out every story with title, author, and timestamp
        while($stmt->fetch())
        {
			//Also escape all the outputs
            ?>
            <h2><?php printf("%s", htmlentities($title));?></h2>
            <i>Posted by:  <?php printf("%s", htmlentities($auth));?></i><br>
            <?php printf("%s", htmlentities($timestamp)); ?><br>
				<!--Give the user an optiont to go to a detailed view to see the story description and comments-->
                <form action = "storydetails.php" method = "GET">
                    <input type="hidden" name="storyid" value= "<?php echo $story_id; ?>"/>
                    <input type="submit" value="View Comments/Details">
                </form><br><br>
            <?php
        }
            ?>
        </body>
</html>
    