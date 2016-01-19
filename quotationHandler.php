<?php
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $quotationNum = $_GET['i']; //number of quotations
    $adminURL = $_POST["adminURL"]; //admin URL
    for($num = 0; $num <= $quotationNum; $num++){ //for each quotation
        $studentURL = $_POST["studentURL$num"]; //get the specific url for each quotation
        $isStudentAdmin = $_POST["isStudentAdmin$num"]; //get whether it was a student admin 
        $radioState = $_POST["radioSet$num"]; //whether the quotation was approved, disapproved, or cleared
        //put that you are logged in in the db
        if($isStudentAdmin == 1){
            $statement = $db -> prepare('UPDATE quotations SET processedStudent = :radioState WHERE url = :studentURL');
        }
        else {
            $statement = $db -> prepare('UPDATE quotations SET processedTeacher = :radioState WHERE url = :studentURL');
        }
        $statement -> bindValue(':radioState', $radioState);
        $statement -> bindValue(':studentURL', $studentURL);
        $statement->execute();
    }
    //log out
    if (isset($adminURL) and ($adminURL != "")) {
        $statement = $db -> prepare('UPDATE admin SET isLoggedIn = 0 WHERE url = :adminURL'); 
        $statement -> bindValue(':adminURL', $adminURL);
        $result = $statement->execute();
        echo "Thank you! You're now logged out!";   
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