<?php 

//Dependencies
require_once("../data/User.php");
require_once("../data/connect.php");

//Initialize database connection.
//This must be done for every page load to load the country names.
$dbh = db_connect();

session_start();
//If the user is logged in prepopulate the view model and remove the create account fields.
if(isset($_SESSION["USER_EMAIL"])) {
	$user = getUser($dbh, $_SESSION["USER_EMAIL"]);
	$RegisterViewModel = MapStoredUserToRegisterViewModel($RegisterViewModel, $user);
}

//If a user variable has been posted back validate it and perform the backend form functions.
if(isset($_POST['User'])) {
	$resp = ValidateModel($RegisterViewModel, $_POST['User']);

	//If the model was valid
	if($resp==1) {
		//Repopulate the fields in the event that this is a postback
		$RegisterViewModel = MapUserToRegisterViewModel($_POST['User'], $RegisterViewModel); 
		//Determine if the form wanted to create an account and there is no one logged in.
		if(!isset($_SESSION["USER_EMAIL"]) && array_key_exists("'CreateAccount'",$_POST['User']) && ((bool)$_POST['User']["'CreateAccount'"])) {
			//Create the User
			$resp = CreateUser($dbh, $_POST['User']);
			if(strlen($resp)==8) {
				SendVerificationEmail($resp, $_POST['User']);
				$resp = "A link has been sent to your email to verify your address.";
			}
		} else {
			$resp = "Message Sent";
		}
	}
	SendContactEmail($_POST['User']);

	//Redirect to index with a message
	header("Location: /index.php?e=$resp");
	//Echo the response for debugging purposes.
	echo $resp;
}


#Obtain a list of countries and country codes to populate the dropdown
require_once("../data/Country.php");
$CountryList = getCountryList($dbh);
$dbh = null;


?>


<form method="POST" action="register.php" class="form-horizontal" role="form" data-toggle="validator" id="formReg">
			<?php
			global $ViewModel;
			$ViewModel = $RegisterViewModel;
			include("../data/ViewModelForm.php");
		?>
		<button type="submit" class="btn btn-success col-md-offset-10" id="FormSubmissionButton">SEND</button>
</form>
<br/><br/><br/>



<script>
	//Use the validator.js and initialize the form.
	$("#formReg").validator();



	//Query the GeoIP service to determine the client's external ip country of origin
	$.get("http://freegeoip.net/json/<?php echo $_SERVER['REMOTE_ADDR']; ?>", function(data) {
		//Populate the dropdown with the country of origin
		$('#Country').val(data['country_code']);
	});


	function TogglePasswordFields() {
		if($('#CreateAccount').prop('checked')) {
			$('.hidden-fields').fadeIn();
			$('input.hidden-fields').prop('required', true);
			$('input.hidden-fields').prop('disabled', false);
			$('#FormSubmissionButton').text('REGISTER');
		}else {
			$('.hidden-fields').fadeOut();
			$('input.hidden-fields').prop('required', false);
			$('input.hidden-fields').prop('disabled', true);
			$('#FormSubmissionButton').text('SEND');
		}
	}
</script>
