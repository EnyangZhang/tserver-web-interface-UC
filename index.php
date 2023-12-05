<?php
	require_once(__DIR__.'/lib/User.php');
		
	// Session cookie
	session_start();
    if (isset($_SESSION['curr_user'])) {
        // User is already logged in and redirects them to the home page
        header("Location: pages/home.php");
    } else {
		// User log in
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$curr_user = new User($_POST['username']);
			if ($curr_user->login($_POST['password'])) {
				$_SESSION['curr_user'] = serialize($curr_user);
				UpdateActivity();
                header("Location: pages/home.php");
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Tserver Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<link rel="icon" type="image/png" href="img/icons/lock.ico"/>

	<link rel="stylesheet" type="text/css" href="css/login/util.css">
	<link rel="stylesheet" type="text/css" href="css/login/main.css">

	<link rel="stylesheet" type="text/css" href="js/login/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="js/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="js/login/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="js/login/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="js/login/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="js/login/vendor/animsition/css/animsition.min.css">
<!--	<link rel="stylesheet" type="text/css" href="js/login/vendor/select2/select2.min.css">-->
<!--	<link rel="stylesheet" type="text/css" href="js/login/vendor/daterangepicker/daterangepicker.css">-->
</head>
<body>	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('img/background.jpg');">
			<div class="wrap-login100")>
				<form class="login100-form validate-form" method=post>
					<img class="login100-form-logo" src="img/icons/uni-canterbury-logo.jpg"/>

					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="js/login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="js/login/vendor/animsition/js/animsition.min.js"></script>
	<script src="js/login/vendor/bootstrap/js/popper.js"></script>
	<script src="js/login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/login/vendor/select2/select2.min.js"></script>
<!--	<script src="js/login/vendor/daterangepicker/moment.min.js"></script>-->
<!--	<script src="js/login/vendor/daterangepicker/daterangepicker.js"></script>-->
<!--	<script src="js/login/vendor/countdowntime/countdowntime.js"></script>-->
	<script src="js/login/main.js"></script>

</body>
</html>