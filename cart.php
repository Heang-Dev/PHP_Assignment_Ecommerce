<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Check if product already exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity
        $_SESSION['cart'][$product_id]['product_quantity'] += $product_quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity
        );
    }

    header('Location: cart.php');
    exit();
}

// Handle Remove from Cart
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    header('Location: cart.php');
    exit();
}

// Handle Update Cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['product_quantity'] as $product_id => $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id]['product_quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    header('Location: cart.php');
    exit();
}

// Calculate totals
$subtotal = 0;
$cart_count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $subtotal += $product['product_price'] * $product['product_quantity'];
        $cart_count += $product['product_quantity'];
    }
}

$tax_rate = 0.05; // 5% tax
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;

// Store totals in session
$_SESSION['cart_subtotal'] = $subtotal;
$_SESSION['cart_tax'] = $tax;
$_SESSION['cart_total'] = $total;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta
			name="viewport"
			content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
		<meta
			http-equiv="X-UA-Compatible"
			content="ie=edge" />
		<title>Heang's E-Shop - Cart</title>
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
			crossorigin="anonymous" />
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css"
			integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer" />
		<link
			rel="stylesheet"
			href="Assets/CSS/style.css" />
		<link
			rel="stylesheet"
			href="Assets/CSS/cart.css" />
	</head>
	<body>
		<!--NAVIGATION BAR-->
		<nav
			class="navbar navbar-expand-lg bg-white navbar-light fixed-top shadow-sm">
			<div class="container">
				<a
					class="navbar-brand d-flex align-items-center"
					href="index.php"
					aria-label="eShop home">
					<img
						src="Assets/Images/logo.jpeg"
						alt="eShop logo"
						class="rounded-circle me-2"
						width="40px" />
					<span class="fw-semibold">eShop</span>
				</a>
				<button
					class="navbar-toggler"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#navbarSupportedContent"
					aria-controls="navbarSupportedContent"
					aria-expanded="false"
					aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div
					class="collapse navbar-collapse"
					id="navbarSupportedContent">
					<ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2">
						<li class="nav-item">
							<a
								class="nav-link px-3"
								href="index.php"
								>Home</a
							>
						</li>
						<li class="nav-item">
							<a
								class="nav-link px-3"
								href="shop.php"
								>Shop</a
							>
						</li>
						<li class="nav-item">
							<a
								class="nav-link px-3"
								href="blog.php"
								>Blog</a
							>
						</li>
						<li class="nav-item">
							<a
								class="nav-link px-3"
								href="contact.php"
								>Contact Us</a
							>
						</li>
					</ul>
					<div
						class="d-flex align-items-center justify-content-center justify-content-lg-end gap-3 ms-lg-3">
						<a
							href="cart.php"
							class="text-dark position-relative"
							aria-label="View cart">
							<i class="ri-shopping-basket-2-line fs-5"></i>
							<?php if ($cart_count > 0): ?>
							<span
								class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-1 py-0">
								<?php echo $cart_count; ?>
								<span class="visually-hidden"
									>items in cart</span
								>
							</span>
							<?php endif; ?>
						</a>
						<a
							href="account.php"
							class="text-dark"
							aria-label="Account">
							<i class="ri-user-3-line fs-5"></i>
						</a>
					</div>
				</div>
			</div>
		</nav>

		<!-- CART -->
		<section
			id="cart"
			class="cart container my-5 py-5">
			<div class="container mt-5">
				<h2 class="font-weight-bold">Your Cart</h2>
				<hr />

				<?php if (empty($_SESSION['cart'])): ?>
					<!-- Empty Cart Message -->
					<div class="text-center py-5">
						<i class="ri-shopping-cart-line" style="font-size: 80px; color: #ccc;"></i>
						<h4 class="mt-3">Your cart is empty</h4>
						<p class="text-muted">Add some products to get started!</p>
						<a href="shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
					</div>
				<?php else: ?>
					<!-- Cart Table -->
					<form method="POST" action="cart.php" id="cartForm">
						<table class="mt-5 pt-5">
							<tr>
								<th>Product</th>
								<th>Quantity</th>
								<th>Subtotal</th>
							</tr>
							<?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
							<tr>
								<td>
									<div class="product-info">
										<img
											src="<?php echo htmlspecialchars($product['product_image']); ?>"
											alt="<?php echo htmlspecialchars($product['product_name']); ?>" />
										<div>
											<p><?php echo htmlspecialchars($product['product_name']); ?></p>
											<small>Price: $<?php echo number_format($product['product_price'], 2); ?></small>
											<br />
											<form method="POST" action="cart.php" style="display: inline;">
												<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
												<button type="submit" name="remove_product" class="remove-btn" style="border: none; background: none; cursor: pointer;">Remove</button>
											</form>
										</div>
									</div>
								</td>

								<td>
									<input
										type="number"
										name="product_quantity[<?php echo $product_id; ?>]"
										value="<?php echo $product['product_quantity']; ?>"
										min="1"
										class="quantity-input"
										data-price="<?php echo $product['product_price']; ?>"
										data-product-id="<?php echo $product_id; ?>" />
									<button type="submit" name="update_cart" class="edit-btn" style="border: none; background: none; cursor: pointer;">Update</button>
								</td>

								<td>
									<span> $ </span>
									<span class="product-price" id="subtotal-<?php echo $product_id; ?>">
										<?php echo number_format($product['product_price'] * $product['product_quantity'], 2); ?>
									</span>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</form>

					<!-- Cart Total -->
					<div class="cart-total">
						<table>
							<tr>
								<td>Subtotal</td>
								<td id="cart-subtotal">$<?php echo number_format($subtotal, 2); ?></td>
							</tr>
							<tr>
								<td>Tax (5%)</td>
								<td id="cart-tax">$<?php echo number_format($tax, 2); ?></td>
							</tr>
							<tr>
								<td><strong>Total</strong></td>
								<td id="cart-total"><strong>$<?php echo number_format($total, 2); ?></strong></td>
							</tr>
						</table>
						<div class="checkout-container mt-3">
							<a href="checkout.php" class="checkout-btn btn">CHECKOUT</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<!--FOOTER-->
		<footer class="bg-dark text-white pt-5 mt-5">
			<div class="container">
				<div class="row gy-4 pt-3 align-items-start">
					<div class="col-lg-4 col-md-6 col-sm-12">
						<img
							src="Assets/Images/logo.jpeg"
							alt="eShop logo"
							class="rounded-circle mb-3"
							width="50px" />
						<p class="mb-0 small text-white-50">
							Our featured products are handpicked by our team of
							experts. Discover a wide range of quality items and
							seasonal offers tailored for you.
						</p>
					</div>
					<div class="col-lg-2 col-md-6 col-sm-12">
						<h5 class="pb-2 mb-2">Shop</h5>
						<ul class="list-unstyled text-uppercase small mb-0">
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="#"
									>Men</a
								>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="#"
									>Women</a
								>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="#"
									>Boys</a
								>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="#"
									>Girls</a
								>
							</li>
							<li>
								<a
									class="text-decoration-none text-white-50"
									href="#"
									>Shoes</a
								>
							</li>
						</ul>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<h5 class="pb-2 mb-2">Contact</h5>
						<div class="small">
							<div class="mb-2">
								<h6 class="text-uppercase mb-1">Address</h6>
								<p class="mb-0 text-white-50">
									123 Street, City, Country
								</p>
							</div>
							<div class="mb-2">
								<h6 class="text-uppercase mb-1">Phone</h6>
								<p class="mb-0 text-white-50">+123 456 7890</p>
							</div>
							<div>
								<h6 class="text-uppercase mb-1">Email</h6>
								<p class="mb-0 text-white-50">
									info@example.com
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 col-sm-12">
						<h5 class="pb-2 mb-2">From Instagram</h5>
						<div class="row g-2">
							<div class="col-3">
								<img
									src="Assets/Images/featured_1.png"
									alt="gallery 1"
									class="img-fluid rounded" />
							</div>
							<div class="col-3">
								<img
									src="Assets/Images/featured_2.png"
									alt="gallery 2"
									class="img-fluid rounded" />
							</div>
							<div class="col-3">
								<img
									src="Assets/Images/featured_3.png"
									alt="gallery 3"
									class="img-fluid rounded" />
							</div>
							<div class="col-3">
								<img
									src="Assets/Images/featured_4.png"
									alt="gallery 4"
									class="img-fluid rounded" />
							</div>
						</div>
					</div>
				</div>

				<hr class="border-secondary opacity-25 my-4" />

				<div class="row align-items-center text-center text-md-start">
					<div class="col-md-6 mb-3 mb-md-0">
						<p class="mb-0 small">
							eShop &copy; 2024, All Rights Reserved • Designed by
							Meng Heang
						</p>
					</div>
					<div class="col-md-6 text-md-end">
						<a
							class="text-white-50 mx-2"
							href="#"
							aria-label="Facebook"
							><i class="ri-facebook-fill fs-5"></i
						></a>
						<a
							class="text-white-50 mx-2"
							href="#"
							aria-label="Twitter"
							><i class="ri-twitter-fill fs-5"></i
						></a>
						<a
							class="text-white-50 mx-2"
							href="#"
							aria-label="Instagram"
							><i class="ri-instagram-fill fs-5"></i
						></a>
						<a
							class="text-white-50 mx-2"
							href="#"
							aria-label="LinkedIn"
							><i class="ri-linkedin-fill fs-5"></i
						></a>
					</div>
				</div>
			</div>
		</footer>

		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
			crossorigin="anonymous"></script>

		<!-- JavaScript for dynamic quantity updates -->
		<script>
			// Real-time quantity update calculations
			document.addEventListener('DOMContentLoaded', function() {
				const quantityInputs = document.querySelectorAll('.quantity-input');

				quantityInputs.forEach(input => {
					input.addEventListener('input', function() {
						updateSubtotal(this);
						updateCartTotals();
					});
				});

				function updateSubtotal(input) {
					const price = parseFloat(input.getAttribute('data-price'));
					const quantity = parseInt(input.value) || 0;
					const productId = input.getAttribute('data-product-id');
					const subtotal = price * quantity;

					const subtotalElement = document.getElementById('subtotal-' + productId);
					if (subtotalElement) {
						subtotalElement.textContent = subtotal.toFixed(2);
					}
				}

				function updateCartTotals() {
					let subtotal = 0;

					// Calculate subtotal from all products
					const quantityInputs = document.querySelectorAll('.quantity-input');
					quantityInputs.forEach(input => {
						const price = parseFloat(input.getAttribute('data-price'));
						const quantity = parseInt(input.value) || 0;
						subtotal += price * quantity;
					});

					const taxRate = 0.05; // 5% tax
					const tax = subtotal * taxRate;
					const total = subtotal + tax;

					// Update display
					const subtotalElement = document.getElementById('cart-subtotal');
					const taxElement = document.getElementById('cart-tax');
					const totalElement = document.getElementById('cart-total');

					if (subtotalElement) subtotalElement.textContent = '$' + subtotal.toFixed(2);
					if (taxElement) taxElement.textContent = '$' + tax.toFixed(2);
					if (totalElement) totalElement.innerHTML = '<strong>$' + total.toFixed(2) + '</strong>';
				}
			});
		</script>
	</body>
</html>
