<?php
session_start();

require_once '../auth/dbconnect.php';
require_once 'cart-functions.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index'])) {
  echo json_encode(['success' => false, 'message' => 'Missing required data']);
  exit;
}

$index = $data['index'];

if (isset($_SESSION['cart'][$index])) {
  array_splice($_SESSION['cart'], $index, 1);

  // // Load the cart
  // if (isset($_SESSION['user_id'])) {
  //   sync_cart_with_db($_SESSION['user_id']);
  // }

  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Item not found']);
}
