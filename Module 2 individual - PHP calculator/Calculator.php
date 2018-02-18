<!DOCTYPE html>
    <html>
        <head>
            <title>Let's Calculate</title>
            <?php
            //set defaults for the variables
            $var1 = 0;
            $var2 = 0;
            $operation = "";
            ?>
            
        </head>
        <body>
            
            <form action = "Calculator.php" method = "GET">
                <!-- text fields for user to enter numbers -->
            Number 1:  <input type="text" name="value1" value="<?php echo $var1; ?>">
            Number 2:  <input type="text" name="value2" value="<?php echo $var2; ?>"><br>
                    <!--Radio buttons for the user to select a mathematical operation -->
                <input type ="radio" name="op" value = "add"> Add<br>
                <input type ="radio" name="op" value = "subtract"> Subtract<br>
                <input type ="radio" name="op" value = "multiply"> Multiply<br>
                <input type ="radio" name="op" value = "divide"> Divide<br>
                    <!--submit button for user-->
                <input type = "submit">
            </form>
            <?php
            //check to make sure inputs from user exist
            if (isset($_GET['value1']) && isset($_GET['value2']) && isset($_GET['op']))
                {
                    //set user input to variables
                    $var1 = $_GET['value1'];
                    $var2 = $_GET['value2'];
                    $operation = $_GET["op"];   
                }
            ?>
            <?php
            //cases for each operation
            switch ($operation){
                //perform addition and display result
                case "add":
                    $result = $var1+$var2;
                    printf("<p> %d plus %d is %d </p> \n",
                           htmlentities($var1),
                           htmlentities($var2),
                           $result
                           );
                    break;
                //perform subtraction and display result
                case "subtract":
                    $result = $var1-$var2;
                    printf("<p> %d minus %d is %d </p> \n",
                           htmlentities($var1),
                           htmlentities($var2),
                           $result
                           );
                    break;
                //perform multiplication and display result
                case "multiply":
                    $result = $var1*$var2;
                    printf("<p> %d times %d is %d </p> \n",
                           htmlentities($var1),
                           htmlentities($var2),
                           $result
                           );
                    break;
                //perform division and display result to 2 decimals
                case "divide":
                    $result = $var1/ ((float) $var2);
                    printf("<p> %d divided by %d is %.2f </p> \n",
                           htmlentities($var1),
                           htmlentities($var2),
                           $result
                           );
                    break;
            }
            ?>
        </body>
    </html>
