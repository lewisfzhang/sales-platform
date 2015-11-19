<?php
	$url = $_POST["id"]; //get the hash
	$quotation = $_POST["quotation"]; //get the quotation itself
	$quotation = trim(preg_replace('/\s+/', ' ', $quotation)); //trim whitespace ?
	if(strlen($quotation)<=100) { //make sure that it's within the char limit
		$file_db = new PDO('sqlite:quotations.sqlite3'); //connect
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //updates the database w/ the new quotation to be reviewed by a teacher
		$find = "UPDATE quotations SET quotation=:quotation, processedTeacher=0, processedStudent=0 WHERE url=:url"; 
		$stmt = $file_db->prepare($find);
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$stmt->bindParam(':quotation',$quotation, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetchAll();
	}
	echo "done";
?>
