<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Cuna Hardware Inventory System</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/bootstrap-select.min.css" rel="stylesheet" />
	
	<!-- CSS custom made 	-->
	<link href="assets/css/custom.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>

    <!--     Fonts and icons     -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>

<body onload="navFrameSelected()";>
<?php
	ob_start();
	session_start();
	require_once 'server.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	// select loggedin users detail
	$res=mysqli_query($con, "SELECT * FROM accounts WHERE userId=".$_SESSION['user']);
	$userRow=mysqli_fetch_array($res);
	
	//set validation error flag as false
	$error = false; 	
	
	$prodName = !empty($_GET['prodName']) ? $_GET['prodName'] : '';
	$selBranch = !empty($_GET['selBranch']) ? $_GET['selBranch'] : '';
	//$modalUpdate = !empty($_GET['modalUpdate']) ? $_GET['modalUpdate'] : '';
	
	// if user is admin/encoder query depends on select
	if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){
		if(!empty($_GET['selBranch'])){
			$selBranch = $_GET['selBranch'];
			$activeBranch = $selBranch;
		} 
		if(empty($_GET['selBranch'])){
			$selBranch = "Tomay Cuna Hardware";
			$activeBranch = $selBranch;
		}
	}
	
	// if user is cashier/staff query depends on branch
	if($userRow['accountType'] == 'Cashier' || $userRow['accountType'] == 'Staff'){
		$activeBranch = $userRow['userBranch'];
	} else {
		if(empty($selBranch)){
			$activeBranch = $activeBranch;
		} else{
			$activeBranch = $selBranch;
		}
	}
	
	// Get the selected product type and giving defaut value for type
	if(!empty($_GET['type'])){
		$type = $_GET['type'];
	}else{
		$type = 'h';
	}
	switch ($type) {
		case "h":
			$inventProdType = 'Hardware';
			break;
		case "bs":
			$inventProdType = 'Bolts & Screws';
			break;
		case "cr":
			$inventProdType = 'Color Roof';
			break;
		case "gb":
			$inventProdType = 'GI & BI Fitting';
			break;
		case "p":
			$inventProdType = 'Paints';
			break;
		case "sb":
			$inventProdType = 'Steel Bars';
			break;
		default:
			$inventProdType = 'Hardware';
	}
											
?>

<div class="wrapper">
<!-- Side Navigation -->
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-1.jpg">

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    CUNA Hardware
                </a>
            </div>

            <ul class="nav">
				<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier'){ ?> 
					<li>
						<a href='home.php'>
							<i class='pe-7s-home'></i>
							<p>Home</p>
						</a>
					</li>
				<?php } ?>	
				<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Cashier'){ ?> 
					<li>
						<a href="branch.php">
							<i class="pe-7s-map-marker"></i>
							<p>Branch</p>
						</a>
					</li>
					<li class='active'>
						<a href="inventory.php">
							<i class="pe-7s-note2"></i>
							<p>Inventory</p>
						</a>
					</li>
				<?php } ?>
				<?php if($userRow['accountType'] == 'Admin' ){ ?> 
					<li>
						<a href="account.php">
							<i class="pe-7s-users"></i>
							<p>Accounts</p>
						</a>
					</li>
				<?php } ?>
				<?php if($userRow['accountType'] == 'Staff'){ ?> 
					<li class='active'>
						<a href="inventory.php">
							<i class="pe-7s-note2"></i>
							<p>Inventory</p>
						</a>
					</li>
				<?php } ?>
            </ul>
    	</div>
    </div>
