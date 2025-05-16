<?php
	session_start();

	if (!isset($_SESSION["uid"])) {
		header("location:index.php");
	}

	// Get the transaction ID either from URL param or POST
	$trx_id = isset($_GET["trx_id"]) ? $_GET["trx_id"] : (isset($_POST["trx_id"]) ? $_POST["trx_id"] : "");
	
	if (!empty($trx_id)) {
		include_once("db.php");
		
		// Check if the order exists with this transaction ID
		$sql = "SELECT * FROM orders WHERE trx_id = '$trx_id' LIMIT 1";
		$query = mysqli_query($con, $sql);
		
		if (mysqli_num_rows($query) > 0) {
			// Order exists - show thank you page
?>
			<!DOCTYPE html>
			<html>
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>LevelUp | Order Confirmation</title>

					<!-- Bootstrap and jQuery Integrated -->
					<link rel="stylesheet" href="css/bootstrap.min.css" />
					<script src="js/jquery2.js"></script>
					<script src="js/bootstrap.min.js"></script>

					<!-- External JS Integrated -->
					<script src="main.js"></script>

					<!-- Internal CSS Integrated -->
					<style>
						table tr td {
							padding: 10px;
						}
					</style>
				</head>
				<body>
					<div class="navbar navbar-inverse navbar-fixed-top">
						<div class="container-fluid">
							<div class="navbar-header">
								<a href="#" class="navbar-brand">LevelUp</a>
							</div>
							<ul class="nav navbar-nav">
								<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
								<li><a href="profile.php"><span class="glyphicon glyphicon-modal-window"></span> Product</a></li>
							</ul>
						</div>
					</div>

					<p><br></p>
					<p><br></p>
					<p><br></p>

					<div class="container-fluid">
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="panel panel-default">
									<div class="panel-heading"></div>
									<div class="panel-body">
										<h1>Thank you for your order!</h1>
										<hr />
										<p>
											Hello <?php echo "<strong>" . $_SESSION["name"] . "</strong>"; ?>,<br>
											Your order has been placed successfully and your Order ID is
											<strong><?php echo $trx_id; ?></strong><br />
											You can continue your Shopping <br />
										</p>
										<a href="index.php" class="btn btn-success btn-lg">Continue Shopping</a>
									</div>
									<div class="panel-footer"></div>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
					</div>
				</body>
			</html>
<?php
		} else {
			// Order doesn't exist - redirect to home
			header("location: index.php");
		}
	} else {
		// No transaction ID - redirect to home
		header("location: index.php");
	}
?>