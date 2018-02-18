<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'newsadmin', 'plzsendhelpto123help', 'module3');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>

<?php

//Database name:  news_site
    //Table:  USERS
        //Columns
            //id (SMALLINT UNSIGNED NOT NULL AUTO INCREMENT)
                //IS PRIMARY KEY
            //username (VARCHAR(15) NOT NULL)
            //pass (VARCHAR(50) NOT NULL)
                //salt and encrypt the password before entering it into the database

    //Table:  STORIES
        //Columns
            //id (INT UNSIGNED NOT NULL AUTO INCREMENT)
                //PRIMARY KEY
            //title (VARCHAR(50) NOT NULL)
            //submitter (SMALLINT UNSIGNED NOT NULL AUTO INCREMENT)
                //linked to id of USERS
            //timestamp (TIMESTAMP)
            //rating (***)
            
    //Table:  COMMENTS
        //Columns
            //id (INT UNSIGNED NOT NULL AUTO INCREMENT)
                //PRIMARY KEY
            //parent_story (INT UNSIGNED NOT NULL)
                //linked to id of STORIES
            //parent_user (SMALLINT UNSIGNED NOT NULL)
                //linked to id of USERS
            //content (VARCHAR(100) NOT NULL)
            
?>