<?php
session_start();
require 'database.php';
                
                //Properties of a story
                        //Has ID (primary key)
                        //title
                        //description
                        //user that submitted it
                        //timestamp
                        //link (optional)
                        //average rating
                        
            //check to make sure if user is logged in
                 //if not logged in
                    //deny story submission privilege
                    //they shouldn't even be able to get to this page
                        //but since they did somehow, get sassy and tell the user to log in
                //if logged in, ask user to submit the following fields:
                        //Title
                        //Link
                        //Description
                    //verify CSRF token
                    //Filter all the inputs
                    //add story to database
                    //let use know if story has successfully been submitted

?>
<!DOCTYPE html>
    <html>
        <head>
            <title>Submit a Post</title>
        </head>
        <body>
            <link rel="stylesheet" type="text/css" href="stylesheet.css" />
            <?php
                //check that the user is logged in
                if (isset($_SESSION['user_id']))
                {//if they are logged in, let them submit a story
                    
            ?>
            <h1>Submit your story here:</h1><br>
            <form action="submitstory.php" method ="POST">
                <fieldset>
                  <legend>Submission Info:</legend>
                  Title:<br>
                  <input type="text" name="title"><br>
                  Link:<br>
                  <input type="text" name="link"><br><br>
                  Description:<br>
                  <textarea cols="80" rows="30" name="description"></textarea><br><br>
                  <!--submit CSFR token-->
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                  <input type="submit" value="Submit">
                </fieldset>
              </form>
            <?php
                }
                //if the user is not logged in, tell them to log in
                else
                {

            ?>
                <h1>Please log in before submitting a story</h1>
                <form action = "login.php">
                    <input type="submit" value="Login">
                </form>
                <form action = "newuser.php">
                    <input type="submit" value="Register">
                </form>    
                
            <?php
                }
                ?>
                <!--Go back to home-->
                <form action = "home.php">
                    <input type="submit" value="Return to Homepage">
                </form>                  
            <?php
            //check that all the variables are set
            if(isset($_POST['title']) AND isset($_POST['description']))
            {	 
            //Filter the inputs 
            $story_title = (string) $_POST['title'];
		if(isset($_POST['link'])){
			$story_link = (string) $_POST['link'];
		}
            $story_description = (string) $_POST['description'];
                //check the security token and see if it's a-ok
                if($_SESSION['token'] !== $_POST['token'])
                {
                    //if not, we kill
                    die("Request forgery detected");
                }
                
                //prepare the query for inserting authorid, title, and story body into the stories table
                $stmt = $mysqli->prepare("INSERT INTO stories (authorid, title, story_body) VALUES (?, ?, ?)");
                //if the query fails, get angry
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                //bind our parameters
                $stmt->bind_param('iss', $_SESSION['user_id'], $story_title, $story_description);
                
                //execute and close
                $stmt->execute();
                //get the value of the last auto-incremented row (aka storyid)
                $story_id= $mysqli->insert_id;
                $stmt->close();
               if(isset($story_link)){
                //Insert into the links table
                $stmt = $mysqli->prepare("INSERT INTO links (url, storyid) VALUES (?, ?)");
                //if the query fails, get angry
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('si', $story_link, $story_id);
                //execute and close
                $stmt->execute();
                $stmt->close();
                }
                //take the user to the result page
                header('Location: home.php');
            }
           ?>
        </body>
</html>
