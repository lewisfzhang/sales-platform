<?php​
	$key = 'C5kf72Ka3yU0'; //Encryption key for unsubscribe link hashing​
    //PHPMailer using local PHP SMTP server
	require('PHPMailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'localhost';
	$mail->Port = 25;
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