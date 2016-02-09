<!DOCTYPE HTML>
<html>
    <head>
        <title>Test Emailer</title>
    </head>
    <body>
        <p>Some words</p>
        <?php
            //PHPMailer using local PHP SMTP server
	        require('PHPMailer/PHPMailerAutoload.php');
	        $mail = new PHPMailer;
	        $mail->isSMTP();
	        $mail->Host = 'localhost';
	        $mail->Port = 25;
            
	        //Get data from form
	        $subject = "Test from Carillon";
	        //$body = htmlspecialchars($_POST['message'])
	        //echo $_POST['message'];
	        $body = nl2br("Test");
            
	        //Set initial mail headers
	        $mail->From = "carillon@bcp.org";
	        $mail->FromName = "The Carillon";
	        $mail->AddAddress('carillon@bcp.org');
            $mail->AddAddress('kevin.gottlieb19@bcp.org');
	        $mail->Subject = $subject;
	        $mail->Body = $body;
	        $mail->IsHTML(true);
        
		        if(!$mail->send()) {
			        echo 'Message could not be sent.';
			        echo 'Mailer Error: ' . $mail->ErrorInfo . '<br>';
		        } else {
			        echo 'All messages have been sent.';
		        }
        ?>
    </body>
</html>