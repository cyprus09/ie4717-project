<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../auth/dbconnect.php';
require_once 'cart-functions.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$index = $data['index'];
$quantity = intval($data['quantity']);

if ($quantity <= 0 || $quantity > 10) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit;
}

if (isset($_SESSION['cart'][$index])) {
    // Update session cart
    $_SESSION['cart'][$index]['quantity'] = $quantity;

    // Update database if user is logged in
    if (isset($_SESSION['user_id'])) {
        try {
            $product_id = $_SESSION['cart'][$index]['product_id'];

            $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $_SESSION['user_id'], $product_id);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Quantity update failed: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error']);
            exit;
        }
    }

    echo json_encode([
        'success' => true,
        'newQuantity' => $quantity,
        'newTotal' => number_format($quantity * $_SESSION['cart'][$index]['price'], 2)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
}