<!-- END of Side Navigation -->


    <div class="main-panel">
	<!-- header -->
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand" href="#">Inventory</div>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Hello, <?php echo $userRow['username']; ?>
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="myAccount.php"><i class="fa fa-user"></i> My Account</a></li>
								<li class="divider"></li>
								<li><a href="logout.php?logout"><i class="fa fa-power-off"></i> Log out</a></li>
							</ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
	<!-- END of header -->

	<!-- Page Content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
					<div class="col-md-12">
						<div class="card">
						<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
							<div class="row col-md-3 col-sm-4 col-xs-7 col-centered ">
							<form action="" method="get">
								<select class="form-control void-slct-pad slct-fix-width" name="selBranch" onchange="this.form.submit();">
									<option selected value="<?php echo $selBranch;?>"><?php echo $selBranch;?></option>
									<?php
										$sql = "SELECT * FROM cuna_hardware.branch;";
										$result = $con->query($sql);

											if ($result->num_rows > 0) {
												// output data of each row
												while($row = $result->fetch_assoc()) {
													if ($selBranch != $row["branchName"]){
													echo "
														<option> ". $row["branchName"]. " </option>";	
													}
												}
											} else {
												echo "0 results";
											}
									?>
								</select>
							</form>
							</div>
						<?php } ?>
							<div class="row">
								<div class="col-md-5 col-sm-5 col-xs-12">
									<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
									<h4 class="title" id="currentBranch" name="currentBranch"><?php echo $selBranch; ?></h4>
									<?php }else{ ?>
										<h4 class="title" id="currentBranch" name="currentBranch">	<?php echo $userRow['userBranch']; ?></h4>
									<?php } ?>
								</div>
								<div class="col-md-7 col-sm-7 col-xs-12" id="invent-responsive-nav">
								<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Cashier'){ ?> 
									<div class="pull-right">
										<a href="#">
											<button class="btn btn-prpl btn-sm" data-toggle="modal" data-target="#inventSell">
												<i class="fa fa-shopping-cart"></i> Sell
											</button>
										</a>
										<a href="#">
											<button class="btn btn-prpl btn-sm" data-toggle="modal" data-target="#inventRefund">
												<i class="fa fa-database"></i> Refund
											</button>
										</a>
										<a href="#">
											<button class="btn btn-prpl btn-sm" data-toggle="modal" data-target="#inventNewProd">
												<i class="fa fa-pencil-square-o"></i> New Product
											</button>
										</a>
									</div>
								<?php } ?>
								</div>
							</div>
							<div class="row">	
								<div class="col-md-12 mrgn-top-10">
									<div id="invent_type" class="buttons">
										<a href="inventory.php?type=h&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="hardware_btn" class="btn-noDes" value="Hardware"/>
										</a>  
										<a href="inventory.php?type=bs&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="boltScrew_btn" class="btn-noDes" value="Bolts & Screws"/>
										</a>
										<a href="inventory.php?type=cr&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="colorRoof_btn" class="btn-noDes" value="Color Roof">
										</a>  
										<a href="inventory.php?type=gb&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="gibiFitting_btn" class="btn-noDes" value="GI & BI Fitting"/>
										</a>  
										<a href="inventory.php?type=p&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="paints_btn" class="btn-noDes" value="Paints"/>
										</a>  
										<a href="inventory.php?type=sb&selBranch=<?php echo $selBranch ?>" class="frame-nav">
											<input type="submit" name="steelBar_btn" class="btn-noDes" value="Steel Bars"/>
										</a> 
									</div>
									<div class="inner_invent">
										<div class="row mrgn-top-10 mrgn-bottom-10">
											<div class="col-md-12 col-xs-12">
												<div class="pull-right">
													<div class="form-group search-div">
														<div class="icon-addon addon-sm">
															<input type="text" placeholder="Search.." name="inventProductSearch" id="inventProductSearch" class="form-control">
															<label class="fa fa-search" ></label>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="content table-responsive invent-tbl-height void-tbl-pad">
											<table class="tbl-prpl table table-hover table-striped" id="invent_table">
												<thead>
													<th class="invent-table1">Product</th>
													<th class="invent-table2">Information</th>
													<th class="invent-table3">Size</th>
													<th class="invent-table4">Quantity</th>
												<?php if($userRow['accountType'] == 'Admin'){ ?> 
													<th class="invent-table5">Net Price</th>
												<?php } ?>
													<th class="invent-table6">Price</th>
												<?php if($userRow['accountType'] == 'Admin'){ ?> 
													<th class="invent-table7">Action</th>
												<?php } ?>
												<?php if($userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Cashier'){ ?> 
													<th class="invent-table7-1">Action</th>
												<?php } ?>
												</thead>
												<tbody>
												<?php
													// show the selected product type
													$sql = "SELECT * FROM cuna_hardware.inventory WHERE productType = '$inventProdType' AND productBranch ='$activeBranch' ORDER BY productName;";
													$result = $con->query($sql);
													if ($result->num_rows > 0) {
														// output data of each row
														while($row = $result->fetch_assoc()) {
															if($userRow['accountType'] == "Admin"){
																echo "
																<tr> 
																	<td class='invent-table1'> " . $row["productName"] . " </td>
																	<td class='invent-table2'> " . $row["productInformation"] . " </td>
																	<td class='invent-table3'> " . $row["productSize"] . " </td>
																	<td class='invent-table4'> " . $row["productQuantity"] . " </td> 
																	<td class='invent-table5'> " . $row["productNetPrice"] . " </td>
																	<td class='invent-table6'> " . $row["productPrice"] . " </td> 
																	<td class='invent-table7'>
																		<button type='button' class='btn btn-fix-width btn-info btn-fill btn-xs product_update' 
																		id='" . $row["productId"] . "'>Update</button>
																		<button type='button' class='btn btn-fix-width btn-primary btn-fill btn-xs product_restock' 
																		id='" . $row["productId"] . "'>Restock</button>
																</tr>";
															} else if($userRow['accountType'] == "Staff"){
																echo "
																<tr> 	
																	<td class='invent-table1'> " . $row["productName"] . " </td>
																	<td class='invent-table2'> " . $row["productInformation"] . " </td>
																	<td class='invent-table3'> " . $row["productSize"] . " </td>
																	<td class='invent-table4'> " . $row["productQuantity"] . " </td>
																	<td class='invent-table6'> " . $row["productPrice"] . " </td> 
																</tr>";
															} else if($userRow['accountType'] == "Encoder" || $userRow['accountType'] == "Cashier" ){
																echo "
																<tr> 	
																	<td class='invent-table1'> " . $row["productName"] . " </td>
																	<td class='invent-table2'> " . $row["productInformation"] . " </td>
																	<td class='invent-table3'> " . $row["productSize"] . " </td>
																	<td class='invent-table4'> " . $row["productQuantity"] . " </td> 
																	<td class='invent-table6'> " . $row["productPrice"] . " </td> 
																	<td class='invent-table7-1'><button type='button' class='btn btn-fix-width btn-primary btn-fill btn-xs product_restock' 
																		id='" . $row["productId"] . "'>Restock</button></td>
																</tr>";
															}
														}
													} else {
														echo "";
													}
												?>
												</tbody>
											</table>
										</div>
									</div>
									
									
								<!-- Low Quantity Products Table -->
									<div class="row mrgn-top-30">
										<div class="col-md-12 ">
											<div class="form-group label-border-bottom">
												<label class="label-title">Low Quantity Products</label>
											</div>
										</div>
									</div>	
									<div class="row mrgn-bottom-10">
										<div class="col-md-12">
											<div class="pull-right">
												<div class="form-group search-div">
													<div class="icon-addon addon-sm">
														<input type="text" placeholder="Search.." name="lowProductSearch" id="lowProductSearch" class="form-control">
														<label class="fa fa-search" ></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="content table-responsive invent-tbl-height void-tbl-pad">
										<table class="tbl-prpl table table-hover table-striped" id="lowProduct_table">
											<thead>
												<th class="invent-low1">Type</th>
												<th class="invent-low2">Product</th>
												<th class="invent-low3">Information</th>
												<th class="invent-low4">Size</th>
												<th class="invent-low5">Quantity</th>
											<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Cashier'){ ?> 
												<th class="invent-low6">Action</th>
											<?php } ?>
											</thead>
											<tbody>
											<?php
												// show the products going in the hardware
												$sql = "SELECT * FROM cuna_hardware.inventory WHERE productBranch = '$activeBranch' AND productQuantity < productAlerQuantity ORDER BY productName;";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													// output data of each row
													while($row = $result->fetch_assoc()) {
														if($userRow['accountType'] == "Staff"){
															echo "
																<tr> 
																	<td class='invent-low1'> " . $row["productType"] . " </td>
																	<td class='invent-low2'> " . $row["productName"] . " </td>
																	<td class='invent-low3'> " . $row["productInformation"] . "</td>
																	<td class='invent-low4'> " . $row["productSize"] . "  </td>
																	<td class='invent-low5'> " . $row["productQuantity"] . " </td> 
																</tr>";
														} else {
															echo "
																<tr> 	
																	<td class='invent-low1'> " . $row["productType"] . " </td>
																	<td class='invent-low2'> " . $row["productName"] . " </td>
																	<td class='invent-low3'> " . $row["productInformation"] . "</td>
																	<td class='invent-low4'> " . $row["productSize"] . "  </td>
																	<td class='invent-low5'> " . $row["productQuantity"] . " </td> 
																	<td class='invent-low6'><button type='button' class='btn btn-fix-width btn-primary btn-fill btn-xs product_restock' 
																		id='" . $row["productId"] . "'>Restock</button></td>
																</tr>";
														}
													}
												} else {
													echo "";
												}
											?>
											</tbody>
										</table>
									</div>
								<!-- END of Low Quantity Products Table -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- End of Page Content -->
    </div>
