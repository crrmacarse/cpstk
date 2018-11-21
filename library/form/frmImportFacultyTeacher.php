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

		$checkUsername = $db->connection->prepare("SELECT FUsername FROM Faculty WHERE FUsername = ?");
		$checkUsername->bindParam(1,$getData['1']);
		$checkUsername->execute();

		if ($checkUsername->rowCount() < 0){
     		die("error: Username already exists!");
			}

		$checkCollege = $db->connection->prepare("SELECT * FROM College WHERE idCollege = ?");
		$checkCollege->bindParam(1,$getData['0']);
		$checkCollege->execute();

		if (!$checkCollege->fetchColumn()){
			die("error: College doesnt seem to exist. Please use a valid College ID");
			}


        $sql = "INSERT INTO Faculty
					(idFacultyType, idCollege, FUsername, FPassword, FFirstName, FMiddleName, FLastName, FDateCreated, FStatus)
                   values (5,'".$getData[0]."','".$getData[1]."','teacher','".$getData[2]."','".$getData[3]."','".$getData[4]."',GETDATE(),1)";
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


