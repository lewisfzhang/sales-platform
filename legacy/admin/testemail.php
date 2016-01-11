<?php
	require 'PHPMailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'smail.bcp.org';
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'carillon@bcp.org';                 // SMTP username
	$mail->Password = 'carillon1951';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to
	$mail->From = 'carillon@bcp.org';
	$mail->FromName = 'Carillon';
	$mail->addAddress("dorian.chan15@bcp.org");
	$mail->AddBCC("dorian.chan15@bcp.org", "Dorian Chan");
	$mail->Subject = 'Your Carillon Senior Quotation was Approved!';
	$mail->Body = 'Hi Dorian Chan,<br><br>
	Good news, your senior quotation has been approved! Your final quotation is below. If something looks wrong, please reply directly to this email:<br><br>
	Your quotation: <br><br>
	Thanks again,<br>
	The Carillon Staff';
	$mail->isHTML(true);     
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message has been sent';
	}
?>