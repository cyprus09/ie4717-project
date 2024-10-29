<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/prod-desc.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
  <link rel="stylesheet" href="../styles/components/product-card.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>
  <!-- Main Content -->
  <main>
    <div class="product-container">
      <div class="product-image-container">
        <img src="../assets/products/nike-shoe2.jpg" alt="Jordan 1 Black & White" class="product-image">
      </div>
      <div class="product-info">
        <h1 class="product-title">Jordan 1 Black & White</h1>
        <p class="product-price">$499.00</p>
        <p class="product-description">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>

        <div class="product-options">
          <div class="size-selector">
            <label class="option-label">Size (UK)</label>
            <div class="select-wrapper">
              <select class="size-select">
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9" selected>9</option>
                <option value="10">10</option>
                <option value="11">11</option>
              </select>
            </div>
            <a href="#" class="size-chart-link" id="sizeChart">Size Chart</a>
            <div id="myModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <img src="../assets/size-chart.png" alt="Size Chart" class="size-chart-image">
              </div>
            </div>
          </div>
        </div>

        <div class="quantity-selector">
          <label class="option-label">Quantity</label>
          <div class="quantity">
            <button class="quantity-btn minus">-</button>
            <input type="number" value="1" min="1" max="10" class="quantity-input" readonly />
            <button class="quantity-btn plus">+</button>
          </div>
        </div>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>

    </div>

    <h1 class="header-similar-products">Similar Products</h1>
    <div class="similar-products">
      <div class="similar-product-item">
        <?php include "../components/product-card.php" ?>
      </div>
      <div class="similar-product-item">
        <?php include "../components/product-card.php" ?>
      </div>
      <div class="similar-product-item">
        <?php include "../components/product-card.php" ?>
      </div>
      <div class="similar-product-item">
        <?php include "../components/product-card.php" ?>
      </div>
    </div>

  </main>
  <!-- Footer -->
  <?php include "../components/footer.php" ?>
  <script src="../scripts/pages/prod-desc.js"></script>
</body>

</html>