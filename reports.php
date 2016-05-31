<?php
    error_reporting(E_ERROR | E_PARSE); //doesn't report small errors
    $db = new SQLite3('quotations2016.sqlite3'); //connect
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Reports</title>
    </head>
    <body>
        <p>Show List of Students:</p>
        <form method="post">
            <input type="submit" name="approved" value="Submitted and Approved">
        </form>
        <br>
        <form method="post">
            <input type="submit" name="disapproved" value="Disapproved without Resubmiting">
        </form>
        <br>
        <form method="post">
            <input type="submit" name="noSubmit" value="No Submission">
        </form>
        <br>
        <table border="1" id="approvedTable">
            <?php
                if(isset($_POST['approved'])){
                    $emailArray[] = [];
                    $firstNameArray[] = [];
                    $lastNameArray[] =[];
                    $quotationArray[] = [];
                    //$disapprovalReasonArray[] = [];
                    //get rows for those who's quotations have been approved by either student or teacher and not disapproved
                    $statement = $db -> prepare('SELECT * FROM quotations WHERE (processedStudent!=-1 AND processedTeacher!=-1) AND (processedStudent=1 OR processedTeacher=1)');
                    $result = $statement -> execute();
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($lastNameArray, $row['lastName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($firstNameArray, $row['firstName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($emailArray, $row['email']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($quotationArray, $row['quotation']); //set the values in to the array
                    }
                    /*while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($disapprovalReasonArray, $row['disapprovalReason']); //set the values in to the array
                    }*/ //won't be needing this since these quotation are approved
                    $index = 0; //this is the index of the array that is being stepped through
                    foreach($lastNameArray as $lastName){
                        if($index == 0){ //the first value in the array is always "Array", so put something in place of that
                            //create the first row which will have column names
                            $echoString = 
                                "<tr>
                                    <td>Last Name</td>
                                    <td>First Name</td>
                                    <td>Email</td>
                                    <td>Quotation</td>
                                </tr>";    
                        }
                        else{ //all of the other index values
                            //get the values for this row
                            $firstName = $firstNameArray[$index];
                            $email = $emailArray[$index];
                            $quotation = $quotationArray[$index];
                            //$disapprovalReason = $disapprovalReasonArray[$index];
                            //put together the html that will go into the table
                            $echoString = 
                            "<tr>
                                <td>$lastName</td>
                                <td>$firstName</td>
                                <td>$email</td>
                                <td>$quotation</td>
                            </tr>";
                        }
                        echo $echoString; //put the row into the table
                        $index++; //increment index
                    }
                }
            ?>
        </table>
        <table border="1" id="disapprovedTable">
            <?php
                if(isset($_POST['disapproved'])){
                    $emailArray[] = [];
                    $firstNameArray[] = [];
                    $lastNameArray[] =[];
                    $quotationArray[] = [];
                    $disapprovalReasonArray[] = [];
                    //get rows for those who's quotations have been disapproved by either teacher or student
                    $statement = $db -> prepare('SELECT * FROM quotations WHERE processedStudent=-1 OR processedTeacher=-1');
                    $result = $statement -> execute();
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($lastNameArray, $row['lastName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($firstNameArray, $row['firstName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($emailArray, $row['email']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($quotationArray, $row['quotation']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($disapprovalReasonArray, $row['disapprovalReason']); //set the values in to the array
                    }
                    $index = 0; //this is the index of the array that is being stepped through
                    foreach($lastNameArray as $lastName){
                        if($index == 0){ //the first value in the array is always "Array", so put something in place of that
                            //create the first row which will have column names
                            $echoString = 
                                "<tr>
                                    <td>Last Name</td>
                                    <td>First Name</td>
                                    <td>Email</td>
                                    <td>Quotation</td>
                                    <td>Disapproval Reason</td>
                                </tr>";    
                        }
                        else{ //all of the other index values
                            //get the values for this row
                            $firstName = $firstNameArray[$index];
                            $email = $emailArray[$index];
                            $quotation = $quotationArray[$index];
                            $disapprovalReason = $disapprovalReasonArray[$index];
                            //put together the html that will go into the table
                            $echoString = 
                            "<tr>
                                <td>$lastName</td>
                                <td>$firstName</td>
                                <td>$email</td>
                                <td>$quotation</td>
                                <td>$disapprovalReason</td>
                            </tr>";
                        }
                        echo $echoString; //put the row into the table
                        $index++; //increment index
                    }
                }
            ?>
        </table>
        <table border="1" id="noSubmitTable">
            <?php
                if(isset($_POST['noSubmit'])){
                    $emailArray[] = [];
                    $firstNameArray[] = [];
                    $lastNameArray[] =[];
                    //$quotationArray[] = [];
                    //$disapprovalReasonArray[] = [];
                    //get rows for those who have no quotation
                    $statement = $db -> prepare('SELECT * FROM quotations WHERE quotation IS NULL');
                    $result = $statement -> execute();
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($lastNameArray, $row['lastName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($firstNameArray, $row['firstName']); //set the values in to the array
                    }
                    while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($emailArray, $row['email']); //set the values in to the array
                    }
                    /*while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($quotationArray, $row['quotation']); //set the values in to the array
                    }*/ //no need for this, they have no quotation
                    /*while($row = $result->fetchArray(SQLITE3_ASSOC)){
                        array_push($disapprovalReasonArray, $row['disapprovalReason']); //set the values in to the array
                    }*/ //no need for this, they have no quotation
                    $index = 0; //this is the index of the array that is being stepped through
                    foreach($lastNameArray as $lastName){
                        if($index == 0){ //the first value in the array is always "Array", so put something in place of that
                            //create the first row which will have column names
                            $echoString = 
                                "<tr>
                                    <td>Last Name</td>
                                    <td>First Name</td>
                                    <td>Email</td>
                                </tr>";    
                        }
                        else{ //all of the other index values
                            //get the values for this row
                            $firstName = $firstNameArray[$index];
                            $email = $emailArray[$index];
                            //$quotation = $quotationArray[$index];
                            //$disapprovalReason = $disapprovalReasonArray[$index];
                            //put together the html that will go into the table
                            $echoString = 
                            "<tr>
                                <td>$lastName</td>
                                <td>$firstName</td>
                                <td>$email</td>
                            </tr>";
                        }
                        echo $echoString; //put the row into the table
                        $index++; //increment index
                    }
                }
            ?>
        </table>
    </body>
</html>
