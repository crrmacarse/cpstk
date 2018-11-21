<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_CSCID = $_POST['CSCID'];
$form_FILE = $_FILES['FILE'];

if (isset($form_FILE)) {
           
    $filename = $_FILES["FILE"]["tmp_name"];
 	$row = 1;

 	$file = fopen($filename, "r");

 	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
     {
     	if($row == 1){ $row++; continue; }
        $row++; 

        if($form_CSCID == ""){
					die("error: Please Select a Class Schedule First");
	         	}


        $idkwhyitdoesntworksoimadethisanditworked = $getData['0'];
        $STUDENTID = str_replace("-", "", $idkwhyitdoesntworksoimadethisanditworked);


		$checkClassControl = $db->connection->prepare("SELECT idStudent FROM Student WHERE SUsername = ?");
		$checkClassControl->bindParam(1,$STUDENTID);
		$checkClassControl->execute();
		$response = $checkClassControl->fetch();
		$responseanswer = $response['idStudent'];

		If(!$response)
		{
			die("error: Student not found");
		}

       $sql = "INSERT INTO ClassScheduleData
			(idClassScheduleControl, idStudent, CSDFinalGrade, CSDRemarks, CSDStudentYear, CSDDateCreated, CSDStatus)
           values ($form_CSCID,$responseanswer,'".$getData[1]."','".$getData[2]."','".$getData[3]."',GETDATE(),1)";
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


