<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_CODE = str_replace("-", "", $_POST['CODE']);
$form_USERNAME = $_POST['USERNAME'];
$form_FACULTYTYPE = $_POST['FACULTYTYPE'];
$form_COLLEGE = $_POST['COLLEGE'];
$form_FIRSTNAME = $_POST['FIRSTNAME'];
$form_MIDDLENAME = $_POST['MIDDLENAME'];
$form_LASTNAME = $_POST['LASTNAME'];
$form_STATUS = $_POST['STATUS'];

$checkCodeAndUserName = $db->connection->prepare("SELECT * FROM Faculty WHERE idFaculty <> ? AND (FUsername = ? OR FCode = ?)");
$checkCodeAndUserName->bindParam(1,$form_ID);
$checkCodeAndUserName->bindParam(2,$form_USERNAME);
$checkCodeAndUserName->bindParam(3,$form_CODE);
$checkCodeAndUserName->execute();

if ($checkCodeAndUserName->rowCount() < 0){
		die("error: Faculty Code / Username already exists!");
	}


$sql = "UPDATE Faculty SET
		idFacultyType = :idfacultytype,
		idCollege = :idcollege,
		FCode = :code,
		FUsername = :username,
		FLastName = :lastname,
		FFirstName = :firstname,
		FMiddleName = :middlename,
		FStatus = :status

		WHERE idFaculty = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':username', $form_USERNAME);
$getResult->bindParam(':code', $form_CODE);
$getResult->bindParam('idfacultytype', $form_FACULTYTYPE);
$getResult->bindParam(':idcollege', $form_COLLEGE);
$getResult->bindParam(':lastname', $form_LASTNAME);
$getResult->bindParam(':firstname', $form_FIRSTNAME);
$getResult->bindParam(':middlename', $form_MIDDLENAME);
$getResult->bindParam(':status', $form_STATUS);
$getResult->bindParam(':id',$form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>