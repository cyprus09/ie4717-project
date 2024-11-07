<?php
require_once "session.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = array(
    'isLoggedIn' => isset($_SESSION['user_id'])
);

echo json_encode($response);