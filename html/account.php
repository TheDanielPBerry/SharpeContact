<?php 
session_start();
if(!isset($_SESSION["USER_EMAIL"])) { ?>
	<li class="nav-item">
		<a class="nav-link jsLink" onclick="LoadModal('login.php');">Login</a>
	</li>
<?php } else { ?>
<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?php 
			require_once("../data/User.php");
			require_once("../data/connect.php");
			$dbh = db_connect();
			echo getUserFullName($dbh, $_SESSION["USER_EMAIL"]);
 		?>
	</a>
	<div class="dropdown-menu" aria-labelledby="navbarDropdown">
		<a class="dropdown-item" href="logout.php">Logout</a>
	</div>
</li>
<?php } ?>