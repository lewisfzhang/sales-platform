<?php​
	$key = 'C5kf72Ka3yU0'; //Encryption key for unsubscribe link hashing​
    //PHPMailer using local PHP SMTP server
	require('PHPMailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'localhost';
	$mail->Port = 25;
    ​
	//MYSQL connection
	$servername = 'localhost';
	$username = 'clubs_u3er';
	$password = 'tCpw6uTKZj3faqPT';
	$dbname = 'clubs';
	/*$con=mysqli_connect($servername, $username, $password, $dbname);
	if (mysqli_connect_errno()) { exit('Failed to connect to MySQL database: ' . mysqli_connect_error()); }*/
​
	//Get data from form
	$subject = "Test from Carillon";
	//$body = htmlspecialchars($_POST['message'])
	//echo $_POST['message'];
	$body = nl2br("Test");
    ​
	//Set initial mail headers
	$mail->From = "clubmailer@bcp.org";
	$mail->FromName = "The Carillon";
	$mail->AddAddress('carillon@bcp.org');
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->IsHTML(true);
​
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo . '<br>';
		} else {
			echo 'All messages have been sent.';
		}​
?>