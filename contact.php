<?php
/**
 * Contact Us Page
 * Contact form and company information
 */

session_start();

// Calculate cart count for navigation
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['product_quantity']) ? $item['product_quantity'] : 0;
    }
}

// Handle form submission (for display purposes only)
$form_submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $form_submitted = true;
    // In a real application, you would process the form here
    // Send email, save to database, etc.
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
		<title>Contact Us - Heang's E-Shop</title>
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
			.contact-section {
				margin-top: 100px;
			}
			.contact-info-card {
				height: 100%;
				transition: transform 0.3s;
			}
			.contact-info-card:hover {
				transform: translateY(-5px);
			}
			.contact-icon {
				font-size: 2.5rem;
				color: #0d6efd;
				margin-bottom: 1rem;
			}
			.map-placeholder {
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				height: 400px;
				display: flex;
				align-items: center;
				justify-content: center;
				color: white;
				font-size: 1.5rem;
				border-radius: 8px;
			}
			.form-control:focus {
				border-color: #0d6efd;
				box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
			}
			.contact-form {
				background-color: #f8f9fa;
				padding: 2rem;
				border-radius: 8px;
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
								class="nav-link px-3 active"
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

		<!-- CONTACT SECTION -->
		<section class="contact-section my-5 py-5">
			<div class="container">
				<!-- Page Header -->
				<div class="text-center mb-5">
					<h1 class="display-5 fw-bold mb-3">Get In Touch</h1>
					<p class="lead text-muted">
						Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
					</p>
				</div>

				<!-- Contact Information Cards -->
				<div class="row g-4 mb-5">
					<div class="col-md-4">
						<div class="card contact-info-card text-center shadow-sm">
							<div class="card-body p-4">
								<i class="ri-map-pin-line contact-icon"></i>
								<h5 class="card-title mb-3">Visit Us</h5>
								<p class="card-text text-muted">
									123 Street Name<br>
									City, Country<br>
									Postal Code 12345
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card contact-info-card text-center shadow-sm">
							<div class="card-body p-4">
								<i class="ri-phone-line contact-icon"></i>
								<h5 class="card-title mb-3">Call Us</h5>
								<p class="card-text text-muted">
									Main: +123 456 7890<br>
									Support: +123 456 7891<br>
									Mon-Fri, 9AM-6PM
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card contact-info-card text-center shadow-sm">
							<div class="card-body p-4">
								<i class="ri-mail-line contact-icon"></i>
								<h5 class="card-title mb-3">Email Us</h5>
								<p class="card-text text-muted">
									General: info@example.com<br>
									Support: support@example.com<br>
									We reply within 24 hours
								</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Contact Form and Map -->
				<div class="row g-4">
					<!-- Contact Form -->
					<div class="col-lg-6">
						<div class="contact-form">
							<h3 class="mb-4">Send Us a Message</h3>

							<?php if ($form_submitted): ?>
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<i class="ri-checkbox-circle-line me-2"></i>
									<strong>Thank you for your message!</strong> We'll get back to you soon.
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							<?php endif; ?>

							<form method="POST" action="contact.php">
								<div class="row g-3">
									<div class="col-md-6">
										<label for="name" class="form-label">Your Name</label>
										<input
											type="text"
											class="form-control"
											id="name"
											name="name"
											placeholder="John Doe"
											required />
									</div>
									<div class="col-md-6">
										<label for="email" class="form-label">Email Address</label>
										<input
											type="email"
											class="form-control"
											id="email"
											name="email"
											placeholder="john@example.com"
											required />
									</div>
									<div class="col-12">
										<label for="subject" class="form-label">Subject</label>
										<input
											type="text"
											class="form-control"
											id="subject"
											name="subject"
											placeholder="How can we help you?"
											required />
									</div>
									<div class="col-12">
										<label for="message" class="form-label">Message</label>
										<textarea
											class="form-control"
											id="message"
											name="message"
											rows="6"
											placeholder="Write your message here..."
											required></textarea>
									</div>
									<div class="col-12">
										<button type="submit" name="send_message" class="btn btn-primary btn-lg w-100">
											<i class="ri-send-plane-fill me-2"></i>
											Send Message
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<!-- Map Placeholder -->
					<div class="col-lg-6">
						<div class="map-placeholder shadow-sm">
							<div class="text-center">
								<i class="ri-map-2-line" style="font-size: 4rem; margin-bottom: 1rem;"></i>
								<h4>Find Us on the Map</h4>
								<p class="mb-0">123 Street Name, City, Country</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Business Hours -->
				<div class="row mt-5">
					<div class="col-12">
						<div class="card shadow-sm">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col-md-3 text-center mb-3 mb-md-0">
										<i class="ri-time-line" style="font-size: 3rem; color: #0d6efd;"></i>
									</div>
									<div class="col-md-9">
										<h4 class="mb-3">Business Hours</h4>
										<div class="row">
											<div class="col-sm-6 mb-2">
												<strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM
											</div>
											<div class="col-sm-6 mb-2">
												<strong>Saturday:</strong> 10:00 AM - 4:00 PM
											</div>
											<div class="col-sm-6 mb-2">
												<strong>Sunday:</strong> Closed
											</div>
											<div class="col-sm-6 mb-2">
												<strong>Holidays:</strong> Closed
											</div>
										</div>
									</div>
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
