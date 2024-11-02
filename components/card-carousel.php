<div class="card-carousel-container">
  <div class="card-carousel">
    <?php
    include "../utils/auth/dbconnect.php";

    // Fetch random 8 products
    $query = "SELECT name, price FROM products ORDER BY RAND() LIMIT 8";
    $result = $db->query($query);

    if ($result->num_rows > 0): ?>
      <?php while ($product = $result->fetch_assoc()): ?>
        <?php include "../components/product-card.php"; ?>
      <?php endwhile; ?>
    <?php
    endif;

    $db->close();
    ?>
  </div>
</div>

<!-- JS Scripts -->
<script src="../scripts/components/card-carousel.js"></script>
