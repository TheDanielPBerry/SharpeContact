<?php

function SanitizeField($input) {
	$input = htmlentities($input, ENT_QUOTES, 'UTF-8');
	$input = trim($input);
	return $input;
}
function SanitizeObject($input) {
	foreach($input as $value) {
		$value = SanitizeField($value);
	}
	return $input;
}

function ValidateField($field, $value) {
	switch ($field['type']) {
		case 'date':
		case 'text':
			return true;
		case 'email':
			return filter_var($value, FILTER_VALIDATE_EMAIL);
			break;
		case 'number':
			return filter_var($value, FILTER_VALIDATE_INT);
			break;
		default:
			return True;
			break;
	}
}


function SendEmail($subject, $body, $recipient) {

	$headers = 'From: webmaster@sharpeconceptsproject' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($recipient, $subject, $body, $headers);
	/*
	//Still working on getting PHPMailer to work with my install of postfix.
	require_once('../PHPMailer/PHPMailerAutoload.php');
	
	require_once('../data/config.php');

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host = $email_config['SMTP_SERVER'];
		$mail->SMTPSecure = 'tls';
		$mail->Port = $email_config['EMAIL_PORT'];
		$mail->SMTPAuth = true;
		$mail->Username = $email_config['EMAIL_USERNAME'];
		$mail->Password = $email_config['EMAIL_PASSWORD'];

		//Recipients
		$mail->setFrom($email_config['EMAIL_FROM'], $email_config['EMAIL_USER']);
		$mail->addAddress($recipient);

		//Content
		$mail->isHTML(False);
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->send();
	} catch (Exception $e) {
		echo '<br/><br/>Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}

	*/
}


?>