<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once '../auth/dbconnect.php';
require_once 'cart-functions.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index'])) {
  echo json_encode(['success' => false, 'message' => 'Missing required data']);
  exit;
}

$index = $data['index'];

if (isset($_SESSION['cart'][$index])) {
  $product_id = $_SESSION['cart'][$index]['product_id'];

  // Remove from database if user is logged in
  if (isset($_SESSION['user_id'])) {
    try {
      $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
      $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
      $stmt->execute();
    } catch (Exception $e) {
      error_log("Remove item failed: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Database error']);
      exit;
    }
  }

  // Remove from session cart
  array_splice($_SESSION['cart'], $index, 1);

  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Item not found']);
}
