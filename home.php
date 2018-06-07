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
	if($userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Staff' ){
		header("Location: unAuthorizeError.php");
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
					<li  class='active'>
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
					<li>
						<a href="inventory.php">
							<i class="pe-7s-note2"></i>
							<p>Inventory</p>
						</a>
					</li>
					<li>
				<?php } ?>
				<?php if($userRow['accountType'] == 'Admin' ){ ?> 
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
                    <div class="navbar-brand" href="#">Home</div>
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
			<?php if($userRow['accountType'] == 'Admin' || $userRow['accountType'] == 'Encoder'){ ?> 
				<div class="row">
					<!-- Total Products -->
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="dash-card">
							<div class="row mrgn-bottom-10">
								<div class="col-md-4 col-xs-4 dash-icon">
									<i class="fa fa-dropbox"></i>
								</div>
								<div class="col-md-8 col-xs-8">
									<p class="dash-value "><?php
										$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory;";
										$result = $con->query($sql);
											if ($result->num_rows > 0) {
												while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
											} else { echo "0 results";}
									?></p>
									<p class="dash-title "> Total Products</p>
								</div>
							</div>
							<a href="#totalProdMore" class="dash-a dash-downIcon" data-toggle="collapse">
								<div class="row dash-card-more">
									<div class="col-md-8 col-xs-8">
										<p class="dash-more toggle-more">View More</p>
									</div>
									<div class="col-md-4 col-xs-4">	
										<i class="fa fa-arrow-circle-down dash-more-icon pull-right"></i>
									</div>
								</div>
							</a>
							<div id="totalProdMore" class="collapse">
								<div class="content table-responsive void-tbl-pad void-tbl-pad-bottom">
									<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
										<thead>
											<th id="dash-prodM1">Branch</th>
											<th id="dash-prodM2">Low Stock</th>
											<th id="dash-prodM3">No Stock</th>
										</thead>
										<tbody>
											<tr>
												<td>Tomay</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Tomay Cuna Hardware' AND productQuantity < productAlerQuantity;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Tomay Cuna Hardware' AND productQuantity = 0;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
											<tr>
												<td>Naguilian</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Naguilian Cuna Hardware' AND productQuantity < productAlerQuantity;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Naguilian Cuna Hardware' AND productQuantity = 0;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
											<tr>
												<td>Quezon Hill</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Quezon Hill Cuna Hardware' AND productQuantity < productAlerQuantity;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.inventory WHERE productBranch = 'Quezon Hill Cuna Hardware' AND productQuantity = 0;";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END of Total Products -->
					
					<!-- Total Transactions -->
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="dash-card">
							<div class="row mrgn-bottom-10">
								<div class="col-md-4 col-xs-4 dash-icon">
									<i class="fa fa-handshake-o" aria-hidden="true"></i>
								</div>
								<div class="col-md-8 col-xs-8">
									<p class="dash-value ">151,142,162</p>
									<p class="dash-title ">Total Transactions</p>
								</div>
							</div>
							<a href="#totalTransMore" class="dash-a dash-downIcon" data-toggle="collapse">
								<div class="row dash-card-more">
									<div class="col-md-8 col-xs-8">
										<p class="dash-more toggle-more">View More</p>
									</div>
									<div class="col-md-4 col-xs-4">	
										<i class="fa fa-arrow-circle-down dash-more-icon pull-right"></i>
									</div>
								</div>
							</a>
							<div id="totalTransMore" class="collapse">
								<div class="content table-responsive void-tbl-pad void-tbl-pad-bottom">
									<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
										<thead>
											<th id="dash-transM1">Branch</th>
											<th id="dash-transM2">Pending</th>
											<th id="dash-transM3">Unpaid</th>
										</thead>
										<tbody>
											<tr>
												<td>Tomay</td>
												<td>3</td>
												<td>10</td>
											</tr>
											<tr>
												<td>Naguilian</td>
												<td>1</td>
												<td>4</td>
											</tr>
											<tr>
												<td>Quezon Hil</td>
												<td>1</td>
												<td>4</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END of Total Transactions -->
					
					<!-- Total Accounts -->
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="dash-card">
							<div class="row mrgn-bottom-10">
								<div class="col-md-4 col-xs-4 dash-icon">
									<i class="fa fa-group"></i>
								</div>
								<div class="col-md-8 col-xs-8">
									<p class="dash-value "><?php
										$sql = "SELECT COUNT(*) FROM cuna_hardware.accounts;";
										$result = $con->query($sql);
											if ($result->num_rows > 0) {
												while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
											} else { echo "0 results";}
									?></p>
									<p class="dash-title ">Total Accounts</p>
								</div>
							</div>
							<a href="#totalAccMore" class="dash-a dash-downIcon" data-toggle="collapse">
								<div class="row dash-card-more">
									<div class="col-md-8 col-xs-8">
										<p class="dash-more toggle-more">View More</p>
									</div>
									<div class="col-md-4 col-xs-4">	
										<i class="fa fa-arrow-circle-down dash-more-icon pull-right"></i>
									</div>
								</div>
							</a>
							<div id="totalAccMore" class="collapse">
								<div class="content table-responsive void-tbl-pad void-tbl-pad-bottom">
									<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
										<thead>
											<th id="dash-AccM1">Account Type</th>
											<th id="dash-AccM2">Number</th>
										</thead>
										<tbody>
											<tr>
												<td>Admin</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.accounts WHERE accountType  = 'admin';";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
											<tr>
												<td>Encoder</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.accounts WHERE accountType  = 'encoder';";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
											<tr>
												<td>Cashier</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.accounts WHERE accountType  = 'cashier';";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
											<tr>
												<td>Staff</td>
												<td><?php
													$sql = "SELECT COUNT(*) FROM cuna_hardware.accounts WHERE accountType  = 'staff';";
													$result = $con->query($sql);
														if ($result->num_rows > 0) {
															while($row = $result->fetch_assoc()) { echo " ".$row["COUNT(*)"] ." ";}
														} else { echo "0 results";}
												?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END of Total Accounts -->
				</div>
				
				<div class="row">
				<!-- Today's Sale -->
					<div class="col-md-8 col-sm-7 col-xs-12">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<h4 class="title">Today's Sales <small class="pull-right"><?php echo date('M d'); ?></small></h4>
									<hr class="bellow-hr">
								</div>
							<!-- Current Sales -->
								<div class="col-md-6 col-xs-6 border-right">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<p class="sale-icon"><i class="fa fa-money"></i></p>
											<p class="sale-value">552,552</p>
											<p class="sale-title">Total Sales</p>
										</div>
									</div>
									<div class="content table-responsive void-tbl-pad void-tbl-pad-bottom">
										<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
											<thead>
												<th id="sale-sold1">Branch</th>
												<th id="sale-sold2">Sales</th>
											</thead>
											<tbody>
												<tr>
													<td>Tomay</td>
													<td>222,552</td>
												</tr>
												<tr>
													<td>Naguillian</td>
													<td>330,000</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							<!-- END of Current Sales -->
								
							<!-- Current Number of Item Sold -->
								<div class="col-md-6 col-xs-6 border-left">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<p class="sale-icon"><i class="fa fa-shopping-basket"></i></p>
											<p class="sale-value">2,162</p>
											<p class="sale-title">Total Items Sold</p>
										</div>
									</div>
									<div class="content table-responsive void-tbl-pad void-tbl-pad-bottom">
										<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
											<thead>
												<th id="sale-item1">Branch</th>
												<th id="sale-item2">Items Sold</th>
											</thead>
											<tbody>
												<tr>
													<td>Tomay</td>
													<td>662</td>
												</tr>
												<tr>
													<td>Naguillian</td>
													<td>1,500</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							<!-- END of Current Number of Item Sold -->
							</div>
						</div>
					</div>
					<!-- END of Today's Sale -->
					
					<!-- Top Selling -->
					<div class="col-md-4 col-sm-5 col-xs-12">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<p class="dash-top"><i class="fa fa-trophy"></i></p>
									<p class="dash-top-title">Top Selling Product</p>
								</div>
							</div>
							<div class="content table-responsive void-tbl-pad dash-top-height void-tbl-pad-bottom">
								<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
									<thead>
										<th id="dash-topProd1">Rank</th>
										<th id="dash-topProd2">Product</th>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>4 in 1 Oil Single all purpose 120ml</td>
										</tr>
										<tr>
											<td>2</td>
											<td>Rain or Shine TX white 1 Liter</td>
										</tr>
										<tr>
											<td>3</td>
											<td>Steel Bar 2 inch</td>
										</tr>
										<tr>
											<td>4</td>
											<td>Steel Bar 2 inch</td>
										</tr>
										<tr>
											<td>5</td>
											<td>Steel Bar 2 inch</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- END of Top Selling -->
				</div>
				
				<!-- Different Branch Table -->
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<h4 class="title">Tomay Cuna Hardware</h4>
									<hr class="bellow-hr">
								</div>
							</div>
							<div class="row mrgn-bottom-10">
								<div class="col-md-12">
									<div class="pull-right">
										<div class="form-group search-div">
											<div class="icon-addon addon-sm">
												<input type="text" placeholder="Search.." name="tomaySearch" id="tomaySearch" class="form-control">
												<label class="fa fa-search" ></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="content table-responsive home-tbl-height void-tbl-pad" id="tomay_table">
								<table class="tbl-prpl table table-hover table-striped">
									<thead>
										<th class="home-table1">Date</th>
										<th class="home-table2">First</th>
										<th class="home-table3">Last</th>
										<th class="home-table4">Total Sales</th>
									</thead>
									<tbody>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 2,2017</td>
											<td>00121</td>
											<td>00180</td>
											<td>106,950</td>
										</tr>
										<tr>
											<td>June 3,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 4,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 5,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<div class="col-md-6 col-sm-6">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<h4 class="title">Naguilian Cuna Hardware</h4>
									<hr class="bellow-hr">
								</div>
							</div>
							<div class="row mrgn-bottom-10">
								<div class="col-md-12">
									<div class="pull-right">
										<div class="form-group search-div">
											<div class="icon-addon addon-sm">
												<input type="text" placeholder="Search.." name="naguilianSearch" id="naguilianSearch" class="form-control">
												<label class="fa fa-search" ></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="content table-responsive home-tbl-height void-tbl-pad" id="naguilian_table">
								<table class="tbl-prpl table table-hover table-striped">
									<thead>
										<th class="home-table1">Date</th>
										<th class="home-table2">First</th>
										<th class="home-table3">Last</th>
										<th class="home-table4">Total Sales</th>
									</thead>
									<tbody>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 2,2017</td>
											<td>00121</td>
											<td>00180</td>
											<td>106,950</td>
										</tr>
										<tr>
											<td>June 3,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 4,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 5,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- END of Different branch -->
				
                <div class="row">
					<!-- Yearly Progress x=month y=sales -->
					<div class="col-md-12">
						<div class="card">
							<div class="row">
								<div class="col-md-3 col-xs-3 col-centered">
									<select class="form-control void-slct-pad slct-fix-width">
										<option disabled selected> Select year...</option>
										<option>2017</option>
										<option>2018</option>
										<option>2019</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<img src="assets/img/graph.png" alt="yearly progress" id="temporary" class="logo">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 pull-right">
								<button class="btn btn-default btn-xs btn-block" type="submit" onclick="custom.showNotification('top','center')">Top Center</button>
								</div>
							</div>
						</div>
					</div>
					<!-- END of  Yearly Progress -->
				</div>
			<?php } ?> 
			
			
			<?php if($userRow['accountType'] == 'Cashier'){?>
			<div class="row">
				<!-- Today's Sale -->
					<div class="col-md-7 col-sm-7 col-xs-12">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<h4 class="title">Today's Sales <small class="pull-right"><?php echo date('M-d-Y'); ?></small></h4>
									<hr class="bellow-hr">
								</div>
							<!-- Current Sales -->
								<div class="col-md-6 col-xs-6 border-right">
									<div class="row">
										<div class="col-md-12 col-xs-12 mrgn-top-50">
											<p class="sale-icon"><i class="fa fa-money"></i></p>
											<p class="sale-value">552,552</p>
											<p class="sale-title">Total Sales</p>
										</div>
									</div>
								</div>
							<!-- END of Current Sales -->
								
							<!-- Current Number of Item Sold -->
								<div class="col-md-6 col-xs-6 border-left">
									<div class="row">
										<div class="col-md-12 col-xs-12 mrgn-top-50">
											<p class="sale-icon"><i class="fa fa-shopping-basket"></i></p>
											<p class="sale-value">2,162</p>
											<p class="sale-title">Total Items Sold</p>
										</div>
									</div>
								</div>
							<!-- END of Current Number of Item Sold -->
							</div>
						</div>
					</div>
					<!-- END of Today's Sale -->
					
					<!-- Top Selling -->
					<div class="col-md-5 col-sm-5 col-xs-12">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<p class="dash-top"><i class="fa fa-trophy"></i></p>
									<p class="dash-top-title">Top Selling Product</p>
								</div>
							</div>
							<div class="content table-responsive void-tbl-pad dash-top-height void-tbl-pad-bottom">
								<table class="dash-tbl table table-hover void-tbl-mrgn-bottom table-striped">
									<thead>
										<th id="dash-topProd1">Rank</th>
										<th id="dash-topProd2">Product</th>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>4 in 1 Oil Single all purpose 120ml</td>
										</tr>
										<tr>
											<td>2</td>
											<td>Rain or Shine TX white 1 Liter</td>
										</tr>
										<tr>
											<td>3</td>
											<td>Steel Bar 2 inch</td>
										</tr>
										<tr>
											<td>4</td>
											<td>Steel Bar 2 inch</td>
										</tr>
										<tr>
											<td>5</td>
											<td>Steel Bar 2 inch</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- END of Top Selling -->
				</div>
				
				<!-- Records of Daily Earning of branch -->
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="row">
								<div class="col-md-12">
									<h4 class="title">Daily Sales (<?php echo $userRow['userBranch'] ?>) </h4>
									<hr class="bellow-hr">
								</div>
							</div>
							<div class="row mrgn-bottom-10">
								<div class="col-md-12">
									<div class="pull-right">
										<div class="form-group search-div">
											<div class="icon-addon addon-sm">
												<input type="text" placeholder="Search.." name="homeDailySearch" id="homeDailySearch" class="form-control">
												<label class="fa fa-search" ></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="content table-responsive home-tbl-height void-tbl-pad" id="homeDaily_table">
								<table class="tbl-prpl table table-hover table-striped">
									<thead>
										<th class="home-table1">Date</th>
										<th class="home-table2">First</th>
										<th class="home-table3">Last</th>
										<th class="home-table4">Total Sales</th>
									</thead>
									<tbody>
										<tr>
											<td>June 1,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 2,2017</td>
											<td>00121</td>
											<td>00180</td>
											<td>106,950</td>
										</tr>
										<tr>
											<td>June 3,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 4,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
										<tr>
											<td>June 5,2017</td>
											<td>00001</td>
											<td>00121</td>
											<td>56,950</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- END of Records of Daily Earning of branch -->
			<?php } ?> 			
			</div>
		</div>
	<!-- End of Page Content -->
    </div>
</div>

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