<?php
	session_start();

	$ip_add = getenv("REMOTE_ADDR");
	include "db.php";

	if (isset($_POST["category"])) {
		$category_query = "SELECT * FROM categories";
		$run_query = mysqli_query($con, $category_query) or die(mysqli_error($con));

		echo "
							<div class='nav nav-pills nav-stacked'>
								<li class='active'><a href='#'><h4>Product Categories</h4></a></li>
						";

		if (mysqli_num_rows($run_query) > 0) {
			while ($row = mysqli_fetch_array($run_query)) {
				$cid = $row["cat_id"];
				$cat_name = $row["cat_title"];

				echo "
									<li><a href='#' class='category' cid='$cid'>$cat_name</a></li>
								";
			}

			echo "</div>";
		}
	}

	if (isset($_POST["brand"])) {
		$brand_query = "SELECT * FROM brands";
		$run_query = mysqli_query($con, $brand_query);

		echo "
							<div class='nav nav-pills nav-stacked'>
								<li class='active'><a href='#'><h4>Brands</h4></a></li>
						";

		if (mysqli_num_rows($run_query) > 0) {
			while ($row = mysqli_fetch_array($run_query)) {
				$bid = $row["brand_id"];
				$brand_name = $row["brand_title"];

				echo "
									<li><a href='#' class='selectBrand' bid='$bid'>$brand_name</a></li>
								";
			}

			echo "</div>";
		}
	}

	if (isset($_POST["page"])) {
		$sql = "SELECT * FROM products";
		$run_query = mysqli_query($con, $sql);
		$count = mysqli_num_rows($run_query);
		$pageno = ceil($count / 9);

		for ($i = 1; $i <= $pageno; $i++) {
			echo "
								<li><a href='#' page='$i' id='page'>$i</a></li>
							";
		}
	}

	if (isset($_POST["getProduct"])) {
		$limit = 9;

		if (isset($_POST["setPage"])) {
			$pageno = $_POST["pageNumber"];
			$start = ($pageno * $limit) - $limit;
		} else {
			$start = 0;
		}

		$product_query = "SELECT * FROM products LIMIT $start,$limit";
		$run_query = mysqli_query($con, $product_query);

		if (mysqli_num_rows($run_query) > 0) {
			while ($row = mysqli_fetch_array($run_query)) {
				$pro_id = $row['product_id'];
				$pro_cat = $row['product_cat'];
				$pro_brand = $row['product_brand'];
				$pro_title = $row['product_title'];
				$pro_price = $row['product_price'];
				$pro_image = $row['product_image'];

				echo "
									<div class='col-md-4'>
										<div class='panel panel-info'>
											<div class='panel-heading'>$pro_title</div>
											
											<div class='panel-body'>
												<img src='product_images/$pro_image' style='width:220px; height:250px;'/>
											</div>

											<div class='panel-heading'>" . CURRENCY . "$pro_price.00
												<button pid='$pro_id' style='float:right;' id='product' class='btn btn-danger btn-xs'>Add To Cart</button>
											</div>
										</div>
									</div>  
								";
			}
		}
	}

	if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
		if (isset($_POST["get_seleted_Category"])) {
			$id = $_POST["cat_id"];
			$sql = "SELECT * FROM products WHERE product_cat = '$id'";
		} else if (isset($_POST["selectBrand"])) {
			$id = $_POST["brand_id"];
			$sql = "SELECT * FROM products WHERE product_brand = '$id'";
		} else {
			$keyword = $_POST["keyword"];
			// Search in both title and keywords for better results
			$sql = "SELECT * FROM products WHERE product_title LIKE '%$keyword%' OR product_keywords LIKE '%$keyword%'";
		}

		$run_query = mysqli_query($con, $sql);

		// Check if there are search results
		if (mysqli_num_rows($run_query) > 0) {
			while ($row = mysqli_fetch_array($run_query)) {
				$pro_id    = $row['product_id'];
				$pro_cat   = $row['product_cat'];
				$pro_brand = $row['product_brand'];
				$pro_title = $row['product_title'];
				$pro_price = $row['product_price'];
				$pro_image = $row['product_image'];
				echo "
									<div class='col-md-4'>
										<div class='panel panel-info'>
											<div class='panel-heading'>$pro_title</div>
											<div class='panel-body'>
												<img src='product_images/$pro_image' style='width:220px; height:250px;'/>
											</div>
											<div class='panel-heading'>" . CURRENCY . "$pro_price.00
												<button pid='$pro_id' style='float:right;' id='product' class='btn btn-danger btn-xs'>Add To Cart</button>
											</div>
										</div>
									</div>  
								";
			}
		} else {
			// No results found
			echo "<div class='alert alert-warning'>No products found matching your search.</div>";
		}
	}

	if (isset($_POST["addToCart"])) {
		$p_id = $_POST["proId"];

		if (isset($_SESSION["uid"])) {
			$user_id = $_SESSION["uid"];

			$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
			$run_query = mysqli_query($con, $sql);
			$count = mysqli_num_rows($run_query);

			if ($count > 0) {
				echo "
									<div class='alert alert-warning'>
										<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
										<strong>Product is already added into the cart, continue shopping!</strong>
									</div>
								";
			} else {
				$sql = "INSERT INTO `cart` (`p_id`, `ip_add`, `user_id`, `qty`) 
										VALUES ('$p_id', '$ip_add', '$user_id', '1')";

				if (mysqli_query($con, $sql)) {
					echo "
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
											<strong>Product is added!</strong>
										</div>
									";
				}
			}
		} else {
			$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
			$query = mysqli_query($con, $sql);

			if (mysqli_num_rows($query) > 0) {
				echo "
									<div class='alert alert-warning'>
										<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
										<strong>Product is already added into the cart continue shopping!</strong>
									</div>
								";

				exit();
			}

			$sql = "INSERT INTO `cart` (`p_id`, `ip_add`, `user_id`, `qty`) 
									VALUES ('$p_id', '$ip_add', '-1', '1')";

			if (mysqli_query($con, $sql)) {
				echo "
									<div class='alert alert-success'>
										<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
										<strong>Your product has been added to cart!</strong>
									</div>
								";

				exit();
			}
		}
	}

	// Counting User cart item
	if (isset($_POST["count_item"])) {
		// When the user is logged in then we will count number of item in cart by using user session id
		if (isset($_SESSION["uid"])) {
			$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
		} else {
			// When the user is not logged in then we will count number of item in cart by using users unique ip address
			$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
		}

		$query = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($query);
		echo $row["count_item"];

		exit();
	}

	// Getting Cart Item From Database to Dropdown menu
	if (isset($_POST["Common"])) {
		if (isset($_SESSION["uid"])) {
			// When the user is logged in this query will execute
			$sql = "SELECT a.product_id, a.product_title, a.product_price, a.product_image, b.id, b.qty 
									FROM products a, cart b 
									WHERE a.product_id = b.p_id AND b.user_id = '$_SESSION[uid]'";
		} else {
			// When the user is not logged in this query will execute
			$sql = "SELECT a.product_id, a.product_title, a.product_price, a.product_image, b.id, b.qty 
									FROM products a, cart b 
									WHERE a.product_id = b.p_id AND b.ip_add = '$ip_add' AND b.user_id < 0";
		}

		$query = mysqli_query($con, $sql);

		if (isset($_POST["getCartItem"])) {
			// Display cart item in dropdown menu
			if (mysqli_num_rows($query) > 0) {
				$n = 0;

				while ($row = mysqli_fetch_array($query)) {
					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];

					echo '
										<div class="row">
											<div class="col-md-3">' . $n . '</div>
											<div class="col-md-3"><img class="img-responsive" src="product_images/' . $product_image . '" /></div>
											<div class="col-md-3">' . $product_title . '</div>
											<div class="col-md-3">' . CURRENCY . $product_price . '</div>
										</div>
									';
				}
	?>
				<a style="float:right;" href="cart.php" class="btn btn-warning">Edit&nbsp;&nbsp;<span class="glyphicon glyphicon-edit"></span></a>
	<?php
				exit();
			}
		}

		if (isset($_POST["checkOutDetails"])) {
			if (mysqli_num_rows($query) > 0) {
				// Displaying user cart item with "Ready to checkout" button if user is not login
				echo "<form method='post' action='login_form.php'>";
				$n = 0;
				$total_price = 0;

				while ($row = mysqli_fetch_array($query)) {
					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];
					$total = $product_price * $qty;
					$total_price = $total_price + $total;

					echo
					'<div class="row">
											<div class="col-md-2">
												<div class="btn-group">
													<a href="#" remove_id="' . $product_id . '" class="btn btn-danger remove"><span class="glyphicon glyphicon-trash"></span></a>
													<a href="#" update_id="' . $product_id . '" class="btn btn-primary update"><span class="glyphicon glyphicon-ok-sign"></span></a>
												</div>
											</div>

											<input type="hidden" name="product_id[]" value="' . $product_id . '"/>
											<input type="hidden" name="" value="' . $cart_item_id . '"/>
											<div class="col-md-2"><img class="img-responsive" src="product_images/' . $product_image . '"></div>
											<div class="col-md-2">' . $product_title . '</div>
											<div class="col-md-2"><input type="text" class="form-control qty" value="' . $qty . '" ></div>
											<div class="col-md-2"><input type="text" class="form-control price" value="' . $product_price . '" readonly="readonly"></div>
											<div class="col-md-2"><input type="text" class="form-control total" value="' . $product_price * $qty . '" readonly="readonly"></div>
										</div>
									';
				}

				echo '<div class="row">
										<div class="col-md-8"></div>
										<div class="col-md-4">
											<b class="net_total" style="font-size:20px;">Total: ' . CURRENCY . ' ' . $total_price . '</b>
										</div>
									</div>';

				if (!isset($_SESSION["uid"])) {
					echo '<input type="submit" style="float:right;" name="login_user_with_product" class="btn btn-info btn-lg" value="Login to Checkout" >
												</form>';
				} else if (isset($_SESSION["uid"])) {
					echo '</form>';
					// Changed form action from process_order.php to action.php
					echo '<form action="action.php" method="post">';
					echo '<input type="hidden" name="total_price" value="' . $total_price . '">';
					echo '<input type="submit" style="float:right;" name="place_order" class="btn btn-success btn-lg" value="Place Order">';
					echo '</form>';
				}
			}
		}
	}

	// Process direct order
	if (isset($_POST["place_order"])) {
		if (!isset($_SESSION["uid"])) {
			echo "<script>window.location.href = 'login_form.php'</script>";
			exit();
		}

		$user_id = $_SESSION["uid"];
		$trx_id = "TRX" . rand(10000, 99999) . time();
		$p_status = "Completed";

		// Get cart items
		$sql = "SELECT p_id, qty FROM cart WHERE user_id = '$user_id'";
		$query = mysqli_query($con, $sql);

		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_array($query)) {
				$product_id = $row["p_id"];
				$qty = $row["qty"];

				// Insert into orders table
				$insert_order = "INSERT INTO orders (user_id, product_id, qty, trx_id, p_status) 
										VALUES ('$user_id', '$product_id', '$qty', '$trx_id', '$p_status')";
				mysqli_query($con, $insert_order);
			}

			// Clear cart after order placement
			$sql = "DELETE FROM cart WHERE user_id = '$user_id'";
			if (mysqli_query($con, $sql)) {
				// CHANGED: Direct redirect to payment_success.php with transaction ID
				echo "<script>window.location.href = 'payment_success.php?trx_id=$trx_id';</script>";
				exit();
			}
		}
	}

	// Remove Item From cart
	if (isset($_POST["removeItemFromCart"])) {
		$remove_id = $_POST["rid"];
		if (isset($_SESSION["uid"])) {
			$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
		} else {
			$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
		}

		if (mysqli_query($con, $sql)) {
			echo "<div class='alert alert-danger'>
									<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
									<b>Product is removed from cart</b>
								</div>";
			exit();
		}
	}

	// Update Item From cart
	if (isset($_POST["updateCartItem"])) {
		$update_id = $_POST["update_id"];
		$qty = $_POST["qty"];

		if (isset($_SESSION["uid"])) {
			$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
		} else {
			$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
		}

		if (mysqli_query($con, $sql)) {
			echo "<div class='alert alert-info'>
									<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
									<b>Product is updated</b>
								</div>";
			exit();
		}
	}
?>