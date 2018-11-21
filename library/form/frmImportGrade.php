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

       $STUDID = str_replace("-", "", $getData['2']);
       
       $sql = "INSERT INTO GradeFinal
		(STUBCODE, SUBJCODE, STIDNUM, COURSE, STYEAR, STGRADE, STATTEND, GRADERMK, ACADEMICSCHOOLYEAR, ACADEMICSEMESTERID)
	   values ('".$getData[0]."','".$getData[1]."',$STUDID,'".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."','".$getData[7]."','".$getData[8]."','".$getData[9]."')";
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


