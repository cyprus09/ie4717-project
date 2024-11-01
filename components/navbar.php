<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="navbar">
    <!-- Logo -->
    <a href="../pages/home.php">
        <img src="../assets/logos/white-logo.svg" alt="FootScape" class="navbar-logo">
    </a>
    <div class="link-wrapper">
        <!-- Navigation Links -->
        <a href="../pages/catalog.php" class="nav-link">Explore</a>
        <a href="../pages/shopping-cart.php" class="nav-link">Cart</a>
        <!-- In navbar.php -->
        <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] > 0): ?>
            <a href="../utils/auth/logout.php" class="nav-link">Logout</a>
        <?php else: ?>
            <a href="../pages/auth.php" class="nav-link">Login</a>
        <?php endif; ?>
        <!-- Search Bar -->
        <form class="search-bar">
            <input type="text" class="search-input" placeholder="Search">
            <a href="#"><img class="search-submit" src="../assets/icons/utilities/search-icon.svg" alt="Submit"></a>
        </form>
    </div>
</header>