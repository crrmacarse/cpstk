<?php
session_start();
include('library/form/connection.php');
$db = new db();

  	if(isset($_SESSION['CURRENT_ID']))
	{
	    $fullname = $_SESSION['CURRENT_FULLNAME'];
	    $usertype = $_SESSION['CURRENT_FACULTYTYPE'];
	}
	else {
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1><h4>Unauthorized access.</h4> </div> </div> </body> </html>';
        exit;
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
		
		  <script src = "library/js/jquery-3.3.1.min.js"></script>
		  <script src = "library/js/jquery.form.js"></script>
		  <script src = "library/js/app.js"></script>	
		  <script src="library/js/messagealert.js"></script>
		  <script src="library/js/bootstrap.min.js"></script>
		  <script src="library/js/popper.js"></script>
		  <script src="library/js/all.js"></script>
		
	</head>

<body>

<body>
	
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="index.php">CPU STIK</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	      <li class="nav-item">
	        <a class="nav-link" href="#manage-grades">Grades</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="#manage-class-schedule"><span class="fa fa-calendar"></span> Schedule</a>
	      </li>
	      <li class="nav-item">
	      	<a class="nav-link" href="#manage-exam-schedule"><span class="fa fa-calendar"></span> Exam Schedule</a>
	      </li>
	      <li class="nav-item">
	         <a class="nav-link" href="#manage-events">Events</a>
	      </li>
	      <li class="nav-item">
	         <a class="nav-link" href="#manage-repositories"><span class="fa fa-users"></span> Accounts</a>
	      </li>
	      <li class = "nav-item">
			  <a class="nav-link disabled" href="#manage-navigation"><span class="fa fa-map-marker"></span> Navigation</a>
	      </li>
	    </ul>
	    <form class="form-inline my-2 my-lg-0">
	      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
	      <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
	    </form>
	  </div>
	</nav>
	
	<div class = "row top-buffer ">		
	<div class="col-lg-12">
		<div class="jumbotron">
	  	<h1 class="display-4">Welcome, <?php echo $fullname ?></h1>
			<p class="lead"><?php echo $usertype ?></p>
		  <hr class="my-4">
		  <p>For system concerns, please contact paulmanares@gmail.com.</p>
			<u><a href="patchnotes.php"><small class="text-muted">Read Patchnotes</small></a>
          </u>
			<div class="float-right">
				<a href = "mailto:totopaulmanares@yahoo.com?Subject=CPU%20Touch%20Information%20Kiosk%20Concerns" class="btn btn-info">Need Help?</a>
				<a href = "logout.php" class="btn btn-dark">Sign-out</a>
			</div>
				</div>
	</div>

	</div>
	

	<?php
	if($_SESSION['CURRENT_FACULTYTYPE'] == "Administrator")
	{
		include('library/html/admin.html');
	}
	elseif($_SESSION['CURRENT_FACULTYTYPE'] == "Secretary")
	{
		include('library/html/secretary.html');
	}
	elseif($_SESSION['CURRENT_FACULTYTYPE'] == "Information Center")
	{
		include('library/html/infocenter.html');		
	}
	elseif($_SESSION['CURRENT_FACULTYTYPE'] == "Schedule Coordinator")
	{
		include('library/html/schedcoord.html');		
	}
	?>
		
	
	<?php include('library/html/footer.html'); ?>
					
	</div>
	
</div>
	
<script src = "library/js/jquery-3.3.1.min.js"></script>
  <script src = "library/js/jquery.form.js"></script>
  <script src = "library/js/app.js"></script>	
  <script src="library/js/messagealert.js"></script>
  <script src="library/js/messagealert.js"></script>
  <script src="library/js/bootstrap.min.js"></script>
  <script src="library/js/popper.js"></script>
  <script src="library/js/all.js"></script>
</body>
	
	
</html>
	