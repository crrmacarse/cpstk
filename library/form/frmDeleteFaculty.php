<?php
 
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_ID = $_POST['ID'];

if($form_ID == 1){
	echo 'warning: Genesis User deletion is invalid';
	die; 
}

$sql = "DELETE FROM Faculty WHERE idFaculty = $form_ID";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Deletion Success!';
}
else {
	echo 'error: Deletion Failed.';
}
?>