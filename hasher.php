<?php
    //will turn the student id's into 10 character hash values to append to url
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $studentNum = 386; //number of students in the table
    for($rowNum = 1; $rowNum < ($studentNum + 1); $rowNum++){ //loop through every row
        //get the student id
        $statement = $db -> prepare('SELECT id FROM quotations WHERE rowNumber = :rowNum;'); 
        $statement -> bindValue(':rowNum', $rowNum);
        $result = $statement->execute();
        //set realResult to the numerical student id value
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $realResult = $row['id']; 
        }
        $hash = crypt("$realResult"); //hash of the id
        $last10Hash = substr($hash, -10); //last 10 characters of the hash of the id
        $last10Hash = str_replace(array(".","/"), "", $last10Hash); //take out . and / in the hash
        //update the url field as the last 10 characters of hash
        $statement2 = $db->prepare('UPDATE quotations
        SET url = :last10Hash
        WHERE rowNumber = :rowNum;'); 
        $statement2 -> bindValue(':rowNum', $rowNum);
        $statement2 -> bindValue(':last10Hash', $last10Hash);
        $statement2 -> execute();
        echo nl2br("$last10Hash \n"); //show on screen 
    }
?>