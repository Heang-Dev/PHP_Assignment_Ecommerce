# E-commerce Website Project

A full-featured e-commerce website built with PHP, SQLite, and Bootstrap 5.

## 🚀 Project Status: ✅ COMPLETE

All major features have been successfully implemented and are working!

## 🎯 Features Implemented

### User Authentication & Management
- ✅ User registration with secure password hashing (bcrypt)
- ✅ User login with session management
- ✅ User account dashboard
- ✅ Change password functionality
- ✅ Order history tracking
- ✅ Logout functionality

### Product Catalog
- ✅ Dynamic product loading from SQLite database
- ✅ Product categories (Shoes, Bags, Hats, Watches)
- ✅ Featured products section
- ✅ Product detail pages with 4-image gallery
- ✅ Related products display
- ✅ Category filtering
- ✅ Pagination (8 products per page)

### Shopping Cart
- ✅ Add to cart functionality
- ✅ Update product quantities
- ✅ Remove products from cart
- ✅ Real-time total calculation
- ✅ Tax calculation (5%)
- ✅ Session-based cart persistence
- ✅ Dynamic cart count badge

### Checkout & Orders
- ✅ Secure checkout process (login required)
- ✅ Order form with shipping details
- ✅ Order placement and storage
- ✅ Order confirmation page
- ✅ Order history in user account
- ✅ Order status tracking

## 📦 Database

**Type**: SQLite (as requested)
**Location**: `/database/ecommerce.db`
**Pre-populated with**:
- 16 products (4 per category)
- 1 test user account

### Test Credentials
- **Email**: test@example.com
- **Password**: password123

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+ with PDO
- **Database**: SQLite
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 5.0.2
- **Icons**: RemixIcon 4.6.0
- **Security**: bcrypt password hashing, prepared statements

## 📁 Project Structure

```
Projects/
├── Assets/
│   ├── CSS/          # Stylesheets
│   ├── Images/       # Product images (16 products)
│   └── JS/           # JavaScript files
├── database/
│   ├── ecommerce.db            # SQLite database (28 KB)
│   ├── create_database.php     # Database setup script
│   └── verify_database.php     # Verification script
├── server/
│   ├── connection.php          # Database connection (PDO + SQLite)
│   ├── get_featured_products.php
│   ├── get_category_products.php
│   └── place_order.php
├── index.php          # Homepage with dynamic products
├── shop.php           # Product catalog with pagination
├── single_product.php # Product detail page
├── cart.php           # Shopping cart
├── checkout.php       # Checkout page
├── payment.php        # Order confirmation
├── login.php          # User login
├── register.php       # User registration
├── account.php        # User dashboard
├── contact.php        # Contact page
└── blog.php           # Blog (coming soon placeholder)
```

## 🚦 Quick Start

### Using PHP Built-in Server

```bash
# Navigate to project directory
cd /home/mengheang/Desktop/Learning/School/YEAR_4_SEMESTER_1/Projects

# Start PHP server
php -S localhost:8000

# Open browser
http://localhost:8000/index.php
```

### Using XAMPP/WAMP/LAMP

1. Copy project to web server directory
2. Access via `http://localhost/Projects/index.php`

## 📝 Complete User Flow

1. **Browse** → Visit index.php, browse featured products
2. **Shop** → Click categories or shop page
3. **View Product** → Click on any product to see details
4. **Add to Cart** → Select quantity and add to cart
5. **View Cart** → Review items, update quantities
6. **Checkout** → Click checkout (will prompt login if needed)
7. **Login/Register** → Create account or login
8. **Place Order** → Fill shipping details and place order
9. **Confirmation** → View order confirmation
10. **View Orders** → Check order history in account page

## 🎨 Key Improvements Over Reference Project

1. **Security**: bcrypt instead of MD5 password hashing
2. **Database**: SQLite instead of MySQL (portable, no server needed)
3. **Validation**: Enhanced form validation
4. **UX**: Better error messages and user feedback
5. **Code Quality**: Clean, well-documented code
6. **Responsive**: Fully responsive across all devices

## 📊 Database Verification

Run this command to verify database:
```bash
php database/verify_database.php
```

Expected output:
```
✓ Total Products: 16
  - shoes: 4 products
  - bags: 4 products
  - hats: 4 products
  - watches: 4 products
✓ Total Users: 1
✓ Total Orders: 0
```

## 🔐 Security Features

- ✅ Password hashing with bcrypt (PASSWORD_DEFAULT)
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS prevention (htmlspecialchars on all outputs)
- ✅ Session-based authentication
- ✅ Protected pages (checkout, account require login)
- ✅ Secure password validation (minimum 6 characters)

## 📱 Pages Overview

| Page | URL | Description | Auth Required |
|------|-----|-------------|---------------|
| Homepage | index.php | Featured products, categories | No |
| Shop | shop.php | All products with pagination | No |
| Product Detail | single_product.php | Product info, image gallery | No |
| Cart | cart.php | Shopping cart management | No |
| Login | login.php | User login | No |
| Register | register.php | User registration | No |
| Checkout | checkout.php | Order placement | Yes |
| Payment | payment.php | Order confirmation | Yes |
| Account | account.php | User dashboard, orders | Yes |
| Contact | contact.php | Contact information | No |
| Blog | blog.php | Coming soon placeholder | No |

## ✅ All Requirements Met

- [x] Database: SQLite (not MySQL)
- [x] User registration with validation
- [x] User login with authentication
- [x] Dynamic product loading
- [x] Shopping cart functionality
- [x] Checkout process
- [x] Order placement and history
- [x] Session management
- [x] Security improvements (bcrypt)
- [x] Responsive design
- [x] Clean navigation flow
- [x] All pages converted from HTML to PHP

## 🎓 Assignment Notes

This project successfully adapts the reference project (friend's MySQL version) to use SQLite while implementing all features and improving security. The project demonstrates:

- Full-stack PHP development
- Database integration (SQLite)
- Session management
- User authentication
- E-commerce functionality
- Responsive web design
- Security best practices

## 👨‍💻 Developer

**Meng Heang**
- Year 4, Semester 1
- Course: PHP E-commerce Development

---

**Status**: ✅ Ready for testing and submission
**Last Updated**: November 11, 2025
