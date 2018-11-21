<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$getyear = isset($_GET['year']) ? $_GET['year'] : '';
	$getsemester = isset($_GET['semester']) ? $_GET['semester'] : '';
	$convertedsem = "";

	if($getsemester == 1)
	{
		$convertedsem = "1ST SEMESTER";
	}
	elseif($getsemester == 2)
	{
		$convertedsem = "2ND SEMESTER";	
	}
	elseif($getsemester == 3)
	{
		$convertedsem = "SUMMER";	
	}
	else
	{
		$convertedsem = "INVALID SEMESTER";	
	}

	$limit = 10;

	$getCount = $db->connection->prepare('SELECT Count(Distinct STUBCODE) FROM GradeFinal WHERE ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ?');
	$getCount->bindparam(1,$getyear);
	$getCount->bindparam(2,$getsemester);
	$getCount->execute();
	$total_count = $getCount->fetchColumn(); 

	$total_page = ceil($total_count/$limit);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = ($page * $limit) - $limit;

	if($page < 2) {
		$disable_previous = 'disabled text-muted';
		$disable_previous2 = 'pointer-events: none;';
		$bottom_page = 'display:none';
	}
	else {
		$disable_previous = '';
		$disable_previous2 = '';
		$bottom_page = '';
	}
	if($total_page < 2) {
		$top_page = 'display:none';
	}
	else {
		$top_page = '';
	}
	if($total_page == $page || $total_page < 1) {
		$disable_next = 'disabled text-muted';
		$disable_next2 = 'pointer-events: none;';
		$top_page = 'display:none';
	}
	else {
		$disable_next = '';
		$disable_next2 = '';
		$top_page = '';
	}
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

		<div class="modal fade" id="deleteSemesterGradeForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Drop All Grades For this Semester</h5>
			</div>
            <form id="deleteSemesterGradeForm" method="post" action="library/form/frmDeleteSemesterGrade.php">
            	<input id = "deleteSemesterGradeForm_YEAR" type = "text" name = "YEAR" value = "<?php echo  $getyear ?>" hidden />
            	<input id = "deleteSemesterGradeForm_SEMESTER" type = "text" name = "SEMESTER" value = "<?php echo  $getsemester ?>" hidden />
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Class Schedule Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><b>School Year: </b></td>
                            <td id="deleteSemesterGradeForm_YEAR"><?php echo $getyear; ?></td>
                        </tr>
                        <tr>
                            <td><b>Semester: </b></td>
                            <td id="deleteSemesterGradeForm_SEMESTER"><?php echo $getsemester; ?></td>
                        </tr>

                        <tr>
                            <td><b>Class Schedule Count: </b></td>
                            <td id="deleteSemesterGradeForm_COUNT"><?php echo $total_count; ?></td>
                        </tr>

						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteSemesterGradeForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Manage Grade</h1>
					<p class = "p">For School Year: <?php echo $convertedsem . ' ' . $getyear ?></p>
				</div>
				
				<div class = "col-lg-6">
						<div class = "float-right button-manage-group">
							<a href = "view-period.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						  	<a class="dropdown-item" href="#deleteSemesterGradeForm_MODAL" data-toggle="modal">Drop All Grades for this Semester</a>
						    <a class="dropdown-item" href="mailto:totopaulmanares@yahoo.com?Subject=CPU%20Touch%20Information%20Kiosk%20Concerns" target="_top">Report</a>
						  </div>
						</span>
					</div>
				</div>
			</div>
	
		<div class = "row top-buffer">
		<?php 
				$list_getCSC = $db->connection->prepare('SELECT STUBCODE, SUBJCODE FROM GradeFinal WHERE ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ? GROUP BY STUBCODE, SUBJCODE');
				$list_getCSC->bindparam(1,$getyear);
				$list_getCSC->bindparam(2,$getsemester);
				$list_getCSC->execute();
				$result = $list_getCSC->fetchAll();
				foreach($result As $row) {
					?>
					<?php 	
				$list_STUDENT = $db->connection->prepare('SELECT Count(*) FROM GradeFinal WHERE STUBCODE = ? AND ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ?');
				$list_STUDENT->bindparam(1, $row['STUBCODE']);
				$list_STUDENT->bindparam(2, $getyear);
				$list_STUDENT->bindparam(3, $getsemester);
				$list_STUDENT->execute();
				$count_STUDENT = $list_STUDENT->fetchColumn();
					?>
					<div class="card m-3" style="width: 25rem;">
					  <div class="card-body">
					    <h5 class="card-title">[ <?php echo  $row["STUBCODE"]?> ] <b><?php echo $row['SUBJCODE'] ?></b></h5>
					    <p class="card-text mb-1"></p>
					    <p class="card-text">Student Count: <span class="badge badge-info"><?php echo $count_STUDENT ?> </span></p>
					    <a href="#" onClick = "viewSchedule('<?php echo $getyear ?>', <?php echo $getsemester ?>,<?php echo $row['STUBCODE'] ?> )" class="btn btn-primary">Go</a>
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

	function viewSchedule(year, semester, stubcode){
		window.location.href='manage-grades.php?year='+year+'&semester='+semester+'&stubcode='+stubcode+'';

	}

	var DGForm = {
	    form: document.getElementById('deleteSemesterGradeForm'),
	    modal: document.getElementById('deleteSemesterGradeForm_MODAL'),
	    submit: document.getElementById('deleteSemesterGradeForm_SUBMIT')
	   };

	$(DGForm.form).on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DGForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DGForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DGForm.submit).button('reset');
					DGForm.form.reset();
					$(DGForm.modal).modal('hide');
					alert(GetSuccessMsg(server_message));
					location.href = "view-period.php";
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
    });


</script>	