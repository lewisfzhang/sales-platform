<?php
	$url = $_POST["id"];
	$quotation = $_POST["quotation"];
	$quotation = trim(preg_replace('/\s+/', ' ', $quotation));
	if(strlen($quotation)<=100) {
		$file_db = new PDO('sqlite:quotations.sqlite3');
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$find = "UPDATE quotations SET quotation=:quotation, processedTeacher=0, processedStudent=0 WHERE url=:url";
		$stmt = $file_db->prepare($find);
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$stmt->bindParam(':quotation',$quotation, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetchAll();
	}
	echo "done";
?>
