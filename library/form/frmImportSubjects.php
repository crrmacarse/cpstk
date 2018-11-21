<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_FILE = $_FILES['FILE'];

if (isset($form_FILE)) {
           
    $filename = $_FILES["FILE"]["tmp_name"];
 	$row = 1;

 	$file = fopen($filename, "r");

 	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
     {
     	if($row == 1){ $row++; continue; }
        $row++; 


		$checkSubject = $db->connection->prepare("SELECT * FROM Subjects WHERE SCode = ?");
		$checkSubject->bindParam(1,$getData['0']);
		$checkSubject->execute();

		if ($checkSubject->rowCount() < 0){
     		die("error: Duplicate Subject Code Found!");
			}

        $sql = "INSERT INTO Subjects
					(SCode, SNameAbbr, SName, SDescription, STotalCredit, SAddedBy, SDateCreated, SStatus)
                   values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."',$form_UPLOADER,GETDATE(),1)";
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


