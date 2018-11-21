<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	
	$total_count = $db->connection->query('SELECT COUNT(*) FROM Student')->fetchColumn(); 
	$total_page = ceil($total_count/10);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = $page * 10;
	$limit =  ($page * 10) - 9;

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
	
	<div class="modal fade" id="addStudentForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Student Registration</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addStudentForm" class="form-horizontal" action="library/form/frmAddStudent.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Username</span>
									  </div>
									  <input id = "addStudentForm_USERNAME" name = "USERNAME" type="number" class="form-control" placeholder="Username" aria-describedby="sizing-addon2" required min="10000000" max = "99999999">
								</div>
								<small class = "float-right pb-3">*Username only accepts 8 NUMERICAL value</small>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Password</span>
									  </div>
									  <input id = "addStudentForm_PASSWORD" name = "PASSWORD" type="password" class="form-control" placeholder="Password" aria-describedby="sizing-addon2" max = "11"required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "COURSE" class="form-control" id="addStudentForm_COURSE" required>
								      <option selected = "true" disabled value = "">Select a Course</option>
								    <?php
								    	$sql = "SELECT * FROM Course WHERE Course.CStatus = 1 ORDER BY CName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["CCode"]; ?>" id="employee_<?php echo $row["idCourse"]; ?>"><?php echo $row["CName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<div class="form-group">
								    <select name = "YEARLEVEL" class="form-control" id="addStudentForm_YEARLEVEL" required>
								      <option selected = "true" disabled value = "">Select a Year</option>
								      <option>1ST YEAR</option>
								      <option>2ND YEAR</option>
								      <option>3RD YEAR</option>
								      <option>4TH YEAR</option>
								      <option>5TH YEAR</option>
								      <option>UNDER PROBATION</option>
								    </select>
								 </div>
								<br />
								<h6 class = "float-right">Personal Information</h6>
								<br />
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Firstname</span>
									  </div>
									  <input id = "addStudentForm_FIRSTNAME" name = "FIRSTNAME" type="text" class="form-control" placeholder="Firstname" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Middlename</span>
									  </div>
									  <input id = "addStudentForm_MIDDLENAME" name = "MIDDLENAME" type="text" class="form-control" placeholder="Middlename" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Lastname</span>
									  </div>
									  <input id = "addStudentForm_LASTNAME" name = "LASTNAME" type="text" class="form-control" placeholder="Lastname" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Guardian</span>
									  </div>
									  <input id = "addStudentForm_GUARDIAN" name = "GUARDIAN" type="text" class="form-control" placeholder="Guardian Fullname" aria-describedby="sizing-addon2" 
									  required>
								</div>								
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addEmployeeForm_SUBMIT" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Register">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="importStudentForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Import Student Data</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		         <form id = "importStudentForm" class="form-horizontal" action="library/form/frmImportStudents.php" method="post" name="upload_excel" enctype="multipart/form-data">
		         <div class="input-group mb-3">
					  <div class="input-group-prepend">
					    <span class="input-group-text">Upload</span>
					  </div>
					  <div class="custom-file">
					    <input type="file" id = "importStudentForm_FILE" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					    <label class="custom-file-label" for="fileupload">Choose file</label>
					  </div>
					</div>
	                      
		      </div>
		      <div class="modal-footer">
		        <input type="submit" class="btn btn-success" id = "importStudentForm_SUBMIT" name = "Import" data-loading-text = "Importing.." value = "Import">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		  </form>
		    </div>
		  </div>
		</div>

	<div class="modal fade" id="updateStudentForm_MODAL" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5>Update Student</h5>
					</div>
					<form id="updateStudentForm" method="post" action="library/form/frmUpdateStudent.php">
						<div class="modal-body">
							<input type="hidden" id="updateStudentForm_ID" name="ID" required />
							<!-- Infomation -->
							<div class="panel panel-default">
								<div class="panel-heading pull-right"><b>Information</b></div>
								<br />
								<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Username</span>
									  </div>
									  <input id = "updateStudentForm_USERNAME" name = "USERNAME" type="number" class="form-control" aria-describedby="sizing-addon2" required min="10000000" max = "99999999">
								</div>
								<small class = "float-right pb-3">*Username only accepts 8 NUMERICAL value</small>
								<br />
								<div class="form-group">
								    <select name = "COURSE" class="form-control" id="updateStudentForm_COURSE" required>
								      <option selected = "true" disabled value = "">Select a Course</option>
								    <?php
								    	$sql = "SELECT * FROM Course WHERE Course.CStatus = 1 ORDER BY CName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["CCode"]; ?>" id="employee_<?php echo $row["idCourse"]; ?>"><?php echo $row["CName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<div class="form-group">
								    <select name = "YEARLEVEL" class="form-control" id="updateStudentForm_YEARLEVEL" required>
								      <option selected = "true" disabled value = "">Select a Year</option>
								      <option>1ST YEAR</option>
								      <option>2ND YEAR</option>
								      <option>3RD YEAR</option>
								      <option>4TH YEAR</option>
								      <option>5TH YEAR</option>
								      <option>UNDER PROBATION</option>
								    </select>
								 </div>
								<br />
								<h6 class = "float-right">Personal Information</h6>
								<br />
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">First name</span>
									  </div>
									  <input id = "updateStudentForm_FIRSTNAME" name = "FIRSTNAME" type="text" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Middle name</span>
									  </div>
									  <input id = "updateStudentForm_MIDDLENAME" name = "MIDDLENAME" type="text" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Last name</span>
									  </div>
									  <input id = "updateStudentForm_LASTNAME" name = "LASTNAME" type="text" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Guardian</span>
									  </div>
									  <input id = "updateStudentForm_GUARDIAN" name = "GUARDIAN" type="text" class="form-control" aria-describedby="sizing-addon2" 
									  required>
								</div>
								<br />
								 <div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateStudentForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
								</div>
							</div>
						</div>
						<!-- Submission -->
						<div class="modal-footer">
							<button type="submit" id="updateStudentForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
	</div>

	<div class="modal fade" id="updateStudentPasswordForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Student Update Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="updateStudentPasswordForm_INFORMATION"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "updateStudentPasswordForm" class="form-horizontal" action="library/form/frmUpdateStudentPassword.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
					<input type="hidden" id="updateStudentPasswordForm_ID" name="ID" required />
					<input type="hidden" id="updateStudentPasswordForm_USERNAME" name="USERNAME" required />
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Password</span>
									  </div>
									  <input id = "updateStudentPasswordForm_PASSWORD" name = "PASSWORD" type="password" class="form-control" placeholder="Password" aria-describedby="sizing-addon2" required>
								</div>

					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "updateStudentPasswordForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Update.." value = "Update">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteStudentForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Student</h5>
			</div>
            <form id="deleteStudentForm" method="post" action="library/form/frmDeleteStudent.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteStudentForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Student Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Username: </td>
                            <td id="deleteStudentForm_USERNAME"></td>
                        </tr>
                        <tr>
                            <td>Student Fullname: </td>
                            <td id="deleteStudentForm_FULNAME"></td>
                        </tr>
                        <tr>
                            <td>Course: </td>
                            <td id="deleteStudentForm_COURSE"></td>
                        </tr>
                        <tr>
                            <td>Year Level: </td>
                            <td id="deleteStudentForm_YEARLEVEL"></td>
                        </tr>
                        <tr>
                            <td>Guardian: </td>
                            <td id="deleteStudentForm_GUARDIAN"></td>
                        </tr>                                        
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteStudentForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteStudentForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteStudentForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Student Management</h1>
					<p class = "text-muted">Manages Students, Register Students, Change Password if the student can't remember the password. Also import new Students via CSV File.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addStudentForm_MODAL" class = "dropdown-item"data-toggle = "modal">Register a Student</a>
						    <a class="dropdown-item" href="#importStudentForm_MODAL" data-toggle="modal">Import Student Data</a>
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
				<div class = "input-group">
				<input type = "text" id = "txtSearch" class = "form-control" placeholder="Search">
				&nbsp;
				<button id = "btnSearch" class = "btn btn-success">Search</button>
				</div>
			</div>
		</div>

		<div class = "row top-buffer">
			<table class="table table-striped">
			  <thead>
				<tr>
				  <th scope="col">Username</th>
				  <th scope="col">Fullname</th>
				  <th scope="col">Course</th>
				  <th scope="col">Year Level</th>
				  <th scope="col">Guardian Fullname</th>					  					  
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "studentList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-students.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-students.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-students.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
						</li>			  </ul>
				</nav>
			</div>
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

	$(document).ready(function(){
		$("#paginationActive<?php echo $page ?>").addClass("active");
		$("#txtSearch").val("<?php echo $trimmedsearch ?>");
			 $( "#btnSearch" ).click(function() {
			  var searchValue = $("#txtSearch").val().toLowerCase(); 
				window.location.href='manage-students.php?search='+searchValue;
				});
				$('#txtSearch').keypress(function(e){
				if(e.which == 13){ //Enter key pressed
					$('#btnSearch').click();//Trigger search button click event
				}
			});

			$("#txtSearch").click(function(){
				$("#txtSearch").val("");
			})
	});

	var PageComponent = {
        studentList: document.getElementById('studentList')
    };

    var ASForm = {
    	form: document.getElementById('addStudentForm'),
    	username: document.getElementById('addStudentForm_USERNAME'),
    	password: document.getElementById('addStudentForm_PASSWORD'),
    	lastname: document.getElementById('addStudentForm_LASTNAME'),
    	firstname: document.getElementById('addStudentForm_FIRSTNAME'),
    	middlename: document.getElementById('addStudentForm_MIDDLENAME'),
    	course: document.getElementById('addStudentForm_COURSE'),
    	yearlevel: document.getElementById('addStudentForm_YEARLEVEL'),
    	guardian: document.getElementById('addStudentForm_GUARDIAN'),
    	submit: '#addStudentForm_SUBMIT',
    	modal: '#addStudentForm_MODAL'
    }

	ASForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ASForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ASForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ASForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ASForm.form.reset();
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

	var ISForm =
    {
    	form: document.getElementById('importStudentForm'),
    	file: document.getElementById('importStudentForm_FILE'),
    	modal: '#importStudentForm_MODAL',
    	submit: '#importStudentForm_SUBMIT'
    }

    ISForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ISForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ISForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ISForm.modal).modal('hide');
						alert('Imported Succesfully');
						window.location.reload(false); 						
						ISForm.form.reset();
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

	var USForm = {
		form: document.getElementById('updateStudentForm'),
		id: document.getElementById('updateStudentForm_ID'),
		username: document.getElementById('updateStudentForm_USERNAME'),
		firstname: document.getElementById('updateStudentForm_FIRSTNAME'),
		middlename: document.getElementById('updateStudentForm_MIDDLENAME'),
		lastname: document.getElementById('updateStudentForm_LASTNAME'),
		course: document.getElementById('updateStudentForm_COURSE'),
		yearlevel: document.getElementById('updateStudentForm_YEARLEVEL'),
		guardian: document.getElementById('updateStudentForm_GUARDIAN'),
		status: document.getElementById('updateStudentForm_STATUS'),
		modal: document.getElementById('updateStudentForm_MODAL'),
		submit: document.getElementById('updateStudentForm_SUBMIT')
	}

	function updateStudent(id, username, firstname, middlename, lastname, course, yearlevel, guardian, status)
	{
		document.getElementById('student_USERNAME_'+id).innerHTML = username;
		document.getElementById('student_FIRSTNAME_'+id).innerHTML = firstname;
		document.getElementById('student_MIDDLENAME_'+id).innerHTML = middlename;
		document.getElementById('student_LASTNAME_'+id).innerHTML = lastname;
		document.getElementById('student_COURSE_'+id).innerHTML = course;
		document.getElementById('student_YEARLEVEL_'+id).innerHTML = yearlevel;
		document.getElementById('student_GUARDIAN_'+id).innerHTML = guardian;
		document.getElementById('student_STATUS_'+id).innerHTML = status;
	}

	function updateStudentFill(id){
		var username = document.getElementById('student_USERNAME_'+id).innerHTML
		var firstname = document.getElementById('student_FIRSTNAME_'+id).innerHTML
		var middlename = document.getElementById('student_MIDDLENAME_'+id).innerHTML
		var lastname = document.getElementById('student_LASTNAME_'+id).innerHTML
		var guardian = document.getElementById('student_GUARDIAN_'+id).innerHTML	
		var status = document.getElementById('student_STATUS_'+id).innerHTML;

		USForm.id.value = id;
		USForm.username.value = username;
		USForm.firstname.value = firstname;
		USForm.middlename.value = middlename
		USForm.lastname.value = lastname;
		USForm.guardian.value = guardian;

		var yearlevel = document.getElementById('student_YEARLEVEL_'+id).innerHTML
		for(var i = 0; i < USForm.yearlevel.options.length; i++) {
			if(USForm.yearlevel.options[i].text == yearlevel) {
				USForm.yearlevel.selectedIndex = i;
			}
		}

		var course = document.getElementById('student_COURSE_'+id).innerHTML
		for(var i = 0; i < USForm.course.options.length; i++) {
			if(USForm.course.options[i].text == course) {
				USForm.course.selectedIndex = i;
			}
		}

		if(status == 'Active')
		{
			USForm.status.selectedIndex = 0;
			USForm.status.value = 1;
		}
		else
		{
			USForm.status.selectedIndex = 1;
			USForm.status.value = 0;
		}

	}

	USForm.form.onsubmit = function(e) {
	e.preventDefault();
	
	$(this).ajaxSubmit({
		beforeSend:function()
		{
			$(USForm.submit).button('loading');
		},
		uploadProgress:function(event,position,total,percentCompelete)
		{

		},
		success:function(data)
		{
			$(USForm.submit).button('reset');
			var server_message = data.trim();
			if(!isWhitespace(GetSuccessMsg(server_message)))
			{			
			 	updateStudent(GetSuccessMsg(server_message), USForm.username.value, USForm.firstname.value, USForm.middlename.value, USForm.lastname.value, USForm.course.options[USForm.course.selectedIndex].text, USForm.yearlevel.options[USForm.yearlevel.selectedIndex].text, USForm.guardian.value, USForm.status.options[USForm.status.selectedIndex].text);
				$('#updateStudentForm_MODAL').modal('toggle');
				alert("Updated Succesfully");
				USForm.form.reset();
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

	var USPForm = {
		form: document.getElementById('updateStudentPasswordForm'),
		id: document.getElementById('updateStudentPasswordForm_ID'),
		username: document.getElementById('updateStudentPasswordForm_USERNAME'),
		password: document.getElementById('updateStudentPasswordForm_PASSWORD'),
		information: document.getElementById('updateStudentPasswordForm_INFORMATION'),
		modal: document.getElementById('updateStudentForm_MODAL'),
		submit: document.getElementById('updateStudentPasswordForm_SUBMIT')
	}


	function updateStudentPasswordFill(id){
		var username = document.getElementById('student_USERNAME_'+id).innerHTML;

		USPForm.id.value = id;
		USPForm.username.value = username;

		USPForm.information.innerHTML = "New Password for " + username;

	}

	USPForm.form.onsubmit = function(e) {
	e.preventDefault();
	
	$(this).ajaxSubmit({
		beforeSend:function()
		{
			$(USPForm.submit).button('loading');
		},
		uploadProgress:function(event,position,total,percentCompelete)
		{

		},
		success:function(data)
		{
			$(USPForm.submit).button('reset');
			var server_message = data.trim();
			if(!isWhitespace(GetSuccessMsg(server_message)))
			{		
				$('#updateStudentPasswordForm_MODAL').modal('toggle');
				alert("Updated Password Succesfully");
				USPForm.form.reset();
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

	var DSForm = {
	    form: document.getElementById('deleteStudentForm'),
	    modal: document.getElementById('deleteStudentForm_MODAL'),
	    id: document.getElementById('deleteStudentForm_ID'),
	    username: document.getElementById('deleteStudentForm_USERNAME'),
	    course: document.getElementById('deleteStudentForm_COURSE'),
	    fullname: document.getElementById('deleteStudentForm_FULNAME'),
	    yearlevel: document.getElementById('deleteStudentForm_YEARLEVEL'),
	    datecreated: document.getElementById('deleteStudentForm_DATECREATED'),
	    guardian: document.getElementById('deleteStudentForm_GUARDIAN'),
	    status: document.getElementById('deleteStudentForm_STATUS'),
	    submit: document.getElementById('deleteStudentForm_SUBMIT')
	};

	function deleteStudent(id) {
        $('#student_' + id).remove();
    }

    function openDeleteStudentModal(id) {
        DSForm.id.value = id;
        DSForm.username.innerHTML = document.getElementById('student_USERNAME_' + id).innerHTML;
        DSForm.fullname.innerHTML = document.getElementById('student_FULLNAME_' + id).innerHTML;
        DSForm.course.innerHTML = document.getElementById('student_COURSE_' + id).innerHTML;
        DSForm.yearlevel.innerHTML = document.getElementById('student_YEARLEVEL_' + id).innerHTML;
        DSForm.guardian.innerHTML = document.getElementById('student_GUARDIAN_' + id).innerHTML;
        DSForm.datecreated.innerHTML = document.getElementById('student_DATECREATED_'+id).innerHTML;
        DSForm.status.innerHTML = document.getElementById('student_STATUS_' + id).innerHTML;
        $(DSForm.modal).modal('show');
    }


	$(DSForm.form).on('submit', function (e) {
        var id = DSForm.id.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DSForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DSForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DSForm.submit).button('reset');
					deleteStudent(id);
					DSForm.form.reset();
					$(DSForm.modal).modal('hide');
					alert('Succesfully Deleted');
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
	

    function addStudentList(id, username, firstname, middlename, lastname, yearlevel, course, guardian, datecreated, status)
	{
		PageComponent.studentList.innerHTML = PageComponent.studentList.innerHTML +
			'<thead>'+
			'<tr id = "student_'+ id +'">'+
			'	<td scope = "col" id = "student_USERNAME_' + id +'">' + username + '</td>'+
			'	<td scope = "col" class = "hidden" id = "student_FULLNAME_' + id + '">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" hidden id = "student_LASTNAME_' + id + '">' + lastname + '</td>'+
			'	<td scope = "col" hidden id = "student_FIRSTNAME_' + id + '">' + firstname + '</td>'+
			'	<td scope = "col" hidden id = "student_MIDDLENAME_' + id + '">' + middlename + '</td>'+
			'	<td scope = "col" id = "student_FULLNAME_' + id + '" hidden>' + firstname + ' '+ middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "student_COURSE_' + id + '">' + course + '</td>'+
			'	<td scope = "col" id = "student_YEARLEVEL_' + id + '">' + yearlevel + '</td>'+
			'	<td scope = "col" id = "student_GUARDIAN_' + id + '">' + guardian + '</td>'+
			'	<td scope = "col" id = "student_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "student_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td><div class = "btn-group" role = "group"><button id="student_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateStudentForm_MODAL" data-toggle = "modal" onclick = "updateStudentFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="student_BTNCHANGEPASSWORD_' + id + '" value="' + id + '" class="btn btn-info ml-1" role = "button" data-target = "#updateStudentPasswordForm_MODAL" data-toggle = "modal" onclick="updateStudentPasswordFill(' + id + ')"><i class="fas fa-key"></i></button><button id="student_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteStudentModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
	}

	<?php 

	$list_sql = 'WITH OrderedList AS
				(
	
				SELECT

					Student.idStudent,
					Course.CName,
					Student.SUsername,
					Student.SPassword,
					Student.SLastName,
					Student.SFirstName,
					Student.SMiddleName,
					Student.SYearLevel,
					Student.SGuardianName,
					Student.SDateCreated,
					Student.SStatus,
					ROW_NUMBER() OVER (ORDER BY Student.SUsername) AS "RowNumber"
					
				FROM Student
				
				INNER JOIN Course
				ON Student.CCode = Course.CCode

				WHERE (Student.SUsername LIKE ? OR Student.SFirstName LIKE ? OR Student.SLastName LIKE ? OR Student.SMiddleName LIKE ?)
				)
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $search);
	$list_getResult->bindparam(4, $search);
	$list_getResult->bindparam(5, $limit);
	$list_getResult->bindparam(6, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>
			

			var content = '<tr>'+
			'<tr>'+
			'<td id = "grades_TITLE_">No Students Found</td>'+
			'<tr>';

			$("#studentList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idStudent']); 
			$result_USERNAME = htmlspecialchars($list_row['SUsername']);
			$result_FIRSTNAME = htmlspecialchars($list_row['SFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['SMiddleName']);
			$result_LASTNAME = htmlspecialchars($list_row['SLastName']);
			$result_YEARLEVEL = htmlspecialchars($list_row['SYearLevel']);
			$result_DATECREATED = htmlspecialchars($list_row['SDateCreated']);
			$result_COURSE = htmlspecialchars($list_row['CName']);
			$result_GUARDIANNAME = htmlspecialchars($list_row['SGuardianName']);
			$result_STATUS = htmlspecialchars($list_row['SStatus']);

		if($result_STATUS == '1')
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addStudentList("<?php echo $result_ID; ?>","<?php echo $result_USERNAME; ?>","<?php echo $result_FIRSTNAME; ?>","<?php echo $result_MIDDLENAME; ?>", "<?php echo $result_LASTNAME; ?>","<?php echo $result_YEARLEVEL; ?>" ,"<?php echo $result_COURSE; ?>","<?php echo $result_GUARDIANNAME; ?>","<?php echo $result_DATECREATED; ?>","<?php echo $result_STATUS; ?>");
		
		<?php 
		}			
	}
	?>

</script>	