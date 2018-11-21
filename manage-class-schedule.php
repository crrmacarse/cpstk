<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->prepare('SELECT Count(Distinct STUBCODE)  FROM ClassScheduleFinal WHERE (ClassScheduleFinal.SUBJCODE LIKE ? OR ClassScheduleFinal.EMPLOYEEID LIKE ? OR ClassScheduleFinal.EMPLOYEENAME LIKE ? OR ClassScheduleFinal.ROOMCODE LIKE ? OR ClassScheduleFinal.CLASSSTATUSCODE LIKE ?)');
	$total_count->bindParam(1,$search);
	$total_count->bindParam(2,$search);
	$total_count->bindParam(3,$search);
	$total_count->bindParam(4,$search);
	$total_count->bindParam(5,$search);
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
	
	<div class="modal fade" id="importClassScheduleForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Import Class Schedule Data</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "importClassScheduleForm" class="form-horizontal" action="library/form/frmImportClassSchedule.php" method="post" name="upload_excel" enctype="multipart/form-data">
	         <div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <span class="input-group-text">Upload</span>
				  </div>
				  <div class="custom-file">
				    <input type="file" id = "importClassScheduleForm_FILE" class="custom-file-input" name = "FILE" id = "fileupload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
				    <label class="custom-file-label" for="fileupload">Choose file</label>
				  </div>
				</div>
                      
	      </div>
	      <div class="modal-footer">
	        <input type="submit" id = "importClassScheduleForm_SUBMIT" class="btn btn-success" name = "Import" data-loading-text = "Importing.." value = "Import">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteClassScheduleForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete All Class Schedule</h5>
			</div>
            <form id="deleteClassScheduleForm" method="post" action="library/form/frmDeleteClassSchedule.php">
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Class Schedule Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><b>Class Schedule Count: </b></td>
                            <td id="deleteClassScheduleForm_SCHOOLYEAR"><?php echo $total_count; ?></td>
                        </tr>

						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteClassScheduleForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Class Schedule Management</h1>
					<p class = "text-muted">Manages Class Schedule that are Offered.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#importClassScheduleForm_MODAL" data-toggle="modal">Import Batch Class Schedule</a>
						    <a class="dropdown-item" href="#deleteClassScheduleForm_MODAL" data-toggle="modal">Drop All Class Schedule</a>
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
					  <th scope="col">Stub Code</th>
					  <th scope="col">Subject</th>
					  <th scope="col">Room</th>
					  <th scope="col">Assigned Faculty</th>
					  <th scope="col">Schedule</th>
					  <th scope="col">Class Type</th>
					  <th scope="col">View Students</th>  
					</tr>
				  </thead>
				  <tbody id = "classScheduleList">
				  </tbody>
				</table>
			
		</div>

		<div class = "row top-buffer justify-content-md-center">
			 <div class="col-md-auto top-buffer">
		<nav>
			  <ul class="pagination justify-content-center">
			    	<li class="page-item">
					  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
					</li>
					<?php   	
					  	for($i = 1; $i <= $total_page; $i++)
						{
							?>
					  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
							<a class="page-link" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
					    </li>
					  
					  <?php
						}
					  ?>
					<li class="page-item">
					  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-class-schedule.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-class-schedule.php?search='+searchValue;
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
        classScheduleList: document.getElementById('classScheduleList')
    };

    function viewClassScheduleData(id){
    	window.location.href='view-class-schedule.php?id='+id;
    }

