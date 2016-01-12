<?php
    $url = $_GET['id'];
    if($url != NULL){ //the url has the admin's unique hash
        $db = new SQLite3('quotations2016.sqlite3'); //connect
        //get name 
        $statement = $db -> prepare('SELECT name FROM admin WHERE url = :url;'); 
        $statement -> bindValue(':url', $url);
        $result = $statement->execute();
        //set name to the name
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $name = $row['name']; 
        }
        if($result){ //if the hash is found
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
        <script type="text/javascript">
            function loginAdmin() {
                document.getElementById("container").innerHTML = "<h1>Some HTML for the admin approval page</h1>" //insert new HTML
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
                $statement = $db -> prepare('SELECT email FROM admin WHERE url = :url;'); 
                $statement -> bindValue(':url', $url);
                $result = $statement->execute();
                //set name to the name
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    $dbEmail = $row['email']; 
                }
                //get the hashed password from the database
                $statement = $db -> prepare('SELECT password FROM admin WHERE url = :url;'); 
                $statement -> bindValue(':url', $url);
                $result = $statement->execute();
                //set name
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    $dbPassword = $row['password']; 
                }
                if($email == $dbEmail and $hashPass == $dbPassword){ //if passwords match
        ?>
        <script type="text/javascript">
            loginAdmin(); //call function to change page once admin logs in successfully
        </script>
        <?php          
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