<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_USERNAME = $_POST['USERNAME'];
$form_PASSWORD = $_POST['PASSWORD'];

// There is no hashing in student password ky tamad. ty

$sql = "UPDATE Student SET
		SPassword = :password

		WHERE idStudent = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':password', $form_PASSWORD);
$getResult->bindParam(':id', $form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>