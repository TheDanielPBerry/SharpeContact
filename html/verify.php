<?php
require_once("../data/User.php");
require_once("../data/connect.php");
$dbh = db_connect();


if(($email = VerifyEmail($dbh, $_GET['token']))) {
	$dbh = null;
	//If the verification was sucessful log them in.
	$user = ['Email'=>$email];
	Login($user);

	header('Location: /index.php?e=Your account has been verified.');
} else {
	echo "fail";
	$dbh = null;
	header('Location: /index.php');
}


?>