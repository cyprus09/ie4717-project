<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FootScape - Catalog</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
    <!-- Main CSS -->
    <link rel="stylesheet" href="../styles/main.css" />
    <!-- Components CSS -->
    <link rel="stylesheet" href="../styles/components/navbar.css" />
    <link rel="stylesheet" href="../styles/components/footer.css" />
    <link rel="stylesheet" href="../styles/components/product-card.css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="../styles/pages/catalog.css" />
  </head>
  <body>
    <!-- Navbar -->
    <?php include "../components/navbar.php" ?>
    <!-- Main Content -->
    <main class="main-content">
        <div class="sidebar">
            <h1>Filter</h1>
            <form action="/submit-form" method="post" class="filter-form">
                <!-- Brand Checkbox Group -->
                <fieldset>
                    <legend>Brand</legend>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Nike" class="checkbox" id="nike"><label for="nike"><span></span>Nike</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Adidas" class="checkbox" id="adidas"><label for="adidas"><span></span>Adidas</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Puma" class="checkbox" id="puma"><label for="puma"><span></span>Puma</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Converse" class="checkbox" id="converse"><label for="converse"><span></span>Converse</label></div>
                </fieldset>

                <!-- Category Checkbox Group -->
                <fieldset>
                    <legend>Category</legend>
                    <div class="checkbox-wrapper"><input type="checkbox" name="category" value="Sneakers" class="checkbox" id="sneakers"><label for="sneakers"><span></span>Sneakers</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="category" value="Running" class="checkbox" id="running"><label for="running"><span></span>Running</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="category" value="Casual" class="checkbox" id="casual"><label for="casual"><span></span>Casual</label></div>
                </fieldset>

                <!-- Gender Checkbox Group -->
                <fieldset>
                    <legend>Gender</legend>
                    <div class="checkbox-wrapper"><input type="checkbox" name="gender" value="Male" class="checkbox" id="male"><label for="male"><span></span>Male</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="gender" value="Female" class="checkbox" id="female"><label for="female"><span></span>Female</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="gender" value="Unisex" class="checkbox" id="unisex"><label for="unisex"><span></span>Unisex</label></div>
                </fieldset>

                <!-- Price Range -->
                <fieldset>
                    <legend>Price Range</legend>
                    <div class="price-range-wrapper">
                        <input type="number" id="min-price" name="minPrice" placeholder="0">
                        -
                        <input type="number" id="max-price" name="maxPrice" placeholder="1000">
                    </div>
                </fieldset>

                <!-- Submit Button -->
                <button type="submit" class="button hover-filled-slide-right filter-button"><span>Apply</span></button>
            </form>
        </div>
        <div class="catalog-section">
            <h1>Explore</h1>
            <div class="product-grid">
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
                <?php include "../components/product-card.php" ?>
            </div>
            <a class="button hover-filled-slide-right" href="#"><span>View More</span></a>
        </div>
    </main>
    <!-- Footer -->
    <?php include "../components/footer.php" ?>
    <script src="../scripts/pages/home.js"></script>
  </body>
</html>
