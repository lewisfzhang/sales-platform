<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE); //doesn't report small errors
    require('PHPMailer/PHPMailerAutoload.php'); //PHPMailer file
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $quotationNum = $_GET['i']; //number of quotations
    $adminURL = $_POST["adminURL"]; //admin URL
    $mailSent = FALSE; //whether the email has been sent
    $pastStudentURL = " "; //the hash of the quotation acted upon in the previous interaiont of the for loop

    function sendMail($to, $subject, $message){ //send email
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 25;

        //Set initial mail headers
        $mail->From = "carillon@bcp.org";
        $mail->FromName = "The Carillon";
        $mail->AddBCC('carillon@bcp.org');
        $mail->AddAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHTML(true);
                
        return $mail->send(); //will return true if sending worked
    }

    for($num = 0; $num <= $quotationNum; $num++){ //for each quotation 
        $studentURL = $_POST["studentURL$num"]; //get the specific hash for each quotation
        $fullStudentURL = "http://times.bcp.org/yb/q2016/index.php?id=$studentURL"; //the actual link to the student's quotation entry page
        $isStudentAdmin = $_POST["isStudentAdmin$num"]; //get whether it was a student admin 
        $radioState = $_POST["radioSet$num"]; //whether the quotation was approved, disapproved, or cleared
        $disapprovalReason = $_POST["disapprovalReason$num"];

        if($studentURL != $pastStudentURL){ //if the current url is different 
            $mailSent = FALSE; 
        }

        //get student stuff
        $statement2 = $db -> prepare('SELECT * FROM quotations WHERE url = :studentURL;'); 
        $statement2 -> bindValue(':studentURL', $studentURL);
        //get student email
        $result2 = $statement2->execute();
        while($row = $result2->fetchArray(SQLITE3_ASSOC)){
            $studentEmail = $row['email']; 
        }
        //get student first name
        while($row = $result2->fetchArray(SQLITE3_ASSOC)){
            $studentFirstName = $row['firstName']; 
        }
        //get quotation
        while($row = $result2->fetchArray(SQLITE3_ASSOC)){
            $quotation = $row['quotation']; 
        }  

        if($radioState == -1){ //if quotation is disapproved
            //set the disapproval state of the quotation
            if($isStudentAdmin == 1){
                $statement = $db -> prepare('UPDATE quotations SET processedStudent = :radioState, disapprovalReason = :disapprovalReason WHERE url = :studentURL');
            }
            else {
                $statement = $db -> prepare('UPDATE quotations SET processedTeacher = :radioState, disapprovalReason = :disapprovalReason WHERE url = :studentURL');
            }
            $statement -> bindValue(':radioState', $radioState);
            $statement -> bindValue(':studentURL', $studentURL);
            $statement -> bindValue(':disapprovalReason', $disapprovalReason);
            $statement->execute(); //update table

            //send disapproval email to student
            $emailMessage = 
            "Hello $studentFirstName, <br><br>
            Unfortunately, your senior quotation for this year's Carillon Yearbook has been disapproved. You will need to resubmit your quotation as soon as possible. <br><br>
            Please resubmit your quotation here: <a href='$fullStudentURL'>$fullStudentURL</a> <br><br>
            Your quotation: $quotation <br><br>
            The reason your quotation was disapproved: $disapprovalReason <br><br>
            If something looks wrong, reply directly to this email. <br><br>
            Thanks again, <br><br>
            The Carillon Staff
            ";
            if(!$mailSent){ //if mail hasn't been sent
                if(sendMail($studentEmail, "Carillon Senior Quotation Status", $emailMessage)){ //if mail is sent successfully
                    echo "Mail sent to $studentEmail <br>";
                    $mailSent = TRUE;
                }
                else{ //if send fails
                    echo "Oh no! Sending a disapproval email has failed! Plase contact <a href='mailto:carillon@bcp.org'>carillon@bcp.org</a> so we can fix the problem.";
                }
            }
        }
        else{
            //set the approval state of the quotation
            if($isStudentAdmin == 1){
                $statement = $db -> prepare('UPDATE quotations SET processedStudent = :radioState WHERE url = :studentURL');
            }
            else {
                $statement = $db -> prepare('UPDATE quotations SET processedTeacher = :radioState WHERE url = :studentURL');
            }
            $statement -> bindValue(':radioState', $radioState);
            $statement -> bindValue(':studentURL', $studentURL);
            $statement->execute(); //update table

            if($radioState == 1){
                $result2 = $statement2->execute(); //get student stuff again
                //get whether student has approved
                while($row = $result2->fetchArray(SQLITE3_ASSOC)){
                    $processedStudent = $row['processedStudent']; 
                }
                //get whether teacher has approved
                while($row = $result2->fetchArray(SQLITE3_ASSOC)){
                    $processedTeacher = $row['processedTeacher']; 
                }

                if(($processedStudent == 1) and ($processedTeacher == 1)){ //if student quotation has been approved by student and teacher admins
                    $emailMessage = 
                    "Hello $studentFirstName, <br><br>
                    Congratulations, your senior quotation for this year's Carillon Yearbook has been approved! You'll see it in the yearbook! <br><br>
                    Your quotation: $quotation <br><br>
                    If something looks wrong, reply directly to this email. <br><br>
                    Thanks again, <br><br>
                    The Carillon Staff
                    ";
                    if(!$mailSent){
                        if(sendMail($studentEmail, "Carillon Senior Quotation Status", $emailMessage)){ //if mail is sent successfully
                            echo "Mail sent to $studentEmail <br>";
                            $mailSent = TRUE;
                        }
                        else{ //if send fails
                            echo "Oh no! Sending a disapproval email has failed! Plase contact <a href='mailto:carillon@bcp.org'>carillon@bcp.org</a> so we can fix the problem.";
                        }
                    }
                }
            }
        }

        $pastStudentURL = $studentURL; //set the current url as the past url for the next loop
    }

    //log out
    if (isset($adminURL) and ($adminURL != "")) {
        $statement = $db -> prepare('UPDATE admin SET isLoggedIn = 0 WHERE url = :adminURL'); 
        $statement -> bindValue(':adminURL', $adminURL);
        $result = $statement->execute();
        echo "<br> Thank you! You're now logged out!";  
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
    </head>
    <body>
        <a href=<?php echo "\"admin.php?id=$adminURL\""?>>Go back to Login Page</a>
    </body>
    <?php 
    }
    ?>
</html>