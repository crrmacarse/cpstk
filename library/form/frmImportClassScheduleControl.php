<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_PERIOD = $_POST['PERIOD'];
$form_FILE = $_FILES['FILE'];

if (isset($form_FILE)) {
           
    $filename = $_FILES["FILE"]["tmp_name"];
 	$row = 1;

 	$file = fopen($filename, "r");

 	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
     {
     	if($row == 1){ $row++; continue; }
        $row++; 

         $idkwhyitdoesntworksoimadethisanditworked = $getData['6'];
        $FACULTYID = str_replace("-", "", $idkwhyitdoesntworksoimadethisanditworked);

        if($form_PERIOD == ""){
					die("error: Please Select a Period First");
	         	}

	    $checkStubcode = $db->connection->prepare("SELECT * FROM ClassScheduleControl WHERE CSCStubCode = ? AND idPeriod = ?");
		$checkStubcode->bindParam(1,$getData['1']);
		$checkStubcode->bindParam(2,$form_PERIOD);
		$checkStubcode->execute();

		if ($checkStubcode->rowCount() < 0){
         		die("error: Stubcode for this period already exist");
				}

  		$checkSubject = $db->connection->prepare("SELECT * FROM Subjects WHERE SCode = ? AND SStatus = 1");
		$checkSubject->bindParam(1,$getData['0']);
		$checkSubject->execute();

		if (!$checkSubject->fetchColumn()){
         		die("error: Subject doesnt seem to exist");
				}


		if($getData[4] > 5 AND $getData[4] < 0)
				{
					die("error: From 1 to 5 only for Credit");
				}

		if($getData[5] != "LEC" AND $getData[5] != "LAB")
				{
					die("error: LEC or LAB is only allowed for Class Type");
				}

		$sql = "INSERT INTO ClassScheduleControl
					(idPeriod, SCode, CSCStubCode, CSCStart, CSCEnd, CSCDay, CSCClassType, FCode, RCode, CSCCredit, CSCDateCreated, CSCStatus)
                   values ($form_PERIOD,'".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."',
                   '".$getData[4]."','".$getData[5]."','$FACULTYID','".$getData[7]."','".$getData[8]."',GETDATE(),1)";
                 	$result = $db->connection->query($sql);

     //           			$sql = "INSERT INTO ClassScheduleControl
					// (idPeriod, idSubject, idRoom, idFaculty, CSCStubCode, CSCCredit, CSCClassType, CSCTime, CSCDay, CSCRoomCode, CSCTeacherName, CSCClassStatus, CSCLoadHR, CSCMaxSeat, CSCTotalEnrolled, CSCClassStart, CSCDateCreated, CSCStatus)
     //               values ($form_PERIOD,'".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."',
     //               '".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."','".$getData[8]."','".$getData[9]."',
     //               '".$getData[10]."','".$getData[11]."','".$getData[12]."','".$getData[13]."','".$getData[14]."',GETDATE(),1)";
     //             	$result = $db->connection->query($sql);

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


