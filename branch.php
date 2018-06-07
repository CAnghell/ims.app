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

<body>
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
	
	// redirect user depending on account type
	if($userRow['accountType'] == 'Staff'){
		header("Location: unAuthorizeError.php");
	} 
	
	
	$selBranch = !empty($_GET['selBranch']) ? $_GET['selBranch'] : '';
	
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
					<li class='active'>
						<a href="branch.php">
							<i class="pe-7s-map-marker"></i>
							<p>Branch</p>
						</a>
					</li>
					<li>
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
					<li>
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
                    <div  class="navbar-brand" href="#"> Branch</div>
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
								<div class="col-md-12">
								<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
									<h4 class="title" id="currentBranch" name="currentBranch"><?php echo $selBranch;?></h4>
								<?php }else{ ?>
									<h4 class="title" id="currentBranch" name="currentBranch">	<?php echo $userRow['userBranch']; ?></h4>
								<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mrgn-top-10">
									<div class="buttons">
										<a href="#" class="frame-nav frame-active" onclick="divVisibility('stock1'); navFrame(this);">
											<input type="button" class="btn-noDes" value="Product Out">
										</a>  
										<a href="#" class="frame-nav" onclick="divVisibility('stock2'); navFrame(this);">
											<input type="button" class="btn-noDes" value="Product In">
										</a> 
										<a href="#" class="frame-nav" onclick="divVisibility('stock3'); navFrame(this);">
											<input type="button" class="btn-noDes" value="Refund">
										</a> 
									</div>
									<div class="inner_stock mrgn-top-10">
									<!-- PRODUCT OUT -->
										<div id="stock1">
											<div class="row mrgn-bottom-10">
												<div class="col-md-12 col-xs-12">
													<div class="pull-right">
														<div class="form-group search-div">
															<div class="icon-addon addon-sm">
																<input type="text" placeholder="Search.." name="prodOutSearch" id="prodOutSearch" class="form-control">
																<label class="fa fa-search" ></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="content table-responsive tbl-height void-tbl-pad" id="prodOut_table">
												<table class="tbl-prpl table table-hover table-striped">
													<thead>
														<th class="prod-out1">Date</th>
														<th class="prod-out2">Type</th>
														<th class="prod-out3">Receipt No.</th>
														<th class="prod-out4">Name</th>
														<th class="prod-out5">Total Price</th>
														<th class="prod-out6">Status</th>
														<th class="prod-out7">Action</th>
													</thead>
													<tbody>
														<tr>
															<td>June 12,2017</td>
															<td>Sales Invoice</td>
															<td>000421</td>
															<td>Chester</td>
															<td>3580</td>
															<td>Pending</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateProdOut">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Sales Invoice</td>
															<td>000421</td>
															<td>Chester</td>
															<td>3580</td>
															<td>Verified</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateProdOut">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Sales Invoice</td>
															<td>000421</td>
															<td>Chester</td>
															<td>3580</td>
															<td>Unpaid</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateProdOut">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Canvas Sheet</td>
															<td>000421</td>
															<td></td>
															<td>3580</td>
															<td>Pending</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateProdOut">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Canvas Sheet</td>
															<td>000421</td>
															<td></td>
															<td>3580</td>
															<td>Verified</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateProdOut">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Transfer</td>
															<td></td>
															<td>Transfered to Naguilian</td>
															<td></td>
															<td>Pending</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateTransfer">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Transfer</td>
															<td></td>
															<td>Transfered to Tomay</td>
															<td></td>
															<td>Verified</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateTransfer">Update</button>
															</td>	
														</tr>
														<tr>
															<td>June 12,2017</td>
															<td>Transfer</td>
															<td></td>
															<td>Transfered to Tomay</td>
															<td></td>
															<td>Verified</td>
															<td><button type="button" class="btn btn-info btn-fill btn-xs"
																data-toggle="modal" data-target="#updateTransfer">Update</button>
															</td>	
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										
									<!-- PRODUCT IN -->
										<div id="stock2" class="dis_none">
											<div class="row mrgn-bottom-10">
												<div class="col-md-12">
													<div class="pull-right">
														<div class="form-group search-div">
															<div class="icon-addon addon-sm">
																<input type="text" placeholder="Search.." name="prodInSearch" id="prodInSearch" class="form-control">
																<label class="fa fa-search" ></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="content table-responsive tbl-height void-tbl-pad" id="prodIn_table">
												<table class="tbl-prpl table table-hover table-striped">
													<thead>
														<th class="prod-in1">Date</th>
														<th class="prod-in2">Product</th>
														<th class="prod-in3">Supplier</th>
														<th class="prod-in4">Price</th>
														<th class="prod-in5">Quantity</th>
														<th class="prod-in6">Total Price</th>
													</thead>
													<tbody>
													<?php
														// show the products going in the hardware
														$sql = "SELECT * FROM cuna_hardware.product_in WHERE prodInBranch = '$activeBranch' ORDER BY prodInId DESC;";
														$result = $con->query($sql);
														if ($result->num_rows > 0) {
															// output data of each row
															while($row = $result->fetch_assoc()) {
																	echo "
																	<tr> 	
																		<td class='prod-in1'> " . $row["prodInDate"] . " </td>
																		<td class='prod-in2'> " . $row["prodInProduct"] ." " . $row["prodInInformation"] . "  " . $row["prodInSize"] . "</td>
																		<td class='prod-in3'> " . $row["prodInSupplier"] . "  </td>
																		<td class='prod-in4'> " . $row["prodInPrice"] . " </td> 
																		<td class='prod-in5'> " . $row["prodInQuantity"] . " </td>
																		<td class='prod-in6'> " . $row["prodInPrice"]*$row["prodInQuantity"] . " </td> 
																	</tr>";
															} 
														} else {
															echo "";
														}
													?>
													</tbody>
												</table>
											</div>
										</div>
										
									<!-- PRODUCT REFUND -->
										<div id="stock3" class="dis_none">
											<div class="row mrgn-bottom-10">
												<div class="col-md-12">
													<div class="pull-right">
														<div class="form-group search-div">
															<div class="icon-addon addon-sm">
																<input type="text" placeholder="Search.." name="refundSearch" id="refundSearch" class="form-control">
																<label class="fa fa-search" ></label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="content table-responsive tbl-height void-tbl-pad" id="refund_table">
												<table class="tbl-prpl table table-hover table-striped">
													<thead>
														<th class="prod-re1">Date</th>
														<th class="prod-re2">Receipt No.</th>
														<th class="prod-re3">Reason</th>
														<th class="prod-re4">Product</th>
														<th class="prod-re5">Price</th>
														<th class="prod-re6">Quantity</th>
														<th class="prod-re7">Total Price</th>
														
													</thead>
													<tbody>
													<?php
														// show the refunded products
														$sql = "SELECT * FROM cuna_hardware.refund WHERE refundBranch = '$activeBranch' ORDER BY refundId DESC;";
														$result = $con->query($sql);
														if ($result->num_rows > 0) {
															// output data of each row
															while($row = $result->fetch_assoc()) {
																	echo "
																	<tr> 	
																		<td class='prod-re1'> " . $row["refundDate"] . " </td>
																		<td class='prod-re2'> " . $row["refundReceipt"] . " </td>
																		<td class='prod-re3'> " . $row["refundReason"] . " </td>
																		<td class='prod-re4'> " . $row["refundProduct"] . " </td> 
																		<td class='prod-re5'> " . $row["refundPrice"] . " </td>
																		<td class='prod-re6'> " . $row["refundQuantity"] . " </td>
																		<td class='prod-re7'> " . $row["refundPrice"]*$row["refundQuantity"] . " </td> 
																	</tr>";
															} 
														} else {
															echo "";
														}
													?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
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

<!-- Update Sales Invoice Modal -->
<div class="modal fade	void-back" id="updateProdOut" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Transaction Details
					<?php if($userRow['accountType'] == 'Admin'){ ?> 
						<button class="btn btn-warning btn-xs btn-fill pull-right mrgn-right-10" data-dismiss="modal" data-toggle="modal" data-target="#transDeleteConfirm">
							<i class="fa fa-trash"></i> Delete Transaction 
						</button>
					<?php } ?>
				</h4>
			</div>
			<form>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Date</label>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label receiptNo-fix">Receipt No.</label>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
								<label class="modal-label">Name</label>
								<input type="text" class="form-control">
							</div>
						</div>
						
						<div class="col-md-3 col-xs-3">
						<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier'){ ?> 
							<div class="form-group">
								<label class="modal-label">Status</label>
								<select class="form-control">
									<option disabled selected>Select status...</option>
									<option>Paid</option>
									<option>Unpaid</option>
								</select>
							</div>
						<?php } ?>
						</div>
					</div>
					<div class="content table-responsive modal-tbl-height void-tbl-pad">
						<table class="table table-hover table-striped">
							<thead>
								<th id="invent-sell1">Product</th>
								<th id="invent-sell2">Information</th>
								<th id="invent-sell3">Size</th>
								<th id="invent-sell4">Price</th>
								<th id="invent-sell5">Quantity</th>
								<th id="invent-sell6">Total</th>
							</thead>
							<tbody>
								<tr>
									<td>Rain or Shine</td>
									<td>TX White</td>
									<td>1 Liter</td>
									<td>1200</td>
									<td>2</td>
									<td>52,578</td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td><input type="text" class="form-control void-input-pad"></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="form-horizontal mrgn-top-10">
						<div class="form-group void-mrgn-bottom">
							<label class="col-sm-9 modal-label control-label">Total:</label>
							<div class="col-sm-3">
							<input type="text" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<p class="trans-paid pull-left"> PAID: DATE</p>
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-info btn-fill btn-sm" value="Update"/>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END of Update Sales Invoice Modal -->	

