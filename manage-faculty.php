<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	
	$total_count = $db->connection->query('SELECT COUNT(*) FROM Faculty')->fetchColumn(); 
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

	<div class="modal fade" id="addFacultyForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Faculty Add Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="subjectModalTitle">Add Faculty</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addFacultyForm" class="form-horizontal" action="library/form/frmAddFaculty.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
								<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Faculty Code</span>
									  </div>
									  <input id = "addFacultyForm_CODE" name = "CODE" type="input" class="form-control" placeholder="XX-XXXX-XX" aria-describedby="sizing-addon2" required maxlength="10" minlength="10">
								</div>
								<br />
								<div class="input-group">
								<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Username</span>
									  </div>
									  <input id = "addFacultyForm_USERNAME" name = "USERNAME" type="input" class="form-control" placeholder="Username" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Password</span>
									  </div>
									  <input id = "addFacultyForm_PASSWORD" name = "PASSWORD" type="password" class="form-control" placeholder="Password" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<h6 class = "font-weight-bold float-right">Personal Information </h6>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">First name</span>
									  </div>
									  <input id = "addFacultyForm_FIRSTNAME" name = "FIRSTNAME" type="text" class="form-control" placeholder="First name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Last name</span>
									  </div>
									  <input id = "addFacultyForm_LASTNAME" name = "LASTNAME" type="text" class="form-control" placeholder="Last name" aria-describedby="sizing-addon2" required>
								</div>
								<br / >
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Middle name</span>
									  </div>
									  <input id = "addFacultyForm_MIDDLENAME" name = "MIDDLENAME" type="text" class="form-control" placeholder="Middle name" aria-describedby="sizing-addon2">
								</div>
								<br />
								 <div class="form-group">
								    <select name = "FACULTYTYPE" class="form-control" id="addFacultyForm_FACULTYTYPE" required>
								      <option selected = "true" disabled value = "">Select Faculty Type</option>
								    <?php
								    	$sql = "SELECT * FROM FacultyType WHERE FTStatus = 1 ORDER BY FTName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idFacultyType"]; ?>" id="Building_<?php echo $row["idFacultyType"]; ?>"><?php echo $row["FTName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
 								<div class="form-group">
								    <select name = "COLLEGE" class="form-control" id="addFacultyForm_COLLEGE" required>
								      <option selected = "true" disabled value = "">Select a College</option>
								    <?php
								    	$sql = "SELECT * FROM College WHERE CStatus = 1 ORDER BY CName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idCollege"]; ?>" id="College_<?php echo $row["idCollege"]; ?>"><?php echo $row["CName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addFacultyForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="updateFacultyForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Update Faculty</h5>
				</div>
				<form id="updateFacultyForm" method="post" action="library/form/frmUpdateFaculty.php">
					<div class="modal-body">
						<input type="hidden" id="updateFacultyForm_ID" name="ID" required />
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-heading pull-right"><b>Information</b></div>
							<br />
							<div class="panel-body">
							<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Faculty Code</span>
									  </div>
									  <input id = "updateFacultyForm_CODE" name = "CODE" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
							<br />
							<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Username</span>
									  </div>
									  <input id = "updateFacultyForm_USERNAME" name = "USERNAME" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<h6 class = "font-weight-bold float-right">Personal Information </h6>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">First name</span>
									  </div>
									  <input id = "updateFacultyForm_FIRSTNAME" name = "FIRSTNAME" type="text" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Last name</span>
									  </div>
									  <input id = "updateFacultyForm_LASTNAME" name = "LASTNAME" type="text" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br / >
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Middle name</span>
									  </div>
									  <input id = "updateFacultyForm_MIDDLENAME" name = "MIDDLENAME" type="text" class="form-control" aria-describedby="sizing-addon2">
								</div>
								<br />
								 <div class="form-group">
								    <select name = "FACULTYTYPE" class="form-control" id="updateFacultyForm_FACULTYTYPE" required>
								      <option selected = "true" disabled value = "">Select Faculty Type</option>
								    <?php
								    	$sql = "SELECT * FROM FacultyType WHERE FTStatus = 1 ORDER BY FTName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idFacultyType"]; ?>" id="Building_<?php echo $row["idFacultyType"]; ?>"><?php echo $row["FTName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
 								<div class="form-group">
								    <select name = "COLLEGE" class="form-control" id="updateFacultyForm_COLLEGE" required>
								      <option selected = "true" disabled value = "">Select a College</option>
								    <?php
								    	$sql = "SELECT * FROM College WHERE CStatus = 1 ORDER BY CName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idCollege"]; ?>" id="College_<?php echo $row["idCollege"]; ?>"><?php echo $row["CNameAbbr"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								 <div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateFacultyForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="updateFacultyForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="updatePasswordForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Faculty Update Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="updatePasswordForm_INFORMATION"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "updatePasswordForm" class="form-horizontal" action="library/form/frmUpdateFacultyPassword.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
					<input type="hidden" id="updatePasswordForm_ID" name="ID" required />
					<input type="hidden" id="updatePasswordForm_USERNAME" name="USERNAME" required />
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Password</span>
									  </div>
									  <input id = "updatePasswordForm_PASSWORD" name = "PASSWORD" type="password" class="form-control" placeholder="Password" aria-describedby="sizing-addon2" required>
								</div>

					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "updatePasswordForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Update.." value = "Update">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="importFacultyTeacherForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Import Bulk Teacher Data</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		         <form id = "importFacultyTeacherForm" class="form-horizontal" action="library/form/frmImportFacultyTeacher.php" method="post" name="upload_excel" enctype="multipart/form-data">
		         <div class="input-group mb-3">
					  <div class="input-group-prepend">
					    <span class="input-group-text">Upload</span>
					  </div>
					  <div class="custom-file">
					    <input type="file" id = "importFacultyTeacherForm_FILE" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					    <label class="custom-file-label" for="fileupload">Choose file</label>
					  </div>
					</div>
	                      
		      </div>
		      <div class="modal-footer">
		        <input type="submit" class="btn btn-success" id = "importFacultyTeacherForm_SUBMIT" name = "Import" data-loading-text = "Importing.." value = "Import">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		  </form>
		    </div>
		  </div>
		</div>


	<div class="modal fade" id="deleteFacultyForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Faculty</h5>
			</div>
            <form id="deleteFacultyForm" method="post" action="library/form/frmDeleteFaculty.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteFacultyForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Faculty Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>USERNAME</td>
                            <td id="deleteFacultyForm_USERNAME"></td>
                        </tr>
                        <tr>
                            <td>Full Name:</td>
                            <td id="deleteFacultyForm_FULLNAME"></td>
                        </tr>
                        <tr>
                            <td>Faculty Type:</td>
                            <td id="deleteFacultyForm_FACULTYTYPE"></td>
                        </tr>
                        <tr>
                            <td>College: </td>
                            <td id="deleteFacultyForm_COLLEGE"></td>
                        </tr>
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteFacultyForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteFacultyForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteFacultyForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>


	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Faculty Management</h1>
					<p class = "text-muted">Manages Faculty Type/Roles.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addFacultyForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Faculty</a>
						    <a class="dropdown-item" href="#importFacultyTeacherForm_MODAL" data-toggle="modal">Import Teacher Data</a>
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
				  <th scope="col">Code</th>
				  <th scope="col">Username</th>
				  <th scope="col">Faculty Type</th>
				  <th scope="col">College</th>
				  <th scope="col">Fullname</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "facultyList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-faculty.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-faculty.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-faculty.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-faculty.php?search='+searchValue;
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
        facultyList: document.getElementById('facultyList')
    };

    var AFForm = {
    	form: document.getElementById('addFacultyForm'),
    	username: document.getElementById('addFacultyForm_USERNAME'),
    	password: document.getElementById('addFacultyForm_PASSWORD'),
    	firstname: document.getElementById('addFacultyForm_FIRSTNAME'),
    	middlename: document.getElementById('addFacultyForm_MIDDLENAME'),
    	lastname: document.getElementById('addFacultyForm_LASTNAME'),
    	college: document.getElementById('addFacultyForm_COLLEGE'),
    	facultytype: document.getElementById('addFacultyForm_FACULTYTYPE'),
    	modal: '#addFacultyForm_MODAL',
    	submit: '#addFacultyForm_SUBMIT'
    }

	AFForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(AFForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(AFForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(AFForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						AFForm.form.reset();
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

	var FTForm =
    {
    	form: document.getElementById('importFacultyTeacherForm'),
    	file: document.getElementById('importFacultyTeacherForm_FILE'),
    	modal: '#importFacultyTeacherForm_MODAL',
    	submit: '#importFacultyTeacherForm_SUBMIT'
    }

    FTForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(FTForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(FTForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(FTForm.modal).modal('hide');
						alert('Imported Succesfully');
						window.location.reload(false); 						
						FTForm.form.reset();
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

	var UFForm = {
		form: document.getElementById('updateFacultyForm'),
		id: document.getElementById('updateFacultyForm_ID'),
		code: document.getElementById('updateFacultyForm_CODE'),
		username: document.getElementById('updateFacultyForm_USERNAME'),
		firstname: document.getElementById('updateFacultyForm_FIRSTNAME'),
		middlename: document.getElementById('updateFacultyForm_MIDDLENAME'),
		lastname: document.getElementById('updateFacultyForm_LASTNAME'),
		facultytype: document.getElementById('updateFacultyForm_FACULTYTYPE'),
		college: document.getElementById('updateFacultyForm_COLLEGE'),
		status: document.getElementById('updateFacultyForm_STATUS'),
		submit: document.getElementById('updateFacultyForm_SUBMIT')
	}

	function updateFaculty(id, code, username, facultytype, college, fullname, status)
	{
		document.getElementById('faculty_USERNAME_'+id).innerHTML = username;
		document.getElementById('faculty_CODE_'+id).inner = code;
		document.getElementById('faculty_FACULTYTYPE_'+id).innerHTML = facultytype;
		document.getElementById('faculty_COLLEGE_'+id).innerHTML = college;
		document.getElementById('faculty_FULLNAME_'+id).innerHTML = fullname;
		document.getElementById('faculty_STATUS_'+id).innerHTML = status;
	}

	function updateFacultyFill(id){
		var code = document.getElementById('faculty_CODE_'+id).innerHTML;
		var username = document.getElementById('faculty_USERNAME_'+id).innerHTML;
		var firstname = document.getElementById('faculty_FIRSTNAME_'+id).innerHTML;
		var middlename = document.getElementById('faculty_MIDDLENAME_'+id).innerHTML;
		var lastname = document.getElementById('faculty_LASTNAME_'+id).innerHTML;
		var status = document.getElementById('faculty_STATUS_'+id).innerHTML;

		UFForm.id.value = id;
		UFForm.code.value = code;
		UFForm.username.value = username;
		UFForm.firstname.value = firstname;
		UFForm.middlename.value = middlename;
		UFForm.lastname.value = lastname;

		var college = document.getElementById('faculty_COLLEGE_' + id).innerHTML;
		for(var i = 0; i < UFForm.college.options.length; i++) {
			if(UFForm.college.options[i].text == college) {
				UFForm.college.selectedIndex = i;
			}
		}

		var facultytype = document.getElementById('faculty_FACULTYTYPE_' + id).innerHTML;
		for(var i = 0; i < UFForm.facultytype.options.length; i++) {
			if(UFForm.facultytype.options[i].text == facultytype) {
				UFForm.facultytype.selectedIndex = i;
			}
		}		

		if(status == 'Active')
		{
			UFForm.status.selectedIndex = 0;
			UFForm.status.value = 1;
		}
		else
		{
			UFForm.status.selectedIndex = 1;
			UFForm.status.value = 0;
		}
	}

	UFForm.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UFForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UFForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{			
					updateFaculty(GetSuccessMsg(server_message), UFForm.code.value, UFForm.username.value, UFForm.facultytype.options[UFForm.facultytype.selectedIndex].text, UFForm.college.options[UFForm.college.selectedIndex].Text, UFForm.firstname.value + " " + UFForm.middlename.value + " " + UFForm.lastname.value, UFForm.status.options[UFForm.status.selectedIndex].text);
					$('#updateFacultyForm_MODAL').modal('toggle');
					alert("Updated Succesfully");
					UFForm.form.reset();
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


	var UFPForm = {
		form: document.getElementById('updatePasswordForm'),
		id: document.getElementById('updatePasswordForm_ID'),
		password: document.getElementById('updatePasswordForm_PASSWORD'),
		username: document.getElementById('updatePasswordForm_USERNAME'),
		information: document.getElementById('updatePasswordForm_INFORMATION'),
		modal: document.getElementById('updatePasswordForm_MODAL'),
		submit: document.getElementById('updatePasswordForm_SUBMIT')
	}

	function updatePasswordFill(id)
	{
		var username = document.getElementById('faculty_USERNAME_'+id).innerHTML;

		UFPForm.id.value = id;
		UFPForm.username.value = username;
		
		UFPForm.information.innerHTML = "New Password for " + username; 
	}

	UFPForm.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UFPForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UFPForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
					$('#updatePasswordForm_MODAL').modal('toggle');
					alert("Updated Password Succesfully");
					UFPForm.form.reset();
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


	var DFForm = {
		form: document.getElementById('deleteFacultyForm'),
		modal: document.getElementById('deleteFacultyForm_MODAL'),
		id: document.getElementById('deleteFacultyForm_ID'),
		username: document.getElementById('deleteFacultyForm_USERNAME'),
		fullname: document.getElementById('deleteFacultyForm_FULLNAME'),
		facultytype: document.getElementById('deleteFacultyForm_FACULTYTYPE'),
		college: document.getElementById('deleteFacultyForm_COLLEGE'),
		datecreated: document.getElementById('deleteFacultyForm_DATECREATED'),
		status: document.getElementById('deleteFacultyForm_STATUS'),
		submit: document.getElementById('deleteFacultyForm_SUBMIT')
	}

	$(DFForm.form).on('submit', function (e) {
        var id = DFForm.id.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DFForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(UFPForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DFForm.submit).button('reset');
					deleteFaculty(id);
					DFForm.form.reset();
					$(DFForm.modal).modal('hide');
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
	
	function deleteFaculty(id) {
        $('#faculty_' + id).remove();
    }

    function openDeleteFacultyModal(id) {
        DFForm.id.value = id;
        DFForm.fullname.innerHTML = document.getElementById('faculty_FULLNAME_' + id).innerHTML;
        DFForm.username.innerHTML = document.getElementById('faculty_USERNAME_' + id).innerHTML;
        DFForm.facultytype.innerHTML = document.getElementById('faculty_FACULTYTYPE_'+id).innerHTML;
        DFForm.college.innerHTML = document.getElementById('faculty_COLLEGE_'+id).innerHTML;
        DFForm.datecreated.innerHTML = document.getElementById('faculty_DATECREATED_'+id).innerHTML;
        DFForm.status.innerHTML = document.getElementById('faculty_STATUS_'+id).innerHTML;
        $(DFForm.modal).modal('show');
    }

    function addFacultyList(id, code, facultytype, username, college, lastname, firstname, middlename, datecreated, status)
    {
    	PageComponent.facultyList.innerHTML = PageComponent.facultyList.innerHTML +
    		'<thead>'+
			'<tr id = "faculty_'+ id +'">'+
			'	<td scope = "col" id = "faculty_CODE_' + id + '">' + code + '</td>'+
			'	<td scope = "col" id = "faculty_USERNAME_' + id +'">' + username + '</td>'+
			'	<td scope = "col" id = "faculty_FACULTYTYPE_' + id + '">' + facultytype + '</td>'+
			'	<td scope = "col" id = "faculty_COLLEGE_' + id + '">' + college + '</td>'+
			'	<td scope = "col" id = "faculty_FULLNAME_' + id + '">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "faculty_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "faculty_FIRSTNAME_' + id + '" hidden>' + firstname + '</td>'+
			'	<td scope = "col" id = "faculty_MIDDLENAME_' + id + '" hidden>' + middlename + '</td>'+
			'	<td scope = "col" id = "faculty_LASTNAME_' + id + '" hidden>' + lastname + '</td>'+
			'	<td scope = "col" id = "faculty_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="faculty_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateFacultyForm_MODAL" data-toggle = "modal" onclick = "updateFacultyFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="faculty_BTNCHANGEPASSWORD_' + id + '" value="' + id + '" class="btn btn-info ml-1" role = "button" data-target = "#updatePasswordForm_MODAL" data-toggle = "modal" onclick="updatePasswordFill(' + id + ')"><i class="fas fa-key"></i></button><button id="faculty_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteFacultyModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT
				
				Faculty.idFaculty,
				FacultyType.FTName,
				College.CNameAbbr,
				Faculty.FCode,
				Faculty.FUsername,
				Faculty.FLastName,
				Faculty.FFirstName,
				Faculty.FMiddleName,
				Faculty.FStatus,
				Faculty.FDateCreated,
				ROW_NUMBER() OVER (ORDER BY Faculty.FUsername) AS "RowNumber"
				
				FROM Faculty
				
				INNER JOIN FacultyType
				ON Faculty.idFacultyType = FacultyType.idFacultyType
				INNER JOIN College
				ON College.idCollege = Faculty.idCollege
				
				WHERE (Faculty.FUsername LIKE ? OR Faculty.FFirstName LIKE ? OR Faculty.FLastName LIKE ? OR Faculty.FMiddleName LIKE ?)
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
			'<td id = "subjects_TITLE_">No Faculty Found</td>'+
			'<tr>';

			$("#facultyList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{

			$result_ID = htmlspecialchars($list_row['idFaculty']);
			$result_CODE = htmlspecialchars($list_row['FCode']);
			$result_FACULTYTYPE = htmlspecialchars($list_row['FTName']);
			$result_USERNAME = htmlspecialchars($list_row['FUsername']);
			$result_COLLEGE = htmlspecialchars($list_row['CNameAbbr']);
			$result_LASTNAME = htmlspecialchars($list_row['FLastName']);
			$result_FIRSTNAME = htmlspecialchars($list_row['FFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['FMiddleName']);
			$result_DATECREATED = htmlspecialchars($list_row['FDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['FStatus']);		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addFacultyList("<?php echo $result_ID ?>","<?php echo $result_CODE ?>","<?php echo $result_FACULTYTYPE ?>","<?php echo $result_USERNAME ?>","<?php echo $result_COLLEGE ?>","<?php echo $result_LASTNAME ?>","<?php echo $result_FIRSTNAME ?>","<?php echo $result_MIDDLENAME ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");		
		<?php 
		}			
	}
	?>


</script>	