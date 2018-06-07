<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		
		$refundDate = trim($_POST['refundDate']);
		//Changing the format of the date from yyyy-mm-dd to mm-dd-yyyy
			$refundDate = strtotime($refundDate);
			$refundDate = date('M d Y', $refundDate);
		$refundBranch = trim($_POST['refundBranch']);
		$refundReceipt = trim($_POST['refundReceipt']);
		$refundName = trim($_POST['refundName']);
		$refundProduct = trim($_POST['refundProduct']);
		$refundQuantity = trim($_POST['refundQuantity']);
		$refundPrice = trim($_POST['refundPrice']);
		$refundReason = trim($_POST['refundReason']);
		
		$refund_Date = strip_tags($refundDate);
		$refund_Branch = strip_tags($refundBranch);
		$refund_Receipt = strip_tags($refundReceipt);
		$refund_Name = strip_tags($refundName);
		$refund_Product = strip_tags($refundProduct);
		$refund_Quantity = strip_tags($refundQuantity);
		$refund_Price = strip_tags($refundPrice);
		$refund_Reason = strip_tags($refundReason);
		
		$query = "INSERT INTO refund (refundDate,refundBranch,refundReceipt,refundName,refundProduct,refundQuantity,refundPrice,refundReason
					) VALUES (:refundDate, :refundBranch, :refundReceipt, :refundName, :refundProduct, :refundQuantity, :refundPrice, :refundReason)";
					//REFUND WALA PA
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':refundDate', $refund_Date);
		$stmt->bindParam(':refundBranch', $refund_Branch);
		$stmt->bindParam(':refundReceipt', $refund_Receipt);
		$stmt->bindParam(':refundName', $refund_Name);
		$stmt->bindParam(':refundProduct', $refund_Product);
		$stmt->bindParam(':refundQuantity', $refund_Quantity);
		$stmt->bindParam(':refundPrice', $refund_Price);
		$stmt->bindParam(':refundReason', $refund_Reason);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully refunded';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not refund, try again later';
        }	
	}
	
	echo json_encode($response);