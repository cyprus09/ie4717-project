<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['index'])) {
  echo json_encode(['success' => false, 'message' => 'Missing required data']);
  exit;
}

$index = $data['index'];

if (isset($_SESSION['cart'][$index])) {
  array_splice($_SESSION['cart'], $index, 1);
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Item not found']);
}
