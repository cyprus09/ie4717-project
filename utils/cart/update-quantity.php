<?php
// utils/cart/update-quantity.phpsession_start();

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
    $_SESSION['cart'][$index]['quantity'] = $quantity;
    echo json_encode([
        'success' => true,
        'newQuantity' => $quantity,
        'newTotal' => number_format($quantity * $_SESSION['cart'][$index]['price'], 2)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
}