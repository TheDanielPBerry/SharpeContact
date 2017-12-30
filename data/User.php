<?php

$RegisterViewModel = [
	'FirstName' => [
		'label' => 'First Name',
		'type' => 'text',
		'validator' => '^[A-Za-z ]{1,30}$',
		'required' => True,
		'maxlength' => '30',
		'minlength' => '1' ],

	'LastName' => [
		'label' => 'Last Name',
		'type' => 'text',
		'validator' => '^[A-Za-z ]{1,30}$',
		'required' => True,
		'maxlength' => '30',
		'minlength' => '1' ],

	'Email' => [
		'label' => 'Email Address',
		'type' => 'email',
		'validator' => '^.*$',
		'required' => True,
		'maxlength' => '50',
		'minlength' => '4' ],

	'Phone' => [
		'label' => 'Phone Number',
		'type' => 'phone',
		'validator' => '^.*$',
		'required' => True,
		'maxlength' => '17',
		'minlength' => '7' ],

	'DOB' => [
		'label' => 'Date of Birth',
		'type' => 'date',
		'validator' => '',
		'required' => False ],

	'Country' => [
		'label' => 'Country of Origin',
		'type' => 'dropdown',
		'validator' => '',
		'maxlength' => '2',
		'minlength' => '2', 
		'required' => False ],

	'Comments' => [
		'label' => 'Comments',
		'type' => 'textarea',
		'validator' => '',
		'required' => False ],

	'CreateAccount' => [
		'label' => 'Would you like to create an account?',
		'type' => 'checkbox',
		'validator' => '',
		'required' => False,
		'onclick' => 'TogglePasswordFields()' ],

	'Password' => [
		'label' => 'Password',
		'type' => 'password',
		'validator' => '^.{8,64}$',
		'required' => False,
		'maxlength' => '64',
		'minlength' => '8',
		'hidden' => True,
		'disabled' => True ],

	'PasswordVerify' => [
		'label' => 'Verify Password',
		'type' => 'password',
		'validator' => '^.{8,64}$',
		'required' => False,
		'maxlength' => '64',
		'minlength' => '8',
		'matches' => 'Password',
		'hidden' => True,
		'disabled' => True ]
];

$LoginViewModel = [
	'Email' => [
		'label' => 'Email Address',
		'type' => 'email',
		'validator' => '^.*$',
		'required' => True,
		'maxlength' => '50',
		'minlength' => '4' ],

	'Password' => [
		'label' => 'Password',
		'type' => 'password',
		'validator' => '^.{8,64}$',
		'required' => True,
		'maxlength' => '64',
		'minlength' => '8' ]
];


/**
Examine a an http passed value and determine if the object matches a specified view model.
We can do this to make sure validation is fluid and ready.
*/
function ValidateModel($model, $submission) {
	require_once("../data/utility.php");
	foreach($model as $field => $attrib) {
		if(array_key_exists('required', $model[$field]) && $model[$field]['required']) {
			if(!array_key_exists("'$field'", $submission)) {
				return $model[$field]['label']." is required!";
			}
			#If the field is required it must also meet these criteria
			if(array_key_exists('maxlength', $model[$field])) {
				if(((int)$model[$field]['maxlength'])<strlen($submission["'$field'"])) {
					return $model[$field]['label']." is too long!";
				}
			}
			if(array_key_exists('minlength', $model[$field])) {
				if(((int)$model[$field]['minlength'])>strlen($submission["'$field'"])) {
					return $model[$field]['label']." is too short!";
				}
			}
			if(array_key_exists('matches', $model[$field])) {
				if(strcmp($submission["'".$model[$field]["matches"]."'"], $submission["'$field'"])!=0) {
					return $model[$field]['label']." does not match!";
				}
			}
		}
		if(array_key_exists('type', $model[$field])) {
			if(array_key_exists("'$field'", $submission)) {
				if(!ValidateField($model[$field], $submission["'$field'"])) {
					return $model[$field]['label']." has an incorrect format!";
				}
			}
		}
	}
	return 1;
}

