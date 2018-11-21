<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_USERNAME = $_POST['USERNAME'];
$form_FIRSTNAME = $_POST['FIRSTNAME'];
$form_MIDDLENAME = $_POST['MIDDLENAME'];
$form_LASTNAME = $_POST['LASTNAME'];
$form_COURSE = $_POST['COURSE'];
$form_YEARLEVEL = $_POST['YEARLEVEL'];
$form_GUARDIAN = $_POST['GUARDIAN'];
$form_STATUS = $_POST['STATUS'];

$sql = "UPDATE Student SET
		CCode = :code,
		SUsername = :username,
		SLastName = :lastname,
		SFirstName = :firstname,
		SMiddleName = :middlename,
		SYearLevel = :yearlevel,
		SGuardianName = :guardianname,
		SStatus = :status

		WHERE idStudent = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':username', $form_USERNAME);
$getResult->bindParam(':code', $form_COURSE);
$getResult->bindParam(':lastname', $form_LASTNAME);
$getResult->bindParam(':firstname', $form_FIRSTNAME);
$getResult->bindParam(':middlename', $form_MIDDLENAME);
$getResult->bindParam(':yearlevel', $form_YEARLEVEL);
$getResult->bindParam(':guardianname', $form_GUARDIAN);
$getResult->bindParam(':status', $form_STATUS);
$getResult->bindParam(':id',$form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>