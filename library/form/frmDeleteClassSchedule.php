<?php
 
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$sql = "TRUNCATE TABLE ClassScheduleFinal";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Deletion Success!';
}
else {
	echo 'error: Deletion Failed.';
}
?>