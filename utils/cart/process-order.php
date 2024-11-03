<?php
// utils/cart/process-order.php
session_start();
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
  $total_amount = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
  }
  $total_amount += 15.00; // Delivery fee
  $total_amount *= 1.10;  // Add 10% GST

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

  // Prepare statements for order items and product updates
  $stmt_order_items = $db->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

  $stmt_update_product = $db->prepare("
        UPDATE products 
        SET quantity = quantity - ? 
        WHERE product_id = ? AND quantity >= ?
    ");

  foreach ($_SESSION['cart'] as $item) {
    // Check product quantity
    $stmt_check = $db->prepare("SELECT quantity FROM products WHERE product_id = ?");
    $stmt_check->bind_param("i", $item['product_id']);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();
    $current_quantity = $row['quantity'];
    $stmt_check->close();

    if ($current_quantity < $item['quantity']) {
      throw new Exception("Insufficient stock for " . $item['name']);
    }

    // Add order item
    $stmt_order_items->bind_param(
      "iiid",
      $order_id,
      $item['product_id'],
      $item['quantity'],
      $item['price']
    );
    $stmt_order_items->execute();

    // Update product quantity
    $stmt_update_product->bind_param(
      "iii",
      $item['quantity'],
      $item['product_id'],
      $item['quantity']
    );
    $stmt_update_product->execute();
  }

  $stmt_order_items->close();
  $stmt_update_product->close();

  // Clear cart
  $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $stmt->close();

  $_SESSION['cart'] = [];

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
