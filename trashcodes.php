angular.module('postLogin', [])
    .controller('PostController', ['$scope', '$http', function($scope, $http) {		
            this.postForm = function() {
                var encodedString = 'username=' +
                    encodeURIComponent(this.inputData.username) +
                    '&password=' +
                    encodeURIComponent(this.inputData.password);
 
                $http({
                    method: 'POST',
                    url: 'library/form/userauth.php',
                    data: encodedString
                })
                
                .success(function(data) {
                        //console.log(data);
                        if ( data.trim() === 'correct') {
                            window.location.href = 'dms.php';
                        } else {
                            $scope.errorMsg = "Username and password do not match.";
                        }
                })			
            }
    }]);




<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	if(!isset($_SESSION['CURRENT_ID']))
	{
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>Un-authorized Access</h4> </div> </div> </body> </html>';
        exit;
	}

	$search = isset($_GET['search']) ? $_GET['search'] : '';
	$limit = 10;


	$total_count = $db->connection->query('SELECT COUNT(*) FROM ClassScheduleControl')->fetchColumn(); 
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
<html>
	
	<head>
	
		<meta charset="utf-8">

		<title>CPU Smart Touch Information Kiosk</title>

		<link href = "library/css/bootstrap.min.css" rel = " stylesheet">
		<link href = "library/css/mystyles.css" rel = "stylesheet">
		
		
	</head>

<body>
	

	<!-- Modal -->
	<div class="modal fade" id="loadGrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Kindly Select your Preferences</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form class="form-horizontal" action="library/form/frmLoadGrade.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         
			<div class="form-group">
								    <select name = "USERTYPE" class="form-control" id="addEmployeeForm_USERTYPE" required>
								      <option selected = "true" disabled value = "">Select a Faculty</option>
								    <?php
								    	$sql = "SELECT * FROM Employee WHERE idUserType = 6 ORDER BY EMPLastname";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idEmployee"]; ?>" id="employee_<?php echo $row["idEmployee"]; ?>"><?php echo $row["EMPLastName"]; ?>, <?php echo $row["EMPFirstName"]; ?> <?php echo $row["EMPMiddleName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
			</div>
			<div class="form-group">
								    <select name = "USERTYPE" class="form-control" id="addEmployeeForm_USERTYPE" required>
								      <option selected = "true" disabled value = "">Select a Semester & School Year</option>
								    <?php
								    	$sql = "SELECT * FROM SchoolYear ORDER BY SYYear";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idSchoolYear"]; ?>" id="employee_<?php echo $row["idSchoolYear"]; ?>"><?php echo $row["SYSemester"]; ?> <?php echo $row["SYYear"]; ?></option>
											<?php
										}
								    ?>
								    </select>
			</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input type="submit" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Select">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="updateGradeForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Update Grade</h5>
				</div>
				<form id="updateGradeForm" method="post" action="library/form/frmUpdateGrade.php">
					<div class="modal-body">
						<input type="hidden" id="updateGradeForm_ID" name="ID" required />
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-heading pull-right"><b>Information</b></div>
							<br />
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Fullname</span>
									  </div>
									  <input id = "updateGradeForm_FULLNAME" name = "FULLNAME" type="text" class="form-control" placeholder="Fullname" aria-describedby="sizing-addon2" readonly>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject</span>
									  </div>
									  <input id = "updateGradeForm_SUBJECT" name = "SUBJECT" type="text" class="form-control" placeholder="Subject" aria-describedby="sizing-addon2" readonly>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Grade</span>
									  </div>
									  <input id = "updateGradeForm_GRADE" name = "GRADE" type="number" class="form-control" placeholder="GRADE" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Credit</span>
									  </div>
									  <input id = "updateGradeForm_CREDIT" name = "CREDIT" type="text" class="form-control" placeholder="Fullname" aria-describedby="sizing-addon2" readonly>
								</div>
								<br />								
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Remarks</span>
									  </div>
									  <textarea id = "updateGradeForm_REMARKS" maxlength = "180" name = "REMARKS" style = "padding-bottom: 150px;" type="text" class="form-control" placeholder="Remarks..." aria-describedby="sizing-addon2" required></textarea>
								</div>
								<br />
								<div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateGradeForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="updateGradeForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Grades Management</h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#">Add Data</a>
						    <a id = "btnLoadGrades" class="dropdown-item" href="#loadGrade" data-toggle="modal">Load Data</a>
						    <a class="dropdown-item" href="mailto:customercare@coffeebreak.ph?Subject=Coffeebreak%20Careers" target="_top">Report</a>
						  </div>
						</span>
					</div>
				</div>
				
		</div>
		

		<div class = "row">
			<div class = "col-lg-12">
						<hr class="my-4 float-right" width="65%">
			</div>
			<div class = "col-lg-8">
			</div>
			<div class = "col-lg-4">
				<div class = "input-group">
				<input type = "text" id = "searchBar" class = "form-control" placeholder="Search">
				&nbsp;
				<button id = "gradeSearch" class = "btn btn-success">Search</button>
				</div>
			</div>
		</div>

		<div class = "row top-buffer">
		  	<table class="table table-striped">
				  <thead>
					<tr>
					  <th scope="col">Unique ID</th>
					  <th scope="col">Stub Code</th>
					  <th scope="col">Subject</th>
					  <th scope="col">Room</th>
					  <th scope="col">Assigned Faculty</th>
	  				  <th scope="col">Credit</th>
					  <th scope="col">Class Type</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>					  
					</tr>
				  </thead>
				  <tbody id = "gradelist">
				  </tbody>
				</table>
			
		</div>

		<div class = "row top-buffer justify-content-md-center">
			 <div class="col-md-auto top-buffer">
		<nav aria-label="Page navigation example">
			  <ul class="pagination justify-content-center">
			    	<li class="page-item">
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-grades.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-grades.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-grades.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
					</li>			  </ul>
			</nav>
		</div>
		</div>
	</div>
	
		 
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script src = "library/js/app.js"></script>	
  <script src="library/js/messagealert.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</body>
	
	
