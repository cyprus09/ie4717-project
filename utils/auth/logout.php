<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once '../cart/cart-functions.php';

$_SESSION = array();

$params = session_get_cookie_params();

setcookie(
  session_name(),
  '',
  [
    'expires' => time() - 42000,
    'path' => $params["path"],
    'domain' => $params["domain"],
    'secure' => $params["secure"],
    'httponly' => $params["httponly"],
    'samesite' => 'Strict'
  ]
);

if (isset($_SESSION['user_id'])) {
  // require_once "../utils/cart/cart-functions.php";
  // sync_cart_with_db($_SESSION['user_id']);

  // Clear all session variables
  session_unset();
  session_destroy();
}

// Clear any other application cookies if they exist
setcookie('remember_me', '', time() - 3600, '/');

if (headers_sent($file, $line)) {
  die("Headers already sent in $file on line $line");
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
header("Location: ../../pages/home.php");
exit();
