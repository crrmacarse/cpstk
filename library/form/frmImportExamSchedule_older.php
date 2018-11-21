<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_FILE = $_FILES['FILE'];

if (isset($form_FILE)) {
           
    $filename = $_FILES["FILE"]["tmp_name"];
 	$row = 1;

 	$file = fopen($filename, "r");

 	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
     {
     	if($row == 1){ $row++; continue; }
        $row++; 

        $checkClassControl = $db->connection->prepare("SELECT idClassScheduleControl FROM ClassScheduleControl WHERE CSCStubCode = ? AND idPeriod = ?");
		$checkClassControl->bindParam(1,$getData['0']);
		$checkClassControl->bindParam(2,$getData['1']);
		$checkClassControl->execute();
		$response = $checkClassControl->fetch();
		$responseanswer = $response['idClassScheduleControl'];

		If(!$response)
		{
			die("error: NO Class Schedule exist");
		}

		$chechkExam = $db->connection->prepare("SELECT * FROM ExamSchedule WHERE idClassScheduleControl = ? AND idTerm = ?");
		$chechkExam->bindParam(1,$responseanswer);
		$chechkExam->bindParam(2,$getData['2']);
		$chechkExam->execute();

		if ($chechkExam->rowCount() < 0){
     		die("error: Exam Schedule is already existing");
			}

       $sql = "INSERT INTO ExamSchedule
		(idClassScheduleControl, idTerm, ESSName, ESDate, ESStart, ESEnd, ESProctorName, ESRoomCode, ESDateCreated, ESStatus)
	   values ($responseanswer,'".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."','".$getData[8]."',GETDATE(),1)";
	 	$result = $db->connection->query($sql);
		
		if(!isset($result))
				{
					die("error: Import Failed: Database Query Conflict");
				}
     } 

	fclose($file);

	echo 'success: Successfully Imported';	
    
} else {
	echo 'error: Import Failed.';

};

?>


