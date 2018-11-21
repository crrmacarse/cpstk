<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->prepare('SELECT COUNT(*) FROM ExamScheduleFinal 						WHERE (SUBJCODE LIKE ? OR SUBJTEACHER LIKE ? OR EXPROCTOR LIKE ? OR EXROOM LIKE ?)');
	$total_count->bindParam(1,$search);
	$total_count->bindParam(2,$search);
	$total_count->bindParam(3,$search);
	$total_count->bindParam(4,$search);
	$total_count->execute();
	$total_count = $total_count->fetchColumn();

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
				    <input type="file" class="custom-file-input" name = "FILE" id = "importExamScheduleForm_FILE" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
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
				<h5>Drop All Class Schedule</h5>
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
                            <td><b>Exam Schedule Count: </b></td>
                            <td id="deleteExamScheduleForm_SCHOOLYEARTERM"><?php echo $total_count; ?></td>
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
						    <a class="dropdown-item" href="#importExamScheduleForm_MODAL" data-toggle="modal">Import Batch Exam Data</a>
						    <a class="dropdown-item" href="#deleteExamScheduleForm_MODAL" data-toggle="modal">Drop All Exam Data</a>
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
					  <th scope="col">Exam Day</th>
					  <th scope="col">Exam Time</th>
					  <th scope="col">Exam Room</th>
					  <th scope="col">Subject Schedule</th>
					  <th scope="col">Subject Teacher</th>
					  <th scope="col">Exam Proctor</th>				  
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
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-examination-schedule.php?search=<?php echo $trimmedsearch ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-examination-schedule.php?search=<?php echo $trimmedsearch ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-examination-schedule.php?search=<?php echo $trimmedsearch ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
		$("#txtSearch").val("<?php echo $trimmedsearch ?>");
			 $( "#btnSearch" ).click(function() {
			  var searchValue = $("#txtSearch").val().toLowerCase(); 
				window.location.href='manage-examination-schedule.php?search='+searchValue;
				});
				$('#txtSearch').keypress(function(e){
				if(e.which == 13){ 
					$('#btnSearch').click();
				}
			});

			$("#txtSearch").click(function(){
				$("#txtSearch").val("");
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
					$("#examSchedList tr").remove(); 
					$(".pagination").hide();
					DESForm.form.reset();
					$(DESForm.modal).modal('hide');
					alert(GetSuccessMsg(server_message));
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


    function addExamSchedList(id, subjcode, examroom, examdate, examstart, examend, subjstart, subjend, subjday, teachername, proctorname)
	{
		PageComponent.examSchedList.innerHTML = PageComponent.examSchedList.innerHTML +
			'<thead>'+
			'<tr id = "exam_'+ id +'">'+
			'	<td scope = "col" id = "exam_SUBJECTNAME_' + id + '">' + subjcode + '</td>'+
			'	<td scope = "col" id = "exam_EXAMDATE_' + id + '">' + examdate + '</td>'+
			'	<td scope = "col" id = "exam_TIME_' + id + '">' + examstart + ' - ' + examend + '</td>'+
			'	<td scope = "col" id = "exam_ROOM_' + id + '">'+ examroom +'</td>'+
			'	<td scope = "col" id = "exam_SCHEDULE_' + id + '">' + subjstart + ' - ' + subjend + ' ' + subjday + '</td>'+
			'	<td scope = "col" id = "exam_TEACHER_' + id + '">' + teachername + '</td>'+
			'	<td scope = "col" id = "exam_PROCTOR_' + id + '">' + proctorname + '</td>'+
			'	<td scope = "col" id = "exam_DAYTIME_' + id + '" hidden>' + examdate + ' ' + examstart + ' - ' + examend +'</td>'+
			'</tr>'+	
			'</thead>';
	}


	<?php 

	$list_sql = "
				WITH OrderedList AS
				(
						SELECT 

						idExamScheduleFinal, 
						EXAMDATE, 
						EXTIMESTART, 
						EXTIMEEND, 
						SUBJCODE, 
						SUBJSTART, 
						SUBJEND, 
						SUBJDAY, 
						SUBJTEACHER, 
						EXPROCTOR, 
						EXROOM,
						ROW_NUMBER() OVER (ORDER BY idExamScheduleFinal) AS 'RowNumber' 
						

						FROM ExamScheduleFinal

						WHERE (SUBJCODE LIKE ? OR SUBJTEACHER LIKE ? OR EXPROCTOR LIKE ? OR EXROOM LIKE ?)
				)	
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ? ORDER BY EXAMDATE";
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $search);
	$list_getResult->bindparam(4, $search);
	$list_getResult->bindparam(5, $limit);
	$list_getResult->bindparam(6, $offset);
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
			$result_ID = htmlspecialchars($list_row['idExamScheduleFinal']);
			$result_SUBJECTCODE = htmlspecialchars($list_row['SUBJCODE']);
			$result_EXAMROOM = htmlspecialchars($list_row['EXROOM']);
			$result_EXAMDATE = htmlspecialchars($list_row['EXAMDATE']);
			$result_EXAMSTART = htmlspecialchars($list_row['EXTIMESTART']);
			$result_EXAMEND = htmlspecialchars($list_row['EXTIMEEND']);
			$result_SUBJSTART = htmlspecialchars($list_row['SUBJSTART']);
			$result_SUBJEND = htmlspecialchars($list_row['SUBJEND']);
			$result_SUBJDAY = htmlspecialchars($list_row['SUBJDAY']);
			$result_TEACHERNAME = htmlspecialchars($list_row['SUBJTEACHER']);
			$result_PROCTORNAME = htmlspecialchars($list_row['EXPROCTOR']);

	?>

	addExamSchedList("<?php echo $result_ID; ?>", "<?php echo $result_SUBJECTCODE; ?>","<?php echo $result_EXAMROOM; ?>", "<?php echo $result_EXAMDATE; ?>", "<?php echo $result_EXAMSTART ?>","<?php echo $result_EXAMEND ?>", "<?php echo $result_SUBJSTART ?>","<?php echo $result_SUBJEND ?>", "<?php echo $result_SUBJDAY ?>", "<?php echo $result_TEACHERNAME ?>", "<?php echo $result_PROCTORNAME?>");

	<?php 
		}			
	}
	?>

</script>