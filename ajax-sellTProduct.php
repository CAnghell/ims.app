<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$sellTTrans = trim($_POST['sellTTrans']);
		$sellTDate = trim($_POST['sellTDate']);
			$sellTDate = strtotime($sellTDate);
			$sellTDate = date('M d Y', $sellTDate);
		$sellTBranch = trim($_POST['sellTBranch']);
		$sellTReceipt = trim($_POST['sellTReceipt']);
		$sellTNameTo = trim($_POST['sellTNameTo']);
		$sellTName = trim($_POST['sellTName']);
		$sellTStatus = trim($_POST['sellTStatus']);
		$sellTProd = trim($_POST['sellTProd']);
		$sellTQuantity = trim($_POST['sellTQuantity']);
		
		$sell_TTrans = strip_tags($sellTTrans);
		$sell_TDate = strip_tags($sellTDate);
		$sell_TBranch = strip_tags($sellTBranch);
		$sell_TReceipt = strip_tags($sellTReceipt);
		$sell_TNameTo = strip_tags($sellTNameTo);
		$sell_TName = strip_tags($sellTName);
		$sell_TStatus = strip_tags($sellTStatus);
		$sell_TProd = strip_tags($sellTProd);
		$sell_TQuantity = strip_tags($sellTQuantity);
		
		
		$query = "INSERT INTO product_out (prodOutTranType,prodOutDate,prodOutBranch,prodOutReceipt,prodOutName,prodOutStatus,prodOutProduct,prodOutQuantity
					) VALUES (:sellTTrans, :sellTDate, :sellTBranch, :sellTReceipt, :sellTNameTo :sellTName, :sellTStatus, :sellTProd, :sellTQuantity)";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':sellTTrans', $sell_TTrans);
		$stmt->bindParam(':sellTDate', $sell_TDate);
		$stmt->bindParam(':sellTBranch', $sell_TBranch);
		$stmt->bindParam(':sellTReceipt', $sell_TReceipt);
		$stmt->bindParam(':sellTNameTo', $sell_TNameTo);
		$stmt->bindParam(':sellTName', $sell_TName);
		$stmt->bindParam(':sellTStatus', $sell_TStatus);
		$stmt->bindParam(':sellTProd', $sell_TProd);
		$stmt->bindParam(':sellTQuantity', $sell_TQuantity);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully Transferred ';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not create, try again later';
        }	
	}
	
	echo json_encode($response);