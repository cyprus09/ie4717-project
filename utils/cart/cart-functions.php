<?php
require_once '../auth/dbconnect.php';

function sync_cart_with_db($user_id)
{
  global $db;

  error_log("Syncing cart for user: " . $user_id);

  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    error_log("Empty cart - nothing to sync");
    return;
  }

  try {
    $db->begin_transaction();

    // Clear existing cart items for user
    $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Insert current session cart items
    $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
      $stmt->bind_param(
        "iii",
        $user_id,
        $item['product_id'],
        $item['quantity']
      );
      $stmt->execute();
      error_log("Synced item: Product ID " . $item['product_id'] . " Quantity " . $item['quantity']);
    }
    $stmt->close();

    $db->commit();
    error_log("Cart sync completed successfully");
  } catch (Exception $e) {
    $db->rollback();
    error_log("Cart sync failed: " . $e->getMessage());
    throw $e;
  }
}

function load_cart_from_db($user_id)
{
  global $db;

  error_log("Loading cart for user: " . $user_id);

  try {
    $stmt = $db->prepare("
            SELECT c.quantity, p.product_id, p.name, p.price, p.size
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $_SESSION['cart'] = [];

    while ($row = $result->fetch_assoc()) {
      $_SESSION['cart'][] = [
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'size' => $row['size']
      ];
      error_log("Loaded item: Product ID " . $row['product_id'] . " Quantity " . $row['quantity']);
    }

    $stmt->close();
    error_log("Cart load completed. Total items: " . count($_SESSION['cart']));
  } catch (Exception $e) {
    error_log("Cart load failed: " . $e->getMessage());
    throw $e;
  }
}
