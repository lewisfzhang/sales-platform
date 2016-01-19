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
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> <!--W3.CSS stylesheet-->
    </head>
    <body>
        <div id="container">
            <!--W3.CSS Login page example template-->
            <header class="w3-container w3-blue">
                <h1>Please Login</h1>
            </header>

            <div class="w3-container w3-half w3-margin-top">
                <form class="w3-container w3-card-4" name="auth" method="post">
                  <h2 class="w3-text-theme">Login</h2>
                  <div class="w3-group">      
                    <input class="w3-input" type="text" name="email" required>
                    <label class="w3-label">Email</label>
                  </div>
                  <div class="w3-group">      
                    <input class="w3-input" type="password" name="password" required>
                    <label class="w3-label">Password</label>
                  </div>
                  <br><br>
                  <input type="submit" name="submit" value="Log in" class="w3-btn w3-theme">
                  <br><br>
                </form>
                <!--End W3.CSS Login page example template-->
            </div>
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
            <header class="w3-container w3-blue" style="padding-top: 10px;">
                <!--Log out button-->
                <form name="logout" action="logOut.php" method="post" style="float: left; padding-right: 20px;">
                    <input type="hidden" name="URL" value=<?php echo "\"$url\""; //send the url?>>
                    <input type="submit" name="logOutSubmit" value="Log Out" class="w3-btn"> 
                </form> 
                <!--Title-->
                <h1 style="float: left; margin-top: -15px;">Senior Quotations</h1>
                <!--Name-->
                <h1 style="float: right; margin-top: -15px;"><?php echo "Signed in as: $name"?></h1>
            </header>
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
                $i = 0; //number of quotations to be displayed 
                foreach($allQuotations as $eachQuotation){
                    if(($eachQuotation != "") and ($eachQuotation != "Array")){ //if not "" of "Array"
                        //get name of person with quoation
                        $statement5 = $db -> prepare('SELECT * FROM quotations WHERE quotation = :eachQuotation;'); 
                        $statement5 -> bindValue(':eachQuotation', $eachQuotation);
                        $result5 = $statement5->execute();
                        //set name
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $studentFirstName = $row['firstName']; 
                        } 
                        while($row = $result5->fetchArray(SQLITE3_ASSOC)){
                            $studentLastName = $row['lastName']; 
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
                <!--Show quotation in half of the browser-->
                <div class="w3-card w3-half" style="padding-top: 40px;">
                <header class="w3-container w3-teal">
                    <h3>Quotation</h3>
                </header>
                <div class="w3-container">
                     <?php 
                        echo "\"$eachQuotation\"";
                     ?>
                </div>
                </div>
                <!--Show Name and action in other half-->
                <div class="w3-card w3-half" style="padding-top: 40px;">
                <header class="w3-container w3-teal">
                    <h3><?php echo "$studentFirstName $studentLastName";?></h3>
                </header>
                <div class="w3-container">
                    <!--Form stuff-->
                    Approve: <input type="radio" name=<?php echo "\"$radioName\"";?> value="1" <?php
                       if(($isStudentAdmin == 1) and ($isProcessedStudent == 1)){ //if admin is a student and the quotation had been previously approved by a student admin
                            echo "checked"; //check this radio button
                       }
                       elseif(($isStudentAdmin == 0) and ($isProcessedTeacher == 1)){ //if admin is a teacher and the quotation had been previously approved by a teacher admin
                            echo "checked"; //check this radio button
                       }
                   ?>>
                Disapprove: <input type="radio" name=<?php echo "\"$radioName\"";?> value="-1" <?php
                       if(($isStudentAdmin == 1) and (($isProcessedStudent == -1) or ($isProcessedStudent == -2))){ //if admin is a student and the quotation had been previously disapproved by a student admin
                            echo "checked"; //check this radio button
                       }
                       elseif((($isProcessedTeacher == -1) or ($isProcessedTeacher == -2))){ //if admin is a teacher and the quotation had been previously disapproved by a teacher admin
                            echo "checked"; //check this radio button
                       }
                   ?>>
                Clear: <input type="radio" name=<?php echo "\"$radioName\"";?> value="0">
                <input type="hidden" name=<?php echo "\"studentURL$i\""?> value=<?php echo "\"$studentURL\"";?>> <!--Sends the URL of the student whose quotation is being looked at-->
                <input type="hidden" name=<?php echo "\"isStudentAdmin$i\""?> value=<?php echo "\"$isStudentAdmin\""?>> <!--Send whether or not it's a student admin-->
                </div>
                </div>
                <br>
            <?php
                        }
                        $i = $i + 1; //number
                    }
                }
            ?>
                <input type="hidden" name=<?php echo "\"adminURL\""?> value=<?php echo "\"$url\""?>> <!--Send admin url to log out-->
                <input type="submit" name="submit2" class="w3-btn" style="margin-top: 40px; margin-left: 20px;">
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