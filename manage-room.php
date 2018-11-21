<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	$getbuilding = isset($_GET['building']) ? $_GET['building'] : '';
	
	$list_getBuilding = $db->connection->prepare('SELECT Landmark.Lname FROM Landmark WHERE Landmark.idLandmark = ?');
	$list_getBuilding->bindparam(1, $getbuilding);
	$list_getBuilding->execute();
	$building = $list_getBuilding->fetchColumn();
	
	$getCount = $db->connection->prepare('SELECT COUNT(*) FROM Room WHERE Room.idLandmark = ?');
	$getCount->bindparam(1,$getbuilding);
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

	<div class="modal fade" id="addRoomForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Add Room Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add Room for <?php echo $building ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addRoomForm" class="form-horizontal" action="library/form/frmAddRoom.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <input id = "addRoomForm_BUILDING" type = "text" name = "BUILDING" value = "<?php echo  $getbuilding ?>" hidden />
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Room Code</span>
									  </div>
									  <input id = "addRoomForm_CODE" name = "CODE" type="input" class="form-control" placeholder="Room Code" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Room No.</span>
									  </div>
									  <input id = "addRoomForm_NO" name = "ROOMNO" type="input" class="form-control" placeholder="Room No" aria-describedby="sizing-addon2" required>
								</div>
								<br />
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addRoomForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteRoomForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete Room</h5>
				</div>
	            <form id="deleteRoomForm" method="post" action="library/form/frmDeleteRoom.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteRoomForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>Room Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                       	<tr>
	                            <td>Room Code: </td>
	                            <td id="deleteRoomForm_CODE"></td>
	                        </tr>
	                        <tr>
	                            <td>Room Name: </td>
	                            <td id="deleteRoomForm_ROOMNO"></td>
	                        </tr>
	                        <tr>
	                            <td>Building Located: </td>
	                            <td id="deleteRoomForm_BUILDING" class = "font-italic"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created: </td>
	                            <td id="deleteRoomForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteRoomForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteRoomForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
	    	</div>
		</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Room Management for <?php echo $building ?></h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-landmarks.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addRoomForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Room</a>
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
				  <th scope="col">Room Code</th>
				  <th scope="col">Room NO</th>
				  <th scope="col">Status</th>
				  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "roomList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-room.php?building=<?php echo $getbuilding ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-room.php?building=<?php echo $getbuilding ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-room.php?building=<?php echo $getbuilding ?>&search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-room.php?building=<?php echo $getbuilding ?>&search='+searchValue;
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
        roomList: document.getElementById('roomList')
    };

    var ARForm = {
    	form: document.getElementById('addRoomForm'),
    	building: document.getElementById('addRoomForm_BUILDING'),
    	roomcode: document.getElementById('addRoomForm_CODE'),
    	no: document.getElementById('addRoomForm_NO'),
    	modal: '#addRoomForm_MODAL',
    	submit: '#addRoomForm_SUBMIT'
    }

	ARForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ARForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ARForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ARForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ARForm.form.reset();
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

	var DRForm = {
		form: document.getElementById('deleteRoomForm'),
		modal: document.getElementById('deleteRoomForm_MODAL'),
		id: document.getElementById('deleteRoomForm_ID'),
		building: document.getElementById('deleteRoomForm_BUILDING'),
		code: document.getElementById('deleteRoomForm_CODE'),
		roomno: document.getElementById('deleteRoomForm_ROOMNO'),
		datecreated: document.getElementById('deleteRoomForm_DATECREATED'),
		status: document.getElementById('deleteRoomForm_STATUS'),
		submit: document.getElementById('deleteRoomForm_SUBMIT')
	};

	function deleteRoom(id) {
        $('#room_' + id).remove();
    }


    function openDeleteRoomModal(id) {
    	DRForm.id.value = id;
    	DRForm.building.innerHTML = document.getElementById('room_BUILDING_'+id).innerHTML;
    	DRForm.roomno.innerHTML = document.getElementById('room_ROOMNO_'+id).innerHTML;
    	DRForm.code.innerHTML = document.getElementById('room_CODE_'+id).innerHTML;
    	DRForm.datecreated.innerHTML = document.getElementById('room_DATECREATED_'+id).innerHTML;
    	DRForm.status.innerHTML = document.getElementById('room_STATUS_'+id).innerHTML;
    	$(DRForm.modal).modal('show');
    }


	$(DRForm.form).on('submit', function (e) {
        var id = DRForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DRForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(DRForm.submit).button('reset');
				deleteRoom(id);
				DRForm.form.reset();
				$(DRForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
    function addRoomList(id, idlandmark, code, landmark, roomno, datecreated, status)
    {
    	PageComponent.roomList.innerHTML = PageComponent.roomList.innerHTML +
    		'<thead>'+
			'<tr id = "room_'+ id +'">'+
			'	<td scope = "col" id = "room_CODE_' + id +'">' + code + '</td>'+
			'	<td scope = "col" id = "room_ROOMNO_' + id +'">' + roomno + '</td>'+
			'	<td scope = "col" id = "room_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "room_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "room_IDLANDMARK_' + id + '" hidden>' + idlandmark + '</td>'+
			'	<td scope = "col" id = "room_BUILDING_' + id + '" hidden>' + landmark + '</td>'+
			// ' 	<td><div class = "btn-group" role = "group"><button id="room_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateSubjectForm_MODAL" data-toggle = "modal" onclick = "updateSubjectFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="subject_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteRoomModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			' 	<td><button id="subject_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteRoomModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
				
				Room.idRoom,
				Room.RCode,
				Landmark.idLandmark,
				Landmark.LName,
				Room.RNumber,
				Room.RDateCreated,
				Room.RStatus,
				ROW_NUMBER() OVER (ORDER BY Room.RNumber) AS "RowNumber"
				
				
				FROM Room

				INNER JOIN Landmark
				ON Room.idLandmark = Landmark.idLandmark				
		
				WHERE Room.RNumber LIKE ? AND Landmark.idLandmark = ?
				)

				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $getbuilding);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->bindparam(4, $offset);
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
			$result_ID = htmlspecialchars($list_row['idRoom']);
			$result_CODE = htmlspecialchars($list_row['RCode']);
			$result_IDLANDMARK = htmlspecialchars($list_row['idLandmark']);
			$result_LANDMARK = htmlspecialchars($list_row['LName']);
			$result_ROOMNO = htmlspecialchars($list_row['RNumber']);
			$result_DATECREATED = htmlspecialchars($list_row['RDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['RStatus']);
		

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addRoomList("<?php echo $result_ID ?>","<?php echo $result_IDLANDMARK ?>","<?php echo $result_CODE ?>","<?php echo $result_LANDMARK ?>","<?php echo $result_ROOMNO ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	