<div class=".card-carousel-container">
  <div class="card-carousel">
    <?php
    include "../utils/auth/dbconnect.php";

    $query = "SELECT name, price FROM products ORDER BY RAND() LIMIT 8";
    $result = $db->query($query);

    if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
        $product_name = $row['name'];
        $product_price = $row['price'];
        ?>
        <?php include "../components/product-card.php"?>
      <?php endwhile; ?>
    <?php
    endif;

    $db->close();
    ?>
  </div>
</div>

<!-- JS Scripts -->
<script src="../scripts/components/card-carousel.js"></script>