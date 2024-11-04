<?php
// Import DB connection and session starting
require_once "../utils/auth/dbconnect.php";
require_once "../utils/auth/session.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
  // Verify CSRF token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
  }

  // Sanitize inputs
  $firstName = trim(htmlspecialchars($_POST['firstName']));
  $lastName = trim(htmlspecialchars($_POST['lastName']));
  $username = trim(htmlspecialchars($_POST['username']));
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address.');</script>";
    header('Location: ./auth.php');
    exit;
  }

  // Debug password requirements separately
  $has_lowercase = preg_match('/[a-z]/', $password);
  $has_uppercase = preg_match('/[A-Z]/', $password);
  $has_number = preg_match('/\d/', $password);
  $has_special = preg_match('/[^A-Za-z0-9]/', $password);
  $is_long_enough = strlen($password) >= 8;

  // Detailed validation with specific messages
  if (!$is_long_enough || !$has_lowercase || !$has_uppercase || !$has_number || !$has_special) {
    $error_message = "Password must contain:\n";
    if (!$is_long_enough) $error_message .= "- At least 8 characters\n";
    if (!$has_lowercase) $error_message .= "- At least one lowercase letter\n";
    if (!$has_uppercase) $error_message .= "- At least one uppercase letter\n";
    if (!$has_number) $error_message .= "- At least one number\n";
    if (!$has_special) $error_message .= "- At least one special character\n";

    echo "<script>alert(" . json_encode($error_message) . ");</script>";
    header('Location: ./auth.php');
    exit;
  }

  // Hash the password for security
  $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

  // Check if user already exists
  $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
  if (!$stmt) {
    echo "<script>alert('Database error occurred. Please try again.');</script>";
    exit;
  }

  $stmt->bind_param('ss', $email, $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "<script>alert('The email address or username is already registered!');</script>";
    $stmt->close();
    header('Location: ./auth.php');
    exit;
  }
  $stmt->close();

  // Insert the new user
  $stmt = $db->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)");
  if (!$stmt) {
    echo "<script>alert('Database error occurred. Please try again.');</script>";
    exit;
  }

  $stmt->bind_param("sssss", $firstName, $lastName, $username, $email, $password_hash);
  if ($stmt->execute()) {
    $user_id = $db->insert_id;

    // Start session and set session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $email;
    $_SESSION['username'] = $username;

    // Initialize empty cart
    $_SESSION['cart'] = array();

    echo "<script>alert('Registration successful!'); window.location.href='../../pages/home.php';</script>";
    $stmt->close();
    exit;
  } else {
    echo "<script>alert('Registration failed: " . $db->error . "');</script>";
  }
  $stmt->close();
}

// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  // Verify CSRF token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed");
  }

  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  // Validate inputs
  if (empty($email) || empty($password)) {
    echo "<script>alert('Please fill in all fields!');</script>";
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address!');</script>";
    exit;
  }

  // Query user from database
  $stmt = $db->prepare("SELECT id, email, username, password FROM users WHERE email = ?");
  if (!$stmt) {
    echo "<script>alert('Database error occurred. Please try again.');</script>";
    exit;
  }

  $stmt->bind_param('s', $email);

  if (!$stmt->execute()) {
    echo "<script>alert('An error occurred during login. Please try again.');</script>";
    $stmt->close();
    exit;
  }

  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Start new session
      session_regenerate_id(true);

      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_email'] = $user['email'];
      $_SESSION['username'] = $user['username'];

      // Initialize cart
      $_SESSION['cart'] = array();

      // Load cart from database
      // load_cart_from_db($user['id']);

      $stmt->close();
      header('Location: ./home.php');
      exit;
    }
  }

  // Invalid credentials
  echo "<script>alert('Invalid email or password.');</script>";
  $stmt->close();
}

// Close DB connection
mysqli_close($db);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footscape | Login/Signup</title>
  <link rel="shortcut icon" href="../assets/logos/favicon-logo.png" type="image/png" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <link rel="stylesheet" href="../styles/pages/auth.css">
  <!-- Components CSS -->
  <link rel="stylesheet" href="../styles/components/navbar.css" />
  <link rel="stylesheet" href="../styles/components/footer.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
  <header class="navbar">
    <!-- Logo -->
    <a href="../pages/home.php">
      <img src="../assets/logos/white-logo.svg" alt="FootScape" class="navbar-logo">
    </a>
    <div class="link-wrapper">
      <!-- Navigation Links -->
      <a href="../pages/catalog.php" class="nav-link">Explore</a>
    </div>
  </header>

  <div class="container" id="container">

    <div class="form-container sign-up">
      <form method="POST" id="register-form" action="">
        <h1>Create Account</h1>
        <span class="subheading">Please enter your details below</span>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <input type="text" name="firstName" id="firstName" placeholder="First Name" required maxlength="50">
        <input type="text" name="lastName" id="lastName" placeholder="Last Name" required maxlength="50">
        <input type="text" name="username" id="username" placeholder="Username" required maxlength="20">
        <input type="email" name="email" placeholder="Email" required maxlength="50">
        <input type="password" name="password" placeholder="Password" required maxlength="500">

        <button type="submit" name="register" id="register">Sign Up</button>
      </form>
    </div>

    <div class="form-container sign-in">
      <form method="POST" id="login-form" action="">
        <h1>Sign In</h1>
        <span class="subheading">Please enter your details below</span>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login" id="login">Sign In</button>
      </form>
    </div>

    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <p><img src="../assets/logos/favicon-logo.png" height="80" width="80" /></p>
          <h1>Welcome Back !</h1>
          <p>Enter your personal details to use all of site features</p>
          <button class="hidden" id="loginToggle">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <p><img src="../assets/logos/favicon-logo.png" height="80" width="80" /></p>
          <h1>Hello, Friend !</h1>
          <p>Register with your personal details to use all of site features</p>
          <button class="hidden" id="registerToggle">Sign Up</button>
        </div>
      </div>
    </div>

  </div>
  <script src="../scripts/pages/auth.js"></script>
</body>

</html>