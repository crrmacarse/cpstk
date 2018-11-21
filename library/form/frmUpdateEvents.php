<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_STATUS = $_POST['STATUS'];

$sql = "UPDATE EventsList SET
		ELStatus = :status

		WHERE idEventsList = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':status', $form_STATUS);
$getResult->bindParam(':id', $form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>