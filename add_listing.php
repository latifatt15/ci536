<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    $image_paths = [];

    $target_dir = "uploads/";
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        if (!empty($_FILES["images"]["name"][$key])) {
            $image_name = basename($_FILES["images"]["name"][$key]);
            $target_file = $target_dir . time() . "_" . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (in_array($imageFileType, $allowed_types)) {
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                    $image_paths[] = $target_file;
                }
            }
        }
    }

    $image_paths_str = implode(",", $image_paths);

    $stmt = $conn->prepare("INSERT INTO Listings (User_ID, Title, Description, Price, Category, image_url, status) VALUES (?, ?, ?, ?, ?, ?, 'Available')");
    $stmt->bind_param("issdss", $user_id, $title, $description, $price, $category, $image_paths_str);

    if ($stmt->execute()) {
        header("Location: listings.php");
        exit();
    } else {
        $error = "Error adding listing.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <h1>Add a Listing</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="add_listing.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Item Title" required>
        <textarea name="description" placeholder="Item Description" rows="4" required></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price (£)" required>

        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Books">Books</option>
            <option value="Electronics">Electronics</option>
            <option value="Furniture">Furniture</option>
            <option value="Clothing">Clothing</option>
            <option value="Other">Other</option>
        </select>

        <input type="file" name="images[]" id="imageUpload" accept="image/*" multiple>
<div id="imagePreviewContainer"></div>

<!-- Image popup for preview -->
<div id="imagePopup">
    <span id="closePopup">&times;</span>
    <img id="popupImage" src="" alt="Full Image">
</div>

        <button type="submit">Post Listing</button>
    </form>

    <script src="js/add-listing.js"></script>

</main>

<?php include 'includes/footer.php'; ?>


