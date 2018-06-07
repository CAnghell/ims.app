<?php
	$dbservername = "localhost";
	$dbusername = "root";
	$dbpassword = "";
	$dbname = "cuna_hardware";
	
	// Create connection
	$con = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
	// Check connection
	if ($con->connect_error) {
		die("Connection failed: " . $mysqli->connect_error);
	} 
	
	// Create connection for modal AJAX
	try {
		$DBcon = new PDO("mysql:host=".$dbservername.";dbname=".$dbname,$dbusername,$dbpassword);
	} catch(PDOException $e){ 
		die($e->getMessage());
	}
?>