</html> 

<script>
	$(document).ready(function(){
		$('#loadGrade').modal('show');
		$("#paginationActive<?php echo $page ?>").addClass("active");
		$("#gradeSearch").val('<?php echo $search; ?>');
		$("#gradeSearch").click(function(){
			this.value = '';
			 $( "#searchBar" ).click(function() {
			  jQuery('#gradeSearch').empty();
			  var searchValue = $("#gradeSearch").val().toLowerCase(); 
				window.location.href='manage-grades.php?search='+searchValue;
				 
				});

				$('#gradeSearch').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#searchBar').click();//Trigger search button click event
				}
			});
		});
	});

		  var PageComponent = {
        gradelist: document.getElementById('gradelist')
    };


    	var updateGradeForm = {
		form: document.getElementById('updateGradeForm'),
		modal: document.getElementById('updateGradeForm_MODAL'),
		id: document.getElementById('updateGradeForm_ID'),
		fullname: document.getElementById('updateGradeForm_FULLNAME'),
		subject: document.getElementById('updateGradeForm_SUBJECT'),
		grade: document.getElementById('updateGradeForm_GRADE'),
		credit: document.getElementById('updateGradeForm_CREDIT'),
		remarks: document.getElementById('updateGradeForm_REMARKS'),
		status: document.getElementById('updateGradeForm_STATUS'),
		submit : '#updateGradeForm_SUBMIT'

	};
	
	function updateMenuFill(id) {
		// image fill
		var fullname = document.getElementById('grade_FULLNAME_' + id).innerHTML;
		var subject = document.getElementById('grade_SUBJECT_' + id).innerHTML;
		var grade = document.getElementById('grade_GRADE_'+id).innerHTML;
		var credit = document.getElementById('grade_CREDIT_'+id).innerHTML;
		var remarks = document.getElementById('grade_REMARKS_'+id).innerHTML;
		var status = document.getElementById('grade_STATUS_'+id).innerHTML;
		
		
		for(var i = 0; i < updateGradeForm.status.options.length; i++)
			{
				if(updateGradeForm.status.options[i].text == status)
					{
						updateGradeForm.status.selectedIndex = i;
					}
			}
		updateGradeForm.fullname.value = fullname;
		updateGradeForm.subject.value = subject;
		updateGradeForm.grade.value = grade;
		updateGradeForm.credit.value = credit;
		updateGradeForm.remarks.value = remarks;
	}
	
	function updateMenu(id, name, group, recommendation, image, description, status)
	{
		document.getElementById('menu_NAME_'+id).innerHTML = name;
		document.getElementById('menu_GROUP_'+id).innerHTML = group;
		document.getElementById('menu_RECOMMENDATION_'+id).innerHTML = recommendation;
		document.getElementById('menu_IMAGE_'+id).innerHTML = image;
		document.getElementById('menu_DESCRIPTION_'+id).innerHTML = description;
		document.getElementById('menu_STATUS_'+id).innerHTML = status;
		// image add here
		updateMenuForm.form.reset();
	}

	function addGradeList(csid, firstname, middlename, lastname, subject, grade, credit, remarks, status)
	{
		PageComponent.gradelist.innerHTML = PageComponent.gradelist.innerHTML +
			'<thead>'+
			'<tr id = "grade_'+ csid +'">'+
			'	<td scope = "col" id = "grade_CSDID_' + csid +'">' + csid + '</td>'+
			'	<td scope = "col" id = "grade_FULLNAME_' + csid + '">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "grade_SUBJECT_' + csid + '">' + subject + '</td>'+
			'	<td scope = "col" id = "grade_GRADE_' + csid + '">' + grade + '</td>'+
			'	<td scope = "col" id = "grade_CREDIT_' + csid + '">' + credit + '</td>'+
			'	<td scope = "col" id = "grade_REMARKS_' + csid + '">' + remarks + '</td>'+
			'	<td scope = "col" id = "grade_STATUS_' + csid + '">' + status + '</td>'+
			' 	<td><button id="grade_BTNUPDATE_' + csid + '" value="' + csid + '" data-target = "#updateGradeForm_MODAL" data-toggle = "modal" onclick = "updateMenuFill(\'' + csid + '\')"class="btn btn-primary">Update</button></td>'+
			'</tr>'+	
			'</thead>';
	}



