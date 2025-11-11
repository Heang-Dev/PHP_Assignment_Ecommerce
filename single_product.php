<?php
session_start();

// Include database connection
include('server/connection.php');

// Initialize cart count
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['product_quantity']) ? $item['product_quantity'] : 0;
    }
}

// Get product_id from URL (support both ?id= and ?product_id=)
$product_id = null;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} elseif (isset($_GET['id'])) {
    $product_id = $_GET['id'];
}

// Initialize variables
$product = null;
$related_products = array();
$error_message = null;

// Validate and fetch product if ID is provided
if ($product_id) {
    try {
        // Fetch product details from database
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error_message = "Product not found.";
        } else {
            // Fetch related products from the same category (limit 4)
            $stmt_related = $conn->prepare("
                SELECT * FROM products
                WHERE product_category = :category
                AND product_id != :product_id
                LIMIT 4
            ");
            $stmt_related->bindParam(':category', $product['product_category'], PDO::PARAM_STR);
            $stmt_related->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt_related->execute();
            $related_products = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error_message = "Error fetching product: " . $e->getMessage();
    }
} else {
    $error_message = "No product ID provided.";
}

// Prepare image paths with fallback
function getImagePath($image) {
    if ($image && file_exists(__DIR__ . '/Assets/Images/' . $image)) {
        return 'Assets/Images/' . $image;
    }
    return 'Assets/Images/featured_1.png'; // Default fallback image
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
		<title><?php echo $product ? htmlspecialchars($product['product_name']) : 'Product'; ?> - Heang's E-Shop</title>
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
			href="Assets/CSS/single_product.css" />
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
								class="nav-link px-3 active"
								aria-current="page"
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

		<!-- SINGLE PRODUCT -->
		<section
			id="single_product"
			class="container single-product my-5 pt-5">
			<?php if ($error_message): ?>
				<!-- Error Message -->
				<div class="row mt-5">
					<div class="col-12 text-center py-5">
						<i class="ri-error-warning-line" style="font-size: 80px; color: #ff6b6b;"></i>
						<h3 class="mt-3"><?php echo htmlspecialchars($error_message); ?></h3>
						<p class="text-muted">The product you're looking for might have been removed or is temporarily unavailable.</p>
						<a href="shop.php" class="btn btn-primary mt-3">Back to Shop</a>
					</div>
				</div>
			<?php elseif ($product): ?>
				<div class="row mt-5">
					<div class="col-lg-5 col-md-6 col-sm-12">
						<img
							src="<?php echo getImagePath($product['product_image']); ?>"
							alt="<?php echo htmlspecialchars($product['product_name']); ?>"
							id="mainImg"
							class="img-fluid w-100 pb-1" />
						<div class="small-img-group">
							<div class="small-img-col">
								<img
									src="<?php echo getImagePath($product['product_image']); ?>"
									alt="<?php echo htmlspecialchars($product['product_name']); ?> - View 1"
									class="small-img img-fluid w-100" />
							</div>
							<div class="small-img-col">
								<img
									src="<?php echo getImagePath($product['product_image2'] ?: $product['product_image']); ?>"
									alt="<?php echo htmlspecialchars($product['product_name']); ?> - View 2"
									class="small-img img-fluid w-100" />
							</div>
							<div class="small-img-col">
								<img
									src="<?php echo getImagePath($product['product_image3'] ?: $product['product_image']); ?>"
									alt="<?php echo htmlspecialchars($product['product_name']); ?> - View 3"
									class="small-img img-fluid w-100" />
							</div>
							<div class="small-img-col">
								<img
									src="<?php echo getImagePath($product['product_image4'] ?: $product['product_image']); ?>"
									alt="<?php echo htmlspecialchars($product['product_name']); ?> - View 4"
									class="small-img img-fluid w-100" />
							</div>
						</div>
					</div>

					<div class="col-lg-6 col-md-12 col-12">
						<h6><?php echo htmlspecialchars(ucfirst($product['product_category'])); ?><?php echo $product['product_color'] ? ' / ' . htmlspecialchars($product['product_color']) : ''; ?></h6>
						<h3 class="py-4"><?php echo htmlspecialchars($product['product_name']); ?></h3>
						<h2>$<?php echo number_format($product['product_price'], 2); ?></h2>

						<!-- Add to Cart Form -->
						<form method="POST" action="cart.php">
							<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
							<input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
							<input type="hidden" name="product_price" value="<?php echo $product['product_price']; ?>">
							<input type="hidden" name="product_image" value="<?php echo getImagePath($product['product_image']); ?>">
							<input
								type="number"
								name="product_quantity"
								value="1"
								min="1"
								max="99"
								class="quantity-input" />
							<button type="submit" name="add_to_cart" class="buy-btn ms-2 px-3 py-2">Add To Cart</button>
						</form>

						<h4 class="my-5">Product Details</h4>
						<span><?php echo nl2br(htmlspecialchars($product['product_description'])); ?></span>
					</div>
				</div>
			<?php endif; ?>
		</section>

		<!--RELATED PRODUCTS-->
		<?php if ($product && !empty($related_products)): ?>
		<section
			id="related-products"
			class="my-5 pb-5">
			<div class="text-center container mt-5 py-5">
				<h3>Related Products</h3>
				<hr class="mx-auto" />
				<p>
					Discover more products from the <?php echo htmlspecialchars($product['product_category']); ?> category.
					Find the perfect match for your style and needs.
				</p>
			</div>
			<div class="row mx-auto container-fluid">
				<?php foreach ($related_products as $related): ?>
				<div
					class="product text-center col-md-4 col-sm-12 col-lg-3"
					onclick="window.location.href='single_product.php?id=<?php echo $related['product_id']; ?>';"
					style="cursor: pointer;">
					<img
						src="<?php echo getImagePath($related['product_image']); ?>"
						alt="<?php echo htmlspecialchars($related['product_name']); ?>"
						class="img-fluid mb-3" />
					<div class="star">
						<i class="ri-star-line"></i>
						<i class="ri-star-line"></i>
						<i class="ri-star-line"></i>
						<i class="ri-star-line"></i>
						<i class="ri-star-line"></i>
					</div>
					<h5 class="p-name"><?php echo htmlspecialchars($related['product_name']); ?></h5>
					<h4 class="p-price">$<?php echo number_format($related['product_price'], 2); ?></h4>
					<a
						href="single_product.php?id=<?php echo $related['product_id']; ?>"
						class="buy-btn"
						onclick="event.stopPropagation();">
						Buy Now
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endif; ?>

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

		<script>
			// Image gallery switcher
			var mainImg = document.getElementById("mainImg");
			var smallImg = document.getElementsByClassName("small-img");

			for (let i = 0; i < smallImg.length; i++) {
				smallImg[i].onclick = function () {
					mainImg.src = smallImg[i].src;
				};
			}
		</script>
	</body>
</html>
