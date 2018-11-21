<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_SCHOOLYEAR = $_POST['SCHOOLYEAR'];
$form_PERIOD = $_POST['PERIODNAME'];
$form_PERIODSTART = $_POST['PERIODSTART'];
$form_PERIODEND = $_POST['PERIODEND'];


$check = $db->connection->prepare("SELECT PName FROM Period WHERE PName = ? AND idSchoolYear = ?");
$check->bindParam(1,$form_PERIOD);
$check->bindParam(2,$form_SCHOOLYEAR);
$check->execute();

if ($check->rowCount() < 0){
	echo "warning: Period already taken";
	die;
	}

$sql = "INSERT INTO Period(
			idSchoolYear,
			PName,
			PStart,
			PEnd,
			PDateCreated,
			PStatus)

		VALUES(
		$form_SCHOOLYEAR,
		'$form_PERIOD',
		'$form_PERIODSTART',
		'$form_PERIODEND',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Period Year!';
}
else {
	echo 'error: Failure in Period Year submission.';
}	

?>