<?php 

	$list_sql = 'WITH OrderedOrders AS
			(
				SELECT ClassScheduleControl.idClassScheduleControl, ClassScheduleData.idClassScheduleData, Subjects.SName, Subjects.SCredit, ClassScheduleControl.CSCGrade, ClassScheduleControl.CSCRemarks, Users.UFirstName, Users.UMiddleName, Users.ULastName, CSCStatus,
				ROW_NUMBER() OVER (ORDER BY ClassScheduleControl.idClassScheduleControl) AS "RowNumber"
				FROM ClassScheduleControl 
				INNER JOIN ClassScheduleData
				ON ClassScheduleControl.idClassScheduleData = ClassScheduleData.idClassScheduleData
				INNER JOIN Subjects
				ON Subjects.idSubject = ClassScheduleData.idSubject
				INNER JOIN Users
				ON Users.idUser = ClassScheduleControl.idUser
	
			) 
			SELECT * 
			FROM OrderedOrders 
			WHERE RowNumber BETWEEN '.$offset.' AND '. $limit;
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>
			

			var content = '<tr>'+
			'<tr>'+
			'<td id = "grades_TITLE_">No Grades Found</td>'+
			'<tr>';

			$("gradelist").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idClassScheduleControl']); 
			$result_FIRSTNAME = htmlspecialchars($list_row['UFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['UMiddleName']);
			$result_LASTNAME = htmlspecialchars($list_row['ULastName']);
			$result_SUBJECT = htmlspecialchars($list_row['SName']);
			$result_GRADE = htmlspecialchars($list_row['CSCGrade']);
			$result_CREDIT = htmlspecialchars($list_row['SCredit']);
			$result_REMARKS = htmlspecialchars($list_row['CSCRemarks']);
			$result_STATUS = htmlspecialchars($list_row['CSCStatus']);

		if($result_STATUS == '1')
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addGradeList("<?php echo $result_ID; ?>","<?php echo $result_FIRSTNAME; ?>","<?php echo $result_MIDDLENAME; ?>", "<?php echo $result_LASTNAME; ?>","<?php echo $result_SUBJECT; ?>" ,"<?php echo $result_GRADE; ?>","<?php echo $result_CREDIT; ?>", "<?php echo $result_REMARKS; ?>","<?php echo $result_STATUS; ?>");
		
		<?php 
		}			
	}
	?>



	</script>


	<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	if(!isset($_SESSION['CURRENT_ID']))
	{
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>Un-authorized Access</h4> </div> </div> </body> </html>';
        exit;
	}

	$search = isset($_GET['search']) ? $_GET['search'] : '';
	$limit = 10;


	$total_count = $db->connection->query('SELECT COUNT(*) FROM ClassScheduleControl')->fetchColumn(); 
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

