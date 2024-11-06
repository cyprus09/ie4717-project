<?php
require_once "../auth/dbconnect.php";
require_once "../auth/session.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Validate First Name (alphabets and spaces only)
if (!preg_match('/^[A-Za-z\s]+$/', $data['firstName'])) {
    echo json_encode(['success' => false, 'message' => 'First Name must contain only alphabets and spaces']);
    exit;
}

// Validate Last Name (alphabets and spaces only)
if (!preg_match('/^[A-Za-z\s]+$/', $data['lastName'])) {
    echo json_encode(['success' => false, 'message' => 'Last Name must contain only alphabets and spaces']);
    exit;
}

// Validate Mobile Number (8 digits)
if (!preg_match('/^[0-9]{8}$/', $data['mobile'])) {
    echo json_encode(['success' => false, 'message' => 'Mobile Number must be exactly 8 digits']);
    exit;
}

try {
    // First check if username is unique
    $checkUsername = "SELECT id FROM users WHERE username = ? AND id != ?";
    $checkStmt = $db->prepare($checkUsername);
    
    if (!$checkStmt) {
        throw new Exception("Database error while checking username");
    }

    $checkStmt->bind_param('si', $data['username'], $_SESSION['user_id']);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username is already taken']);
        $checkStmt->close();
        exit;
    }
    $checkStmt->close();

    // If all validations pass, proceed with update
    $query = "UPDATE users SET 
             first_name = ?,
             last_name = ?,
             username = ?,
             user_mobile = ?
             WHERE id = ?";
             
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Database error occurred");
    }

    $stmt->bind_param('ssssi', 
        $data['firstName'],
        $data['lastName'],
        $data['username'],
        $data['mobile'],
        $_SESSION['user_id']
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to update profile");
    }

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
}
?>