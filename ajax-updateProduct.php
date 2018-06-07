<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$product_id = trim($_POST['product_id']);
		$updateType = trim($_POST['updateType']);
		$updateProd = trim($_POST['updateProd']);
		$updateInfo = trim($_POST['updateInfo']);
		$updateSize = trim($_POST['updateSize']);
		$updateCurQuantity = trim($_POST['updateCurQuantity']);
		$updateAlerQuantity = trim($_POST['updateAlerQuantity']);
		$updatePrice = trim($_POST['updatePrice']);
		
		$productId = strip_tags($product_id);
		$update_Type = strip_tags($updateType);
		$update_Prod = strip_tags($updateProd);
		$update_Info = strip_tags($updateInfo);
		$update_Size = strip_tags($updateSize);
		$update_CurQuantity = strip_tags($updateCurQuantity);
		$update_AlerQuantity = strip_tags($updateAlerQuantity);
		$update_Price = strip_tags($updatePrice);
		
		
		$query = "UPDATE inventory SET productType = :updateType, productName = :updateProd, productInformation = :updateInfo,
					productSize = :updateSize, productQuantity = :updateCurQuantity, productAlerQuantity = :updateAlerQuantity,
					productPrice = :updatePrice WHERE productId = :product_id";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':product_id', $productId);
		$stmt->bindParam(':updateType', $update_Type);
		$stmt->bindParam(':updateProd', $update_Prod);
		$stmt->bindParam(':updateInfo', $update_Info);
		$stmt->bindParam(':updateSize', $update_Size);
		$stmt->bindParam(':updateCurQuantity', $update_CurQuantity);
		$stmt->bindParam(':updateAlerQuantity', $update_AlerQuantity);
		$stmt->bindParam(':updatePrice', $update_Price);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully updated';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not update, try again later';
        }	
	}
	
	echo json_encode($response);