</div>

<!-- Sell Modal -->
<div class="modal fade	" id="inventSell" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Product Sell</h4>
			</div>
			<div class="modal-body modal-min-height">
				<div class="row">
					<div class="col-md-12">
						<div class="errorDiv"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 row-centered">
						<div class="input-group centered mrgn-bottom-10">
							<div id="radioBtn" class="btn-group">
								<a class="btn btn-default btn-fill btn-sm active" data-toggle="sellSelect" data-title="Sales_Invoice">Sales Invoice</a>
								<a class="btn btn-default btn-fill btn-sm notActive" data-toggle="sellSelect" data-title="Canvas_Sheet">Canvas Sheet</a>
								<a class="btn btn-default btn-fill btn-sm notActive" data-toggle="sellSelect" data-title="Transfer">Transfer</a>
							</div>
						</div>
					</div>
				</div>
					
				<!-- SALES INVOICE -->
				<div class="Sales_Invoice">
					<form method="post" role="form" id="sellSIProduct-form" autocomplete="off">
						<div class="row">
							<div class="col-md-12 ">
								<div class="form-group label-border-bottom">
									<label class="modal-label-title">Sales Invoice</label>
								</div>
							</div>
						</div>
						<input type="hidden" id="sellSITrans" name="sellSITrans" value="Sales Invoice">
						<div class="row">
							<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Date</label>
									<input type="date" class="form-control" id="sellSIDate" name="sellSIDate" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Branch</label>
									<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
										<input type="text" id="sellSIBranch" name="sellSIBranch" class="form-control" value="<?php echo $activeBranch; ?>" readonly />
									<?php } ?>
									<?php if($userRow['accountType'] == 'Cashier'){ ?>
										<input type="text" id="sellSIBranch" name="sellSIBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
									<?php } ?>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Receipt No.</label>
									<input type="text" id="sellSIReceipt" name="sellSIReceipt" class="form-control">
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Name</label>
									<input type="text" id="sellSIName" name="sellSIName" class="form-control">
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier' ){ ?> 
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Status</label>
									<select class="form-control" id="sellSIStatus" name="sellSIStatus">
										<option disabled selected>Select status...</option>
										<option>Paid</option>
										<option>Unpaid</option>
									</select>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<?php } ?>
							<?php if($userRow['accountType'] == 'Encoder'){ ?> 
							<input type="hidden" id="sellSIStatus" name="sellSIStatus" value="Pending">
							<?php } ?>
						</div>
						<div class="content table-responsive modal-tbl-height void-tbl-pad">
							<table class="table table-hover table-striped">
								<thead>
									<th class="invent-sell1">Product</th>
									<th class="invent-sell2">Price</th>
									<th class="invent-sell3">Quantity</th>
									<th class="invent-sell4">Total</th>
									<th class="invent-sell5">Action</th>
								</thead>
								<tbody>
									<tr> 
										<td><div class="form-group">
											<select class="form-control selectpicker" data-live-search="true" id="sellSIProd" name="sellSIProd">
												<option disabled selected value="">Select product...</option>
												<?php
													$sql = "SELECT * FROM cuna_hardware.inventory WHERE productBranch = '$activeBranch' ORDER BY productName;";
													$result = $con->query($sql);

													if ($result->num_rows > 0) {
														// output data of each row
														while($row = $result->fetch_assoc()) {
															echo "
																<option> ".$row["productName"]." ".$row["productInformation"]."  ".$row["productSize"]." </option>";	
														}
													} else {
														echo "0 results";
													}
												?>
											</select>
											<span class="help-block" id="error"></span></div>
										</td>
										<td><div class="form-group"><input type="text" id="sellSIPrice" name="sellSIPrice" class="form-control void-input-pad sellPrice">
											<span class="help-block" id="error"></span></div></td>
										<td><div class="form-group"><input type="text" id="sellSIQuantity" name="sellSIQuantity" class="form-control void-input-pad sellQuantity">
											<span class="help-block" id="error"></span></div></td>
										<td><div class="form-group"><input type="text" id="sellSITotal" name="sellSITotal" class="form-control void-input-pad sellTotal" readonly>
											<span class="help-block" id="error"></span></div></td>
										<td><button type="button" id="add" class="btn btn-success btn-fill btn-xs modal-tbl-btn">Add</button><span class="help-block" id="error"></span></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-horizontal mrgn-top-10">
							<div class="form-group void-mrgn-bottom">
								<label class="col-sm-9 modal-label control-label">Total:</label>
								<div class="col-sm-3">
								<input type="text" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="modal-footer mrgn-top-10">
							<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
							<input type="submit" id="sellSIProd_btn" name="sellSIProd_btn" class="btn btn-primary btn-fill btn-sm" value="Confirm" />
						</div>
					</form>
				</div>
					
				<!-- CANVAS SHEET -->
				<div class="Canvas_Sheet box">
					<form method="post" role="form" id="sellCSProduct-form" autocomplete="off">
						<div class="row">
							<div class="col-md-12 ">
								<div class="form-group label-border-bottom">
									<label class="modal-label-title">Canvas Sheet</label>
								</div>
							</div>
						</div>
						<input type="hidden" id="sellCSTrans" name="sellCSTrans" value="Canvas Sheet">
						<div class="row">
							<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Date</label>
									<input type="date" class="form-control" id="sellCSDate" name="sellCSDate" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Branch</label>
									<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
										<input type="text" id="sellCSBranch" name="sellCSBranch" class="form-control" value="<?php echo $activeBranch; ?>" readonly />
									<?php } ?>
									<?php if($userRow['accountType'] == 'Cashier'){ ?>
										<input type="text" id="sellCSBranch" name="sellCSBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
									<?php } ?>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Receipt No.</label>
									<input type="text" id="sellCSReceipt" name="sellCSReceipt" class="form-control">
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Name</label>
									<input type="text" id="sellCSName" name="sellCSName" class="form-control">
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier' ){ ?> 
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Status</label>
									<select class="form-control" id="sellCSStatus" name="sellCSStatus">
										<option disabled selected>Select status...</option>
										<option>Paid</option>
										<option>Unpaid</option>
									</select>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="content table-responsive modal-tbl-height void-tbl-pad">
							<table class="table table-hover table-striped">
								<thead>
									<th class="invent-sell1">Product</th>
									<th class="invent-sell2">Price</th>
									<th class="invent-sell3">Quantity</th>
									<th class="invent-sell4">Total</th>
									<th class="invent-sell5">Action</th>
								</thead>
								<tbody>
									<tr> 
										<td>
											<select class="form-control selectpicker" data-live-search="true" id="sellCSProd" name="sellCSProd">
												<option disabled selected value="">Select product...</option>
												<?php
													$sql = "SELECT * FROM cuna_hardware.inventory WHERE productBranch = '$activeBranch' ORDER BY productName;";
													$result = $con->query($sql);

													if ($result->num_rows > 0) {
														// output data of each row
														while($row = $result->fetch_assoc()) {
															echo "
																<option> ".$row["productName"]." ".$row["productInformation"]."  ".$row["productSize"]." </option>";	
														}
													} else {
														echo "0 results";
													}
												?>
											</select>
											<span class="help-block" id="error"></span>
										</td>
										<td><input type="text" id="sellCSPrice" name="sellCSPrice" class="form-control void-input-pad sellPrice">
											<span class="help-block" id="error"></span></td>
										<td><input type="text" id="sellCSQuantity" name="sellCSQuantity" class="form-control void-input-pad sellQuantity">
											<span class="help-block" id="error"></span></td>
										<td><input type="text" id="sellCSTotal" name="sellCSTotal" class="form-control void-input-pad sellTotal" readonly>
											<span class="help-block" id="error"></span></td>
										<td><button type="button" id="add" class="btn btn-success btn-fill btn-xs modal-tbl-btn">Add</button><span class="help-block" id="error"></span></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form-horizontal mrgn-top-10">
							<div class="form-group void-mrgn-bottom">
								<label class="col-sm-9 modal-label control-label">Total:</label>
								<div class="col-sm-3">
								<input type="text" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="modal-footer mrgn-top-10">
							<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
							<input type="submit" id="sellCSProd_btn" name="sellCSProd_btn" class="btn btn-primary btn-fill btn-sm" value="Confirm" />
						</div>
					</form>
				</div>
				
				<!-- TRANSFER -->
				<div class="Transfer box">
					<form method="post" role="form" id="sellTProduct-form" autocomplete="off">
						<div class="row">
							<div class="col-md-12 ">
								<div class="form-group label-border-bottom">
									<label class="modal-label-title">Canvas Sheet</label>
								</div>
							</div>
						</div>
						<input type="hidden" id="sellTTrans" name="sellTTrans" value="Transfer">
						<div class="row">
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Date</label>
									<input type="date" class="form-control" id="sellTDate" name="sellTDate" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Branch</label>
									<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
										<input type="text" id="sellTBranch" name="sellTBranch" class="form-control" value="<?php echo $activeBranch; ?>" readonly />
									<?php } ?>
									<?php if($userRow['accountType'] == 'Cashier'){ ?>
										<input type="text" id="sellTBranch" name="sellTBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
									<?php } ?>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Receipt No.</label>
									<input type="text" id="sellTReceipt" name="sellTReceipt"  class="form-control">
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Transfer to</label>
									<select class="form-control" id="sellTNameTo" name="sellTNameTo" onchange="otherTrans(this)">
										<option disabled selected value="">Select branch...</option>
										<?php
										$sql = "SELECT * FROM cuna_hardware.branch;";
										$result = $con->query($sql);

										if ($result->num_rows > 0) {
											// output data of each row
											while($row = $result->fetch_assoc()) {
												echo "
													<option name='Branch'> ". $row["branchName"]. " </option>";	
											}
										} else {
											echo "0 results";
										}
										?>
										<option value = "Other - ">Other</option>
									</select>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<div id="hidden_trans" class="col-md-2 col-xs-6 dis_none">
								<div class="form-group">
									<label class="modal-label">Name</label>
									<input type="text" id="sellTName" name="sellTName" class="form-control">
								</div>
								<span class="help-block" id="error"></span>
							</div>
							<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier' ){ ?> 
							<div class="col-md-2 col-xs-6">
								<div class="form-group">
									<label class="modal-label">Status</label>
									<select class="form-control" id="sellTStatus" name="sellTStatus">
										<option disabled selected value="">Select status...</option>
										<option>Claimed</option>
										<option>Unclaimed</option>
									</select>
									<span class="help-block" id="error"></span>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="content table-responsive modal-tbl-height void-tbl-pad">
							<table class="table table-hover table-striped">
								<thead>
									<th class="invent-sell1">Product</th>
									<th class="invent-sell3">Quantity</th>
									<th class="invent-sell5">Action</th>
								</thead>
								<tbody>
									<tr>
										<td><select class="form-control selectpicker" data-live-search="true" id="sellTProd" name="sellTProd">
												<option disabled selected value="">Select product...</option>
												<?php
													$sql = "SELECT * FROM cuna_hardware.inventory WHERE productBranch = '$activeBranch' ORDER BY productName;";
													$result = $con->query($sql);

													if ($result->num_rows > 0) {
														// output data of each row
														while($row = $result->fetch_assoc()) {
															echo "
																<option> ".$row["productName"]." ".$row["productInformation"]."  ".$row["productSize"]." </option>";	
														}
													} else {
														echo "0 results";
													}
												?>
											</select>
											<span class="help-block" id="error"></span>
										</td>
										<td><input type="text" id="sellTQuantity" name="sellTQuantity" class="form-control void-input-pad">
											<span class="help-block" id="error"></span></td>
										<td><button type="button" id="add" class="btn btn-success btn-fill btn-xs modal-tbl-btn">Add</button><span class="help-block" id="error"></span></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-footer mrgn-top-10">
							<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
							<input type="submit" id="sellTProd_btn" name="sellTProd_btn" class="btn btn-primary btn-fill btn-sm" value="Confirm" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END of Sell Modal -->	

