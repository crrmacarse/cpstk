<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_PERIOD = $_POST['PERIOD'];
$form_STUBCODE = $_POST['STUBCODE'];
$form_SUBJECT = $_POST['SUBJECT'];
$form_ROOMNUMBER = $_POST['ROOMNUMBER'];
$form_FACULTY = $_POST['FACULTY'];
$form_START = $_POST['START'];
$form_END = $_POST['END'];
$form_DAY = $_POST['DAY'];
$form_CREDIT = $_POST['CREDIT'];
$form_CLASSTYPE = $_POST['CLASSTYPE'];


if($form_START > $form_END)
{
	die("warning: End of Class Schedule must be higher than Class Schedule Start");
}


$checkStubcode = $db->connection->prepare("SELECT CSCStubCode FROM ClassScheduleControl WHERE CSCStubCode = ? AND idPeriod = ?");
$checkStubcode->bindParam(1,$form_STUBCODE);
$checkStubcode->bindParam(2,$form_PERIOD);
$checkStubcode->execute();

if ($checkStubcode->rowCount() < 0){
	echo "warning: Stubcode Already exists";
	die;
	}

$sql = "INSERT INTO ClassScheduleControl(
			idPeriod,
			SCode,
			RCode,
			FCode,
			CSCStubCode,
			CSCCredit,
			CSCClassType,
			CSCDay,
			CSCStart,
			CSCEnd,
			CSCDateCreated,
			CSCStatus)

		VALUES(
		$form_PERIOD,
		'$form_SUBJECT',
		'$form_ROOMNUMBER',
		'$form_FACULTY',
		'$form_STUBCODE',
		$form_CREDIT,
		'$form_CLASSTYPE',
		'$form_DAY',
		'$form_START',
		'$form_END',
		GETDATE(),	
		1
		)";

$getResult = $db->connection->prepare($sql);
if($getResult->execute()) {
	echo 'success: Successfully created a Class Schedule!';
}
else {
	echo 'error: Failure in Class Schedule submission.';
}	

?>