var ICSCForm =
    {
    	form: document.getElementById('importClassScheduleForm'),
    	file: document.getElementById('importClassScheduleForm_FILE'),
    	modal: '#importClassScheduleForm_MODAL',
    	submit: '#importClassScheduleForm_SUBMIT'
    }

    ICSCForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ICSCForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ICSCForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ICSCForm.modal).modal('hide');
						alert('Imported Succesfully');
						window.location.reload(false); 						
						ICSCForm.form.reset();
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

	var DCSForm = {
	    form: document.getElementById('deleteClassScheduleForm'),
	    modal: document.getElementById('deleteClassScheduleForm_MODAL'),
	    submit: document.getElementById('deleteClassScheduleForm_SUBMIT')
	   };


	$(DCSForm.form).on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DCSForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DCSForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DCSForm.submit).button('reset');
					$("#classScheduleList tr").remove(); 
					$(".pagination").hide();
					DCSForm.form.reset();
					$(DCSForm.modal).modal('hide');
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
	
	function addClassScheduleList(id, stubcode, subject, employee, day, start, end, roomno, classtype)
	{
		PageComponent.classScheduleList.innerHTML = PageComponent.classScheduleList.innerHTML +
			'<thead>'+
			'<tr id = "classSchedule_'+ id +'">'+
			'	<td scope = "col" id = "classSchedule_STUBCODE_' + id +'">' + stubcode + '</td>'+
			'	<td scope = "col" id = "classSchedule_SUBJECTNAME_' + id +'">' + subject + '</td>'+
			'	<td scope = "col" id = "classSchedule_ROOMCODE_' + id +'">' + roomno + '</td>'+
			'	<td scope = "col" id = "classSchedule_EMPLOYEE_' + id + '">' + employee + '</td>'+
			'	<td scope = "col" id = "classSchedule_SCHEDULE_' + id + '">' + start + ' - ' + end + ' ' + day + '</td>'+
			'	<td scope = "col" id = "classSchedule_DAY_' + id + '" hidden>' + day + '</td>'+
			'	<td scope = "col" id = "classSchedule_START_' + id + '" hidden>' + start + '</td>'+
			'	<td scope = "col" id = "classSchedule_END_' + id + '" hidden>' + end + '</td>'+
			'	<td scope = "col" id = "classSchedule_CLASSTYPE_' + id + '">' + classtype + '</td>'+
			'	<td></button><button id = "classSchedule_CSD_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewClassScheduleData(\'' + stubcode + '\')" role = "button"">View Students</button></td>'+
			// '	<td><div class = "btn-group" role = "group"><button id="classSchedule_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateClassSchedulelForm_MODAL" data-toggle = "modal" onclick = "updateClassScheduleFill(\'' + id + '\')"class="btn btn-warning"><i class="far fa-edit"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
	}


<?php 

	$list_sql = '
					WITH SQLList AS
							(
							SELECT 

								SUBJCODE, 
								STUBCODE, 
								STARTCLASS, 
								ENDCLASS, 
								CLASSDAYS,
								CLASSTYPE, 
								EMPLOYEEID, 
								EMPLOYEENAME, 
								ROOMCODE, 
								CLASSSTATUSCODE,
								ROW_NUMBER() OVER (ORDER BY ClassScheduleFinal.STUBCODE) AS "RowNumber"


							FROM ClassScheduleFinal

							WHERE (ClassScheduleFinal.SUBJCODE LIKE ? OR ClassScheduleFinal.EMPLOYEEID LIKE ? OR ClassScheduleFinal.EMPLOYEENAME LIKE ? OR ClassScheduleFinal.ROOMCODE LIKE ? OR ClassScheduleFinal.CLASSSTATUSCODE LIKE ?)		
		
							GROUP BY SUBJCODE, STUBCODE, STARTCLASS, ENDCLASS, CLASSDAYS, CLASSTYPE, EMPLOYEEID, EMPLOYEENAME, ROOMCODE, CLASSSTATUSCODE
									) 
							SELECT * 
							FROM SQLList
							WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindParam(1,$search);
	$list_getResult->bindParam(2,$search);
	$list_getResult->bindParam(3,$search);
	$list_getResult->bindParam(4,$search);
	$list_getResult->bindParam(5,$search);
	$list_getResult->bindParam(6,$limit);
	$list_getResult->bindParam(7,$offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>
			
			var content = '<tr>'+
			'<tr>'+
			'<td id = "classSched_TITLE_">No Class Schedule Found</td>'+
			'<tr>';

			$("classScheduleList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['STUBCODE']);
			$result_STUBCODE = htmlspecialchars($list_row['STUBCODE']);
			$result_SUBJECT = htmlspecialchars($list_row['SUBJCODE']);
			$result_EMPLOYEEID = htmlspecialchars($list_row['EMPLOYEEID']);
			$result_EMPLOYEENAME = htmlspecialchars($list_row['EMPLOYEENAME']);
			$result_DAY = htmlspecialchars($list_row['CLASSDAYS']);
			$result_START = htmlspecialchars($list_row['STARTCLASS']);
			$result_END = htmlspecialchars($list_row['ENDCLASS']);
			$result_ROOMNUMBER = htmlspecialchars($list_row['ROOMCODE']);
			$result_CLASSTYPE = htmlspecialchars($list_row['CLASSTYPE']);
	?>

	addClassScheduleList("<?php echo $result_ID; ?>", "<?php echo $result_STUBCODE; ?>", "<?php echo $result_SUBJECT; ?>", "<?php echo $result_EMPLOYEENAME; ?>", "<?php echo $result_DAY; ?>", "<?php echo $result_START; ?>", "<?php echo $result_END; ?>", "<?php echo $result_ROOMNUMBER; ?>", "<?php echo $result_CLASSTYPE; ?>");

		<?php 
		}			
	}
	?>
	</script>