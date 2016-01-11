<?php
    //will turn the emails into 10 character hash values to append to url
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $adminNum = 7; //number of admins in the table
    for($rowNum = 1; $rowNum < ($adminNum + 1); $rowNum++){ //loop through every row
        //get the admin email
        $statement = $db -> prepare('SELECT email FROM admin WHERE rowNumber = :rowNum;'); 
        $statement -> bindValue(':rowNum', $rowNum);
        $result = $statement->execute();
        //set realResult to the numerical student id value
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $realResult = $row['email']; 
        }
        $hash = crypt("$realResult"); //hash of the id
        $last10Hash = substr($hash, -10); //last 10 characters of the hash of the id
        $last10Hash = str_replace(array(".","/"), "", $last10Hash); //take out . and / in the hash
        //update the url field as the last 10 characters of hash
        $statement2 = $db->prepare('UPDATE admin
        SET url = :last10Hash
        WHERE rowNumber = :rowNum;'); 
        $statement2 -> bindValue(':rowNum', $rowNum);
        $statement2 -> bindValue(':last10Hash', $last10Hash);
        $statement2 -> execute();
        echo nl2br("$last10Hash \n"); //show on screen 
    }
?>