<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Import DB connection and session starting
    require_once "../utils/auth/dbconnect.php";
    require_once "../utils/auth/session.php";

    session_start();

    // Fetch products based on filter parameters from the POST request
    $filters = json_decode(file_get_contents("php://input"), true);
    $brandFilter = !empty($filters['brand']) ? implode("','", array_map('htmlspecialchars', $filters['brand'])) : '';
    $categoryFilter = !empty($filters['category']) ? implode("','", array_map('htmlspecialchars', $filters['category'])) : '';
    $genderFilter = !empty($filters['gender']) ? implode("','", array_map('htmlspecialchars', $filters['gender'])) : '';
    $minPrice = (float)($filters['min-price'] ?? 0);
    $maxPrice = (float)($filters['max-price'] ?? PHP_INT_MAX);

    // Prepare SQL query
    $sql = "SELECT * FROM products WHERE 
        (category IN ('$categoryFilter') OR '$categoryFilter' = '') AND
        (gender IN ('$genderFilter') OR '$genderFilter' = '') AND
        (price BETWEEN ? AND ?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("dd", $minPrice, $maxPrice);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all products
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($products);
?>
