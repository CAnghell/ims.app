<?php

	header('Content-type: application/json');

	require_once 'server.php';
	
	$response = array();

	if ($_POST) {
		$prodDate = trim($_POST['prodDate']);
		//Changing the format of the date from yyyy-mm-dd to mm-dd-yyyy
			$prodDate = strtotime($prodDate);
			$prodDate = date('M d Y', $prodDate);
		$prodSupplier = trim($_POST['prodSupplier']);
		$prodName = trim($_POST['prodName']);
		$prodInfo = trim($_POST['prodInfo']);
		$prodSize = trim($_POST['prodSize']);
		$prodAlerQuantity = trim($_POST['prodAlerQuantity']);
		$prodQuantity = trim($_POST['prodQuantity']);
		$netPrice = trim($_POST['netPrice']);
		$prodPrice = trim($_POST['prodPrice']);
		$prodType = trim($_POST['prodType']);
		$prodBranch = trim($_POST['prodBranch']);
		
		
		$product_Date = strip_tags($prodDate);
		$product_Supplier = strip_tags($prodSupplier);
		$product_Name = strip_tags($prodName);
		$product_Information = strip_tags($prodInfo);
		$product_Size = strip_tags($prodSize);
		$product_Quantity = strip_tags($prodQuantity);
		$product_AlerQuantity = strip_tags($prodAlerQuantity);
		$product_NetPrice = strip_tags($netPrice);
		$product_Price = strip_tags($prodPrice);
		$product_Type = strip_tags($prodType);
		$product_Branch = strip_tags($prodBranch);
		
		// QUERY OF THE INVENTORY
		$query = "INSERT INTO inventory (productName,productInformation,productSize,productQuantity,productAlerQuantity,productNetPrice,productPrice,productType,productBranch
					) VALUES (:prodName, :prodInfo, :prodSize, :prodQuantity, :prodAlerQuantity, :netPrice, :prodPrice, :prodType, :prodBranch);
				  INSERT INTO product_in (prodInDate,prodInType,prodInProduct,prodInInformation,prodInSize,prodInSupplier,prodInQuantity,prodInPrice,prodInBranch
					) VALUES (:prodDate, :prodType, :prodName, :prodInfo, :prodSize, :prodSupplier, :prodQuantity, :netPrice, :prodBranch)";
		
		$stmt = $DBcon->prepare( $query );
		$stmt->bindParam(':prodDate', $product_Date);
		$stmt->bindParam(':prodName', $product_Name);
		$stmt->bindParam(':prodInfo', $product_Information);
		$stmt->bindParam(':prodSize', $product_Size);
		$stmt->bindParam(':prodSupplier', $product_Supplier);
		$stmt->bindParam(':prodQuantity', $product_Quantity);
		$stmt->bindParam(':prodAlerQuantity', $product_AlerQuantity);
		$stmt->bindParam(':netPrice', $product_NetPrice);
		$stmt->bindParam(':prodPrice', $product_Price);
		$stmt->bindParam(':prodType', $product_Type);
		$stmt->bindParam(':prodBranch', $product_Branch);
	
		
        if ( $stmt->execute() ) {
			$response['status'] = 'success'; // check for successfull create
			$response['message'] = '<span class="fa fa-check"></span> &nbsp; New product was successfully created';
        } else {
            $response['status'] = 'error'; // could not create
			$response['message'] = '<span class="fa fa-info"></span> &nbsp; could not create, try again later';
        }	
	}
	
	echo json_encode($response);