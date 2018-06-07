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
	if($userRow['accountType'] == 'Encoder' || $userRow['accountType'] == 'Cashier' || $userRow['accountType'] == 'Staff'){
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
					<li>
						<a href="inventory.php">
							<i class="pe-7s-note2"></i>
							<p>Inventory</p>
						</a>
					</li>
				<?php } ?>
				<?php if($userRow['accountType'] == 'Admin' ){ ?> 
					<li  class='active'>
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
                    <div  class="navbar-brand" href="#"> Accounts</div>
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
							<div class="row">
								<div class="col-md-8 col-sm-6 col-xs-8">
									<h4 class="title">Account List</h4>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-4">
									<div class="pull-right">
										<a href="newAccount.php">
											<button class="btn btn-prpl btn-sm">
												<i class="fa fa-user-plus"></i> Add New Account 
											</button>
										</a>
									</div>
									
								</div>
							</div>
							<!-- Search account -->
							<div class="row">
								<div class="col-md-12 mrgn-top-10 mrgn-bottom-10">
									<div class="pull-right">
										<div class="form-group search-div">
											<div class="icon-addon addon-sm">
												<input type="text" placeholder="Search.." name="accountSearch" id="accountSearch" class="form-control">
												<label class="fa fa-search" ></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="content table-responsive tbl-height void-tbl-pad" id="account_table">
                                <table class="tbl-prpl table table-hover table-striped">
                                    <thead>
                                        <th class="acct-list1">Username</th>
                                    	<th class="acct-list2">Account Type</th>
										<th class="acct-list3">Branch</th>
										<th class="acct-list4">Action</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        	<?php
											$sql = "SELECT * FROM cuna_hardware.accounts ORDER BY username;";
											$result = $con->query($sql);

											if ($result->num_rows > 0) {
												// output data of each row
												while($row = $result->fetch_assoc()) {
													echo "
													<tr> 	
														<td class='acct-list1'> ". $row["username"]. " </td>
														<td class='acct-list2'> ". $row["accountType"] . " </td>
														<td class='acct-list3'> ". $row["userBranch"]. " </td>
														<td class='acct-list4'><a href='accountInformation.php?id=" . $row['userId'] . "'>
															<button type='button' class='btn btn-info btn-fill btn-xs'>Update</button></a></td>
													</tr>";
												}
											} else {
												echo "0 results";
											}
											
											?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
							<!-- END of List of accounts table -->
                        </div>
                    </div>
                </div>
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