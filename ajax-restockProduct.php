<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$restock_id = trim($_POST['restock_id']);
		$restockDate = trim($_POST['restockDate']);
			$restockDate = strtotime($restockDate);
			$restockDate = date('M d Y', $restockDate);
		$restockType = trim($_POST['restockType']);
		$restockProd = trim($_POST['restockProd']);
		$restockInfo = trim($_POST['restockInfo']);
		$restockSize = trim($_POST['restockSize']);
		$restockSupplier = trim($_POST['restockSupplier']);
		$restockAddQuantity = trim($_POST['restockAddQuantity']);
		$restockNetPrice = trim($_POST['restockNetPrice']);
		$restockBranch = trim($_POST['restockBranch']);
		
		$restockId = strip_tags($restock_id);
		$restock_Date = strip_tags($restockDate);
		$restock_Type = strip_tags($restockType);
		$restock_Prod = strip_tags($restockProd);
		$restock_Info = strip_tags($restockInfo);
		$restock_Size = strip_tags($restockSize);
		$restock_Supplier = strip_tags($restockSupplier);
		$restock_AddQuantity = strip_tags($restockAddQuantity);
		$restock_NetPrice = strip_tags($restockNetPrice);
		$restock_Branch = strip_tags($restockBranch);
		
		
		$query = "INSERT INTO product_in (prodInDate,prodInType,prodInProduct,prodInInformation,prodInSize,prodInSupplier,prodInQuantity,prodInPrice,prodInBranch 
					) VALUE (:restockDate, :restockType, :restockProd, :restockInfo, :restockSize, :restockSupplier, :restockAddQuantity, :restockNetPrice, :restockBranch);
				  UPDATE inventory SET productQuantity = (productQuantity + :restockAddQuantity) WHERE productId = :restock_id;";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':restock_id', $restockId);
		$stmt->bindParam(':restockDate', $restock_Date);
		$stmt->bindParam(':restockType', $restock_Type);
		$stmt->bindParam(':restockProd', $restock_Prod);
		$stmt->bindParam(':restockInfo', $restock_Info);
		$stmt->bindParam(':restockSize', $restock_Size);
		$stmt->bindParam(':restockSupplier', $restock_Supplier);
		$stmt->bindParam(':restockAddQuantity', $restock_AddQuantity);
		$stmt->bindParam(':restockNetPrice', $restock_NetPrice);
		$stmt->bindParam(':restockBranch', $restock_Branch);
		
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; Product was successfully restocked';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not restock, try again later';
        }	
	}
	
	echo json_encode($response);