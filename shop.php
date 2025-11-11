<?php
session_start();

// Include database connection and product fetching
include('server/connection.php');
include('server/get_category_products.php');

// Get current category for display
$current_category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Calculate cart count from session
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? $item['quantity'] : 0;
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
		<title>Heang's E-Shop</title>
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
			href="Assets/CSS/shop.css" />
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
								aria-current="page"
								href="index.php"
								>Home</a
							>
						</li>
						<li class="nav-item">
							<a
								class="nav-link px-3 active"
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

		<!--FEATURED-->
		<section
			id="featured"
			class="my-5 pb-5">
			<div class="p-5 mt-5">
				<h3>
					<?php
					if ($current_category === 'all') {
						echo 'All Products';
					} else {
						echo ucfirst($current_category);
					}
					?>
				</h3>
				<hr class="" />
				<p>
					<?php
					if ($current_category === 'all') {
						echo 'Browse our complete collection of quality products.';
					} else {
						echo 'Browse our selection of ' . strtolower($current_category) . '.';
					}
					?>
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php if (!empty($products)): ?>
					<?php foreach ($products as $product): ?>
						<div class="product text-center col-md-6 col-sm-12 col-lg-3 mb-4">
							<div class="product-card h-100">
								<div class="product-img-wrapper" onclick="window.location.href='single_product.php?id=<?php echo $product['product_id']; ?>';" style="cursor: pointer;">
									<img
										src="Assets/Images/<?php echo htmlspecialchars($product['product_image']); ?>"
										alt="<?php echo htmlspecialchars($product['product_name']); ?>"
										class="img-fluid product-img" />
									<div class="product-overlay">
										<a
											href="single_product.php?id=<?php echo $product['product_id']; ?>"
											class="btn btn-light btn-sm">
											<i class="ri-eye-line me-1"></i>
											View Details
										</a>
									</div>
								</div>
								<div class="product-info p-3">
									<div class="star mb-2">
										<?php for ($i = 0; $i < 5; $i++): ?>
											<i class="ri-star-fill text-warning"></i>
										<?php endfor; ?>
									</div>
									<h5 class="p-name mb-2"><?php echo htmlspecialchars($product['product_name']); ?></h5>
									<p class="text-muted small mb-2"><?php echo ucfirst($product['product_category']); ?></p>
									<h4 class="p-price mb-3">$<?php echo number_format($product['product_price'], 2); ?></h4>
									<a
										href="single_product.php?id=<?php echo $product['product_id']; ?>"
										class="buy-btn w-100">
										<i class="ri-shopping-cart-line me-1"></i>
										View Product
									</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center py-5">
						<h4>No products found</h4>
						<p>Try browsing a different category or check back later.</p>
						<a href="shop.php" class="btn btn-primary">View All Products</a>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($total_pages > 1): ?>
			<nav aria-label="Page navigation example">
				<ul class="pagination mt-5 justify-content-center">
					<!-- Previous Button -->
					<li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
						<?php if ($page > 1): ?>
							<a
								class="page-link"
								href="?<?php echo ($current_category !== 'all') ? 'category=' . urlencode($current_category) . '&' : ''; ?>page=<?php echo $page - 1; ?>">
								Previous
							</a>
						<?php else: ?>
							<span class="page-link">Previous</span>
						<?php endif; ?>
					</li>

					<!-- Page Numbers -->
					<?php
					// Show max 5 page numbers
					$start_page = max(1, $page - 2);
					$end_page = min($total_pages, $page + 2);

					// Show first page if not in range
					if ($start_page > 1):
					?>
						<li class="page-item">
							<a
								class="page-link"
								href="?<?php echo ($current_category !== 'all') ? 'category=' . urlencode($current_category) . '&' : ''; ?>page=1">
								1
							</a>
						</li>
						<?php if ($start_page > 2): ?>
							<li class="page-item disabled"><span class="page-link">...</span></li>
						<?php endif; ?>
					<?php endif; ?>

					<?php for ($i = $start_page; $i <= $end_page; $i++): ?>
						<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
							<a
								class="page-link"
								href="?<?php echo ($current_category !== 'all') ? 'category=' . urlencode($current_category) . '&' : ''; ?>page=<?php echo $i; ?>">
								<?php echo $i; ?>
							</a>
						</li>
					<?php endfor; ?>

					<!-- Show last page if not in range -->
					<?php if ($end_page < $total_pages): ?>
						<?php if ($end_page < $total_pages - 1): ?>
							<li class="page-item disabled"><span class="page-link">...</span></li>
						<?php endif; ?>
						<li class="page-item">
							<a
								class="page-link"
								href="?<?php echo ($current_category !== 'all') ? 'category=' . urlencode($current_category) . '&' : ''; ?>page=<?php echo $total_pages; ?>">
								<?php echo $total_pages; ?>
							</a>
						</li>
					<?php endif; ?>

					<!-- Next Button -->
					<li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
						<?php if ($page < $total_pages): ?>
							<a
								class="page-link"
								href="?<?php echo ($current_category !== 'all') ? 'category=' . urlencode($current_category) . '&' : ''; ?>page=<?php echo $page + 1; ?>">
								Next
							</a>
						<?php else: ?>
							<span class="page-link">Next</span>
						<?php endif; ?>
					</li>
				</ul>
			</nav>
			<?php endif; ?>
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
