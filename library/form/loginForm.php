<?php

	session_start();

	//db initialization 
	
	include('connection.php');
	include ('../functions/functions.php');
	$db = new db();

	//form variables
	
	$form_USERNAME = $_POST['USERNAME'];
	$form_PASSWORD = $_POST['PASSWORD'];

	// hashed password. not advisable

	$encryptedusername = md5($form_USERNAME);
	$encryptedpassword = md5($form_PASSWORD);

	$usernamecount = strlen($form_USERNAME);

	$salt = substr($encryptedusername, 0, $usernamecount);


	$hash1 = substr($encryptedusername, 0,$usernamecount);
	$hash2 = substr($encryptedpassword, 0, $usernamecount +1);

	$hashed_password = $hash1.$salt.$hash2;

	//sql

	$sql = "SELECT 
					
			Faculty.idFaculty, 
			FacultyType.FTName, 
			College.CName, 
			Faculty.FUsername, 
			Faculty.FPassword, 
			Faculty.FLastName, 
			Faculty.FFirstName, 
			Faculty.FMiddleName,
			Faculty.FStatus, 
			Faculty.FDateCreated
			
			FROM Faculty
			
			INNER JOIN FacultyType
			ON Faculty.idFacultyType = FacultyType.idFacultyType
			INNER JOIN College 
			ON Faculty.idCollege = College.idCollege 

			WHERE Faculty.FUsername =?";
	
	$getResult = $db->connection->prepare($sql);
	$getResult->bindparam(1, $form_USERNAME);
	$getResult->execute();
	$count = $getResult->rowCount();
	$result = $getResult->fetch();

	// result variables to be passed for verification. 

	/* 
		Multiple Username is invalid in this approach. add filters in registration
	*/

	$result_ID = $result['idFaculty'];
	$result_FACULTYTYPE = $result['FTName'];
	$result_COLLEGE = $result['CName'];
	$result_USERNAME = $result['FUsername'];
	$result_PASSWORD = $result['FPassword'];
	$result_FIRSTNAME = $result['FFirstName'];
	$result_MIDDLENAME = $result['FMiddleName'];
	$result_LASTNAME = $result['FLastName'];
	$result_STATUS = $result['FStatus'];
	$result_DATECREATED = $result['FDateCreated'];

	// simple verification process
	
	if($count < 0)
	{
		if($result_STATUS == True)
		{
			if($result_PASSWORD == $hashed_password)
			{
				// determine the faculty type

				switch ($result_FACULTYTYPE)
				{
					case 'Administrator':
						echo "success: admin";
						break;
					case 'Secretary':
						echo "success: secretary";
						break;
					case 'Information Center':
						echo "success: infocenter";
						break;
					case 'Schedule Coordinator':
						echo "success: schedcoord";
						break;
					case 'Teacher':	
						echo "success: teacher";
						break;
					Default:
						echo 'error:';
						exit;
						break;
				}

					// creates a session variable

					$_SESSION['CURRENT_ID'] = $result_ID;
					$_SESSION['CURRENT_FACULTYTYPE'] = $result_FACULTYTYPE;
					$_SESSION['CURRENT_COLLEGE'] = $result_COLLEGE;
					$_SESSION['CURRENT_USERNAME'] = $result_USERNAME;
					$_SESSION['CURRENT_PASSWORD'] = $result_PASSWORD; 
					$_SESSION['CURRENT_FIRSTNAME'] = $result_FIRSTNAME; 
					$_SESSION['CURRENT_MIDDLENAME'] = $result_MIDDLENAME;
					$_SESSION['CURRENT_LASTNAME'] = $result_MIDDLENAME;
					$_SESSION['CURRENT_STATUS'] = $result_STATUS;
					$_SESSION['CURRENT_DATECREATED'] = $result_DATECREATED;
					$_SESSION['CURRENT_FULLNAME'] = $result_FIRSTNAME . ' ' . $result_MIDDLENAME . ' ' . $result_LASTNAME;

	
			}
			else
			{
				echo 'error: Error! Wrong Password!';
			
			}
		}
		else
		{
			echo "warning: Account Deactivated";
		}
	}
	else
	{
		echo 'msg: No Account found!';
	}
?>