<?php
    $quotationNum = $_GET['i']; //number of quotations
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    for($num = 0; $num <= $quotationNum; $num++){ //for each quotation
        $studentURL = $_POST["studentURL$num"]; //get the specific url for each quotation 
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
    </head>
    <body>
        
    </body>
</html>
