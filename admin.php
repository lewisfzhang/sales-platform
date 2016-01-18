<?php
    $url = $_GET['id'];
    if(($url != NULL) and ($url != "")){ //the url has the admin's unique hash
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
                Password: <input type="password" name="password"> <br>
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
                $statement2 = $db -> prepare('SELECT * FROM admin'); 
                $result2 = $statement2->execute();
                //create an array for all of the isLoggedIn column values
                $isLoggedIn[] = [];
                while($row = $result2->fetchArray(SQLITE3_ASSOC)){
                    array_push($isLoggedIn, $row['isLoggedIn']); //set the values in to the array
                }
                /*foreach($isLoggedIn as $key){
                    echo $key;
                }*/
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
            <!--Log out button-->
            <form name="logout" action="logOut.php" method="post">
                <input type="hidden" name="URL" value=<?php echo "\"$url\""; //send the url?>>
                <input type="submit" name="logOutSubmit" value="Log Out"> 
            </form>
            <!--
            Form that for all of the quotation approvals
            Must go to a separate handler because radio button might not be clicked but form still has to be handled
            The number of entrie will be set with as get
            -->
            <form name="quotationApproval" method="post" action=<?php
                //get everything
                $statement4 = $db -> prepare('SELECT * FROM quotations;'); 
                $result4 = $statement4 -> execute();
                //set quotations
                //create an array for all of the quotations
                $allQuotations[] = [];
                while($row = $result4->fetchArray(SQLITE3_ASSOC)){
                    array_push($allQuotations, $row['quotation']); //set the values in to the array
                } 
                $i = 0; 
                foreach($allQuotations as $eachQuotation){
                    if(($eachQuotation != "") and ($eachQuotation != "Array")){ //if not "" of "Array"
                        $i = $i + 1; //increment number of quotations ready to be approved
                    }
                }
                $i2 = $i - 1;
                echo "quotationHandler.php?i=$i2"?>>
            <?php  
                //get everything
                /*$statement4 = $db -> prepare('SELECT * FROM quotations;'); 
                $result4 = $statement4 -> execute();
                //set quotations
                //create an array for all of the quotations
                $allQuotations[] = [];
                while($row = $result4->fetchArray(SQLITE3_ASSOC)){
                    array_push($allQuotations, $row['quotation']); //set the values in to the array
                }*/
                /*foreach($allQuotations as $key){
                    if($key != ""){
                        echo $key;
                    }
                }*/
                //show HTML ones that aren't null or ""
                $i = 0; //number of quotations displayed 
                foreach($allQuotations as $eachQuotation){
                    if(($eachQuotation != "") and ($eachQuotation != "Array")){ //if not "" of "Array"
                        //get name of person with quoation
                        $statement5 = $db -> prepare('SELECT * FROM quotations WHERE quotation = :eachQuotation;'); 
                        $statement5 -> bindValue(':eachQuotation', $eachQuotation);
                        $result5 = $statement5->execute();
                        //set name to the name
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $studentFirstName = $row['firstName']; 
                        } 
                        //set url
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $studentURL = $row['url']; 
                        } 
                        //get whether the student has been processed by another teacher or student admin
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $isProcessedStudent = $row['processedStudent']; 
                        }  
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $isProcessedTeacher= $row['processedTeacher']; 
                        }
                        //use the very 1st query to get whether the admin is a student or a teacher
                        while($row = $result->fetchArray(SQLITE3_ASSOC)){
                            $isStudentAdmin = $row['isStudent']; 
                        } 
                        if($i != 0){ //takes out the first quotation, which is always "Array"
                            //name of the radio button fields
                            //it is incremented so that each quotation has it's own set of radio buttons
                            $radioName = "radioSet$i"; 
            ?>
                <?php 
                    echo "Quotation: \"$eachQuotation\" Name: $studentFirstName ";
                ?>
                Approve: <input type="radio" name=<?php echo "\"$radioName\"";?> value="Approve" <?php
                       if(($isStudentAdmin == 1) and ($isProcessedStudent == 1)){ //if admin is a student and the quotation had been previously approved by a student admin
                            echo "checked"; //check this radio button
                       }
                       elseif(($isStudentAdmin == 0) and ($isProcessedTeacher == 1)){ //if admin is a teacher and the quotation had been previously approved by a teacher admin
                            echo "checked"; //check this radio button
                       }
                   ?>>
                Disapprove: <input type="radio" name=<?php echo "\"$radioName\"";?> value="Disapprove" <?php
                       if(($isStudentAdmin == 1) and (($isProcessedStudent == -1) or ($isProcessedStudent == -2))){ //if admin is a student and the quotation had been previously disapproved by a student admin
                            echo "checked"; //check this radio button
                       }
                       elseif((($isProcessedTeacher == -1) or ($isProcessedTeacher == -2))){ //if admin is a teacher and the quotation had been previously disapproved by a teacher admin
                            echo "checked"; //check this radio button
                       }
                   ?>>
                Clear: <input type="radio" name=<?php echo "\"$radioName\"";?> value="Clear">
                <input type="hidden" name=<?php echo "\"studentURL$i\""?> value=<?php echo "\"$studentURL\"";?>> <!--Sends the URL of the student whose quotation is being looked at-->
                <br>
            <?php
                        }
                        $i = $i + 1; //number
                    }
                }
            ?>
                <input type="submit" name="submit2">
            </form> 
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
    
<?php 	} else{ //if the hash is not found
			echo "404" ;
        }
    }
    else {
        echo "Please use your customized URL";
    }
?>
</html>