<!-- Refund Modal -->
<div class="modal fade	" id="inventRefund" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Product Refund</h4>
			</div>
			<form method="post" role="form" id="refund-form" autocomplete="off">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="errorDiv"></div>
						</div>
					</div>
					<input type="hidden" id="refundProductId" name="refundProductId"/>????
					<div class="row">
						<div class="col-md-4 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Date</label>
								<input type="date" id="refundDate" name="refundDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-2">
						</div>
						<div class="col-md-4 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Branch</label>
								<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
									<input type="text" id="refundBranch" name="refundBranch" class="form-control" value="<?php echo $activeBranch; ?>" readonly />
								<?php } ?>
								<?php if($userRow['accountType'] == 'Cashier'){ ?>
									<input type="text" id="refundBranch" name="refundBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
								<?php } ?>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Receipt No.</label>
								<input type="text" id="refundReceipt" name="refundReceipt" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Name</label>
								<input type="text" id="refundName" name="refundName" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<label class="modal-label">Product</label>
								<select class="form-control selectpicker" data-live-search="true" id="refundProduct" name="refundProduct">
									<option disabled selected value="">Select product...</option>
									<?php
										$sql = "SELECT * FROM cuna_hardware.inventory WHERE productBranch = '$activeBranch' ORDER BY productName;";
										$result = $con->query($sql);

										if ($result->num_rows > 0) {
											// output data of each row
											while($row = $result->fetch_assoc()) {
												echo "
													<option> ".$row["productName"]." ".$row["productInformation"]."  ".$row["productSize"]." </option>";	
											}
										} else {
											echo "0 results";
										}
									?>
								</select>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Quantity</label>
								<input type="text" id="refundQuantity" name="refundQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Price</label>
								<input type="text" id="refundPrice" name="refundPrice" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="modal-label">Reason</label>
								<textarea class="form-control" id="refundReason" name="refundReason" rows="3"></textarea>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
					<input type="submit" id="refundProd_btn" name="refundProd_btn" class="btn btn-primary btn-fill btn-sm" value="Confirm" />
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END of Refund Modal -->	

