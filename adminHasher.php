<?php
    //will turn the designated passwords into hashes
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $adminNum = 7; //number of admins in the table
    for($rowNum = 1; $rowNum < ($adminNum + 1); $rowNum++){ //loop through every row
        //get the plain text password
        $statement = $db -> prepare('SELECT password FROM admin WHERE rowNumber = :rowNum;'); 
        $statement -> bindValue(':rowNum', $rowNum);
        $result = $statement->execute();
        //set realResult to the plain text password
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $realResult = $row['password']; 
        }
        $hash = sha1($realResult); //SHA hash of the plain text password
        $statement2 = $db->prepare('UPDATE admin
        SET password = :hash
        WHERE rowNumber = :rowNum;'); 
        $statement2 -> bindValue(':rowNum', $rowNum);
        $statement2 -> bindValue(':hash', $hash);
        $statement2 -> execute();
        echo nl2br("$hash \n"); //show on screen 
    }
?>