<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// // Load cart if user is logged in
// if (isset($_SESSION['user_id'])) {
//   require_once "../utils/cart/cart-functions.php";
//   load_cart_from_db($_SESSION['user_id']);
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape | Cart</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/cart.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>

  <!-- Main Content -->
  <main>
    <div class="container">
      <h1 class="cart-main-header">My Cart</h1>

      <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <div class="cart-items">
          <?php foreach ($_SESSION['cart'] as $index => $item): ?>
            <div class="cart-item" data-price="<?php echo htmlspecialchars($item['price']); ?>">
              <img src="../assets/products/nike-shoe.jpg" alt="<?php echo htmlspecialchars($item['name']); ?>" />
              <div class="item-details">
                <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                <p class="size">Size (US): <span><?php echo htmlspecialchars($item['size']); ?></span></p>
                <p class="price">$<span><?php echo htmlspecialchars($item['price']); ?></span></p>
                <button type="button" class="remove-item" onclick="removeItem(<?php echo $index; ?>)">
                  Remove
                </button>
              </div>
              <div class="quantity">
                <button type="button" class="quantity-btn minus">-</button>
                <input type="number" value="<?php echo htmlspecialchars($item['quantity']); ?>"
                  min="1" max="10" class="quantity-input" readonly />
                <button type="button" class="quantity-btn plus">+</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <form class="checkout-form" method="POST" action="../utils/cart/process-order.php">
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

          <!-- Delivery Address Section -->
          <div class="delivery-address">
            <h2>Delivery Address</h2>
            <input type="text" name="address" placeholder="Address" required
              pattern=".{10,}" title="Please enter a valid address (minimum 10 characters)" />
            <input type="text" name="postal_code" placeholder="Postal Code" required
              pattern="[0-9]{6}" title="Please enter a valid 6-digit postal code" />
            <input type="text" name="full_name" placeholder="Full Name" required
              pattern="[A-Za-z\s]{2,}" title="Please enter a valid name" />
            <input type="tel" name="mobile" placeholder="Mobile" required
              pattern="[0-9]{8,}" title="Please enter a valid phone number" />
          </div>

          <!-- Payment Details Section -->
          <div class="payment-details">
            <h2>Payment Details</h2>
            <div class="payment-options">
              <label><input type="radio" name="payment" value="visa" /> <img src="../assets/icons/brand/visa.svg"></label>
              <label><input type="radio" name="payment" value="mastercard" /> <img src="../assets/icons/brand/mastercard.svg"></label>
              <label><input type="radio" name="payment" value="alipay" /> <img src="../assets/icons/brand/alipay.svg"></label>
              <label><input type="radio" name="payment" value="paypal" /> <img src="../assets/icons/brand/paypal.svg"></label>
            </div>
            <div class="card-details">
              <input type="text" name="card_name" placeholder="Full Name" required
                pattern="[A-Za-z\s]{2,}" title="Please enter the name as shown on card" />
              <input type="text" name="card_number" placeholder="Card Number" required
                pattern="[0-9]{16}" title="Please enter a valid 16-digit card number" />
              <input type="text" name="expiry" placeholder="MM/YY" required
                pattern="(0[1-9]|1[0-2])\/([0-9]{2})" title="Please enter a valid expiry date (MM/YY)" />
              <input type="text" name="cvv" placeholder="CVV" required
                pattern="[0-9]{3,4}" title="Please enter a valid CVV" />
            </div>
          </div>

          <!-- Order Summary Section -->
          <div class="order-summary">
            <h2>Order Summary</h2>
            <p>Subtotal: $<span id="subtotal">0.00</span></p>
            <p>Delivery Fee: $<span id="delivery-fee">15.00</span></p>
            <p>GST (10%): $<span id="gst">0.00</span></p>
            <p class="total">Total: $<span id="total">0.00</span></p>
          </div>

          <button type="submit" class="checkout-btn">Checkout</button>
        </form>
      <?php else: ?>
        <div class="empty-cart">
          <h2>Your cart is empty.</h2>
          <a href="../pages/home.php" class="continue-shopping">Continue Shopping</a>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <?php include "../components/footer.php" ?>
  <script src="../scripts/pages/cart.js"></script>
</body>

</html>