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

			 				
	           $sql = "INSERT INTO Subjects
					(SNameAbbr, SName, SDescription, STotalCredit, SAddedBy, SDateCreated, SStatus)
                   values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."',GETDATE(),1)";
                 	$result = $db->connection->query($sql);

				if(!isset($result))
				{
					echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
						window.location.href = '../../manage-subjects.php';		
						</script>";		
				}
				else {
					  echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location.href = '../../manage-subjects.php';
					</script>";
				}
	         }
			
	         fclose($file);	
		 }
	}	 
 ?>


<?php
