<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_CODE = $_POST['CODE'];
$form_NAME = $_POST['NAME'];
$form_ABBR = $_POST['NAMEABBR'];
$form_COLLEGE = $_POST['COLLEGE'];

$checkCode = $db->connection->prepare("SELECT CCode FROM Course WHERE CCode = ?");
$checkCode->bindParam(1,$form_CODE);
$checkCode->execute();

if ($checkCode->rowCount() < 0){
	echo "warning: Course Code already exist";
	die;
	}

$sql = "INSERT INTO Course(
			idCollege,
			CCode,
			CNameAbbr,
			CName,
			CDateCreated,
			CStatus)

		VALUES(
		$form_COLLEGE,
		'$form_CODE',
		'$form_ABBR',
		'$form_NAME',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Course!';
}
else {
	echo 'error: Failure in Course submission.';
}	

?>