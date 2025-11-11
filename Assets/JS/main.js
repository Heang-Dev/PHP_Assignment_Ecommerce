/**
 * Main JavaScript File for eShop
 * Handles cart interactions, form validation, and dynamic updates
 */

// Cart functionality
document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // Cart Page - Quantity Update & Totals
    // ========================================
    const quantityInputs = document.querySelectorAll('input[name^="quantity"]');

    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                updateCartTotals();
            });

            input.addEventListener('input', function() {
                updateCartTotals();
            });
        });

        // Initial calculation
        updateCartTotals();
    }

    function updateCartTotals() {
        let subtotal = 0;
        const TAX_RATE = 0.05; // 5% tax

        // Calculate subtotal from all products
        quantityInputs.forEach(input => {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('.product-price').textContent.replace('$', ''));
            const quantity = parseInt(input.value) || 0;
            const productSubtotal = price * quantity;

            // Update product subtotal display
            const subtotalCell = row.querySelector('.product-subtotal');
            if (subtotalCell) {
                subtotalCell.textContent = '$' + productSubtotal.toFixed(2);
            }

            subtotal += productSubtotal;
        });

        // Calculate tax and total
        const tax = subtotal * TAX_RATE;
        const total = subtotal + tax;

        // Update totals display
        const subtotalElement = document.getElementById('cart-subtotal');
        const taxElement = document.getElementById('cart-tax');
        const totalElement = document.getElementById('cart-total');

        if (subtotalElement) subtotalElement.textContent = '$' + subtotal.toFixed(2);
        if (taxElement) taxElement.textContent = '$' + tax.toFixed(2);
        if (totalElement) totalElement.textContent = '$' + total.toFixed(2);
    }


    // ========================================
    // Form Validation
    // ========================================

    // Register form validation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    }

    // Checkout form validation
    const checkoutForm = document.querySelector('form[action*="place_order"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
        });
    }


    // ========================================
    // Single Product Page - Image Gallery
    // ========================================
    const smallImages = document.querySelectorAll('.small-img');
    const mainImage = document.getElementById('mainImg');

    if (smallImages.length > 0 && mainImage) {
        smallImages.forEach(img => {
            img.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    }


    // ========================================
    // Add to Cart - Visual Feedback
    // ========================================
    const addToCartButtons = document.querySelectorAll('button[name="add_to_cart"]');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Visual feedback
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ri-check-line me-2"></i>Added!';
            this.disabled = true;

            // Reset after 2 seconds
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    });


    // ========================================
    // Smooth Scroll for Anchor Links
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = this.getAttribute('href');
            if (target !== '#' && document.querySelector(target)) {
                e.preventDefault();
                document.querySelector(target).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });


    // ========================================
    // Auto-hide Alerts
    // ========================================
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // Auto-hide after 5 seconds
    });


    // ========================================
    // Number Input Validation
    // ========================================
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            const min = parseInt(this.min) || 1;
            const max = parseInt(this.max) || 999;
            let value = parseInt(this.value);

            if (isNaN(value) || value < min) {
                this.value = min;
            } else if (value > max) {
                this.value = max;
            }
        });
    });


    // ========================================
    // Shop Page - Category Filter Highlight
    // ========================================
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');

    if (category) {
        const categoryLinks = document.querySelectorAll(`a[href*="category=${category}"]`);
        categoryLinks.forEach(link => {
            link.classList.add('active');
        });
    }


    // ========================================
    // Confirm Before Delete/Remove
    // ========================================
    const removeButtons = document.querySelectorAll('button[name="remove_product"]');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
                return false;
            }
        });
    });


    // ========================================
    // Loading State for Forms
    // ========================================
    const forms = document.querySelectorAll('form[method="POST"]');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                const originalContent = submitButton.innerHTML || submitButton.value;

                if (submitButton.tagName === 'BUTTON') {
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                }
                submitButton.disabled = true;
            }
        });
    });

});


// ========================================
// Helper Functions
// ========================================

/**
 * Format number as currency
 */
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

/**
 * Show toast notification (requires Bootstrap 5)
 */
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }

    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    document.getElementById('toast-container').insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById('toast-container').lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function () {
        this.remove();
    });
}
