<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_USERNAME = $_POST['USERNAME'];
$form_CODE = str_replace("-", "", $_POST['CODE']);
$form_PASSWORD = $_POST['PASSWORD'];
$form_FACULTYTYPE = $_POST['FACULTYTYPE'];
$form_COLLEGE = $_POST['COLLEGE'];
$form_FIRSTNAME = $_POST['FIRSTNAME'];
$form_MIDDLENAME = $_POST['MIDDLENAME'];
$form_LASTNAME = $_POST['LASTNAME'];


$checkCodeAndUserName = $db->connection->prepare("SELECT * FROM Faculty WHERE FUsername = ? AND FCode = ?");
$checkCodeAndUserName->bindParam(1,$form_USERNAME);
$checkCodeAndUserName->bindParam(2,$form_CODE);
$checkCodeAndUserName->execute();

if ($checkCodeAndUserName->rowCount() < 0){
		die("error: Faculty Code / Username already exists!");
	}


$encryptedusername = md5($form_USERNAME);
$encryptedpassword = md5($form_PASSWORD);

$usernamecount = strlen($form_USERNAME);

$salt = substr($encryptedusername, 0, $usernamecount);


$hash1 = substr($encryptedusername, 0,$usernamecount);
$hash2 = substr($encryptedpassword, 0, $usernamecount +1);

$hashed_password = $hash1.$salt.$hash2;

$checkUsername = $db->connection->prepare("SELECT FUsername FROM Faculty WHERE FUsername = ?");
$checkUsername->bindParam(1,$form_USERNAME);
$checkUsername->execute();

if ($checkUsername->rowCount() == 0){
		$sql = "INSERT INTO Faculty(
				idFacultyType, 
				idCollege,
				FCode,
				FUsername,
				FPassword,
				FLastName,
				FFirstName,
				FMiddleName, 
				FDateCreated, 
				FStatus)

			VALUES(
			$form_FACULTYTYPE,
			$form_COLLEGE,
			'$form_CODE',
			'$form_USERNAME',
			'$hashed_password',
			'$form_LASTNAME', 
			'$form_FIRSTNAME', 
			'$form_MIDDLENAME', 
			GETDATE(), 
			1
			)";

	$getResult = $db->connection->prepare($sql);
	if($getResult->execute()) {
		echo 'success: Successfully created Faculty!';
	}
	else {
		echo 'error: Failure in Faculty submission.';
	}	
}
else
{
		echo 'warning: Username already exists.';
}



?>