<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

	$search = isset($_GET['search']) ? '%'.$_GET['search'].'%' : '%%';
	$trimmedsearch = str_replace('%', '', $search);


	$total_count = $db->connection->query('SELECT COUNT(*) FROM Term')->fetchColumn(); 

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

	<div class="modal fade" id="addTermForm_MODAL" tabindex="-1" role="dialog" aria-labelledby="Term Modal" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalTitle">Add Examination Term</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	         <form id = "addTermForm" class="form-horizontal" action="library/form/frmAddTerm.php" method="post" 
	         name="upload_excel" enctype="multipart/form-data">
	         <div class="panel panel-default">
							<div class="panel-body">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="inputGroup-sizing-sm">Examination Term Name</span>
									  </div>
									  <input id = "addTermForm_NAME" name = "NAME" type="input" class="form-control" placeholder="Exam Term Name" aria-describedby="sizing-addon2" required>
								</div>
								<br />
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
		        <input type="submit" id = "addTermForm_SUBMIT" class="btn btn-success" name = "ADD" data-loading-text = "Adding.." value = "Add">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
	  </form>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="deleteTermForm_MODAL" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
				<h5>Delete Faculty Type</h5>
			</div>
            <form id="deleteTermForm" method="post" action="library/form/frmDeleteTerm.php">
                <div class="modal-body">
                    <div><input type="text" id="deleteTermForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Term Details</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Term Name: </td>
                            <td id="deleteTermForm_NAME"></td>
                        </tr>
                        <tr>
                            <td>Date Created: </td>
                            <td id="deleteTermForm_DATECREATED"></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td id="deleteTermForm_STATUS"></td>
                        </tr>	
						</tbody>
                    </table>
                </div>
                <div class="modal-footer">
					<button type="submit" id="deleteTermForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"></span> Close</button>
                   
                </div>
            </form>
        </div>
    	</div>
	</div>

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-6">
					<h1 class = "h2">Examination Term Management</h1>
					<p class = "text-muted">Manage Examination Term, user can add examination term, Example: Prelim, Midterm, Finals etc.</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "manage-repositories.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<span class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Actions
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a href = "#" data-target = "#addTermForm_MODAL" class = "dropdown-item"data-toggle = "modal">Add a Term</a>
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
				  <th scope="col">Term Name</th>
				  <th scope="col">Status</th>
	  			  <th scope="col">Actions</th>
				</tr>
			  </thead>
			  <tbody id = "termList">
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
				window.location.href='manage-term.php?search='+searchValue;
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
        termList: document.getElementById('termList')
    };

    var ATForm = {
    	form: document.getElementById('addTermForm'),
    	name: document.getElementById('addTermForm_NAME'),
    	modal: '#addTermForm_MODAL',
    	submit: '#addTermForm_SUBMIT'
    }

	ATForm.form.onsubmit = function (e)
	{
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ATForm.submit).button('loading');
			},
			
			uploadProgress:function(event,position,total,percentComplete)
			{
				
			},
			success:function(data)
			{
				$(ATForm.submit).button('reset');
				var server_message = data.trim();	
				if(!isWhitespace(GetSuccessMsg(server_message)))
					{
						$(ATForm.modal).modal('hide');
						alert('Added Succesfully');
						window.location.reload(false); 						
						ATForm.form.reset();
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

	var DTForm = {
		form: document.getElementById('deleteTermForm'),
		id: document.getElementById('deleteTermForm_ID'),
		name: document.getElementById('deleteTermForm_NAME'),
		datecreated: document.getElementById('deleteTermForm_DATECREATED'),
		status: document.getElementById('deleteTermForm_STATUS'),
		modal: document.getElementById('deleteTermForm_MODAL'),
		submit: document.getElementById('deleteTermForm_SUBMIT')
	}

	function deleteTerm(id) {
        $('#term_' + id).remove();
    }

    function openDeleteTermModal(id) {
    	DTForm.id.value = id;
    	DTForm.name.innerHTML = document.getElementById('term_NAME_'+id).innerHTML;
    	DTForm.datecreated.innerHTML = document.getElementById('term_DATECREATED_'+id).innerHTML;
    	DTForm.status.innerHTML = document.getElementById('term_STATUS_'+id).innerHTML;
       $(DTForm.modal).modal('show');
    }

	$(DTForm.form).on('submit', function (e) {
        var id = DTForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DTForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DTForm.submit).button('reset');
				deleteTerm(id);
				DTForm.form.reset();
				$(DTForm.modal).modal('hide');
				alert('Succesfully Deleted');
            }
        });
    });
	


    function addTermList(id, name, datecreated, status)
    {
    	PageComponent.termList.innerHTML = PageComponent.termList.innerHTML +
    		'<thead>'+
			'<tr id = "term_'+ id +'">'+
			'	<td scope = "col" id = "term_NAME_' + id +'">' + name + '</td>'+
			'	<td scope = "col" id = "term_DATECREATED_' + id + '" hidden>' + datecreated + '</td>'+
			'	<td scope = "col" id = "term_STATUS_' + id + '">' + status + '</td>'+
			' 	<td><div class = "btn-group" role = "group"><button id="term_BTNUPDATE_' + id + '" value="' + id + '" data-target = "#updateTermForm_MODAL" data-toggle = "modal" onclick = "updateTermFill(\'' + id + '\')"class="btn btn-primary"><i class="far fa-edit"></i></button><button id="term_BTNDELETE_' + id + '" value="' + id + '" class="btn btn-warning ml-1" role = "button" onclick="openDeleteTermModal(' + id + ')"><i class="far fa-trash-alt"></i></button></div></td>'+
			'</tr>'+	
			'</thead>';
    }

	<?php 

	$list_sql = '
				WITH OrderedList AS
				(
				SELECT 
				
				Term.idTerm,
				Term.TName,
				Term.TDateCreated,
				Term.TStatus,
				ROW_NUMBER() OVER (ORDER BY Term.TName) AS "RowNumber"
				
				FROM Term
				
				WHERE Term.TName LIKE ?
				)
				SELECT * 
				FROM OrderedList 
				
				WHERE RowNumber BETWEEN ? AND ?';
	
	$list_getResult = $db->connection->prepare($list_sql);
	$list_getResult->bindparam(1, $search);
	$list_getResult->bindparam(2, $offset);
	$list_getResult->bindparam(3, $limit);
	$list_getResult->execute();
	$list_count = $list_getResult->RowCount();

	if($list_count > 0){
		?>

			var content = '<tr>'+
			'<tr>'+
			'<td id = "term_TITLE_">No Subjects Found</td>'+
			'<tr>';

			$("#termList").append(content);

		<?php
	}
	else
	{

		foreach($list_getResult as $list_row)
		{

			$result_ID = htmlspecialchars($list_row['idTerm']);
			$result_TERMNAME = htmlspecialchars($list_row['TName']);
			$result_DATECREATED = htmlspecialchars($list_row['TDateCreated']);
			$result_STATUS = htmlspecialchars($list_row['TStatus']);			

		if($result_STATUS == True)
		{
			$result_STATUS = 'Active';
		}
		else
		{
			$result_STATUS = 'Inactive';
		}
	?>

	addTermList("<?php echo $result_ID ?>","<?php echo $result_TERMNAME ?>","<?php echo $result_DATECREATED ?>","<?php echo $result_STATUS ?>");
		<?php 
		}			
	}
	?>


</script>	