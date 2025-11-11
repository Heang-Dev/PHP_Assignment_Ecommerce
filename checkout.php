<?php
/**
 * Checkout Page
 */

session_start();

// Include database connection
include('server/connection.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login with checkout as redirect parameter
    header('Location: login.php?redirect=checkout.php');
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    // Redirect to cart if empty
    header('Location: cart.php');
    exit();
}

// Calculate totals if not already in session
if (!isset($_SESSION['cart_total'])) {
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $product) {
        $subtotal += $product['product_price'] * $product['product_quantity'];
    }
    $tax_rate = 0.05; // 5% tax
    $tax = $subtotal * $tax_rate;
    $_SESSION['cart_subtotal'] = $subtotal;
    $_SESSION['cart_tax'] = $tax;
    $_SESSION['cart_total'] = $subtotal + $tax;
}

// Get totals from session
$subtotal = $_SESSION['cart_subtotal'];
$tax = $_SESSION['cart_tax'];
$total = $_SESSION['cart_total'];

// Count cart items
$cart_count = 0;
foreach ($_SESSION['cart'] as $product) {
    $cart_count += $product['product_quantity'];
}

// Get user information for pre-filling form
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
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
		<title>Checkout - Heang's E-Shop</title>
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
			href="Assets/CSS/checkout.css" />
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
									<span class="visually-hidden">items in cart</span>
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

		<section class="my-5 py-5">
			<div class="container text-center mt-3 pt-5">
				<h2 class="form-weight-bold">Checkout</h2>
				<hr class="mx-auto" />
			</div>

			<div class="mx-auto container">
				<div class="row">
					<!-- Order Summary -->
					<div class="col-lg-6 col-md-12 mb-4">
						<h4 class="mb-3">Order Summary</h4>
						<div class="card">
							<div class="card-body">
								<table class="table table-borderless">
									<thead>
										<tr>
											<th>Product</th>
											<th class="text-center">Qty</th>
											<th class="text-end">Price</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($_SESSION['cart'] as $product): ?>
										<tr>
											<td>
												<div class="d-flex align-items-center">
													<img
														src="<?php echo htmlspecialchars($product['product_image']); ?>"
														alt="<?php echo htmlspecialchars($product['product_name']); ?>"
														class="rounded me-2"
														style="width: 50px; height: 50px; object-fit: cover;" />
													<div>
														<p class="mb-0"><?php echo htmlspecialchars($product['product_name']); ?></p>
														<small class="text-muted">$<?php echo number_format($product['product_price'], 2); ?></small>
													</div>
												</div>
											</td>
											<td class="text-center align-middle">
												<?php echo $product['product_quantity']; ?>
											</td>
											<td class="text-end align-middle">
												$<?php echo number_format($product['product_price'] * $product['product_quantity'], 2); ?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" class="text-end"><strong>Subtotal:</strong></td>
											<td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
										</tr>
										<tr>
											<td colspan="2" class="text-end"><strong>Tax (5%):</strong></td>
											<td class="text-end">$<?php echo number_format($tax, 2); ?></td>
										</tr>
										<tr class="border-top">
											<td colspan="2" class="text-end"><strong>Total:</strong></td>
											<td class="text-end"><strong class="text-primary">$<?php echo number_format($total, 2); ?></strong></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

					<!-- Checkout Form -->
					<div class="col-lg-6 col-md-12">
						<h4 class="mb-3">Shipping Information</h4>
						<form id="checkout-form" method="POST" action="server/place_order.php">
							<div class="form-group">
								<label>Full Name</label>
								<input
									type="text"
									class="form-control"
									id="checkout-name"
									name="name"
									placeholder="Enter your full name"
									value="<?php echo htmlspecialchars($user_name); ?>"
									required />
							</div>
							<div class="form-group">
								<label>Email</label>
								<input
									type="email"
									class="form-control"
									id="checkout-email"
									name="email"
									placeholder="Enter your email"
									value="<?php echo htmlspecialchars($user_email); ?>"
									required />
							</div>
							<div class="form-group">
								<label>Phone</label>
								<input
									type="tel"
									class="form-control"
									id="checkout-phone"
									name="phone"
									placeholder="Enter your phone number"
									required />
							</div>
							<div class="form-group">
								<label>City</label>
								<input
									type="text"
									class="form-control"
									id="checkout-city"
									name="city"
									placeholder="Enter your city"
									required />
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea
									class="form-control"
									id="checkout-address"
									name="address"
									placeholder="Enter your full address"
									rows="3"
									required></textarea>
							</div>

							<div class="order-total-display mb-4">
								<div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
									<h5 class="mb-0">Order Total:</h5>
									<h5 class="mb-0 text-primary">$<?php echo number_format($total, 2); ?></h5>
								</div>
							</div>

							<div class="form-group">
								<input
									type="submit"
									class="btn w-100"
									id="checkout-btn"
									name="place_order"
									value="Place Order" />
							</div>

							<div class="text-center mt-3">
								<a
									href="cart.php"
									class="text-decoration-none"
									><i class="ri-arrow-left-line"></i> Return to Cart</a
								>
							</div>
						</form>
					</div>
				</div>
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
									href="shop.php?category=shoes"
									>Shoes</a
								>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=bags"
									>Bags</a
								>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=hats"
									>Hats</a
								>
							</li>
							<li>
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=watches"
									>Watches</a
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
	</body>
</html>
