<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    $db = new SQLite3('quotations2016.sqlite3'); //connect
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin Login Reset</title>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> <!--W3.CSS stylesheet-->
    </head>
    <body>
        <form class="w3-container" method="post">
            <input type="submit" name="reset" value="Reset Admin Login Values" class="w3-btn w3-theme">
        </form>
        <?php
            if(isset($_POST['reset'])){ //after submit button is clicked
                //reset the login states for all admins
                $statement = $db -> prepare('UPDATE admin SET isLoggedIn = 0'); 
                $result = $statement->execute();
                if($result){ //if query is successful
                    //alert user
        ?>
                    <script>
                        alert("Admin login reset successful!");
                    </script>
        <?php
                }
                else{ //if query fails
                    //alert
        ?>
                    <script>
                        alert("Admin login reset failed, please email carillon@bcp.org for support");
                    </script>
        <?php
                }
            }
        ?>
    </body>
</html>
