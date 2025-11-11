<?php
/**
 * Payment Confirmation Page
 * Displays order confirmation after successful checkout
 */

session_start();

// Check if order was placed
if (!isset($_SESSION['order_id'])) {
    header('Location: shop.php');
    exit();
}

// Get order details from session
$order_id = $_SESSION['order_id'];
$order_total = isset($_SESSION['order_cost']) ? $_SESSION['order_cost'] : 0;
$order_status = isset($_SESSION['order_status']) ? $_SESSION['order_status'] : 'on_hold';

// Calculate cart count for navigation (from current cart, if any)
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['product_quantity']) ? $item['product_quantity'] : 0;
    }
}

// Store order info temporarily before clearing cart
$temp_order_id = $order_id;
$temp_order_total = $order_total;
$temp_order_status = $order_status;

// Clear cart after displaying confirmation
unset($_SESSION['cart']);
unset($_SESSION['cart_subtotal']);
unset($_SESSION['cart_tax']);
unset($_SESSION['cart_total']);

// Clear order session data
unset($_SESSION['order_id']);
unset($_SESSION['order_cost']);
unset($_SESSION['order_status']);

// Reset cart count after clearing
$cart_count = 0;
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
		<title>Order Confirmation - Heang's E-Shop</title>
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
		<style>
			.confirmation-section {
				margin-top: 100px;
				min-height: 60vh;
			}
			.confirmation-card {
				max-width: 600px;
				margin: 0 auto;
				box-shadow: 0 4px 15px rgba(0,0,0,0.1);
			}
			.success-icon {
				font-size: 80px;
				color: #28a745;
			}
			.order-details {
				background-color: #f8f9fa;
				padding: 1.5rem;
				border-radius: 8px;
				margin: 1rem 0;
			}
			.order-details-item {
				display: flex;
				justify-content: space-between;
				padding: 0.5rem 0;
				border-bottom: 1px solid #dee2e6;
			}
			.order-details-item:last-child {
				border-bottom: none;
			}
			.order-total {
				font-size: 1.5rem;
				color: #28a745;
				font-weight: bold;
			}
			.status-badge {
				display: inline-block;
				padding: 0.5rem 1rem;
				border-radius: 20px;
				font-size: 0.875rem;
				font-weight: 600;
			}
			.status-on-hold {
				background-color: #fff3cd;
				color: #856404;
			}
			.action-buttons {
				margin-top: 2rem;
			}
		</style>
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

		<!-- PAYMENT CONFIRMATION -->
		<section class="confirmation-section my-5 py-5">
			<div class="container">
				<div class="confirmation-card card">
					<div class="card-body text-center p-5">
						<!-- Success Icon -->
						<div class="mb-4">
							<i class="ri-checkbox-circle-line success-icon"></i>
						</div>

						<!-- Thank You Message -->
						<h2 class="mb-3">Thank You for Your Order!</h2>
						<p class="text-muted mb-4">
							Your order has been successfully placed. We'll send you a confirmation email shortly.
						</p>

						<!-- Order Details -->
						<div class="order-details">
							<div class="order-details-item">
								<span class="fw-semibold">Order ID:</span>
								<span>#<?php echo htmlspecialchars($temp_order_id); ?></span>
							</div>
							<div class="order-details-item">
								<span class="fw-semibold">Order Total:</span>
								<span class="order-total">$<?php echo number_format($temp_order_total, 2); ?></span>
							</div>
							<div class="order-details-item">
								<span class="fw-semibold">Order Status:</span>
								<span class="status-badge status-<?php echo strtolower(str_replace('_', '-', $temp_order_status)); ?>">
									<?php echo ucfirst(str_replace('_', ' ', $temp_order_status)); ?>
								</span>
							</div>
						</div>

						<!-- Additional Information -->
						<div class="alert alert-info mt-4" role="alert">
							<i class="ri-information-line me-2"></i>
							<strong>Next Steps:</strong> You will receive an email confirmation with your order details.
							You can track your order status in your account dashboard.
						</div>

						<!-- Action Buttons -->
						<div class="action-buttons d-grid gap-3 d-md-flex justify-content-md-center">
							<a href="shop.php" class="btn btn-primary btn-lg">
								<i class="ri-shopping-bag-line me-2"></i>
								Continue Shopping
							</a>
							<a href="account.php" class="btn btn-outline-primary btn-lg">
								<i class="ri-file-list-3-line me-2"></i>
								View My Orders
							</a>
						</div>
					</div>
				</div>

				<!-- Additional Help -->
				<div class="text-center mt-4">
					<p class="text-muted">
						Need help with your order?
						<a href="contact.php" class="text-decoration-none">Contact us</a>
					</p>
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
									href="shop.php?category=shoes">
									Shoes
								</a>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=bags">
									Bags
								</a>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=hats">
									Hats
								</a>
							</li>
							<li class="mb-2">
								<a
									class="text-decoration-none text-white-50"
									href="shop.php?category=watches">
									Watches
								</a>
							</li>
							<li>
								<a
									class="text-decoration-none text-white-50"
									href="shop.php">
									All Products
								</a>
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
