<?php

session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();


$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_ID = $_POST['ID'];
$form_CODE = $_POST['CODE'];
$form_NAMEABBR = $_POST['SUBJECTABBR'];
$form_NAME = $_POST['TITLE'];
$form_DESCRIPTION = $_POST['DESCRIPTION'];
$form_TOTALCREDIT = $_POST['TOTALCREDIT'];
$form_STATUS = $_POST['STATUS'];

$checkSubject = $db->connection->prepare("SELECT * FROM Subjects WHERE idSubjects <> ? AND SCode = ?");
$checkSubject->bindParam(1,$form_ID);
$checkSubject->bindParam(2,$form_CODE);
$checkSubject->execute();

if ($checkSubject->rowCount() < 0){
 		die("error: Subject Code already exist");
		}

$sql = "UPDATE Subjects SET
		SCode = :code,
		SName = :name,
		SNameAbbr = :nameabbr,
		SDescription = :description,
		STotalCredit = :totalcredit,
		SSTatus = :status

		WHERE idSubjects = :id
		";

$getResult = $db->connection->prepare($sql);
$getResult->bindParam(':code',$form_CODE);
$getResult->bindParam(':name',$form_NAME);
$getResult->bindParam(':nameabbr',$form_NAMEABBR);
$getResult->bindParam(':description', $form_DESCRIPTION);
$getResult->bindParam(':totalcredit', $form_TOTALCREDIT);
$getResult->bindParam(':status',$form_STATUS);	
$getResult->bindParam(':id',$form_ID);

if($getResult->execute()) {
	echo 'success: ' . $form_ID;
}
else {
	echo 'error: Failure in updating report.';
}
?>