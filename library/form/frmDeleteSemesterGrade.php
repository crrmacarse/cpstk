<?php
 
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_SCHOOLYEAR = $_POST['YEAR'];
$form_SEMESTER = $_POST['SEMESTER'];

$sql = "DELETE FROM GradeFinal WHERE ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ? ";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(1, $form_SCHOOLYEAR);
$getResult->bindParam(2, $form_SEMESTER);

if($getResult->execute()) {
	echo 'success: Succesfully Deleted the specified Semester Grades!';
}
else {
	echo 'error: Deletion Failed.';
}
?>