<body>

	<div class="modal fade" id="loadClassSchedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Load Class Schedule Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form class="form-horizontal" action="library/form/frmLoadClassSchedule.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input type="file" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input type="submit" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>
	
	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Class Schedule Management</h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addEmployeesForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Class Schedule</a>
						    <a class="dropdown-item" href="#loadClassSchedule" data-toggle="modal">Load Data</a>
						    <a class="dropdown-item" href="mailto:totopaulmanares@yahoo.com?Subject=CPU%20Touch%20Information%20Kiosk%20Concerns" target="_top">Report</a>
						  </div>
						</span>
					</div>
				</div>
			</div>

		<div class = "row">
			<div class = "col-lg-12">
						<hr class="my-4 float-right" width="65%">
			</div>
			<div class = "col-lg-8">
			</div>
			<div class = "col-lg-4">
				<div class="input-group">
				  <select class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
				    <option selected = "true" disabled value = "">Select a Period & School Year</option>
				    		<?php
						    	$sql = "SELECT Period.idPeriod, SchoolYear.SYYear, Period.PName FROM SchoolYear INNER JOIN Period ON SChoolYear.idSchoolYear = Period.idPeriod";
								$getResult = $db->connection->prepare($sql);
								$getResult->execute();
								$count = $getResult->rowCount();
								$result = $getResult->fetchAll();
								foreach($result As $row) {
									?>
									   <option value = "<?php echo $row["idPeriod"]; ?>"><?php echo $row["PName"] . " " . $row["SYYear"] ?></option>
									<?php
								}
						    ?>
				  </select>
				  <div class="input-group-append">
				    <button class="btn btn-primary" type="button">Display</button>
				  </div>
				</div>
				</div>
			</div>
		</div>		
		
		<div class = "row top-buffer">
				<table class="table table-striped">
				  <thead>
					<tr>
					  <th scope="col">Unique ID</th>
					  <th scope="col">Stub Code</th>
					  <th scope="col">Subject</th>
					  <th scope="col">Room</th>
					  <th scope="col">Assigned Faculty</th>
	  				  <th scope="col">Credit</th>
					  <th scope="col">Class Type</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>						  				
					</tr>
				  </thead>
				  <tbody id = "classSchedList">
				  </tbody>
				</table>
			
		</div>


		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
			    	<li class="page-item">
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-employees.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-employees.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-employees.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
					</li>			  </ul>
			</nav>
		</div>
		</div>

	<?php include('library/html/footer.html'); ?>

	</div>
			 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script src = "library/js/app.js"></script>	
  <script src="library/js/messagealert.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</body>
	
	
</html>

