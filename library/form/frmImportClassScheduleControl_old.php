<?php

session_start();


include('connection.php');
include ('../functions/functions.php');
include('../functions/checkSession.php');
$db = new db();

$form_PERIOD = $_POST['PERIOD'];

 if(isset($_POST["Import"])){
		
		$filename=$_FILES["FILE"]["tmp_name"];		
 
 
		 if($_FILES["FILE"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
	         	if($form_PERIOD == ""){
	         		echo "<script type=\"text/javascript\">
						alert(\"Please Select a Period First \");
						window.location.href = '../../manage-class-schedule.php';
					</script>";
					exit;
	         	}

				$checkStubcode = $db->connection->prepare("SELECT * FROM ClassScheduleControl WHERE CSCStubCode = ? AND idPeriod = ?");
				$checkStubcode->bindParam(1,$getData['3']);
				$checkStubcode->bindParam(2,$form_PERIOD);
				$checkStubcode->execute();

				if ($checkStubcode->rowCount() < 0){
	         		echo "<script type=\"text/javascript\">
						alert(\"Stubcode already exisits \");
						window.location.href = '../../manage-class-schedule.php?period=". $form_PERIOD. "';
					</script>";
					exit;
					}

				if($getData[4] > 5 AND $getData[4] < 0)
				{
					echo "<script type=\"text/javascript\">
						alert(\"From 1 to 5 only for Credit \");
						window.location.href = '../../manage-class-schedule.php?period=". $form_PERIOD. "';
					</script>";
					exit;
				}

				if($getData[5] != "LEC" AND $getData[5] != "LAB")
				{
					echo "<script type=\"text/javascript\">
						alert(\"LEC or LAB is only allowed for Class Type " . $getData[5] ."\");
						window.location.href = '../../manage-class-schedule.php?period=". $form_PERIOD. "';
					</script>";
					exit;
				}

	
	           $sql = "INSERT INTO ClassScheduleControl
					(idPeriod, idSubject, idRoom, idFaculty, CSCStubCode, CSCCredit, CSCClassType, CSCTime, CSCDay, CSCDateCreated, CSCStatus)
                   values ($form_PERIOD,'".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."',
                   '".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."',GETDATE(),1)";
                 	$result = $db->connection->query($sql);

				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						window.location.href = '../../manage-class-schedule.php?period=". $form_PERIOD. "';		
						</script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location.href = '../../manage-class-schedule.php?period=". $form_PERIOD. "';
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 ?>


<?php
