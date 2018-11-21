<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	include('library/functions/checkSession.php');

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
					<h1 class = "h1">Repository Management</h1>
					<p class = "text-muted">Manage Faculties, Students, Subjects, Faculty type, Landmarks, Rooms, Colleges, School Year and Examination</p>
				</div>
				
				<div class = "col-lg-6">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>	
					</div>
				</div>
			</div>

		<div class = "row top-buffer">	
			<div class = "col-lg-12 col-sm-12">
			<div class="card-deck">
				<div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Faculties</h5>
					  <p class="card-text mb-5">Manages Faculty, Import New Facuty.</p>
						<a href = "manage-faculty.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
				  <div class="card features" id = "manage-exam-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Students</h5>
					  <p class="card-text mb-5">Manages the Data Entry for Student Import.</p>
						<a href = "manage-students.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
				  <div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Subjects</h5>
					  <p class="card-text mb-5">Manages Subjects for the user.</p>
						<a href = "manage-subjects.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
			  	<div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Faculty Type</h5>
					  <p class="card-text mb-5">Manage Faculties for the User.</p>
						<a href = "manage-faculty-type.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
			 	 </div>
				</div>
			</div>
		</div>

		<div class = "row top-buffer">	
			<div class = "col-lg-12 col-sm-12">
			<div class="card-deck">
				<div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Landmarks & Room</h5>
					  <p class="card-text mb-5">Manages Landmarks and Room for the user, view existing Landmarks and Rooms.</p>
						<a href = "manage-landmarks.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
				<div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage College, Departments, & Course</h5>
					  <p class="card-text mb-5">Manages College, Departments and Course for the user.</p>
						<a href = "manage-college.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
				  <div class="card features" id = "manage-exam-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage School Year & Period</h5>
					  <p class="card-text mb-5">Manage School School Year for the user.</p>
						<a href = "manage-school-year.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
				  <div class="card features" id = "manage-class-schedule">
					<img class="card-img-top" src="img/dummy.png" alt="Card image cap">
					<div class="card-body">
					  <h5 class="card-title">Manage Examination Term</h5>
					  <p class="card-text mb-5">Manages The Examination Schedule.</p>
						<a href = "manage-term.php" class = "btn btn-secondary bottom-right top-buffer">Manage</a>
					</div>
				  </div>
			

				</div>
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

