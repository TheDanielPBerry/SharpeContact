<?php 
	

	function db_connect() {
		require_once("../data/config.php");
		try {
			return new PDO($mysql_config['MYSQL_CONNECTION_STRING'], $mysql_config['MYSQL_USER'], $mysql_config['MYSQL_PASSWORD']);
		} catch(PDOException $e) {
			print($e->getMessage());
			die();
		}
	}
?>