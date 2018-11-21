<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	$getcollege = isset($_GET['college']) ? $_GET['college'] : '';
	

	$list_getCollege = $db->connection->prepare('SELECT College.CName FROM College WHERE College.idCollege = ?');
	$list_getCollege->bindparam(1, $getcollege);
	$list_getCollege->execute();
	$college = $list_getCollege->fetchColumn();
	$limit = 10;

	$getCount = $db->connection->prepare('SELECT COUNT(*) FROM Department WHERE Department.idCollege = ?');
	$getCount->bindparam(1,$getcollege);
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

<body>

	<div class="modal fade" id="addDepartmentForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add Department Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add Department for <?php echo $college ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addDepartmentForm" class="form-horizontal" action="library/form/frmAddDepartment.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <input id = "addDepartmentForm_COLLEGE" type = "text" name = "COLLEGE" value = "<?php echo  $getcollege ?>" hidden />
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Department Name Abbr</span>
									  </div>
									  <input id = "addDepartmentForm_NAMEABBR" name = "NAMEABBR" type="input" class="form-control" placeholder="Department Abbreviation" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Department Name</span>
									  </div>
									  <input id = "addDepartmentForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Department Name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="form-group">
								    <select name = "BUILDING" class="form-control" id="addDepartmentForm_BUILDING" required>
								      <option selected = "true" disabled value = "">Building Located</option>
								    <?php
								    	$sql = "SELECT * FROM Landmark WHERE Status = 1 AND LName <> '' ORDER BY LName";
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
								<br />
							</div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addDepartmentForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteDepartmentForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete Department</h5>
				</div>
	            <form id="deleteDepartmentForm" method="post" action="library/form/frmDeleteDepartment.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteDepartmentForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>Department Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td>Department Abbreviation: </td>
	                            <td id="deleteDepartmentForm_NAMEABBR"></td>
	                        </tr>
	                        <tr>
	                            <td>Department Name: </td>
	                            <td id="deleteDepartmentForm_NAME"></td>
	                        </tr>
	                        <tr>
	                            <td>College: </td>
	                            <td id="deleteDepartmentForm_COLLEGE" class = "font-italic"></td>
	                        </tr>
	                        <tr>
	                            <td>Department Building: </td>
	                            <td id="deleteDepartmentForm_BUILDING"></td>
	                        </tr>
	                       	<tr>
	                            <td>Date Created: </td>
	                            <td id="deleteDepartmentForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteDepartmentForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteDepartmentForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
	    	</div>
		</div>

	
	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Department Management for <?php echo $college ?></h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-college.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addDepartmentForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Department</a>
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
				  <th scope="col">Department ABBR</th>
				  <th scope="col">Department Name</th>
				  <th scope="col">Department Building</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "departmentList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-department.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-department.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-department.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-department.php?college=<?php echo $getcollege ?>&search='+searchValue;
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
        departmentList: document.getElementById('departmentList')
    };


    var ADForm = {
    	form: document.getElementById('addDepartmentForm'),
    	name: document.getElementById('addDepartmentForm_NAME'),
    	nameabbr: document.getElementById('addDepartmentForm_NAMEABBR'),
    	college: document.getElementById('addDepartmentForm_COLLEGE'),
    	building: document.getElementById('addDepartmentForm_BUILDING'),
    	modal: '#addDepartmentForm_MODAL',
    	submit: '#addDepartmentForm_SUBMIT'
    };

	ADForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ADForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ADForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ADForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ADForm.form.reset();
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

	
	var DDForm = {
		form: document.getElementById('deleteDepartmentForm'),
		modal: document.getElementById('deleteDepartmentForm_MODAL'),
		id: document.getElementById('deleteDepartmentForm_ID'),
		name: document.getElementById('deleteDepartmentForm_NAME'),
	    nameabbr: document.getElementById('deleteDepartmentForm_NAMEABBR'),
	    college: document.getElementById('deleteDepartmentForm_COLLEGE'),
	    building: document.getElementById('deleteDepartmentForm_BUILDING'),
	    datecreated: document.getElementById('deleteDepartmentForm_DATECREATED'),
	    status: document.getElementById('deleteDepartmentForm_STATUS'),
		submit: document.getElementById('deleteDepartmentForm_SUBMIT')
	   }

	$(DDForm.form).on('submit', function (e) {
        var id = DDForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DDForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(DDForm.submit).button('reset');
				deleteDepartment(id);
				DDForm.form.reset();
				$(DDForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	

	function deleteDepartment(id){
		$('#department_'+id).remove();
	}

    function openDeleteDepartmentModal(id) {
        DDForm.id.value = id;
        DDForm.name.innerHTML = document.getElementById('department_NAME_'+id).innerHTML;
        DDForm.nameabbr.innerHTML = document.getElementById('department_NAMEABBR_'+id).innerHTML;
        DDForm.college.innerHTML = document.getElementById('department_COLLEGE_'+id).innerHTML;
        DDForm.building.innerHTML = document.getElementById('department_DEPARTMENTBUILDING_'+id).innerHTML;
        DDForm.datecreated.innerHTML = document.getElementById('department_DATECREATED_'+id).innerHTML;
        DDForm.status.innerHTML = document.getElementById('department_STATUS_'+id).innerHTML;
        $(DDForm.modal).modal('show');
    }

    function addDepartmentList(id, nameabbr, name, departmentbuilding, college, datecreated, status)
    {
    	PageComponent.departmentList.innerHTML = PageComponent.departmentList.innerHTML +
    		'<thead>'+
			'<tr id = "department_'+ id +'">'+
			'	<td scope = "col" id = "department_NAMEABBR_' + id +'">' + nameabbr + '</td>'+
			'	<td scope = "col" id = "department_NAME_' + id +'">' + name + '</td>'+
			'	<td scope = "col" id = "department_DEPARTMENTBUILDING_' + id +'">' + departmentbuilding + '</td>'+
			'	<td scope = "col" id = "department_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "department_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "department_COLLEGE_' + id + '" hidden>' + college + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="department_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateDepartmentForm_MODAL" data-toggle = "modal" onclick = "updateDepartmentFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="department_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteDepartmentModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
			 
				Department.idDepartment,
				Department.idCollege,
				College.CName,
				Landmark.LName,
				Department.DName,
				Department.DNameAbbr,
				Department.DDateCreated,
				Department.DStatus,			
				ROW_NUMBER() OVER (ORDER BY Department.DName) AS "RowNumber"
				
				
				FROM Department
				
				INNER JOIN Landmark
				ON Landmark.idLandmark = Department.idLandmark			
				INNER JOIN College
				ON College.idCollege = Department.idCollege

				WHERE (Department.DName LIKE ? OR Department.DNameAbbr LIKE ?) AND Department.idCollege = ?
				)

				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $getcollege);
	$list_getResult->bindparam(4, $offset);
	$list_getResult->bindparam(5, $limit);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "department_TITLE_">No Department Found</td>'+
			'<tr>';

			$("#departmentList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idDepartment']);
			$result_NAME = htmlspecialchars($list_row['DName']);
			$result_NAMEABBR = htmlspecialchars($list_row['DNameAbbr']);
			$result_DEPARTMENTBUILDING = htmlspecialchars($list_row['LName']);
			$result_COLLEGE = htmlspecialchars($list_row['CName']);
			$result_DATECREATED = htmlspecialchars($list_row['DDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['DStatus']);
		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addDepartmentList("<?php echo $result_ID ?>","<?php echo $result_NAMEABBR ?>","<?php echo $result_NAME ?>","<?php echo $result_DEPARTMENTBUILDING ?>","<?php echo $result_COLLEGE ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	