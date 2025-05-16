<?php
session_start();
require_once 'includes/config.php';

$user_first_name = '';

// Get user's name if logged in
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT fName FROM Users WHERE User_ID = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($user_first_name);
    $stmt->fetch();
    $stmt->close();
}
?>

<?php
// Example data (in real use, fetch from your database)
$studentSpotlight = [
    'name' => 'Alex Johnson',
    'quote' => 'I sold my old bike in less than 24 hours on Buy Bright. Super easy!',
    'image' => 'images/alex.jpg'
];

$trendingItems = [
    ['title' => 'Textbooks and Course Materials', 'image' => 'images/textbooks.jpg'],
    ['title' => 'Electronics', 'image' => 'images/electronics.jpg'],
    ['title' => 'Furniture and Accom essentials', 'image' => 'images/furniture.jpg'],
    ['title' => 'Clothing and Accessories', 'image' => 'images/clothing.jpg'],
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style2.css">
    <?php include 'includes/header.php'; ?>
</head>
    
<main class="container">
    <body>
    
    <?php if (!empty($user_first_name)): ?>
        <h1>Welcome back, <?php echo htmlspecialchars($user_first_name); ?>!</h1>
    <?php else: ?>
        <h1>Welcome to Buy Bright</h1>
    <?php endif; ?>
    
    <div class="info-card">
        <h2>How Buy Bright Works</h2>
        <p>Welcome to the student marketplace built just for you!</p>
        <ul>
            <li>📦 <strong>List items</strong> you want to sell — books, electronics, clothes, and more.</li>
            <li>🛍️ <strong>Browse items</strong> posted by other students.</li>
            <li>📨 <strong>Message buyers or sellers</strong> through your profile.</li>
            <li>👤 <strong>Manage your listings</strong> and profile from your dashboard.</li>
        </ul>
        <p>Everything is student-only — safe, simple, and local. Buy right, Buy Bright!</p>
    </div>
    
    <div class="container">

        <!-- Student Spotlight -->
        <section class="spotlight">
            <h2>🌟 Student Spotlight</h2>
            <div class="card">
                <img src="<?= $studentSpotlight['image'] ?>" alt="<?= $studentSpotlight['name'] ?>">
                <div class="info">
                    <h3><?= $studentSpotlight['name'] ?></h3>
                    <p>“<?= $studentSpotlight['quote'] ?>”</p>
                </div>
            </div>
        </section>

        <!-- Trending Listings -->
        <section class="trending">
            <h2>🔥 Most frequently bought items</h2>
            <div class="trending-grid">
                <?php foreach ($trendingItems as $item): ?>
                    <div class="trend-card">
                        <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
                        <p><?= $item['title'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </body>
</main>

<?php include 'includes/footer.php'; ?>
<footer><p><a href="https://www.vecteezy.com/free-vector/book">Book Vectors by Vecteezy</a>
<a href="https://www.vecteezy.com/free-vector/devices">Devices Vectors by Vecteezy</a>
<a href="https://www.vecteezy.com/free-vector/board">Board Vectors by Vecteezy</a>
<a href="https://www.vecteezy.com/free-vector/outfit">Outfit Vectors by Vecteezy</a></p></footer>

