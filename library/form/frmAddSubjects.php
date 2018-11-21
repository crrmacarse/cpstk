<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_CODE = $_POST['CODE'];
$form_NAME = $_POST['NAME'];
$form_ABBR = $_POST['ABBR'];
$Form_DESCRIPTION = $_POST['DESCRIPTION'];
$form_TOTALCREDIT = $_POST['TOTALCREDIT'];

$checkSubject = $db->connection->prepare("SELECT * FROM Subjects WHERE SCode = ?");
$checkSubject->bindParam(1,$form_CODE);
$checkSubject->execute();

if ($checkSubject->rowCount() < 0){
 		die("error: Subject Code already exist");
		}


$sql = "INSERT INTO Subjects(
			SName,
			SCode,
			SNameAbbr,
			SDescription,
			STotalCredit,
			SAddedBy,
			SDateCreated,
			SStatus)

		VALUES(
		'$form_NAME',
		'$form_CODE',
		'$form_ABBR',
		'$Form_DESCRIPTION',
		$form_TOTALCREDIT, 
		'$form_UPLOADER', 
		GETDATE(),
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Subject!';
}
else {
	echo 'error: Failure in Subject&apos;s submission.';
}	

?>