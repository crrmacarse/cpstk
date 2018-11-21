<?php

// simple database connection

class db 
{
	public $server = "LANGGA\SQLEXPRESS";
	public $username = "";
	public $password = "";
	public $database = "CPUSmartInfoKiosk";
	function __construct()
	{
		try
		{
			$this->connection = new PDO("sqlsrv:Server=$this->server; Database=$this->database","","");

			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e)
		{
			die(print_r($e->getMessage()));
		}

	}
}

?>

