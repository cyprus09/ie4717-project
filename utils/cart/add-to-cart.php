<?php
session_start();

// Retrieve product details from the request
$data = json_decode(file_get_contents('php://input'), true);
$product_name = $data['name'];
$product_price = $data['price'];
$product_quantity = $data['quantity'];
$product_size = $data['size'];

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Add product to cart
$_SESSION['cart'][] = [
  'name' => $product_name,
  'price' => $product_price,
  'quantity' => $product_quantity,
  'size' => $product_size
];

echo json_encode(['success' => true]);
