<?php
	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	if(!isset($_SESSION['USER_USERNAME']))
	{
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>Un-authorized Access</h4> </div> </div> </body> </html>';
        exit;
	}

	$search = isset($_GET['search']) ? $_GET['search'] : '';
	$limit = 10;


	$total_count = $db->connection->query('SELECT COUNT(*) FROM road')->fetchColumn(); 
	$total_page = ceil($total_count/$limit);

	$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
	$offset = ($page * $limit) - $limit;

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
	<!-- Add Events Modal -->
	<div class="modal fade" id="addRoadForm_MODAL" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Add Road</h5>
				</div>
				<form id="addEventForm" method="post" action="library/form/frmAddEvent.php">
					<div class="modal-body">
						<!-- Infomation -->
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Road Name</span>
									  </div>
									  <input id = "updateGradeForm_GRADE" name = "GRADE" type="number" class="form-control" placeholder="GRADE" aria-describedby="sizing-addon2" required>
								</div>
								<br />								
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Event Description</span>
									  </div>
									  <textarea id = "updateGradeForm_REMARKS" maxlength = "180" name = "REMARKS" style = "padding-bottom: 150px;" type="text" class="form-control" placeholder="Remarks..." aria-describedby="sizing-addon2" required></textarea>
								</div>
								<br />
								<div class="form-group">
								  <select class="form-control" name = "STATUS" id="updateGradeForm_STATUS">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
					<!-- Submission -->
					<div class="modal-footer">
						<button type="submit" id="updateGradeForm_SUBMIT" class="btn btn-primary" data-loading-text="Updating..."> Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<body>
	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h1">Road Management</h1>
					<p class = "text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc non mauris vitae dui lacinia cursus eget eu urna.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addRoadForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add Road</a>
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
				<input type = "text" id = "searchBar" class = "form-control" placeholder="Search">
				&nbsp;
				<button id = "gradeSearch" class = "btn btn-success">Search</button>
				</div>
			</div>
		</div>
		
		<div class = "row top-buffer">
				<table class="table table-striped">
				  <thead>
					<tr>
					  <th scope="col">Road Name</th>
					  <th scope="col">X1</th>
					  <th scope="col">Y1</th>
					  <th scope="col">X2</th>	
					  <th scope="col">Y2</th>
					  <th scope="col">IsBuildingConnected</th>
					  <th scope="col">Building Code</th>
					  <th scope="col">Status</th>
					  <th scope="col">Actions</th>
					</tr>
				  </thead>
				  <tbody id = "roadList">
					
				  </tbody>
				</table>
			
		</div>
		<div class = "row top-buffer justify-content-md-center">
				 <div class="col-md-auto top-buffer">
			<nav aria-label="Page navigation example">
				  <ul class="pagination justify-content-center">
				    	<li class="page-item">
						  <a class="page-link <?php echo $disable_previous; ?>" style="<?php echo $disable_previous2; ?>" href="manage-road.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
						</li>
						<?php   	
						  	for($i = 1; $i <= $total_page; $i++)
							{
								?>
						  	<li id = "paginationActive<?php echo $i; ?>" class="page-item">
								<a class="page-link" href="manage-road.php?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
						    </li>
						  
						  <?php
							}
						  ?>
						<li class="page-item">
						  <a class = "page-link <?php echo $disable_next; ?>" style="<?php echo $disable_next2; ?>" href="manage-road.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" >Next</a>
						</li>		  </ul>
				</nav>
			</div>
			</div>

		<?php include('library/html/footer.html'); ?>
	 </div>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script src = "library/js/app.js"></script>	
  <script src="library/js/messagealert.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

</body>
	
	
</html>

<script>

