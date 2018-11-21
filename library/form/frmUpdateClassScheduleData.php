<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_GRADE = $_POST['GRADE'];
$form_REMARKS = $_POST['REMARKS'];

if(!in_array($form_GRADE,array(1, 1.00, 1.0, 1.25, 1.5, 1.75, 2, 2.0, 2.25, 2.5, 2.75, 2.00, 3.00, 3, 3.0, 5, 5.0, 5.00)))
{
	die("error: Grades inputed must be compliant to the Score Grading System");
}

$sql = "UPDATE ClassScheduleData SET
		CSDFinalGrade = :grade,
		CSDRemarks = :remarks

		WHERE idClassScheduleData = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':grade', $form_GRADE);
$getResult->bindParam(':remarks', $form_REMARKS);
$getResult->bindParam(':id',$form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating.';
}
?>