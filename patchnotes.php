<?php

	session_start();
	include('library/form/connection.php');
	include('library/functions/functions.php');
	$db = new db();

	if(!isset($_SESSION['CURRENT_ID']))
	{
		echo '<html style="awidth: 100%; height: 100%; margin: 0px; padding: 0px;"> <head> <title>Page not Availble</title><link rel="icon" href="img/favicon.ico"></head> <body style="width: 100%; height: 100%; margin: 0px; padding: 0px; text-align: center; background-color: #454551; color: lightgray;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table;"> <div style="width: 100%; height: 100%; margin: 0px; padding: 0px; vertical-align: middle; display: table-cell;"> 

            <h1><a href = "https://www.google.com.ph/search?q=get+a+life&rlz=1C1GCEA_enPH782PH782&oq=get+a+life&aqs=chrome..69i57.1191j0j1&sourceid=chrome&ie=UTF-8" style = "font-size: 50px; color: lightgray; text-decoration:none;"> 403 Forbidden</a></h1> <h4>Un-authorized Access</h4> </div> </div> </body> </html>';
	        exit;
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
	

	<div class="container">
			<div class = "row top-buffer">
				<div class = "col-lg-8 col-sm-12">
					<h4 class = "mt-2">CPU Smart Touch Information Kiosk Patch Notes</h4>
					<p class = "mt-0">Beta Version</p>
				</div>
				<div class = "col-lg-4 col-sm-12">
					<div class = "float-right button-manage-group">
						<a href = "dms.php" class="btn btn-info"><i class="fas fa-arrow-left"></i>&nbsp; Return</a>
						<a href = "mailto:totopaulmanares@yahoo.com?Subject=CPU%20Touch%20Information%20Kiosk%20Concerns" class="btn btn-primary">Need Assistance?</a>
					</div>
				</div>
			</div>

			<div class = "row top-buffer">

				<div class = "col-lg-12 col-sm-12">
					<p class = "blockquote mb-1"><small>v1</small> &nbsp; June 06, 2018</p>
					<ul class = "ml-3 mt-2">
						<li>
							<p class = "h6">Fresh Release</p>	
						</li>
					</ul>
				</div>
								
			</div>

		<?php include('library/html/footer.html'); ?>

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

