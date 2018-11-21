<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	$getschoolyear = isset($_GET['schoolyear']) ? $_GET['schoolyear'] : '';
	

	$list_getSchoolYear = $db->connection->prepare('SELECT SchoolYear.SYYear FROM SchoolYear WHERE SchoolYear.idSchoolYear = ?');
	$list_getSchoolYear->bindparam(1, $getschoolyear);
	$list_getSchoolYear->execute();
	$schoolyear = $list_getSchoolYear->fetchColumn();

	$getCount = $db->connection->prepare('SELECT COUNT(*) FROM Period WHERE Period.idSchoolYear = ? ');
	$getCount->bindparam(1,$getschoolyear);
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


	<div class="modal fade" id="addPeriodForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add Period Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add Period for <?php echo $schoolyear ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addPeriodForm" class="form-horizontal" action="library/form/frmAddPeriod.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <input id = "addPeriodForm_SCHOOLYEAR" type = "text" name = "SCHOOLYEAR" value = "<?php echo  $getschoolyear ?>" hidden />
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
								    <select name = "PERIODNAME" class="form-control" id="addPeriodForm_PERIODNAME" required>
								      <option selected = "true" disabled value = "">Select a Period</option>
								      <option value = "1ST SEMESTER">1ST SEMESTER</option>
								      <option value = "2ND SEMESTER">2ND SEMESTER</option>
								      <option value = "SUMMER">SUMMER</option>
								    </select>
								 </div>
								 <br />
 								 <h6 class = "font-weight-bold float-right">Period Information</h6>
								 <div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Period Start</span>
									  </div>
									  <input id = "addPeriodForm_PERIODSTART" name = "PERIODSTART" type="date" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
								<br / >
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Period End</span>
									  </div>
									  <input id = "addPeriodForm_PERIODEND" name = "PERIODEND" type="date" class="form-control" aria-describedby="sizing-addon2" required>
								</div>
							</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addPeriodForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deletePeriodForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete Period</h5>
				</div>
	            <form id="deletePeriodForm" method="post" action="library/form/frmDeletePeriod.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deletePeriodForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>Period Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td>School Year: </td>
	                            <td id="deletePeriodForm_SCHOOLYEAR" class = "font-italic"></td>
	                        </tr>
	                        <tr>
	                            <td>Period: </td>
	                            <td id="deletePeriodForm_PERIODNAME"></td>
	                        </tr>
	                        <tr>
	                            <td>Period Start:</td>
	                            <td id="deletePeriodForm_PERIODSTART"></td>
	                        </tr>
	                        <tr>
	                            <td>Period End: </td>
	                            <td id="deletePeriodForm_PERIODEND"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created:</td>
	                            <td id="deletePeriodForm_DATECREATED"></td>
	                        </tr>	
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deletePeriodForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deletePeriodForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
	    	</div>
		</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Period Management for <?php echo $schoolyear ?></h1>
					<p class = "text-muted">Manages Period, Example 2018-2019, User Can Add a New Academic Period.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-school-year.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addPeriodForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Period</a>
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
				  <th scope="col">Period Name</th>
  				  <th scope="col">Period Start</th>
				  <th scope="col">Period End</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "periodList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-term.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-term.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-term.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-period.php?search='+searchValue;
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
        periodList: document.getElementById('periodList')
    };


    var APForm = {
    	form: document.getElementById('addPeriodForm'),
    	schoolyear: document.getElementById('addPeriodForm_SCHOOLYEAR'),
    	periodname: document.getElementById('addPeriodForm_PERIODNAME'),
    	periodstart: document.getElementById('addPeriodForm_PERIODSTART'),
    	periodend: document.getElementById('addPeriodForm_PERIODEND'),
    	submit: document.getElementById('addPeriodForm_SUBMIT'),
    	modal: '#addPeriodForm_SUBMIT'
    }

	APForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(APForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(APForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(APForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						APForm.form.reset();
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

	var PDForm = {
		form: document.getElementById('deletePeriodForm'),
		modal: document.getElementById('deletePeriodForm_MODAL'),
		id: document.getElementById('deletePeriodForm_ID'),
		schoolyear: document.getElementById('deletePeriodForm_SCHOOLYEAR'),
		periodname: document.getElementById('deletePeriodForm_PERIODNAME'),
		periodstart: document.getElementById('deletePeriodForm_PERIODSTART'),
		periodend: document.getElementById('deletePeriodForm_PERIODEND'),
		datecreated: document.getElementById('deletePeriodForm_DATECREATED'),
		status: document.getElementById('deletePeriodForm_STATUS'),
		submit: document.getElementById('deletePeriodForm_SUBMIT')
	}

	$(PDForm.form).on('submit', function (e) {
        var id = PDForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(PDForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(PDForm.submit).button('reset');
                deletePeriod(id);
				PDForm.form.reset();
				$(PDForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deletePeriod(id) {
        $('#period_' + id).remove();
    }

    function openDeletePeriodModal(id) {
        PDForm.id.value = id;
      	PDForm.periodname.innerHTML = document.getElementById('period_NAME_'+id).innerHTML;
      	PDForm.periodstart.innerHTML = document.getElementById('period_START_'+id).innerHTML;
      	PDForm.periodend.innerHTML = document.getElementById('period_END_'+id).innerHTML;
      	PDForm.schoolyear.innerHTML = document.getElementById('period_SCHOOLYEAR_'+id).innerHTML;
      	PDForm.datecreated.innerHTML = document.getElementById('period_DATECREATED_'+id).innerHTML;
      	PDForm.status.innerHTML = document.getElementById('period_STATUS_'+id).innerHTML;
        $(PDForm.modal).modal('show');
    }

    function addPeriodList(id, schoolyear, pname, pstart, pend, datecreated, status)
    {
    	PageComponent.periodList.innerHTML = PageComponent.periodList.innerHTML +
    		'<thead>'+
			'<tr id = "period_'+ id +'">'+
			'	<td scope = "col" id = "period_NAME_' + id +'">' + pname + '</td>'+
			'	<td scope = "col" id = "period_START_' + id +'">' + pstart + '</td>'+
			'	<td scope = "col" id = "period_END_' + id +'">' + pend + '</td>'+
			'	<td scope = "col" id = "period_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "period_SCHOOLYEAR_' + id + '" hidden>' + schoolyear + '</td>'+
			'	<td scope = "col" id = "period_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="period_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeletePeriodModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			// ' 	<td><div class = "btn-group" role = "group"><button id="period_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updatePeriodForm_MODAL" data-toggle = "modal" onclick = "updatePeriodFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="period_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeletePeriodModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				
				SELECT 
						
				Period.idPeriod,
				Period.idSchoolYear,
				SchoolYear.SYYear,
				Period.PName,
				Period.PStart,
				Period.PEnd,
				Period.PStatus,
				Period.PDateCreated,
				ROW_NUMBER() OVER (ORDER BY Period.idPeriod) AS "RowNumber"
				
				
				FROM Period

				INNER JOIN SchoolYear
				On SchoolYear.idSchoolYear = Period.idSchoolYear
		
				WHERE Period.PName LIKE ? AND Period.idSchoolYear = ?
				)

				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $getschoolyear);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->bindparam(4, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "period_TITLE_">No Period Found</td>'+
			'<tr>';

			$("#periodList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idPeriod']);
			$result_SCHOOLYEAR = htmlspecialchars($list_row['SYYear']);
			$result_PERIODNAME = htmlspecialchars($list_row['PName']);
			$result_PERIODSTART = htmlspecialchars($list_row['PStart']);
			$result_PERIODEND = htmlspecialchars($list_row['PEnd']);
			$result_DATECREATED = htmlspecialchars($list_row['PDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['PStatus']);
		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addPeriodList("<?php echo $result_ID ?>","<?php echo $result_SCHOOLYEAR ?>","<?php echo $result_PERIODNAME ?>","<?php echo $result_PERIODSTART ?>","<?php echo $result_PERIODEND?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	