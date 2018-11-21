<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_CLASSSCHEDCONTROL = $_POST['CLASSSCHEDCONTROL'];
$form_STUDENT = $_POST['STUDENT'];
$form_GRADE = $_POST['GRADE'];
$form_REMARKS = "";

if(!in_array($form_GRADE,array(1, 1.00, 1.0, 1.25, 1.5, 1.75, 2, 2.0, 2.25, 2.5, 2.75, 2.00, 3.00, 3, 3.0, 5, 5.0, 5.00)))
{
	die("error: Grades inputed must be compliant to the Score Grading System");
}

$checkList = $db->connection->prepare("SELECT idStudent FROM ClassScheduleData WHERE idStudent = ? AND idClassScheduleControl = ?");
$checkList->bindParam(1,$form_STUDENT);
$checkList->bindParam(2,$form_CLASSSCHEDCONTROL);
$checkList->execute();

if ($checkList->rowCount() < 0){
	echo "warning: Student already exists";
	die;
	}

$sql = "INSERT INTO ClassScheduleData(
			idClassScheduleControl,
			idStudent,
			CSDFinalGrade,
			CSDRemarks,
			CSDDateCreated,
			CSDStatus)

		VALUES(
		$form_CLASSSCHEDCONTROL,
		$form_STUDENT,
		$form_GRADE,
		'$form_REMARKS',
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully added a Student!';
}
else {
	echo 'error: Failure in Student submission.';
}	

?>