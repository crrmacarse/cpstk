<?php

require ('library/form/connection.php');

global $dbc;

$stmt = $dbc->$prepare("SELECT idUsername, UPassword FROM Users WHERE username = '".$_POST['username']."' && password='".$_POST['password'])."'");

$stmt->execute();

$row-> $stmt->rowCount();

if ($row > 0)
{
	echo 'correct'
}
else
{
	echo 'wrong'
}

?>