/**
Retrieve a user by the primary key, their email.
*/
function getUser($dbh, $email) {
	try {
		$stmt = $dbh->prepare("SELECT Email, FirstName, LastName, Phone, DOB, Country, PasswordHash, Verified, Registration 
			FROM User 
			WHERE Email=:email");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $stmt->fetch();
	} catch(PDOException $e) {
		return $e->getMessage();
	}
}


/*
After a postback we can take a User array from the http data and remap the values back to the view model
*/
function MapUserToRegisterViewModel($user, $viewModel) {
	$userViewModel = $viewModel;
	$userViewModel['FirstName']['default'] = $user["'FirstName'"];
	$userViewModel['LastName']['default'] = $user["'LastName'"];
	$userViewModel['Email']['default'] = $user["'Email'"];
	$userViewModel['Phone']['default'] = $user["'Phone'"];
	$userViewModel['DOB']['default'] = $user["'DOB'"];
	$userViewModel['Country']['default'] = $user["'Country'"];
	return $userViewModel;
}
/*
After a postback we can take a User array from the http data and remap the values back to the view model
This version will operate for logged in users.
*/
function MapStoredUserToRegisterViewModel($viewModel, $user) {
	$viewModel['FirstName']['default'] = $user['FirstName'];
	$viewModel['LastName']['default'] = $user['LastName'];
	$viewModel['Email']['default'] = $user['Email'];
	$viewModel['Phone']['default'] = $user['Phone'];
	$viewModel['Country']['default'] = $user['Country'];
	$viewModel['DOB']['default'] = $user['DOB'];
	unset($viewModel['CreateAccount']);
	unset($viewModel['Password']);
	unset($viewModel['PasswordVerify']);
	return $viewModel;
}


/**
Retrieve a boolean value on whether a use exists with the specified email.
*/
function userExists($dbh, $email) {
	try {
		$stmt = $dbh->prepare("SELECT count(*) FROM User WHERE Email=?");
		$stmt->bindParam(1, $email);
		$stmt->execute();
		return $stmt->fetch()[0]==1;
	} catch(PDOException $e) {
		return $e->getMessage();
	}
}


/**
Get a list of all the users in the database.
*/
function getUserList($dbh) {
	try {
	$sql = 'SELECT FirstName, LastName, Email, Verified FROM User';
		return $dbh->query($sql);
	} catch(PDOException $e) {
		return $e->getMessage();
	}
}


/*
Retrieve a concatenated first and last name of a specific user.
This is used to display their name later in the show.
*/
function getUserFullName($dbh, $email) {
	try {
		$stmt = $dbh->prepare("SELECT FirstName, LastName FROM User WHERE Email=:email");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$names = $stmt->fetch();
		return $names[0] . " " . $names[1];
	} catch(PDOException $e) {
		return $e->getMessage();
	}
	return '<a href="logout.php">Logout</a>';
}



/*
Add a new user to the user table.
The user object fed to this should be from the register view model and should be validated before it is passed.
*/
function CreateUser($dbh, $user) {
	require_once("../data/utility.php");
	if(array_key_exists("'Password'", $user) 
		&& array_key_exists("'PasswordVerify'", $user)) {
		$user = SanitizeObject($user);
		if(!userExists($dbh, $user["'Email'"])) {
			try {
				$stmt = $dbh->prepare("INSERT INTO User (Email, FirstName, LastName, Phone, DOB, Country, PasswordHash, Verified, Registration) 
					VALUES (:email, :fname, :lname, :phone, :dob, :country, :passwordHash, 0, CURRENT_TIMESTAMP)");

				$stmt->bindParam(':email', $user["'Email'"]);
				$stmt->bindParam(':fname', $user["'FirstName'"]);
				$stmt->bindParam(':lname', $user["'LastName'"]);
				$stmt->bindParam(':phone', $user["'Phone'"]);
				$stmt->bindParam(':dob', $user["'DOB'"]);
				$stmt->bindParam(':country', $user["'Country'"]);

				//Hash the password for storage in the database
				$passwordHash = password_hash($user["'Password'"], PASSWORD_DEFAULT);
				$stmt->bindParam(':passwordHash', $passwordHash);

				$stmt->execute();
				return substr($passwordHash, -8);
			} catch(PDOException $e) {
				return $e->getMessage();
			}
			return "A login error occured.";
		}
	}
	return "This email is already in use.";
}


/**
Send an email to the registered config for a recipient of the 
*/
function SendContactEmail($user) {
	require_once("../data/config.php");
	$body = "New Message from ".$user["'FirstName'"];
	$body.="
	Details:
	Full Name: " . $user["'FirstName'"] . " " . $user["'LastName'"]."
	Email: " . $user["'Email'"]."
	Phone: " . $user["'Phone'"]."
	Date of Birth: " . $user["'DOB'"]."
	Country of Origin: " . $user["'Country'"];
	if(array_key_exists("'Comments'", $user)) {
		$body.= "\r\n	Comments: " . $user["'Comments'"];
	}
	SendEmail("New User Message from SharpeConceptsProject",$body, EMAIL_CONTACT_RECIPIENT);
}

/*
Send an email to a new user with a validation link to verify their email.
*/
function SendVerificationEmail($token, $user) {
	require_once("../data/utility.php");
	require_once("../data/config.php");
	$link = "http://".SERVER_IP."/verify.php?token=$token";
	$body = "Welcome to the SharpeConcepts Project.
Please visit the following link to verify your email address.
$link";
	SendEmail("Welcome to the SharpeConceptsProject",$body,$user["'Email'"]);
}


/*
This is called from the verify page and will retrieve the user with that token.
Then update their verified status.
*/
function VerifyEmail($dbh, $value) {
	try {
		$stmt = $dbh->prepare("SELECT Email 
			FROM User  
			WHERE SUBSTRING(PasswordHash, -8)=:tag");
		$stmt->bindParam(":tag", $value);
		$stmt->execute();
		$email = $stmt->fetch();
		//If the user exists uodate the verified tag
		if($stmt->rowCount()>=1) {
			$stmt = $dbh->prepare("UPDATE User 
				SET Verified=1 
				WHERE Email=:email");
			$stmt->bindParam(":email", $email['Email']);
			$stmt->execute();
			return $email['Email'];
		}
		return False;
	} catch(PDOException $e) {
		return $e->getMessage();
	}
}




/*
Fetch a user from the table verify the password and set a login session variable.
*/
function AttemptLoginUser($dbh, $user) {
	require_once("../data/utility.php");
	$user = SanitizeObject($user);
	if(userExists($dbh, $user["'Email'"])) {
		try {
			$storedUser = getUser($dbh, $user["'Email'"]);
			print_r($storedUser);
			#if the hashes match then this user can login.
			if(password_verify($user["'Password'"], $storedUser['PasswordHash'])) {
				if($storedUser['Verified']==1) {
					Login($storedUser);
				} else {
					return "You must verify your email before you can login.";
				}
			}
			else {
				return "The username or password is incorrect.";
			}
		} catch(PDOException $e) {
			return $e->getMessage();
		}
		return 1;
	}
	return "The username or password is incorrect.";
}

/**
Set session variables for the login.
*/
function Login($user) {
	session_start();
	$_SESSION["USER_EMAIL"] = $user['Email'];
	$_SESSION["timeout"] = time();
}



?>