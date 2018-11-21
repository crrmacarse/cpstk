<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_NAME = $_POST['NAME'];
$form_ABBR = $_POST['NAMEABBR'];
$form_BUILDING = $_POST['BUILDING'];
$form_DEANNAME = $_POST['DEANNAME'];
$form_HISTORY = $_POST['HISTORY'];

$sql = "INSERT INTO College(
			idLandmark,
			CName,
			CNameAbbr,
			CDean,
			CHistory,
			CAddedBy,
			CDateCreated,
			CStatus
			)

		VALUES(
		$form_BUILDING,
		'$form_NAME',
		'$form_ABBR',
		'$form_DEANNAME',
		'$form_HISTORY',
		$form_UPLOADER,
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Landmark!';
}
else {
	echo 'error: Failure in Landmark&apos;s submission.';
}	

?>