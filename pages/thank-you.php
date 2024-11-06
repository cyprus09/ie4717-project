<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Redirect if no order details found
if (!isset($_SESSION['last_order'])) {
  header("Location: ./home.php");
  exit;
}

$order = $_SESSION['last_order'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape | Order Confirmation</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/thankyou.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
</head>

<body>
  <?php include "../components/navbar.php" ?>

  <main>
    <div class="thank-you-content">
      <h1>Thank You for Your Order!</h1>
      <div class="success-icon">
        <img src="../assets/thankyou.svg" alt="success">
      </div>
      <p class="order-number">Order #<?php echo str_pad($order['order_id'], 8, '0', STR_PAD_LEFT); ?></p>

      <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="order-items">
          <?php foreach ($order['items'] as $item): ?>
            <div class="order-item">
              <div class="item-info">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
              </div>
              <div class="item-price">
                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="price-breakdown">
          <div class="price-row">
            <span>Subtotal</span>
            <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
          </div>
          <div class="price-row">
            <span>Delivery Fee</span>
            <span>$<?php echo number_format($order['delivery_fee'], 2); ?></span>
          </div>
          <div class="price-row">
            <span>GST (10%)</span>
            <span>$<?php echo number_format($order['gst'], 2); ?></span>
          </div>
          <div class="price-row total">
            <span>Total</span>
            <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
          </div>
        </div>
      </div>

      <div class="shipping-details">
        <h2>Shipping Details</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['shipping']['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping']['address']); ?></p>
        <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($order['shipping']['postal_code']); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($order['shipping']['mobile']); ?></p>
      </div>

    </div>
    <div class="next-steps">
      <a class="button hover-filled-slide-right continue-shopping-btn" href="./home.php"><span>Continue Shopping</span></a>
    </div>
  </main>

  <?php include "../components/footer.php" ?>
</body>

</html>