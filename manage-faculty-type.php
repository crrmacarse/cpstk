<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->query('SELECT COUNT(*) FROM FacultyType')->fetchColumn(); 
	$total_page = ceil($total_count/10);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = ($page * 10) + 10;
	$limit =  ($page * 10) - 10;


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

	<!-- Add Faculty Type -->

	<div class="modal fade" id="addFacultyTypeForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Faculty Type Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add a Faculty Type</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addFacultyTypeForm" class="form-horizontal" action="library/form/frmAddFacultyType.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Faculty Type Name</span>
									  </div>
									  <input id = "addFacultyTypeForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Faculty Type Name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addFacultyTypeForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>
	
	<!-- Update Faculty Type -->

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

	<!-- Delete Faculty Type -->

	<div class="modal fade" id="deleteFacultyTypeForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Faculty Type</h5>
			</div>
            <form id="deleteFacultyTypeForm" method="post" action="library/form/frmDeleteFacultyType.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteFacultyTypeForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Faculty Type Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Faculty Type Name: </td>
                            <td id="deleteFacultyTypeForm_NAME"></td>
                        </tr>
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteFacultyTypeForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteFacultyTypeForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteFacultyTypeForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Faculty Type Management</h1>
					<p class = "text-muted">Manages Faculty Type that will use the Kiosk.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addFacultyTypeForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add a Faculty Type</a>
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
				  <th scope="col">Faculty Type Name</th>
				  <th scope="col">Status</th>
	  			  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "facultyTypeList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-faculty-type.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-faculty-type.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-faculty-type.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-faculty-type.php?search='+searchValue;
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
        facultyTypeList: document.getElementById('facultyTypeList')
    };

    var AFTForm = {
    	form: document.getElementById('addFacultyTypeForm'),
    	name: document.getElementById('addFacultyTypeForm_NAME'),
    	modal: document.getElementById('addFacultyTypeForm_MODAL'),
    	modal: '#addFacultyTypeForm_MODAL',
    	submit: '#addFacultyTypeForm_SUBMIT'
    }

	AFTForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(AFTForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(AFTForm.submit).button('reset');
				var server_message = data.trim();	
				console.log("server message" + server_message);
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(AFTForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
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

	var DFTForm = {
		form: document.getElementById('deleteFacultyTypeForm'),
		id: document.getElementById('deleteFacultyTypeForm_ID'),
		name: document.getElementById('deleteFacultyTypeForm_NAME'),
		datecreated: document.getElementById('deleteFacultyTypeForm_DATECREATED'),
		status: document.getElementById('deleteFacultyTypeForm_STATUS'),
		modal: document.getElementById('deleteFacultyTypeForm_MODAL'),
		submit:	'#deleteFacultyTypeForm_SUBMIT'
	}

	function deleteFacultyType(id) {
        $('#facultytype_' + id).remove();
    }

    function openDeleteFacultyTypeModal(id) {
    	DFTForm.id.value = id;
    	DFTForm.name.innerHTML = document.getElementById('facultytype_NAME_'+id).innerHTML;
    	DFTForm.datecreated.innerHTML = document.getElementById('facultytype_DATECREATED_'+id).innerHTML;
    	DFTForm.status.innerHTML = document.getElementById('facultytype_STATUS_'+id).innerHTML;
       $(DFTForm.modal).modal('show');
    }

	$(DFTForm.form).on('submit', function (e) {
        var id = DFTForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DFTForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DFTForm.submit).button('reset');
				deleteFacultyType(id);
				DFTForm.form.reset();
				$(DFTForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	
    function addFacultyList(id, ftname, datecreated, status)
    {
    	PageComponent.facultyTypeList.innerHTML = PageComponent.facultyTypeList.innerHTML +
    		'<thead>'+
			'<tr id = "facultytype_'+ id +'">'+
			'	<td scope = "col" id = "facultytype_NAME_' + id +'">' + ftname + '</td>'+
			'	<td scope = "col" id = "facultytype_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "facultytype_STATUS_' + id + '">' + status + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="facultytype_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateFacultyTypeForm_MODAL" data-toggle = "modal" onclick = "updateFacultyTypeFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="facultytype_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteFacultyTypeModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				
				SELECT 

				FacultyType.idFacultyType,
				FacultyType.FTName,
				FacultyType.FTDateCreated,
				FacultyType.FTStatus,
				ROW_NUMBER() OVER (ORDER BY FacultyType.FTName) AS "RowNumber"
				
				FROM FacultyType
				
				WHERE FacultyType.FTName LIKE ?
				)
				SELECT * 
				FROM OrderedList 
				
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $offset);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "facultytype_TITLE_">No Faculty Type Found</td>'+
			'<tr>';

			$("#facultyTypeList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{

			$result_ID = htmlspecialchars($list_row['idFacultyType']);
			$result_FACULTYTYPENAME = htmlspecialchars($list_row['FTName']);
			$result_DATECREATED = htmlspecialchars($list_row['FTDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['FTStatus']);		
		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addFacultyList("<?php echo $result_ID ?>","<?php echo $result_FACULTYTYPENAME ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	