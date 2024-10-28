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
  <link rel="stylesheet" href="../styles/pages/home.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
  <link rel="stylesheet" href="../styles/components/main-carousel.css" />
  <link rel="stylesheet" href="../styles/components/product-card.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>
  <!-- Main Content -->
  <main>
    <div class="product-container">
      <div class="product-image-container">
        <img src="../assets/products/nike-shoe.jpg" alt="Jordan 1 Black & White" class="product-image">
      </div>
      <div class="product-info">
        <h1 class="product-title">Jordan 1 Black & White</h1>
        <p class="product-price">$499.00</p>
        <p class="product-description">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>

        <div class="product-options">
          <div class="size-selector">
            <label class="option-label">Size (US)</label>
            <div class="select-wrapper">
              <select class="size-select">
                <option value="45">45</option>
                <option value="44">44</option>
                <option value="43">43</option>
              </select>
            </div>
            <a href="#" class="size-chart-link">Size Chart</a>
          </div>

          <div class="quantity-selector">
            <label class="option-label">Quantity</label>
            <div class="quantity-controls">
              <button class="quantity-btn minus">-</button>
              <input type="text" class="quantity-input" value="1" readonly>
              <button class="quantity-btn plus">+</button>
            </div>
          </div>
        </div>

        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
    </div>
  </main>
  <!-- Footer -->
  <?php include "../components/footer.php" ?>
</body>

</html>