<!-- New Product Modal -->
<div class="modal fade	" id="inventNewProd" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">New Product</h4>
			</div>
			<form method="post" role="form" id="newProduct-form" autocomplete="off">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="errorDiv"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Date</label>
								<input type="date" id="prodDate" name="prodDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-2">
						</div>
						<div class="col-md-4 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Branch</label>
								<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
								<select class="form-control" id="prodBranch" name="prodBranch">
									<option disabled selected> Select Branch... </option>
									<?php
										$sql = "SELECT * FROM cuna_hardware.branch;";
										$result = $con->query($sql);

										if ($result->num_rows > 0) {
											// output data of each row
											while($row = $result->fetch_assoc()) {
												echo "
													<option name='Branch'> ". $row["branchName"]. " </option>";	
											}
										} else {
											echo "0 results";
										}
									?>
								</select>
								<?php } ?>
								<?php if($userRow['accountType'] == 'Cashier'){ ?>
								<input type="text" id="prodBranch" name="prodBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
								<?php } ?>
								<span class="help-block" id="error"></span>  
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7 col-xs-7">
							<div class="form-group">
								<label class="modal-label">Supplier</label>
								<input type="text" id="prodSupplier" name="prodSupplier" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-5 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Type</label>
								<select class="form-control" id="prodType" name="prodType">
									<option disabled selected> Select type... </option>
									<?php
										$sql = "SELECT * FROM cuna_hardware.product_type;";
										$result = $con->query($sql);

										if ($result->num_rows > 0) {
											// output data of each row
											while($row = $result->fetch_assoc()) {
												echo "
													<option name='type'> ". $row["productType"]. " </option>";	
											}
										} else {
											echo "0 results";
										}
									?>
								</select>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Product</label>
								<input type="text" id="prodName" name="prodName" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div> 
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Information</label>
								<input type="text" id="prodInfo" name="prodInfo" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">size</label>
								<input type="text" id="prodSize" name="prodSize" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Quantity</label>
								<input type="text" id="prodQuantity" name="prodQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Alerting Quantity</label>
								<input type="text" id="prodAlerQuantity" name="prodAlerQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Net Price</label>
								<input type="text" id="netPrice" name="netPrice" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Price</label>
								<input type="text" id="prodPrice" name="prodPrice" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
					<input type="submit" id="newProd_btn" class="btn btn-success btn-fill btn-sm" value="Create" />
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END of New Product Modal -->