$(document).ready(function(){
		$("#paginationActive<?php echo $page ?>").addClass("active");
		$("#gradeSearch").val('<?php echo $search; ?>');
		$("#gradeSearch").click(function(){
			this.value = '';
			 $( "#searchBar" ).click(function() {
			  jQuery('#gradeSearch').empty();
			  var searchValue = $("#gradeSearch").val().toLowerCase(); 
				window.location.href='manage-navigation.php?search='+searchValue;
				 
				});

				$('#gradeSearch').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#searchBar').click();//Trigger search button click event
				}
			});
		});
	});

	var PageComponent = {
        roadList: document.getElementById('roadList')
    };

    function fillDisplayModal(id)
	{
		var image = document.getElementById('event_IMAGE_'+id).innerHTML;
		
		document.getElementById('displayModal_IMAGESRC').innerHTML = "Image source: "+image;
		document.getElementById("displayModal_IMAGE").src = "img/events/"+image;
		$("#displayModal_MODAL").modal('show');
	}

	function addRoadList(id, rname, bcode, bname, binfo, rx1, ry1, rx2, ry2, isbuildingroad, datecreated, status)
	{

		PageComponent.roadList.innerHTML = PageComponent.roadList.innerHTML +
			'<thead>'+
			'<tr id = "road'+ id +'">'+
			'	<td scope = "col" id = "road_RName_' + id + '">' + rname + '</td>'+
			'	<td scope = "col" id = "road_RX1_' + id + '">' + rx1 + '</td>'+
			'	<td scope = "col" id = "road_RY1_' + id + '">' + ry1 + '</td>'+
			'	<td scope = "col" id = "road_RX2_' + id + '">' + rx2 + '</td>'+
			'	<td scope = "col" id = "road_RY2' + id + '">' + ry2 + '</td>'+
			'	<td scope = "col" id = "road_ISBUILDINGROAD' + id + '">' + isbuildingroad + '</td>'+ 
			'	<td scope = "col" id = "road_BCODE' + id + '">' + bcode + '</td>'+
			'	<td scope = "col" id = "road_STATUS_' + id + '">' + status + '</td>'+
			' 	<td><button id="exam_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateExamForm_MODAL" data-toggle = "modal" onclick = "updateExamFill(\'' + id + '\')"class="btn btn-primary">Update</button></td>'+
			'</tr>'+	
			'</thead>';
	}

	<?php 

	$list_sql = '		
			WITH OrderedList AS
				(
				SELECT 
				Road.idRoad,
				Road.RName,
				Building.BCode,
				Building.BName,
				Building.BInfo,
				Road.RX1,
				Road.RY1,
				Road.RX2,
				Road.RY2,
				RIsBuildingRoad,
				RDateCreated,
				RStatus,
				ROW_NUMBER() OVER (ORDER BY Road.RName) AS "RowNumber" 
				FROM Road
				INNER JOIN Building
				ON Road.idBuilding = Building.idBuilding
				)
				SELECT * 
				FROM OrderedList 
				WHERE RowNumber BETWEEN '. $offset.' AND '. $limit;
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "roadList_TITLE_">No Road Table Found</td>'+
			'<tr>';

			$("#roadList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{
			$result_ID = htmlspecialchars($list_row['idRoad']);
			$result_RName = htmlspecialchars($list_row['RName']);
			$result_BCODE = htmlspecialchars($list_row['BCode']);
			$result_BNAME = htmlspecialchars($list_row['BName']);
			$result_BINFO = htmlspecialchars($list_row['BInfo']);
			$result_RX1 = htmlspecialchars($list_row['RX1']);
			$result_RY1 = htmlspecialchars($list_row['RY1']);
			$result_RX2 = htmlspecialchars($list_row['RX2']);
			$result_RY2 = htmlspecialchars($list_row['RY2']);
			$result_RISBUILDINGROAD = htmlspecialchars($list_row['RIsBuildingRoad']);
			$result_RDATECREATED = htmlspecialchars($list_row['RDateCreated']);
			$result_RSTATUS = htmlspecialchars($list_row['RStatus']);
		if($result_RSTATUS == '1')
		{
			$result_RSTATUS = 'Active';
		}
		else
		{
			$result_RSTATUS = 'Inactive';
		}

		if($result_RISBUILDINGROAD == '1')
		{
			$result_RISBUILDINGROAD = 'True';
		}
		else
		{
			$result_RISBUILDINGROAD = 'False';
		}

	?>

	addRoadList("<?php echo $result_ID; ?>","<?php echo $result_RName; ?>","<?php echo $result_BCODE; ?>","<?php echo $result_BNAME; ?>","<?php echo $result_BINFO; ?>","<?php echo $result_RX1 ?>","<?php echo $result_RY1 ?>","<?php echo $result_RX2; ?>","<?php echo $result_RY2; ?>", "<?php echo $result_RISBUILDINGROAD; ?>","<?php echo $result_RDATECREATED; ?>","<?php echo $result_RSTATUS; ?>");
		
		<?php 
		}			
	}
	?>

</script>