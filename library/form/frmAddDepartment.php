<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_NAME = $_POST['NAME'];
$form_ABBR = $_POST['NAMEABBR'];
$form_BUILDING = $_POST['BUILDING'];
$form_COLLEGE = $_POST['COLLEGE'];

$sql = "INSERT INTO Department(
			idCollege,
			idLandmark,
			DName,
			DNameAbbr,
			DDateCreated,
			DStatus)

		VALUES(
		$form_COLLEGE,
		$form_BUILDING,
		'$form_NAME',
		'$form_ABBR',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Department!';
}
else {
	echo 'error: Failure in Department&apos;s submission.';
}	

?>