<?php
require_once 'includes/config.php';
include 'includes/header.php';

if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

    // Fetch user's name
    $stmt = $conn->prepare("SELECT fName, sName FROM Users WHERE User_ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo "<h2>Listings by " . htmlspecialchars($user['fName'] . ' ' . $user['sName']) . "</h2>";

        // Fetch listings
        $stmt = $conn->prepare("SELECT title, description, price FROM Listings WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $listings = $stmt->get_result();

        if ($listings->num_rows > 0) {
            while ($listing = $listings->fetch_assoc()) {
                echo "<div class='listing'>";
                echo "<h3>" . htmlspecialchars($listing['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($listing['description']) . "</p>";
                echo "<strong>£" . htmlspecialchars($listing['price']) . "</strong>";
                echo "</div>";
            }
        } else {
            echo "<p>No listings found for this user.</p>";
        }
    } else {
        echo "<p>User not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p>No user selected.</p>";
}

include 'includes/footer.php';
?>

