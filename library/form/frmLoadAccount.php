<?php

	session_start();

	//db initialization
	include('connection.php');
	include ('../functions/functions.php');
	$db = new db();

 if(isset($_POST["Import"])){
		
		$filename=$_FILES["FILE"]["tmp_name"];		
 
 
		 if($_FILES["FILE"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {

			 				
	           $sql = "INSERT INTO Users
				(idUserType, idCourse, idUsername, UPassword, ULastName, UFirstName, UMiddleName, ULevel, UGuardianName, UDateCreated, UStatus) 
                   values (2,'".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."',GETDATE(),'".$getData[8]."')";
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