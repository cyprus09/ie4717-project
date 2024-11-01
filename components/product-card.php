<?php if (isset($product)): ?>
<a class="product-card" href="../pages/prod-desc.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
    <div class="product-img">
        <!-- Placeholder image or product image loading logic here -->
    </div>
    <div class="product-desc-section">
        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
    </div>
</a>
<?php endif; ?>