<script>

	var PageComponent = {
        classSchedList: document.getElementById('classSchedList')
    };

	function addClassSchedList(stubcode, subject, time, day, room, building, efirstname, emiddlename, elastname, status)
	{
		PageComponent.classSchedList.innerHTML = PageComponent.classSchedList.innerHTML +
			'<thead>'+
			'<tr id = "exam_'+ stubcode +'">'+
			'	<td scope = "col" id = "exam_STUBCODE_' + stubcode +'">' + stubcode + '</td>'+
			'	<td scope = "col" id = "exam_SUBJECT_' + stubcode + '">' + subject + '</td>'+
			'	<td scope = "col" id = "exam_TIME_' + stubcode + '">' + time + '</td>'+
			'	<td scope = "col" id = "exam_DAY' + stubcode + '">' + day + '</td>'+
			'	<td scope = "col" id = "exam_ROOM_' + stubcode + '">' + room + ' '+ building +'</td>'+
			'	<td scope = "col" id = "exam_PROFESSOR_' + stubcode + '">' + efirstname + ' ' + emiddlename +' ' + elastname + '</td>'+
			'	<td scope = "col" id = "exam_STATUS_' + stubcode + '">' + status + '</td>'+
			' 	<td><button id="exam_BTNUPDATE_' + stubcode + '" value="' + stubcode + '" data-target = "#updateExamForm_MODAL" data-toggle = "modal" onclick = "updateExamFill(\'' + stubcode + '\')"class="btn btn-primary">Update</button></td>'+
			'</tr>'+	
			'</thead>';
	}



	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT  

					ClassScheduleData.idClassScheduleData,  
					ClassScheduleControl.idUser, 
					ClassScheduleControl.idSchoolYear, 
					ClassScheduleControl.CSCStubcode,  
					Subjects.SName, 
					Subjects.SCredit,
					Building.BCode,
					ClassScheduleControl.CSCStatus,
					ROW_NUMBER() OVER (ORDER BY ClassScheduleControl.CSCStubCode) AS "RowNumber",
					ClassScheduleData.CSDTime, ClassScheduleData.CSDDay, Rooms.RName,  Employee.EMPFirstName, Employee.EMPLastName, Employee.EMPMiddleName FROM ClassScheduleControl 
				    INNER JOIN ClassScheduleData ON ClassScheduleControl.idClassScheduleData = ClassScheduleData.idClassScheduleData INNER JOIN Rooms ON ClassScheduleData.idRoom = Rooms.idRoom INNER JOIN
				    Subjects ON Subjects.idSubject = ClassScheduleData.idSubject INNER JOIN Employee ON Employee.idEmployee = Employee.idEmployee
				    INNER JOIN Building ON Rooms.idBuilding = Building.idBuilding 
				)

				SELECT * FROM OrderedList 
				WHERE RowNumber BETWEEN '. $offset.' AND '. $limit;
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "examSched_TITLE_">No Class Schedule Found</td>'+
			'<tr>';

			$("#classSchedList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_CSDID = htmlspecialchars($list_row['idClassScheduleData']); 
			$result_IDSTUDENT = htmlspecialchars($list_row['idUser']); 
			$result_STUBCODE = htmlspecialchars($list_row['CSCStubcode']);
			$result_SUBJECTNAME = htmlspecialchars($list_row['SName']);
			$result_CREDIT = htmlspecialchars($list_row['SCredit']);
			$result_TIME = htmlspecialchars($list_row['CSDTime']);
			$result_DAY = htmlspecialchars($list_row['CSDDay']);
			$result_ROOM = htmlspecialchars($list_row['RName']);
			$result_BUILDING = htmlspecialchars($list_row['BCode']);
			$result_EMPFIRSTNAME = htmlspecialchars($list_row['EMPFirstName']);
			$result_EMPMIDDLENAME = htmlspecialchars($list_row['EMPMiddleName']);
			$result_EMPLASTNAME = htmlspecialchars($list_row['EMPLastName']);
			$result_STATUS = htmlspecialchars($list_row['CSCStatus']);

		if($result_STATUS == '1')
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addClassSchedList("<?php echo $result_STUBCODE; ?>","<?php echo $result_SUBJECTNAME; ?>","<?php echo $result_TIME; ?>","<?php echo $result_DAY; ?>","<?php echo $result_ROOM; ?>", "<?php echo $result_BUILDING; ?>","<?php echo $result_EMPFIRSTNAME; ?>","<?php echo $result_EMPMIDDLENAME; ?>","<?php echo $result_EMPLASTNAME; ?>","<?php echo $result_STATUS; ?>");
		
		<?php 
		}			
	}


	?>

</script>