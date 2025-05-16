<?php
session_start();
require_once 'includes/config.php';

if (isset($_GET['updated'])) {
    echo "<p class='success'>Profile updated successfully!</p>";
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT fName, sName, dob, Email, University, Profile_Picture, push_notifications FROM Users WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fname, $sname, $dob, $email, $university, $profile_picture, $push_notifications);
$stmt->fetch();
$stmt->close();
?>

<?php include 'includes/header.php'; ?>

<main class="profile-container">
    <h1>Profile Page</h1>

    <div class="profile-info">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($fname . ' ' . $sname); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob ?? 'Not provided'); ?></p>
        <p><strong>University:</strong> <?php echo htmlspecialchars($university); ?></p>
    </div>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <h2>Edit Your Details</h2>
        <label>First Name: <input type="text" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required></label>
        <label>Surname: <input type="text" name="sname" value="<?php echo htmlspecialchars($sname); ?>" required></label>
        <label>Date of Birth: <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>"></label>
        <label>Update Profile Picture:<input type="file" name="profile_picture" accept="image/*">
</label>


        <h2>Settings</h2>
        
        <label>
            Push Notifications:
            <input type="checkbox" name="push_notifications" <?php if ($push_notifications) echo 'checked'; ?>>
        </label>

        <button type="submit">Save Changes</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>