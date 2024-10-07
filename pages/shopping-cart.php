<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape - Shopping Cart</title>
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
      <h1>My Cart</h1>
      <div class="cart-items">
        <div class="cart-item">
          <img src="../assets/products/nike-shoe.jpg" alt="Jordan 1 Black & White" />
          <div class="item-details">
            <h2>Jordan 1 Black & White</h2>
            <p>Size (US): <span class="size">10</span></p>
            <p class="price">$499.00</p>
          </div>
          <div class="quantity">
            <button class="quantity-btn minus">-</button>
            <input type="number" value="1" min="1" max="10" />
            <button class="quantity-btn plus">+</button>
          </div>
        </div>
      </div>

      <form class="checkout-form">
        <!-- Delivery Address -->
        <div class="delivery-address">
          <h2>Delivery Address</h2>
          <input type="text" placeholder="Address" required />
          <input type="text" placeholder="Postal Code" required />
          <input type="text" placeholder="Full Name" required />
          <input type="tel" placeholder="Mobile" required />
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
          <h2>Payment Details</h2>
          <div class="payment-options">
            <label><input type="radio" name="payment" value="visa" /> <img src="../assets/icons/brand/visa.svg"></label>
            <label><input type="radio" name="payment" value="mastercard" /> <img src="../assets/icons/brand/mastercard.svg"></label>
            <label><input type="radio" name="payment" value="alipay" /> <img src="../assets/icons/brand/alipay.svg"></label>
            <label><input type="radio" name="payment" value="paypal" /> <img src="../assets/icons/brand/paypal.svg"></label>
          </div>
          <div class="card-details">
            <input type="text" placeholder="Full Name" required />
            <input type="text" placeholder="Card Number" required />
            <input type="text" placeholder="Expiry Date" required />
            <input type="text" placeholder="CVV" required />
          </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
          <h2>Subtotal</h2>
          <p>Subtotal: $XX.XX</p>
          <p>Delivery Fee: $XX.XX</p>
          <p>GST (10%): $XX.XX</p>
          <p class="total">Total: $XX.XX</p>
        </div>

        <button type="submit" class="checkout-btn">Checkout</button>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <?php include "../components/footer.php" ?>

</body>

</html>