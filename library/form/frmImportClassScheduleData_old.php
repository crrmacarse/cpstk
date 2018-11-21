<?php

session_start();


include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_CSCID = $_POST['CSCID'];

 if(isset($_POST["Import"])){
		
		$filename=$_FILES["FILE"]["tmp_name"];		
 
 
		 if($_FILES["FILE"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {

				$checkUsername = $db->connection->prepare("SELECT * FROM ClassScheduleData WHERE idStudent = ? AND idClassScheduleControl = ?");
				$checkUsername->bindParam(1,$getData['0']);
				$checkUsername->bindParam(2,$form_CSCID);
				$checkUsername->execute();

				if ($checkUsername->rowCount() < 0){
	         		echo "<script type=\"text/javascript\">
						alert(\"Student already exisits \");
						window.location.href = '../../manage-class-schedule-data.php?id=". $form_CSCID. "';
					</script>";
					exit;
					}
	
	           $sql = "INSERT INTO ClassScheduleData
					(idClassScheduleControl, idStudent, CSDFinalGrade, CSDDateCreated, CSDStatus)
                   values ($form_CSCID,'".$getData[0]."','".$getData[1]."',GETDATE(),1)";
                 	$result = $db->connection->query($sql);

				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						window.location.href = '../../manage-class-schedule-data.php?id=". $form_CSCID. "';		
						</script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location.href = '../../manage-class-schedule-data.php?id=". $form_CSCID. "';
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 ?>


<?php
