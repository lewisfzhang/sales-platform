<?php
    $db = new SQLite3('quotations2016.sqlite3'); //connect
    $url = $_POST['URL']; //get url of admin
    if (isset($url) and ($url != "")) {
        $statement = $db -> prepare('UPDATE admin SET isLoggedIn = 0 WHERE url = :url'); 
        $statement -> bindValue(':url', $url);
        $result = $statement->execute();
        echo "You're now logged out!";   
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Admin</title>
    </head>
    <body>
        <a href=<?php echo "\"admin.php?id=$url\""?>>Go back to Login Page</a>
    </body>
    <?php 
    }
    ?>
</html>
