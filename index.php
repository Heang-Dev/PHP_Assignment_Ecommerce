<?php
/**
 * E-Commerce Homepage
 * Displays featured products and products by category (bags, hats, watches)
 */

// Start session
session_start();

// Include database connection
include('server/connection.php');

// Include featured products
include('server/get_featured_products.php');

// Get products by category
try {
    // Fetch bags
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category = :category LIMIT 4");
    $stmt->execute([':category' => 'bags']);
    $bag_products = $stmt->fetchAll();

    // Fetch hats
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category = :category LIMIT 4");
    $stmt->execute([':category' => 'hats']);
    $hat_products = $stmt->fetchAll();

    // Fetch watches
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_category = :category LIMIT 4");
    $stmt->execute([':category' => 'watches']);
    $watch_products = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_message = "Error fetching products: " . $e->getMessage();
    $bag_products = [];
    $hat_products = [];
    $watch_products = [];
}

// Calculate cart count
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
								class="nav-link px-3 active"
								aria-current="page"
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

		<!--Home-->
		<section id="home">
			<div class="text-white">
				<h5 class="text-uppercase title">New Arrival</h5>
				<h1>Best Price This Season</h1>
				<p>
					E-Shop offered the best products this season. We have a wide
					range of products for you to choose from.<br />
					We also have a special offer for you. If you buy 2 products,
					you will get 1 product for free.
				</p>
				<button>Shop Now</button>
			</div>
		</section>

		<!--Brands-->
		<section
			id="brand"
			class="container">
			<div class="row g-2 p-2">
				<img
					src="Assets/Images/brand_1.jpg"
					alt=""
					class="col-md-6 col-sm-12 col-lg-3 img-fluid" />
				<img
					src="Assets/Images/brand_2.jpg"
					alt=""
					class="col-md-6 col-sm-12 col-lg-3 img-fluid" />
				<img
					src="Assets/Images/brand_3.jpg"
					alt=""
					class="col-md-6 col-sm-12 col-lg-3 img-fluid" />
				<img
					src="Assets/Images/brand_4.jpg"
					alt=""
					class="col-md-6 col-sm-12 col-lg-3 img-fluid" />
			</div>
		</section>

		<!--New-->
		<section
			id="new"
			class="w-100">
			<div class="row p-0 m-0">
				<!--ONE-->
				<div class="p-0 col-lg-4 col-md-12 col-sm-12 one">
					<img
						src="Assets/Images/new_1.jpeg"
						alt=""
						class="img-fluid" />
					<div class="details">
						<h2>New Collection of Shoes</h2>
						<button class="text-uppercase">Shop Now</button>
					</div>
				</div>
				<!--TWO-->
				<div class="p-0 col-lg-4 col-md-12 col-sm-12 one">
					<img
						src="Assets/Images/new_2.jpeg"
						alt=""
						class="img-fluid" />
					<div class="details">
						<h2>Awesome Jackets</h2>
						<button class="text-uppercase">Shop Now</button>
					</div>
				</div>
				<!--THREE-->
				<div class="p-0 col-lg-4 col-md-12 col-sm-12 one">
					<img
						src="Assets/Images/new_3.jpeg"
						alt=""
						class="img-fluid" />
					<div class="details">
						<h2>50% Off Watches</h2>
						<button class="text-uppercase">Shop Now</button>
					</div>
				</div>
			</div>
		</section>

		<!--FEATURED-->
		<section
			id="featured"
			class="my-5 pb-5">
			<div class="text-center container mt-5 py-5">
				<h3>Featured Products</h3>
				<hr class="mx-auto" />
				<p>
					Our featured products are handpicked by our team of experts.
					We have a wide range of products for you to choose from. We
					also have a special offer for you. If you buy 2 products,
					you will get 1 product for free.
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php if (!empty($featured_products)): ?>
					<?php foreach ($featured_products as $product): ?>
						<div class="product text-center col-md-4 col-sm-12 col-lg-3">
							<img
								src="Assets/Images/<?php echo htmlspecialchars($product['product_image']); ?>"
								alt="<?php echo htmlspecialchars($product['product_name']); ?>"
								class="img-fluid mb-3" />
							<div class="star">
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
							</div>
							<h5 class="p-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
							<h4 class="p-price">$<?php echo number_format($product['product_price'], 2); ?></h4>
							<a href="single_product.php?product_id=<?php echo $product['product_id']; ?>">
								<button class="buy-btn">Buy Now</button>
							</a>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center">
						<p>No featured products available at the moment.</p>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<!--BANNER-->
		<section
			id="banner"
			class="my-5 py-5">
			<div class="container">
				<h4 class="text-uppercase">Mid-Season's Sale</h4>
				<h1>
					Autumn Collections <br />
					Up to 50% Off
				</h1>
				<button class="text-uppercase">Shop Now</button>
			</div>
		</section>

		<!--Bags-->
		<section
			id="bag"
			class="my-5 pb-5">
			<div class="text-center container mt-5">
				<h3>Bags & Backpacks</h3>
				<hr class="mx-auto" />
				<p>
					Our featured products are handpicked by our team of experts.
					We have a wide range of products for you to choose from. We
					also have a special offer for you. If you buy 2 products,
					you will get 1 product for free.
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php if (!empty($bag_products)): ?>
					<?php foreach ($bag_products as $product): ?>
						<div class="product text-center col-md-4 col-sm-12 col-lg-3">
							<img
								src="Assets/Images/<?php echo htmlspecialchars($product['product_image']); ?>"
								alt="<?php echo htmlspecialchars($product['product_name']); ?>"
								class="img-fluid mb-3" />
							<div class="star">
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
							</div>
							<h5 class="p-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
							<h4 class="p-price">$<?php echo number_format($product['product_price'], 2); ?></h4>
							<a href="single_product.php?product_id=<?php echo $product['product_id']; ?>">
								<button class="buy-btn">Buy Now</button>
							</a>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center">
						<p>No bags available at the moment.</p>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<!--Hat-->
		<section
			id="hat"
			class="my-5 pb-5">
			<div class="text-center container mt-5">
				<h3>Hats & Caps</h3>
				<hr class="mx-auto" />
				<p>
					Our featured products are handpicked by our team of experts.
					We have a wide range of products for you to choose from. We
					also have a special offer for you. If you buy 2 products,
					you will get 1 product for free.
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php if (!empty($hat_products)): ?>
					<?php foreach ($hat_products as $product): ?>
						<div class="product text-center col-md-4 col-sm-12 col-lg-3">
							<img
								src="Assets/Images/<?php echo htmlspecialchars($product['product_image']); ?>"
								alt="<?php echo htmlspecialchars($product['product_name']); ?>"
								class="img-fluid mb-3" />
							<div class="star">
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
							</div>
							<h5 class="p-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
							<h4 class="p-price">$<?php echo number_format($product['product_price'], 2); ?></h4>
							<a href="single_product.php?product_id=<?php echo $product['product_id']; ?>">
								<button class="buy-btn">Buy Now</button>
							</a>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center">
						<p>No hats available at the moment.</p>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<!--WATCHES-->
		<section
			id="watches"
			class="my-5 pb-5">
			<div class="text-center container mt-5">
				<h3>Best Watches</h3>
				<hr class="mx-auto" />
				<p>
					Our featured products are handpicked by our team of experts.
					We have a wide range of products for you to choose from. We
					also have a special offer for you. If you buy 2 products,
					you will get 1 product for free.
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php if (!empty($watch_products)): ?>
					<?php foreach ($watch_products as $product): ?>
						<div class="product text-center col-md-4 col-sm-12 col-lg-3">
							<img
								src="Assets/Images/<?php echo htmlspecialchars($product['product_image']); ?>"
								alt="<?php echo htmlspecialchars($product['product_name']); ?>"
								class="img-fluid mb-3" />
							<div class="star">
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
								<i class="ri-star-line"></i>
							</div>
							<h5 class="p-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
							<h4 class="p-price">$<?php echo number_format($product['product_price'], 2); ?></h4>
							<a href="single_product.php?product_id=<?php echo $product['product_id']; ?>">
								<button class="buy-btn">Buy Now</button>
							</a>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center">
						<p>No watches available at the moment.</p>
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
	</body>
</html>
