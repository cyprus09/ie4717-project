<?php
require_once '../auth/dbconnect.php';

function load_cart_from_db($user_id)
{
  global $db;

  // Reset cart array
  $_SESSION['cart'] = array();

  try {
    $stmt = $db->prepare("
            SELECT 
                p.product_id,
                p.name,
                p.price,
                p.size,
                c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
            ORDER BY c.id ASC
        ");

    if (!$stmt) {
      error_log("Failed to prepare statement: " . $db->error);
      return false;
    }

    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
      error_log("Failed to execute statement: " . $stmt->error);
      $stmt->close();
      return false;
    }

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $_SESSION['cart'][] = array(
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'size' => $row['size'],
        'quantity' => $row['quantity']
      );
    }

    $stmt->close();
    return true;
  } catch (Exception $e) {
    error_log("Cart load failed: " . $e->getMessage());
    return false;
  }
}