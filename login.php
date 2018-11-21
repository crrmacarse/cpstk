<?php

  session_start();
  include('library/form/connection.php');
  $db = new db();
  
  if(isset($_SESSION['CURRENT_ID']))
    {
      header('Location: dms.php');
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
		
	</head>


<body>
	<div class="container">
			  <div class="row justify-content-md-center">
  				<div class="col-md-auto top-buffer">
    				<div class = "card text-center">
      				<div class = "card-header">
      					 CPU Smart Touch Info Kiosk Data Management System</div>
      				<div class ="card-body">
      					<img src="img/cpu-logo.png" class="img-fluid mx-auto d-block" alt="...">
      		     	<form action="library/form/loginForm.php" method="post" id="loginForm">				
                  <div id = "loginForm_msgbox" tabindex="0" style = "color: black"></div>
                  <br />
      						<div class="input-group mb-3 top-buffer">
      						  <div class="input-group-prepend">
      							<span class="input-group-text" id="basic-addon1">Username</span>
      						  </div>
      						  <input type="text" id="loginForm_USERNAME" class="form-control" name="USERNAME" aria-label="Username" aria-describedby="basic-addon1" required autofocus />
      						</div>
      						<div class="input-group mb-3 top	">
      						  <div class="input-group-prepend">
      							<span class="input-group-text" id="basic-addon1">Password</span>
      						  </div>
      						  <input type="password" id="loginForm_PASSWORD" name="PASSWORD" class="form-control"  aria-label="Password" aria-describedby="basic-addon1" required />
      							</div>
      						<div class = "float-right form-group" style = "margin-top: 10px;">
      							<div class = "controls">
      								<input type = "submit" id="loginForm_SUBMIT" class = "btn btn-primary" data-loading-text="Signing in..." value = "Sign-in" />
      								<a href = "index.php" class = "btn btn-secondary">Cancel</a>
      							</div>
      						</div>
      					</form>
      				</div>

      				<div class = "card-footer text-muted">
      					Copyright Fourty Six Solutions<br> &copy; All Rights Reserved 2018&trade;
      				</div>	
    				</div>
  				</div>  
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

<script type="text/javascript">
	
  $( document ).ready( function(  ) { 
 var loginForm = {
        form: document.getElementById('loginForm'),
        username: document.getElementById('loginForm_USERNAME'),
        password: document.getElementById('loginForm_PASSWORD'),
        msgbox: 'loginForm_msgbox',
        submit: document.getElementById('loginForm_SUBMIT')
    };

  $(loginForm.form).on('submit', function (e) {
        var username = loginForm.password.value;
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(loginForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
            $(loginForm.submit).button('reset');
            var server_message = data.trim();
            if(!isWhitespace(GetSuccessMsg(server_message)))
            {
              loginForm.form.reset();
              validateAccess(GetSuccessMsg(server_message));

            }
            else if(!isWhitespace(GetErrorMsg(server_message)))
            {
              alert(GetErrorMsg(data));
              clearPassword();
            }
            else if(!isWhitespace(GetWarningMsg(server_message)))
            {
              alert(GetWarningMsg(data));
              clearPassword();
            }
            else if(!isWhitespace(GetServerMsg(server_message)))
            {
              alert(GetServerMsg(data));
              clearAllField();
            }
            else
            {
              alert('Oh Snap! There is a problem with the server or your connection.');
            }
                }
            });
        }); 
  
  // textbox clear JS
  function clearAllField()
  {
    $("#loginForm_USERNAME").val('');
    $("#loginForm_PASSWORD").val('');
  }
  
  function clearPassword()
  {
    $("#loginForm_PASSWORD").val('');
  }
  
  // moves user to other page depending on group id
  function validateAccess(id)
  {
      switch (id)
        {
          case 'admin':
            window.location = 'dms.php';
            break;
          case 'secretary':
            window.location = 'dms.php';
            break;
          case 'schedcoord':
            window.location = 'dms.php';
            break;            
          case 'infocenter':
            window.location = 'dms.php';
            break;  
          case 'teacher':
            window.location = 'dms.php';
            break;
           Default:
             window.location = 'index.php';
             break;
        }
  }
  } );
    
</script>



</script>