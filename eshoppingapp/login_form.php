<?php
	// This is Login form page, if user is already logged in then we will not allow user to access this page by executing isset($_SESSION["uid"]). If below statement returns true then we will send user to their profile.php page
	if (isset($_SESSION["uid"])) {
		header("location:profile.php");
	}

	// In action.php page if user clicks on "ready to checkout" button that time we will pass data in a form from action.php page
	if (isset($_POST["login_user_with_product"])) {
		// This is product list array
		$product_list = $_POST["product_id"];

		// Here we are converting array into json format because array cannot be stored in cookie
		$json_e = json_encode($product_list);

		// Here we are creating cookie and name of cookie is product_list
		setcookie("product_list", $json_e, strtotime("+1 day"), "/", "", "", TRUE);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>LevelUp | Login</title>

		<!-- Bootstrap and jQuery Integrated -->
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script src="js/jquery2.js"></script>
		<script src="js/bootstrap.min.js"></script>

		<!-- External JS Integration -->
		<script src="main.js"></script>

		<!-- Implementing External CSS -->
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="wait overlay">
			<div class="loader"></div>
		</div>

		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="#" class="navbar-brand">LevelUp</a>
				</div>
				<ul class="nav navbar-nav">
					<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
					<li><a href="index.php"><span class="glyphicon glyphicon-modal-window"></span> Product</a></li>
				</ul>
			</div>
		</div>

		<p><br></p>
		<p><br></p>
		<p><br></p>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8" id="signup_msg">
					<!--Alert from signup form-->
				</div>
				<div class="col-md-2"></div>
			</div>

			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<div class="panel panel-primary">
						<div class="panel-heading">Customer Login Form</div>
						<div class="panel-body">
							<!-- User Login Form -->
							<form onsubmit="return false" id="login">
								<label for="email">Email</label>
								<input type="email" class="form-control" name="email" id="email" required />

								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" id="password" required />
								<p><br></p>

								<a href="#" style="color:#333; list-style:none;">Forgotten Password</a>
								<input type="submit" class="btn btn-success" style="float:right;" Value="Login">

								<!-- If user doesn't have an account then he/she will click on create account button -->
								<div><a href="customer_registration.php?register=1">Create a new account?</a></div>
							</form>
						</div>
						<div class="panel-footer">
							<div id="e_msg"></div>
						</div>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</body>
</html>