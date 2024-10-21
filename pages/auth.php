<?php
// Database connection
$hostname = "localhost";
$username = "root";
$password = "";
$database = "footscape_db";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to validate password
function validatePassword($password)
{
  return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password);
}

// Handling new user signup
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $username = $conn->real_escape_string($_POST['username']);
  $email = $conn->real_escape_string($_POST['email']);
  $password = $_POST['password'];

  if (strlen($first_name) < 3 || strlen($last_name) < 3 || strlen($username) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !validatePassword($password)) {
    echo "<script>alert('Please fill out all fields correctly.'); </script>";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $check_user = $conn->query("SELECT * FROM users WHERE email='$email' OR username='$username'");
    if ($check_user->num_rows > 0) {
      echo "<script>alert('Email or Username already exists!'); </script>";
    } else {
      $sql = "INSERT INTO users (first_name, last_name, username, email, password) VALUES ('$first_name', '$last_name', '$username', '$email', '$hashed_password')";
      if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful!'); </script>";
      } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
      }
    }
  }
}

// Handling existing user signin
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
  $email = $conn->real_escape_string($_POST['email']);
  $password = $_POST['password'];

  $check_user = $conn->query("SELECT * FROM users WHERE email='$email'");
  if ($check_user->num_rows > 0) {
    $user = $check_user->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      echo "<script>alert('Login Successful!');</script>";
      // todo: start a session and redirect user to the logged in page
    } else {
      echo "<script>alert('Invalid Password!');</script>";
    }
  } else {
    echo "<script>alert('No user found with this email!');</script>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footscape</title>
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
    <a href="#">
      <img src="../assets/logos/white-logo.svg" alt="FootScape" class="navbar-logo">
    </a>
    <div class="link-wrapper">
      <!-- Navigation Links -->
      <a href="#" class="nav-link">Explore</a>
    </div>
  </header>

  <div class="container" id="container">
    <div class="form-container sign-up">
      <form method="POST" id="register-form">
        <h1>Create Account</h1>
        <span>Please enter your details below</span>
        <input type="text" name="first_name" id="firstName" placeholder="First Name" required>
        <input type="text" name="last_name" id="lastName" placeholder="Last Name" required>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Sign Up</button>
      </form>
    </div>

    <div class="form-container sign-in">
      <form method="POST" id="login-form">
        <h1>Sign In</h1>
        <span>Please enter your details below</span>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Sign In</button>
      </form>
    </div>
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <p><img src="../assets/logos/favicon-logo.png" height="80" width="80" /></p>
          <h1>Welcome Back!</h1>
          <p>Enter your personal details to use all of site features</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toggle-panel toggle-right">
          <p><img src="../assets/logos/favicon-logo.png" height="80" width="80" /></p>
          <h1>Hello, Friend!</h1>
          <p>Register with your personal details to use all of site features</p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../scripts/pages/auth.js"></script>
</body>

</html>