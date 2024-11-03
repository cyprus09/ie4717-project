<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once '../auth/dbconnect.php';

if (
  $_SERVER['REQUEST_METHOD'] !== 'POST' ||
  !isset($_SESSION['csrf_token']) ||
  !isset($_POST['csrf_token']) ||
  $_SESSION['csrf_token'] !== $_POST['csrf_token']
) {
  header("Location: ../../pages/shopping-cart.php");
  exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
  $_SESSION['error'] = "Invalid order request";
  header("Location: ../../pages/shopping-cart.php");
  exit;
}

// Start transaction
$db->begin_transaction();

try {
  // Calculate total amount
  $subtotal = 0;
  foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
  }
  $delivery_fee = 15.00;
  $gst = ($subtotal + $delivery_fee) * 0.10;
  $total_amount = $subtotal + $delivery_fee + $gst;

  // Create order
  $stmt = $db->prepare("
        INSERT INTO orders (user_id, total_amount, address, postal_code, receiver_name, receiver_mobile)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

  $stmt->bind_param(
    "idssss",
    $_SESSION['user_id'],
    $total_amount,
    $_POST['address'],
    $_POST['postal_code'],
    $_POST['full_name'],
    $_POST['mobile']
  );
  $stmt->execute();
  $order_id = $db->insert_id;
  $stmt->close();

  // Process each item in cart
  foreach ($_SESSION['cart'] as $item) {
    // Check product availability
    $stmt = $db->prepare("
            SELECT quantity 
            FROM products 
            WHERE product_id = ? 
            FOR UPDATE
        ");
    $stmt->bind_param("i", $item['product_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product || $product['quantity'] < $item['quantity']) {
      throw new Exception("Insufficient stock for product: " . $item['name']);
    }

    // Add order item
    $stmt = $db->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
    $stmt->bind_param(
      "iiid",
      $order_id,
      $item['product_id'],
      $item['quantity'],
      $item['price']
    );
    $stmt->execute();
    $stmt->close();

    // Update product quantity
    $stmt = $db->prepare("
            UPDATE products 
            SET quantity = quantity - ? 
            WHERE product_id = ?
        ");
    $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    $stmt->execute();
    $stmt->close();
  }

  // Clear user's cart
  $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $stmt->close();

  // Clear session cart
  $_SESSION['cart'] = array();

  $db->commit();
  $_SESSION['success'] = "Order placed successfully!";
  header("Location: ../../pages/home.php");
  exit;
} catch (Exception $e) {
  $db->rollback();
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../../pages/shopping-cart.php");
  exit;
}
