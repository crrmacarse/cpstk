<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$period = isset($_GET['period']) ? '%'.$_GET['period'].'%' : '%%';
	$trimmedperiod = str_replace('%', '', $period);
	
	$total_count = $db->connection->query('SELECT COUNT(*) FROM ClassScheduleControl')->fetchColumn(); 
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
<html>
	
	<head>
	
		<meta charset="utf-8">

		<title>CPU Smart Touch Information Kiosk</title>

		<link href = "library/css/bootstrap.min.css" rel = " stylesheet">
		<link href = "library/css/mystyles.css" rel = "stylesheet">
		
		
	</head>

<body>
	

	<div class="modal fade" id="addClassScheduleForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Add Class Schedule</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addClassScheduleForm" class="form-horizontal" action="library/form/frmAddClassSchedule.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
								    <select name = "PERIOD" class="form-control" id="addClassScheduleForm_PERIOD" required>
								      <option selected = "true" disabled value = "">Select a Period</option>
								    <?php
									   	$sql = "SELECT Period.idPeriod, SchoolYear.SYYear, Period.PName FROM SchoolYear INNER JOIN Period ON SChoolYear.idSchoolYear = Period.idSchoolYear WHERE Period.PStatus = 'True'";
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
								 </div>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Stub Code</span>
									  </div>
									  <input id = "addClassScheduleForm_STUBCODE" name = "STUBCODE" type="text" class="form-control" placeholder="Stubcode" aria-describedby="sizing-addon2" max = "11"required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "SUBJECT" class="form-control" id="addClassScheduleForm_SUBJECT" required>
								      <option selected = "true" disabled value = "">Select a Subject</option>
								    <?php
								    	$sql = "SELECT * FROM Subjects WHERE Subjects.SStatus = 1 ORDER BY SName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["SCode"]; ?>" id="subjects_<?php echo $row["idSubjects"]; ?>"><?php echo "[". $row["SCode"] . "] ". $row["SName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<h6 class = "float-right">Class Schedule Information</h6>
								<br />
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Day</span>
									  </div>
									  <input id = "addClassScheduleForm_DAY" name = "DAY" type="input" class="form-control" placeholder="M/W/T/TH/F" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Start</span>
									  </div>
									  <input id = "addClassScheduleForm_START" name = "START" type="number" class="form-control" placeholder="XXXX" aria-describedby="sizing-addon2" required min = "0700" max="2000">
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">End</span>
									  </div>
									  <input id = "addClassScheduleForm_END" name = "END" type="number" class="form-control" placeholder="XXXX" aria-describedby="sizing-addon2" min = "0700" max="2000" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "ROOMNUMBER" class="form-control" id="addClassScheduleForm_ROOMNUMBER" required>
								      <option selected = "true" disabled value = "">Select a Room</option>
								    <?php
								    	$sql = "SELECT * FROM Room INNER JOIN Landmark ON Room.idLandmark = Landmark.idLandmark WHERE Room.RStatus = 1 ORDER BY RNumber";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["RCode"]; ?>" id="room_<?php echo $row["idRoom"]; ?>"><?php echo $row["RCode"] ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								 <div class="form-group">
								    <select name = "FACULTY" class="form-control" id="addClassScheduleForm_FACULTY" required>
								      <option selected = "true" disabled value = "">Select a Faculty</option>
								    <?php
								    	$sql = "SELECT * FROM Faculty WHERE Faculty.FStatus = 1 AND idFacultyType = 5 ORDER BY FLastName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["FCode"] ?>" id="faculty_<?php echo $row["idFaculty"]; ?>"><?php echo " [ ". $row["FCode"] . " ] " .strtoupper($row["FLastName"]) . ", " . $row["FFirstName"] . " " . $row['FMiddleName'] ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Credit</span>
									  </div>
									  <input id = "addClassScheduleForm_CREDIT" name = "CREDIT" type="input" class="form-control" placeholder="Credit" aria-describedby="sizing-addon2" min = "1" max = "5" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "CLASSTYPE" class="form-control" id="addClassScheduleForm_CLASSTYPE" required>
								      <option selected = "true" disabled value = "">Select a Class Type</option>
								      <option value = "LEC">LEC</option>
								      <option value = "LAB">LAB</option>
								    </select>
								 </div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addClassScheduleForm_SUBMIT" class="btn btn-success" name = "Submit" data-loading-text = "Importing.." value = "Register">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="importClassScheduleForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Import Class Schedule Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "importClassScheduleForm" class="form-horizontal" action="library/form/frmImportClassScheduleControl.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <input id = "importClassScheduleForm_PERIOD" type = "text" name = "PERIOD" value = "<?php echo  $trimmedperiod ?>" hidden />
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input type="file" id = "importClassScheduleForm_FILE" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input type="submit" id = "importClassScheduleForm_SUBMIT" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="updateClassSchedulelForm_MODAL" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5>Update Class Schedule</h5>
					</div>
					<form id="updateClassSchedulelForm" method="post" action="library/form/frmUpdateClassSchedule.php">
						<div class="modal-body">
							<input type="hidden" id="updateClassSchedulelForm_ID" name="ID" required />
							<!-- Infomation -->
							<div class="panel panel-default">
								<div class="panel-heading pull-right"><b>Information</b></div>
								<br />
								<div class="panel-body">
									<div class="form-group">
								    <select name = "PERIOD" class="form-control" id="updateClassSchedulelForm_PERIOD" required>
								      <option selected = "true" disabled value = "">Select a Period</option>
								    <?php
									   	$sql = "SELECT Period.idPeriod, SchoolYear.SYYear, Period.PName FROM SchoolYear INNER JOIN Period ON SChoolYear.idSchoolYear = Period.idSchoolYear WHERE Period.PStatus = 'True'";
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
								 </div>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Stub Code</span>
									  </div>
									  <input id = "updateClassSchedulelForm_STUBCODE" name = "STUBCODE" type="text" class="form-control" aria-describedby="sizing-addon2" max = "11"required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "SUBJECT" class="form-control" id="updateClassSchedulelForm_SUBJECT" required>
								      <option selected = "true" disabled value = "">Select a Subject</option>
								    <?php
								    	$sql = "SELECT * FROM Subjects WHERE Subjects.SStatus = 1 ORDER BY SName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idSubjects"]; ?>" id="subjects_<?php echo $row["idSubjects"]; ?>"><?php echo $row["SName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<h6 class = "float-right">Class Schedule Information</h6>
								<br />
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Day</span>
									  </div>
									  <input id = "updateClassSchedulelForm_DAY" name = "DAY" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Time</span>
									  </div>
									  <input id = "updateClassSchedulelForm_TIME" name = "TIME" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "ROOMNUMBER" class="form-control" id="updateClassSchedulelForm_ROOMNUMBER" required>
								      <option selected = "true" disabled value = "">Select a Room</option>
								    <?php
								    	$sql = "SELECT * FROM Room INNER JOIN Landmark ON Room.idLandmark = Landmark.idLandmark WHERE Room.RStatus = 1 ORDER BY RNumber";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idRoom"]; ?>" id="room_<?php echo $row["idRoom"]; ?>"><?php echo $row["LName"] . " " . $row["RNumber"] ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								  <div class="form-group">
								    <select name = "FACULTY" class="form-control" id="updateClassSchedulelForm_FACULTY" required>
								      <option selected = "true" disabled value = "">Select a Faculty</option>
								    <?php
								    	$sql = "SELECT * FROM Faculty WHERE Faculty.FStatus = 1 AND idFacultyType = 5 ORDER BY FLastName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idFaculty"] ?>" id="faculty_<?php echo $row["idFaculty"]; ?>"><?php echo strtoupper($row["FLastName"]) . ", " . $row["FFirstName"] . " " . $row['FMiddleName'] ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Credit</span>
									  </div>
									  <input id = "updateClassSchedulelForm_CREDIT" name = "CREDIT" type="input" class="form-control" aria-describedby="sizing-addon2" min = "1" max = "5" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "CLASSTYPE" class="form-control" id="updateClassSchedulelForm_CLASSTYPE" required>
								      <option selected = "true" disabled value = "">Select a Class Type</option>
								      <option>LEC</option>
								      <option>LAB</option>
								    </select>
								 </div>
								 <div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateClassSchedulelForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>

								</div>
							</div>
						</div>
						<!-- Submission -->
						<div class="modal-footer">
							<button type="submit" id="updateClassSchedulelForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
	</div>


	<div class="modal fade" id="deleteClassScheduleForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Class Schedule</h5>
			</div>
            <form id="deleteClassScheduleForm" method="post" action="library/form/frmDeleteClassSchedule.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteClassScheduleForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Class Schedule Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><b>School Year Offered: </b></td>
                            <td id="deleteClassScheduleForm_SCHOOLYEAR"></td>
                        </tr>
                        <tr>
                            <td>Stubcode: </td>
                            <td id="deleteClassScheduleForm_STUBCODE"></td>
                        </tr>
                        <tr>
                            <td>Subject: </td>
                            <td id="deleteClassScheduleForm_SUBJECT"></td>
                        </tr>
                        <tr>
                            <td>Room: </td>
                            <td id="deleteClassScheduleForm_ROOM"></td>
                        </tr>
                        <tr>
                            <td>Assigned Faculty: </td>
                            <td id="deleteClassScheduleForm_FACULTY"></td>
                        </tr>
                        <tr>
                            <td>Schedule: </td>
                            <td id="deleteClassScheduleForm_SCHEDULE"></td>
                        </tr>                                        
                        <tr>
                            <td>Credit: </td>
                            <td id="deleteClassScheduleForm_CREDIT"></td>
                        </tr>
                        <tr>
                       		<td>Class Type: </td>
                            <td id="deleteClassScheduleForm_CLASSTYPE"></td>
                        </tr>
                        <tr>
                            <td>Date Created:</td>
                            <td id="deleteClassScheduleForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteClassScheduleForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteClassScheduleForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Class Schedule Management</h1>
					<p class = "text-muted">Manages Class Schedule that are Offered.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addClassScheduleForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Class Schedule</a>
						    <a class="dropdown-item" href="#importClassScheduleForm_MODAL" data-toggle="modal">Import Class Schedule</a>
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
				  <select class="custom-select add-with" id="selectedPeriod" aria-label="Example select with button addon" style = "width: 35%">
				    <option selected = "true" disabled value = "">Select a Period & School Year</option>
				    		<?php
						    	$sql = "SELECT Period.idPeriod, SchoolYear.SYYear, Period.PName FROM SchoolYear INNER JOIN Period ON SChoolYear.idSchoolYear = Period.idSchoolYear WHERE Period.PStatus = 'True'";
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
				    <button id = "examSearch" class="btn btn-primary" type="button">Display</button>
				  </div>
				</div>
			</div>
		</div>

		<div class = "row top-buffer">
		  	<table class="table table-striped">
				  <thead>
					<tr>
					  <th scope="col">Stub Code</th>
					  <th scope="col">Subject</th>
					  <th scope="col">Room</th>
					  <th scope="col">Assigned Faculty</th>
					  <th scope="col">Schedule</th>
	  				  <th scope="col">Credit</th>
					  <th scope="col">Class Type</th>
					  <th scope="col">View Students</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>					  
					</tr>
				  </thead>
				  <tbody id = "classScheduleList">
				  </tbody>
				</table>
			
		</div>

		<div class = "row top-buffer justify-content-md-center">
			 <div class="col-md-auto top-buffer">
		<nav aria-label="Page navigation example">
			  <ul class="pagination justify-content-center">
			    	<li class="page-item">
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
					</li>			  </ul>
			</nav>
		</div>
		</div>
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
		$("#selectedPeriod").val('<?php echo $trimmedperiod ?>');
		$("#examSearch").click(function(){
			var period = $("#selectedPeriod").val() || '';
			window.location.href='manage-class-schedule.php?period='+period;
		});
			$("#searchBar").click(function(){
			$("#searchBar").val("");
		})
		
	});


	$(function() { //shorthand document.ready function
	    $('#importClassScheduleForm_SUBMIT').on('submit', function(e) { //use on if jQuery 1.7+
	        e.preventDefault();  //prevent form from submitting
	    });
	});

	var PageComponent = {
        classScheduleList: document.getElementById('classScheduleList')
    };

    function viewClassScheduleData(id){
    	window.location.href='manage-class-schedule-data.php?id='+id;
    }

    var ACSForm = {
    	form: document.getElementById('addClassScheduleForm'),
    	period: document.getElementById('addClassScheduleForm_PERIOD'),
    	stubcode: document.getElementById('addClassScheduleForm_STUBCODE'),
    	subject: document.getElementById('addClassScheduleForm_SUBJECT'),
    	start: document.getElementById('addClassScheduleForm_START'),
    	end: document.getElementById('addClassScheduleForm_END'),
    	day: document.getElementById('addClassScheduleForm_DAY'),
    	roomno: document.getElementById('addClassScheduleForm_ROOMNUMBER'),
    	faculty: document.getElementById('addClassScheduleForm_FACULTY'),
    	credit: document.getElementById('addClassScheduleForm_CREDIT'),
    	classtype: document.getElementById('addClassScheduleForm_CLASSTYPE'),
 		modal: '#addClassScheduleForm_MODAL',
 		submit: '#addClassScheduleForm_SUBMIT'
    };


   ACSForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ACSForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ACSForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ACSForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ACSForm.form.reset();
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

	var ICSCForm =
    {
    	form: document.getElementById('importClassScheduleForm'),
    	file: document.getElementById('importClassScheduleForm_FILE'),
    	period: document.getElementById('importClassScheduleForm_PERIOD'),
    	modal: '#importClassScheduleForm_MODAL',
    	submit: '#importClassScheduleForm_SUBMIT'
    }

    ICSCForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ICSCForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ICSCForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ICSCForm.modal).modal('hide');
						alert('Imported Succesfully');
						window.location.reload(false); 						
						ICSCForm.form.reset();
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

	var UCSForm = {
		form: document.getElementById('updateClassSchedulelForm'),
		modal: document.getElementById('updateClassSchedulelForm_MODAL'),
		id: document.getElementById('updateClassSchedulelForm_ID'),
		period: document.getElementById('updateClassSchedulelForm_PERIOD'),
		stubcode: document.getElementById('updateClassSchedulelForm_STUBCODE'),
		subject: document.getElementById('updateClassSchedulelForm_SUBJECT'),
		faculty: document.getElementById('updateClassSchedulelForm_FACULTY'),
		day: document.getElementById('updateClassSchedulelForm_DAY'),
		time: document.getElementById('updateClassSchedulelForm_TIME'),
		room: document.getElementById('updateClassSchedulelForm_ROOMNUMBER'),
		credit: document.getElementById('updateClassSchedulelForm_CREDIT'),
		classtype: document.getElementById('updateClassSchedulelForm_CLASSTYPE'),
		status: document.getElementById('updateClassSchedulelForm_STATUS'),
		submit: document.getElementById('updateClassSchedulelForm_SUBMIT')

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

	function updateClassScheduleFill(id){
		var stubcode = document.getElementById('classSchedule_STUBCODE_'+id).innerHTML;
		var day = document.getElementById('classSchedule_DAY_'+id).innerHTML;
		var time = document.getElementById('classSchedule_TIME_'+id).innerHTML;
		var credit = document.getElementById('classSchedule_DAY_'+id).innerHTML;

		UCSForm.id.value = id;
		UCSForm.stubcode.value = stubcode;
		UCSForm.day.value = day;
		UCSForm.time.value = time;
		UCSForm.credit.value = credit;

		var period = document.getElementById('classSchedule_FULLPERIOD_'+id).innerHTML;
		for(var i = 0; i < UCSForm.subject.options.length; i++) {
			if(UCSForm.period.options[i].text == period) {
				UCSForm.period.selectedIndex = i;
			}
		}

		var subject = document.getElementById('classSchedule_SUBJECTNAME_'+id).innerHTML;
		for(var i = 0; i < UCSForm.subject.options.length; i++) {
			if(UCSForm.subject.options[i].text == subject) {
				UCSForm.subject.selectedIndex = i;
			}
		}

		var room = document.getElementById('classSchedule_ROOMBUILDING_'+id).innerHTML;
		for(var i = 0; i < UCSForm.room.options.length; i++) {
			if(UCSForm.room.options[i].text == room) {
				UCSForm.room.selectedIndex = i;
			}
		}


		var faculty = document.getElementById('classSchedule_FULLNAME_'+id).innerHTML;
		for(var i = 0; i < UCSForm.faculty.options.length; i++) {
			if(UCSForm.faculty.options[i].text == faculty) {
				UCSForm.faculty.selectedIndex = i;
			}
		}		

		var classtype = document.getElementById('classSchedule_CLASSTYPE_'+id).innerHTML
		for(var i = 0; i < USForm.classtype.options.length; i++) {
			if(USForm.classtype.options[i].text == classtype) {
				USForm.classtype.selectedIndex = i;
			}
		}

		if(status == 'Active')
		{
			UCSForm.status.selectedIndex = 0;
			UCSForm.status.value = 1;
		}
		else
		{
			UCSForm.status.selectedIndex = 1;
			UCSForm.status.value = 0;
		}

	}

	UCSForm.form.onsubmit = function(e) {
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

	var DCSForm = {
	    form: document.getElementById('deleteClassScheduleForm'),
	    modal: document.getElementById('deleteClassScheduleForm_MODAL'),
	    id: document.getElementById('deleteClassScheduleForm_ID'),
	    schoolyear: document.getElementById('deleteClassScheduleForm_SCHOOLYEAR'),
	    stubcode: document.getElementById('deleteClassScheduleForm_STUBCODE'),
	    subject: document.getElementById('deleteClassScheduleForm_SUBJECT'),
	    room: document.getElementById('deleteClassScheduleForm_ROOM'),
	    faculty: document.getElementById('deleteClassScheduleForm_FACULTY'),
	    schedule: document.getElementById('deleteClassScheduleForm_SCHEDULE'),
	    credit: document.getElementById('deleteClassScheduleForm_CREDIT'),
	    classtype: document.getElementById('deleteClassScheduleForm_CLASSTYPE'),
	    datecreated: document.getElementById('deleteClassScheduleForm_DATECREATED'),
	    status: document.getElementById('deleteClassScheduleForm_STATUS'),
	    submit: document.getElementById('deleteClassScheduleForm_SUBMIT')
	   };

	function deleteClassSchedule(id) {
        $('#classSchedule_' + id).remove();
    }

    function openDeleteClassScheduleModal(id) {
        DCSForm.id.value = id;
        DCSForm.schoolyear.innerHTML = document.getElementById('classSchedule_FULLPERIOD_'+id).innerHTML;
        DCSForm.stubcode.innerHTML = document.getElementById('classSchedule_STUBCODE_'+id).innerHTML;
        DCSForm.subject.innerHTML = document.getElementById('classSchedule_SUBJECTNAME_'+id).innerHTML;
        DCSForm.room.innerHTML = document.getElementById('classSchedule_ROOMBUILDING_'+id).innerHTML;
        DCSForm.faculty.innerHTML = document.getElementById('classSchedule_FULLNAME_'+id).innerHTML;
        DCSForm.schedule.innerHTML = document.getElementById('classSchedule_SCHEDULE_'+id).innerHTML;
        DCSForm.credit.innerHTML = document.getElementById('classSchedule_CREDIT_'+id).innerHTML;
        DCSForm.classtype.innerHTML = document.getElementById('classSchedule_CLASSTYPE_'+id).innerHTML;
        DCSForm.datecreated.innerHTML = document.getElementById('classSchedule_DATECREATED_'+id).innerHTML;
        DCSForm.status.innerHTML = document.getElementById('classSchedule_STATUS_' + id).innerHTML;
        $(DCSForm.modal).modal('show');
    }


	$(DCSForm.form).on('submit', function (e) {
        var id = DCSForm.id.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DCSForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DCSForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DCSForm.submit).button('reset');
					deleteClassSchedule(id);
					DCSForm.form.reset();
					$(DCSForm.modal).modal('hide');
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


	
	function addClassScheduleList(id, stubcode, schoolyear, period, firstname, middlename, lastname, building, roomno, subject, day, start, end,classtype, credit, datecreated, status)
	{
		PageComponent.classScheduleList.innerHTML = PageComponent.classScheduleList.innerHTML +
			'<thead>'+
			'<tr id = "classSchedule_'+ id +'">'+
			'	<td scope = "col" id = "classSchedule_STUBCODE_' + id +'">' + stubcode + '</td>'+
			'	<td scope = "col" id = "classSchedule_SUBJECTNAME_' + id +'">' + subject + '</td>'+
			'	<td scope = "col" id = "classSchedule_ROOM_' + id +'" hidden>' + roomno + ' ' + building + '</td>'+
			'	<td scope = "col" id = "classSchedule_ROOMBUILDING_' + id +'">' + building + ' ' + roomno + '</td>'+
			'	<td scope = "col" id = "classSchedule_FULLNAME_' + id + '">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "classSchedule_SCHEDULE_' + id + '">' + start + ' - ' + end + ' ' + day + '</td>'+
			'	<td scope = "col" id = "classSchedule_DAY_' + id + '" hidden>' + day + '</td>'+
			'	<td scope = "col" id = "classSchedule_START_' + id + '" hidden>' + start + '</td>'+
			'	<td scope = "col" id = "classSchedule_END_' + id + '" hidden>' + end + '</td>'+
			'	<td scope = "col" id = "classSchedule_CREDIT_' + id + '">' + credit + '</td>'+
			'	<td scope = "col" id = "classSchedule_CLASSTYPE_' + id + '">' + classtype + '</td>'+
			'	<td></button><button id = "classSchedule_CSD_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewClassScheduleData(\'' + id + '\')" role = "button"">View Students</button></td>'+
			'	<td scope = "col" id = "classSchedule_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "classSchedule_PERIOD_' + id + '" hidden>' + period + '</td>'+
			'	<td scope = "col" id = "classSchedule_FULLPERIOD_' + id + '" hidden>' + period + ' ' + schoolyear + '</td>'+
			'	<td scope = "col" id = "classSchedule_SCHOOLYEAR_' + id + '" hidden>' + schoolyear + '</td>'+
			'	<td scope = "col" id = "classSchedule_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><button id="classSchedule_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteClassScheduleModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			// '	<td><div class = "btn-group" role = "group"><button id="classSchedule_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateClassSchedulelForm_MODAL" data-toggle = "modal" onclick = "updateClassScheduleFill(\'' + id + '\')"class="btn btn-warning"><i class="far fa-edit"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
	}


<?php 

	$list_sql = '
					WITH SQLList AS
							(
								SELECT 
									ClassScheduleControl.idClassScheduleControl,
									SchoolYear.SYYear,
									Period.PName,
									ClassScheduleControl.idPeriod,
									Subjects.SName,
									Landmark.LName,
									Room.RNumber,
									Faculty.FFirstName,
									Faculty.FMiddleName,
									Faculty.FLastName,
									ClassScheduleControl.CSCStubCode,
									ClassScheduleControl.CSCCredit,
									ClassScheduleControl.CSCClassType,
									ClassScheduleControl.CSCDay,
									ClassScheduleControl.CSCStart,
									ClassScheduleControl.CSCEnd,
									ClassScheduleControl.CSCStatus,
									ClassScheduleControl.CSCDateCreated,
									ROW_NUMBER() OVER (ORDER BY ClassScheduleControl.idClassScheduleControl) AS "RowNumber"

									FROM
										ClassScheduleControl
									
									INNER JOIN Subjects
									ON ClassScheduleControl.SCode = Subjects.SCode
									INNER JOIN Room
									ON ClassScheduleControl.RCode = Room.RCode
									INNER JOIN Faculty
									ON ClassScheduleControl.FCode = Faculty.FCode
									INNER JOIN Period
									ON Period.idPeriod = ClassScheduleControl.idPeriod
									INNER JOIN SchoolYear
									ON SchoolYear.idSchoolYear = Period.idSchoolYear
									INNER JOIN Landmark
									ON Landmark.idLandmark = Room.idLandmark
	

									WHERE ClassScheduleControl.idPeriod LIKE ?		
							) 
							SELECT * 
							FROM SQLList
							WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindParam(1,$period);
	$list_getResult->bindParam(2,$limit);
	$list_getResult->bindParam(3,$offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>
			

			var content = '<tr>'+
			'<tr>'+
			'<td id = "classSched_TITLE_">No Class Schedule Found</td>'+
			'<tr>';

			$("classScheduleList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idClassScheduleControl']);
			$result_STUBCODE = htmlspecialchars($list_row['CSCStubCode']);
			$result_SCHOOLYEAR = htmlspecialchars($list_row['SYYear']);
			$result_PERIOD = htmlspecialchars($list_row['PName']);
			$result_FIRSTNAME = htmlspecialchars($list_row['FFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['FMiddleName']);
			$result_LASTNAME = htmlspecialchars($list_row['FLastName']);
			$result_BUILDING = htmlspecialchars($list_row['LName']);
			$result_DAY = htmlspecialchars($list_row['CSCDay']);
			$result_START = htmlspecialchars($list_row['CSCStart']);
			$result_END = htmlspecialchars($list_row['CSCEnd']);
			$result_ROOMNUMBER = htmlspecialchars($list_row['RNumber']);
			$result_SUBJECT = htmlspecialchars($list_row['SName']);
			$result_CLASSTYPE = htmlspecialchars($list_row['CSCClassType']);
			$result_CREDIT = htmlspecialchars($list_row['CSCCredit']);
			$result_DATECREATED = htmlspecialchars($list_row['CSCDateCreated']);
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

	addClassScheduleList("<?php echo $result_ID; ?>", "<?php echo $result_STUBCODE; ?>","<?php echo $result_SCHOOLYEAR; ?>","<?php echo $result_PERIOD; ?>","<?php echo $result_FIRSTNAME; ?>","<?php echo $result_MIDDLENAME; ?>","<?php echo $result_LASTNAME; ?>","<?php echo $result_BUILDING; ?>","<?php echo $result_ROOMNUMBER; ?>","<?php echo $result_SUBJECT; ?>","<?php echo $result_DAY; ?>","<?php echo $result_START; ?>","<?php echo $result_END; ?>","<?php echo $result_CLASSTYPE; ?>","<?php echo $result_CREDIT; ?>","<?php echo $result_DATECREATED; ?>","<?php echo $result_STATUS; ?>");

		<?php 
		}			
	}
	?>



	</script>