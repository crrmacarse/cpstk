<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);
	

	$total_count = $db->connection->query('SELECT COUNT(*) FROM Landmark')->fetchColumn(); 
	$total_page = ceil($total_count/10);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = ($page * 10) + 10;
	$limit =  ($page * 10) - 10;

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


	<div class="modal fade" id="addLandMarkForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Subject Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="addLandmarkFormTITLe">Add a Landmark / Building</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addLandMarkForm" class="form-horizontal" action="library/form/frmAddLandmarks.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Landmark Abbreviation</span>
									  </div>
									  <input id = "addLandMarkForm_NAMEABBR" name = "NAMEABBR" type="input" class="form-control" placeholder="Landmark Abbreviation" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Landmark Name</span>
									  </div>
									  <input id = "addLandMarkForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Landmark Name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Description</span>
									  </div>
									  <textarea id = "addLandMarkForm_DESCRIPTION" name = "DESCRIPTION" class="form-control" placeholder="Subject Description" aria-describedby="sizing-addon2" required maxlength="180"></textarea>
								</div>		
						</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addLandMarkForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteLandmarkForm_MODAL" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	           <div class="modal-header">
					<h5>Delete Landmark</h5>
				</div>
	            <form id="deleteLandmarkForm" method="post" action="library/form/frmDeleteLandmarks.php">
	                <div class="modal-body">
	                    <div><input type="text" id="deleteLandmarkForm_ID" name="ID" style="display: none;"></div>
	                    <p>Do you want to delete this record?</p>
	                    <table class="table">
	                        <thead>
	                        <tr>
	                            <td></td>
	                            <td><b>Landmark Details</b></td>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td>Landmark ABBR: </td>
	                            <td id="deleteLandmarkForm_NAMEABBR"></td>
	                        </tr>
	                        <tr>
	                            <td>Landmark Name: </td>
	                            <td id="deleteLandmarkForm_NAME"></td>
	                        </tr>
	                        <tr>
	                            <td>Description: </td>
	                            <td id="deleteLandmarkForm_DESCRIPTION"></td>
	                        </tr>
	                        <tr>
	                            <td>Date Created: </td>
	                            <td id="deleteLandmarkForm_DATECREATED"></td>
	                        </tr>
	                        <tr>
	                            <td>Status:</td>
	                            <td id="deleteLandmarkForm_STATUS"></td>
	                        </tr>	
							</tbody>
	                    </table>
	                </div>
	                <div class="modal-footer">
						<button type="submit" id="deleteLandmarkForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
	                   
	                </div>
	            </form>
	        </div>
	    	</div>
		</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Landmark & Building Management</h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <!-- <a href = "#" data-target = "#addLandMarkForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add a Landmark / Building</a> -->
						    <a title="Adding of Landmark is Disabled ATM(You can ONLY Add Landmark in the Desktop Application)" class = "dropdown-item"data-toggle = "modal">Add a Landmark / Building</a>
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
				  <th scope="col">Landmark ABBR</th>
				  <th scope="col">Landmark Name</th>
				  <th scope="col">Description</th>
				  <th scope="col">Rooms</th>
				  <th scope="col">Status</th>
				</tr>
			  </thead>
			  <tbody id = "landmarkList">
			  </tbody>
			</table>			
		</div>

		<div class = "row top-buffer justify-content-md-center">
		 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-landmarks.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-landmarks.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-landmarks.php?search=<?php echo $trimmedsearch; ?>&page=<?php echo $page + 1; ?>" >Next</a>
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
				window.location.href='manage-landmarks.php?search='+searchValue;
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
        landmarkList: document.getElementById('landmarkList')
    };

    var ALForm = {
    	form: document.getElementById('addLandMarkForm'),
    	nameabbr: document.getElementById('addLandMarkForm_NAMEABBR'),
    	name: document.getElementById('addLandMarkForm_NAME'),
    	description: document.getElementById('addLandMarkForm_DESCRIPTION'),
    	modal: '#addLandMarkForm_MODAL',
    	submit: '#addLandMarkForm_SUBMIT'
    }

	ALForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ALForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ALForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ALForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ALForm.form.reset();
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


	var DLForm = {
	    form: document.getElementById('deleteLandmarkForm'),
	    modal: document.getElementById('deleteLandmarkForm_MODAL'),
	    id: document.getElementById('deleteLandmarkForm_ID'),
	    name: document.getElementById('deleteLandmarkForm_NAME'),
	    nameabbr: document.getElementById('deleteLandmarkForm_NAMEABBR'),
	    description: document.getElementById('deleteLandmarkForm_DESCRIPTION'),
	    datecreated: document.getElementById('deleteLandmarkForm_DATECREATED'),
	    status: document.getElementById('deleteLandmarkForm_STATUS'),
	    submit: document.getElementById('deleteLandmarkForm_SUBMIT')		
	}


	$(DLForm.form).on('submit', function (e) {
        var id = DLForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DLForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {

                $(DLForm.submit).button('reset');
				deleteLandmark(id);
				DLForm.form.reset();
				$(DLForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	
	function deleteLandmark(id) {
        $('#landmark_' + id).remove();
    }

    function openDeleteLandmarkModal(id) {
    	DLForm.id.value = id;
    	DLForm.nameabbr.innerHTML = document.getElementById('landmark_NAMEABBR_'+id).innerHTML;
    	DLForm.name.innerHTML = document.getElementById('landmark_NAME_'+id).innerHTML;
    	DLForm.description.innerHTML = document.getElementById('landmark_DESCRIPTION_'+id).innerHTML;
    	DLForm.datecreated.innerHTML = document.getElementById('landmark_DATECREATED_'+id).innerHTML;
    	DLForm.status.innerHTML = document.getElementById('landmark_STATUS_'+id).innerHTML;
        $(DLForm.modal).modal('show');
    }

    function viewRoom(id){
    	window.location.href='manage-room.php?building='+id;
    }


    function addLandmarkList(id, idvertex, name, nameabbr, description, addedby, datecreated, status)
    {
    	PageComponent.landmarkList.innerHTML = PageComponent.landmarkList.innerHTML +
    		'<thead>'+
			'<tr id = "landmark_'+ id +'">'+
			'	<td scope = "col" id = "landmark_NAMEABBR_' + id +'">' + nameabbr + '</td>'+
			'	<td scope = "col" id = "landmark_NAME_' + id + '">' + name + '</td>'+
			'	<td scope = "col" id = "landmark_DESCRIPTION_' + id + '">' + description + '</td>'+
			'	<td></button><button id="landmark_VIEWROOM_' + id + '" value="' + id + '" class="btn btn-primary ml-1" onClick = "viewRoom(\'' + id + '\')" role = "button" dal(' + id + ')">View Rooms</button></td>'+
			'	<td scope = "col" id = "landmark_STATUS_' + id + '">' + status + '</td>'+
			'	<td scope = "col" id = "landmark_IDVERTEX_' + id + '" hidden>' + idvertex + '</td>'+
			'	<td scope = "col" id = "landmark_ADDEDBY_' + id + '" hidden>' + addedby + '</td>'+
			'	<td scope = "col" id = "landmark_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = "
				WITH OrderedList AS
				(
				SELECT

				Landmark.idLandmark,
				Landmark.idVertex,
				Landmark.LName,
				Landmark.LNameAbbr,
				Landmark.LDescription,
				Landmark.LDateCreated,
				Landmark.LAddedBy,
				Landmark.Status,	
				ROW_NUMBER() OVER (ORDER BY Landmark.LName) AS 'RowNumber'
				
				FROM Landmark

				
				WHERE (Landmark.LDescription LIKE ? OR Landmark.LNameAbbr LIKE ?) AND Landmark.LName <> '' AND Landmark.LNameAbbr <> ''
				)
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN ? AND ?";
	
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
			'<td id = "landmark_TITLE_">No Landmark Found</td>'+
			'<tr>';

			$("#landmarkList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idLandmark']);
			$result_IDVERTEX = htmlspecialchars($list_row['idVertex']);
			$result_NAME = htmlspecialchars($list_row['LName']);
			$result_NAMEABBR = htmlspecialchars($list_row['LNameAbbr']);
			$result_DESCRIPTION = htmlspecialchars($list_row['LDescription']);
			$result_ADDEDBY = htmlspecialchars($list_row['LAddedBy']);
			$result_DATECREATED = htmlspecialchars($list_row['LDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['Status']);			

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addLandmarkList("<?php echo $result_ID ?>","<?php echo $result_IDVERTEX ?>","<?php echo $result_NAME ?>","<?php echo $result_NAMEABBR ?>","<?php echo $result_DESCRIPTION ?>","<?php echo $result_ADDEDBY ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS?>");
		<?php 
		}			
	}
	?>


</script>	