<?php
$host = 'localhost';
$db = 'footscape_db';
$user = 'root';
$pass = '12345';

try {
  // Create a new PDO instance
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Could not connect to the database $db :" . $e->getMessage());
}

// Handle user registration
if (isset($_POST['register'])) {
  $firstName = trim($_POST['firstName']);
  $lastName = trim($_POST['lastName']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

  // Validate input
  if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
  }

  // Check if the email or username already exists
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
  $stmt->execute(['email' => $email, 'username' => $username]);
  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'Email or Username already exists.']);
    exit;
  }

  // Insert the new user into the database
  $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, password) VALUES (:firstName, :lastName, :username, :email, :password)");
  if ($stmt->execute(['firstName' => $firstName, 'lastName' => $lastName, 'username' => $username, 'email' => $email, 'password' => $password])) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
  }
}

// Handle user login
if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Validate input
  if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and Password are required.']);
    exit;
  }

  // Fetch user by email
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Verify password
  if ($user && password_verify($password, $user['password'])) {
    // Here you can set session variables or perform further actions upon successful login
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
  }
}
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
        <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
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