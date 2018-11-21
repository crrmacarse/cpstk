<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['idEmployee'];
$form_USERNAME = $_POST['USERNAME'];
$form_PASSWORD = $_POST['PASSWORD'];
$form_USERTYPE = $_POST['USERTYPE'];
$form_FIRSTNAME = $_POST['FIRSTNAME'];
$form_MIDDLENAME = $_POST['MIDDLENAME'];
$form_LASTNAME = $_POST['LASTNAME'];


$sql = "INSERT INTO Employee(
			idUserType, 
			EMPUsername, 
			EMPPassword, 
			EMPLastName, 
			EMPFirstName, 
			EMPMiddleName, 
			EMPDateCreated, 
			EMPStatus)

		VALUES(
		$form_USERTYPE,
		'$form_USERNAME',
		'$form_PASSWORD',
		'$form_LASTNAME', 
		'$form_FIRSTNAME', 
		'$form_MIDDLENAME', 
		GETDATE(), 
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created account!';
}
else {
	echo 'error: Failure in account submission.';
}	

?>