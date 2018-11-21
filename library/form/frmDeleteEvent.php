<?php
 
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_ID = $_POST['ID'];

$sql = "DELETE FROM EventsList WHERE idEventsList = $form_ID";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Deletion Success!';
}
else {
	echo 'error: Deletion Failed.';
}
?>