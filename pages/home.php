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
          <a class="brand-category-item" href="#">
            <img class="brand-logo" src="../assets/icons/brand/nike.svg" alt="">
            <h2 class="brand-name">Nike</h2>
          </a>
          <a class="brand-category-item" href="#">
            <img class="brand-logo" src="../assets/icons/brand/adidas.svg" alt="">
            <h2 class="brand-name">Adidas</h2>
          </a>
          <a class="brand-category-item" href="#">
            <img class="brand-logo" src="../assets/icons/brand/puma.svg" alt="">
            <h2 class="brand-name">Puma</h2>
          </a>
          <a class="brand-category-item" href="#">
            <img class="brand-logo" src="../assets/icons/brand/converse.svg" alt="">
            <h2 class="brand-name">Converse</h2>
          </a>
        </div>
      </div>
      <!-- Sale Highlight -->
      <div class="highlight-section sale-highlight">
        <div class="header-wrapper">
          <h1 class="highlight-header">Flash Sale</h1>
          <a class="view-more-button" href="#">View More</a>
        </div> 
        <div class="bg-text bg-text-gray bg-text-top">Sale Sale Sale Sale Sale Sale Sale Sale Sale Sale</div>
        <div class="bg-text bg-text-gray bg-text-bottom">Sale Sale Sale Sale Sale Sale Sale Sale Sale Sale</div>
        <?php include "../components/card-carousel.php" ?>
      </div>
      <!-- Trending Highlight -->
      <div class="highlight-section trending-highlight">
        <div class="header-wrapper">
          <h1 class="highlight-header">Trending</h1>
          <a class="view-more-button" href="#">View More</a>
        </div>
        <div class="bg-text bg-text-white bg-text-top">Trending Trending Trending Trending Trending Trending</div>
        <div class="bg-text bg-text-white bg-text-bottom">Trending Trending Trending Trending Trending Trending</div>
        <?php include "../components/card-carousel.php" ?>
      </div>
      <!-- Categories Highlight -->
      <div class="highlight-section category-highlight">
        <div class="header-wrapper">
          <h1 class="highlight-header">Categories</h1>
          <a class="view-more-button" href="#">View More</a>
        </div>
        <div class="bg-text bg-text-gray bg-text-top">Explore Explore Explore Explore Explore Explore Explore</div>
        <div class="bg-text bg-text-gray bg-text-bottom">Explore Explore Explore Explore Explore Explore Explore</div>
        <?php include "../components/card-carousel.php" ?>
      </div>
    <!-- Footer -->
    <?php include "../components/footer.php" ?>
    </main>
    <script src="../scripts/pages/home.js"></script>
  </body>
</html>
