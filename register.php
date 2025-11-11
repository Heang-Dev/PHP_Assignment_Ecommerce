<?php
/**
 * User Registration Page
 */

session_start();

// If user is already logged in, redirect to account page
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: account.php');
    exit();
}

// Include database connection
include('server/connection.php');

// Initialize variables
$error_message = '';
$success_message = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    }
    elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }
    elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    }
    else {
        try {
            // Check if email already exists
            $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE user_email = :email");
            $check_stmt->execute([':email' => $email]);

            if ($check_stmt->fetch()) {
                $error_message = "Email already registered. Please use a different email or <a href='login.php'>login</a>.";
            } else {
                // Hash password using bcrypt (more secure than MD5)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $insert_stmt = $conn->prepare("
                    INSERT INTO users (user_name, user_email, user_password)
                    VALUES (:name, :email, :password)
                ");

                $insert_stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);

                // Get the newly created user ID
                $user_id = $conn->lastInsertId();

                // Auto-login after successful registration
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['logged_in'] = true;

                // Redirect to account page
                header('Location: account.php?success=Registration successful! Welcome to eShop.');
                exit();
            }

        } catch (PDOException $e) {
            $error_message = "Registration failed. Please try again.";
        }
    }
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
		<title>Register - Heang's E-Shop</title>
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
			href="Assets/CSS/register.css" />
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

		<section class="my-5 py-5">
			<div class="container text-center mt-3 pt-5">
				<h2 class="form-weight-bold">Register</h2>
				<hr class="mx-auto" />
			</div>
			<div class="mx-auto container">
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

				<form id="register-form" method="POST" action="register.php">
					<div class="form-group">
						<label>Name</label>
						<input
							type="text"
							class="form-control"
							id="register-name"
							name="name"
							placeholder="Name"
							value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
							required />
					</div>
					<div class="form-group">
						<label>Email</label>
						<input
							type="email"
							class="form-control"
							id="register-email"
							name="email"
							placeholder="Email"
							value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
							required />
					</div>
					<div class="form-group">
						<label>Password</label>
						<input
							type="password"
							class="form-control"
							id="register-password"
							name="password"
							placeholder="Password"
							required />
						<small class="form-text text-muted">Minimum 6 characters</small>
					</div>
					<div class="form-group">
						<label>Confirm Password</label>
						<input
							type="password"
							class="form-control"
							id="register-confirm-password"
							name="confirm-password"
							placeholder="Confirm Password"
							required />
					</div>
					<div class="form-group">
						<input
							type="submit"
							class="btn"
							id="register-btn"
							name="register"
							value="Register" />
					</div>
					<div>
						<a
							href="login.php"
							id="register_url"
							>Have an account? Login</a
						>
					</div>
				</form>
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
