<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_CODE = $_POST['CODE'];
$form_ROOMNO = $_POST['ROOMNO'];
$form_BUILDING = $_POST['BUILDING'];

$checkRoom = $db->connection->prepare("SELECT RCode FROM Room WHERE RCode = ?");
$checkRoom->bindParam(1,$form_CODE);
$checkRoom->execute();

if ($checkRoom->rowCount() < 0){
	echo "warning: Room Code already taken";
	die;
	}

$sql = "INSERT INTO Room(
			idLandmark,
			RCode,
			RNumber,
			RDateCreated,
			RStatus)

		VALUES(
		'$form_BUILDING',
		'$form_CODE',
		'$form_ROOMNO',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Room!';
}
else {
	echo 'error: Failure in Room submission.';
}	

?>