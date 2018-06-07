<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$sellSITrans = trim($_POST['sellSITrans']);
		$sellSIDate = trim($_POST['sellSIDate']);
			$sellSIDate = strtotime($sellSIDate);
			$sellSIDate = date('M d Y', $sellSIDate);
		$sellSIBranch = trim($_POST['sellSIBranch']);
		$sellSIReceipt = trim($_POST['sellSIReceipt']);
		$sellSIName = trim($_POST['sellSIName']);
		$sellSIStatus = trim($_POST['sellSIStatus']);
		$sellSIProd = trim($_POST['sellSIProd']);
		$sellSIPrice = trim($_POST['sellSIPrice']);
		$sellSIQuantity = trim($_POST['sellSIQuantity']);
		$sellSITotal = trim($_POST['sellSITotal']);
		
		$sell_SITrans = strip_tags($sellSITrans);
		$sell_SIDate = strip_tags($sellSIDate);
		$sell_SIBranch = strip_tags($sellSIBranch);
		$sell_SIReceipt = strip_tags($sellSIReceipt);
		$sell_SIName = strip_tags($sellSIName);
		$sell_SIStatus = strip_tags($sellSIStatus);
		$sell_SIProd = strip_tags($sellSIProd);
		$sell_SIPrice = strip_tags($sellSIPrice);
		$sell_SIQuantity = strip_tags($sellSIQuantity);
		$sell_SITotal = strip_tags($sellSITotal);
		
		
		$query = "INSERT INTO product_out (prodOutTranType,prodOutDate,prodOutBranch,prodOutReceipt,prodOutName,prodOutStatus,prodOutProduct,prodOutPrice,prodOutQuantity,prodOutTotalPrice
					) VALUES (:sellSITrans, :sellSIDate, :sellSIBranch, :sellSIReceipt, :sellSIName, :sellSIStatus, :sellSIProd, :sellSIPrice, :sellSIQuantity, :sellSITotal)";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':sellSITrans', $sell_SITrans);
		$stmt->bindParam(':sellSIDate', $sell_SIDate);
		$stmt->bindParam(':sellSIBranch', $sell_SIBranch);
		$stmt->bindParam(':sellSIReceipt', $sell_SIReceipt);
		$stmt->bindParam(':sellSIName', $sell_SIName);
		$stmt->bindParam(':sellSIStatus', $sell_SIStatus);
		$stmt->bindParam(':sellSIProd', $sell_SIProd);
		$stmt->bindParam(':sellSIPrice', $sell_SIPrice);
		$stmt->bindParam(':sellSIQuantity', $sell_SIQuantity);
		$stmt->bindParam(':sellSITotal', $sell_SITotal);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully Sold';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not create, try again later';
        }	
	}
	
	echo json_encode($response);