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

<?php include 'includes/header.php'; ?>

<main class="container">
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
    
</main>

<?php include 'includes/footer.php'; ?>
