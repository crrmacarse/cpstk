<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	
	$total_count = $db->connection->query('SELECT COUNT(*) FROM College')->fetchColumn(); 
	$total_page = ceil($total_count/5);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = $page * 5;
	$limit =  ($page * 5) - 4;


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


	<div class="modal fade" id="addCollegeForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="College Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="subjectModalTitle">Add a College</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addCollegeForm" class="form-horizontal" action="library/form/frmAddCollege.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Abbreviation</span>
									  </div>
									  <input id = "addCollegeForm_NAMEABBR" name = "NAMEABBR" type="input" class="form-control" placeholder="College Abbreviation" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Name</span>
									  </div>
									  <input id = "addCollegeForm_NAME" name = "NAME" type="input" class="form-control" placeholder="College Name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Dean</span>
									  </div>
									  <input id = "addCollegeForm_DEANNAME" name = "DEANNAME" type="input" class="form-control" placeholder="College Dean" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College History</span>
									  </div>
									  <textarea id = "addCollegeForm_HISTORY" name = "HISTORY" class="form-control" placeholder="College History" aria-describedby="sizing-addon2" required maxlength="500" style = "height: 300px;"></textarea>
								</div>
								<br />
								<div class="form-group">
								    <select name = "BUILDING" class="form-control" id="addCollegeForm_BUILDING" required>
								      <option selected = "true" disabled value = "">Building Located</option>
								    <?php
								    	$sql = "SELECT * FROM Landmark WHERE LName <> '' AND Status = 1 ORDER BY LName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idLandmark"]; ?>" id="Building_<?php echo $row["idLandmark"]; ?>"><?php echo $row["LName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addCollegeForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

		<div class="modal fade" id="updateCollegeForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Update College</h5>
				</div>
				<form id="updateCollegeForm" method="post" action="library/form/frmUpdateCollege.php">
					<div class="modal-body">
						<input type="hidden" id="updateCollegeForm_ID" name="ID" required />
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-heading pull-right"><b>Information</b></div>
							<br />
							<div class="panel-body">
							<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Abbreviation</span>
									  </div>
									  <input id = "updateCollegeForm_NAMEABBR" name = "NAMEABBR" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Name</span>
									  </div>
									  <input id = "updateCollegeForm_NAME" name = "NAME" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College Dean</span>
									  </div>
									  <input id = "updateCollegeForm_DEANNAME" name = "DEANNAME" type="input" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">College History</span>
									  </div>
									  <textarea id = "updateCollegeForm_HISTORY" name = "HISTORY" class="form-control"aria-describedby="sizing-addon2" required maxlength="500" style = "height: 300px;"></textarea>
								</div>
								<br />
								<div class="form-group">
								    <select name = "BUILDING" class="form-control" id="updateCollegeForm_BUILDING" required>
								      <option selected = "true" disabled value = "">Building Located</option>
								    <?php
								    	$sql = "SELECT * FROM Landmark WHERE LName <> '' AND Status = 1 ORDER BY LName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idLandmark"]; ?>" id="Building_<?php echo $row["idLandmark"]; ?>"><?php echo $row["LName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								 <div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateCollegeForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="updateCollegeForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	
	<div class="modal fade" id="deleteCollegeForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete College</h5>
				</div>
	            <form id="deleteCollegeForm" method="post" action="library/form/frmDeleteCollege.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteCollegeForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>College Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td>College ABBR: </td>
	                            <td id="deleteCollegeForm_NAMEABBR"></td>
	                        </tr>
	                        <tr>
	                            <td>College Name: </td>
	                            <td id="deleteCollegeForm_NAME"></td>
	                        </tr>
	                        <tr>
	                            <td>College Dean: </td>
	                            <td id="deleteCollegeForm_DEANNAME"></td>
	                        </tr>
         	                <tr>
	                            <td>History: </td>
	                            <td id="deleteCollegeForm_HISTORY"></td>
	                        </tr>
         	                <tr>
	                            <td>Building Located: </td>
	                            <td id="deleteCollegeForm_BUILDING"></td>
	                        </tr>	                        
	                        <tr>
	                            <td>Added By: </td>
	                            <td id="deleteCollegeForm_ADDEDBY"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created: </td>
	                            <td id="deleteCollegeForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteCollegeForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteCollegeForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">College Management</h1>
					<p class = "text-muted">Manages College History, Department and Course Offered</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addCollegeForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add a College</a>
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
				  <th scope="col">College Abbr</th>
  				  <th scope="col">College Name</th>
				  <th scope="col">College Dean</th>
				  <th scope="col">College History</th>
  				  <th scope="col">Department</th>
	 			  <th scope="col">Courses Offered</th>
				  <th scope="col">Status</th>
				  <th scope="col">Action</th>
				</tr>
			  </thead>
			  <tbody id = "collegeList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-college.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-college.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-college.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
						</li>			  </ul>
				</nav>
			</div>
			</div>

		<?php include('library/html/footer.html'); ?>

	</div>
