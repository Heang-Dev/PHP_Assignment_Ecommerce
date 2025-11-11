<?php
/**
 * User Account Page
 * View profile, order history, change password, logout
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?error=Please login to view your account');
    exit();
}

// Include database connection
include('server/connection.php');

// Initialize variables
$error_message = '';
$success_message = '';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php?success=Logged out successfully');
    exit();
}

// Handle change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All password fields are required.";
    }
    elseif (strlen($new_password) < 6) {
        $error_message = "New password must be at least 6 characters long.";
    }
    elseif ($new_password !== $confirm_password) {
        $error_message = "New passwords do not match.";
    }
    else {
        try {
            // Verify current password
            $stmt = $conn->prepare("SELECT user_password FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $_SESSION['user_id']]);
            $user = $stmt->fetch();

            if ($user && password_verify($current_password, $user['user_password'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET user_password = :password WHERE user_id = :user_id");
                $update_stmt->execute([
                    ':password' => $hashed_password,
                    ':user_id' => $_SESSION['user_id']
                ]);

                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Current password is incorrect.";
            }

        } catch (PDOException $e) {
            $error_message = "Failed to change password. Please try again.";
        }
    }
}

// Fetch user's orders
try {
    $stmt = $conn->prepare("
        SELECT o.*, COUNT(oi.item_id) as item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.user_id = :user_id
        GROUP BY o.order_id
        ORDER BY o.order_date DESC
    ");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $orders = $stmt->fetchAll();

} catch (PDOException $e) {
    $orders = [];
}

// Handle error/success messages from URL parameters
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
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
		<title>My Account - Heang's E-Shop</title>
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
			href="Assets/CSS/login.css" />
		<link
			rel="stylesheet"
			href="Assets/CSS/account.css" />
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
							<?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
								<span
									class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger px-1 py-0">
									<?php echo count($_SESSION['cart']); ?>
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

		<section class="account-section my-5 py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-4 mb-4">
						<div class="card">
							<div class="card-body text-center">
								<div class="mb-3">
									<i class="ri-user-3-fill fs-1 text-primary"></i>
								</div>
								<h5 class="card-title"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
								<p class="card-text small text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
								<hr>
								<div class="d-grid gap-2">
									<a href="#orders" class="btn btn-sm btn-outline-primary">My Orders</a>
									<a href="#change-password" class="btn btn-sm btn-outline-secondary">Change Password</a>
									<a href="account.php?logout=1" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-9 col-md-8">
						<?php if (!empty($error_message)): ?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<i class="ri-error-warning-line me-2"></i>
								<?php echo $error_message; ?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						<?php if (!empty($success_message)): ?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<i class="ri-checkbox-circle-line me-2"></i>
								<?php echo $success_message; ?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						<?php endif; ?>

						<!-- Orders Section -->
						<div id="orders" class="mb-5">
							<h3 class="mb-4">
								<i class="ri-shopping-bag-line me-2"></i>
								My Orders
							</h3>

							<?php if (empty($orders)): ?>
								<div class="alert alert-info">
									<i class="ri-information-line me-2"></i>
									You haven't placed any orders yet. <a href="shop.php" class="alert-link">Start shopping now!</a>
								</div>
							<?php else: ?>
								<div class="row g-3">
									<?php foreach ($orders as $order): ?>
										<div class="col-12">
											<div class="card order-card">
												<div class="card-body">
													<div class="row align-items-center">
														<div class="col-md-7">
															<h6 class="mb-2">
																Order #<?php echo $order['order_id']; ?>
																<span class="badge bg-<?php echo $order['order_status'] === 'on_hold' ? 'warning' : ($order['order_status'] === 'paid' ? 'success' : 'secondary'); ?> status-badge">
																	<?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
																</span>
															</h6>
															<p class="mb-1 small text-muted">
																<i class="ri-calendar-line me-1"></i>
																<?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?>
															</p>
															<p class="mb-1 small">
																<i class="ri-map-pin-line me-1"></i>
																<?php echo htmlspecialchars($order['user_city'] . ', ' . $order['user_address']); ?>
															</p>
															<p class="mb-0 small">
																<i class="ri-phone-line me-1"></i>
																<?php echo htmlspecialchars($order['user_phone']); ?>
															</p>
														</div>
														<div class="col-md-5 text-md-end mt-3 mt-md-0">
															<h5 class="mb-2 text-primary">$<?php echo number_format($order['order_cost'], 2); ?></h5>
															<p class="mb-2 small text-muted"><?php echo $order['item_count']; ?> item(s)</p>
															<a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-primary">
																<i class="ri-eye-line me-1"></i>
																View Details
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<!-- Change Password Section -->
						<div id="change-password">
							<h3 class="mb-4">
								<i class="ri-lock-password-line me-2"></i>
								Change Password
							</h3>
							<div class="card">
								<div class="card-body">
									<form method="POST" action="account.php#change-password">
										<div class="mb-3">
											<label for="current_password" class="form-label">Current Password</label>
											<input
												type="password"
												class="form-control"
												id="current_password"
												name="current_password"
												required />
										</div>
										<div class="mb-3">
											<label for="new_password" class="form-label">New Password</label>
											<input
												type="password"
												class="form-control"
												id="new_password"
												name="new_password"
												required />
											<small class="form-text text-muted">Minimum 6 characters</small>
										</div>
										<div class="mb-3">
											<label for="confirm_password" class="form-label">Confirm New Password</label>
											<input
												type="password"
												class="form-control"
												id="confirm_password"
												name="confirm_password"
												required />
										</div>
										<button type="submit" name="change_password" class="btn btn-primary">
											<i class="ri-save-line me-2"></i>
											Update Password
										</button>
									</form>
								</div>
							</div>
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
