<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

 if(isset($_POST["Import"])){
		
		$filename=$_FILES["FILE"]["tmp_name"];		
 
 
		 if($_FILES["FILE"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
	         	if(strlen($getData[1]) !== 8)
	         	{
	         		echo "<script type=\"text/javascript\">
						alert(\"Username could only accept 8 numerical numbers.\");
						window.location.href = '../../manage-students.php';
					</script>";
					exit;
	         	}


				$checkUsername = $db->connection->prepare("SELECT SUsername FROM Student WHERE SUsername = ?");
				$checkUsername->bindParam(1,$getData['1']);
				$checkUsername->execute();

				if ($checkUsername->rowCount() < 0){
	         		echo "<script type=\"text/javascript\">
						alert(\"Username already exisits \");
						window.location.href = '../../manage-students.php';
					</script>";
					exit;
					}

				$checkCourse = $db->connection->prepare("SELECT idCourse FROM Course WHERE idCourse = ?");
				$checkCourse->bindParam(1,$getData['0']);
				$checkCourse->execute();

				if ($checkCourse->rowCount() < 0){
	         		echo "<script type=\"text/javascript\">
						alert(\"Course doesn't seem to exist. Please use a valid Course [ID]\");
						window.location.href = '../../manage-students.php';
					</script>";
					}
	
	           $sql = "INSERT INTO Student
					(idCourse, SUsername, SPassword, SLastName, SFirstName, SMiddleName, SYearLevel, SGuardianName, SDateCreated, SStatus)
                   values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."',GETDATE(),1)";
                 	$result = $db->connection->query($sql);

				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						window.location.href = '../../manage-students.php';		
						</script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location.href = '../../manage-students.php';
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 ?>


<?php
