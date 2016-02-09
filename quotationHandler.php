<?php
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $quotationNum = $_GET['i']; //number of quotations
    $adminURL = $_POST["adminURL"]; //admin URL
    function sendMail($to, $subject, $message){ //send email
        
        return TRUE; //will return true if sending worked
    }
    for($num = 0; $num <= $quotationNum; $num++){ //for each quotation   
        $studentURL = $_POST["studentURL$num"]; //get the specific url for each quotation
        $isStudentAdmin = $_POST["isStudentAdmin$num"]; //get whether it was a student admin 
        $radioState = $_POST["radioSet$num"]; //whether the quotation was approved, disapproved, or cleared
        $disapprovalReason = $_POST["disapprovalReason$num"];
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

            //send disapproval email to student
            $emailMessage = 
            "Hello $studentFirstName, <br><br>
            Unfortunately, your senior quotation for this year's Carillon Yearbook has been disapproved. You will need to resubmit your quotation as soon as possible. <br><br>
            Your quotation: $quotation <br><br>
            The reason your quotation was disapproved: $disapprovalReason <br><br>
            If something looks wrong, reply directly to this email. <br><br>
            Thanks again, <br><br>
            The Carillon Staff
            ";
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
        }
        $statement->execute(); //update table

        $result2 = $statement2->execute(); //get student stuff agagin
        //get whether student has approved
        while($row = $result2->fetchArray(SQLITE3_ASSOC)){
            $processedStudent = $row['processedStudent']; 
        }
        //get whether teacher has approved
        while($row = $result2->fetchArray(SQLITE3_ASSOC)){
            $processedTeacer = $row['processedTeacher']; 
        }
        if(($processedStudent == 1) and ($processedTeacer == 1)){ //if student quotation has been approved by student and teacher admins
            $emailMessage = 
            "Hello $studentFirstName, <br><br>
            Congratulations, your senior quotation for this year's Carillon Yearbook has been approved! You'll see it in the yearbook! <br><br>
            Your quotation: $quotation <br><br>
            If something looks wrong, reply directly to this email. <br><br>
            Thanks again, <br><br>
            The Carillon Staff
            ";
        }

        if(isset($emailMessage) and $emailMessage != ""){
            if(sendMail($studentEmail, "Carillon Senior Quotation Status", $emailMessage)){ //if mail is sent successfully
                //echo "$studentEmail <br> $emailMessage";
            }
            else{ //if send fails
                echo "Oh no! Sending a disapproval email has failed! Plase contact <a href='mailto:carillon@bcp.org'>carillon@bcp.org</a> so we can fix the problem.";
            }
        }
    }
    //log out
    if (isset($adminURL) and ($adminURL != "")) {
        $statement = $db -> prepare('UPDATE admin SET isLoggedIn = 0 WHERE url = :adminURL'); 
        $statement -> bindValue(':adminURL', $adminURL);
        $result = $statement->execute();
        echo "<br> Thank you! You're now logged out!";
        //echo "$studentEmail <br> $emailMessage";   
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