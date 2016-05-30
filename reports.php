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
        <form method="post">
            <input type="submit" name="disapproved" value="Disapproved without Resubmiting">
        </form>
        <form method="post">
            <input type="submit" name="noSubmit" value="No Submission">
        </form>
        <p id="approvedTableLabel"></p>
        <table border="1" id="approvedTable"></table>
        <p id="disapprovedTableLabel"></p>
        <table border="1" id="disapprovedTable"></table>
        <p id="noSubmitTableLabel"></p>
        <table border="1" id="noSubmitTable"></table>
        <?
            if(isset($_POST['approved'])){
                $statement = $db -> prepare('SELECT * FROM quotations WHERE processedStudent=1 OR processedTeacher=1');
                $result = $statement -> execute();
            }
        ?>
    </body>
</html>
