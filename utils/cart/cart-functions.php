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
                p.name,
                p.price,
                p.size,
                c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
            ORDER BY c.id ASC
        ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $_SESSION['cart'] = array(
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
