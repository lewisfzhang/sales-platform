<?php
	include 'users.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$validated = ($user==$valid_user)&&($pass==$valid_password);
	if($validated) {
		$url = $_POST["id"];
		$file_db = new PDO('sqlite:../quotations.sqlite3');
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$quotation = $_POST["quotation"];
		$quotation = trim(preg_replace('/\s+/', ' ', $quotation));
		$find = "UPDATE quotations SET quotation=:quotation, processedTeacher=2, processedStudent=2 WHERE url=:url";
		$stmt = $file_db->prepare($find);
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$stmt->bindParam(':quotation',$quotation, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetchAll();
	}
	else {
		header('WWW-Authenticate: Basic realm="Carillon"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
?>