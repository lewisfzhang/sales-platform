<?php
	include 'users.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$validated = ($user==$valid_user)&&($pass==$valid_password);
	if($validated) {
		$file_db = new PDO('sqlite:../quotations.sqlite3');
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$find = "SELECT * FROM quotations WHERE processedStudent=0";
		$stmt = $file_db->prepare($find);
		$result = $stmt->execute();
		$data = $stmt->fetch();
		if($data) {
			echo json_encode($data);
		}
		else {
			echo "{}";
		}
	}
	else {
		header('WWW-Authenticate: Basic realm="Carillon"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
?>