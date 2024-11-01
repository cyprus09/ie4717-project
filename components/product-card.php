<a class="product-card" href="../pages/prod-desc.php?name=<?php echo urlencode($product_name); ?>&price=<?php echo urlencode($product_price); ?>">
    <div class="product-img"></div>
    <div class="product-desc-section">
        <h3 class="product-name"><?php echo htmlspecialchars($product_name); ?></h3>
        <p class="product-price">$<?php echo htmlspecialchars($product_price); ?></p>
    </div>
</a>