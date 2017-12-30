<?php
	session_start();
	unset($_SESSION["USER_EMAIL"]);
	unset($_SESSION["timeout"]);
	session_destroy();
	header("Location: /");
?>