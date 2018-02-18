<?php
session_start();
require 'database.php';
?>
<!DOCTYPE html>
<html>
        <head>
                <title>Edit Comment</title>
        </head>
        <body>
                <link rel="stylesheet" type="text/css" href="stylesheet.css" />

                <?php


                        if (isset($_POST['commentid'])){
                                //check for token
                                $commentid = $_POST['commentid'];
                                if ($_SESSION['token'] !== $_POST['token']) {
                                        die("Request forgery detected"); }
                                $stmt = $mysqli->prepare("select comment_body,storyid from comments where commentid = ?");
                                if(!$stmt){
                                        printf("Query Prep Failed: %s\n", $mysqli->error);
                                        exit;
                                }
                                $stmt->bind_param('i', $commentid);
                                $stmt->execute();
                                $stmt->bind_result($comment,$storyid);
                                $stmt->fetch();
                                $stmt->close();
                                if (isset($_POST['edited'])){
                                        $commentbody = $_POST['commentbody'];
                                        $stmt = $mysqli->prepare("update comments set comment_body= ? where commentid = ?");
                                        if(!$stmt){
                                                printf("Query Prep Failed: %s\n", $mysqli->error);
                                                exit;
                                        }
                                        $stmt->bind_param('si',$commentbody,$commentid);
                                        $stmt->execute();
                                        $stmt->close();
                                        $location = "Location: storydetails.php?storyid=" .  $storyid;
                                        header($location);

                                }

                        }

                ?>
                <form action="editcomment.php" method="POST">
                        <fieldset>
                        <legend>Edit Comment:</legend>
                        <textarea cols="50" rows="30" name="commentbody"><?php echo $comment;?></textarea><br>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                        <input type="hidden" name="commentid" value="<?php echo $commentid;?>" />
                        <input type="hidden" name="edited" value="true"/>
                        <input type="submit" value="Submit!">
                        </fieldset>
                </form>

        </body>
</html>

