<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_NAME = $_POST['NAME'];

$sql = "INSERT INTO FacultyType(
			FTName,
			FTDateCreated,
			FTStatus)

		VALUES(
		'$form_NAME',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Faculty Type!';
}
else {
	echo 'error: Failure in Faculty Type submission.';
}	

?>