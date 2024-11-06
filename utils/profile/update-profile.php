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

try {
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