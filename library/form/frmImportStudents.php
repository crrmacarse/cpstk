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

        $idkwhyitdoesntworksoimadethisanditworked = $getData['0'];
        $form_USERNAME = str_replace("-", "", $idkwhyitdoesntworksoimadethisanditworked);

	 	if(strlen($form_USERNAME) !== 8)
	     	{
	     		die("error: Username / Student ID could only accept 8 numerical numbers" . $form_USERNAME);
	     	}

		$checkUsername = $db->connection->prepare("SELECT SUsername FROM Student WHERE SUsername = ?");
		$checkUsername->bindParam(1,$form_USERNAME);
		$checkUsername->execute();

		if ($checkUsername->rowCount() < 0){
     		die("error: Username / Student ID already exists!");
			}
	
		$checkCourse = $db->connection->prepare("SELECT idCourse FROM Course WHERE CCode = ?");
		$checkCourse->bindParam(1,$getData['1']);
		$checkCourse->execute();

		if (!$checkCourse->fetchColumn()){
			die("error: Course doesnt seem to exist. Please use a valid Course ID");
			}

        $sql = "INSERT INTO Student
					(CCode, SUsername, SPassword, SLastName, SFirstName, SMiddleName, SYearLevel, SGuardianName, SDateCreated, SStatus)
                   values ('".$getData[1]."','$form_USERNAME','123','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','God',GETDATE(),1)";
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


