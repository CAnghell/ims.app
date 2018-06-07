<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$sellCSTrans = trim($_POST['sellCSTrans']);
		$sellCSDate = trim($_POST['sellCSDate']);
			$sellCSDate = strtotime($sellCSDate);
			$sellCSDate = date('M d Y', $sellCSDate);
		$sellCSBranch = trim($_POST['sellCSBranch']);
		$sellCSReceipt = trim($_POST['sellCSReceipt']);
		$sellCSName = trim($_POST['sellCSName']);
		$sellCSStatus = trim($_POST['sellCSStatus']);
		$sellCSProd = trim($_POST['sellCSProd']);
		$sellCSPrice = trim($_POST['sellCSPrice']);
		$sellCSQuantity = trim($_POST['sellCSQuantity']);
		$sellCSTotal = trim($_POST['sellCSTotal']);
		
		$sell_CSTrans = strip_tags($sellCSTrans);
		$sell_CSDate = strip_tags($sellCSDate);
		$sell_CSBranch = strip_tags($sellCSBranch);
		$sell_CSReceipt = strip_tags($sellCSReceipt);
		$sell_CSName = strip_tags($sellCSName);
		$sell_CSStatus = strip_tags($sellCSStatus);
		$sell_CSProd = strip_tags($sellCSProd);
		$sell_CSPrice = strip_tags($sellCSPrice);
		$sell_CSQuantity = strip_tags($sellCSQuantity);
		$sell_CSTotal = strip_tags($sellCSTotal);
		
		
		$query = "INSERT INTO product_out (prodOutTranType,prodOutDate,prodOutBranch,prodOutReceipt,prodOutName,prodOutStatus,prodOutProduct,prodOutPrice,prodOutQuantity,prodOutTotalPrice
					) VALUES (:sellCSTrans, :sellCSDate, :sellCSBranch, :sellCSReceipt, :sellCSName, :sellCSStatus, :sellCSProd, :sellCSPrice, :sellCSQuantity, :sellCSTotal)";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':sellCSTrans', $sell_CSTrans);
		$stmt->bindParam(':sellCSDate', $sell_CSDate);
		$stmt->bindParam(':sellCSBranch', $sell_CSBranch);
		$stmt->bindParam(':sellCSReceipt', $sell_CSReceipt);
		$stmt->bindParam(':sellCSName', $sell_CSName);
		$stmt->bindParam(':sellCSStatus', $sell_CSStatus);
		$stmt->bindParam(':sellCSProd', $sell_CSProd);
		$stmt->bindParam(':sellCSPrice', $sell_CSPrice);
		$stmt->bindParam(':sellCSQuantity', $sell_CSQuantity);
		$stmt->bindParam(':sellCSTotal', $sell_CSTotal);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully Sold';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not create, try again later';
        }	
	}
	
	echo json_encode($response);