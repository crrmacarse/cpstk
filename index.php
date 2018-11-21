<?php
	session_start();
	include ('library/form/connection.php');
	
?>


<!doctype html>
<html lang="en">
	
	<head>
	
	   	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>CPU Smart Touch Information Kiosk</title>

		<link href = "library/css/bootstrap.min.css" rel = " stylesheet">
		<link href = "library/css/mystyles.css" rel = "stylesheet">
		<link rel="icon" href="img/cpu-logo.png">
		
	</head>

<body>
	
	<div class="container ">
		<div class = "row top-buffer ">
			
		<div class="col-lg-12">
			
		  	<h1 class="display-4">Central Philippine University</h1>
		  	<p class="lead">
			 Smart Touch Information Kiosk Data Management System
			</p>
			  
		</div>

		<div class = "col-lg-12 top">
			<img src="img/background.png" class="img-fluid" alt="Responsive image">
		</div>

		<div class = "col-lg-12">

			  <hr class="my-4">
			<div class = "float-right">
				<a href = "login.php" class = "btn btn-primary">Sign-in</a>
			</div>
		</div>


	</div>
	
		<div id = "footer" class = "row justify-content-md-center top-buffer" >
		<div class = "col-md-auto">
			<small class = "text-muted">Copyright Fourty Six &copy;<br>All Rights Reserved 2018&trade;</small>
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
	