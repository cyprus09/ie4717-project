<?php
session_start();

// Retrieve product details from the request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['name']) || !isset($data['price']) || !isset($data['quantity']) || !isset($data['size'])) {
  echo json_encode(['success' => false, 'message' => 'Missing required data']);
  exit;
}

$product_name = $data['name'];
$product_price = $data['price'];
$product_quantity = intval($data['quantity']);
$product_size = intval($data['size']);

// Validate quantity and size
if ($product_quantity <= 0 || $product_quantity > 10) {
  echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
  exit;
}

if ($product_size < 4 || $product_size > 11) {
  echo json_encode(['success' => false, 'message' => 'Invalid size']);
  exit;
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Check if product already exists in cart with same size
$product_exists = false;
foreach ($_SESSION['cart'] as &$item) {
  if ($item['name'] === $product_name && $item['size'] === $product_size) {
    // Update quantity instead of adding new item
    $item['quantity'] = min(10, $item['quantity'] + $product_quantity);
    $product_exists = true;
    break;
  }
}

// If product doesn't exist, add it
if (!$product_exists) {
  $_SESSION['cart'][] = [
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $product_quantity,
    'size' => $product_size
  ];
}

echo json_encode(['success' => true]);
