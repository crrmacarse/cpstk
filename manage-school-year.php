<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	
	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->query('SELECT COUNT(*) FROM SchoolYear')->fetchColumn(); 
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

	<div class="modal fade" id="addSchoolYearForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add School Year Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="addModalTitle">Add School Year</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addSchoolYearForm" class="form-horizontal" action="library/form/frmAddSchoolYear.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">School Year</span>
									  </div>
									  <input id = "addSchoolYearForm_SCHOOLYEAR" name = "SCHOOLYEAR" type="text" class="form-control" placeholder="XXXX - XXXX" aria-describedby="sizing-addon2" maxlength="11" required>
								</div>
								<br />
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addSchoolYearForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="deleteSchoolYearForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete College</h5>
				</div>
	            <form id="deleteSchoolYearForm" method="post" action="library/form/frmDeleteSchoolYear.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteSchoolYearForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>School Year Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td>School Year: </td>
	                            <td id="deleteSchoolYearForm_SCHOOLYEAR"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created: </td>
	                            <td id="deleteSchoolYearForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteSchoolYearForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteSchoolYearForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">School Year Management</h1>
					<p class = "text-muted">Mange School Year, User can Add a New School Year</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addSchoolYearForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add School Year</a>
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
				  <th scope="col">School Year</th>
  				  <th scope="col">Period</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "schoolYearList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-school-year.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-school-year.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-school-year.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-school-year.php?search='+searchValue;
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
        schoolYearList: document.getElementById('schoolYearList')
    };


    function viewPeriod(id){
    	window.location.href='manage-period.php?schoolyear='+id;
    }

    var ASYForm = {
    	form: document.getElementById('addSchoolYearForm'),
    	scoolyear: document.getElementById('addSchoolYearForm_SCHOOLYEAR'),
    	modal: '#addSchoolYearForm_MODAL',
    	submit: '#addSchoolYearForm_SUBMIT'
    }

	ASYForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ASYForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ASYForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ASYForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ASYForm.form.reset();
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

	var DSYForm = {
		form: document.getElementById('deleteSchoolYearForm'),
		id: document.getElementById('deleteSchoolYearForm_ID'),
		schoolyear: document.getElementById('deleteSchoolYearForm_SCHOOLYEAR'),
		modal: document.getElementById('deleteSchoolYearForm_MODAL'),
		datecreated: document.getElementById('deleteSchoolYearForm_DATECREATED'),
		status: document.getElementById('deleteSchoolYearForm_STATUS')
	};


	$(DSYForm.form).on('submit', function (e) {
        var id = DSYForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DSYForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(DSYForm.submit).button('reset');
				deleteSchoolYear(id);
				DSYForm.form.reset();
				$(DSYForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deleteSchoolYear(id) {
        $('#schoolyear_' + id).remove();
    }

    function openDeleteSchoolYearModal(id) {
        DSYForm.id.value = id;
        DSYForm.schoolyear.innerHTML = document.getElementById('schoolyear_SYYEAR_'+id).innerHTML;
        DSYForm.datecreated.innerHTML = document.getElementById('schoolyear_DATECREATED_'+id).innerHTML;
        DSYForm.status.innerHTML = document.getElementById('schoolyear_STATUS_'+id).innerHTML;
        $(DSYForm.modal).modal('show');
    }

    function addSchoolYearList(id, syyear, datecreated, status)
    {
    	PageComponent.schoolYearList.innerHTML = PageComponent.schoolYearList.innerHTML +
    		'<thead>'+
			'<tr id = "schoolyear_'+ id +'">'+
			'	<td scope = "col" id = "schoolyear_SYYEAR_' + id +'">' + syyear + '</td>'+
			'	<td></button><button id="schoolyear_VIEWPERIOD_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewPeriod(\'' + id + '\')" role = "button" dal(' + id + ')">View Periods</button></td>'+
			'	<td scope = "col" id = "schoolyear_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "schoolyear_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="schoolyear_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteSchoolYearModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			// ' 	<td><div class = "btn-group" role = "group"><button id="schoolyear_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateSchoolYearForm_MODAL" data-toggle = "modal" onclick = "updateSchoolYearFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="schoolyear_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteSchoolYearModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT
			
				SchoolYear.idSchoolYear,
				SchoolYear.SYYear,
				SchoolYear.SYDateCreated,
				SchoolYear.SYStatus,
				ROW_NUMBER() OVER (ORDER BY SchoolYear.SYYear) AS "RowNumber"
			
				
				FROM SchoolYear	
		
				WHERE SchoolYear.SYYear LIKE ?
				)

				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $limit);
	$list_getResult->bindparam(3, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "room_TITLE_">No Room Found</td>'+
			'<tr>';

			$("#roomList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idSchoolYear']);
			$result_SYYEAR = htmlspecialchars($list_row['SYYear']);
			$result_DATECREATED = htmlspecialchars($list_row['SYDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['SYStatus']);
		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addSchoolYearList("<?php echo $result_ID ?>","<?php echo $result_SYYEAR ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	