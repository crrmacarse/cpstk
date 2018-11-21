<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);

	$total_count = $db->connection->query('SELECT COUNT(*) FROM EventsList')->fetchColumn(); 
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

	<!-- Add Events Modal -->
	<div class="modal fade" id="addEventForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Add Event</h5>
				</div>
				<form id="addEventForm" method="post" action="library/form/frmAddEvent.php">
					<div class="modal-body">
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Event Title</span>
									  </div>
									  <input id = "addEventForm_TITLE" name = "TITLE" type="text" class="form-control" placeholder="Event Title" aria-describedby="sizing-addon2" required>
								</div>
								<br />
									<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Event Start</span>
									  </div>
									  <input id = "addEventForm_EVENTSTART" name = "EVENTSTART" type="date" class="form-control" placeholder="Event Start" aria-describedby="sizing-addon2" required>
								</div>
								<br />								
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Event End</span>
									  </div>
									  <input id = "addEventForm_EVENTEND" name = "EVENTEND" type="date" class="form-control" placeholder="Event End" aria-describedby="sizing-addon2" required>
								</div>
								<br />							  
								<input type = "image" class = "d-none mx-auto my-3" id = "addEventForm_IMAGEDISPLAY" width="300"></input>

								<div class="custom-file">
								  <input type = "file" class="custom-file-input" name = "IMAGE" accept="image/*" id="addEventForm_IMAGE">
								  <label class="custom-file-label" for="customFile">Choose file</label>
								</div>
								<br />
								<br />	
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Event Description</span>
									  </div>
									  <textarea id = "addEventForm_DESCRIPTION" maxlength = "180" name = "DESCRIPTION" style = "padding-bottom: 150px;" type="text" class="form-control" placeholder="Event Description..." aria-describedby="sizing-addon2" required></textarea>
								</div>
								<br />
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="addEventForm_SUBMIT" class="btn btn-primary" data-loading-text="Adding..."> Add</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="displayModal_MODAL" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<h5>Image Display</h5>
			</div>
			<div class="modal-body">
				<img id = "displayModal_IMAGE" src = "" class="img-fluid">
				<br />
				<small id = "displayModal_IMAGESRC"></small>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			  </div>
		</div>
  	</div>

  	<div class="modal fade" id="updateEventsForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Events Update Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="updateEventsForm_INFORMATION"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "updateEventsForm" class="form-horizontal" action="library/form/frmUpdateEvents.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
					<input type="hidden" id="updateEventsForm_ID" name="ID" required />
							<div class="panel-body">
								<div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateEventsForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "updateEventsForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Update.." value = "Update">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

		<div class="modal fade" id="deleteEventForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete Event</h5>
				</div>
	            <form id="deleteEventForm" method="post" action="library/form/frmDeleteEvent.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteEventForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>Event Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                       	<tr>
	                            <td>Event Date: </td>
	                            <td id="deleteEventForm_EVENTDATE"></td>
	                        </tr>
	                        <tr>
	                            <td>Event Title: </td>
	                            <td id="deleteEventForm_TITLE"></td>
	                        </tr>
	                        <tr>
	                            <td>Event Description: </td>
	                            <td id="deleteEventForm_DESCRIPTION"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created: </td>
	                            <td id="deleteEventForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteEventForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteEventForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
	    	</div>
		</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Events Management</h1>
					<p class = "text-muted">Manages Events that is being viewed from the Information Kiosk.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addEventForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add New Event</a>
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
					  <th scope="col">Event Date</th>
					  <th scope="col">Event Title</th>
					  <th scope="col">Image</th>
					  <th scope="col">Event Descriptions</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>
					</tr>
				  </thead>
				  <tbody id = "eventsList">
					
				  </tbody>
				</table>
			
		</div>

		<div class = "row top-buffer justify-content-md-center">
				 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-events.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-events.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-events.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-events.php?search='+searchValue;
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

	// Displaying of Image

	var _URL = window.URL || window.webkitURL;

	$('#addEventForm_IMAGE').change( function(event) {
		var file, img;
		if ((file = this.files[0])) {
			img = new Image();
			img.onload = function () {
				// if(this.width < 400 || this.width > 500)
				// 	{
				// 		alert("Please adjust the width to the desirable dimension [width: 400-500]");
				// 		document.getElementById("addMenuForm_IMAGE").value = "";
				// 		$("#addEventForm_IMAGEDISPLAY").css('display', 'none');
				// 	}
				// else if(this.height < 400 || this.height > 500)
				// 	{
				// 		alert("Please adjust the height to the desirable dimension [height: 400-500]");
				// 		document.getElementById("addMenuForm_IMAGE").value = "";
				// 		$("#addEventForm_IMAGEDISPLAY").css('display', 'none');
				// 	}
				// else
				// 	{
						$("#addEventForm_IMAGEDISPLAY").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
						$("#addEventForm_IMAGEDISPLAY").addClass('d-block');
					// }
				
			};
			img.src = _URL.createObjectURL(file);
		}
		
	});

	var PageComponent = {
        eventsList: document.getElementById('eventsList')
    };

    function fillDisplayModal(id)
	{
		var image = document.getElementById('event_IMAGE_'+id).innerHTML;
		
		document.getElementById('displayModal_IMAGESRC').innerHTML = "Image source: "+image;
		document.getElementById("displayModal_IMAGE").src = "img/events/"+image;
		$("#displayModal_MODAL").modal('show');
	}

    var AEForm = {
    	form: document.getElementById('addEventForm'),
    	title: document.getElementById('addEventForm_TITLE'),
    	eventstart: document.getElementById('addEventForm_EVENTSTART'),
    	eventend: document.getElementById('addEventForm_EVENTEND'),
    	image: document.getElementById('addEventForm_IMAGE'),
    	imagedisplay: document.getElementById('addEventForm_IMAGEDISPLAY'),
    	description: document.getElementById('addEventForm_DESCRIPTION'),
    	modal: '#addEventForm_MODAL',
    	submit: '#addEventForm_SUBMIT'
    }

    AEForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(AEForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(AEForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(AEForm.modal).modal('hide');
						alert('Added Succesfully');
						AEForm.form.reset();
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


	var UEForm = {
		form: document.getElementById('updateEventsForm'),
		id: document.getElementById('updateEventsForm_ID'),
		information: document.getElementById('updateEventsForm_INFORMATION'),
		status: document.getElementById('updateEventsForm_STATUS'),
		modal: document.getElementById('updateEventsForm_MODAL'),
		submit: document.getElementById('updateEventsForm_SUBMIT')
	}


	function updateEventFill(id){
		var title = document.getElementById('event_TITLE_'+id).innerHTML;

		UEForm.id.value = id;
		UEForm.information.innerHTML = "Status Update For " + title
	}

	UEForm.form.onsubmit = function(e) {
	e.preventDefault();
	
	$(this).ajaxSubmit({
		beforeSend:function()
		{
			$(UEForm.submit).button('loading');
		},
		uploadProgress:function(event,position,total,percentCompelete)
		{

		},
		success:function(data)
		{
			$(UEForm.submit).button('reset');
			var server_message = data.trim();
			if(!isWhitespace(GetSuccessMsg(server_message)))
			{		
				$('#updateEventsForm_MODAL').modal('toggle');
				alert("Updated Events Succesfully");
				UEForm.form.reset();
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

	var DEForm = {
		form: document.getElementById('deleteEventForm'),
		modal: document.getElementById('deleteEventForm_MODAL'),
		id: document.getElementById('deleteEventForm_ID'),
		date: document.getElementById('deleteEventForm_EVENTDATE'),
		title: document.getElementById('deleteEventForm_TITLE'),
		description: document.getElementById('deleteEventForm_DESCRIPTION'),
		datecreated: document.getElementById('deleteEventForm_DATECREATED'),
		status: document.getElementById('deleteEventForm_STATUS'),
		submit: document.getElementById('deleteEventForm_SUBMIT')
	};

	function deleteEvent(id) {
        $('#event_' + id).remove();
    }


    function openDeleteEventModal(id) {
    	DEForm.id.value = id;
    	DEForm.date.innerHTML = document.getElementById('event_DATE_'+id).innerHTML;
    	DEForm.title.innerHTML = document.getElementById('event_TITLE_'+id).innerHTML;
    	DEForm.description.innerHTML = document.getElementById('event_DESCRIPTION_'+id).innerHTML;
    	DEForm.datecreated.innerHTML = document.getElementById('event_DATECREATED_'+id).innerHTML;
    	DEForm.status.innerHTML = document.getElementById('event_STATUS_'+id).innerHTML;
    	$(DEForm.modal).modal('show');
    }


	$(DEForm.form).on('submit', function (e) {
        var id = DEForm.id.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DEForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            	$(DEForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{		
				    $(DEForm.submit).button('reset');
					deleteEvent(id);
					DEForm.form.reset();
					$(DEForm.modal).modal('hide');
					alert("Succesfully Deleted Event");
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

    function addEventList(id, eventstart, eventend, title, imgpath, description, datecreated, status)
	{

		PageComponent.eventsList.innerHTML = PageComponent.eventsList.innerHTML +
			'<thead>'+
			'<tr id = "event_'+ id +'">'+
			'	<td scope = "col" id = "event_DATE_' + id + '">' + eventstart + " - " + eventend + '</td>'+
			'	<td scope = "col" id = "event_TITLE_' + id + '">' + title + '</td>'+
			'   <td id="event_IMAGE_' + id + '" style = "display: none;">' + imgpath + '</td>'+			
			'	<td><button role="button" class="btn btn-info" onclick="fillDisplayModal(' + id + ')"><i class="fas fa-eye"></i></button></td>'+			
			'	<td scope = "col" id = "event_DESCRIPTION_' + id + '">' + description + '</td>'+
			'	<td scope = "col" id = "event_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "event_STATUS_' + id + '">' + status + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="event_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateEventsForm_MODAL" data-toggle = "modal" onclick = "updateEventFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="event_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteEventModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
	}


	<?php 

	$list_sql = '		
				WITH OrderedList AS
				(
				SELECT 
				EventsList.idEventsList, 
				EventsList.ELStart,
				EventsList.ELEnd,
				EventsList.ELTitle, 
				EventsList.ELDescription, 
				EventsList.ELImage, 
				EventsList.ELDateCreated,
				EventsList.ELStatus,
				ROW_NUMBER() OVER (ORDER BY EventsList.ELStatus) AS "RowNumber" 

				FROM EventsList

				WHERE (EventsList.ELTitle LIKE ? OR EventsList.ELDescription LIKE ?)
				)
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $search);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->bindparam(4, $offset);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "event_TITLE_">No Event Found</td>'+
			'<tr>';

			$("#eventsList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idEventsList']); 
			$date_EVENTSTART = date_create(htmlspecialchars($list_row['ELStart']));
			$result_EVENTSTART = date_format($date_EVENTSTART, 'F/d/Y');
			$date_EVENTEND = date_create(htmlspecialchars($list_row['ELEnd']));
			$result_EVENTEND = date_format($date_EVENTEND, 'F/d/Y');
			$result_TITLE = htmlspecialchars($list_row['ELTitle']);
			$result_IMAGE = htmlspecialchars($list_row['ELImage']);
			$result_EVENTDESCRIPTION = htmlspecialchars($list_row['ELDescription']);
			$result_DATECREATED = htmlspecialchars($list_row['ELDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['ELStatus']);

		if($result_STATUS == '1')
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addEventList("<?php echo $result_ID; ?>","<?php echo $result_EVENTSTART ?>","<?php echo $result_EVENTEND; ?>","<?php echo $result_TITLE; ?>","<?php echo $result_IMAGE; ?>","<?php echo $result_EVENTDESCRIPTION; ?>","<?php echo $result_DATECREATED; ?>", "<?php echo $result_STATUS; ?>");
		
		<?php 
		}			
	}
	?>

</script>