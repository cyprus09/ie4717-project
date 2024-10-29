<?php
// Import DB connection and session starting
require_once "../utils/auth/dbconnect.php";
require_once "../utils/auth/session.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
  // Sanitize inputs
  $firstName = trim($_POST['firstName']);
  $lastName = trim($_POST['lastName']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Debug password requirements separately
  $has_lowercase = preg_match('/[a-z]/', $password);
  $has_uppercase = preg_match('/[A-Z]/', $password);
  $has_number = preg_match('/\d/', $password);
  $is_long_enough = strlen($password) >= 8;

  // Detailed validation with specific messages
  if (!$is_long_enough) {
    echo "<script>alert('Password must be at least 8 characters long.');</script>";
    header('Location: ./auth.php');
    exit;
  }
  if (!$has_lowercase) {
    echo "<script>alert('Password must contain at least one lowercase letter.');</script>";
    header('Location: ./auth.php');
    exit;
  }
  if (!$has_uppercase) {
    echo "<script>alert('Password must contain at least one uppercase letter.');</script>";
    header('Location: ./auth.php');
    exit;
  }
  if (!$has_number) {
    echo "<script>alert('Password must contain at least one number.');</script>";
    header('Location: ./auth.php');
    exit;
  }

  // Hash the password for security
  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  // Check if user already exists
  if ($query = $db->prepare("SELECT * FROM users WHERE email = ? OR username = ?")) {
    $query->bind_param('ss', $email, $username);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
      echo "<script>alert('The email address or username is already registered!');</script>";
      $query->close();
      header('Location: ./auth.php');
      exit;
    }
    $query->close();
  } else {
    echo "<script>alert('Database error occurred. Please try again.');</script>";
    exit;
  }

  // If we get here, validation passed - insert the new user
  if ($insertQuery = $db->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)")) {
    $insertQuery->bind_param("sssss", $firstName, $lastName, $username, $email, $password_hash);
    $result = $insertQuery->execute();

    if ($result) {
      echo "<script>alert('Your registration was successful!'); window.location.href='../../pages/home.php';</script>";
      $insertQuery->close();
      header('Location: ./auth.php');
      exit;
    } else {
      echo "<script>alert('Something went wrong during registration: " . $db->error . "');</script>";
    }
    $insertQuery->close();
  } else {
    echo "<script>alert('Database error occurred. Please try again.');</script>";
  }
}

// Handle user login case
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Check for empty fields
  if (empty($email)) {
    echo "<script>alert('Please enter your email!');</script>";
    exit;
  }
  if (empty($password)) {
    echo "<script>alert('Please enter your password!');</script>";
    exit;
  }

  // Basic email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Please enter a valid email address!');</script>";
    exit;
  }

  // Query user from database
  if ($query = $db->prepare("SELECT id, email, password FROM users WHERE email = ?")) {
    $query->bind_param('s', $email);

    if (!$query->execute()) {
      echo "<script>alert('An error occurred during login. Please try again.');</script>";
      $query->close();
      exit;
    }

    $result = $query->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user['password'])) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
          session_start();
        }

        // Set session variables
        $_SESSION["userid"] = $user['id'];
        $_SESSION['user'] = [
          'id' => $user['id'],
          'email' => $user['email']
        ];

        $query->close();

        // Redirect to home page
        header('Location: ./home.php');
        exit;
      } else {
        echo "<script>alert('Invalid email or password.');</script>";
      }
    } else {
      // Use generic message for security
      echo "<script>alert('Invalid email or password.');</script>";
    }
    $query->close();
  } else {
    echo "<script>alert('An error occurred during login. Please try again.');</script>";
  }
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
      <a href="#" class="nav-link">Explore</a>
    </div>
  </header>

  <div class="container" id="container">

    <div class="form-container sign-up">
      <form method="POST" id="register-form" action="">
        <h1>Create Account</h1>
        <span class="subheading">Please enter your details below</span>

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