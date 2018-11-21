<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->query('SELECT COUNT(*) FROM Subjects')->fetchColumn(); 
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


	<!-- Subject Add Modal -->
	
	<div class="modal fade" id="addSubjectForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Subject Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="subjectModalTitle">Add a Subject</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addSubjectForm" class="form-horizontal" action="library/form/frmAddSubjects.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Code</span>
									  </div>
									  <input id = "addSubjectForm_CODE" name = "CODE" type="input" class="form-control" placeholder="Subject Code" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Abbreviation</span>
									  </div>
									  <input id = "addSubjectForm_ABBR" name = "ABBR" type="input" class="form-control" placeholder="Subject's Abbreviation" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Title</span>
									  </div>
									  <input id = "addSubjectForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Subject Title" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Description</span>
									  </div>
									  <textarea id = "addSubjectForm_DESCRIPTION" name = "DESCRIPTION" class="form-control" placeholder="Subject Description" aria-describedby="sizing-addon2" required maxlength="180"></textarea>
								</div>		
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Total Credit</span>
									  </div>
									  <input id = "addSubjectForm_TOTALCREDIT" name = "TOTALCREDIT" type="number" class="form-control" min="1" max = "5" placeholder="Subject's Total Credit" aria-describedby="sizing-addon2" required>
								</div>						
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addSubjectForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>
	

	<div class="modal fade" id="importSubjectForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Import Subject Data</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		         <form id = "importSubjectForm" class="form-horizontal" action="library/form/frmImportSubjects.php" method="post" name="upload_excel" enctype="multipart/form-data">
		         <div class="input-group mb-3">
					  <div class="input-group-prepend">
					    <span class="input-group-text">Upload</span>
					  </div>
					  <div class="custom-file">
					    <input type="file" id = "importSubjectForm_FILE" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
					    <label class="custom-file-label" for="fileupload">Choose file</label>
					  </div>
					</div>
	                      
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "importSubjectForm_SUBMIT" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Import">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		  </form>
		    </div>
		  </div>
		</div>
	
	<div class="modal fade" id="updateSubjectForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Update Subjects</h5>
				</div>
				<form id="updateSubjectForm" method="post" action="library/form/frmUpdateSubjects.php">
					<div class="modal-body">
						<input type="hidden" id="UpdateSubjectForm_ID" name="ID" required />
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-heading pull-right"><b>Information</b></div>
							<br />
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Code</span>
									  </div>
									  <input id = "updateSubjectForm_CODE" name = "CODE" type="text" class="form-control" placeholder="Subject Code" aria-describedby="sizing-addon2">
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Abbreviation</span>
									  </div>
									  <input id = "UpdateSubjectForm_NAMEABBR" name = "SUBJECTABBR" type="text" class="form-control" placeholder="Subject Abbreviation" aria-describedby="sizing-addon2">
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Title</span>
									  </div>
									  <input id = "UpdateSubjectForm_TITLE" name = "TITLE" type="text" class="form-control" placeholder="Subject Title" aria-describedby="sizing-addon2">
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Description</span>
									  </div>
									  <textarea id = "UpdateSubjectForm_DESCRIPTION" maxlength = "180" name = "DESCRIPTION" style = "padding-bottom: 150px;" type="text" class="form-control" placeholder="Description..." aria-describedby="sizing-addon2" required></textarea>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Total Credit</span>
									  </div>
									  <input id = "UpdateSubjectForm_TOTALCREDIT" name = "TOTALCREDIT" type="number" class="form-control" min="1" max = "5" placeholder="Subject's Total Credit" aria-describedby="sizing-addon2" required>
								</div>
								<br />								
								<div class="form-group">
								  <select class="form-control" name = "STATUS" id="UpdateSubjectForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="updateSubjectForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteSubjectForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Subject</h5>
			</div>
            <form id="deleteSubjectForm" method="post" action="library/form/frmDeleteSubjects.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteSubjectForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Subject Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Subject Code: </td>
                            <td id="deleteSubjectForm_CODE"></td>
                        </tr>
                        <tr>
                            <td>Subject Title ABBR: </td>
                            <td id="deleteSubjectForm_NAMEABBR"></td>
                        </tr>
                        <tr>
                            <td>Subject Title: </td>
                            <td id="deleteSubjectForm_NAME"></td>
                        </tr>
                        <tr>
                            <td>Description: </td>
                            <td id="deleteSubjectForm_DESCRIPTION"></td>
                        </tr>
                        <tr>
                            <td>Total Credit: </td>
                            <td id="deleteSubjectForm_TOTALCREDIT"></td>
                        </tr>
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteSubjectForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteSubjectForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteSubjectForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Subject Management</h1>
					<p class = "text-muted">This module would allow the user to update, add and delete Subjects.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addSubjectForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add a Subject</a>
						    <a class="dropdown-item" href="#importSubjectForm_MODAL" data-toggle="modal">Import Subjects</a>
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
				  <th scope="col">Subject Code</th>
				  <th scope="col">Subject ABBR</th>
				  <th scope="col">Subject Title</th>
				  <th scope="col">Description</th>
				  <th scope="col">Total Credit</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "subjectList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-subjects.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-subjects.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-subjects.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-subjects.php?search='+searchValue;
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
        subjectList: document.getElementById('subjectList')
    };

    var ASForm = {
    	form: document.getElementById('addSubjectForm'),
    	code: document.getElementById('addSubjectForm_CODE'),
    	name: document.getElementById('addSubjectForm_NAME'),
    	nameabbr: document.getElementById('addSubjectForm_ABBR'),
    	description: document.getElementById('addSubjectForm_DESCRIPTION'),
    	totalcredit: document.getElementById('addSubjectForm_TOTALCREDIT'),
    	submit: '#addSubjectForm_SUBMIT',
    	modal: '#addSubjectForm_MODAL'
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
    	form: document.getElementById('importSubjectForm'),
    	file: document.getElementById('importSubjectForm_FILE'),
    	modal: '#importSubjectForm_MODAL',
    	submit: '#importSubjectForm_SUBMIT'
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
		form: document.getElementById('updateSubjectForm'),
		code: document.getElementById('updateSubjectForm_CODE'),
		id: document.getElementById('UpdateSubjectForm_ID'),
		code: document.getElementById('updateSubjectForm_CODE'),
		nameabbr: document.getElementById('UpdateSubjectForm_NAMEABBR'),
		title: document.getElementById('UpdateSubjectForm_TITLE'),
		description: document.getElementById('UpdateSubjectForm_DESCRIPTION'),
		totalcredit: document.getElementById('UpdateSubjectForm_TOTALCREDIT'),
		status: document.getElementById('UpdateSubjectForm_STATUS'),
		modal: document.getElementById('updateSubjectForm_MODAL'),
		submit: '#updateSubjectForm_SUBMIT'
	};


	function updateSubject(id, code, name, nameabbr, description, totalcredit, status){
		document.getElementById('subject_SUBJECTTITLE_'+id).innerHTML = name;
		document.getElementById('subject_CODE_'+id).innerHTML = code;
		document.getElementById('subject_SUBJECTABBR_'+id).innerHTML = nameabbr;
		document.getElementById('subject_DESCRIPTION_'+id).innerHTML = description;
		document.getElementById('subject_TOTALCREDIT_'+id).innerHTML = totalcredit;
		document.getElementById('subject_STATUS_'+id).innerHTML = status;
	}

	function updateSubjectFill(id){
		var nameabbr = document.getElementById('subject_SUBJECTABBR_'+id).innerHTML;
		var code = document.getElementById('subject_CODE_'+id).innerHTML;
		var name = document.getElementById('subject_SUBJECTTITLE_'+id).innerHTML;
		var description = document.getElementById('subject_DESCRIPTION_'+id).innerHTML;
		var totalcredit = document.getElementById('subject_TOTALCREDIT_'+id).innerHTML;
		var status = document.getElementById('subject_STATUS_'+id).innerHTML;

		USForm.id.value = id;
		USForm.code.value = code;
		USForm.nameabbr.value = nameabbr;
		USForm.title.value = name;
		USForm.description.value = description;
		USForm.totalcredit.value = totalcredit

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
					updateSubject(GetSuccessMsg(server_message), USForm.code.value, USForm.title.value, USForm.nameabbr.value, USForm.description.value, USForm.totalcredit.value, USForm.status.options[USForm.status.selectedIndex].text);
					$('#updateSubjectForm_MODAL').modal('toggle');
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


	var DSForm = {
	    form: document.getElementById('deleteSubjectForm'),
	    modal: document.getElementById('deleteSubjectForm_MODAL'),
	    id: document.getElementById('deleteSubjectForm_ID'),
	    code: document.getElementById('deleteSubjectForm_CODE'),
	    name: document.getElementById('deleteSubjectForm_NAME'),
	    nameabbr: document.getElementById('deleteSubjectForm_NAMEABBR'),
	    description: document.getElementById('deleteSubjectForm_DESCRIPTION'),
	    totalcredit: document.getElementById('deleteSubjectForm_TOTALCREDIT'),
	    datecreated: document.getElementById('deleteSubjectForm_DATECREATED'),
	    status: document.getElementById('deleteSubjectForm_STATUS'),
	    submit: document.getElementById('deleteSubjectForm_SUBMIT')
	};

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
				deleteStudent(id);
				DSForm.form.reset();
				$(DSForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deleteStudent(id) {
        $('#subject_' + id).remove();
    }

    function openDeleteSubjectModal(id) {
        DSForm.id.value = id;
        DSForm.code.innerHTML = document.getElementById('subject_CODE_'+id).innerHTML;
        DSForm.name.innerHTML = document.getElementById('subject_SUBJECTABBR_'+id).innerHTML;
        DSForm.nameabbr.innerHTML = document.getElementById('subject_SUBJECTTITLE_'+id).innerHTML;
        DSForm.description.innerHTML = document.getElementById('subject_DESCRIPTION_'+id).innerHTML;
        DSForm.totalcredit.innerHTML = document.getElementById('subject_TOTALCREDIT_'+id).innerHTML;
        DSForm.status.innerHTML = document.getElementById('subject_STATUS_'+id).innerHTML;
        DSForm.datecreated.innerHTML = document.getElementById('subject_DATECREATED_'+id).innerHTML;
        $(DSForm.modal).modal('show');
    }

    function addSubjectList(id, scode, sname, snameabbr, sdescription, stotalcredit, datecreated, status)
    {
    	PageComponent.subjectList.innerHTML = PageComponent.subjectList.innerHTML +
    		'<thead>'+
			'<tr id = "subject_'+ id +'">'+
			'	<td scope = "col" id = "subject_CODE_' + id +'">' + scode + '</td>'+
			'	<td scope = "col" id = "subject_SUBJECTABBR_' + id +'">' + snameabbr + '</td>'+
			'	<td scope = "col" id = "subject_SUBJECTTITLE_' + id + '">' + sname + '</td>'+
			'	<td scope = "col" id = "subject_DESCRIPTION_' + id + '">' + sdescription + '</td>'+
			'	<td scope = "col" id = "subject_TOTALCREDIT_' + id + '">' + stotalcredit + '</td>'+
			'	<td scope = "col" id = "subject_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "subject_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="subject_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateSubjectForm_MODAL" data-toggle = "modal" onclick = "updateSubjectFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="subject_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteSubjectModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
				
				Subjects.idSubjects,
				Subjects.SCode,
				Subjects.SName,
				Subjects.SNameAbbr,
				Subjects.SDescription,
				Subjects.STotalCredit,
				Subjects.SStatus,
				Subjects.SDateCreated,
				ROW_NUMBER() OVER (ORDER BY Subjects.SName) AS "RowNumber"
				
				FROM Subjects
				
				WHERE (Subjects.SName LIKE ? OR Subjects.SCode LIKE ? OR Subjects.SNameAbbr LIKE ?)

				)
				SELECT * 
				FROM OrderedList 
				
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $search);
	$list_getResult->bindparam(4, $limit);
	$list_getResult->bindparam(5, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "subjects_TITLE_">No Subjects Found</td>'+
			'<tr>';

			$("#subjectList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{

			$result_ID = htmlspecialchars($list_row['idSubjects']);
			$result_CODE = htmlspecialchars($list_row['SCode']);
			$result_SUBJECTNAME = htmlspecialchars($list_row['SName']);
			$result_SUBJECTNAMEABBR = htmlspecialchars($list_row['SNameAbbr']);
			$result_SUBJECTDESCRIPTION = htmlspecialchars($list_row['SDescription']);
			$result_SUBJECTOTALCREDIT = htmlspecialchars($list_row['STotalCredit']);
			$result_DATECREATED = htmlspecialchars($list_row['SDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['SStatus']);
			

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addSubjectList("<?php echo $result_ID; ?>","<?php echo $result_CODE; ?>","<?php echo $result_SUBJECTNAME; ?>","<?php echo $result_SUBJECTNAMEABBR ?>","<?php echo $result_SUBJECTDESCRIPTION; ?>","<?php echo $result_SUBJECTOTALCREDIT ?>" ,"<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS; ?>");		
		<?php 
		}			
	}
	?>


</script>	