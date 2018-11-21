<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');
?>

<!doctype html>
<html lang="en">
	
	<head>
	
	   	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>CPU Smart Touch Information Kiosk</title>

		<link href = "library/css/bootstrap.min.css" rel = " stylesheet">
		<link href = "library/css/mystyles.css" rel = "stylesheet">
		
	</head>

<body>

<body>

	<div class="modal fade" id="importGradeForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="importGradeForm_TITLE">Import Grade Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "importGradeForm" class="form-horizontal" action="library/form/frmImportGrade.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input type="file" class="custom-file-input" name = "FILE" id = "importGradeForm_FILE" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input id = "importGradeForm_SUBMIT" type="submit" class="btn btn-success" name = "IMPORT" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Manage Grade</h1>
					<p class = "text-muted">Select a Period</p>
				</div>
				
				<div class = "col-lg-6">
						<div class = "float-right button-manage-group">
							<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						  	<a class="dropdown-item" href="#importGradeForm_MODAL" data-toggle="modal">Import Grade Data</a>
						    <a class="dropdown-item" href="mailto:totopaulmanares@yahoo.com?Subject=CPU%20Touch%20Information%20Kiosk%20Concerns" target="_top">Report</a>
						  </div>
						</span>
					</div>
				</div>
			</div>
	
		<div class = "row top-buffer">
			
		<?php 
				$list_getPeriod = $db->connection->prepare('SELECT ACADEMICSCHOOLYEAR, ACADEMICSEMESTERID FROM GradeFinal GROUP BY ACADEMICSCHOOLYEAR, ACADEMICSEMESTERID ORDER BY ACADEMICSCHOOLYEAR');
				$list_getPeriod->execute();
				$count = $list_getPeriod->rowCount();
				$result = $list_getPeriod->fetchAll();
				foreach($result As $row) {
					?>
					<?php 
		
				$list_getClassSchedule = $db->connection->prepare('SELECT Count(Distinct STUBCODE) FROM GradeFinal WHERE ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ?');
				$list_getClassSchedule->bindParam(1,$row["ACADEMICSCHOOLYEAR"]);
				$list_getClassSchedule->bindParam(2,$row["ACADEMICSEMESTERID"]);
				$list_getClassSchedule->execute();
				$count_getClassSchedule = $list_getClassSchedule->fetchColumn();
				$SEMESTERNAME = "";
				switch ($row["ACADEMICSEMESTERID"]) {
					case '1':
						$SEMESTERNAME = "1ST SEMESTER";
						break;
					case '2':
						$SEMESTERNAME = "2ND SEMESTER";
						break;
					case '3':
						$SEMESTERNAME = "SUMMER";
						break;
					default:
						$SEMESTERNAME = "SEMESTER OVERFLOW";
						break;
				}
					?>
					<div class="card m-3" style="width: 18rem;">
					  <div class="card-body">
					    <h5 class="card-title"><?php echo $SEMESTERNAME . " " . $row["ACADEMICSCHOOLYEAR"] ?></h5>
					    <p class="card-text">Subjects Count: <span class="badge badge-info"><?php echo $count_getClassSchedule ?> </span></p>
					    <a href="#" onClick = "viewSchedule('<?php echo $row["ACADEMICSCHOOLYEAR"] ?>',<?php echo $row['ACADEMICSEMESTERID'] ?>)" class="btn btn-primary">Go</a>
					  </div>
					</div>
					<?php
				}

		?>

		</div>

		<?php include('library/html/footer.html'); ?>

	</div>
	
  <script src = "library/js/jquery-3.3.1.min.js"></script>
  <script src="library/js/popper.min.js"></script>
  <script src="library/js/bootstrap.min.js"></script>
  <script src = "library/js/jquery.form.js"></script>
  <script src = "library/js/app.js"></script>	
  <script src="library/js/messagealert.js"></script>
  <script src="library/js/all.js"></script>
</body>
	
	
</html>

<script>

	function viewSchedule(year,id){
		window.location.href='view-schedule.php?year=' + year + '&semester='+id;
	}

	var IGForm =
    {
    	form: document.getElementById('importGradeForm'),
    	file: document.getElementById('importGradeForm_FILE'),
    	modal: '#importGradeForm_MODAL',
    	submit: '#importGradeForm_SUBMIT'
    }

    IGForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(IGForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(IGForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(IGForm.modal).modal('hide');
						alert(GetSuccessMsg(server_message));
						window.location.reload(false); 						
						IGForm.form.reset();
					}
				else if(!isWhitespace(GetWarningMsg(server_message)))
					{
						alert(GetWarningMsg(server_message));
					}
				else if(!isWhitespace(GetErrorMsg(server_message)))
					{
						alert(GetErrorMsg(server_message));
					}
				else if(!isWhitespace(GetServerMsg(server_message)))
					{
						alert(GetServerMsg(server_message));
					}
				else
					{
						alert('Oh Snap! There is a problem with the server or your connection.');
					}
				}
			});
		};

</script>	