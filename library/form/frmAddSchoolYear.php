<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_SCHOOLYEAR = $_POST['SCHOOLYEAR'];

$sql = "INSERT INTO SchoolYear(
			SYYear,
			SYDateCreated,
			SYStatus)

		VALUES(
		'$form_SCHOOLYEAR',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a School Year!';
}
else {
	echo 'error: Failure in School Year submission.';
}	

?>