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

// Function to get user's orders
function getUserOrders($userId) {
    global $db;
    
    $query = "SELECT o.id, o.total_amount, o.address, 
                     o.postal_code, o.receiver_name, o.receiver_mobile
              FROM orders o 
              WHERE o.user_id = ?
              ORDER BY o.id DESC"; // Ordering by order ID instead of date
              
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        die("Error preparing statement: " . $db->error);
    }
    
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    
    return $stmt->get_result();

    // Set security headers
    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
}

// Function to get order items
function getOrderItems($orderId) {
    global $db;
    
    $query = "SELECT oi.*, p.name as product_name, p.image_url, p.size
              FROM order_items oi
              JOIN products p ON oi.product_id = p.product_id
              WHERE oi.order_id = ?";
              
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        die("Error preparing statement: " . $db->error);
    }
    
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    
    return $stmt->get_result();
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
        <!-- Profile Section -->
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

        <!-- My Orders Section -->
        <h1 class="container-header">My Orders</h1>
        <div class="orders-container">
            <?php
            $orders = getUserOrders($_SESSION['user_id']);
            if ($orders->num_rows === 0) {
                ?>
                <div class="order-batch empty-orders">
                    <div class="empty-orders-content">
                        <p class="empty-message">No order has been made</p>
                        <a href="./home.php" class="button hover-filled-slide-right continue-shopping-btn">
                            <span>Continue Shopping</span>
                        </a>
                    </div>
                </div>
                <?php
            } else {
                while ($order = $orders->fetch_assoc()) {
                    ?>
                    <div class="order-batch">
                        <div class="order-header">
                            <h3>Order #<?php echo htmlspecialchars($order['id']); ?></h3>
                        </div>

                        <div class="order-items">
                            <?php
                            $orderItems = getOrderItems($order['id']);
                            while ($item = $orderItems->fetch_assoc()) {
                                ?>
                                <div class="order-item">
                                    <div class="order-item-image" style="background-image: url('<?php echo $item['image_url']; ?>')"></div>
                                    <div class="product-details">
                                        <div class="product-name">
                                            <?php echo htmlspecialchars($item['product_name']); ?>
                                        </div>
                                        <div class="product-price">
                                            $<?php echo number_format($item['price'], 2); ?>
                                        </div>
                                        <div class="product-size">
                                            Size: <?php echo htmlspecialchars($item['size']); ?>
                                        </div>
                                    </div>

                                    <div class="quantity">
                                        Qty: <?php echo $item['quantity']; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="delivery-info">
                            <p><strong>Delivery Details:</strong></p>
                            <p>Receiver: <?php echo htmlspecialchars($order['receiver_name']); ?></p>
                            <p>Mobile: <?php echo htmlspecialchars($order['receiver_mobile']); ?></p>
                            <p>Address: <?php echo htmlspecialchars($order['address']); ?></p>
                            <p>Postal Code: <?php echo htmlspecialchars($order['postal_code']); ?></p>
                        </div>

                        <div class="total-amount">
                            Total Amount: $<?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <?php include "../components/footer.php" ?>
    <script src="../scripts/pages/profile.js"></script>
</body>

</html>