</body>	
		 
	
	
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
				window.location.href='manage-college.php?search='+searchValue;
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
        collegeList: document.getElementById('collegeList')
    };

    function viewCourse(id){
    	window.location.href='manage-course.php?college='+id;
    }

    function viewDepartment(id){
    	window.location.href='manage-department.php?college='+id;
    }

 
    var ACForm = {
    	form: document.getElementById('addCollegeForm'),
    	nameabbr: document.getElementById('addCollegeForm_NAMEABBR'),
    	name: document.getElementById('addCollegeForm_NAME'),
    	deanname: document.getElementById('addCollegeForm_DEANNAME'),
    	history: document.getElementById('addCollegeForm_HISTORY'),
    	building: document.getElementById('addCollegeForm_BUILDING'),
    	submit: '#addCollegeForm_SUBMIT',
    	modal: '#addCollegeForm_MODAL'
    }

	ACForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ACForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ACForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ACForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ACForm.form.reset();
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

	var UCForm = {
		form: document.getElementById('updateCollegeForm'),
		id: document.getElementById('updateCollegeForm_ID'),
		nameabbr: document.getElementById('updateCollegeForm_NAMEABBR'),
		name: document.getElementById('updateCollegeForm_NAME'),
		building: document.getElementById('updateCollegeForm_BUILDING'),
		deanname: document.getElementById('updateCollegeForm_DEANNAME'),
		history: document.getElementById('updateCollegeForm_HISTORY'),
		status: document.getElementById('updateCollegeForm_STATUS'),
		modal: document.getElementById('updateCollegeForm_MODAL'),
		submit: document.getElementById('updateCollegeForm_SUBMIT')
	}

	function updateCollege(id, nameabbr, name, building, deanname, history, status)
	{
		document.getElementById('college_NAMEABBR_'+id).innerHTML = nameabbr;
		document.getElementById('college_NAME_'+id).innerHTML = name;
		document.getElementById('college_BUILDING_'+id).innerHTML = building;
		document.getElementById('college_DEAN_'+id).innerHTML = deanname;
		document.getElementById('college_HISTORY_'+id).innerHTML = history;
		document.getElementById('college_STATUS_'+id).innerHTML = status;
	}

	function updateCollegeFill(id){
		var nameabbr = document.getElementById('college_NAMEABBR_'+id).innerHTML;
		var name = document.getElementById('college_NAME_'+id).innerHTML;
		var deanname = document.getElementById('college_DEAN_'+id).innerHTML;
		var history = document.getElementById('college_HISTORY_'+id).innerHTML;
		var status = document.getElementById('college_STATUS_'+id).innerHTML;

		UCForm.id.value = id;
		UCForm.nameabbr.value = nameabbr;
		UCForm.name.value = name;
		UCForm.deanname.value = deanname;
		UCForm.history.value = history;

		var building = document.getElementById('college_BUILDING_'+id).innerHTML;
		for(var i = 0; i < UCForm.building.options.length; i++) {
			if(UCForm.building.options[i].text == building) {
				UCForm.building.selectedIndex = i;
			}
		}

		if(status == 'Active')
		{
			UCForm.status.selectedIndex = 0;
			UCForm.status.value = 1;
		}
		else
		{
			UCForm.status.selectedIndex = 1;
			UCForm.status.value = 0;
		}
	}

	UCForm.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UCForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UCForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{			
					updateCollege(GetSuccessMsg(server_message), UCForm.nameabbr.value, UCForm.name.value, UCForm.building.options[UCForm.building.selectedIndex].Text, UCForm.deanname.value, UCForm.history.value, UCForm.status.options[UCForm.status.selectedIndex].text);
					$('#updateCollegeForm_MODAL').modal('toggle');
					alert("Updated Succesfully");
					UCForm.form.reset();
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


	var DCForm = {
		form: document.getElementById('deleteCollegeForm'),
		modal: document.getElementById('deleteCollegeForm_MODAL'),
		id: document.getElementById('deleteCollegeForm_ID'),
		name: document.getElementById('deleteCollegeForm_NAME'),
		nameabbr: document.getElementById('deleteCollegeForm_NAMEABBR'),
		deanname: document.getElementById('deleteCollegeForm_DEANNAME'),
		history: document.getElementById('deleteCollegeForm_HISTORY'),
		building: document.getElementById('deleteCollegeForm_BUILDING'),
		addedby: document.getElementById('deleteCollegeForm_ADDEDBY'),
		datecreated: document.getElementById('deleteCollegeForm_DATECREATED'),
		status: document.getElementById('deleteCollegeForm_STATUS')
	}


	$(DCForm.form).on('submit', function (e) {
        var id = DCForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DCForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(DCForm.submit).button('reset');
				deleteCollege(id);
				DCForm.form.reset();
				$(DCForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deleteCollege(id) {
        $('#college_' + id).remove();
    }

    function openDeleteCollegeModal(id){
    	DCForm.id.value = id;
        DCForm.name.innerHTML = document.getElementById('college_NAME_'+id).innerHTML;
        DCForm.nameabbr.innerHTML = document.getElementById('college_NAMEABBR_'+id).innerHTML;
        DCForm.deanname.innerHTML = document.getElementById('college_DEAN_'+id).innerHTML;
        DCForm.building.innerHTML = document.getElementById('college_BUILDING_'+id).innerHTML;
        DCForm.history.innerHTML = document.getElementById('college_HISTORY_'+id).innerHTML;
        DCForm.datecreated.innerHTML = document.getElementById('college_DATECREATED_'+id).innerHTML;
        DCForm.addedby.innerHTML = document.getElementById('college_ADDEDBY_'+id).innerHTML;
        DCForm.status.innerHTML = document.getElementById('college_STATUS_'+id).innerHTML;
     	$(DCForm.modal).modal('show');
    }


    function addCollegeList(id, name, nameabbr, cdean, history, building, addedby, datecreated, status)
    {
    	PageComponent.collegeList.innerHTML = PageComponent.collegeList.innerHTML +
    		'<thead>'+
			'<tr id = "college_'+ id +'">'+
			'	<td scope = "col" id = "college_NAMEABBR_' + id +'">' + nameabbr + '</td>'+
			'	<td scope = "col" id = "college_NAME_' + id +'">' + name + '</td>'+
			'	<td scope = "col" id = "college_DEAN_' + id +'">' + cdean + '</td>'+
			'	<td scope = "col" id = "college_HISTORY_' + id +'">' + history + '</td>'+
			'	<td></button><button id = "college_VIEWDEPARTMENT_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewDepartment(\'' + id + '\')" role = "button" dal(' + id + ')">View Department</button></td>'+
			'	<td></button><button id = "college_VIEWCOURSE_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewCourse(\'' + id + '\')" role = "button" dal(' + id + ')">View Courses</button></td>'+
			'	<td scope = "col" id = "college_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "college_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "college_BUILDING_' + id + '" hidden>' + building + '</td>'+
			'	<td scope = "col" id = "college_ADDEDBY_' + id + '" hidden>' + addedby + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="college_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateCollegeForm_MODAL" data-toggle = "modal" onclick = "updateCollegeFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="college_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteCollegeModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
				
				College.idCollege,
				Landmark.LName,
				College.CName,
				College.CNameAbbr,
				College.CDean,
				College.CHistory,
				College.CDateCreated,
				Faculty.FFirstName, 
				Faculty.FMiddleName,
				Faculty.FLastName,
				College.CStatus,
				ROW_NUMBER() OVER (ORDER BY College.idCollege) AS "RowNumber"
			
				FROM College 
				
				INNER JOIN Landmark
				ON Landmark.idLandmark = College.idLandmark
				INNER JOIN Faculty 
				ON Faculty.idFaculty = College.CAddedBy
	
				WHERE (College.CName LIKE ? OR College.CNameAbbr LIKE ?)
				)

				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->bindparam(4, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "college_TITLE_">No College Found</td>'+
			'<tr>';

			$("#collegeList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idCollege']);
			$result_NAME = htmlspecialchars($list_row['CName']);
			$result_NAMEABBR = htmlspecialchars($list_row['CNameAbbr']);
			$result_CDEAN = htmlspecialchars($list_row['CDean']);
			$result_HISTORY = htmlspecialchars($list_row['CHistory']);
			$result_BUILDING = htmlspecialchars($list_row['LName']);
			$result_ADDEDBY_LASTNAME = htmlspecialchars($list_row['FLastName']);
			$result_ADDEDBY_FIRSTNAME = htmlspecialchars($list_row['FFirstName']);
			$result_ADDEDBY_MIDDLENAME = htmlspecialchars($list_row['FMiddleName']);
			$result_DATECREATED = htmlspecialchars($list_row['CDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['CStatus']);
		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addCollegeList("<?php echo $result_ID ?>","<?php echo $result_NAME ?>","<?php echo $result_NAMEABBR ?>","<?php echo $result_CDEAN ?>","<?php echo $result_HISTORY ?>","<?php echo $result_BUILDING ?>","<?php echo $result_ADDEDBY_FIRSTNAME . ' ' . $result_ADDEDBY_MIDDLENAME . ' ' . $result_ADDEDBY_LASTNAME ?>" ,"<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	