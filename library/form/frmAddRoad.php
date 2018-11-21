<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['idEmployee'];
$form_RNAME = $_POST['RNAME'];
$form_idBUILDING = $_POST['idBuilding'];
$form_RX1 = $_POST['RX1'];
$form_RY1 = $_POST['RY1'];
$form_RX2 = $_POST['RX2'];
$form_RY2 = $_POST['RY2'];
$form_ISBUILDINGCONNECTED = $_POST['ISBUILDINGCONNECTED'];

$sql = "INSERT INTO Road(
			RName,
			idBuilding,
			RX1,
			RY1,
			RX2,
			RY2,
			RIsBuildingRoad,
			RDateCreated,
			RStatus
		)
		VALUES
		(
		'$form_RNAME',
		$form_idBUILDING,
		$form_RX1,
		$form_RY1,
		$form_RX2,
		$form_RY2,
		$form_ISBUILDINGCONNECTED,
		GETDATE(),
		1
		)";

	$getResult = $db->connection->prepare($sql);
	if($getResult->execute()) {
		echo 'success: Successfully added Road!';
	}
	else {
		echo 'error: Failure in adding Road.';
	}	

?>