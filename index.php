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

    <!--     Fonts and icons     -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>

<body class="index-back">
<?php
	ob_start();
	session_start();
	require_once 'server.php';
	
	// it will never let you open index(login) page if session is set
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: home.php");
		exit;
	}
	
	$error = false;
	
	if (isset($_POST['login_btn'])) {
		
		// prevent sql injections/ clear user invalid inputs
		$username = trim($_POST['username']);
		$username = strip_tags($username);
		$username = htmlspecialchars($username);
		
		$password = trim($_POST['password']);
		$password = strip_tags($password);
		$password = htmlspecialchars($password);
		
		// prevent sql injections / clear user invalid inputs
		if(empty($username)){
			$error = true;
			$username_error = "Please enter your username.";
		} 
		
		if(empty($password)){
			$error = true;
			$password_error = "Please enter your password.";
		}
		
		// if there's no error, continue to login
		if (!$error) {
			
			//$password = hash('sha256', $password); // password hashing using SHA256
		
			$res=mysqli_query($con, "SELECT userId, username, password FROM accounts WHERE username='$username'");
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row
			
			if( $count == 1 && $row['password']==$password ) {
				$_SESSION['user'] = $row['userId'];

				$sql = "SELECT * FROM cuna_hardware.accounts Where userId ='" . $_SESSION['user'] . "'";
				$result = $con->query($sql);
			
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						$accountType = $row["accountType"];
					}
				} else {
					echo "0 results";
				}
				
				if($accountType == 'Staff' || $accountType == 'Encoder'){
					header("Location: inventory.php");
				} else {
					header("Location: home.php");
				}

			} else {
				$errorMsg = "Incorrect Credentials, Try again...";
			}
		}
	}
?>

	<div class="carousel slide carousel-fade" data-ride="carousel">
		<div class="carousel-inner" role="listbox">
			<div class="item active">
			</div>
			<div class="item">
			</div>
			<div class="item">
			</div>
		</div>
	</div>
	<div class="wrapper">
		<div class="top">
			<div class="col-md-12 centered">
				<div class="col-md-4 col-sm-7 col-xm-10">
					<div class="row pad-top-20">
						<img src="assets/img/cunaLogo.png" alt="Cuna Icon" id="indexLogo" class="logo">
						<img src="assets/img/cunaIcon.png" alt="Cuna Icon" id="indexIcon" class="logo">
						<div class="login-box">
							<div class="box-header">
								<h2 id="index-head">Log In</h2>
							</div>
							<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="col-md-11 col-xs-11 col-centered center-block pad-top-10  backDesign">
								<?php if ( isset($errorMsg) ) { ?>
									<div class="form-group">
										<div class="alert alert-danger">
											<span class="fa fa-bell"></span> <?php echo $errorMsg; ?>
										</div>
									</div>
								<?php }?>
								<div class="form-group">
									<input type="text" class="form-control index-input" name="username" placeholder="Username" autofocus="true" value="<?php if($error) echo $username; ?>"/>
									<span class="text-danger"><?php if (isset($username_error)) echo $username_error; ?></span>
								</div>
								<div class="form-group">
									<input type="password" class="form-control index-input" name="password" placeholder="Password"/>
									<span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
								</div>
								<div class="form-group">
									<button type="submit" class="index-btn" name="login_btn">SIGN IN</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
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