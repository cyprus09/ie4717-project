<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get search parameter from URL
$searchValue = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
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
        <form class="search-bar" action="../pages/catalog.php" method="GET">
            <input type="text" name="search" class="search-input" placeholder="Search" value="<?php echo $searchValue; ?>">
            <button type="submit" class="search-submit">
                <img src="../assets/icons/utilities/search-icon.svg" alt="Submit">
            </button>
        </form>
    </div>
</header>
<script src="../scripts/components/navbar.js"></script>