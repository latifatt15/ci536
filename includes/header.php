<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Bright</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ci536/css/style2.css">
    <link rel="icon" href="images/logo.ico">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
        <img src ="images/logoNoName.png" class="logo" alt="BuyBright Logo">
        <img src ="images/logoName.png" class="logoName" alt="BuyBright Logo Name">
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
  <div class="logout-container">
    <a href="logout.php" class="logout-btn">Log out</a>
  </div>
<?php endif; ?>

    </header>
    <nav class="nav-links">
        <ul class="nav-menu">
            <li><a href="/ci536/index.php">Home</a></li>
            <li><a href="listings.php">Listings</a></li>
            <li><a href="social.php">Social</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="login.php">Log In</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </nav>
