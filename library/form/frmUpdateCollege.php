<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_NAMEABBR = $_POST['NAMEABBR'];
$form_NAME = $_POST['NAME'];
$form_DEANNAME = $_POST['DEANNAME'];
$form_HISTORY = $_POST['HISTORY'];
$form_BUILDING = $_POST['BUILDING'];
$form_STATUS = $_POST['STATUS'];

$sql = "UPDATE College SET
		idLandmark = :building,
		CNameAbbr = :nameabbr,
		CName = :name,
		CDean = :deanname,
		CHistory = :history,
		CStatus = :status

		WHERE idCollege = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':building', $form_BUILDING);
$getResult->bindParam(':nameabbr', $form_NAMEABBR);
$getResult->bindParam(':name', $form_NAME);
$getResult->bindParam(':deanname', $form_DEANNAME);
$getResult->bindParam(':history', $form_HISTORY);
$getResult->bindParam(':status', $form_STATUS);
$getResult->bindParam(':id',$form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>