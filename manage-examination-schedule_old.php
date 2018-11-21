<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$period = isset($_GET['period']) ? '%'.$_GET['period'].'%' : '%%';
	$term = isset($_GET['term']) ? '%'.$_GET['term'].'%' : '%%';
	$trimmedperiod = str_replace('%', '', $period);
	$trimmedterm = str_replace('%', '', $term);

	$total_count = $db->connection->query('SELECT COUNT(*) FROM ExamSchedule')->fetchColumn(); 
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

	<div class="modal fade" id="addExamSchedForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add Exam Schedule Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="addModalTitle">Add Examination Schedule</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addExamSchedForm" class="form-horizontal" action="library/form/frmAddExamSched.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Subject Title</span>
									  </div>
									  <input id = "addSubjectForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Subject Title" aria-describedby="sizing-addon2" required>
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
	
	<div class="modal fade" id="importExamScheduleForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="importExamScheduleForm_TITLE">Import Examination Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "importExamScheduleForm" class="form-horizontal" action="library/form/frmImportExamSchedule.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input type="file" class="custom-file-input" name = "FILE" id = "importExamScheduleForm_FILE" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input id = "importExamScheduleForm_SUBMIT" type="submit" class="btn btn-success" name = "IMPORT" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteExamScheduleForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Class Schedule</h5>
			</div>
            <form id="deleteExamScheduleForm" method="post" action="library/form/frmDeleteExamSchedule.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteExamScheduleForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Exam Schedule Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><b>School Year Offered: </b></td>
                            <td id="deleteExamScheduleForm_SCHOOLYEARTERM"></td>
                        </tr>
                        <tr>
                            <td>Exam Date Offered: </td>
                            <td id="deleteExamScheduleForm_EXAMDAYTIME"></td>
                        </tr>
                        <tr>
                            <td>Stubcode: </td>
                            <td id="deleteExamScheduleForm_STUBCODE"></td>
                        </tr>
                        <tr>
                            <td>Subject: </td>
                            <td id="deleteExamScheduleForm_SUBJECT"></td>
                        </tr>
                        <tr>
                            <td>Room: </td>
                            <td id="deleteExamScheduleForm_EXAMROOM"></td>
                        </tr>
                        <tr>
                            <td>Assigned Faculty: </td>
                            <td id="deleteExamScheduleForm_SUBJECTTEACHER"></td>
                        </tr>
                        <tr>
                            <td>Schedule: </td>
                            <td id="deleteExamScheduleForm_EXAMPROCTOR"></td>
                        </tr>                                        
                        <tr>
                            <td>Status:</td>
                            <td id="deleteExamScheduleForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteExamScheduleForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>
	
	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Exam Schedule Management</h1>
					<p class = "text-muted">Manages Exam Schedule for Students</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <!-- <a class="dropdown-item" href="#">New Exam Schedule</a> -->
						    <a class="dropdown-item" href="#importExamScheduleForm_MODAL" data-toggle="modal">Import Exam Data</a>
						    <a class="dropdown-item" href="mailto:customercare@coffeebreak.ph?Subject=Coffeebreak%20Careers" target="_top">Report</a>
						  </div>
						</span>
					</div>
				</div>	
			</div>

			<div class = "row">
			<div class = "col-lg-12">
						<hr class="my-4 float-right" width="65%">
			</div>
			<div class = "col-lg-6">
			</div>
			<div class = "col-lg-6">
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
				   <select class="custom-select" id="selectedTerm" aria-label="Example select with button addon">
				    <option selected = "true" disabled value = "">Select a Term</option>
				    <?php
				    	$sql = "SELECT Term.idTerm, Term.TName FROM Term WHERE TStatus = 'True'";
						$getResult = $db->connection->prepare($sql);
						$getResult->execute();
						$count = $getResult->rowCount();
						$result = $getResult->fetchAll();
						foreach($result As $row) {
							?>
							   <option value = "<?php echo $row["idTerm"]; ?>"><?php echo $row["TName"] ?></option>
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
					  <th scope="col">Stubcode</th>
					  <th scope="col">Subject</th>
					  <th scope="col">Exam Day</th>
					  <th scope="col">Exam Time</th>
					  <th scope="col">Exam Room</th>
					  <th scope="col">Subject Teacher</th>
					  <th scope="col">Exam Proctor</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>					  
					</tr>
				  </thead>
				  <tbody id = "examSchedList">
				  </tbody>
				</table>	
			</div>

		<div class = "row top-buffer justify-content-md-center">
			 <div class="col-md-auto top-buffer">
		<nav aria-label="Page navigation example">
			  <ul class="pagination justify-content-center">
			    	<li class="page-item">
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-examination-schedule.php?period=<?php echo $trimmedperiod; ?>&term=<?php echo $trimmedterm ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-examination-schedule.php?period=<?php echo $trimmedperiod; ?>&term=<?php echo $trimmedterm ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-examination-schedule.php?period=<?php echo $trimmedperiod; ?>&term=<?php echo $trimmedterm ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
		$("#selectedTerm").val('<?php echo $trimmedterm ?>');
		$("#examSearch").click(function(){
			var period = $("#selectedPeriod").val() || '';
			var term = $("#selectedTerm").val() || '';
			window.location.href='manage-examination-schedule.php?period='+period+'&term='+term;
		});
			$("#searchBar").click(function(){
			$("#searchBar").val("");
		})
		
	});

	
	var PageComponent = {
        examSchedList: document.getElementById('examSchedList')
    };

    var IESForm =
    {
    	form: document.getElementById('importExamScheduleForm'),
    	file: document.getElementById('importExamScheduleForm_FILE'),
    	modal: '#importExamScheduleForm_MODAL',
    	submit: '#importExamScheduleForm_SUBMIT'
    }

    IESForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(IESForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(IESForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(IESForm.modal).modal('hide');
						alert('Imported Succesfully');
						window.location.reload(false); 						
						IESForm.form.reset();
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

	var DESForm = {
	    form: document.getElementById('deleteExamScheduleForm'),
	    modal: document.getElementById('deleteExamScheduleForm_MODAL'),
	    id: document.getElementById('deleteExamScheduleForm_ID'),
	    schoolyearterm: document.getElementById('deleteExamScheduleForm_SCHOOLYEARTERM'),
	    daytime: document.getElementById('deleteExamScheduleForm_EXAMDAYTIME'),
	    stubcode: document.getElementById('deleteExamScheduleForm_STUBCODE'),
	    subject: document.getElementById('deleteExamScheduleForm_SUBJECT'),
	    room: document.getElementById('deleteExamScheduleForm_EXAMROOM'),
	    faculty: document.getElementById('deleteExamScheduleForm_SUBJECTTEACHER'),
	    proctor: document.getElementById('deleteExamScheduleForm_EXAMPROCTOR'),
	    datecreated: document.getElementById('deleteExamScheduleForm_DATECREATED'),
	    status: document.getElementById('deleteExamScheduleForm_STATUS'),
	    submit: document.getElementById('deleteExamScheduleForm_SUBMIT')
	   };

	function deleteExamSchedule(id) {
        $('#exam_' + id).remove();
    }

    function openDeleteExamScheduleModal(id) {
        DESForm.id.value = id;
        DESForm.daytime.innerHTML = document.getElementById('exam_DAYTIME_'+id).innerHTML;
        DESForm.schoolyearterm.innerHTML = document.getElementById('exam_SCHOOLYEARTERM_'+id).innerHTML;
        DESForm.stubcode.innerHTML = document.getElementById('exam_STUBCODE_'+id).innerHTML;
        DESForm.subject.innerHTML = document.getElementById('exam_SUBJECTNAME_'+id).innerHTML;
        DESForm.faculty.innerHTML = document.getElementById('exam_TEACHER_'+id).innerHTML;
        DESForm.proctor.innerHTML = document.getElementById('exam_PROCTOR_'+id).innerHTML;
        DESForm.room.innerHTML = document.getElementById('exam_ROOM_'+id).innerHTML;
        DESForm.status.innerHTML = document.getElementById('exam_STATUS_' + id).innerHTML;
        $(DESForm.modal).modal('show');
    }


	$(DESForm.form).on('submit', function (e) {
        var id = DESForm.id.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DESForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DESForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DESForm.submit).button('reset');
					deleteExamSchedule(id);
					DESForm.form.reset();
					$(DESForm.modal).modal('hide');
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



    function addExamSchedList(id, stubcode, idperiod, idterm, subjectcode, subjectname, exxamroom, examdate, examstart, examend, teachername, proctorname, datecreated, status, schoolyear, periodname)
	{
		PageComponent.examSchedList.innerHTML = PageComponent.examSchedList.innerHTML +
			'<thead>'+
			'<tr id = "exam_'+ id +'">'+
			'	<td scope = "col" id = "exam_STUBCODE_' + id +'">' + stubcode + '</td>'+
			'	<td scope = "col" id = "exam_SUBJECTNAME_' + id + '">' + ' [ ' + subjectcode + ' ] ' + subjectname + '</td>'+
			'	<td scope = "col" id = "exam_EXAMDATE_' + id + '">' + examdate + '</td>'+
			'	<td scope = "col" id = "exam_TIME_' + id + '">' + examstart + ' - ' + examend + '</td>'+
			'	<td scope = "col" id = "exam_ROOM_' + id + '">'+ exxamroom +'</td>'+
			'	<td scope = "col" id = "exam_TEACHER_' + id + '">' + teachername + '</td>'+
			'	<td scope = "col" id = "exam_PROCTOR_' + id + '">' + proctorname + '</td>'+
			'	<td scope = "col" id = "exam_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "exam_SCHOOLYEARTERM_' + id + '" hidden>' + schoolyear + ' ' + periodname + '</td>'+
			'	<td scope = "col" id = "exam_DAYTIME_' + id + '" hidden>' + examdate + ' ' + examstart + ' - ' + examend +'</td>'+
			'	<td scope = "col" id = "exam_ADDEDBY_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><button id="exam_ADDEDBY_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteExamScheduleModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			// ' 	<td><div class = "btn-group" role = "group"><button id="exam_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateExamForm_MODAL" data-toggle = "modal" onclick = "updateExamFill(\'' + id + '\')"class="btn btn-warning"><i class="far fa-edit"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
	}


	<?php 

	$list_sql = "
				WITH OrderedList AS
				(
						SELECT
						
						ExamSchedule.idExamSchedule,
						ClassScheduleControl.CSCStubCode,
						ClassScheduleControl.idPeriod,
						SchoolYear.SYYear,
						Period.PName,
						Term.idTerm,
						Term.TName,
						Subjects.SCode,
						Subjects.SName,
						ExamSchedule.ESRoomCode,
						ExamSchedule.ESDate,
						ExamSchedule.ESStart,
						ExamSchedule.ESEnd,
						Faculty.FFirstName + ' ' + Faculty.FMiddleName + ' ' + Faculty.FLastName AS TeacherName,
						ExamSchedule.ESProctorName,
						ExamSchedule.ESDateCreated,
						ExamSchedule.ESStatus,
						ROW_NUMBER() OVER (ORDER BY ExamSchedule.idExamSchedule) AS 'RowNumber' 
						
						FROM 
						
						ExamSchedule
						
						INNER JOIN ClassScheduleControl
						ON ExamSchedule.idClassScheduleControl = ClassScheduleControl.idClassScheduleControl						
						INNER JOIN Subjects
						ON Subjects.SCode = ClassScheduleControl.SCode
						INNER JOIN Room
						ON Room.RCode = ExamSchedule.ESRoomCode
						INNER JOIN Term
						ON ExamSchedule.idTerm = Term.idTerm
						INNER JOIN Faculty
						ON Faculty.FCode = ClassScheduleControl.FCode
						INNER JOIN Period
						ON Period.idPeriod = ClassScheduleControl.idPeriod
						INNER JOIN SChoolYear
						ON SchoolYear.idSchoolYear = Period.idSchoolYear
						

						WHERE ClassScheduleControl.idPeriod LIKE ?
						AND ExamSchedule.idTerm LIKE ?
				)	
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?";
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $period);
	$list_getResult->bindparam(2, $term);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->bindparam(4, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "examSched_TITLE_">No Exam Schedule Found</td>'+
			'<tr>';

			$("#examSchedList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idExamSchedule']);
			$result_STUBCODE = htmlspecialchars($list_row['CSCStubCode']);
			$result_SCHOOLYEAR = htmlspecialchars($list_row['SYYear']);
			$result_PERIODNAME = htmlspecialchars($list_row['PName']);
			$result_IDPERIOD = htmlspecialchars($list_row['idPeriod']);
			$result_IDTERM = htmlspecialchars($list_row['idTerm']);
			$result_SUBJECTCODE = htmlspecialchars($list_row['SCode']);
			$result_SUBJECTNAME = htmlspecialchars($list_row['SName']);
			$result_EXAMROOM = htmlspecialchars($list_row['ESRoomCode']);
			$result_EXAMDATE = htmlspecialchars($list_row['ESDate']);
			$result_EXAMSTART = htmlspecialchars($list_row['ESStart']);
			$result_EXAMEND = htmlspecialchars($list_row['ESEnd']);
			$result_TEACHERNAME = htmlspecialchars($list_row['TeacherName']);
			$result_PROCTORNAME = htmlspecialchars($list_row['ESProctorName']);
			$result_DATECREATED = htmlspecialchars($list_row['ESDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['ESStatus']);


	
		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addExamSchedList("<?php echo $result_ID; ?>", "<?php echo $result_STUBCODE; ?>","<?php echo $result_IDPERIOD; ?>", "<?php echo $result_IDTERM; ?>", "<?php echo $result_SUBJECTCODE ?>","<?php echo $result_SUBJECTNAME ?>", "<?php echo $result_EXAMROOM ?>","<?php echo $result_EXAMDATE ?>", "<?php echo $result_EXAMSTART ?>", "<?php echo $result_EXAMEND ?>", "<?php echo $result_TEACHERNAME ?>", "<?php echo $result_PROCTORNAME ?>", "<?php echo $result_DATECREATED ?>", "<?php echo $result_STATUS ?>","<?php echo $result_SCHOOLYEAR ?>","<?php echo $result_PERIODNAME ?>");
	

	<?php 
		}			
	}
	?>

</script>