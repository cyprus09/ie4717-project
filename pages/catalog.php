<?php
// Import DB connection and session starting
require_once "../utils/auth/dbconnect.php";
require_once "../utils/auth/session.php";

session_start();

// Retrieve filters from the URL parameters
$brand = isset($_GET['brand']) ? explode(',', strtolower($_GET['brand'])) : [];
$category = isset($_GET['category']) ? explode(',', strtolower($_GET['category'])) : [];
$gender = isset($_GET['gender']) ? explode(',', strtolower($_GET['gender'])) : [];
$minPrice = isset($_GET['min-price']) ? floatval($_GET['min-price']) : null;
$maxPrice = isset($_GET['max-price']) ? floatval($_GET['max-price']) : null;

// Build the SQL query based on filters
$query = "SELECT product_id, name, brand, category, gender, price FROM products WHERE 1=1";
$params = [];

if (!empty($brand)) {
    $placeholders = implode(',', array_fill(0, count($brand), '?'));
    $query .= " AND LOWER(brand) IN ($placeholders)";
    $params = array_merge($params, $brand);
}

if (!empty($category)) {
    $placeholders = implode(',', array_fill(0, count($category), '?'));
    $query .= " AND LOWER(category) IN ($placeholders)";
    $params = array_merge($params, $category);
}

if (!empty($gender)) {
    $placeholders = implode(',', array_fill(0, count($gender), '?'));
    $query .= " AND LOWER(gender) IN ($placeholders)";
    $params = array_merge($params, $gender);
}

if ($minPrice !== null) {
    $query .= " AND price >= ?";
    $params[] = $minPrice;
}

if ($maxPrice !== null) {
    $query .= " AND price <= ?";
    $params[] = $maxPrice;
}

// Group products based on product name
$query .= " GROUP BY name";

// Prepare the SQL query
$stmt = $db->prepare($query);

// Check if the statement was prepared successfully
if (!$stmt) {
    die("Error preparing statement: " . $db->error);
}

// Bind parameters dynamically
if (!empty($params)) {
    // Assuming all parameters are strings; adjust as necessary
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result(); // Get the result set

// Fetch products
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

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

<body onload="populateFiltersFromURL()">
    <!-- Navbar -->
    <?php include "../components/navbar.php"; ?>
    <!-- Main Content -->
    <main class="main-content">
        <div class="sidebar">
            <h1>Filter</h1>
            <form method="get" id="filter-form" class="filter-form" oninput="updateURLParams(event)">
                <!-- Brand Checkbox Group -->
                <fieldset>
                    <legend>Brand</legend>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Nike" class="checkbox" id="Nike"><label for="Nike"><span></span>Nike</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Adidas" class="checkbox" id="Adidas"><label for="Adidas"><span></span>Adidas</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Puma" class="checkbox" id="Puma"><label for="Puma"><span></span>Puma</label></div><br>
                    <div class="checkbox-wrapper"><input type="checkbox" name="brand" value="Converse" class="checkbox" id="Converse"><label for="Converse"><span></span>Converse</label></div>
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
                        <input type="number" id="min-price" name="min-price" value="0" step="0.01">
                        -
                        <input type="number" id="max-price" name="max-price" value="1000" step="0.01">
                    </div>
                </fieldset>

                <!-- Submit Button -->
                <button type="submit" class="button hover-filled-slide-right filter-button"><span>Apply</span></button>
            </form>
        </div>
        <div class="catalog-section">
            <h1>Explore</h1>
            <div class="product-grid">
                <?php
                foreach ($products as $product) {
                    // Include product card with product data
                    include "../components/product-card.php";
                }
                ?>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <?php include "../components/footer.php"; ?>
    <script src="../scripts/pages/catalog.js"></script>
</body>
</html>