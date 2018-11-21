<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	if(!isset($_SESSION['USER_USERNAME']))
	{
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>Un-authorized Access</h4> </div> </div> </body> </html>';
        exit;
	}

	$search = isset($_GET['search']) ? $_GET['search'] : '';
	$limit = 10;


	$total_count = $db->connection->query('SELECT COUNT(*) FROM Employee')->fetchColumn(); 
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

		<!-- Modal -->
	<div class="modal fade" id="addEmployeesForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Employee Registration</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addEmployeesForm" class="form-horizontal" action="library/form/frmaddEmployee.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Username</span>
									  </div>
									  <input id = "addEmployeeForm_USERNAME" name = "USERNAME" type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Password</span>
									  </div>
									  <input id = "addEmployeeForm_PASSWORD" name = "PASSWORD" type="password" class="form-control" placeholder="Password" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "USERTYPE" class="form-control" id="addEmployeeForm_USERTYPE" required>
								      <option selected = "true" disabled value = "">Select a Role</option>
								    <?php
								    	$sql = "SELECT * FROM UserType WHERE idUserType <> 2 ORDER BY UTName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idUserType"]; ?>" id="employee_<?php echo $row["idUserType"]; ?>"><?php echo $row["UTName"]; ?></option>
											<?php
										}
								    ?>
								    </select>
								 </div>
								<br />
								<h6 class = "float-right">Personal Information</h6>
								<br />
								<div class="input-group mt-3">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Firstname</span>
									  </div>
									  <input id = "addEmployeeForm_FIRSTNAME" name = "FIRSTNAME" type="text" class="form-control" placeholder="Firstname" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Middlename</span>
									  </div>
									  <input id = "addEmployeeForm_MIDDLENAME" name = "MIDDLENAME" type="text" class="form-control" placeholder="Middlename" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Lastname</span>
									  </div>
									  <input id = "addEmployeeForm_LASTNAME" name = "LASTNAME" type="text" class="form-control" placeholder="Lastname" aria-describedby="sizing-addon2" required>
								</div>
								<br />
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
	
	<div class="modal fade" id="updateStudentsForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Update Students</h5>
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

	<div class="modal fade" id="deleteEmployee_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Employee</h5>
			</div>
            <form id="deleteEmployeeForm" method="post" action="library/form/frmDeleteEmployee.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteEmployeeForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Employee Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Username: </td>
                            <td id="deleteEmployeeForm_USERNAME"></td>
                        </tr>
                        <tr>
                            <td>Role: </td>
                            <td id="deleteEmployeeForm_ACCOUNTTYPE"></td>
                        </tr>
                        <tr>
                            <td>Employee Fullname: </td>
                            <td id="deleteEmployeeForm_FULNAME"></td>
                        </tr>
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteEmployeeeForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteEmployeeeForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteEmployeeeForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>


	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Employee Management</h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-accounts.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addEmployeesForm_MODAL" class = "dropdown-item"data-toggle = "modal">Register</a>
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
					  <th scope="col">Username</th>
					  <th scope="col">Role</th>
					  <th scope="col">Employee's Fullname</th>
					  <th scope="col">Status</th>		  
					  <th scope="col">Actions</th>
					</tr>
				  </thead>
				  <tbody id = "employeeList">
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
        employeeList: document.getElementById('employeeList')
    };

    var AEForm = {
    	form: document.getElementById('addEmployeesForm'),
    	username: document.getElementById('addEmployeeForm_USERNAME'),
    	password: document.getElementById('addEmployeeForm_PASSWORD'),
    	usertype: document.getElementById('addEmployeeForm_USERTYPE'),
    	firstname: document.getElementById('addEmployeeForm_FIRSTNAME'),
    	middlename: document.getElementById('addEmployeeForm_MIDDLENAME'),
    	lastname: document.getElementById('addEmployeeForm_LASTNAME'),
    	submit: '#addEmployeeForm_SUBMIT',
    	modal: '#addEmployeesForm_MODAL'
    }


	AEForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(AEForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(AEForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(AEForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						AEForm.form.reset();

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


	 var DEForm = {
	    form: document.getElementById('deleteEmployeeForm'),
	    modal: document.getElementById('deleteEmployee_MODAL'),
	    id: document.getElementById('deleteEmployeeForm_ID'),
	    username: document.getElementById('deleteEmployeeForm_USERNAME'),
	    fullname: document.getElementById('deleteEmployeeForm_FULNAME'),
	    accounttype: document.getElementById('deleteEmployeeForm_ACCOUNTTYPE'),
	    datecreated: document.getElementById('deleteEmployeeeForm_DATECREATED'),
	    status: document.getElementById('deleteEmployeeeForm_STATUS'),
	    submit: document.getElementById('deleteEmployeeeForm_SUBMIT')
	};


	$(DEForm.form).on('submit', function (e) {
        var id = DEForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DEForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DEForm.submit).button('reset');
				deleteEmployee(id);
				DEForm.form.reset();
				$(DEForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deleteEmployee(id) {
        $('#employee_' + id).remove();
    }

    function openDeleteEmployeeModal(id) {
        DEForm.id.value = id;
        DEForm.fullname.innerHTML = document.getElementById('employee_FULLNAME_' + id).innerHTML;
        DEForm.username.innerHTML = document.getElementById('employee_USERNAME_' + id).innerHTML;
        DEForm.accounttype.innerHTML = document.getElementById('employee_ACCOUNTTYPE_' + id).innerHTML;
        DEForm.datecreated.innerHTML = document.getElementById('employee_DATECREATED_' + id).innerHTML;
        DEForm.status.innerHTML = document.getElementById('employee_STATUS_' + id).innerHTML;
        $(DEForm.modal).modal('show');
    }

    function addEmployeeList(id, username, role, firstname, middlename, lastname, datecreated, status)
	{
		PageComponent.employeeList.innerHTML = PageComponent.employeeList.innerHTML +
			'<thead>'+
			'<tr id = "employee_'+ id +'">'+
			'	<td scope = "col" id = "employee_USERNAME_' + id +'">' + username + '</td>'+
			'	<td scope = "col" id = "employee_ACCOUNTTYPE_' + id + '">' + role + '</td>'+
			'	<td scope = "col" id = "employee_FULLNAME_' + id + '">' + firstname + ' '+ middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "employee_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "employee_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><button id="employee_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateEmployeeForm_MODAL" data-toggle = "modal" onclick = "updateEmployeeFill(\'' + id + '\')"class="btn btn-primary">Update</button><button id="employee_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteEmployeeModal(' + id + ')">Delete</button></td>'+
			'</tr>'+	
			'</thead>';
	}

	<?php 

	$list_sql = 'WITH OrderedList AS
				(
				SELECT 
				Employee.idEmployee,
				UserType.UTName,
				Employee.EMPUsername,
				Employee.EMPFirstname,
				Employee.EMPMiddlename,
				Employee.EMPLastname,
				Employee.EMPStatus,
				Employee.EMPDateCreated,
				ROW_NUMBER() OVER (ORDER BY Employee.EMPUsername) AS "RowNumber"

				FROM Employee

				LEFT JOIN UserType
				ON UserType.idUserType = Employee.idUserType
				)
				SELECT * 
				FROM OrderedList
				WHERE RowNumber BETWEEN '. $offset.' AND '. $limit;
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>
			
			var content = '<tr>'+
			'<tr>'+
			'<td id = "employee_TITLE_">No Grades Found</td>'+
			'<tr>';

			$("#employeeList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idEmployee']); 
			$result_USERNAME = htmlspecialchars($list_row['EMPUsername']);
			$result_ROLE = htmlspecialchars($list_row['UTName']);
			$result_FIRSTNAME = htmlspecialchars($list_row['EMPFirstname']);
			$result_MIDDLENAME = htmlspecialchars($list_row['EMPMiddlename']);
			$result_LASTNAME = htmlspecialchars($list_row['EMPLastname']);
			$result_DATECREATED = htmlspecialchars($list_row['EMPDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['EMPStatus']);

		if($result_STATUS == '1')
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addEmployeeList("<?php echo $result_ID; ?>","<?php echo $result_USERNAME; ?>","<?php echo $result_ROLE; ?>","<?php echo $result_FIRSTNAME; ?>","<?php echo $result_MIDDLENAME; ?>", "<?php echo $result_LASTNAME; ?>","<?php echo $result_DATECREATED; ?>","<?php echo $result_STATUS; ?>");
		
		<?php 
		}			
	}
	?>


</script>	