<a class="product-card reveal" href="../pages/prod-desc.php?name=<?php echo urlencode($product['name']); ?>">
    <div class="product-img" style="background-image: url(<?php echo $product['image_url'];?>)"></div>
    <div class="product-desc-section">
        <h3 class="product-name">
            <?php 
                $name = htmlspecialchars($product['name']);
                echo (strlen($name) > 35) ? substr($name, 0, 35) . '...' : $name;
            ?>
        </h3>
        <p class="product-price">$<?php echo htmlspecialchars($product['price']); ?></p>
    </div>
</a>
