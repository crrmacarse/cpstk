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

        $FACULTYID = str_replace("-", "", $getData['7']);
        $STUDID = str_replace("-", "", $getData['0']);


		$sql = "INSERT INTO ClassScheduleFinal
					(STUDID, SUBJCODE, STUBCODE, STARTCLASS, ENDCLASS, CLASSDAYS, CLASSTYPE, EMPLOYEEID, EMPLOYEENAME, ROOMCODE, CLASSSTATUSCODE)
                   values ($STUDID,'".$getData[1]."','".$getData[2]."','".$getData[3]."',
                   '".$getData[4]."','".$getData[5]."','".$getData[6]."','$FACULTYID','".$getData[8]."','".$getData[9]."','".$getData[10]."')";
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


