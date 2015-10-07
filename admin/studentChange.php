<?php
	include 'users.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$validated = ($user==$valid_user)&&($pass==$valid_password);
	if($validated) {
		$url = $_POST["id"];
		$pass = $_POST["pass"];
		$file_db = new PDO('sqlite:../quotations.sqlite3');
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$find = "";
		if($pass==1) {
			$find = "UPDATE quotations SET processedStudent=2 WHERE url=:url";
			echo "1";
		}
		else {
			$find = "UPDATE quotations SET processedStudent=1, processedTeacher=1 WHERE url=:url";
			echo "2";
		}
		$stmt = $file_db->prepare($find);
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetchAll();
		$stmt = $file_db->prepare("SELECT * FROM quotations WHERE url=:url");
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetch();
		if($data["processedStudent"]==2&&$data["processedTeacher"]==2) {
			$urls = 'http://www.antiamoebic.com/yearbook/seniors/admin/sendEmail.php';
			$datas = array('id' => $url, 'pass' => '1', 'name' => $data["name"], 'email' => $data["email"], 'quotation' => $data["quotation"], 'reason' => '');
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($datas),
				),
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($urls, false, $context);
			var_dump($result);

		}
		if($data["processedStudent"]==1||$data["processedTeacher"]==1) {
			$reason = $_POST["reason"];
			$urls = 'http://www.antiamoebic.com/yearbook/seniors/admin/sendEmail.php';
			$datas = array('id' => $url, 'pass' => '0', 'name' => $data["name"], 'email' => $data["email"], 'quotation' => $data["quotation"], 'reason' => $reason);
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($datas),
				),
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($urls, false, $context);
			var_dump($result);
		}
	}
	else {
		header('WWW-Authenticate: Basic realm="Carillon"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
?>