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

	include_once 'server.php';
	
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
	
	//set validation error flag as false
	$error = false;
	
	//check if form is submitted
	if (isset($_POST['acctCreate_btn'])) {
		$username = mysqli_real_escape_string($con, $_POST['username']);
		$password = mysqli_real_escape_string($con, $_POST['password']);
		$cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
		$acctType = !empty($_POST['acctType']) ? $_POST['acctType'] : '';
		$acctBranch = !empty($_POST['acctBranch']) ? $_POST['acctBranch'] : '';
		
		// VALIDATIONS OF THE FIELDS
		// username validations
		$userQuery = mysqli_query($con, "SELECT username FROM cuna_hardware.accounts WHERE username='".$username."'");
		if (mysqli_num_rows($userQuery) != 0){
			$error = true;
			$username_error = "Username already exists";
		} else if (empty($username)) {
			$error = true;
			$username_error = "Please enter a username.";
		} else if (strlen($username) < 5) {
			$error = true;
			$username_error = "Name must have atleat 5 characters.";
		} 
		
		// password validations
		if (empty($password)){
			$error = true;
			$password_error = "Please enter password.";
		} else if(strlen($password) < 5) {
			$error = true;
			$password_error = "Password must be minimum of 5 characters";
		}
	
		if($password != $cpassword) {
			$error = true;
			$cpassword_error = "Password and Confirm Password doesn't match";
		}
		
		// account type validation and branch
		if (empty($acctType)) {
			$error = true;
			$acctType_error = "Please select an account type.";
		} 
		if (($acctType == 'Cashier') && empty($acctBranch)) {
			$error = true;
			$acctBranch_error = "Cashier requires a branch.";
		} 
		if (($acctType == 'Staff') && empty($acctBranch)) {
			$error = true;
			$acctBranch_error = "Staff requires a branch.";
		} 
		if (($acctType == 'Admin')) {
			$acctBranch = "";
			if (($acctType == 'Admin') && ($acctBranch != "")) {
				$error = true;
				$acctBranch_error = "Admin cannot have a branch.";
			}
		}
		if (($acctType == 'Encoder')) {
			$acctBranch = "";
			if (($acctType == 'Encoder') && ($acctBranch != "")) {
				$error = true;
				$acctBranch_error = "Encoder cannot have a branch.";
			}
		}
		
		// password encrypt using SHA256();
		//$password = hash('sha256', $password);
		
		// if there's no error, continue to signup
		if (!$error) {
			$query = "INSERT INTO accounts(username,password,accountType,userBranch) VALUES('$username','$password','$acctType','$acctBranch')";
			$res = mysqli_query($con, $query);
			
			if ($res){
				$errorType = "success";
				$errorMsg = "Account is successfully created! ";
				unset($username);
				unset($password);
				unset($acctType);
			} else {
				$errorType = "danger";
				$errorMsg = "Something went wrong, try again later...";
			}
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
					<li class='active'>
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
                    <div class="navbar-brand" href="#"> Accounts / Add New Account </div>
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
								<div class="col-md-12">
									<h4 class="title">Account Creation</h4>
								</div>
							</div>
					
							<!-- New account requirements -->
							<div class="content">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
									<?php if ( isset($errorMsg) ) { ?>
										<div class="form-group">
											<div class="alert alert-<?php echo ($errorType=="success") ? "success" : $errorType; ?>">
												<span class="fa fa-bell"></span> <?php echo $errorMsg; ?>
											</div>
										</div>
									<?php } ?>
									<div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" name="username" class="form-control" placeholder="Username" autofocus="true" value="<?php if($error) echo $username; ?>"/>
												<span class="text-danger"><?php if (isset($username_error)) echo $username_error; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" name="password" placeholder="Password" class="form-control" />
												<span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                                            </div>
                                        </div>
										 <div class="col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
												<label>Confirm Password</label>
                                                <input type="password" name="cpassword" placeholder="Confirm Password" class="form-control" />
												<span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
											</div>
                                        </div>
                                    </div>

                                    <div class="row">
										<div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Account Type</label>
                                                <select class="form-control" name="acctType" id="acctType" onchange="selectAcctType(this)" >
													<option disabled selected value ="">Select account type... </option>
													<?php
														$sql = "SELECT * FROM cuna_hardware.account_type;";
														$result = $con->query($sql);

														if ($result->num_rows > 0) {
															// output data of each row
															while($row = $result->fetch_assoc()) {
																echo "
																	<option> ". $row["accountType"]. " </option>";	
															}
														} else {
															echo "0 results";
														}
													?>
												</select>
												<span class="text-danger"><?php if (isset($acctType_error)) echo $acctType_error; ?></span>
                                            </div>
                                        </div>
										<div id="acct_branch">
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div class="form-group">
													<label>Branch</label>
													<select class="form-control" name="acctBranch" >
														<option disabled selected value ="">Select branch... </option>
														<?php
														$sql = "SELECT * FROM cuna_hardware.branch;";
														$result = $con->query($sql);

														if ($result->num_rows > 0) {
															// output data of each row
															while($row = $result->fetch_assoc()) {
																echo "
																	<option> ". $row["branchName"]. " </option>";	
															}
														} else {
															echo "0 results";
														}
													?>
													</select>
													<span class="text-danger"><?php if (isset($acctBranch_error)) echo $acctBranch_error; ?></span>
												</div>
											</div>
										</div>
                                    </div>
									
									<div class="row pull-right">
										<a href="account.php">
											<input type="button" class="btn btn-default btn-fill btn-sm mrgn-right-10" value="Cancel">
										</a> 
										<input type="submit" name="acctCreate_btn"  class="btn btn-success btn-fill btn-sm" value="Create">
									</div>
									
                                    <div class="clearfix"></div>
                                </form>
                            </div>
							<!-- END of New account requirements -->
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