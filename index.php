<?php
    $url = $_GET['id']; //student's hash
    if($url != NULL){ //the url has the studnet's unique hash
        $db = new SQLite3('quotations2016.sqlite3'); //connect
        //get first name 
        $statement = $db -> prepare('SELECT * FROM quotations WHERE url = :url;'); 
        $statement -> bindValue(':url', $url);
        $result = $statement->execute();
        //get first name
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $firstName = $row['firstName']; 
        }
        //get quotation
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $quotation = $row['quotation']; 
        }
        //get whether student has approved
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $processedStudent = $row['processedStudent']; 
        }
        //get whether teacher has approved
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            $processedTeacer = $row['processedTeacher']; 
        }
        if(($firstName != "") and isset($firstName)){ //if the hash is found
            function sendMail($to, $from, $fromName, $subject, $message){
                
            }
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
                    document.getElementById("charCount").innerHTML = "Character Count: " + "100/100"; //say that student has gone over limit
                    var quotation = document.getElementById("quotationEntry").value;
                    var newQuotation = quotation.substring(0, 100); //take 1st 100 characters
                    document.getElementById("quotationEntry").value = newQuotation; //stop after first 100 character
                }
                return length;
            }
            function trimmer() { //trims text in textarea
                var text = document.getElementById("quotationEntry").value;
                var trimmedText = text.trim();
                document.getElementById("quotationEntry").value = trimmedText; 
            }
        </script>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> <!--W3.CSS stylesheet-->
    </head>
    <body>
        <?php
            if(!($processedStudent == 1 and $processedTeacer == 1 and $quotation != "")){ //if everything hasn't been approved
            //then allow student to enter quotation
        ?>
        <header class="w3-container w3-blue">
            <h1>Please enter your quotation, <?php echo "$firstName"?></h1>
        </header>
        <form class="w3-container w3-card" method="post" style="margin-top: 20px;"> <!--Form with submit button-->
            <textarea id="quotationEntry" name="quotationEntry" onchange="charCount()" onkeyup="charCount()" onkeydown="charCount()" onblur="charCount()" onfocus="charCount()" rows="3" cols="50">
<?php
    //get the student's quotations
    /*$statement = $db -> prepare('SELECT quotation FROM quotations WHERE url = :url;'); 
    $statement -> bindValue(':url', $url);
    $result = $statement->execute();
    //set quotation
    while($row = $result->fetchArray(SQLITE3_ASSOC)){
        $quotation = $row['quotation']; 
    }*/
    $quotation = trim($quotation); //trim whitespace
    if(isset($quotation) and $quotation != ""){ //if quotation isn't null
        echo "$quotation"; //put quotation in text area 
    }
?>
            </textarea>
            <script>
                trimmer();
            </script>
            <p id="charCount">Character Count: /100</p>
            <p>Be sure to cite your source!</p>
            <input type="submit" name="submitQuote" class="w3-btn">
        </form>
        <?php
            $newQuotation = $_POST['quotationEntry']; //get new quotation
            if(isset($_POST['quotationEntry'])){ //if the user entered a quotation
                //set quotation and reset approvals
                $statement = $db -> prepare(
                'UPDATE quotations
                SET quotation = :newQuotation, 
                processedStudent = 0, 
                processedTeacher = 0,
                disapprovalReason = NULL
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
            }
            else{ //if quotation is already approved
        ?>
        <header class="w3-container w3-blue">
            <h1>You're good to go, <?php echo "$firstName"?>!</h1>
        </header>
        <h2>Your approved quotation: <?php echo "\"$quotation\""?></h2>
        <?php
            }
        ?>
        <p>Please email <a href="mailto:carillon@bcp.org">carillon@bcp.org</a> if you're having any trouble.</p>
    </body>
</html>

<?php 	} else{ //if the hash is not found
			echo "404" ;
        }
    } else {  //if ther is no unique hash at the end
        echo "Please check your email for a customized URL.";
    }
?>