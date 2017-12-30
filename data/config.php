<?php


define('SERVER_IP','104.131.109.240');
define('EMAIL_CONTACT_RECIPIENT','laura@sharpeconceptsny.com');

$mysql_config = [
	'MYSQL_USER' => 'www',
	'MYSQL_PASSWORD' => '*******',
	'MYSQL_CONNECTION_STRING' => 'mysql:host=localhost;dbname=sharpe'
];
$email_config = [
	'SMTP_SERVER' => 'localhost',
	'EMAIL_USER' => 'mailer',
	'EMAIL_USERNAME' => 'mailer@sharpeconceptsproject',
	'EMAIL_PASSWORD' => '*******',
	'EMAIL_CONTACT_RECIPIENT' => 'dpberry@usca.edu',
	'EMAIL_FROM' => 'donotreply@sharpeconceptsproject',
	'EMAIL_PORT' => 587
];

?>
