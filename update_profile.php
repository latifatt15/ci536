<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$fname = trim($_POST['fname']);
$sname = trim($_POST['sname']);
$dob = isset($_POST['dob']) ? trim($_POST['dob']) : null;
$dob = empty($dob) ? null : $dob;
$push_notifications = isset($_POST['push_notifications']) ? 1 : 0;

$uploadFile = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = uniqid('user_', true) . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $uploadFile = $uploadDir . basename($imageName);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $validTypes)) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    } elseif ($_FILES['profile_picture']['size'] > 5000000) {
        die("File is too large. Max 5MB.");
    } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
        die("Failed to upload profile picture.");
    }
}

// Update query
if ($uploadFile) {
    $stmt = $conn->prepare("UPDATE Users SET fName = ?, sName = ?, dob = ?, push_notifications = ?, Profile_Picture = ? WHERE User_ID = ?");
    $stmt->bind_param("sssisi", $fname, $sname, $dob, $push_notifications, $uploadFile, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE Users SET fName = ?, sName = ?, dob = ?, push_notifications = ? WHERE User_ID = ?");
    $stmt->bind_param("sssii", $fname, $sname, $dob, $push_notifications, $user_id);
}

if ($stmt->execute()) {
    header("Location: profile.php?updated=1");
} else {
    echo "Error updating profile.";
}

$stmt->close();
?>


