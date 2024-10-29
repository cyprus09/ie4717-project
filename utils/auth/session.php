<?php
// Session configuration
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // If using HTTPS
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// Start session with these settings
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// If the user is already logged in, redirect them to the welcome page
if (isset($_SESSION["userid"]) && $_SESSION["userid"] === true) {
  header("Location: ../../pages/home.php");
  exit;
}
