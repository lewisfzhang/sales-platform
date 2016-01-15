<?php
    $url = $_GET['id'];
    if($url != NULL){ //the url has the admin's unique hash
        $db = new SQLite3('quotations2016.sqlite3'); //connect
        //get entire row 
        $statement = $db -> prepare('SELECT * FROM admin WHERE url = :url;'); 
        $statement -> bindValue(':url', $url);
        $result = $statement->execute();
        //set name to the name
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $name = $row['name']; 
        }
        if(($name != "") and isset($name)){ //if the hash is found
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
        <script type="text/javascript">
            function loginAdmin() {
                document.getElementById("container").innerHTML = "" //delete login form
            }
        </script>
    </head>
    <body>
        <div id="container">
            <p>Hello <?php echo  "$name" //show name?> </p>
            <form name="auth" method="post">
                Email: <input type="text" name="email"> <br>
                Password: <input type="text" name="password"> <br>
                <input type="submit" name="submit">
            </form>
        </div>
        <?php
            $email = $_POST['email']; //get email
            $password = $_POST['password']; //get password entered
            $hashPass = sha1($password); //hash of the password
            if(isset($_POST['email']) and isset($_POST['password'])){ //if admin entered email and password
                //get the true email from the database
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    $dbEmail = $row['email']; 
                }
                //get the hashed password from the database
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    $dbPassword = $row['password']; 
                }
                //get whether or not anyone else is logged in 
                $statement2 = $db -> prepare('SELECT isLoggedIn FROM admin'); 
                $result2 = $statement2->execute();
                //create an array for all of the isLoggedIn column values
                $isLoggedIn[] = [];
                while($row = $result2->fetchArray(SQLITE3_ASSOC)){
                    array_push($isLoggedIn, $row['isLoggedIn']); //set the values in to the array
                }
                if($email == $dbEmail and $hashPass == $dbPassword){ //if passwords match
                    if(sizeof(array_keys($isLoggedIn, 1)) > 0){ //if someone is logged in
                        echo "Someone else is logged in, please wait for them to finish.";
                    }
                    else{ //if someone is not logged in already
                        //put that you are logged in in the db
                        $statement3 = $db -> prepare('UPDATE admin SET isLoggedIn = 1 WHERE url = :url'); 
                        $statement3 -> bindValue(':url', $url);
                        $result3 = $statement3->execute();
                    
        ?>
        <script>
            loginAdmin(); //call function to change page once admin logs in successfully
        </script>
        <!--HTML if admin logs in succussfully-->
        <div id="container2">
            Meow
        </div>
        <?php   
                    }            
                }
                else{ //if passwords don't match
                    echo "Email or password incorrect, please try again.";
                }
            }
        ?>
    </body>
</html>

<?php 	} else{ //if the hash is not found
			echo "404" ;
        }
    } else {  //if there is no unique hash at the end
        echo "404";
    }
?>