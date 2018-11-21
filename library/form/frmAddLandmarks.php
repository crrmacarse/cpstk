<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_NAME = $_POST['NAME'];
$form_ABBR = $_POST['NAMEABBR'];
$form_DESCRIPTION = $_POST['DESCRIPTION'];

$sql = "INSERT INTO Landmark(
			LName,
			LNameAbbr,
			LDescription,
			LDateCreated,
			Status)

		VALUES(
		'$form_NAME',
		'$form_ABBR',
		'$form_DESCRIPTION',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Subject!';
}
else {
	echo 'error: Failure in Subject&apos;s submission.';
}	

?>