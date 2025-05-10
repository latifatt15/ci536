<?php
session_start();
require_once 'includes/config.php';

$listings = [];

$stmt = $conn->prepare("SELECT title, price, 'condition', image_url, status FROM Listings WHERE status = 'Available' ORDER BY Created_At DESC");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $listings[] = $row;
}

$stmt->close();
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h1>Marketplace Listings</h1>
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="button-row">
        <a href="add_listing.php" class="add-listing-btn">+ Add New Listing</a>
    </div>
<?php endif; ?>
    <div class="listings-grid">
        <?php if (empty($listings)): ?>
            <p>No listings available yet.</p>
        <?php else: ?>
            <?php foreach ($listings as $item): ?>
                <div class="listing-card">
                    <?php if (!empty($item['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Item image">
                    <?php else: ?>
                        <div class="listing-placeholder">No image</div>
                    <?php endif; ?>

                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="price">£<?php echo htmlspecialchars($item['price']); ?></p>
                    <p class="condition"><?php echo htmlspecialchars($item['condition']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
