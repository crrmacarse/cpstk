<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$getyear = isset($_GET['year']) ? $_GET['year'] : '';
	$getsemester = isset($_GET['semester']) ? $_GET['semester'] : '';
	$getstubcode = isset($_GET['stubcode']) ? $_GET['stubcode'] : '';

	$getCount = $db->connection->prepare('SELECT COUNT(*) FROM GradeFinal INNER JOIN Student ON Student.idStudent = STIDNUM WHERE (Student.SUsername LIKE ? OR Student.SLastName LIKE ? OR Student.SFirstName LIKE ? OR Student.SMiddleName = ?
				) AND STUBCODE = ? AND ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ?');
	$getCount->bindparam(1, $search);
	$getCount->bindparam(2, $search);
	$getCount->bindparam(3, $search);
	$getCount->bindparam(4, $search);
	$getCount->bindparam(5, $getstubcode);
	$getCount->bindparam(6, $getyear);
	$getCount->bindparam(7, $getsemester);
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

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Manage Grades</h1>
					<p class = "text-muted">Grade management handles historical data of Grades</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "view-schedule.php?year=<?php echo $getyear ?>&semester=<?php echo $getsemester ?>" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
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
				  <th scope="col">Grade</th>
				  <th scope="col">Remarks</th>
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
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-grades.php?trimmedsearch=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-grades.php?trimmedsearch=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-grades.php?trimmedsearch=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-grades.php?year=<?php echo $getyear ?>&semester=<?php echo $getsemester ?>&stubcode=<?php echo $getstubcode ?>&search='+searchValue;
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


    function addClassScheduleData(id, lastname, firstname, middlename, grade, remarks)
    {
    	PageComponent.classScheduleDataList.innerHTML = PageComponent.classScheduleDataList.innerHTML +
    		'<thead>'+
			'<tr id = "classScheduleData_'+ id +'">'+
			'	<td scope = "col" id = "classScheduleData_STUDENTID_' + id +'">' + id + '</td>'+
			'	<td scope = "col" id = "classScheduleData_STUDENTNAME_' + id +'">' + firstname + ' ' + middlename + ' ' + lastname + '</td>'+
			'	<td scope = "col" id = "classScheduleData_GRADE_' + id +'">' + grade + '</td>'+
			'	<td scope = "col" id = "classScheduleData_REMARKS_' + id +'">' + remarks + '</td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
			 
				STIDNUM, 
				SLastName,
				SMiddleName,
				SFirstName,
				COURSE, 
				STYEAR, 
				STGRADE, 
				STATTEND, 
				GRADERMK, 
				ROW_NUMBER() OVER (ORDER BY STIDNUM) AS "RowNumber" 

				FROM GradeFinal
				
				INNER JOIN Student
				ON Student.SUsername = GradeFinal.STIDNUM

				WHERE (Student.SUsername LIKE ? OR Student.SLastName LIKE ? OR Student.SFirstName LIKE ? OR Student.SMiddleName = ?
				) AND STUBCODE = ? AND ACADEMICSCHOOLYEAR = ? AND ACADEMICSEMESTERID = ?
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
	$list_getResult->bindparam(6, $getyear);
	$list_getResult->bindparam(7, $getsemester);
	$list_getResult->bindparam(8, $limit);
	$list_getResult->bindparam(9, $offset);
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
			$result_ID = htmlspecialchars($list_row['STIDNUM']);
			$result_LASTNAME = htmlspecialchars($list_row['SLastName']);
			$result_FIRSTNAME = htmlspecialchars($list_row['SFirstName']);
			$result_MIDDLENAME = htmlspecialchars($list_row['SMiddleName']);
			$result_GRADE = htmlspecialchars($list_row['STGRADE']);
			$result_REMARKS = htmlspecialchars($list_row['GRADERMK']);		

	?>

	addClassScheduleData("<?php echo $result_ID ?>","<?php echo $result_LASTNAME ?>","<?php echo $result_FIRSTNAME ?>","<?php echo $result_MIDDLENAME ?>","<?php echo $result_GRADE ?>","<?php echo $result_REMARKS ?>");

		<?php 
		}			
	}
	?>


</script>	