<!-- Update Inventory Modal -->
<div class="modal fade" id="updateInventory" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Update Product
					<button class="btn btn-warning btn-xs btn-fill pull-right mrgn-right-10" data-dismiss="modal" data-toggle="modal" data-target="#prodDeleteConfirm">
						<i class="fa fa-trash"></i> Delete Product
					</button>
				</h4>
			</div>
			<form method="post" role="form" id="updateProduct-form" autocomplete="off">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="errorDiv"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Type</label>
								<select class="form-control" id="updateType" name="updateType">
									<option disabled selected> Select type... </option>
									<?php
										$sql = "SELECT * FROM cuna_hardware.product_type;";
										$result = $con->query($sql);

										if ($result->num_rows > 0) {
											// output data of each row
											while($row = $result->fetch_assoc()) {
												echo "
													<option name='type'> ". $row["productType"]. " </option>";	
											}
										} else {
											echo "0 results";
										}
									?>
								</select>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Product</label>
								<input type="text" id="updateProd" name="updateProd" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-5 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Information</label>
								<input type="text" id="updateInfo" name="updateInfo" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Size</label>
								<input type="text" id="updateSize" name="updateSize" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Current Quantity</label>
								<input type="text" id="updateCurQuantity" name="updateCurQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Alerting Quantity</label>
								<input type="text" id="updateAlerQuantity" name="updateAlerQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Price</label>
								<input type="text" id="updatePrice" name="updatePrice" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<input type="hidden" id="product_id" name="product_id"/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
					<input type="submit" id="updateProd_btn" name="updateProd_btn" class="btn btn-info btn-fill btn-sm" value="Update" />
				</div>
			</form>
		</div>
	</div>
