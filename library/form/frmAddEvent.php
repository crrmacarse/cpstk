<?php
session_start();
include('../form/connection.php');
include('../functions/checkSession.php');
$db = new db();

$form_UPLOADER = $_SESSION['CURRENT_ID'];
$form_TITLE = $_POST['TITLE'];
$form_EVENTSTART = $_POST['EVENTSTART'];
$form_EVENTEND = $_POST['EVENTEND'];
$form_DESCRIPTION = $_POST['DESCRIPTION'];
$form_IMAGE = '';


	function getextension($str) {
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		 return $ext;
	}

	$date_start = new DateTime($form_EVENTSTART);
    $date_end = new DateTime($form_EVENTEND);


	if($date_start > $date_end)
	{
		die("warning: Event End must be higher than Event Start");
	}


	// $check = $db->connection->prepare("SELECT COUNT(*) FROM EventsList WHERE ELStatus = 1");
	// $check->execute();

	// if ($check->fetchColumn() > 4){
	// 	echo "warning: Only 4 active Events is allowed(consider disabling current Active Events)";
	// 	die;
	// 	}


		if(isset($_FILES['IMAGE']['name'])){
		
		if(!$_FILES['IMAGE']['error'])
		{
				$new_file_name = stripslashes($_FILES['IMAGE']['name']);
				$extension = strtolower(getextension($new_file_name));

				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") ){
					die('error: Image file types only');
				}

				if($_FILES['IMAGE']['size'] > (200000)){
					$valid_file = false;
					echo('error: Your file\'s size is to large.');
				}else{
					$valid_file = true;
				}
				if($valid_file){
					$form_IMAGE =md5(date("ymdHis")).'.'.$extension;
					$file_path = "C:/CPUSTIK/Images/";
					$browserpath='../../img/events/';

					
					if(file_exists($file_path) == False){
						mkdir($file_path, 0777, true);					
					}
	
					$newname=$file_path.$form_IMAGE;
					$browserupload=$browserpath.$form_IMAGE;

					//move_uploaded_file($_FILES['IMAGE']['tmp_name'],$newname);
					move_uploaded_file($_FILES['IMAGE']['tmp_name'],$browserupload);


					$sql = "INSERT INTO EventsList(
								ELTitle,
								ELStart,
								ELEnd,
								ELDescription,
								ELImage,
								ELAddedBy,
								ELDateCreated,
								ELStatus
								)

							VALUES(
							'$form_TITLE',
							'$form_EVENTSTART',
							'$form_EVENTEND',
							'$form_DESCRIPTION',
							'$form_IMAGE',
							$form_UPLOADER,
							GETDATE(),
							1
							)";

					$getResult = $db->connection->prepare($sql);
					if($getResult->execute()) {
						echo 'success: Succesfully Added an Event';	
					}
					else {
						echo 'error: Failure in Event submission.';
					}	
				}

			}
			else
			{
				echo 'warning: There is an error in adding';

			}
		}
		else
		{
			echo 'msg: Image not found';
		}

?>