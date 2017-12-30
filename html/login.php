
<?php 
require_once("../data/User.php");

require_once("../data/connect.php");
if(isset($_POST['User'])) {

	
	$dbh = db_connect();
	//Verify that the post variable is correct.
	$resp = ValidateModel($LoginViewModel, $_POST['User']);

	//If the model is valid
	if($resp==1) {
		$resp = AttemptLoginUser($dbh, $_POST['User']);
	}
	
	//If an error message is needed append it to the url
	if($resp!=1) {
		$resp = "?e=$resp";
	}else {
		$resp = "";
	}
	header("Location: /index.php$resp");
}
?>

<form method="POST" action="login.php" class="form-horizontal" role="form" data-toggle="validator" id="formLog">
		<?php
			global $ViewModel;
			$ViewModel = $LoginViewModel;
			include("../data/ViewModelForm.php");
		?>
		<button type="submit" class="btn btn-success col-md-offset-10">LOGIN</button>
</form>


<script>
	//Use the validator.js and initialize the form.
	$("#formLog").validator();

</script>
