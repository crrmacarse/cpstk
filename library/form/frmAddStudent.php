<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_USERNAME = $_POST['USERNAME'];
$form_PASSWORD = $_POST['PASSWORD'];
$form_COURSE = $_POST['COURSE'];
$form_FIRSTNAME = $_POST['FIRSTNAME'];
$form_MIDDLENAME = $_POST['LASTNAME'];
$form_LASTNAME = $_POST['LASTNAME'];
$form_YEARLEVEL = $_POST['YEARLEVEL'];
$form_GUARDIAN = $_POST['GUARDIAN'];


$checkUsername = $db->connection->prepare("SELECT SUsername FROM Student WHERE SUsername = ?");
$checkUsername->bindParam(1,$form_USERNAME);
$checkUsername->execute();

if ($checkUsername->rowCount() < 0){
	echo "warning: Username already taken";
	die;
	}

$sql = "INSERT INTO Student(
			CCode, 
			SUsername, 
			SPassword, 
			SLastName, 
			SFirstName, 
			SMiddleName,
			SYearLevel,
			SGuardianName, 
			SDateCreated, 
			SStatus)

		VALUES(
		'$form_COURSE',
		$form_USERNAME,
		'$form_PASSWORD',
		'$form_LASTNAME', 
		'$form_FIRSTNAME', 
		'$form_MIDDLENAME',
		'$form_YEARLEVEL',
		'$form_GUARDIAN',
		GETDATE(), 
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created an account!';
}
else {
	echo 'error: Failure in account submission.';
}	

?>