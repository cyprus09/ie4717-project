<?php
// Import DB connection and session starting
require_once "../utils/auth/dbconnect.php";
require_once "../utils/auth/session.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get product name from URL and decode it
$product_name = isset($_GET['name']) ? urldecode($_GET['name']) : '';

// Prepare and execute query with GROUP BY
$stmt = $db->prepare("
    SELECT 
        name,
        image_url,
        description,
        price,
        MAX(IF(size = 6, quantity, 0)) AS qty_size_6,
        MAX(IF(size = 7, quantity, 0)) AS qty_size_7,
        MAX(IF(size = 8, quantity, 0)) AS qty_size_8,
        MAX(IF(size = 9, quantity, 0)) AS qty_size_9,
        MAX(IF(size = 10, quantity, 0)) AS qty_size_10,
        MAX(IF(size = 11, quantity, 0)) AS qty_size_11,
        MAX(IF(size = 12, quantity, 0)) AS qty_size_12
    FROM products 
    WHERE name = ? 
    GROUP BY name
");
$stmt->bind_param("s", $product_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Create array of available sizes
    $available_sizes = [];
    for ($size = 6; $size <= 12; $size++) {
        $qty_field = "qty_size_" . $size;
        if ($product[$qty_field] > 0) {
            $available_sizes[] = $size;
        }
    }
} else {
    // Redirect to 404 page or show error
    header("Location: ../404.php");
    exit();
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape - <?php echo htmlspecialchars($product['name']); ?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/prod-desc.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
  <link rel="stylesheet" href="../styles/components/product-card.css" />
  <link rel="stylesheet" href="../styles/components/card-carousel.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include "../components/navbar.php" ?>
  <!-- Main Content -->
  <main>
    <div class="product-container">
      <div class="product-image-container" style="background-image: url(<?php echo $product['image_url']; ?>)"></div>
      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
        <p class="product-description">
          <?php echo htmlspecialchars($product['description']); ?>
        </p>

        <div class="product-options">
          <div class="size-selector">
            <label class="option-label">Size (UK)</label>
            <div class="select-wrapper">
              <select class="size-select" id="size-select" <?php echo empty($available_sizes) ? 'disabled' : ''; ?>>
                  <?php
                  if (!empty($available_sizes)) {
                      foreach ($available_sizes as $size) {
                          $selected = ($size == 9) ? 'selected' : '';
                          $qty = $product['qty_size_' . $size];
                          echo "<option value='{$size}' data-quantity='{$qty}' {$selected}>{$size}</option>";
                      }
                  } else {
                      echo "<option value='' disabled>Out of Stock</option>";
                  }
                  ?>
              </select>
            </div>
            <!-- <a href="#" class="size-chart-link" id="sizeChart">Size Chart</a> -->
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
                <input type="number" value="1" min="1" class="quantity-input" id="quantity-input" readonly />
                <button class="quantity-btn plus">+</button>
            </div>
        </div>
        <button type="submit" 
                class="button hover-filled-slide-right" 
                onclick="addToCart('<?php echo htmlspecialchars($product['name']); ?>', <?php echo htmlspecialchars($product['price']); ?>)"
                <?php echo empty($available_sizes) ? 'disabled' : ''; ?>>
            <span><?php echo !empty($available_sizes) ? 'Add to Cart' : 'Out of Stock'; ?></span>
        </button>
      </div>
    </div>
    <div class="similar-products-container">
      <h1 class="header-similar-products">Similar Products</h1>
      <?php include "../components/card-carousel.php" ?>
    </div>
  </main>
  <!-- Footer -->
  <?php include "../components/footer.php" ?>
  <script>
    function addToCart(name, price) {
      const selectedSize = document.querySelector('.size-select').value;
      const selectedQuantity = parseInt(document.querySelector('.quantity-input').value);

      fetch("../utils/cart/add-to-cart.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            name: name,
            price: price,
            quantity: selectedQuantity,
            size: selectedSize
          }),
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert("Product added to cart!");
          } else {
            alert("Failed to add product to cart.");
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("An error occurred while adding to cart.");
        });
    }
  </script>
  <script src="../scripts/pages/prod-desc.js"></script>
</body>

</html>

<?php $db->close(); ?>