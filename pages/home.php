<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape | Home</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
  <link rel="stylesheet" href="../styles/components/main-carousel.css" />
  <link rel="stylesheet" href="../styles/components/product-card.css" />
  <link rel="stylesheet" href="../styles/components/card-carousel.css" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/pages/home.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>
  <!-- Main Content -->
  <main class="main-content">
    <!-- Main Highlight -->
    <div class="highlight-section main-highlight">
      <?php include "../components/main-carousel.php" ?>
      <div class="brand-category-section">
        <a class="brand-category-item reveal" href="./catalog.php?brand=Nike">
          <img class="brand-logo" src="../assets/icons/brand/nike.svg" alt="">
          <h2 class="brand-name">Nike</h2>
        </a>
        <a class="brand-category-item reveal" href="./catalog.php?brand=Adidas">
          <img class="brand-logo" src="../assets/icons/brand/adidas.svg" alt="">
          <h2 class="brand-name">Adidas</h2>
        </a>
        <a class="brand-category-item reveal" href="./catalog.php?brand=Puma">
          <img class="brand-logo" src="../assets/icons/brand/puma.svg" alt="">
          <h2 class="brand-name">Puma</h2>
        </a>
        <a class="brand-category-item reveal" href="./catalog.php?brand=Converse">
          <img class="brand-logo" src="../assets/icons/brand/converse.svg" alt="">
          <h2 class="brand-name">Converse</h2>
        </a>
      </div>
    </div>
    <!-- Sale Highlight -->
    <div class="highlight-section sale-highlight">
      <div class="header-wrapper">
        <h1 class="highlight-header">Flash Sale</h1>
        <a class="view-more-button" href="./catalog.php">View More</a>
      </div>
      <div class="bg-text bg-text-gray bg-text-top">Sale Sale Sale Sale Sale Sale Sale Sale Sale Sale</div>
      <div class="bg-text bg-text-gray bg-text-bottom">Sale Sale Sale Sale Sale Sale Sale Sale Sale Sale</div>
      <?php include "../components/card-carousel.php" ?>
    </div>
    <!-- Trending Highlight -->
    <div class="highlight-section trending-highlight">
      <div class="header-wrapper">
        <h1 class="highlight-header">Trending</h1>
        <a class="view-more-button" href="./catalog.php">View More</a>
      </div>
      <div class="bg-text bg-text-white bg-text-top">Trending Trending Trending Trending Trending Trending</div>
      <div class="bg-text bg-text-white bg-text-bottom">Trending Trending Trending Trending Trending Trending</div>
      <?php include "../components/card-carousel.php" ?>
    </div>
    <!-- Categories Highlight -->
    <div class="highlight-section category-highlight">
      <div class="header-wrapper">
        <h1 class="highlight-header">Categories</h1>
      </div>
      <div class="bg-text bg-text-gray bg-text-top">Explore Explore Explore Explore Explore Explore Explore</div>
      <div class="bg-text bg-text-gray bg-text-bottom">Explore Explore Explore Explore Explore Explore Explore</div>
      <br /> <br />
      <div class="category-carousel">
        <a class="category-card sneakers-card" href="./catalog.php?category=Sneakers">
          <h3 class="category-name">Sneakers</h3>
        </a>
        <a class="category-card running-card" href="./catalog.php?category=Running">
          <h3 class="category-name">Running</h3>
        </a>
        <a class="category-card casual-card" href="./catalog.php?category=Casual">
          <h3 class="category-name">Casual</h3>
        </a>
        <a class="category-card below100-card" href="./catalog.php?min-price=0&max-price=100">
          <h3 class="category-name">Below $100</h3>
        </a>
      </div>
    </div>
    <!-- Footer -->
    <?php include "../components/footer.php" ?>
  </main>
  <script src="../scripts/pages/home.js"></script>
  <script src="../scripts/utils/reveal-animation.js"></script>
  <script src="../scripts/utils/bg-text-animation.js"></script>
</body>

</html>