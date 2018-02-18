<?php
session_start();
require 'database.php';
//There will be 2 sections:  STORY and COMMENTS
    //STORY
        //display title
        //display full description
        //if the logged in user was the one who submitted it, allow them to edit or delete the story
    
    //COMMENTS
        //show comments with newest at the top
        //nested comments?
        //anyways, if the user is logged in, allow them to comment (show above the comments)
        //also allow the user to edit or delete their comments
?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Detailed View</title>
        </head>
        <body>
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <?php
            
			//get the ID of the story
            if(isset($_GET['storyid']))
            {
                $storyid = $_GET['storyid'];
                $stmt = $mysqli->prepare("select posted, title, authorid, users.username, likes, story_body, links.url from stories join users on (stories.authorid=users.userid) join links on (stories.storyid=links.storyid) where stories.storyid=?");
				
				//make sure the query prep succeeds 
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
				}
				
				//bind the parameters and execute
				$stmt->bind_param('i',$storyid);
				$stmt->execute();
				
				//bind result and fetch
				$stmt->bind_result($timestamp,$title,$authorid,$author,$likes,$body,$url);
				$stmt->fetch();
				$stmt->close();
				
				//print the title of the story, link (if applicable), and the description
				printf("<h1>%s",$title);
				if($url)
				{
				printf(" [<a href=\"%s\">link</a>]",$url);
				}
				printf("</h1>");
				printf("<h2>Posted by %s at %s</h2>",$author,$timestamp);
				printf("<pre>%s</pre>",$body);
		
		//offer for comment IF the user is logged in
		if(isset($_SESSION['user_id'])){ ?>
			<form action="storydetails.php?storyid=<?php echo $storyid;?>" method="POST">
				<fieldset>
					<legend>Comment:</legend>
					<textarea cols="50" rows="10" name="comment"></textarea><br><br>
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="submit" value="Submit">
				</fieldset>
			</form>
			
			<?php
			//if the author of the story is logged in, give them the option to edit or delete the story
			if($authorid == $_SESSION['user_id'])
			{
				//give option to edit or delete story
			?>
				<!--Option to edit-->
				<form action="editstory.php" method="POST">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="hidden" name="storyid" value="<?php echo $storyid;?>" />
					<input type="submit" value="Edit Post">
				</form>
				<!--Option to delete-->
				<form action="storydetails.php?storyid=<?php echo $storyid;?>" method="POST">
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
					<input type="hidden" name="delete" />
					<input type="submit" value="Delete Post">
				</form>
<?php		}
			
			//also need to check for commenting, then put in database and force page refresh
			if(isset($_POST['comment']))
			{
				$storyid = $_GET['storyid'];
				$newcomment = (string) $_POST['comment'];
				//check security token
				if($_SESSION['token'] !== $_POST['token'])
				{
                    //if not, we kill
					die("Request forgery detected");
                }
				//prepare statement
				$stmt = $mysqli->prepare("insert into comments (authorid, comment_body, storyid, likes) values (?, ?, ?, ?)");
				//if query fails, get angry
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$likes = 0;
				//bind parameter for inserting comment into database
				$stmt->bind_param('isii', $_SESSION['user_id'], $newcomment, $storyid, $likes);
				$stmt->execute();
				$stmt->close();
				//now refresh the page
				//$location = "Location: storydetails.php?storyid=" . $storyid;
			//	header($location);
			}
			//deleting a story
			if(isset($_POST['delete']))
			{
				//get the id of the story story to be deleted
				$storyid = $_GET['storyid'];
				
				//prepare statement to delete comments associated with the story
				$stmt = $mysqli->prepare("DELETE FROM comments WHERE storyid = ?");
				
				//if the prepare fails, quit
				if(!$stmt)
				{
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				//bind the parametes, execute, and close
				$stmt->bind_param('i', $storyid);
				$stmt->execute();
				$stmt->close();
				
				//prepare to delete the link associated with the story
				$stmt = $mysqli->prepare("DELETE FROM links WHERE storyid = ?");
				
				//if prepare fails, quit
				if(!$stmt){
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				
				//bind parameters, execute, and close
				$stmt->bind_param('i', $storyid);
				$stmt->execute();
				$stmt->close();

				//prepare to delete the actual story
				$stmt = $mysqli->prepare("DELETE FROM stories WHERE stories.storyid = ?");
				
				//if the prepare statement fails, we quit
				if(!$stmt)
				{
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				
				//bind, execute, and close
				$stmt->bind_param('i', $storyid);
				$stmt->execute();
				$stmt->close();
				
				//bring the user home
				header("Location: home.php");
			}
			
			//deleting a comment, if the user so desires
			if(isset($_POST['deletecomment']))
			{
				//set comment to be deleted
				$todelete = $_POST['deletecomment'];
				//prepare to delete comment
				$stmt = $mysqli->prepare("DELETE FROM comments where comments.commentid = ?");
				
				//if the prepare fails, quit
				if(!$stmt)
				{
					printf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				
				//bind parameter, execute, and close
				$stmt->bind_param('i', $todelete);
				$stmt->execute();
				$stmt->close();
			}
		}
		//time to get the comments to display
		//prepare statement
		$stmt = $mysqli->prepare("select comments.posted, comments.comment_body, comments.commentid, comments.authorid, users.username from comments join users on (comments.authorid=users.userid) where comments.storyid = ? order by comments.posted desc");
		
		//if the prepare fails, quit
		if(!$stmt)
		{
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		
		//bind parameters and execute
		$stmt->bind_param('i', $storyid);
		$stmt->execute();
		
		//bind result
		$stmt->bind_result($timestamp,$comment,$commentid,$authorid,$author);
		
		//fetch and print results
		printf("<ul>\n");
		while($stmt->fetch())
		{
			printf("<li><pre>%s</pre>%s at %s ",$comment,$author,$timestamp);
			//check if the user is logged in
			if(isset($_SESSION['user_id']))
			{
				//if the logged in user is the comment author, give them the option to edit or delete the comment
				if($authorid == $_SESSION['user_id']){ ?>
								<!--Option to edit comment-->
                                <form action="editcomment.php" method="POST">
                                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                        <input type="hidden" name="commentid" value="<?php echo $commentid;?>" />
                                        <input type="submit" value="Edit Comment">
                                </form>
								
								<!--Option to delete comment-->
                                <form action="storydetails.php?storyid=<?php echo $storyid;?>" method="POST">
                                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                        <input type="hidden" name="deletecomment" value="<?php echo $commentid;?>" />
                                        <input type="submit" value="Delete Comment">
                                </form>

<?php                   }
				printf("</li>\n");

		}
		}
		printf("</ul>\n");
		$stmt->close();
	     }
	//if the story somehow does not exist, tell them
	else{
		printf("Sorry, the story could not be found.");
	}
            
           ?>
		   <!--Option to return home-->
		    <form action = "home.php">
                    <input type="submit" value="Home">
            </form>
        </body>
</html>
