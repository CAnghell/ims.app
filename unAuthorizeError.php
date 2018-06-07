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

	<!-- CSS custom made 	-->
	<link href="assets/css/custom.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>

<body class="index-back">
<?php
	ob_start();
	session_start();
	require_once 'server.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}

?>
<div class="wrapper">
	<div class="col-md-12 centered mrgn-top-30">
		<div class="errorContainer col-md-6 col-sm-7 col-xm-10">
			<div class="row">
				<img src="assets/img/cunaLogoError.png" alt="Cuna Icon" id="errorLogo">
				<h1 id="errorNumber">401</h1>
				<h3 id="errorTitle">Unauthorized Access</h3><hr>
				<p class="errorSentence">Sorry, but you're not authorized to access the requested page.</p>
				<p class="errorSentence">Please contact Cuna Hardware administrator if you think this is a mistake.</p>
				<?php echo "<a href=\"javascript:history.go(-1)\">GO BACK</a>"; ?>
			</div>
		</div>
	</div>
</div>
</body>


    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	
	<!-- JS Custom made -->
	<script src="assets/js/custom.js"></script>
</html>
<?php ob_end_flush(); ?>