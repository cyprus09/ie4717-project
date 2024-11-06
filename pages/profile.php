<?php
// Import DB connection and session starting
require_once "../utils/auth/dbconnect.php";
require_once "../utils/auth/session.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// // Enable error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// retrieve user id from session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login if not logged in
    header("Location: auth.php");
    exit;
}

// query user information from database
function getUserData($userId) {
    global $db;
        
    $query = "SELECT first_name, last_name, username, email, user_mobile FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        die("Error preparing statement: " . $db->error);
    }
    
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$userData = getUserData($_SESSION['user_id']);
if (!$userData) {
    echo("Error: Unable to fetch user data");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FootScape | Profile</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/profile.css" />
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
</head>

<body>
    <!-- Navbar -->
    <?php include "../components/navbar.php" ?>

    <!-- Main Content -->
    <main class="main-content">
        <h1 class="container-header">Profile</h1>
        <div class="profile-container">
            <!-- View Mode -->
            <div id="viewMode" class="display">
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">Email</div>
                        <div class="value" id="emailValue"><?php echo htmlspecialchars($userData['email']); ?></div>
                    </div>
                </div>
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">First Name</div>
                        <div class="value" id="firstNameValue"><?php echo htmlspecialchars($userData['first_name']); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="label">Username</div>
                        <div class="value" id="usernameValue"><?php echo htmlspecialchars($userData['username']); ?></div>
                    </div>
                </div>
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">Last Name</div>
                        <div class="value" id="lastNameValue"><?php echo htmlspecialchars($userData['last_name']); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="label">Mobile Number</div>
                        <div class="value" id="mobileValue"><?php echo htmlspecialchars($userData['user_mobile']); ?></div>
                    </div>
                </div>
                
                <button class="button hover-filled-slide-right" onclick="toggleEdit()"><span>Edit Profile</span></button>
            </div>

            <!-- Edit Mode -->
            <div id="editMode" class="hidden">
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">Email</div>
                        <div class="value" id="emailValue"><?php echo htmlspecialchars($userData['email']); ?></div>
                    </div>
                </div>
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">First Name</div>
                        <input type="text" id="firstNameInput">
                    </div>
                    <div class="info-group">
                        <div class="label">Username</div>
                        <input type="text" id="usernameInput">
                    </div>
                </div>
                <div class="row-wrapper">
                    <div class="info-group">
                        <div class="label">Last Name</div>
                        <input type="text" id="lastNameInput">
                    </div>
                    <div class="info-group">
                        <div class="label">Mobile Number</div>
                        <input type="tel" id="mobileInput" pattern="[0-9]{8}">
                    </div>
                </div>
                <div id="messageContainer"></div>
                <button class="button hover-filled-slide-right" onclick="submitChanges()"><span>Submit Changes</span></button>
            </div>
        </div>
        <h1 class="container-header">My Orders</h1>
    </main>

    <!-- Footer -->
    <?php include "../components/footer.php" ?>
    <script src="../scripts/pages/profile.js"></script>
</body>

</html>