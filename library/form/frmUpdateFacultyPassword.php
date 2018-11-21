<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_USERNAME = $_POST['USERNAME'];
$form_PASSWORD = $_POST['PASSWORD'];

$encryptedusername = md5($form_USERNAME);
$encryptedpassword = md5($form_PASSWORD);

$usernamecount = strlen($form_USERNAME);

$salt = substr($encryptedusername, 0, $usernamecount);

$hash1 = substr($encryptedusername, 0,$usernamecount);
$hash2 = substr($encryptedpassword, 0, $usernamecount +1);

$hashed_password = $hash1.$salt.$hash2;

$sql = "UPDATE Faculty SET
		FPassword = :password

		WHERE idFaculty = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':password', $hashed_password);
$getResult->bindParam(':id', $form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>