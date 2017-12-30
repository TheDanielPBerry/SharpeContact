


<!DOCTYPE html>
<html lang="EN">
<head>
	<title>SharpeConcepts Contact</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="styles/Site.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="scripts/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="scripts/validator.js"></script>
	<script type="text/javascript" src="scripts/popper.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/Site.js"></script>
</head>
<body>


	<div class="container">
		<?php include("header.php") ?>
	</div>
	<div id="body-content" class="container">
	</div>

	<div class="ModalContainer" id="ModalContainer">
		<div class="ModalBox">
			<div>
				<a class="ExitModal" onclick="CloseModal();"><span class="fa fa-times-circle"></span></a>
			</div>
			<div id="ModalContent">
			</div>
		</div>
	</div>
</body>
</html>








<script>
<?php 
#If an error message is specified display it.
if(isset($_GET['e'])) {
	echo "LoadMessage('" . $_GET['e'] . "');";
}

?>
</script>





