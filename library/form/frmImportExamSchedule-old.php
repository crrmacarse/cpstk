<?php

session_start();

include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_PERIOD = $_POST['PERIOD'];
$form_TERM = $_POST['TERM'];
$form_FILE = $_FILES['FILE'];

 if(isset($_POST["Import"])){
		
		$filename=$_FILES["FILE"]["tmp_name"];		
 
		 if($_FILES["FILE"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {

	           $sql = "INSERT INTO ExamSchedule
					(idClassScheduleControl, idTerm, idRoom, idFaculty, ESDate, ESStart, ESEnd, ESDateCreated, ESAddedBy, ESStatus)
                   values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."',GETDATE(),1,1)";
                 	$result = $db->connection->query($sql);


				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						window.location.href = '../../manage-examination-schedule.php';		
						</script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location.href = '../../manage-examination-schedule.php';
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 ?>