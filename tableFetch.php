<?php
	require_once 'server.php';

	if(isset($_POST["product_id"])){
		$query = "SELECT * FROM cuna_hardware.inventory WHERE productId = '".$_POST["product_id"]."'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result);
		echo json_encode($row);
	}
	
	if(isset($_POST["restock_id"])){
		$query = "SELECT * FROM cuna_hardware.inventory WHERE productId = '".$_POST["restock_id"]."'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result);
		echo json_encode($row);
	}
	
	if(isset($_POST["delete_id"])){
		$query = "SELECT * FROM cuna_hardware.inventory WHERE productId = '".$_POST["delete_id"]."'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result);
		echo json_encode($row);
	}
?>