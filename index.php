<?php
    $url = $_GET['id']; //student's hash
    if($url != NULL){ //the url has the studnet's unique hash
        $db = new SQLite3('quotations2016.sqlite3'); //connect
        //get first name 
        $statement = $db -> prepare('SELECT firstName FROM quotations WHERE url = :url;'); 
        $statement -> bindValue(':url', $url);
        $result = $statement->execute();
        //set firstName to the name
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $firstName = $row['firstName']; 
        }
        if($result){ //if the hash is found
            echo "Hello $firstName"; //show name on page
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Bellarmine Senior Quotaion</title>
        <!--Some JS to count the char number in the textarea-->
        <script>
            //document.getElementById("quotationEntry").onkeyup =
            function charCount() {
                var length = document.getElementById("quotationEntry").value.length; //the number of characters in text area
                var charsLeft = 100 - length; //the number of character left to type out of 100
                if (charsLeft >= 0) { //if student hasn't reach limit
                    document.getElementById("charCount").innerHTML = "Character Count: " + length + "/100"; //put out character count
                }
                else { //if student went over limit
                    document.getElementById("charCount").innerHTML = "Character Count: " + "100+/100"; //say that student has gone over limit
                    var quotation = document.getElementById("quotationEntry").value;
                    var newQuotation = quotation.substring(0, 100); //take 1st 100 characters
                    document.getElementById("quotationEntry").value = newQuotation; //stop after first 100 character
                }
                return length; 
            }
        </script>
    </head>
    <body>
        <form method="post"> <!--Form with submit button-->
            <textarea id="quotationEntry" name="quotationEntry" onkeydown="charCount()" rows="3" cols="50">
<?php
    //get the student's quotations
    $statement = $db -> prepare('SELECT quotation FROM quotations WHERE url = :url;'); 
    $statement -> bindValue(':url', $url);
    $result = $statement->execute();
    //set quotation
    while($row = $result->fetchArray(SQLITE3_ASSOC)){
        $quotation = $row['quotation']; 
    }
    $quotation = trim($quotation); //trim whitespace
    if(isset($quotation)){ //if quotation isn't null
        echo "$quotation"; //put quotation in text area 
    }
?>
            </textarea>
            <p id="charCount">Character Count: /100</p>
            <p>Be sure to cite your source!</p>
            <input type="submit" name="submitQuote">
        </form>
        <?php
            $newQuotation = $_POST['quotationEntry']; //get new quotation
            if(isset($_POST['quotationEntry'])){ //if the user entered a quotation
                $statement = $db -> prepare(
                'UPDATE quotations
                SET quotation = :newQuotation
                WHERE url = :url;'); 
                $statement -> bindValue(':url', $url);
                $statement -> bindValue(':newQuotation', $newQuotation);
                $result = $statement->execute();
                if($result){
                    echo "<script>
                    window.open('thankYou.html', '_self', false);
                    </script>"; //open a new window to show that quotation has been submitted
                }
            }
        ?>
    </body>
</html>

<?php 	} else{ //if the hash is not found
			echo "404" ;
        }
    } else {  //if ther is no unique hash at the end
        echo "Please check your email for a customized URL.";
    }
?>