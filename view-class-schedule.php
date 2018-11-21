<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	$getstubcode = isset($_GET['id']) ? $_GET['id'] : '';
	
	$getCount = $db->connection->prepare('SELECT COUNT(*) FROM ClassScheduleFinal WHERE STUBCODE = ?');
	$getCount->bindparam(1,$getstubcode);
	$getCount->execute();
	$total_count = $getCount->fetchColumn(); 
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

	<div class="modal fade" id="addClassScheduleDataForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add Class Schedule Data Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add Class Data for <?php echo $classschedule ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addClassScheduleDataForm" class="form-horizontal" action="library/form/frmAddClassScheduleData.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <input id = "addClassScheduleDataForm_ClassScheduleControl" type = "text" name = "CLASSSCHEDCONTROL" value = "<?php echo  $getclassschedule ?>" hidden />
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
								    <select name = "STUDENT" class="form-control" id="addClassScheduleDataForm_STUDENT" required>
								      <option selected = "true" disabled value = "">Select a Student</option>
								    <?php
								    	$sql = "SELECT * FROM Student WHERE Student.SStatus = 1 ORDER BY SLastName";
										$getResult = $db->connection->prepare($sql);
										$getResult->execute();
										$count = $getResult->rowCount();
										$result = $getResult->fetchAll();
										foreach($result As $row) {
											?>
											<option value="<?php echo $row["idStudent"]; ?>" id="Student_<?php echo $row["idStudent"]; ?>"><?php echo strtoupper($row["SLastName"]) . ', ' . $row['SFirstName'] . ' ' . $row['SMiddleName'] ?></option>
											<?php
										}
								    ?>
								    </select>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Grade</span>
									  </div>
									  <input id = "addClassScheduleDataForm_GRADE" name = "GRADE" type="number" class="form-control" placeholder="0.0" aria-describedby="sizing-addon2" min = "0" max = "5" step = ".25" value = "0">
								</div>
							</div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addClassScheduleDataForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="updateClassScheduleDataForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Class Schedule Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="updateClassScheduleDataForm_INFORMATION"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "updateClassScheduleDataForm" class="form-horizontal" action="library/form/frmUpdateClassScheduleData.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
					<input type="hidden" id="updateClassScheduleDataForm_ID" name="ID" required />
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Grade</span>
									  </div>
									  <input id = "updateClassScheduleDataForm_GRADE" name = "GRADE" type="number" class="form-control" placeholder="Grade" aria-describedby="sizing-addon2" min = "0" max = "5" required>
								</div>
								<br />
								<div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateClassScheduleDataForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
					</div>
			      </div>
			      <div class="modal-footer">
			        <input type="submit" id = "updateClassScheduleDataForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Update.." value = "Update">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			      </div>
		  </form>
		    </div>
		  </div>
		</div>
	</div>

	<div class="modal fade" id="frmImportClassScheduleData_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Import Class Student Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form class="form-horizontal" id = "frmImportClassScheduleData_FORM" action="library/form/frmImportClassScheduleData.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <input id = "frmImportClassScheduleData_CSID" type = "text" name = "CSCID" value = "<?php echo  $getclassschedule ?>" hidden />
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input id = "frmImportClassScheduleData_FILE" type="file" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input type="submit" id = "frmImportClassScheduleData_SUBMIT" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Class Schedule Data for <?php echo $getstubcode ?></h1>
					<p class = "text-muted">Manages Students who are enrolled in this Subject.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-class-schedule.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
				<th scope="col">Student ID</th>
				<th scope="col">Student Fullname</th>
<!-- 				  <th scope="col">Actions</th> -->
				</tr>
			  </thead>
			  <tbody id = "classScheduleDataList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="view-class-schedule.php?id=<?php echo $getstubcode; ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="view-class-schedule.php?id=<?php echo $getstubcode; ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="view-class-schedule.php?id=<?php echo $getstubcode; ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='view-class-schedule.php?id=<?php echo $getstubcode ?>&search='+searchValue;
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
        classScheduleDataList: document.getElementById('classScheduleDataList')
    };

    function addClassScheduleData(id, studid, firstname, middlename, lastname)
    {
    	PageComponent.classScheduleDataList.innerHTML = PageComponent.classScheduleDataList.innerHTML +
    		'<thead>'+
			'<tr id = "classScheduleData_'+ id +'">'+
			'	<td scope = "col" id = "classScheduleData_STUDENTID_' + id +'">' + studid + '</td>'+
			'	<td scope = "col" id = "classScheduleData_STUDENTNAME_' + id +'">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 	
    						
    				idClassScheduleFinal,	
    				STUDID,
    				SFirstName,
    				SMiddleName,
    				SLastName,
    				ROW_NUMBER() OVER (ORDER BY STUDID) AS "RowNumber"
				
    			FROM 
    
    			ClassScheduleFinal

    			INNER JOIN Student
    			ON STUDENT.SUsername = ClassScheduleFinal.STUDID
    							
				WHERE (STUDID LIKE ? OR Student.SLastName LIKE ? OR Student.SFirstName LIKE ? OR Student.SMiddleName = ?) AND STUBCODE = ?
				)
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $search);
	$list_getResult->bindparam(4, $search);
	$list_getResult->bindparam(5, $getstubcode);
	$list_getResult->bindparam(6, $limit);
	$list_getResult->bindparam(7, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "classScheduleData_TITLE_">No Class Schedule Data Found</td>'+
			'<tr>';

			$("#classScheduleDataList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idClassScheduleFinal']);
			$result_LASTNAME = htmlspecialchars($list_row['SLastName']);
			$result_FIRSTNAME = htmlspecialchars($list_row['SFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['SMiddleName']);
			$result_STUDENTID = htmlspecialchars($list_row['STUDID']);

	?>

	addClassScheduleData("<?php echo $result_ID ?>","<?php echo $result_STUDENTID ?>","<?php echo $result_FIRSTNAME ?>","<?php echo $result_MIDDLENAME ?>","<?php echo $result_LASTNAME ?>");

		<?php 
		}			
	}
	?>


</script>	