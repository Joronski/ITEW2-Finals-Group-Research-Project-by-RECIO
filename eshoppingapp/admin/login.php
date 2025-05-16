<?php
	session_start();

	// Include database connection
	$db_path = __DIR__ . "/db.php";

	// Check if db.php exists at the expected location
	if (file_exists($db_path)) {
		require_once($db_path);
	} else {
		// If db.php doesn't exist in the current directory, try parent directory
		$db_path_alt = dirname(__DIR__) . "/db.php";
		if (file_exists($db_path_alt)) {
			require_once($db_path_alt);
		} else {
			die("Database connection file not found. Please make sure db.php exists.");
		}
	}

	// Check if already logged in
	if (isset($_SESSION['admin_id'])) {
		header("location:index.php");
		exit();
	}

	// Check for logout message
	$logout_message = "";
	if (isset($_SESSION['logout_message'])) {
		$logout_message = $_SESSION['logout_message'];
		unset($_SESSION['logout_message']);
	}

	// Process login request
	if (isset($_POST['admin_login']) && $_POST['admin_login'] == 1) {
		// Simple validation
		if (empty($_POST['email']) || empty($_POST['password'])) {
			echo json_encode(["status" => "error", "message" => "Please fill all required fields"]);
			exit();
		}

		$email = mysqli_real_escape_string($con, $_POST['email']);
		$password = $_POST['password'];

		// Check user in database
		$query = "SELECT * FROM admin WHERE email = '$email'";
		$result = mysqli_query($con, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);

			// Verify password
			if (password_verify($password, $row['password'])) {
				// Check if account is active
				if ($row['is_active'] == '1') {
					// Success - create session
					$_SESSION['admin_id'] = $row['id'];
					$_SESSION['admin_name'] = $row['name'];
					$_SESSION['admin_email'] = $row['email'];

					echo json_encode(["status" => "success", "message" => "Login successful"]);
				} else {
					echo json_encode(["status" => "error", "message" => "Your account is not active. Please contact administrator."]);
				}
			} else {
				echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
			}
		} else {
			echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
		}
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>LevelUp | Admin</title>

		<link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/dashboard/">

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

		<!-- Internal CSS Integration -->
		<style>
			.fa-trash-alt,
			.fa-pencil-alt {
				color: #fff;
			}

			.bd-placeholder-img {
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
			}

			@media (min-width: 768px) {
				.bd-placeholder-img-lg {
					font-size: 3.5rem;
				}
			}
		</style>

		<!-- Custom styles for this template -->
		<link href="./css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">LevelUp Admin</a>

			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<?php
					if (isset($_SESSION['admin_id'])) {
					?>
						<a class="nav-link" href="../admin/admin-logout.php">Sign out</a>

						<?php
					} else {
						$uriAr = explode("/", $_SERVER['REQUEST_URI']);
						$page = end($uriAr);
						if ($page === "login.php") {
						?>

							<a class="nav-link" href="../admin/register.php">Register</a>

						<?php
						} else {
						?>
							<a class="nav-link" href="../admin/login.php">Login</a>
					<?php
						}
					}
					?>
				</li>
			</ul>
		</nav>

		<div class="container">
			<div class="row justify-content-center" style="margin:100px 0;">
				<div class="col-md-4">
					<h4 class="text-center">Admin Login</h4>
					<p class="message">
						<?php if (!empty($logout_message)): ?>
					<div class="alert alert-success"><?php echo $logout_message; ?></div>
				<?php endif; ?>
				</p>

				<form id="admin-login-form">
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
					</div>

					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="Password">
					</div>

					<input type="hidden" name="admin_login" value="1">
					<button type="button" class="btn btn-success login-btn">Login</button>
				</form>
				</div>
			</div>
		</div>

		<!-- Bootstrap JS and jQuery Integration -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

		<!-- Integrated External JS -->
		<script src="./js/dashboard.js"></script>
		<script type="text/javascript" src="./js/main.js"></script>

		<!-- Integrated Internal JS -->
		<script type="text/javascript">
			$(document).ready(function() {
				$(".login-btn").on("click", function() {
					var email = $("#email").val();
					var password = $("#password").val();

					if (email == "" || password == "") {
						$(".message").html("<div class='alert alert-danger'>Please fill all fields</div>");
						return;
					}

					$.ajax({
						url: 'login.php',
						method: 'POST',
						data: {
							email: email,
							password: password,
							admin_login: 1
						},
						dataType: 'json',
						success: function(response) {
							if (response.status == "success") {
								$(".message").html("<div class='alert alert-success'>" + response.message + "</div>");
								setTimeout(function() {
									window.location = "index.php";
								}, 1000);
							} else {
								$(".message").html("<div class='alert alert-danger'>" + response.message + "</div>");
							}
						}
					});
				});
			});
		</script>
	</body>
</html>