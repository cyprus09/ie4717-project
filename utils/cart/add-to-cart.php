<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Content-Type: application/json');
  echo json_encode([
      'success' => false,
      'message' => 'Please login first',
      'redirect' => '../../pages/login.php'
  ]);
  exit;
}

require_once 'cart-functions.php';
require_once '../auth/dbconnect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['price']) || !isset($data['quantity']) || !isset($data['size'])) {
  echo json_encode(['success' => false, 'message' => 'Missing required data']);
  exit;
}

$product_name = $data['name'];
$product_price = $data['price'];
$product_quantity = intval($data['quantity']);
$product_size = intval($data['size']);

// Validate inputs
if ($product_quantity <= 0 || $product_quantity > 10) {
  echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
  exit;
}

if ($product_size < 4 || $product_size > 11) {
  echo json_encode(['success' => false, 'message' => 'Invalid size']);
  exit;
}

// Initialize cart if needed
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Get product_id from database
$stmt = $db->prepare("SELECT product_id FROM products WHERE name = ? AND size = ? LIMIT 1");
$stmt->bind_param("si", $product_name, $product_size);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
  echo json_encode(['success' => false, 'message' => 'Product not found']);
  exit;
}

$product_id = $product['product_id'];

// Update session cart
$product_exists = false;
foreach ($_SESSION['cart'] as &$item) {
  if ($item['name'] === $product_name && $item['size'] === $product_size) {
    $item['quantity'] = min(10, $item['quantity'] + $product_quantity);
    $product_exists = true;
    break;
  }
}

if (!$product_exists) {
  $_SESSION['cart'][] = array(
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $product_quantity,
    'size' => $product_size,
    'product_id' => $product_id
  );
}

// Update database if user is logged in
if (isset($_SESSION['user_id'])) {
  try {
    $db->begin_transaction();

    // Check if product already exists in cart
    $stmt = $db->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Update existing cart item
      $current_quantity = $result->fetch_assoc()['quantity'];
      $new_quantity = min(10, $current_quantity + $product_quantity);

      $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
      $stmt->bind_param("iii", $new_quantity, $_SESSION['user_id'], $product_id);
    } else {
      // Insert new cart item
      $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
      $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $product_quantity);
    }

    $stmt->execute();
    $db->commit();
  } catch (Exception $e) {
    $db->rollback();
    error_log("Cart update failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
  }
}

echo json_encode(['success' => true]);