</div>
	<!-- Delete Product Confirmation Modal -->
	<div class="modal fade" id="prodDeleteConfirm" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Confirmation</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete the product "Echo the product here"?</p>
				</div>
				<div class="modal-footer">
					<form action="" method="post">
						<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#updateInventory">Cancel</button>
						<input type="submit" name="prodDeleteConfirm_btn" class="btn btn-danger btn-fill btn-sm" value="Confirm"/>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- END of Delete Product Confirmation -->	
<!-- END of Update Inventory Modal -->	

<!-- Restock Modal -->
<div class="modal fade" id="restockInventory" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Product Restock</h4>
			</div>
			<form method="post" role="form" id="restockProduct-form" autocomplete="off">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="errorDiv"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Date</label>
								<input type="date" id="restockDate" name="restockDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" autofocus="true"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-4 col-xs-4">
						</div>
						<div class="col-md-4 col-xs-4">
							<div class="form-group box">
								<label class="modal-label">Branch</label>
								<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
									<input type="text" id="restockBranch" name="restockBranch" class="form-control" value="<?php echo $activeBranch; ?>" readonly />
								<?php } ?>
								<?php if($userRow['accountType'] == 'Cashier'){ ?>
									<input type="text" id="restockBranch" name="restockBranch" class="form-control" value="<?php echo $userRow['userBranch']; ?>" readonly />
								<?php } ?>
								<span class="help-block" id="error"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7 col-xs-7">
							<div class="form-group">
								<label class="modal-label">Supplier</label>
								<input type="text" id="restockSupplier" name="restockSupplier" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-5 col-xs-5">
							<div class="form-group">
								<label class="modal-label">Type</label>
								<input type="text" id="restockType" name="restockType" class="form-control" readonly />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4">
							<div class="form-group">
								<label class="modal-label">Product</label>
								<input type="text" id="restockProd" name="restockProd" class="form-control" readonly />
							</div>
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Information</label>
								<input type="text" id="restockInfo" name="restockInfo" class="form-control" readonly />
							</div>
						</div>
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Size</label>
								<input type="text" id="restockSize" name="restockSize" class="form-control" readonly />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
								<label class="modal-label">Additional Quantity</label>
								<input type="text" id="restockAddQuantity" name="restockAddQuantity" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<div class="col-md-6 ol-xs-6">
							<div class="form-group">
								<label class="modal-label">Net Price</label>
								<input type="text" id="restockNetPrice" name="restockNetPrice" class="form-control"/>
								<span class="help-block" id="error"></span>
							</div>
						</div>
						<input type="hidden" id="restock_id" name="restock_id"/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" onClick="window.location.reload();">Cancel</button>
					<input type="submit" id="restockProd_btn" name="restockProd_btn" class="btn btn-primary btn-fill btn-sm" value="Confirm" />
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END of Restock Modal -->	

</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	
	<!--  Validation -->
    <script src="assets/js/jquery.validate.min.js"></script>
	
	<!-- JS Custom made -->
	<script src="assets/js/custom.js"></script>
	<script src="assets/js/ajaxValidator.js"></script>
	
	<!-- Select Plugin -->
	<script src="assets/js/bootstrap-select.min.js"></script>

    <!-- Light Bootstrap Table Core javascript and methods-->
	<script src="assets/js/light-bootstrap-dashboard.js"></script>
</html>
<?php ob_end_flush(); ?>