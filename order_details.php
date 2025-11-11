<?php
/**
 * Order Details Page
 * Shows detailed information about a specific order
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?error=Please login to view order details');
    exit();
}

// Include database connection
include('server/connection.php');

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id <= 0) {
    header('Location: account.php?error=Invalid order ID');
    exit();
}

try {
    // Fetch order details
    $stmt = $conn->prepare("
        SELECT * FROM orders
        WHERE order_id = :order_id AND user_id = :user_id
    ");
    $stmt->execute([
        ':order_id' => $order_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    $order = $stmt->fetch();

    // Check if order exists and belongs to user
    if (!$order) {
        header('Location: account.php?error=Order not found');
        exit();
    }

    // Fetch order items
    $stmt = $conn->prepare("
        SELECT * FROM order_items
        WHERE order_id = :order_id
    ");
    $stmt->execute([':order_id' => $order_id]);
    $order_items = $stmt->fetchAll();

} catch (PDOException $e) {
    header('Location: account.php?error=Error loading order details');
    exit();
}

// Calculate cart count for navigation
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['product_quantity']) ? $item['product_quantity'] : 1;
    }
}
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
		<title>Order Details - Heang's E-Shop</title>
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
			.order-details-section {
				margin-top: 100px;
				min-height: 70vh;
			}
			.order-header {
				background: linear-gradient(135deg, #FB774B 0%, #ff9068 100%);
				color: white;
				padding: 2rem;
				border-radius: 10px;
				margin-bottom: 2rem;
			}
			.order-info-card {
				border: none;
				box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				margin-bottom: 1.5rem;
			}
			.order-items-table {
				background: white;
				border-radius: 10px;
				overflow: hidden;
			}
			.product-img-small {
				width: 60px;
				height: 60px;
				object-fit: cover;
				border-radius: 5px;
			}
			.status-badge {
				font-size: 0.875rem;
				padding: 0.5rem 1rem;
			}
			.btn-back {
				background: #f8f9fa;
				border: 1px solid #dee2e6;
			}
			.btn-back:hover {
				background: #e9ecef;
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

		<section class="order-details-section my-5 py-5">
			<div class="container">
				<!-- Back Button -->
				<div class="mb-3">
					<a href="account.php" class="btn btn-back">
						<i class="ri-arrow-left-line me-2"></i>
						Back to My Orders
					</a>
				</div>

				<!-- Order Header -->
				<div class="order-header">
					<div class="row align-items-center">
						<div class="col-md-8">
							<h2 class="mb-2">
								<i class="ri-file-list-3-line me-2"></i>
								Order #<?php echo $order['order_id']; ?>
							</h2>
							<p class="mb-0 opacity-75">
								<i class="ri-calendar-line me-2"></i>
								Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['order_date'])); ?>
							</p>
						</div>
						<div class="col-md-4 text-md-end mt-3 mt-md-0">
							<span class="badge bg-<?php echo $order['order_status'] === 'on_hold' ? 'warning text-dark' : ($order['order_status'] === 'paid' ? 'success' : 'secondary'); ?> status-badge">
								<?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
							</span>
						</div>
					</div>
				</div>

				<div class="row">
					<!-- Order Information -->
					<div class="col-lg-4 mb-4">
						<!-- Order Summary Card -->
						<div class="card order-info-card">
							<div class="card-header bg-light">
								<h5 class="mb-0">
									<i class="ri-money-dollar-circle-line me-2"></i>
									Order Summary
								</h5>
							</div>
							<div class="card-body">
								<div class="d-flex justify-content-between mb-2">
									<span>Total Items:</span>
									<strong><?php echo count($order_items); ?></strong>
								</div>
								<hr>
								<div class="d-flex justify-content-between">
									<h5 class="mb-0">Total Amount:</h5>
									<h5 class="mb-0 text-primary">$<?php echo number_format($order['order_cost'], 2); ?></h5>
								</div>
							</div>
						</div>

						<!-- Shipping Information Card -->
						<div class="card order-info-card">
							<div class="card-header bg-light">
								<h5 class="mb-0">
									<i class="ri-truck-line me-2"></i>
									Shipping Information
								</h5>
							</div>
							<div class="card-body">
								<p class="mb-2">
									<strong><i class="ri-map-pin-line me-2 text-primary"></i>Address:</strong><br>
									<?php echo htmlspecialchars($order['user_address']); ?>
								</p>
								<p class="mb-2">
									<strong><i class="ri-building-line me-2 text-primary"></i>City:</strong><br>
									<?php echo htmlspecialchars($order['user_city']); ?>
								</p>
								<p class="mb-0">
									<strong><i class="ri-phone-line me-2 text-primary"></i>Phone:</strong><br>
									<?php echo htmlspecialchars($order['user_phone']); ?>
								</p>
							</div>
						</div>
					</div>

					<!-- Order Items -->
					<div class="col-lg-8">
						<div class="card order-info-card">
							<div class="card-header bg-light">
								<h5 class="mb-0">
									<i class="ri-shopping-bag-line me-2"></i>
									Order Items
								</h5>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-hover mb-0">
										<thead class="bg-light">
											<tr>
												<th>Product</th>
												<th class="text-center">Quantity</th>
												<th class="text-end">Price</th>
												<th class="text-end">Subtotal</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($order_items as $item): ?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<img
															src="<?php echo htmlspecialchars($item['product_image']); ?>"
															alt="<?php echo htmlspecialchars($item['product_name']); ?>"
															class="product-img-small me-3"
															onerror="this.src='Assets/Images/placeholder.png';" />
														<div>
															<h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
															<small class="text-muted">
																<a href="single_product.php?product_id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
																	View Product
																</a>
															</small>
														</div>
													</div>
												</td>
												<td class="text-center align-middle">
													<span class="badge bg-secondary">
														<?php echo $item['product_quantity']; ?>
													</span>
												</td>
												<td class="text-end align-middle">
													$<?php echo number_format($item['product_price'], 2); ?>
												</td>
												<td class="text-end align-middle">
													<strong>$<?php echo number_format($item['product_price'] * $item['product_quantity'], 2); ?></strong>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
										<tfoot class="bg-light">
											<tr>
												<td colspan="3" class="text-end"><strong>Total:</strong></td>
												<td class="text-end">
													<h5 class="mb-0 text-primary">
														$<?php echo number_format($order['order_cost'], 2); ?>
													</h5>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>

						<!-- Actions -->
						<div class="mt-4 d-flex gap-2">
							<a href="shop.php" class="btn btn-primary">
								<i class="ri-shopping-bag-line me-2"></i>
								Continue Shopping
							</a>
							<button class="btn btn-outline-secondary" onclick="window.print()">
								<i class="ri-printer-line me-2"></i>
								Print Order
							</button>
						</div>
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
