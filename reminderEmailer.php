<?php
    error_reporting(E_ERROR | E_PARSE); //doesn't report small errors
    require('PHPMailer/PHPMailerAutoload.php'); //PHPMailer file
    $db = new SQLite3('quotations2016.sqlite3'); //connect

    function sendMail($to, $subject, $message){ //send email
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';

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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Reminder Emailer</title>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> <!--W3.CSS stylesheet-->
    </head>
    <body>
        <form class="w3-container" method="post">
            <input type="submit" name="sendEmail" value="Send Email to Seniors who have not Submitted" class="w3-btn w3-theme">
        </form>
        <?php
            if(isset($_POST['sendEmail'])){
                $statement = $db -> prepare('SELECT * FROM quotations'); 
                $result = $statement->execute();

                //create an array for all of the column values
                $emailArray[] = [];
                $firstNameArray[] = [];
                $urlArray[] = [];
                $quotationArray[] = [];
                $processedStudentArray[] = [];
                $processedTeacherArray[] =[];
                $disapprovalReasonArray[] = [];

                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($emailArray, $row['email']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($firstNameArray, $row['firstName']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($urlArray, $row['url']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($quotationArray, $row['quotation']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($processedStudentArray, $row['processedStudent']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($processedTeacherArray, $row['processedTeacher']); //set the values in to the array
                }
                while($row = $result->fetchArray(SQLITE3_ASSOC)){
                    array_push($disapprovalReasonArray, $row['disapprovalReason']); //set the values in to the array
                }

                $index = 0; //the current index the emailer is on
                foreach($emailArray as $email){
                    $quotation = $quotationArray[$index];
                    $firstName = $firstNameArray[$index];
                    $url = $urlArray[$index];
                    $processedStudent = $processedStudentArray[$index];
                    $processedTeacher = $processedTeacherArray[$index];
                    $disapprovalReason = $disapprovalReasonArray[$index];
                    $fullURL = "http://times.bcp.org/yb/q2016/index.php?id=$url";

                    //send an email if student hasn't entered quotation yet
                    if(!isset($quotation) or $quotation == NULL or empty($quotation) or $quotation == "" or $processedStudent == -1 or $processedTeacher == -1){
                        if(!isset($quotation) or $quotation == NULL or empty($quotation) or $quotation == ""){
                            $emailMessage = 
                            "Hello $firstName, <br><br>
                            It seems you haven't submitted your senior quotation yet! Please submit your quotation here: <a href='$fullURL'>$fullURL</a> by Monday the 23rd by 11:59pm.<br><br>
                            We're getting very close to the deadline for printing the book, and need you to make sure the quotation you submit is the one you really want printed. Please keep it appropriate and cite the author of the quotation. If it is original, please write “- Me” at the end of the quotation. We obviously won’t print that but it will indicate to us that it needs no citation. <br><br>
                            If something looks wrong, reply directly to this email. <br><br>
                            Thanks again, <br><br>
                            The Carillon Staff";
                        }
                        if($processedStudent == -1 or $processedTeacher == -1){
                            $emailMessage = 
                            "Hello $firstName, <br><br>
                            It seems you haven't resubmitted your senior quotation after disapproval yet! Please submit your quotation here: <a href='$fullURL'>$fullURL</a> by Monday the 23rd by 11:59pm.<br><br>
                            Your current quotation: $quotation <br><br>
                            The reason your quotation was disapproved: $disapprovalReason <br><br>
                            We're getting very close to the deadline for printing the book, and need you to make sure the quotation you resubmit is the one you really want printed. Please keep it appropriate and cite the author of the quotation. If it is original, please resubmit and write “- Me” at the end of the quotation. We obviously won’t print that but it will indicate to us that it needs no citation. <br><br>
                            If something looks wrong, reply directly to this email. <br><br>
                            Thanks again, <br><br>
                            The Carillon Staff";
                        }
 
                        if(sendMail($email, "Please Submit Your Carillon Senior Quotation", $emailMessage)){ //if mail is sent successfully
                            echo "Mail sent to $email <br>";
                        }
                        else{ //if send fails
                            echo "Oh no! Sending a reminder email has failed! Plase contact <a href='mailto:carillon@bcp.org'>carillon@bcp.org</a> so we can fix the problem.";
                        }
                    }
                    $index++; //increment index
                }
            }
        ?>
    </body>
</html>