<!-- Update Canvas Sheet Modal -->

<!-- END of Update Canvas Sheet Modal -->	

<!-- Update Transfer Modal -->
<div class="modal fade	void-back" id="updateTransfer" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Transaction Details
					<?php if($userRow['accountType'] == 'Admin'){ ?> 
						<button class="btn btn-warning btn-xs btn-fill pull-right mrgn-right-10" data-toggle="modal" data-target="#transDeleteConfirm">
							<i class="fa fa-trash"></i> Delete Transaction 
						</button>
					<?php } ?>
				</h4>
			</div>
			<form>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Date</label>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Receipt No.</label>
								<input type="text" class="form-control">
							</div>
						</div>
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Transfer to</label>
								<input type="text" class="form-control" readonly>
							</div>
						</div>
						<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Cashier' ){ ?> 
						<div class="col-md-2 col-xs-2">
							<div class="form-group">
								<label class="modal-label">Status</label>
								<select  class="form-control">
									<option disabled selected value="">Select status...</option>
									<option>Claimed</option>
									<option>Unclaimed</option>
								</select>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="content table-responsive modal-tbl-height void-tbl-pad">
						<table class="table table-hover table-striped">
							<thead>
								<th id="invent-sell1">Product</th>
								<th id="invent-sell2">Information</th>
								<th id="invent-sell3">Size</th>
								<th id="invent-sell5">Quantity</th>
							</thead>
							<tbody>
								<tr>
									<td>Rain or Shine </td>
									<td>TX White</td>
									<td>1 Liter</td>
									<td>2</td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
								</tr>
								<tr>
									<td><select></select></td>
									<td><select></select></td>
									<td><select></select></td>
									<td><input type="text" class="form-control void-input-pad"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="form-horizontal mrgn-top-10">
						<div class="form-group void-mrgn-bottom">
							<label class="col-sm-9 modal-label control-label">Total:</label>
							<div class="col-sm-3">
							<input type="text" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<p class="trans-paid pull-left"> CLAIMED: DATE</p>
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-info btn-fill btn-sm" value="Update"/>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END of Update Transfer Modal -->	
	<!-- Delete Transaction Confirmation Modal -->
	<div class="modal fade" id="transDeleteConfirm" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Confirmation</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this Transaction?</p>
				</div>
				<div class="modal-footer">
					<form action="" method="post">
						<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal" data-toggle="modal" data-target="#updateProdOut">Cancel</button>
						<input type="submit" name="prodDeleteConfirm_btn" class="btn btn-danger btn-fill btn-sm" value="Confirm"/>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- END of Transaction Product Confirmation -->	
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
	
	<script> 

	</script>
</html>
<?php ob_end_flush(); ?>