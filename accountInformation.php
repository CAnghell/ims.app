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

<body onload="onSelectAcctType()">
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
	
	//  assigns values from account table database to php global variables based on userId
	$id = $_GET['id'];
	$sql = "SELECT * FROM cuna_hardware.accounts Where userId ='" . $id . "'";
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$username = $row["username"];
			$password = $row["password"];
			$accountType = $row["accountType"];
			$userBranch  = $row["userBranch"];
		}
	} else {
		echo "0 results";
	}
	
	//set validation error flag as false
	$error = false;
	
	
	// check if form is submitted, this updates selected account information
	if(isset($_POST['acctUpdate_btn'])){
		
		$updateUsername = mysqli_real_escape_string($con, $_POST['updateUsername']);
		$updatePassword = mysqli_real_escape_string($con, $_POST['updatePassword']);
		$newPass = mysqli_real_escape_string($con, $_POST['newPass']);
		$newConfPass = mysqli_real_escape_string($con, $_POST['newConfPass']);
		$updateAccountType = !empty($_POST['updateAccountType']) ? $_POST['updateAccountType'] : '';
		$updateUserBranch = !empty($_POST['updateUserBranch']) ? $_POST['updateUserBranch'] : '';
		
		
		// VALIDATIONS OF THE FIELDS
		// account type validation and branch 
		if (empty($updateAccountType)) {
			$error = true;
			$updateAccountType_error = "Please select an account type.";
		}
		if (($updateAccountType == 'Cashier') && empty($updateUserBranch)) {
			$error = true;
			$updateUserBranch_error = "Cashier requires a branch.";
		}
		if (($updateAccountType == 'Staff') && empty($updateUserBranch)) {
			$error = true;
			$updateUserBranch_error = "Staff requires a branch.";
		}
		if (($updateAccountType == 'Admin')) {
			$updateUserBranch = "";
			if (($updateAccountType == 'Admin') && ($updateUserBranch != "")) {
				$error = true;
				$updateUserBranch_error = "Admin cannot have a branch.";
			}
		}
		if (($updateAccountType == 'Encoder')) {
			$updateUserBranch = "";
			if (($updateAccountType == 'Encoder') && ($updateUserBranch != "")) {
				$error = true;
				$updateUserBranch_error = "Encoder cannot have a branch.";
			}
		}
		
		// Change password validations
		if ($newPass !=""){
			if (strlen($newPass) < 5){
				$error = true;
				$newPass_error = "Password must be minimum of 5 characters";
			}
			if ($newPass != $newConfPass){
				$error = true;
				$newConfPass_error = "Password and Confirm Password doesn't match";
			}
		}
		
		// Check if the admin change the password
		if ($newPass !=""){
			$updatePassword = $newPass;
		} 
			
		// if there's no error, continue to signup
		if ($error == false) {
			$query = "UPDATE accounts SET password ='$updatePassword',accountType ='$updateAccountType',userBranch = '$updateUserBranch' WHERE userID ='$id';";
			$res = mysqli_query($con, $query);
			
			if ($res){
				$errorType = "success";
				$errorMsg = "Account is successfully updated! ";
					// requery to show the updated account Information
					$res=mysqli_query($con, "SELECT * FROM accounts WHERE userId=".$_SESSION['user']);
					$userRow=mysqli_fetch_array($res);
					$id = $_GET['id'];
					$sql = "SELECT * FROM cuna_hardware.accounts WHERE userId ='" . $id . "'";
					$result = $con->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							$username = $row["username"];
							$password = $row["password"];
							$accountType = $row["accountType"];
							$userBranch  = $row["userBranch"];
						}
					} else {
						echo "0 results";
					}
			} else {
				$errorType = "danger";
				$errorMsg = "Something went wrong, try again later...";
			}
		}
	}
	
	// deletes account user
	if(isset($_POST['acctDelete_btn'])){
		$query = "DELETE FROM cuna_hardware.accounts WHERE userId ='" . $id . "'";
		$res = mysqli_query($con, $query);

	//Inform if the account is successfully deleted 
		header("Location: account.php");
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
                    <div class="navbar-brand" href="#">Accounts / View Account</div>
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
				
				<!-- Edit account update content -->
					<div class="col-md-12">
                        <div class="card">
							<div class="row">
								<div class="col-md-8 col-sm-6 col-xs-8">
									<h4 class="title">Account Information</h4>
								</div>
								<div class="col-md-4 col-sm-6 col-xs-4">
									<div class="pull-right">
										<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#deleteAccountModal">
											<i class="fa fa-user-times"></i> Delete Account 
										</button>
									</div>
								</div>
							</div>
							<div class="content">
                                <form action="" method="post">
									<?php if ( isset($errorMsg) ) { ?>
										<div class="form-group">
											<div class="alert alert-<?php echo ($errorType=="success") ? "success" : $errorType; ?>">
												<span class="fa fa-bell"></span> <?php echo $errorMsg; ?>
											</div>
										</div>
									<?php } ?>
									<div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-7">
                                            <div class="form-group">
                                                <label>Username</label>
												<input type="text" name="updateUsername" class="form-control" value="<?php echo $username;?>" readonly>
                                            </div>
                                        </div>
										<div class="col-md-6 col-sm-6 col-xs-5">
                                            <div class="form-group">
                                                <label>Password</label>
												<div class="input-group">
													<input type="password" id="pwd" name="updatePassword" class="form-control" value="<?php echo $password;?>" readonly>
													<span class="input-group-btn"><button type="button" id="showPass" class="btn btn-secondary">
														<i class="fa fa-eye"></i>
													</button></span>
												</div>
                                            </div>
                                        </div>
									</div>

                                     <div class="row">
										<div class="col-md-6 col-sm-6 col-xs-12 act-type-pad">
                                            <div class="form-group">
                                                <label>Account Type</label>
                                                <select class="form-control" id="selectedAcctType" name="updateAccountType" onchange="selectAcctType(this)">
													<option  selected value ="<?php echo $accountType;?>"><?php echo $accountType;?></option>
													<?php
														$sql = "SELECT * FROM cuna_hardware.account_type;";
														$result = $con->query($sql);

														if ($result->num_rows > 0) {
															// output data of each row
															while($row = $result->fetch_assoc()) {
																if ($accountType != $row["accountType"]){
																echo "
																	<option> ". $row["accountType"]. " </option>";	
																}
															}
														} else {
															echo "0 results";
														}
													?>
												</select>
												<span class="text-danger"><?php if (isset($updateAccountType_error)) echo $updateAccountType_error; ?></span>
                                            </div>
                                        </div>
										<div id="acct_branch">
											<div class="col-md-6 col-sm-6 col-xs-12 act-branch-pad">
												<div class="form-group">
													<label>Branch</label>
													<select class="form-control" name="updateUserBranch">
														<option selected value ="<?php echo $userBranch;?>"><?php echo $userBranch;?></option>
														<?php
															$sql = "SELECT * FROM cuna_hardware.branch;";
															$result = $con->query($sql);

															if ($result->num_rows > 0) {
																// output data of each row
																while($row = $result->fetch_assoc()) {
																	if ($userBranch != $row["branchName"]){
																		echo "
																			<option> ". $row["branchName"]. " </option>";	
																	}
																}
															} else {
																echo "0 results";
															}
														?>
													</select>
													<span class="text-danger"><?php if (isset($updateUserBranch_error)) echo $updateUserBranch_error; ?></span>
												</div>
											</div>
										</div>
                                    </div>

									<div class="row">
										<div class="col-md-12 ">
                                            <div class="form-group label-border-bottom">
                                                <label class="label-title">Change Password</label>
											</div>
										</div>
									</div>	
									
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-5">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="password" name="newPass" class="form-control" placeholder="Password">
												<span class="text-danger"><?php if (isset($newPass_error)) echo $newPass_error; ?></span>
                                            </div>
                                        </div>
										<div class="col-md-6 col-sm-6 col-xs-7">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="password" name="newConfPass" class="form-control" placeholder="Confirm Password">
												<span class="text-danger"><?php if (isset($newConfPass_error)) echo $newConfPass_error; ?></span>
											</div>
                                        </div>
                                    </div>
									<div class="row pull-right">
										<a href="account.php">
											<button type="button" class="btn btn-default btn-fill btn-sm"> Back </button>
										</a>
										<?php echo" <a href='accountInformation.php?id=" . $id . "'> "?>
											<input type="submit" class="btn btn-info btn-fill btn-sm" name="acctUpdate_btn" value="Update">
										<?php echo "</a>" ?>
									</div>
									
                                    <div class="clearfix"></div>
                                </form>
                            </div>
						</div>
					</div>
				<!-- END of Account update content -->
                </div>
            </div>
        </div>
	<!-- End of Page Content -->
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade	" id="deleteAccountModal" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete Confirmation</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete the account "<?php echo $username; ?>"?</p>
			</div>
			<div class="modal-footer">
				<form action="" method="post">
					<button type="button" class="btn btn-default btn-fill btn-sm" data-dismiss="modal">Cancel</button>
					<input type="submit" name="acctDelete_btn" class="btn btn-danger btn-fill btn-sm" value="Confirm">
				</form>
			</div>
		</div>
	</div>
</div>
<!-- END of Delete Modal -->		  

</body>
	<script>
		document.getElementById("showPass").addEventListener("click", function(e){
			var pwd = document.getElementById("pwd");
			if(pwd.getAttribute("type")=="password"){
				pwd.setAttribute("type","text");
			} else {
				pwd.setAttribute("type","password");
			}
		});
	</script>
	
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